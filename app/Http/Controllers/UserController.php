<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\PutUser;
use App\Http\Requests\PostUser;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)
            ->get();
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostUser $request
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function store(PostUser $request)
    {
        // 1. Validate the request

        // 2. Get the form data except password from 
        // the request object
        // $userData = $request->validated();
        // $userData = $request->only(['name', 'email', 'password']);
        $userData = $request->except('password');

        // 3. Hash the password and merge it with the other form
        // data
        $userData = array_merge($userData, [
            'password' => Hash::make($request->password)
        ]);

        // 4. Create a new user record using the data
        $user = User::create($userData);

        $user->assignRole(User::ADMIN);

        // 5. Trigger a new Registered event. This
        // creates an active profile for the registered
        // user.
        event(new Registered($user));
        // $user->profile->create(['active' => Profile::ACTIVE]);

        // 6. Redirect to the users index page with a
        // success message
        return redirect(route('users.index'))->with([
            'status' => "User Created Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response|View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PutUser  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function update(PutUser $request, User $user)
    {
        $profileData = $request->only('active');
        $userData = $request->only('name');

        $user->update($userData); // Update the user's data

        $profile = $user->profile;

        // If the profile doesn't exist
        if(is_null($profile)){
            // create a new one
            Profile::create($profileData);
        }else{
            // update the existing profile
            $profile->update($profileData);
        }

        return back()->with([
            'status' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
