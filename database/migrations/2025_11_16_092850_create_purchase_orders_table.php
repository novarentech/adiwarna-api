<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no', 20);
            $table->string('po_year', 20);
            $table->date('date');
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->string('pic_name', 100);
            $table->string('pic_phone', 20);
            $table->date('required_date');
            $table->string('top_dp')->nullable(); // Terms of Payment - Down Payment
            $table->string('top_cod')->nullable(); // Terms of Payment - Cash on Delivery
            $table->string('quotation_ref')->nullable();
            $table->string('purchase_requisition_no')->nullable();
            $table->string('purchase_requisition_year')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('req_name', 100)->nullable(); // Requester
            $table->string('req_pos', 100)->nullable(); // Requester Position
            $table->string('app_name', 100)->nullable(); // Approver
            $table->string('app_pos', 100)->nullable(); // Approver Position
            $table->string('auth_name', 100); // Authorized
            $table->string('auth_pos', 100); // Authorized Position
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index(['po_year', 'po_no']);
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('quantity', 10);
            $table->string('unit', 20);
            $table->decimal('rate', 15, 2);
            $table->timestamps();

            // Indexes
            $table->index('purchase_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_order_items');
    }
};
