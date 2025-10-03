<?php

namespace Tests\Unit\Models;

use App\Models\Platform;
use App\Models\Report;
use App\Models\ReportComment;
use App\Models\ReportMedia;
use App\Models\ReportVote;
use App\Models\ScamCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $report = Report::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $report->user);
        $this->assertEquals($user->id, $report->user->id);
    }

    public function test_report_belongs_to_category(): void
    {
        $category = ScamCategory::factory()->create();
        $report = Report::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(ScamCategory::class, $report->category);
        $this->assertEquals($category->id, $report->category->id);
    }

    public function test_report_belongs_to_platform(): void
    {
        $platform = Platform::factory()->create();
        $report = Report::factory()->create(['platform_id' => $platform->id]);

        $this->assertInstanceOf(Platform::class, $report->platform);
        $this->assertEquals($platform->id, $report->platform->id);
    }

    public function test_report_belongs_to_moderator(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $report = Report::factory()->create(['moderator_id' => $moderator->id]);

        $this->assertInstanceOf(User::class, $report->moderator);
        $this->assertEquals($moderator->id, $report->moderator->id);
    }

    public function test_report_has_many_media(): void
    {
        $report = Report::factory()->create();
        $media = ReportMedia::factory()->count(3)->create(['report_id' => $report->id]);

        $this->assertCount(3, $report->media);
        $this->assertInstanceOf(ReportMedia::class, $report->media->first());
    }

    public function test_report_has_many_comments(): void
    {
        $report = Report::factory()->create();
        ReportComment::factory()->count(2)->create([
            'report_id' => $report->id,
            'status' => 'active',
        ]);
        ReportComment::factory()->create([
            'report_id' => $report->id,
            'status' => 'hidden',
        ]);

        $this->assertCount(2, $report->comments);
        $this->assertCount(3, $report->allComments);
    }

    public function test_report_has_many_votes(): void
    {
        $report = Report::factory()->create();
        ReportVote::factory()->count(3)->create(['report_id' => $report->id]);

        $this->assertCount(3, $report->votes);
        $this->assertInstanceOf(ReportVote::class, $report->votes->first());
    }

    public function test_report_can_get_upvotes_only(): void
    {
        $report = Report::factory()->create();
        ReportVote::factory()->count(3)->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);
        ReportVote::factory()->count(2)->create([
            'report_id' => $report->id,
            'vote_type' => 'downvote',
        ]);

        $this->assertCount(3, $report->upvotes);
    }

    public function test_report_can_get_downvotes_only(): void
    {
        $report = Report::factory()->create();
        ReportVote::factory()->count(3)->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);
        ReportVote::factory()->count(2)->create([
            'report_id' => $report->id,
            'vote_type' => 'downvote',
        ]);

        $this->assertCount(2, $report->downvotes);
    }

    public function test_verified_scope_returns_verified_reports(): void
    {
        Report::factory()->count(3)->create(['status' => 'verified']);
        Report::factory()->count(2)->create(['status' => 'pending']);

        $verifiedReports = Report::verified()->get();

        $this->assertCount(3, $verifiedReports);
        $this->assertTrue($verifiedReports->every(fn($report) => $report->status === 'verified'));
    }

    public function test_pending_scope_returns_pending_reports(): void
    {
        Report::factory()->count(2)->create(['status' => 'verified']);
        Report::factory()->count(3)->create(['status' => 'pending']);

        $pendingReports = Report::pending()->get();

        $this->assertCount(3, $pendingReports);
        $this->assertTrue($pendingReports->every(fn($report) => $report->status === 'pending'));
    }

    public function test_by_status_scope_filters_by_status(): void
    {
        Report::factory()->count(2)->create(['status' => 'investigating']);
        Report::factory()->count(3)->create(['status' => 'resolved']);

        $investigatingReports = Report::byStatus('investigating')->get();

        $this->assertCount(2, $investigatingReports);
        $this->assertTrue($investigatingReports->every(fn($report) => $report->status === 'investigating'));
    }

    public function test_is_verified_returns_true_for_verified_report(): void
    {
        $report = Report::factory()->create(['status' => 'verified']);

        $this->assertTrue($report->isVerified());
    }

    public function test_is_verified_returns_false_for_non_verified_report(): void
    {
        $report = Report::factory()->create(['status' => 'pending']);

        $this->assertFalse($report->isVerified());
    }

    public function test_is_pending_returns_true_for_pending_report(): void
    {
        $report = Report::factory()->create(['status' => 'pending']);

        $this->assertTrue($report->isPending());
    }

    public function test_is_pending_returns_false_for_non_pending_report(): void
    {
        $report = Report::factory()->create(['status' => 'verified']);

        $this->assertFalse($report->isPending());
    }

    public function test_increment_views_increases_views_count(): void
    {
        $report = Report::factory()->create(['views_count' => 5]);

        $report->incrementViews();

        $this->assertEquals(6, $report->fresh()->views_count);
    }

    public function test_update_vote_counts_updates_upvotes_and_downvotes(): void
    {
        $report = Report::factory()->create([
            'upvotes_count' => 0,
            'downvotes_count' => 0,
        ]);

        ReportVote::factory()->count(3)->create([
            'report_id' => $report->id,
            'vote_type' => 'upvote',
        ]);
        ReportVote::factory()->count(2)->create([
            'report_id' => $report->id,
            'vote_type' => 'downvote',
        ]);

        $report->updateVoteCounts();

        $this->assertEquals(3, $report->fresh()->upvotes_count);
        $this->assertEquals(2, $report->fresh()->downvotes_count);
    }

    public function test_report_casts_dates_correctly(): void
    {
        $report = Report::factory()->create([
            'incident_date' => '2025-01-15',
            'moderated_at' => '2025-01-20 10:30:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $report->incident_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $report->moderated_at);
    }

    public function test_report_hides_sensitive_attributes(): void
    {
        $report = Report::factory()->create([
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
        ]);

        $array = $report->toArray();

        $this->assertArrayNotHasKey('ip_address', $array);
        $this->assertArrayNotHasKey('user_agent', $array);
    }
}
