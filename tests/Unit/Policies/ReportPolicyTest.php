<?php

namespace Tests\Unit\Policies;

use App\Models\Report;
use App\Models\User;
use App\Policies\ReportPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ReportPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReportPolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_reports(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_reports(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_report(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->view($user, $report));
    }

    public function test_inactive_user_cannot_view_report(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->view($user, $report));
    }

    // ========== CREATE TESTS ==========

    public function test_active_user_can_create_report(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_inactive_user_cannot_create_report(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->create($user));
    }

    // ========== UPDATE TESTS ==========

    public function test_user_can_update_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $report));
    }

    public function test_user_cannot_update_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $report));
    }

    public function test_moderator_can_update_any_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($moderator, $report));
    }

    public function test_admin_can_update_any_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($admin, $report));
    }

    public function test_inactive_user_cannot_update_own_report(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->update($user, $report));
    }

    // ========== DELETE TESTS ==========

    public function test_user_can_delete_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $report));
    }

    public function test_user_cannot_delete_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $report));
    }

    public function test_moderator_can_delete_any_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->delete($moderator, $report));
    }

    public function test_admin_can_delete_any_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $this->assertTrue($this->policy->delete($admin, $report));
    }

    public function test_inactive_user_cannot_delete_own_report(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->delete($user, $report));
    }

    // ========== MODERATE TESTS ==========

    public function test_regular_user_cannot_moderate_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->moderate($user, $report));
    }

    public function test_moderator_can_moderate_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->moderate($moderator, $report));
    }

    public function test_admin_can_moderate_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->moderate($admin, $report));
    }

    public function test_inactive_moderator_cannot_moderate_report(): void
    {
        $moderator = User::factory()->create(['is_active' => false, 'role' => 'moderator']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->moderate($moderator, $report));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->restore($user, $report));
    }

    public function test_moderator_can_restore_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->restore($moderator, $report));
    }

    public function test_admin_can_restore_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $report));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $report));
    }

    public function test_moderator_cannot_force_delete_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $report));
    }

    public function test_admin_can_force_delete_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $report = Report::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $report));
    }

    public function test_inactive_admin_cannot_force_delete_report(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $report = Report::factory()->create();

        $this->assertFalse($this->policy->forceDelete($admin, $report));
    }
}
