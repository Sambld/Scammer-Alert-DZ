<?php

namespace Tests\Unit\Policies;

use App\Models\ScamCategory;
use App\Models\User;
use App\Policies\ScamCategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScamCategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ScamCategoryPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ScamCategoryPolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_categories(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_categories(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_category(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $category = ScamCategory::factory()->create();

        $this->assertTrue($this->policy->view($user, $category));
    }

    public function test_inactive_user_cannot_view_category(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->view($user, $category));
    }

    // ========== CREATE TESTS ==========

    public function test_regular_user_cannot_create_category(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);

        $this->assertFalse($this->policy->create($user));
    }

    public function test_moderator_cannot_create_category(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);

        $this->assertFalse($this->policy->create($moderator));
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_inactive_admin_cannot_create_category(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);

        $this->assertFalse($this->policy->create($admin));
    }

    // ========== UPDATE TESTS ==========

    public function test_regular_user_cannot_update_category(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->update($user, $category));
    }

    public function test_moderator_cannot_update_category(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->update($moderator, $category));
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertTrue($this->policy->update($admin, $category));
    }

    public function test_inactive_admin_cannot_update_category(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->update($admin, $category));
    }

    // ========== DELETE TESTS ==========

    public function test_regular_user_cannot_delete_category(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->delete($user, $category));
    }

    public function test_moderator_cannot_delete_category(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->delete($moderator, $category));
    }

    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertTrue($this->policy->delete($admin, $category));
    }

    public function test_inactive_admin_cannot_delete_category(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->delete($admin, $category));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_category(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->restore($user, $category));
    }

    public function test_moderator_cannot_restore_category(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->restore($moderator, $category));
    }

    public function test_admin_can_restore_category(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $category));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_category(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $category));
    }

    public function test_moderator_cannot_force_delete_category(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $category = ScamCategory::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $category));
    }

    public function test_admin_can_force_delete_category(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $category = ScamCategory::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $category));
    }
}
