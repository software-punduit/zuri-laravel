<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Traits\UploadsPhoto;
use Illuminate\Http\Request;
use App\Http\Requests\PostProfile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use UploadsPhoto;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostProfile  $request
     * @return \Illuminate\Http\Response|mixed
     */
    public function store(PostProfile $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $profileData = $request->only(['phone', 'address']);
        $userData = $request->only('name');
        $user->update($userData);
        if ($profile === null) {
            $user->profile()->create($profileData);
        } else {
            $profile->update($profileData);
        }

        $this->uploadPhoto($request, 'photo', $user, User::AVATAR_COLLECTION);

      /*   if ($request->has('photo')) {
            // If there is a valid file upload called
            // 'photo'
            if ($request->file('photo')->isValid()) {
                // 1. Install media-library package
                // 2. Setup the user model to use the library
                $disk = config('filesystems.default');
                $path = $request->photo->store('', $disk);

                $user->addMediaFromDisk($path, $disk)
                    ->toMediaCollection(User::AVATAR_COLLECTION);
            }
        } */

        return back()->with([
            'status' => 'Profile updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
