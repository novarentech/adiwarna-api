<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_project_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');

            // Configuration flags
            $table->boolean('enable_progressive_ot')->default(false);
            $table->boolean('enable_bpjs')->default(false);
            $table->boolean('enable_pph21')->default(false);
            $table->boolean('enable_meal_allowance')->default(false);
            $table->boolean('enable_perjadin')->default(false);
            $table->boolean('enable_driver_rules')->default(false);

            // BPJS Configuration
            $table->decimal('bpjs_jht_rate', 5, 2)->default(2.00); // JHT employee rate
            $table->decimal('bpjs_pensiun_rate', 5, 2)->default(1.00); // Pensiun employee rate
            $table->decimal('bpjs_kesehatan_rate', 5, 2)->default(1.00); // Kesehatan employee rate
            $table->decimal('bpjs_max_salary_jht', 12, 2)->default(10042300.00);
            $table->decimal('bpjs_max_salary_kesehatan', 12, 2)->default(12000000.00);

            // Allowance rates
            $table->decimal('meal_allowance_rate', 10, 2)->default(0);
            $table->decimal('perjadin_breakfast_rate', 10, 2)->default(0);
            $table->decimal('perjadin_lunch_rate', 10, 2)->default(0);
            $table->decimal('perjadin_dinner_rate', 10, 2)->default(0);
            $table->decimal('perjadin_daily_rate', 10, 2)->default(0);
            $table->decimal('perjadin_accommodation_rate', 10, 2)->default(0);

            // Driver specific
            $table->decimal('driver_max_payroll_ot', 10, 2)->default(0);

            $table->timestamps();

            // Indexes
            $table->index('payroll_project_id');
            $table->index(['year', 'month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_periods');
    }
};
