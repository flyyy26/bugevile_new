<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_histories', function (Blueprint $table) {
            if (Schema::hasColumn('order_histories', 'total_lembar_print')) {
                $table->dropColumn('total_lembar_print');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->integer('total_lembar_print')->nullable();
        });
    }
};
