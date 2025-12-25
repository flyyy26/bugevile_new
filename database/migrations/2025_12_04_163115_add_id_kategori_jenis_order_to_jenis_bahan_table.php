<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jenis_bahan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori_jenis_order')->after('id')->nullable();

            $table->foreign('id_kategori_jenis_order')
                  ->references('id')
                  ->on('kategori_jenis_order')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('jenis_bahan', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_jenis_order']);
            $table->dropColumn('id_kategori_jenis_order');
        });
    }
};
