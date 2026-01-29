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
        Schema::create('material_receiving_reports', function (Blueprint $table) {
            $table->id();
            $table->string('po_no', 50);
            $table->string('supplier', 255)->nullable();
            $table->date('receiving_date');
            $table->enum('order_by', ['online', 'offline'])->nullable();
            $table->string('received_by', 255)->nullable();
            $table->string('received_position', 255)->nullable();
            $table->string('acknowledge_by', 255)->nullable();
            $table->string('acknowledge_position', 255)->nullable();
            $table->enum('status', ['complete', 'partial'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('receiving_date');
            $table->index('po_no');
        });

        Schema::create('material_receiving_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_receiving_report_id')
                ->constrained('material_receiving_reports')
                ->onDelete('cascade')
                ->name('mrr_items_mrr_id_fk'); // Shortened foreign key name
            $table->text('description');
            $table->decimal('order_qty', 10, 2);
            $table->decimal('received_qty', 10, 2);
            $table->enum('remarks', ['good', 'reject'])->nullable();
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
        Schema::dropIfExists('material_receiving_reports');
        Schema::dropIfExists('material_receiving_report_items');
    }
};
