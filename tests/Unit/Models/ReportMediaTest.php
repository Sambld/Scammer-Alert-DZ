<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\ReportMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_belongs_to_report(): void
    {
        $report = Report::factory()->create();
        $media = ReportMedia::factory()->create(['report_id' => $report->id]);

        $this->assertInstanceOf(Report::class, $media->report);
        $this->assertEquals($report->id, $media->report->id);
    }

    public function test_file_size_is_cast_to_integer(): void
    {
        $media = ReportMedia::factory()->create(['file_size' => '1024']);

        $this->assertIsInt($media->file_size);
        $this->assertEquals(1024, $media->file_size);
    }

    public function test_sort_order_is_cast_to_integer(): void
    {
        $media = ReportMedia::factory()->create(['sort_order' => '5']);

        $this->assertIsInt($media->sort_order);
        $this->assertEquals(5, $media->sort_order);
    }

    public function test_get_url_attribute_returns_asset_url(): void
    {
        $media = ReportMedia::factory()->create([
            'file_path' => 'reports/test-image.jpg',
        ]);

        $expectedUrl = asset('storage/reports/test-image.jpg');
        $this->assertEquals($expectedUrl, $media->url);
    }

    public function test_get_human_file_size_attribute_formats_bytes(): void
    {
        $media = ReportMedia::factory()->create(['file_size' => 1024]);
        $this->assertEquals('1 KB', $media->human_file_size);

        $media = ReportMedia::factory()->create(['file_size' => 1048576]);
        $this->assertEquals('1 MB', $media->human_file_size);

        $media = ReportMedia::factory()->create(['file_size' => 500]);
        $this->assertEquals('500 B', $media->human_file_size);
    }

    public function test_get_human_file_size_attribute_returns_unknown_for_null(): void
    {
        $media = ReportMedia::factory()->create(['file_size' => null]);
        
        $this->assertEquals('Unknown', $media->human_file_size);
    }

    public function test_is_image_returns_true_for_image_types(): void
    {
        $media = ReportMedia::factory()->create(['type' => 'image']);

        $this->assertTrue($media->isImage());
    }

    public function test_is_video_returns_true_for_video_types(): void
    {
        $media = ReportMedia::factory()->create(['type' => 'video']);

        $this->assertTrue($media->isVideo());
    }

    public function test_is_document_returns_true_for_document_types(): void
    {
        $media = ReportMedia::factory()->create(['type' => 'document']);

        $this->assertTrue($media->isDocument());
    }

    public function test_media_can_be_created_with_fillable_attributes(): void
    {
        $report = Report::factory()->create();

        $media = ReportMedia::create([
            'report_id' => $report->id,
            'type' => 'image',
            'file_name' => 'test.jpg',
            'file_path' => 'reports/test.jpg',
            'file_size' => 2048,
            'mime_type' => 'image/jpeg',
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('report_media', [
            'report_id' => $report->id,
            'file_name' => 'test.jpg',
            'type' => 'image',
        ]);
    }

    public function test_media_ordered_by_sort_order_in_report_relationship(): void
    {
        $report = Report::factory()->create();
        
        ReportMedia::factory()->create([
            'report_id' => $report->id,
            'sort_order' => 3,
        ]);
        ReportMedia::factory()->create([
            'report_id' => $report->id,
            'sort_order' => 1,
        ]);
        ReportMedia::factory()->create([
            'report_id' => $report->id,
            'sort_order' => 2,
        ]);

        $media = $report->media;

        $this->assertEquals(1, $media[0]->sort_order);
        $this->assertEquals(2, $media[1]->sort_order);
        $this->assertEquals(3, $media[2]->sort_order);
    }
}
