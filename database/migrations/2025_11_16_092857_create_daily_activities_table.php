<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->string('po_no', 20);
            $table->string('po_year', 20);
            $table->string('ref_no', 20)->nullable();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->string('location');
            $table->time('time_from');
            $table->time('time_to');
            $table->string('prepared_name', 100);
            $table->string('prepared_pos', 100);
            $table->string('acknowledge_name', 100)->nullable();
            $table->string('acknowledge_pos', 100)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index(['po_year', 'po_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};
