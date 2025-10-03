<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use App\Http\Requests\StoreReportCommentRequest;
use App\Http\Requests\UpdateReportCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportCommentController extends Controller
{
    /**
     * Display a listing of comments for a report.
     */
    public function index(Report $report)
    {
        Gate::authorize('viewAny', ReportComment::class);

        $comments = $report->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id') // Only get top-level comments
            ->latest()
            ->paginate(20);

        return response()->json([
            'comments' => $comments,
        ]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreReportCommentRequest $request, Report $report)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();
        $validated['report_id'] = $report->id;
        $validated['status'] = 'active'; // Default status
        
        // Check if user is the report creator for is_from_victim flag
        $validated['is_from_victim'] = $report->user_id === auth()->id();

        $comment = ReportComment::create($validated);
        $comment->load('user');

        return redirect()->back()
            ->with('success', __('Comment posted successfully.'));
    }

    /**
     * Display the specified comment.
     */
    public function show(Report $report, ReportComment $comment)
    {
        Gate::authorize('view', $comment);

        $comment->load(['user', 'replies.user']);

        return response()->json([
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(UpdateReportCommentRequest $request, Report $report, ReportComment $comment)
    {
        $validated = $request->validated();

        $comment->update($validated);

        return redirect()->back()
            ->with('success', __('Comment updated successfully.'));
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Report $report, ReportComment $comment)
    {
        Gate::authorize('delete', $comment);

        // Soft delete by changing status instead of actual deletion
        // This preserves the conversation thread
        $comment->update(['status' => 'deleted']);

        return redirect()->back()
            ->with('success', __('Comment deleted successfully.'));
    }

    /**
     * Moderate a comment (moderator/admin action).
     */
    public function moderate(Request $request, Report $report, ReportComment $comment)
    {
        if (!auth()->user()->isModerator()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => ['required', 'in:active,hidden,deleted'],
        ]);

        $comment->update([
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', __('Comment moderated successfully.'));
    }
}
