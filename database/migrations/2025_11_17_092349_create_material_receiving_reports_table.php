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
            $table->string('rr_no', 50);
            $table->string('rr_year', 4);
            $table->date('date');
            $table->string('ref_pr_no', 50)->nullable();
            $table->string('ref_po_no', 50)->nullable();
            $table->string('supplier', 255);
            $table->date('receiving_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('date');
            $table->index('receiving_date');
            $table->index(['rr_year', 'rr_no']);
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
