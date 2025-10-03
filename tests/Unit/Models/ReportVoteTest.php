<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ReportVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportVoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_vote_belongs_to_report(): void
    {
        $report = Report::factory()->create();
        $vote = ReportVote::factory()->create(['report_id' => $report->id]);

        $this->assertInstanceOf(Report::class, $vote->report);
        $this->assertEquals($report->id, $vote->report->id);
    }

    public function test_vote_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $vote = ReportVote::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $vote->user);
        $this->assertEquals($user->id, $vote->user->id);
    }

    public function test_upvotes_scope_returns_only_upvotes(): void
    {
        ReportVote::factory()->count(3)->create(['vote_type' => 'upvote']);
        ReportVote::factory()->count(2)->create(['vote_type' => 'downvote']);

        $upvotes = ReportVote::upvotes()->get();

        $this->assertCount(3, $upvotes);
        $this->assertTrue($upvotes->every(fn($vote) => $vote->vote_type === 'upvote'));
    }

    public function test_downvotes_scope_returns_only_downvotes(): void
    {
        ReportVote::factory()->count(3)->create(['vote_type' => 'upvote']);
        ReportVote::factory()->count(2)->create(['vote_type' => 'downvote']);

        $downvotes = ReportVote::downvotes()->get();

        $this->assertCount(2, $downvotes);
        $this->assertTrue($downvotes->every(fn($vote) => $vote->vote_type === 'downvote'));
    }

    public function test_is_upvote_returns_true_for_upvote(): void
    {
        $upvote = ReportVote::factory()->create(['vote_type' => 'upvote']);

        $this->assertTrue($upvote->isUpvote());
    }

    public function test_is_upvote_returns_false_for_downvote(): void
    {
        $downvote = ReportVote::factory()->create(['vote_type' => 'downvote']);

        $this->assertFalse($downvote->isUpvote());
    }

    public function test_is_downvote_returns_true_for_downvote(): void
    {
        $downvote = ReportVote::factory()->create(['vote_type' => 'downvote']);

        $this->assertTrue($downvote->isDownvote());
    }

    public function test_is_downvote_returns_false_for_upvote(): void
    {
        $upvote = ReportVote::factory()->create(['vote_type' => 'upvote']);

        $this->assertFalse($upvote->isDownvote());
    }

    public function test_creating_vote_updates_report_vote_counts(): void
    {
        $report = Report::factory()->create([
            'upvotes_count' => 0,
            'downvotes_count' => 0,
        ]);

        ReportVote::factory()->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);

        $this->assertEquals(1, $report->fresh()->upvotes_count);
    }

    public function test_updating_vote_updates_report_vote_counts(): void
    {
        $report = Report::factory()->create([
            'upvotes_count' => 0,
            'downvotes_count' => 0,
        ]);

        $vote = ReportVote::factory()->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);

        $this->assertEquals(1, $report->fresh()->upvotes_count);
        $this->assertEquals(0, $report->fresh()->downvotes_count);

        $vote->update(['vote_type' => 'downvote']);

        $this->assertEquals(0, $report->fresh()->upvotes_count);
        $this->assertEquals(1, $report->fresh()->downvotes_count);
    }

    public function test_deleting_vote_updates_report_vote_counts(): void
    {
        $report = Report::factory()->create([
            'upvotes_count' => 0,
            'downvotes_count' => 0,
        ]);

        $vote = ReportVote::factory()->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);

        $this->assertEquals(1, $report->fresh()->upvotes_count);

        $vote->delete();

        $this->assertEquals(0, $report->fresh()->upvotes_count);
    }

    public function test_vote_can_be_created_with_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $report = Report::factory()->create();

        $vote = ReportVote::create([
            'report_id' => $report->id,
            'user_id' => $user->id,
            'vote_type' => 'upvote',
        ]);

        $this->assertDatabaseHas('report_votes', [
            'report_id' => $report->id,
            'user_id' => $user->id,
            'vote_type' => 'upvote',
        ]);
    }
}
