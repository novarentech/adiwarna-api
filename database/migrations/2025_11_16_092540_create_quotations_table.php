<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('ref_no', 20);
            $table->string('ref_year', 20);
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->string('pic_name', 100);
            $table->string('pic_phone', 20);
            $table->string('subject');
            $table->string('top'); // Terms of Payment
            $table->string('valid_until');
            $table->text('clause')->nullable();
            $table->string('workday')->nullable();
            $table->string('auth_name', 100);
            $table->string('auth_position', 100);
            $table->decimal('discount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('date');
            $table->index(['ref_year', 'ref_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
