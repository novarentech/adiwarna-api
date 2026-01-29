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
            $table->date('date');
            $table->string('po_no_cash', 100)->nullable();
            $table->enum('supplier', ['online', 'offline']);
            $table->enum('routing', ['online', 'offline']);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(10);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('requested_by', 255)->nullable();
            $table->string('requested_position', 255)->nullable();
            $table->string('approved_by', 255)->nullable();
            $table->string('approved_position', 255)->nullable();
            $table->string('authorized_by', 255)->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('date');
            $table->index('pr_no');
            $table->index('supplier');
            $table->index('routing');
        });

        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->constrained()->onDelete('cascade');
            $table->decimal('qty', 10, 2);
            $table->string('unit', 50);
            $table->text('description');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();

            // Indexes
            $table->index('purchase_requisition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
        Schema::dropIfExists('purchase_requisition_items');
    }
};
