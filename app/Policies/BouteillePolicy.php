<?php
namespace App\Policies;

use App\Models\Bouteille;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;


class BouteillePolicy
{
    //* Les utilisateurs connectés peuvent voir les bouteilles de la SAQ
    //* Et leurs bouteilles personnalisee. Le Response retourne un
    //* message d'erreur à l'utilisateur s'il n'a droit d'exécuter
    //* l'action. Si oui, il est redirigé vers la page.

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
    public function view(User $user, Bouteille $bouteille): bool
    {
        if($bouteille->est_personnalisee == 1 && $bouteille->user_id !== $user->id){
            return false;
        } else if ($user->id === null){
            return false;
        } else {
            return true;
        }
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
    public function update(User $user, Bouteille $bouteille): bool
    {
        return $user->role === 'admin' || $user->id === $bouteille->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bouteille $bouteille): bool
    {
        return $user->role === 'admin' || $user->id === $bouteille->user_id;  
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bouteille $bouteille): Response
    {
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('You must be an administrator.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bouteille $bouteille): Response
    {
        return $user->role === 'admin'
                    ? Response::allow()
                    : Response::deny('You must be an administrator.');
    }
}
