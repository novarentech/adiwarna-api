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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_no', 50)->unique();
            $table->string('name');
            $table->string('phone_number', 20);
            $table->text('address');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_no');
            $table->index('name');
        });

        Schema::create('customer_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('location_name');
            $table->timestamps();

            // Indexes
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_locations');
    }
};
