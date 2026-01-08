<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tb_pembayaran', function (Blueprint $table) {
            $table->foreignId('group_order_id')->nullable()->after('order_id')
                  ->constrained('group_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tb_pembayaran', function (Blueprint $table) {
            $table->dropForeign(['group_order_id']);
            $table->dropColumn('group_order_id');
        });
    }
};