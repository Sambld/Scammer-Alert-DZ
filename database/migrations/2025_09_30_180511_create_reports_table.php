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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('scam_categories');
            $table->foreignId('platform_id')->nullable()->constrained();
            
            // Report content
            $table->string('title', 255);
            $table->text('description');
            
            // Scammer info
            $table->string('scammer_name', 255)->nullable();
            $table->text('scammer_phone')->nullable();
            $table->string('scammer_social_handle', 255)->nullable(); // Username/profile name
            $table->text('scammer_profile_url')->nullable();
            $table->string('scammer_bank_identifier', 100)->nullable(); // RIP, CCP account, BaridiMob number, etc.
            
            $table->date('incident_date')->nullable();
            
            // Status & moderation
            $table->enum('status', ['pending', 'verified', 'investigating', 'resolved', 'rejected'])->default('pending');
            $table->foreignId('moderator_id')->nullable()->constrained('users');
            $table->text('moderator_notes')->nullable();
            $table->timestamp('moderated_at')->nullable();
            
            // Stats
            $table->integer('views_count')->default(0);
            $table->integer('upvotes_count')->default(0);
            $table->integer('downvotes_count')->default(0);
            
            // Meta
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('category_id');
            $table->index('platform_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
