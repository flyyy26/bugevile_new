<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('total_qty')->default(0);
            $table->decimal('total_hari', 8, 2)->default(0);
            $table->decimal('total_deadline', 8, 2)->default(0);

            $table->integer('total_setting')->default(0);
            $table->integer('total_print')->default(0);
            $table->integer('total_press')->default(0);
            $table->integer('total_cutting')->default(0);
            $table->integer('total_jahit')->default(0);

            $table->integer('total_sisa_print')->default(0);
            $table->integer('total_sisa_press')->default(0);
            $table->integer('total_sisa_cutting')->default(0);
            $table->integer('total_sisa_jahit')->default(0);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'total_qty',
                'total_hari',
                'total_deadline',
                'total_print',
                'total_press',
                'total_cutting',
                'total_jahit',
                'total_sisa_print',
                'total_sisa_press',
                'total_sisa_cutting',
                'total_sisa_jahit',
            ]);
        });
    }

};
