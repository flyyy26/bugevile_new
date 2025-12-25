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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            
            // Data Identitas & Kontak
            $table->string('nama');
            $table->string('nomor_whatsapp'); 
            $table->text('alamat');
            $table->string('kode')->unique(); // Kode unik afiliasi

            // Data Pembayaran (Rekening)
            $table->string('nama_bank');      // Misal: BCA
            $table->string('nomor_rekening'); // Misal: 1234567890
            $table->string('nama_rekening');  // Misal: Asep Supriatna

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
