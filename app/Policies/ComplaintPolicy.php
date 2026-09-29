<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplaintPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any complaints.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'department_head', 'staff']);
    }

    /**
     * Determine whether the user can view the complaint.
     */
    public function view(User $user, Complaint $complaint): bool
    {
        // User can view their own complaints
        if ((int) $complaint->user_id === (int) $user->id) {
            return true;
        }

        // Admin can view all complaints
        if ($user->role === 'admin') {
            return true;
        }

        // Department head can view complaints in their department
        if ($user->role === 'department_head' && (int) $complaint->department_id === (int) $user->department_id) {
            return true;
        }

        // Staff can view complaints in their department or assigned to them
        if ($user->role === 'staff' && ((int) $complaint->department_id === (int) $user->department_id || (int) $complaint->assigned_to === (int) $user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create complaints.
     */
    public function create(User $user): bool
    {
        return $user->role === 'citizen';
    }

    /**
     * Determine whether the user can update the complaint.
     */
    public function update(User $user, Complaint $complaint): bool
    {
        // User can update their own complaints if not yet resolved/closed
        if ((int) $complaint->user_id === (int) $user->id && in_array($complaint->status, ['submitted', 'pending'])) {
            return true;
        }

        // Admin can update any complaint
        if ($user->role === 'admin') {
            return true;
        }

        // Department head can update complaints in their department
        if ($user->role === 'department_head' && (int) $complaint->department_id === (int) $user->department_id) {
            return true;
        }

        // Staff can update complaints assigned to them
        if ($user->role === 'staff' && (int) $complaint->assigned_to === (int) $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the complaint.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->role === 'admin';
    }
}
