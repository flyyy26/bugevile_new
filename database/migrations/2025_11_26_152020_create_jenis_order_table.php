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
        Schema::create('jenis_order', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');
            $table->integer('nilai')->default(0); // nilai seperti 4, 2, 5
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_order');
    }

};
