<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_belongs_to_report(): void
    {
        $report = Report::factory()->create();
        $comment = ReportComment::factory()->create(['report_id' => $report->id]);

        $this->assertInstanceOf(Report::class, $comment->report);
        $this->assertEquals($report->id, $comment->report->id);
    }

    public function test_comment_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $comment = ReportComment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_can_have_parent_comment(): void
    {
        $parentComment = ReportComment::factory()->create();
        $childComment = ReportComment::factory()->create(['parent_id' => $parentComment->id]);

        $this->assertInstanceOf(ReportComment::class, $childComment->parent);
        $this->assertEquals($parentComment->id, $childComment->parent->id);
    }

    public function test_comment_can_have_child_comments(): void
    {
        $parentComment = ReportComment::factory()->create();
        ReportComment::factory()->count(3)->create([
            'parent_id' => $parentComment->id,
            'status' => 'active',
        ]);
        ReportComment::factory()->create([
            'parent_id' => $parentComment->id,
            'status' => 'hidden',
        ]);

        $this->assertCount(3, $parentComment->children);
        $this->assertCount(4, $parentComment->allChildren);
    }

    public function test_active_scope_returns_only_active_comments(): void
    {
        ReportComment::factory()->count(3)->create(['status' => 'active']);
        ReportComment::factory()->count(2)->create(['status' => 'hidden']);

        $activeComments = ReportComment::active()->get();

        $this->assertCount(3, $activeComments);
        $this->assertTrue($activeComments->every(fn($comment) => $comment->status === 'active'));
    }

    public function test_top_level_scope_returns_comments_without_parent(): void
    {
        ReportComment::factory()->count(2)->create(['parent_id' => null]);
        $parent = ReportComment::factory()->create(['parent_id' => null]);
        ReportComment::factory()->count(3)->create(['parent_id' => $parent->id]);

        $topLevelComments = ReportComment::topLevel()->get();

        $this->assertCount(3, $topLevelComments);
        $this->assertTrue($topLevelComments->every(fn($comment) => $comment->parent_id === null));
    }

    public function test_replies_to_scope_returns_replies_to_specific_comment(): void
    {
        $parent1 = ReportComment::factory()->create();
        $parent2 = ReportComment::factory()->create();
        
        ReportComment::factory()->count(2)->create(['parent_id' => $parent1->id]);
        ReportComment::factory()->count(3)->create(['parent_id' => $parent2->id]);

        $repliesTo1 = ReportComment::repliesTo($parent1->id)->get();

        $this->assertCount(2, $repliesTo1);
        $this->assertTrue($repliesTo1->every(fn($comment) => $comment->parent_id === $parent1->id));
    }

    public function test_is_from_victim_is_cast_to_boolean(): void
    {
        $victimComment = ReportComment::factory()->create(['is_from_victim' => 1]);
        $regularComment = ReportComment::factory()->create(['is_from_victim' => 0]);

        $this->assertTrue($victimComment->is_from_victim);
        $this->assertFalse($regularComment->is_from_victim);
        $this->assertIsBool($victimComment->is_from_victim);
    }

    public function test_comment_can_be_active(): void
    {
        $comment = ReportComment::factory()->create(['status' => 'active']);

        $this->assertTrue($comment->isActive());
    }

    public function test_comment_can_be_hidden(): void
    {
        $comment = ReportComment::factory()->create(['status' => 'hidden']);

        $this->assertTrue($comment->isHidden());
    }

    public function test_comment_can_check_if_reply(): void
    {
        $parent = ReportComment::factory()->create();
        $reply = ReportComment::factory()->create(['parent_id' => $parent->id]);
        $topLevel = ReportComment::factory()->create(['parent_id' => null]);

        $this->assertTrue($reply->isReply());
        $this->assertFalse($topLevel->isReply());
    }
}
