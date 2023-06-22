<?php

namespace App\Policies;

use App\Models\RestaurantStaff;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantStaffPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('staff.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RestaurantStaff $restaurantStaff)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('staff.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RestaurantStaff $restaurantStaff)
    {
        return $user->can('staff.update') || $user->id === $restaurantStaff->staff_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RestaurantStaff $restaurantStaff)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RestaurantStaff $restaurantStaff)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RestaurantStaff $restaurantStaff)
    {
        //
    }
}