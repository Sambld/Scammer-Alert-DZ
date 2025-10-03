<?php

namespace Tests\Unit\Policies;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use App\Policies\ReportCommentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCommentPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ReportCommentPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReportCommentPolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_comments(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_comments(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_comment(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $comment = ReportComment::factory()->create();

        $this->assertTrue($this->policy->view($user, $comment));
    }

    public function test_inactive_user_cannot_view_comment(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $comment = ReportComment::factory()->create();

        $this->assertFalse($this->policy->view($user, $comment));
    }

    // ========== CREATE TESTS ==========

    public function test_active_user_can_create_comment(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_inactive_user_cannot_create_comment(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->create($user));
    }

    // ========== UPDATE TESTS ==========

    public function test_user_can_update_own_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $comment = ReportComment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $comment));
    }

    public function test_user_cannot_update_others_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $comment));
    }

    public function test_moderator_can_update_any_comment(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($moderator, $comment));
    }

    public function test_admin_can_update_any_comment(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($admin, $comment));
    }

    public function test_inactive_user_cannot_update_own_comment(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $comment = ReportComment::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->update($user, $comment));
    }

    // ========== DELETE TESTS ==========

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $comment = ReportComment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $comment));
    }

    public function test_user_cannot_delete_others_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $comment));
    }

    public function test_moderator_can_delete_any_comment(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->delete($moderator, $comment));
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->delete($admin, $comment));
    }

    public function test_inactive_user_cannot_delete_own_comment(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $comment = ReportComment::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->delete($user, $comment));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $comment = ReportComment::factory()->create();

        $this->assertFalse($this->policy->restore($user, $comment));
    }

    public function test_moderator_can_restore_comment(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $comment = ReportComment::factory()->create();

        $this->assertTrue($this->policy->restore($moderator, $comment));
    }

    public function test_admin_can_restore_comment(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $comment = ReportComment::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $comment));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_comment(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $comment = ReportComment::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $comment));
    }

    public function test_moderator_cannot_force_delete_comment(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $comment = ReportComment::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $comment));
    }

    public function test_admin_can_force_delete_comment(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $comment = ReportComment::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $comment));
    }

    public function test_inactive_admin_cannot_force_delete_comment(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $comment = ReportComment::factory()->create();

        $this->assertFalse($this->policy->forceDelete($admin, $comment));
    }
}
