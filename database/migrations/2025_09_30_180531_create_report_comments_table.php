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
        Schema::create('report_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('report_comments')->onDelete('cascade'); // For threaded comments
            
            $table->text('content');
            $table->boolean('is_from_victim')->default(false); // If commenter is also a victim
            
            $table->enum('status', ['active', 'hidden', 'deleted'])->default('active');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['report_id', 'status']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_comments');
    }
};
