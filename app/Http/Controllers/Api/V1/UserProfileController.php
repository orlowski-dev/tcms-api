<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateUserProfileRequest;
use App\Http\Resources\V1\UserProfileResource;
use App\Models\UserProfile;

class UserProfileController extends Controller
{
    /** Display a listing of the resource. */
    // public function index()
    // {
    //     //
    // }

    /** Store a newly created resource in storage. */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $userId)
    {
        $profile = UserProfile::findOrFail($userId);

        return new UserProfileResource($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserProfileRequest $request, string $userId)
    {
        $profile = UserProfile::findOrFail($userId);
        $profile->update($request->all());
        return new UserProfileResource($profile);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(UserProfile $userProfile)
    // {
    //     //
    // }
}
