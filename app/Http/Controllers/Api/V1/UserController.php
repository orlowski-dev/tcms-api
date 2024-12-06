<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\UserFilter;
use App\Http\Filters\V1\UsersFilter;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->allowRequestIf(Gate::inspect('view-all', $request->user())->allowed());

        $filter = new UsersFilter();
        $filteredQuery = $filter->transform($request);
        $includeQuery = [
            'profile',
            'role',
            'permissions'
        ];

        $users = User::where($filteredQuery);

        $users = $filter->includeRelations($users, $request, $includeQuery);

        return new UserCollection($users->paginate(10)->append($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $newUser = User::create($request->all());
        UserProfile::create(['user_id' => $newUser->id]);

        return response()->json([
            'data' => new UserResource($newUser)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $this->allowRequestIf(Gate::inspect('view', $user)->allowed());
        $filter = new UserFilter();
        $includeQuery = [
            'profile',
            'role',
            'permissions'
        ];

        $user = $filter->includeRelations($user, $request, $includeQuery);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->allowRequestIf(Gate::inspect('delete', $request->user())->allowed());

        $softDelete = true;

        if ($request->post('forceDelete') == true) {
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

    public function restore(Request $request, string $id)
    {
        $this->allowRequestIf(Gate::inspect('restore', $request->user())->allowed());

        $user = User::withTrashed()->find($id);

        if ($user && $user->trashed()) {
            $user->restore();
            return response()->json([
                'message' => 'User has been resotred.'
            ]);
        }

        return response()->json([
            'message' => 'User does not exist or has not been trashed.'
        ]);
    }
}
