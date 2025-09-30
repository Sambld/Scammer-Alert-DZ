<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportMedia>
 */
class ReportMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['image', 'video', 'document', 'audio']);
        $extensions = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'video' => ['mp4', 'avi', 'mov', 'webm'],
            'document' => ['pdf', 'doc', 'docx', 'txt'],
            'audio' => ['mp3', 'wav', 'ogg', 'm4a'],
        ];
        
        $extension = fake()->randomElement($extensions[$type]);
        $fileName = fake()->word() . '.' . $extension;
        
        return [
            'report_id' => \App\Models\Report::factory(),
            'type' => $type,
            'file_name' => $fileName,
            'file_path' => 'reports/' . fake()->uuid() . '/' . $fileName,
            'file_size' => fake()->numberBetween(1024, 10485760), // 1KB to 10MB
            'mime_type' => $this->getMimeType($type, $extension),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    /**
     * Get mime type based on file type and extension.
     */
    private function getMimeType(string $type, string $extension): string
    {
        return match($type) {
            'image' => match($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                default => 'image/jpeg',
            },
            'video' => match($extension) {
                'mp4' => 'video/mp4',
                'avi' => 'video/x-msvideo',
                'mov' => 'video/quicktime',
                'webm' => 'video/webm',
                default => 'video/mp4',
            },
            'document' => match($extension) {
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'txt' => 'text/plain',
                default => 'application/pdf',
            },
            'audio' => match($extension) {
                'mp3' => 'audio/mpeg',
                'wav' => 'audio/wav',
                'ogg' => 'audio/ogg',
                'm4a' => 'audio/mp4',
                default => 'audio/mpeg',
            },
        };
    }

    /**
     * Create an image file.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
        ]);
    }

    /**
     * Create a video file.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'video',
        ]);
    }

    /**
     * Create a document file.
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'document',
        ]);
    }
}
