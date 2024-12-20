<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $secondUser;
    private $expectedUserData;
    private $userProfile;
    private $expectedUserProfileData;

    private function makeExpectedUserData(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->state([
                'name' => 'Test User',
                'email' => 'test@email.com'
            ])
            ->create();

        $this->userProfile = UserProfile::factory()->create(['user_id' => $this->user->id]);
        $this->expectedUserProfileData = [
            'phoneNumber' => $this->userProfile->phone_number,
            'address' => $this->userProfile->address,
            'city' => $this->userProfile->city,
            'postalCode' => $this->userProfile->postal_code,
        ];

        $this->secondUser = User::factory()
            ->state([
                'name' => 'Second User',
                'email' => 'second@email.com'
            ])
            ->create();

        $this->expectedUserData = $this->makeExpectedUserData($this->user);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('users.index'));
        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        0 => $this->expectedUserData
                    ]
                ]
            );
    }

    public function testIndexWithSearchQuery(): void
    {
        $responseEmailEq = $this->get(route('users.index', ['email[eq]' => $this->secondUser->email]));
        $responseEmailEq
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    0 => $this->makeExpectedUserData($this->secondUser)
                ],
                'meta' => [
                    'total' => 1
                ]
            ]);
    }

    public function testIndexUserListWithProfilesAndSearchParams(): void
    {
        $responseEmailEq = $this->get(route('users.index', [
            'includeProfile' => true,
            'email[eq]' => $this->user->email
        ]));
        $responseEmailEq
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    0 => [
                        ...$this->makeExpectedUserData($this->user),
                        'profile' => $this->expectedUserProfileData
                    ]
                ],
                'meta' => [
                    'total' => 1
                ]
            ]);
    }

    public function testShow(): void
    {
        $response = $this->get(route('users.show', $this->user->id));
        $response
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => $this->expectedUserData
                ]
            );
    }

    public function testShowIncludeProfile(): void
    {
        $response = $this->get(route('users.show', [$this->user->id, 'includeProfile' => true]));
        $response
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        ...$this->expectedUserData,
                        'profile' => $this->expectedUserProfileData
                    ]
                ]
            );
    }

    public function testUserSoftDelete(): void
    {
        $user = User::factory()->create();
        $response = $this->delete(route('users.destroy', ['user' => $user]));
        $this->assertNull(User::find($user->id));
        $this->assertEquals(true, User::withTrashed()->find($user->id)->trashed());
        $response
            ->assertStatus(200)
            ->assertJson([
                'softDeleted' => true
            ]);
    }

    public function testUserForceDelete()
    {
        $user = User::factory()->create();
        $response = $this->delete(route('users.destroy',
            [
                'user' => $user,
                'forceDelete' => true
            ]));

        $this->assertNull(User::withTrashed()->find($user->id));

        $response
            ->assertStatus(200)
            ->assertJson([
                'softDeleted' => false
            ]);
    }

    public function testUserRestore(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->post("/api/v1/users/$user->id/restore");
        $response->assertStatus(200);
        $user = User::find($user->id);
        $this->assertEquals(false, $user->trashed());
    }
}
