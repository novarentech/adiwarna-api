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
            $table->string('work_order_year', 4);
            $table->date('date');
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->text('description');
            $table->string('status', 50)->default('pending');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index(['work_order_year', 'work_order_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
