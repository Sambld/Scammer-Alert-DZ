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
        Schema::create('scammer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable();
            $table->string('social_handle', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('bank_identifier', 50)->nullable(); // RIP, CCP account, BaridiMob number, etc.
            
            $table->integer('reports_count')->default(1);
            $table->timestamp('last_reported_at')->useCurrent();
            
            $table->timestamps();
            
            // Indexes
            $table->index('phone');
            $table->index('social_handle');
            $table->index('bank_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scammer_profiles');
    }
};
