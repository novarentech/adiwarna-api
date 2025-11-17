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
        Schema::create('equipment_general', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name', 255);
            $table->string('equipment_type', 100);
            $table->integer('quantity')->default(1);
            $table->string('condition', 50);
            $table->json('specifications')->nullable();
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('equipment_type');
            $table->index('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_general');
    }
};
