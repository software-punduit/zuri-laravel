<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Restaurant;
use App\Traits\UploadsPhoto;
use Illuminate\Http\Request;
use App\Http\Requests\PutMenu;
use App\Http\Requests\PostMenu;
use App\Models\RestaurantStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MenuController extends Controller
{
    use UploadsPhoto;

    public function __construct()
    {
        $this->authorizeResource(Menu::class, 'menu');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($request->has('restaurant')) {
            $restaurant = Restaurant::find($request->restaurant);
            $menus = $restaurant->menuItems()->with(['restaurant'])->get();
            return response()->json($menus);
        }
        elseif ($user->hasRole(User::RESTUARANT_OWNER)) {
            $menus = $user->menus;
        } else {
            $restaurantIds = RestaurantStaff::where('staff_id', $user->id)
                ->pluck('restaurant_id');
            $menus = Menu::whereHas('restaurant', function ($query) use ($restaurantIds) {
                $query->whereIn('restaurant_id', $restaurantIds);
            })->get();
        }

        return view('menus.index', compact('menus'));
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
        return view('menus.create', compact('restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     *s
     * @param  \App\Http\Requests\PostMenu  $request
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function store(PostMenu $request)
    {
        //Validate the request
        //Get the form data
        //Store the menu data

        DB::beginTransaction();

        try {
            $menuData = $request->validated();
            $user = Auth::user();
            $menu = $user->menus()->create($menuData);
            $this->uploadPhoto($request, 'photo', $menu, Menu::MEDIA_COLLECTION);

            DB::commit();

            return redirect(route('menus.index'))->with([
                'status' => 'Menu item created successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response|View
     */
    public function edit(Menu $menu)
    {
        $restaurants = Auth::user()->restaurants;
        return view('menus.edit', compact('menu', 'restaurants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PutMenu  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function update(PutMenu $request, Menu $menu)
    {
        //Validate the request
        //get the form data
        //Update the menu item

        DB::beginTransaction();

        try {
            $menuData = $request->only([
                'name',
                'price',
                'restaurant_id',
                'photo',
                'active'
            ]);
            $menu->update($menuData);
            $this->uploadPhoto($request, 'photo', $menu, Menu::MEDIA_COLLECTION);

            DB::commit();

            return redirect(route('menus.index'))->with([
                'status' => 'Menu Item Updated Successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
