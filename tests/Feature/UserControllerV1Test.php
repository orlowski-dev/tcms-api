<?php

use App\Models\RolePermission;
use App\Models\RolePermissionUserRole;
use App\Models\User;
use App\Models\UserRole;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    $rolePermsMap = [
        'roleGood' => 'users:rw',
        'roleBad' => 'cars:rw'
    ];

    foreach ($rolePermsMap as $role => $perm) {
        $role = UserRole::create(['name' => $role]);
        $permission = RolePermission::create(['ability' => $perm]);
        RolePermissionUserRole::create([
            'permission_id' => $permission->id,
            'role_id' => $role->id
        ]);
    }

    $this->privilegedUser = User::factory()->create([
        'role_id' => UserRole::find(1)
    ]);

    $this->unprivilegedUser = User::factory()->create([
        'role_id' => UserRole::find(2)
    ]);

    $this->thirdUser = User::factory()->create([
        'role_id' => UserRole::find(1)
    ]);

    $this->fourthUser = User::factory()->create([
        'role_id' => UserRole::find(2)
    ]);

    $this->createUserData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'roleId' => 2
    ];

    $this->changePasswordData = [
        'userId' => $this->unprivilegedUser->id,
        'newPassword' => 'password123'
    ];
});

it('allows an privileged user to view user list', function () {
    actingAs($this->privilegedUser);
    $response = get(route('users.index'));

    $response->assertStatus(200);
});

it('denies an unprivileged user to view user list', function () {
    actingAs($this->unprivilegedUser);
    $response = get(route('users.index'));

    $response->assertStatus(403);
});

it('allows an privileged user to create a user', function () {
    actingAs($this->privilegedUser);
    $response = post(route('users.store'), $this->createUserData);

    $response->assertStatus(201);
});

it('denies an unprivileged user to create a user', function () {
    actingAs($this->unprivilegedUser);
    $response = post(route('users.store'), $this->createUserData);

    $response->assertStatus(403);
});

it('allows an privileged user to view a user', function () {
    actingAs($this->privilegedUser);
    $response = get(route('users.show', ['user' => $this->unprivilegedUser]));

    $response->assertStatus(200);
});

it('denies an unprivileged user to view a user', function () {
    actingAs($this->unprivilegedUser);
    $response = get(route('users.show', ['user' => $this->privilegedUser]));

    $response->assertStatus(403);
});

it('allows a user to view their own model', function () {
    actingAs($this->unprivilegedUser);
    $response = get(route('users.show', ['user' => $this->unprivilegedUser]));

    $response->assertStatus(200);
});

it('allows an privileged user to update a user', function () {
    actingAs($this->privilegedUser);
    $response = patch(route('users.update', ['user' => $this->thirdUser]), ['name' => 'Third User']);

    $response->assertStatus(200);
});

it('denies an unprivileged user to update a user', function () {
    actingAs($this->unprivilegedUser);
    $response = patch(route('users.update', ['user' => $this->thirdUser]), ['name' => 'Third User']);

    $response->assertStatus(403);
});

it('allows an privileged user to delete a user', function () {
    actingAs($this->privilegedUser);
    $response = delete(route('users.destroy', ['user' => $this->thirdUser]));

    $response->assertStatus(200);
});

it('denies an unprivileged user to delete a user', function () {
    actingAs($this->unprivilegedUser);
    $response = delete(route('users.destroy', ['user' => $this->thirdUser]));

    $response->assertStatus(403);
});

it('allows an privileged user to restore a user', function () {
    actingAs($this->privilegedUser);
    $response = post("/api/v1/users/{$this->thirdUser->id}/restore");

    $response->assertStatus(200);
});

it('denies an unprivileged user to restore a user', function () {
    actingAs($this->unprivilegedUser);
    $response = post("/api/v1/users/{$this->thirdUser->id}/restore");

    $response->assertStatus(403);
});

it('allows an privileged user to change user password', function () {
    actingAs($this->privilegedUser);
    $response = post('/id/change-password', $this->changePasswordData);

    $response->assertStatus(200);
});

it('denies an unprivileged user to change user password', function () {
    actingAs($this->fourthUser);
    $response = post('/id/change-password', $this->changePasswordData);

    $response->assertStatus(403);
});

it('allows user to change their password', function () {
    actingAs($this->unprivilegedUser);
    $response = post('/id/change-password', $this->changePasswordData);

    $response->assertStatus(200);
});
