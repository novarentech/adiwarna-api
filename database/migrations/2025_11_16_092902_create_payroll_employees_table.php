<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->onDelete('cascade');
            $table->string('employee_name');
            $table->string('employee_no', 50)->nullable();
            $table->string('tax_status', 50)->nullable();
            $table->string('employment_status', 50)->nullable();
            $table->decimal('base_salary', 15, 2);
            $table->decimal('working_hours', 8, 2)->default(173.00);
            $table->string('employee_type', 50)->nullable();
            $table->enum('employee_category', ['staff', 'non-staff'])->default('staff');
            $table->string('position')->nullable();

            // JSON configurations
            $table->json('allowances_config')->nullable();
            $table->json('deductions_config')->nullable();
            $table->json('bpjs_allowances_included')->nullable();
            $table->text('notes')->nullable();

            // Calculated fields
            $table->integer('total_working_days')->default(0);
            $table->integer('total_present_days')->default(0);
            $table->decimal('total_overtime_hours', 10, 2)->default(0);
            $table->decimal('total_overday_hours', 10, 2)->default(0);
            $table->decimal('total_allowances', 15, 2)->default(0);

            // BPJS calculations
            $table->decimal('bpjs_base_salary', 15, 2)->default(0);
            $table->decimal('bpjs_jht_deduction', 15, 2)->default(0);
            $table->decimal('bpjs_pensiun_deduction', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_deduction', 15, 2)->default(0);

            // PPh21
            $table->decimal('pph21_amount', 15, 2)->default(0);

            // Final calculations
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);

            $table->timestamps();

            // Indexes
            $table->index('payroll_period_id');
            $table->index('employee_no');
            $table->index('employee_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_employees');
    }
};
