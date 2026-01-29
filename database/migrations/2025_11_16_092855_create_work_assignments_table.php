<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_no', 20);
            $table->string('assignment_year', 20);
            $table->date('date');
            $table->string('ref_no', 20);
            $table->string('ref_year', 20);
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_location_id')->constrained()->onDelete('restrict');
            $table->string('ref_po_no_instruction')->nullable();
            $table->text('scope');
            $table->string('estimation')->nullable();
            $table->string('mobilization')->nullable();
            $table->string('auth_name', 100);
            $table->string('auth_pos', 100);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index(['assignment_year', 'assignment_no']);
        });

        Schema::create('work_assignment_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_assignment_id')->constrained()->onDelete('cascade');
            $table->string('worker_name');
            $table->string('position');
            $table->timestamps();

            // Indexes
            $table->index('work_assignment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignments');
        Schema::dropIfExists('work_assignment_workers');
    }
};
