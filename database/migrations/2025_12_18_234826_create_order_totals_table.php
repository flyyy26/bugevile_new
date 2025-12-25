<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_totals', function (Blueprint $table) {
            $table->id();

            $table->integer('total_qty')->default(0);
            $table->integer('total_hari')->default(0);
            $table->decimal('total_deadline', 8, 2)->default(0);

            $table->integer('total_print')->default(0);
            $table->integer('total_press')->default(0);
            $table->integer('total_cutting')->default(0);
            $table->integer('total_jahit')->default(0);
            $table->integer('total_finishing')->default(0);
            $table->integer('total_packing')->default(0);
            $table->integer('total_setting')->default(0);

            $table->integer('total_sisa_setting')->default(0);
            $table->integer('total_sisa_print')->default(0);
            $table->integer('total_sisa_press')->default(0);
            $table->integer('total_sisa_cutting')->default(0);
            $table->integer('total_sisa_jahit')->default(0);
            $table->integer('total_sisa_finishing')->default(0);
            $table->integer('total_sisa_packing')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_totals');
    }
};
