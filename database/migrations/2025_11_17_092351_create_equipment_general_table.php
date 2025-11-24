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
            $table->string('description', 255);
            $table->string('merk_type', 255);
            $table->string('serial_number', 100)->unique();
            $table->date('calibration_date');
            $table->integer('duration_months'); // 6 or 12
            $table->date('expired_date');
            $table->enum('calibration_agency', ['internal', 'external']);
            $table->enum('condition', ['ok', 'repair', 'reject']);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('serial_number');
            $table->index('condition');
            $table->index('calibration_agency');
            $table->index('expired_date');
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
