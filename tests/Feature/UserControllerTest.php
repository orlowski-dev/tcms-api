<?php

use App\Models\RolePermission;
use App\Models\RolePermissionUserRole;
use App\Models\User;
use App\Models\UserRole;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

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

    $this->unPrivilegedUser = User::factory()->create([
        'role_id' => UserRole::find(2)
    ]);
});

it('allows an privileged user to view user list', function () {
    actingAs($this->privilegedUser);
    $response = get(route('users.index'));

    $response->assertStatus(200);
});

it('denies an unprivileged user to view user list', function () {
    actingAs($this->unPrivilegedUser);
    $response = get(route('users.index'));

    $response->assertStatus(403);
});

// TODO: write more tests!
