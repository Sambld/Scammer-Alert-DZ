<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ScammerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScammerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_count_is_cast_to_integer(): void
    {
        $profile = ScammerProfile::factory()->create(['reports_count' => '5']);

        $this->assertIsInt($profile->reports_count);
        $this->assertEquals(5, $profile->reports_count);
    }

    public function test_last_reported_at_is_cast_to_datetime(): void
    {
        $profile = ScammerProfile::factory()->create([
            'last_reported_at' => '2025-01-15 10:30:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $profile->last_reported_at);
    }

    public function test_by_phone_scope_searches_phone_numbers(): void
    {
        ScammerProfile::factory()->create(['phone' => '+213555123456']);
        ScammerProfile::factory()->create(['phone' => '+213666789012']);
        ScammerProfile::factory()->create(['phone' => '+213555999888']);

        $results = ScammerProfile::byPhone('555')->get();

        $this->assertCount(2, $results);
    }

    public function test_by_social_handle_scope_searches_social_handles(): void
    {
        ScammerProfile::factory()->create(['social_handle' => '@scammer1']);
        ScammerProfile::factory()->create(['social_handle' => '@scammer2']);
        ScammerProfile::factory()->create(['social_handle' => '@legitimate']);

        $results = ScammerProfile::bySocialHandle('scammer')->get();

        $this->assertCount(2, $results);
    }

    public function test_by_bank_identifier_scope_searches_bank_identifiers(): void
    {
        ScammerProfile::factory()->create(['bank_identifier' => '00799123456789']);
        ScammerProfile::factory()->create(['bank_identifier' => '00799987654321']);
        ScammerProfile::factory()->create(['bank_identifier' => '00712345678901']);

        $results = ScammerProfile::byBankIdentifier('00799')->get();

        $this->assertCount(2, $results);
    }

    public function test_by_name_scope_searches_names(): void
    {
        ScammerProfile::factory()->create(['name' => 'Mohamed Scammer']);
        ScammerProfile::factory()->create(['name' => 'Ahmed Scammer']);
        ScammerProfile::factory()->create(['name' => 'Fatima Legitimate']);

        $results = ScammerProfile::byName('Scammer')->get();

        $this->assertCount(2, $results);
    }

    public function test_search_scope_searches_across_all_fields(): void
    {
        ScammerProfile::factory()->create([
            'phone' => '+213555123456',
            'social_handle' => '@scammer1',
            'name' => 'John Doe',
            'bank_identifier' => '00799123456789',
        ]);
        
        ScammerProfile::factory()->create([
            'phone' => '+213666789012',
            'social_handle' => '@other',
            'name' => 'Jane Smith',
            'bank_identifier' => '00712345678901',
        ]);

        $resultsByPhone = ScammerProfile::search('555')->get();
        $resultsByHandle = ScammerProfile::search('scammer1')->get();
        $resultsByName = ScammerProfile::search('John')->get();
        $resultsByBank = ScammerProfile::search('00799')->get();

        $this->assertCount(1, $resultsByPhone);
        $this->assertCount(1, $resultsByHandle);
        $this->assertCount(1, $resultsByName);
        $this->assertCount(1, $resultsByBank);
    }

    public function test_most_reported_scope_returns_top_scammers(): void
    {
        ScammerProfile::factory()->create(['reports_count' => 10]);
        ScammerProfile::factory()->create(['reports_count' => 5]);
        ScammerProfile::factory()->create(['reports_count' => 15]);
        ScammerProfile::factory()->create(['reports_count' => 3]);

        $mostReported = ScammerProfile::mostReported(2)->get();

        $this->assertCount(2, $mostReported);
        $this->assertEquals(15, $mostReported[0]->reports_count);
        $this->assertEquals(10, $mostReported[1]->reports_count);
    }

    public function test_recently_reported_scope_returns_recent_scammers(): void
    {
        ScammerProfile::factory()->create([
            'last_reported_at' => now()->subDays(5),
        ]);
        ScammerProfile::factory()->create([
            'last_reported_at' => now()->subDays(40),
        ]);
        ScammerProfile::factory()->create([
            'last_reported_at' => now()->subDays(10),
        ]);

        $recentlyReported = ScammerProfile::recentlyReported(30)->get();

        $this->assertCount(2, $recentlyReported);
    }

    public function test_get_associated_reports_finds_reports_by_phone(): void
    {
        $profile = ScammerProfile::factory()->create([
            'phone' => '+213555123456',
        ]);

        Report::factory()->create(['scammer_phone' => '+213555123456']);
        Report::factory()->create(['scammer_phone' => '+213555123456']);
        Report::factory()->create(['scammer_phone' => '+213666789012']);

        $associatedReports = $profile->getAssociatedReports();

        $this->assertCount(2, $associatedReports);
    }

    public function test_get_associated_reports_finds_reports_by_social_handle(): void
    {
        $profile = ScammerProfile::factory()->create([
            'social_handle' => '@scammer123',
        ]);

        Report::factory()->create(['scammer_social_handle' => '@scammer123']);
        Report::factory()->create(['scammer_social_handle' => '@scammer123']);
        Report::factory()->create(['scammer_social_handle' => '@other']);

        $associatedReports = $profile->getAssociatedReports();

        $this->assertCount(2, $associatedReports);
    }

    public function test_get_associated_reports_finds_reports_by_name(): void
    {
        $profile = ScammerProfile::factory()->create([
            'name' => 'John Scammer',
        ]);

        Report::factory()->create(['scammer_name' => 'John Scammer']);
        Report::factory()->create(['scammer_name' => 'John Scammer']);
        Report::factory()->create(['scammer_name' => 'Jane Doe']);

        $associatedReports = $profile->getAssociatedReports();

        $this->assertCount(2, $associatedReports);
    }

    public function test_get_associated_reports_finds_reports_by_bank_identifier(): void
    {
        $profile = ScammerProfile::factory()->create([
            'bank_identifier' => '00799123456789',
        ]);

        Report::factory()->create(['scammer_bank_identifier' => '00799123456789']);
        Report::factory()->create(['scammer_bank_identifier' => '00799123456789']);
        Report::factory()->create(['scammer_bank_identifier' => '00712345678901']);

        $associatedReports = $profile->getAssociatedReports();

        $this->assertCount(2, $associatedReports);
    }

    public function test_scammer_profile_can_be_created_with_fillable_attributes(): void
    {
        $profile = ScammerProfile::create([
            'phone' => '+213555123456',
            'social_handle' => '@scammer',
            'name' => 'Test Scammer',
            'bank_identifier' => '00799123456789',
            'reports_count' => 5,
            'last_reported_at' => now(),
        ]);

        $this->assertDatabaseHas('scammer_profiles', [
            'phone' => '+213555123456',
            'social_handle' => '@scammer',
            'name' => 'Test Scammer',
        ]);
    }
}
