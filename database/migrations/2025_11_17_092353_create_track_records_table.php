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
        Schema::create('track_records', function (Blueprint $table) {
            $table->id();
            $table->string('project_name', 255);
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->string('status', 50);
            $table->text('description');
            $table->json('milestones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index('status');
            $table->index('project_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_records');
    }
};
