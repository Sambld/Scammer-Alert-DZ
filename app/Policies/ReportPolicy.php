<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
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
    public function view(User $user, Report $report): bool
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
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can update any report
        if ($user->isModerator()) {
            return true;
        }

        // Users can only update their own reports
        return $report->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Moderators and admins can delete any report
        if ($user->isModerator()) {
            return true;
        }

        // Users can only delete their own reports
        return $report->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        return $user->is_active && $user->isModerator();
    }

    /**
     * Determine whether the user can moderate the model (change status, add moderator notes).
     */
    public function moderate(User $user, Report $report): bool
    {
        return $user->is_active && $user->isModerator();
    }

  
     

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        return $user->is_active && $user->isAdmin();
    }
}
