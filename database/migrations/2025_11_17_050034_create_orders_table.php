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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('nama_job');
            $table->integer('qty');             // Total pesanan
            $table->float('hari')->default(0);  // Estimasi hari
            $table->float('deadline')->default(0);

            // Progress per divisi
            $table->integer('setting')->default(0);
            $table->integer('print')->default(0);
            $table->integer('press')->default(0);
            $table->integer('cutting')->default(0);
            $table->integer('jahit')->default(0);

            // Estimasi total
            $table->float('est')->default(0);

            // Sisa pekerjaan (qty - yang sudah dikerjakan)
            $table->integer('sisa_print')->default(0);
            $table->integer('sisa_press')->default(0);
            $table->integer('sisa_cutting')->default(0);
            $table->integer('sisa_jahit')->default(0);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
