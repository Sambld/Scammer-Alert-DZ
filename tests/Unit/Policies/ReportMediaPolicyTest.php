<?php

namespace Tests\Unit\Policies;

use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\User;
use App\Policies\ReportMediaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportMediaPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ReportMediaPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReportMediaPolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_media(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_media(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_media(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $media = ReportMedia::factory()->create();

        $this->assertTrue($this->policy->view($user, $media));
    }

    public function test_inactive_user_cannot_view_media(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $media = ReportMedia::factory()->create();

        $this->assertFalse($this->policy->view($user, $media));
    }

    // ========== CREATE TESTS ==========

    public function test_active_user_can_create_media(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_inactive_user_cannot_create_media(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->create($user));
    }

    // ========== ATTACH TO REPORT TESTS ==========

    public function test_user_can_attach_media_to_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->attachToReport($user, $report));
    }

    public function test_user_cannot_attach_media_to_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->attachToReport($user, $report));
    }

    public function test_moderator_can_attach_media_to_any_report(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->attachToReport($moderator, $report));
    }

    public function test_admin_can_attach_media_to_any_report(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->attachToReport($admin, $report));
    }

    public function test_inactive_user_cannot_attach_media_to_own_report(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->attachToReport($user, $report));
    }

    // ========== UPDATE TESTS ==========

    public function test_user_can_update_media_from_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->update($user, $media));
    }

    public function test_user_cannot_update_media_from_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertFalse($this->policy->update($user, $media));
    }

    public function test_moderator_can_update_any_media(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->update($moderator, $media));
    }

    public function test_admin_can_update_any_media(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->update($admin, $media));
    }

    public function test_inactive_user_cannot_update_media_from_own_report(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertFalse($this->policy->update($user, $media));
    }

    // ========== DELETE TESTS ==========

    public function test_user_can_delete_media_from_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $report = Report::factory()->create(['user_id' => $user->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->delete($user, $media));
    }

    public function test_user_cannot_delete_media_from_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertFalse($this->policy->delete($user, $media));
    }

    public function test_moderator_can_delete_any_media(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->delete($moderator, $media));
    }

    public function test_admin_can_delete_any_media(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertTrue($this->policy->delete($admin, $media));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_media(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $media = ReportMedia::factory()->create();

        $this->assertFalse($this->policy->restore($user, $media));
    }

    public function test_moderator_can_restore_media(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $media = ReportMedia::factory()->create();

        $this->assertTrue($this->policy->restore($moderator, $media));
    }

    public function test_admin_can_restore_media(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $media = ReportMedia::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $media));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_media(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $media = ReportMedia::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $media));
    }

    public function test_moderator_cannot_force_delete_media(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $media = ReportMedia::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $media));
    }

    public function test_admin_can_force_delete_media(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $media = ReportMedia::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $media));
    }
}
