<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    private $uri = '/api/v1/profiles/';
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->has(UserProfile::factory()->count(1), 'profile')
            ->create();
    }

    public function testUserProfileShow(): void
    {
        $response = $this->get($this->uri . $this->user->id);
        $response->assertStatus(200);
    }

    public function testUserProfileUpdate(): void
    {
        $data = [
            'phoneNumber' => '987654321',
            'address' => null,
            'city' => null,
            'postalCode' => null
        ];
        $response = $this->put($this->uri . $this->user->id, $data);
        $response
            ->assertStatus(200)
            ->assertJson(['data' => $data]);

        $patchData = [
            'address' => '123 Street 12/2'
        ];

        $response = $this->patch($this->uri . $this->user->id, $patchData);
        $response
            ->assertStatus(200)
            ->assertJson(['data' => [...$data, ...$patchData]]);
    }
}
