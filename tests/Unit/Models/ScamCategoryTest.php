<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ScamCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScamCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_reports(): void
    {
        $category = ScamCategory::factory()->create();
        Report::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->reports);
        $this->assertInstanceOf(Report::class, $category->reports->first());
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $activeCategory = ScamCategory::factory()->create(['is_active' => 1]);
        $inactiveCategory = ScamCategory::factory()->create(['is_active' => 0]);

        $this->assertTrue($activeCategory->is_active);
        $this->assertFalse($inactiveCategory->is_active);
        $this->assertIsBool($activeCategory->is_active);
    }

    public function test_active_scope_returns_only_active_categories(): void
    {
        ScamCategory::factory()->count(3)->create(['is_active' => true]);
        ScamCategory::factory()->count(2)->create(['is_active' => false]);

        $activeCategories = ScamCategory::active()->get();

        $this->assertCount(3, $activeCategories);
        $this->assertTrue($activeCategories->every(fn($cat) => $cat->is_active === true));
    }

    public function test_category_can_be_created_with_fillable_attributes(): void
    {
        $category = ScamCategory::create([
            'name' => 'Edahabia Scam',
            'name_ar' => 'احتيال الذهبية',
            'name_fr' => 'Escroquerie Edahabia',
            'description' => 'Scams involving Edahabia cards',
            'description_ar' => 'احتيالات تتعلق ببطاقات الذهبية',
            'description_fr' => 'Escroqueries impliquant des cartes Edahabia',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('scam_categories', [
            'name' => 'Edahabia Scam',
            'name_ar' => 'احتيال الذهبية',
            'is_active' => true,
        ]);
    }

    public function test_category_supports_multiple_languages(): void
    {
        $category = ScamCategory::factory()->create([
            'name' => 'USDT Scam',
            'name_ar' => 'احتيال USDT',
            'name_fr' => 'Escroquerie USDT',
        ]);

        $this->assertEquals('USDT Scam', $category->name);
        $this->assertEquals('احتيال USDT', $category->name_ar);
        $this->assertEquals('Escroquerie USDT', $category->name_fr);
    }
}
