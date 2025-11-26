<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_employee_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('slip_type', ['period', 'employee', 'all_employees'])->default('employee');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('payroll_period_id');
            $table->index('payroll_employee_id');
            $table->index('slip_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_slips');
    }
};
