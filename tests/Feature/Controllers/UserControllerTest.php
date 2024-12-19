<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $secondUser;
    private $expectedUserData;

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

        $responseNameEq = $this->get(route('users.index', ['name[eq]' => $this->secondUser->name]));
        $responseNameEq
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
}
