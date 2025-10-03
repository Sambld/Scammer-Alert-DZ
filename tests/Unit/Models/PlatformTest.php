<?php

namespace Tests\Unit\Models;

use App\Models\Platform;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_has_many_reports(): void
    {
        $platform = Platform::factory()->create();
        Report::factory()->count(3)->create(['platform_id' => $platform->id]);

        $this->assertCount(3, $platform->reports);
        $this->assertInstanceOf(Report::class, $platform->reports->first());
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $activePlatform = Platform::factory()->create(['is_active' => 1]);
        $inactivePlatform = Platform::factory()->create(['is_active' => 0]);

        $this->assertTrue($activePlatform->is_active);
        $this->assertFalse($inactivePlatform->is_active);
        $this->assertIsBool($activePlatform->is_active);
    }

    public function test_active_scope_returns_only_active_platforms(): void
    {
        Platform::factory()->count(3)->create(['is_active' => true]);
        Platform::factory()->count(2)->create(['is_active' => false]);

        $activePlatforms = Platform::active()->get();

        $this->assertCount(3, $activePlatforms);
        $this->assertTrue($activePlatforms->every(fn($platform) => $platform->is_active === true));
    }

    public function test_platform_can_be_created_with_fillable_attributes(): void
    {
        $platform = Platform::create([
            'name' => 'Facebook',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('platforms', [
            'name' => 'Facebook',
            'is_active' => true,
        ]);
    }
}
