<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InspectionBasis;
use Illuminate\Auth\Access\HandlesAuthorization;

class InspectionBasisPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_inspection::basis');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('view_inspection::basis');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_inspection::basis');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('update_inspection::basis');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('delete_inspection::basis');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_inspection::basis');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('force_delete_inspection::basis');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_inspection::basis');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('restore_inspection::basis');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_inspection::basis');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, InspectionBasis $inspectionBasis): bool
    {
        return $user->can('replicate_inspection::basis');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_inspection::basis');
    }
}
