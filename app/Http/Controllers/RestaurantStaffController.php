<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RestaurantStaff;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\PutRestaurantStaff;
use App\Http\Requests\PostRestaurantStaff;

class RestaurantStaffController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(RestaurantStaff::class, 'restaurant_staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function index()
    {
        $user = Auth::user();
        $staffMembers = $user->restaurantStaff;
        return view('restaurant-staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function create()
    {

        $user = Auth::user();
        $restaurants = $user->restaurants;
        return view('restaurant-staff.create', compact('restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRestaurantStaff  $request
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function store(PostRestaurantStaff $request)
    {
        //Validate the request
        //get the data
        //Store a new record of the restaurant staff
        //Assign restaurant staff role to the new restaurant staff
        //Redirect to the restaurant-staff.index route with a success message

        DB::beginTransaction();
        try {
            $staffData = $request->except([
                'restaurant_id',
                'password'
            ]);
            $staffData = array_merge($staffData, [
                'password' => Hash::make($request->password)
            ]);
            $staff = User::create($staffData);
            event(new Registered($staff));

            $restaurantStaffData = [
                'staff_id' => $staff->id,
                'restaurant_id' => $request->restaurant_id,
            ];

            RestaurantStaff::create($restaurantStaffData);
            $staff->assignRole(User::RESTUARANT_STAFF);

            DB::commit();
            return redirect(route('restaurant-staff.index'))->with([
                'status' => 'Restuarant Staff created successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantStaff $restaurantStaff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Http\Response|View
     */
    public function edit(RestaurantStaff $restaurantStaff)
    {
        $restaurantOwner = Auth::user();
        $restaurants = $restaurantOwner->restaurants;
        return view('restaurant-staff.edit', compact('restaurantStaff', 'restaurants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PutRestaurantStaff $request
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function update(PutRestaurantStaff $request, RestaurantStaff $restaurantStaff)
    {
        //validate the request
        //get the data from the form request
        //update the restaurant staff record

        DB::beginTransaction();

        try {
            $restaurantStaffData = $request->only('restaurant_id');
            $staffData = $request->only('name');

            if ($request->has('password')) {
                $staffData = array_merge($staffData, [
                    'password' => Hash::make($request->password)
                ]);
            }

            $restaurantStaff->update($restaurantStaffData);
            $restaurantStaff->staff()->update($staffData);
            DB::commit();

            return redirect(route('restaurant-staff.index'))->with([
                'status' => 'Restaurant Staff Updated Successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantStaff  $restaurantStaff
     * @return \Illuminate\Http\Response
     */
    public function destroy(RestaurantStaff $restaurantStaff)
    {
        //
    }
}
