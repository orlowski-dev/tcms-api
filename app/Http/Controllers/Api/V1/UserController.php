<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Http\SearchParams\V1\UserSearchParams;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usp = new UserSearchParams();
        $eloQuery = $usp->makeEloquentQuery($request);

        $users = User::where($eloQuery);
        $users = $usp->includeRelations($users, $request);

        return new UserCollection($users->paginate(10)->appends($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $usp = new UserSearchParams();
        $user = $usp->includeRelations($user, $request);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $softDelete = true;

        if ($request->get('forceDelete') == true) {
            $user->forceDelete();
            $softDelete = false;
        } else {
            $user->delete();
        }

        return response()->json([
            'message' => 'User has been deleted.',
            'softDeleted' => $softDelete
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, string $userId)
    {
        $user = User::withTrashed()->find($userId);

        if ($user == null || $user->trashed() == false) {
            return response()->json([
                'message' => 'User doest not exist or has not been trashed.'
            ]);
        }

        $user->restore();

        return response()->json([
            'message' => 'User has been restored'
        ]);
    }
}
