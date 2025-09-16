<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Establishment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstablishmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_establishment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Establishment $establishment): bool
    {
        return $user->can('view_establishment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_establishment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Establishment $establishment): bool
    {
        return $user->can('update_establishment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Establishment $establishment): bool
    {
        return $user->can('delete_establishment');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_establishment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Establishment $establishment): bool
    {
        return $user->can('force_delete_establishment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_establishment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Establishment $establishment): bool
    {
        return $user->can('restore_establishment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_establishment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Establishment $establishment): bool
    {
        return $user->can('replicate_establishment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_establishment');
    }
}
