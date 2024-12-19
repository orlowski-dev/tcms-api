<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->state([
                'name' => 'Super User',
                'email' => 'test@example.com'
            ])
            ->has(UserProfile::factory()->count(1), 'profile')
            ->create();
    }
}
