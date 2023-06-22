<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\UploadsPhoto;
use Illuminate\Http\Request;
use App\Models\RestaurantStaff;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PostRestaurantTable;
use PhpParser\Node\Stmt\TryCatch;

class RestaurantTableController extends Controller
{
    use UploadsPhoto;

    function __construct()
    {
        $this->authorizeResource(RestaurantTable::class, 'restaurant_table');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(User::RESTUARANT_OWNER)) {
            // $restaurantTables = RestaurantTable::whereHas('restaurant', function($query)(
            //     $query->where
            // ))

            $restaurantTables = $user->restaurantTables;
        } else {
            $restaurantIds = RestaurantStaff::where('staff_id', $user->id)
                ->pluck('restaurant_id');
            $restaurantTables = RestaurantTable::whereHas('restaurant', function ($query) use ($restaurantIds) {
                $query->whereIn('restaurant_id', $restaurantIds);
            })->get();
        }

        return view('restaurant-tables.index', compact('restaurantTables'));
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
        return view('restaurant-tables.create', compact('restaurants'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRestaurantTable $request
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function store(PostRestaurantTable $request)
    {
        //Validate the request
        //Get the data from the request
        //Store the record of the restaurant table

        DB::beginTransaction();

        try {
            $restaurantTableData = $request->except('photo');
            $restaurantTable = RestaurantTable::create($restaurantTableData);
    
            $this->uploadPhoto($request, 'photo', $restaurantTable, RestaurantTable::MEDIA_COLLECTION);

            DB::commit();
    
            return redirect(route('restaurant-tables.index'))->with([
                'status' => 'Restaurant Table Created Successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

      

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantTable $restaurantTable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Http\Response
     */
    public function edit(RestaurantTable $restaurantTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RestaurantTable $restaurantTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantTable  $restaurantTable
     * @return \Illuminate\Http\Response
     */
    public function destroy(RestaurantTable $restaurantTable)
    {
        //
    }
}
