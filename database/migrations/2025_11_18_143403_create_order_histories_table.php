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
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            
            // 1. Foreign Key ke tabel Orders (Untuk tahu job apa)
            // onDelete('cascade') artinya jika job dihapus, history-nya ikut hilang (bersih)
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // 2. Foreign Key ke tabel Pegawais (Untuk tahu siapa yang kerja)
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');

            // 3. Jenis Pekerjaan (Print, Press, Cutting, Jahit)
            $table->string('jenis_pekerjaan'); 

            // 4. Jumlah yang dikerjakan saat itu
            $table->integer('qty');

            // 5. Keterangan (Boleh kosong / nullable)
            $table->text('keterangan')->nullable();

            // Opsional: Snapshot Nama Job
            // Sebenarnya tidak wajib karena sudah ada order_id, tapi kalau mau simpan nama
            // sebagai arsip (jaga-jaga nama job asli diganti), boleh dimasukkan.
            $table->string('nama_job_snapshot')->nullable();

            // Tanggal & Waktu (created_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};
