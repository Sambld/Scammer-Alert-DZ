<?php

namespace App\Policies;

use App\Models\ReportMedia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportMediaPolicy
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
    public function view(User $user, ReportMedia $reportMedia): bool
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
     * Determine whether the user can attach media to a specific report.
     */
    public function attachToReport(User $user, \App\Models\Report $report): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can attach media to any report
        if ($user->isModerator()) {
            return true;
        }

        // Users can only attach media to their own reports
        return $report->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReportMedia $reportMedia): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can update any media
        if ($user->isModerator()) {
            return true;
        }

        // Users can only update media from their own reports
        return $reportMedia->report->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReportMedia $reportMedia): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can delete any media
        if ($user->isModerator()) {
            return true;
        }

                // Users can only delete media from their own reports
        return $reportMedia->report->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReportMedia $reportMedia): bool
    {
        return $user->is_active && $user->isModerator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReportMedia $reportMedia): bool
    {
        return $user->is_active && $user->isAdmin();
    }

}
