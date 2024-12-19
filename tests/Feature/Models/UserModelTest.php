<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $userProfile;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create(['user_id' => $this->user->id]);
    }

    public function testUserProfileRelation(): void
    {
        $this->assertEquals($this->userProfile->id, $this->user->profile->id);
    }
}
