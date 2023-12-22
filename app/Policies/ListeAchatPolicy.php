<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ListeAchat;
use Illuminate\Auth\Access\Response;

class ListeAchatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ListeAchat $listeAchat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //return true if user is logged in and doesnt have a listeAchat

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ListeAchat $listeAchat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, listeAchat $listeAchat): bool
    {
        return $user->id === $listeAchat->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, listeAchat $listeAchat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, listeAchat $listeAchat): bool
    {
        return true;
    }
}
