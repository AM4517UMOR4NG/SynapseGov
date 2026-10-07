<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view files.
     */
    public function view(User $user, $reportable)
    {
        // Admin can view all files
        if ($user->role === 'admin') {
            return true;
        }

        // Creator/owner can view their own files
        if (isset($reportable->user_id) && (int) $reportable->user_id === (int) $user->id) {
            return true;
        }

        // Department head can view files in their department OR if assigned to them
        if ($user->role === 'department_head') {
            if (($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id) || (int) $reportable->assigned_to === (int) $user->id) {
                return true;
            }
        }

        // Staff can view files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in an active status in their department
        if ($user->role === 'staff') {
            if (($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id) ||
                (int) $reportable->assigned_to === (int) $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified', 'assigned', 'in_progress', 'reviewed', 'awaiting_admin_approval']) && ($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can download files.
     */
    public function download(User $user, $reportable)
    {
        // Admin can download all files
        if ($user->role === 'admin') {
            return true;
        }

        // Creator/owner can download their own files
        if (isset($reportable->user_id) && (int) $reportable->user_id === (int) $user->id) {
            return true;
        }

        // Department head can download files in their department OR if assigned to them
        if ($user->role === 'department_head') {
            if (($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id) || (int) $reportable->assigned_to === (int) $user->id) {
                return true;
            }
        }

        // Staff can download files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in an active status in their department
        if ($user->role === 'staff') {
            if (($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id) ||
                (int) $reportable->assigned_to === (int) $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified', 'assigned', 'in_progress', 'reviewed', 'awaiting_admin_approval']) && ($user->department_id !== null && (int) $reportable->department_id === (int) $user->department_id))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can preview files.
     */
    public function preview(User $user, $reportable)
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $this->view($user, $reportable);
    }
}

