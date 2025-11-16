<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('attendance_status', ['present', 'absent', 'leave', 'sick', 'holiday'])->default('present');
            $table->decimal('regular_hours', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('total_allowances', 15, 2)->default(0);
            $table->decimal('total_overtime_pay', 15, 2)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('payroll_employee_id');
            $table->index('date');
            $table->unique(['payroll_employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_timesheets');
    }
};
