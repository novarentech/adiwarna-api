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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no', 50);
            $table->string('pr_year', 4);
            $table->date('date');
            $table->string('supplier', 255);
            $table->text('delivery_place');
            $table->decimal('discount', 10, 2)->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('date');
            $table->index(['pr_year', 'pr_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
