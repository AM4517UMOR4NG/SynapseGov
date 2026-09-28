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
        if (isset($reportable->user_id) && $reportable->user_id === $user->id) {
            return true;
        }

        // Department head can view files in their department
        if ($user->role === 'department_head' && $reportable->department_id === $user->department_id) {
            return true;
        }

        // Staff can view files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in submitted/pending/verified status in their department
        if ($user->role === 'staff') {
            if ($reportable->department_id === $user->department_id ||
                $reportable->assigned_to === $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified']) && $reportable->department_id === $user->department_id)) {
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
        if (isset($reportable->user_id) && $reportable->user_id === $user->id) {
            return true;
        }

        // Department head can download files in their department
        if ($user->role === 'department_head' && $reportable->department_id === $user->department_id) {
            return true;
        }

        // Staff can download files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in submitted/pending/verified status in their department
        if ($user->role === 'staff') {
            if ($reportable->department_id === $user->department_id ||
                $reportable->assigned_to === $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified']) && $reportable->department_id === $user->department_id)) {
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
