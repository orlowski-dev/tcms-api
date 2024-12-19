<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileModelTest extends TestCase
{
    use RefreshDatabase;

    private $user = null;
    private $userProfile = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->userProfile = UserProfile::factory()->create(['user_id' => $this->user->id]);
    }

    public function testUserRelation(): void
    {
        $this->assertEquals($this->userProfile->user_id, $this->userProfile->user->id);
    }
}
