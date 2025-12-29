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
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_note_no', 50);
            $table->date('date');
            $table->string('customer', 255);
            $table->text('customer_address');
            $table->string('wo_no', 50);
            $table->string('delivered_with', 255)->nullable();
            $table->string('vehicle_plate', 50);
            $table->string('delivered_by', 255)->nullable();
            $table->string('received_by', 255)->nullable();
            $table->enum('status', ['pending', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('delivery_note_no');
            $table->index('date');
            $table->index('customer');
            $table->index('wo_no');
            $table->index('vehicle_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
