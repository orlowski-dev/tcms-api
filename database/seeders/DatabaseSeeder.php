<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use App\Models\RolePermissionUserRole;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rolesPermsMap = [
            'admin' => [
                'users:rw',
                'invoices:rw',
                'cars:rw',
                'dashboard:access'
            ],
            'driver' => [
                'users:ro',
                'cars:ro'
            ],
            'accounter' => [
                'users:ro',
                'invoices:rw',
                'cars:ro'
            ],
            'client' => [
                'invoices:ro'
            ]
        ];

        $createdPerms = [];
        foreach ($rolesPermsMap as $role => $perms) {
            $roleModel = UserRole::create(['name' => $role]);

            foreach ($perms as $perm) {
                if (in_array($perm, $createdPerms)) {
                    continue;
                }

                $permModel = RolePermission::create(['ability' => $perm]);

                RolePermissionUserRole::create([
                    'permission_id' => $permModel->id,
                    'role_id' => $roleModel->id
                ]);

                $createdPerms[] = $perm;
            }
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => UserRole::find(1)->id
        ]);

        User::factory()
            ->count(30)
            ->create();

        foreach (User::all() as $user) {
            UserProfile::factory()->create(['user_id' => $user->id]);
        }
    }
}
