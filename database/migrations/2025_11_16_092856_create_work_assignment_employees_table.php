<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_assignment_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->text('detail')->nullable(); // Task detail for this employee
            $table->timestamps();

            // Indexes
            $table->index('work_assignment_id');
            $table->index('employee_id');
            $table->unique(['work_assignment_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignment_employees');
    }
};
