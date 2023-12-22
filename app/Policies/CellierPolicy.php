<?php

namespace App\Policies;

use App\Models\Cellier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CellierPolicy
{
    //* Les regles sont : on peut acceder a la page si on est admin ou si on est le proprietaire du cellier
    //* On peut évidemment accèder à la page de création de cellier si on est connecté

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        return $user->id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cellier $cellier): bool
    {
        return $user->id === $cellier->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cellier $cellier): bool
    {
        return $user->id === $cellier->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cellier $cellier): bool
    {
        return $user->id === $cellier->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cellier $cellier): bool
    {
        return $user->id === $cellier->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cellier $cellier): bool
    {
        return $user->id === $cellier->user_id || $user->role === 'admin';
    }
}
