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
            $table->string('dn_no', 50);
            $table->date('date');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('isOther')->default(false);
            $table->json('other')->nullable();
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
            $table->index('dn_no');
            $table->index('date');
            $table->index('customer_id');
            $table->index('wo_no');
            $table->index('vehicle_plate');
        });

        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained()->onDelete('cascade');
            $table->string('item_name', 255);
            $table->string('serial_number', 100)->nullable();
            $table->integer('qty');
            $table->timestamps();

            // Indexes
            $table->index('delivery_note_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
        Schema::dropIfExists('delivery_note_items');
    }
};
