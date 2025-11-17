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
        Schema::create('material_receiving_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_receiving_report_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->decimal('order_qty', 10, 2);
            $table->decimal('received_qty', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('material_receiving_report_id', 'mrr_item_mrr_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_receiving_report_items');
    }
};
