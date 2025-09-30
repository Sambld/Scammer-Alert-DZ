<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['image', 'video', 'document', 'audio']);
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->integer('file_size')->nullable(); // Size in bytes
            $table->string('mime_type', 100)->nullable();
            
            $table->integer('sort_order')->default(0);
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index(['report_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_media');
    }
};
