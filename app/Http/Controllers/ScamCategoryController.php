<?php

namespace App\Http\Controllers;

use App\Models\ScamCategory;
use App\Http\Requests\StoreScamCategoryRequest;
use App\Http\Requests\UpdateScamCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ScamCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ScamCategory::class);

        // Only admins can see all categories
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = ScamCategory::withCount('reports');

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_fr', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(20)->withQueryString();

        return Inertia::render('admin/categories/index', [
            'categories' => $categories,
            'filters' => $request->only(['is_active', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', ScamCategory::class);

        return Inertia::render('admin/categories/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScamCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = ScamCategory::create($validated);

        return redirect()->route('categories.index')
            ->with('success', __('Scam category created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ScamCategory $scamCategory)
    {
        Gate::authorize('view', $scamCategory);

        $scamCategory->loadCount('reports');

        return Inertia::render('admin/categories/show', [
            'category' => $scamCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScamCategory $scamCategory)
    {
        Gate::authorize('update', $scamCategory);

        return Inertia::render('admin/categories/edit', [
            'category' => $scamCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScamCategoryRequest $request, ScamCategory $scamCategory)
    {
        $validated = $request->validated();

        $scamCategory->update($validated);

        return redirect()->route('categories.index')
            ->with('success', __('Scam category updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScamCategory $scamCategory)
    {
        Gate::authorize('delete', $scamCategory);

        // Check if category has reports
        if ($scamCategory->reports()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete category with existing reports. Please reassign them first.'));
        }

        $scamCategory->delete();

        return redirect()->route('categories.index')
            ->with('success', __('Scam category deleted successfully.'));
    }

    /**
     * Toggle category active status.
     */
    public function toggleActive(ScamCategory $scamCategory)
    {
        Gate::authorize('update', $scamCategory);

        $scamCategory->update([
            'is_active' => !$scamCategory->is_active,
        ]);

        return redirect()->back()
            ->with('success', __('Category status updated successfully.'));
    }
}
