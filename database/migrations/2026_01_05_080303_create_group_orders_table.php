<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_group')->unique(); // Contoh: GRP-20240104-001
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('affiliate_id')->nullable()->constrained('affiliates')->onDelete('set null');
            $table->string('nama_job'); // Nama job utama (bisa ambil dari order pertama)
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->decimal('sisa_bayar', 15, 2)->default(0);
            $table->decimal('harus_dibayar', 15, 2)->default(0);
            $table->boolean('payment_status')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_orders');
    }
};