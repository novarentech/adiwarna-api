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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_no', 50);
            $table->integer('work_order_year');
            $table->date('date');
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_location_id')->nullable()->constrained()->onDelete('set null');
            $table->json('scope_of_work');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('customer_location_id');
            $table->index('date');
            $table->index(['work_order_year', 'work_order_no']);
        });

        Schema::create('work_order_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index('work_order_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('work_order_employees');
    }
};
