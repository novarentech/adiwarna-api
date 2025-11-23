<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_assignment_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_assignment_id')->constrained()->onDelete('cascade');
            $table->string('worker_name');
            $table->string('position');
            $table->timestamps();

            // Indexes
            $table->index('work_assignment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignment_workers');
    }
};
