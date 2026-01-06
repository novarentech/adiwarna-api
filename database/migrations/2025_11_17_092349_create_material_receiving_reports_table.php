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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_receiving_reports');
    }
};
