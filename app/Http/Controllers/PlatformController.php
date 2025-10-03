<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Http\Requests\StorePlatformRequest;
use App\Http\Requests\UpdatePlatformRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Platform::class);

        // Only admins can see all platforms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Platform::withCount('reports');

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $platforms = $query->orderBy('name')->paginate(20)->withQueryString();

        return Inertia::render('admin/platforms/index', [
            'platforms' => $platforms,
            'filters' => $request->only(['is_active', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Platform::class);

        return Inertia::render('admin/platforms/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatformRequest $request)
    {
        $validated = $request->validated();
        $validated['created_at'] = now();

        $platform = Platform::create($validated);

        return redirect()->route('platforms.index')
            ->with('success', __('Platform created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        Gate::authorize('view', $platform);

        $platform->loadCount('reports');

        return Inertia::render('admin/platforms/show', [
            'platform' => $platform,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Platform $platform)
    {
        Gate::authorize('update', $platform);

        return Inertia::render('admin/platforms/edit', [
            'platform' => $platform,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatformRequest $request, Platform $platform)
    {
        $validated = $request->validated();

        $platform->update($validated);

        return redirect()->route('platforms.index')
            ->with('success', __('Platform updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        Gate::authorize('delete', $platform);

        // Check if platform has reports
        if ($platform->reports()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete platform with existing reports. Please reassign them first.'));
        }

        $platform->delete();

        return redirect()->route('platforms.index')
            ->with('success', __('Platform deleted successfully.'));
    }

    /**
     * Toggle platform active status.
     */
    public function toggleActive(Platform $platform)
    {
        Gate::authorize('update', $platform);

        $platform->update([
            'is_active' => !$platform->is_active,
        ]);

        return redirect()->back()
            ->with('success', __('Platform status updated successfully.'));
    }
}
