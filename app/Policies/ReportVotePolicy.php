<?php

namespace App\Policies;

use App\Models\ReportVote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportVotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReportVote $reportVote): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can vote on a specific report.
     */
    public function voteOnReport(User $user, \App\Models\Report $report): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Users cannot vote on their own reports
        return $report->user_id !== $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReportVote $reportVote): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can update any vote
        if ($user->isModerator()) {
            return true;
        }

        // Users can only update their own votes
        return $reportVote->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReportVote $reportVote): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can delete any vote
        if ($user->isModerator()) {
            return true;
        }

                // Users can only delete their own votes
        return $reportVote->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReportVote $reportVote): bool
    {
        return $user->is_active && $user->isModerator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReportVote $reportVote): bool
    {
        return $user->is_active && $user->isAdmin();
    }

}
