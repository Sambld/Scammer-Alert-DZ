<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\ReportVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_reports(): void
    {
        $user = User::factory()->create();
        Report::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->reports);
        $this->assertInstanceOf(Report::class, $user->reports->first());
    }

    public function test_user_has_many_moderated_reports(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        Report::factory()->count(2)->create(['moderator_id' => $moderator->id]);

        $this->assertCount(2, $moderator->moderatedReports);
        $this->assertInstanceOf(Report::class, $moderator->moderatedReports->first());
    }

    public function test_user_has_many_comments(): void
    {
        $user = User::factory()->create();
        ReportComment::factory()->count(4)->create(['user_id' => $user->id]);

        $this->assertCount(4, $user->comments);
        $this->assertInstanceOf(ReportComment::class, $user->comments->first());
    }

    public function test_user_has_many_votes(): void
    {
        $user = User::factory()->create();
        ReportVote::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->votes);
        $this->assertInstanceOf(ReportVote::class, $user->votes->first());
    }

    public function test_is_admin_returns_true_for_admin_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $moderator = User::factory()->create(['role' => 'moderator']);

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($moderator->isAdmin());
    }

    public function test_is_moderator_returns_true_for_moderator(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);

        $this->assertTrue($moderator->isModerator());
    }

    public function test_is_moderator_returns_true_for_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isModerator());
    }

    public function test_is_moderator_returns_false_for_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->assertFalse($user->isModerator());
    }

    public function test_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(\Hash::check('password123', $user->password));
    }

    public function test_password_is_hidden_in_array(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
    }

    public function test_remember_token_is_hidden_in_array(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_email_verified_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => '2025-01-01 12:00:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $activeUser = User::factory()->create(['is_active' => 1]);
        $inactiveUser = User::factory()->create(['is_active' => 0]);

        $this->assertTrue($activeUser->is_active);
        $this->assertFalse($inactiveUser->is_active);
        $this->assertIsBool($activeUser->is_active);
        $this->assertIsBool($inactiveUser->is_active);
    }

    public function test_user_can_be_created_with_fillable_attributes(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'user',
        ]);
    }
}
