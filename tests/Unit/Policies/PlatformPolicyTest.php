<?php

namespace Tests\Unit\Policies;

use App\Models\Platform;
use App\Models\User;
use App\Policies\PlatformPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformPolicyTest extends TestCase
{
    use RefreshDatabase;

    private PlatformPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new PlatformPolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_platforms(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_platforms(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_platform(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $platform = Platform::factory()->create();

        $this->assertTrue($this->policy->view($user, $platform));
    }

    public function test_inactive_user_cannot_view_platform(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->view($user, $platform));
    }

    // ========== CREATE TESTS ==========

    public function test_regular_user_cannot_create_platform(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);

        $this->assertFalse($this->policy->create($user));
    }

    public function test_moderator_cannot_create_platform(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);

        $this->assertFalse($this->policy->create($moderator));
    }

    public function test_admin_can_create_platform(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_inactive_admin_cannot_create_platform(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);

        $this->assertFalse($this->policy->create($admin));
    }

    // ========== UPDATE TESTS ==========

    public function test_regular_user_cannot_update_platform(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->update($user, $platform));
    }

    public function test_moderator_cannot_update_platform(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->update($moderator, $platform));
    }

    public function test_admin_can_update_platform(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertTrue($this->policy->update($admin, $platform));
    }

    public function test_inactive_admin_cannot_update_platform(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->update($admin, $platform));
    }

    // ========== DELETE TESTS ==========

    public function test_regular_user_cannot_delete_platform(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->delete($user, $platform));
    }

    public function test_moderator_cannot_delete_platform(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->delete($moderator, $platform));
    }

    public function test_admin_can_delete_platform(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertTrue($this->policy->delete($admin, $platform));
    }

    public function test_inactive_admin_cannot_delete_platform(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->delete($admin, $platform));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_platform(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->restore($user, $platform));
    }

    public function test_moderator_cannot_restore_platform(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->restore($moderator, $platform));
    }

    public function test_admin_can_restore_platform(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $platform));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_platform(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $platform));
    }

    public function test_moderator_cannot_force_delete_platform(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $platform = Platform::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $platform));
    }

    public function test_admin_can_force_delete_platform(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $platform = Platform::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $platform));
    }
}
