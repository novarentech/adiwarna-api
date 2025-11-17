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
            $table->string('project_name', 255);
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->string('equipment_name', 255);
            $table->string('equipment_type', 100);
            $table->integer('quantity')->default(1);
            $table->string('condition', 50);
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('project_name');
            $table->index('assigned_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_projects');
    }
};
