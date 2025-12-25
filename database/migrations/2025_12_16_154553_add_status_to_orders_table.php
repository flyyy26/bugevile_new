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
            // boolean: isinya 0 (false) atau 1 (true)
            // default(true): artinya data order LAMA yang sudah ada akan otomatis jadi TRUE
            // after('total_price'): posisi kolom ditaruh setelah total_price (opsional, biar rapi)
            $table->boolean('status')->default(true);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
