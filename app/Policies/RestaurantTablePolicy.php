<?php

namespace App\Policies;

use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantTablePolicy
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
        return $user->can('table.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RestaurantTable $restaurantTable)
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
        return $user->can('table.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RestaurantTable $restaurantTable)
    {
        return $user->can('table.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RestaurantTable $restaurantTable)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RestaurantTable $restaurantTable)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RestaurantTable $restaurantTable)
    {
        //
    }
}
