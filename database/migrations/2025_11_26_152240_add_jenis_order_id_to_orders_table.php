<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_order_id')->nullable()->after('id');

            $table->foreign('jenis_order_id')
                ->references('id')
                ->on('jenis_order')
                ->onDelete('set null'); 
                // Jika jenis dihapus, value jadi null
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['jenis_order_id']);
            $table->dropColumn('jenis_order_id');
        });
    }

};
