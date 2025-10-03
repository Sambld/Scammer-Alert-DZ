<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Show the search page.
     */
    public function index()
    {
        return Inertia::render('search/index');
    }

    /**
     * Search for reports by phone number.
     */
    public function searchByPhone(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        $phone = $request->phone;

        // Normalize phone number (remove spaces, dashes, etc.)
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $phone);

        $reports = Report::where('status', 'verified')
            ->where(function ($query) use ($phone, $normalizedPhone) {
                $query->where('scammer_phone', 'like', "%{$phone}%")
                    ->orWhere('scammer_phone', 'like', "%{$normalizedPhone}%");
            })
            ->with(['user', 'category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('search/results', [
            'results' => $reports,
            'searchType' => 'phone',
            'searchQuery' => $phone,
            'totalFound' => $reports->total(),
        ]);
    }

    /**
     * Search for reports by social media handle.
     */
    public function searchBySocial(Request $request)
    {
        $request->validate([
            'handle' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $handle = $request->handle;

        $reports = Report::where('status', 'verified')
            ->where(function ($query) use ($handle) {
                $query->where('scammer_social_handle', 'like', "%{$handle}%")
                    ->orWhere('scammer_profile_url', 'like', "%{$handle}%");
            })
            ->with(['user', 'category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('search/results', [
            'results' => $reports,
            'searchType' => 'social',
            'searchQuery' => $handle,
            'totalFound' => $reports->total(),
        ]);
    }

    /**
     * Search for reports by bank identifier (RIP, CCP, BaridiMob, etc.).
     */
    public function searchByBank(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $identifier = $request->identifier;

        $reports = Report::where('status', 'verified')
            ->where('scammer_bank_identifier', 'like', "%{$identifier}%")
            ->with(['user', 'category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('search/results', [
            'results' => $reports,
            'searchType' => 'bank',
            'searchQuery' => $identifier,
            'totalFound' => $reports->total(),
        ]);
    }

    /**
     * Search for reports by scammer name.
     */
    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $name = $request->name;

        $reports = Report::where('status', 'verified')
            ->where('scammer_name', 'like', "%{$name}%")
            ->with(['user', 'category', 'platform'])
            ->withCount(['comments', 'upvotes', 'downvotes'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('search/results', [
            'results' => $reports,
            'searchType' => 'name',
            'searchQuery' => $name,
            'totalFound' => $reports->total(),
        ]);
    }

    /**
     * Get statistics for a phone number (how many times reported).
     */
    public function getPhoneStats(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $phone = $request->phone;
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $phone);

        $stats = [
            'total_reports' => Report::where(function ($query) use ($phone, $normalizedPhone) {
                $query->where('scammer_phone', 'like', "%{$phone}%")
                    ->orWhere('scammer_phone', 'like', "%{$normalizedPhone}%");
            })->count(),
            'verified_reports' => Report::where('status', 'verified')
                ->where(function ($query) use ($phone, $normalizedPhone) {
                    $query->where('scammer_phone', 'like', "%{$phone}%")
                        ->orWhere('scammer_phone', 'like', "%{$normalizedPhone}%");
                })->count(),
            'latest_report_date' => Report::where(function ($query) use ($phone, $normalizedPhone) {
                $query->where('scammer_phone', 'like', "%{$phone}%")
                    ->orWhere('scammer_phone', 'like', "%{$normalizedPhone}%");
            })->latest()->value('created_at'),
            'categories' => Report::where(function ($query) use ($phone, $normalizedPhone) {
                $query->where('scammer_phone', 'like', "%{$phone}%")
                    ->orWhere('scammer_phone', 'like', "%{$normalizedPhone}%");
            })
            ->join('scam_categories', 'reports.category_id', '=', 'scam_categories.id')
            ->select('scam_categories.name', DB::raw('count(*) as count'))
            ->groupBy('scam_categories.id', 'scam_categories.name')
            ->get(),
        ];

        return response()->json($stats);
    }
}
