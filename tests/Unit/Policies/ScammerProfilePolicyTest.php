<?php

namespace Tests\Unit\Policies;

use App\Models\ScammerProfile;
use App\Models\User;
use App\Policies\ScammerProfilePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScammerProfilePolicyTest extends TestCase
{
    use RefreshDatabase;

    private ScammerProfilePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ScammerProfilePolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_scammer_profiles(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_scammer_profiles(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->view($user, $profile));
    }

    public function test_inactive_user_cannot_view_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->view($user, $profile));
    }

    // ========== CREATE TESTS ==========

    public function test_regular_user_cannot_create_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);

        $this->assertFalse($this->policy->create($user));
    }

    public function test_moderator_can_create_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);

        $this->assertTrue($this->policy->create($moderator));
    }

    public function test_admin_can_create_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_inactive_moderator_cannot_create_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => false, 'role' => 'moderator']);

        $this->assertFalse($this->policy->create($moderator));
    }

    // ========== UPDATE TESTS ==========

    public function test_regular_user_cannot_update_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->update($user, $profile));
    }

    public function test_moderator_can_update_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->update($moderator, $profile));
    }

    public function test_admin_can_update_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->update($admin, $profile));
    }

    public function test_inactive_moderator_cannot_update_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => false, 'role' => 'moderator']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->update($moderator, $profile));
    }

    // ========== DELETE TESTS ==========

    public function test_regular_user_cannot_delete_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->delete($user, $profile));
    }

    public function test_moderator_cannot_delete_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->delete($moderator, $profile));
    }

    public function test_admin_can_delete_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->delete($admin, $profile));
    }

    public function test_inactive_admin_cannot_delete_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->delete($admin, $profile));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->restore($user, $profile));
    }

    public function test_moderator_can_restore_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->restore($moderator, $profile));
    }

    public function test_admin_can_restore_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $profile));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_scammer_profile(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $profile));
    }

    public function test_moderator_cannot_force_delete_scammer_profile(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $profile = ScammerProfile::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $profile));
    }

    public function test_admin_can_force_delete_scammer_profile(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $profile = ScammerProfile::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $profile));
    }
}
