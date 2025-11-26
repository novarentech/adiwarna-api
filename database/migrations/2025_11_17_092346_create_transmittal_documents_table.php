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
        Schema::create('transmittal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transmittal_id')->constrained('transmittals')->onDelete('cascade');
            $table->string('wo_number');
            $table->integer('wo_year');
            $table->string('location');
            $table->timestamps();

            // Indexes
            $table->index('transmittal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transmittal_documents');
    }
};
