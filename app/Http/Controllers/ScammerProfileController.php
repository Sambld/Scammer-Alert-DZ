<?php

namespace App\Http\Controllers;

use App\Models\ScammerProfile;
use App\Http\Requests\StoreScammerProfileRequest;
use App\Http\Requests\UpdateScammerProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ScammerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ScammerProfile::class);

        $query = ScammerProfile::withCount('reports');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_numbers', 'like', "%{$search}%")
                    ->orWhere('social_handles', 'like', "%{$search}%");
            });
        }

        // Filter by risk level
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        $profiles = $query->orderByDesc('report_count')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('scammers/index', [
            'profiles' => $profiles,
            'filters' => $request->only(['search', 'risk_level']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ScammerProfile $scammerProfile)
    {
        Gate::authorize('view', $scammerProfile);

        $scammerProfile->load([
            'reports' => function ($query) {
                $query->where('status', 'verified')
                    ->with(['category', 'platform'])
                    ->withCount(['comments', 'upvotes', 'downvotes'])
                    ->latest();
            },
        ]);

        return Inertia::render('scammers/show', [
            'profile' => $scammerProfile,
        ]);
    }

    /**
     * Note: ScammerProfiles are automatically created/updated by the system
     * based on reports. Manual CRUD operations are typically not needed,
     * but moderators can update profiles if necessary.
     */

    /**
     * Update the specified resource (moderator only).
     */
    public function update(UpdateScammerProfileRequest $request, ScammerProfile $scammerProfile)
    {
        if (!auth()->user()->isModerator()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();
        $scammerProfile->update($validated);

        return redirect()->back()
            ->with('success', __('Scammer profile updated successfully.'));
    }

    /**
     * Remove the specified resource (admin only).
     */
    public function destroy(ScammerProfile $scammerProfile)
    {
        Gate::authorize('delete', $scammerProfile);

        $scammerProfile->delete();

        return redirect()->route('scammers.index')
            ->with('success', __('Scammer profile deleted successfully.'));
    }
}
