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
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('reports_count')->default(0);
            $table->integer('verified_reports_count')->default(0);
            $table->integer('new_users_count')->default(0);
            $table->decimal('total_amount_lost', 15, 2)->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_stats');
    }
};
