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
            $table->string('rev_no', 50)->nullable();
            $table->date('date');
            $table->date('required_delivery');
            $table->string('po_no_cash', 100)->nullable();
            $table->string('supplier', 255);
            $table->string('place_of_delivery', 255);
            $table->enum('routing', ['online', 'offline'])->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(10);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('requested_by', 255)->nullable();
            $table->string('approved_by', 255)->nullable();
            $table->string('authorized_by', 255)->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('date');
            $table->index('pr_no');
            $table->index('supplier');
            $table->index('place_of_delivery');
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
