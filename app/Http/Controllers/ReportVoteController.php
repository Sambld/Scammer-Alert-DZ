<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportVote;
use App\Http\Requests\StoreReportVoteRequest;
use App\Http\Requests\UpdateReportVoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ReportVoteController extends Controller
{
    /**
     * Vote on a report (upvote or downvote).
     */
    public function store(StoreReportVoteRequest $request, Report $report)
    {
        $validated = $request->validated();
        $userId = auth()->id();

        // Check if user already voted
        $existingVote = ReportVote::where('report_id', $report->id)
            ->where('user_id', $userId)
            ->first();

        DB::transaction(function () use ($report, $existingVote, $validated, $userId) {
            if ($existingVote) {
                // If same vote type, remove the vote (toggle)
                if ($existingVote->vote_type === $validated['vote_type']) {
                    // Decrement counter
                    if ($existingVote->vote_type === 'upvote') {
                        $report->decrement('upvotes_count');
                    } else {
                        $report->decrement('downvotes_count');
                    }
                    $existingVote->delete();
                } else {
                    // Change vote type
                    // Adjust counters
                    if ($existingVote->vote_type === 'upvote') {
                        $report->decrement('upvotes_count');
                        $report->increment('downvotes_count');
                    } else {
                        $report->decrement('downvotes_count');
                        $report->increment('upvotes_count');
                    }
                    $existingVote->update(['vote_type' => $validated['vote_type']]);
                }
            } else {
                // Create new vote
                ReportVote::create([
                    'report_id' => $report->id,
                    'user_id' => $userId,
                    'vote_type' => $validated['vote_type'],
                ]);

                // Increment counter
                if ($validated['vote_type'] === 'upvote') {
                    $report->increment('upvotes_count');
                } else {
                    $report->increment('downvotes_count');
                }
            }
        });

        return redirect()->back()
            ->with('success', __('Vote recorded successfully.'));
    }

    /**
     * Remove vote from a report.
     */
    public function destroy(Report $report)
    {
        $userId = auth()->id();

        $vote = ReportVote::where('report_id', $report->id)
            ->where('user_id', $userId)
            ->first();

        if (!$vote) {
            return redirect()->back()
                ->with('error', __('No vote found to remove.'));
        }

        Gate::authorize('delete', $vote);

        DB::transaction(function () use ($report, $vote) {
            // Decrement counter
            if ($vote->vote_type === 'upvote') {
                $report->decrement('upvotes_count');
            } else {
                $report->decrement('downvotes_count');
            }

            $vote->delete();
        });

        return redirect()->back()
            ->with('success', __('Vote removed successfully.'));
    }
}
