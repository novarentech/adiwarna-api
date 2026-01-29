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
        Schema::create('transmittals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ta_no');
            $table->date('date');
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->string('customer_district')->nullable();
            $table->string('pic_name');
            $table->string('report_type');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index('ta_no');
        });

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
        Schema::dropIfExists('transmittals');
        Schema::dropIfExists('transmittal_documents');
    }
};
