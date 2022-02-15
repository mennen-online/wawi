<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Offer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the offer can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the offer can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function view(User $user, Offer $model)
    {
        return true;
    }

    /**
     * Determine whether the offer can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the offer can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function update(User $user, Offer $model)
    {
        return true;
    }

    /**
     * Determine whether the offer can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function delete(User $user, Offer $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the offer can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function restore(User $user, Offer $model)
    {
        return false;
    }

    /**
     * Determine whether the offer can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Offer  $model
     * @return mixed
     */
    public function forceDelete(User $user, Offer $model)
    {
        return false;
    }
}
