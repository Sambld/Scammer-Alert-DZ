<?php

namespace Tests\Unit\Policies;

use App\Models\Report;
use App\Models\ReportVote;
use App\Models\User;
use App\Policies\ReportVotePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportVotePolicyTest extends TestCase
{
    use RefreshDatabase;

    private ReportVotePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReportVotePolicy();
    }

    // ========== VIEW ANY TESTS ==========

    public function test_active_user_can_view_any_votes(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_inactive_user_cannot_view_any_votes(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    // ========== VIEW TESTS ==========

    public function test_active_user_can_view_vote(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $vote = ReportVote::factory()->create();

        $this->assertTrue($this->policy->view($user, $vote));
    }

    public function test_inactive_user_cannot_view_vote(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $vote = ReportVote::factory()->create();

        $this->assertFalse($this->policy->view($user, $vote));
    }

    // ========== CREATE TESTS ==========

    public function test_active_user_can_create_vote(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_inactive_user_cannot_create_vote(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertFalse($this->policy->create($user));
    }

    // ========== VOTE ON REPORT TESTS ==========

    public function test_user_can_vote_on_others_report(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->voteOnReport($user, $report));
    }

    public function test_user_cannot_vote_on_own_report(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->voteOnReport($user, $report));
    }

    public function test_inactive_user_cannot_vote_on_any_report(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $otherUser = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->voteOnReport($user, $report));
    }

    // ========== UPDATE TESTS ==========

    public function test_user_can_update_own_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $vote = ReportVote::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $vote));
    }

    public function test_user_cannot_update_others_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $vote));
    }

    public function test_moderator_can_update_any_vote(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($moderator, $vote));
    }

    public function test_admin_can_update_any_vote(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->update($admin, $vote));
    }

    public function test_inactive_user_cannot_update_own_vote(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $vote = ReportVote::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->update($user, $vote));
    }

    // ========== DELETE TESTS ==========

    public function test_user_can_delete_own_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $vote = ReportVote::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $vote));
    }

    public function test_user_cannot_delete_others_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $vote));
    }

    public function test_moderator_can_delete_any_vote(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->delete($moderator, $vote));
    }

    public function test_admin_can_delete_any_vote(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $otherUser = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $otherUser->id]);

        $this->assertTrue($this->policy->delete($admin, $vote));
    }

    public function test_inactive_user_cannot_delete_own_vote(): void
    {
        $user = User::factory()->create(['is_active' => false, 'role' => 'user']);
        $vote = ReportVote::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($this->policy->delete($user, $vote));
    }

    // ========== RESTORE TESTS ==========

    public function test_regular_user_cannot_restore_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $vote = ReportVote::factory()->create();

        $this->assertFalse($this->policy->restore($user, $vote));
    }

    public function test_moderator_can_restore_vote(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $vote = ReportVote::factory()->create();

        $this->assertTrue($this->policy->restore($moderator, $vote));
    }

    public function test_admin_can_restore_vote(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $vote = ReportVote::factory()->create();

        $this->assertTrue($this->policy->restore($admin, $vote));
    }

    // ========== FORCE DELETE TESTS ==========

    public function test_regular_user_cannot_force_delete_vote(): void
    {
        $user = User::factory()->create(['is_active' => true, 'role' => 'user']);
        $vote = ReportVote::factory()->create();

        $this->assertFalse($this->policy->forceDelete($user, $vote));
    }

    public function test_moderator_cannot_force_delete_vote(): void
    {
        $moderator = User::factory()->create(['is_active' => true, 'role' => 'moderator']);
        $vote = ReportVote::factory()->create();

        $this->assertFalse($this->policy->forceDelete($moderator, $vote));
    }

    public function test_admin_can_force_delete_vote(): void
    {
        $admin = User::factory()->create(['is_active' => true, 'role' => 'admin']);
        $vote = ReportVote::factory()->create();

        $this->assertTrue($this->policy->forceDelete($admin, $vote));
    }

    public function test_inactive_admin_cannot_force_delete_vote(): void
    {
        $admin = User::factory()->create(['is_active' => false, 'role' => 'admin']);
        $vote = ReportVote::factory()->create();

        $this->assertFalse($this->policy->forceDelete($admin, $vote));
    }
}
