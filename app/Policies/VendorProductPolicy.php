<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VendorProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the vendorProduct can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the vendorProduct can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function view(User $user, VendorProduct $model)
    {
        return true;
    }

    /**
     * Determine whether the vendorProduct can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the vendorProduct can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function update(User $user, VendorProduct $model)
    {
        return true;
    }

    /**
     * Determine whether the vendorProduct can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function delete(User $user, VendorProduct $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the vendorProduct can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function restore(User $user, VendorProduct $model)
    {
        return false;
    }

    /**
     * Determine whether the vendorProduct can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\VendorProduct  $model
     * @return mixed
     */
    public function forceDelete(User $user, VendorProduct $model)
    {
        return false;
    }
}
