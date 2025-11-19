<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_activity_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_activity_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->timestamps();

            // Indexes
            $table->index('daily_activity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_activity_descriptions');
    }
};
