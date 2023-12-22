<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    //* Les validations de breeze sont faites ailleurs
    //* On ne passe ici que lorsqu'on est dans le admin pannel

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response    {
        // Retourne vrai si l'utilisateur est admin
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        // Retourne vrai si l'utilisateur est admin ou 
        // si l'utilisateur connecté est le même que l'utilisateur dont on veut voir le profil
        return $user->role === 'admin' || $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        // Retourne vrai si l'utilisateur est admin
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        // Retourne vrai si l'utilisateur est admin
        return $user->role === 'admin' || $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        // Retourne vrai si l'utilisateur est admin ou si l'utilisateur connecté est le même 
        // que l'utilisateur dont on veut voir le profil
        return $user->role === 'admin' || $user->id === $model->id
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        // Retourne vrai si l'utilisateur est admin
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        // Retourne vrai si l'utilisateur est admin
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('Vous devez être administrateur pour accéder à cette page.');
    }
}
