<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ScamCategory;
use App\Models\Platform;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Report::class);

        $query = Report::with(['user', 'category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by platform
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('scammer_name', 'like', "%{$search}%")
                    ->orWhere('scammer_phone', 'like', "%{$search}%")
                    ->orWhere('scammer_social_handle', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reports = $query->paginate(20)->withQueryString();

        return Inertia::render('reports/index', [
            'reports' => $reports,
            'filters' => $request->only(['status', 'category_id', 'platform_id', 'search', 'sort_by', 'sort_order']),
            'categories' => ScamCategory::where('is_active', true)->get(),
            'platforms' => Platform::where('is_active', true)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Report::class);

        return Inertia::render('reports/create', [
            'categories' => ScamCategory::where('is_active', true)->get(),
            'platforms' => Platform::where('is_active', true)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // Add user_id and metadata
        $validated['user_id'] = auth()->id();
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();
        $validated['status'] = 'pending';

        $report = Report::create($validated);

        // Handle media uploads if present
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('reports/' . $report->id, 'public');
                $report->media()->create([
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'original_filename' => $file->getClientOriginalName(),
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('reports.show', $report)
            ->with('success', __('Report created successfully. It will be reviewed by our moderation team.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        Gate::authorize('view', $report);

        // Increment views count
        $report->increment('views_count');

        $report->load([
            'user',
            'category',
            'platform',
            'moderator',
            'media',
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with('user', 'replies.user')->latest();
            },
        ]);

        // Check if current user has voted
        $userVote = null;
        if (auth()->check()) {
            $userVote = $report->votes()
                ->where('user_id', auth()->id())
                ->first();
        }

        return Inertia::render('reports/show', [
            'report' => $report,
            'userVote' => $userVote,
            'canModerate' => Gate::allows('moderate', $report),
            'canUpdate' => Gate::allows('update', $report),
            'canDelete' => Gate::allows('delete', $report),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        Gate::authorize('update', $report);

        $report->load(['media']);

        return Inertia::render('reports/edit', [
            'report' => $report,
            'categories' => ScamCategory::where('is_active', true)->get(),
            'platforms' => Platform::where('is_active', true)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        $validated = $request->validated();

        $report->update($validated);

        // Handle new media uploads if present
        if ($request->hasFile('media')) {
            $existingMediaCount = $report->media()->count();
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('reports/' . $report->id, 'public');
                $report->media()->create([
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'original_filename' => $file->getClientOriginalName(),
                    'sort_order' => $existingMediaCount + $index,
                ]);
            }
        }

        return redirect()->route('reports.show', $report)
            ->with('success', __('Report updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        Gate::authorize('delete', $report);

        // Delete associated media files
        foreach ($report->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', __('Report deleted successfully.'));
    }

    /**
     * Update the status of the report (moderator action).
     */
    public function updateStatus(Request $request, Report $report)
    {
        Gate::authorize('moderate', $report);

        $request->validate([
            'status' => ['required', 'in:pending,verified,investigating,resolved,rejected'],
            'moderator_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $report->update([
            'status' => $request->status,
            'moderator_id' => auth()->id(),
            'moderator_notes' => $request->moderator_notes ?? $report->moderator_notes,
            'moderated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', __('Report status updated successfully.'));
    }

    /**
     * Show reports for current user.
     */
    public function myReports(Request $request)
    {
        $query = Report::where('user_id', auth()->id())
            ->with(['category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reports = $query->paginate(20)->withQueryString();

        return Inertia::render('reports/my-reports', [
            'reports' => $reports,
            'filters' => $request->only(['status', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show reports pending moderation (moderator/admin only).
     */
    public function pending(Request $request)
    {
        Gate::authorize('viewAny', Report::class);

        if (!auth()->user()->isModerator()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Report::where('status', 'pending')
            ->with(['user', 'category', 'platform', 'media'])
            ->withCount(['comments', 'upvotes', 'downvotes'])
            ->orderBy('created_at', 'asc');

        $reports = $query->paginate(20);

        return Inertia::render('reports/pending', [
            'reports' => $reports,
        ]);
    }
}
