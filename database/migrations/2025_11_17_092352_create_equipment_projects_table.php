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
        Schema::create('equipment_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_location_id')->nullable()->constrained()->onDelete('set null');
            $table->date('project_date');
            $table->string('prepared_by', 255);
            $table->string('verified_by', 255);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('customer_location_id');
            $table->index('project_date');
        });

        Schema::create('equipment_project_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_project_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_general_id')->constrained('equipment_general')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index('equipment_project_id');
            $table->index('equipment_general_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_projects');
        Schema::dropIfExists('equipment_project_items');
    }
};
