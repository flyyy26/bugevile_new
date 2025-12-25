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
        Schema::create('affiliator_order', function (Blueprint $table) {
            $table->id();

            // Foreign Key ke tabel affiliates
            // Kita namakan kolomnya 'affiliator_id', tapi merujuk ke id di tabel 'affiliates'
            $table->foreignId('affiliator_id')
                ->constrained('affiliates')
                ->onDelete('cascade'); 

            // Foreign Key ke tabel orders
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            // Opsional: Kolom tambahan jika perlu mencatat komisi spesifik per transaksi ini
            // $table->decimal('komisi_didapat', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliator_order');
    }
};
