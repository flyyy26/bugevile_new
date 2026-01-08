@extends('layouts.dashboard')

@section('title', $jenisOrder->nama_jenis)

@section('content')
<div>
    <!-- Header -->
    <div class="dashboard_banner dashboard_banner_print">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <a style="text-decoration:none;" href="{{ route('pegawai.index') }}">
                <button
                    class="hidden_print"
                >
                    ← Kembali
                </button> 
            </a>
        </div>
    </div>
    <div class="ongkos_layout_container">
        <form action="{{ route('harga.update') }}" method="POST">
            @csrf

            <div class="pelanggan_detail_layout">

                <div class="form_field_normal">
                    <label>Nama Kategori</label>
                    <input type="text"
                        value="{{ $jenisOrder->nama_jenis }}"
                        class="input_form"
                        disabled>
                </div>

                <div class="form_field_normal">
                    <label>Harga Produksi</label>
                    <input type="number"
                        name="harga_barang"
                        value="{{ $jenisOrder->harga_barang }}"
                        class="input_form"
                        readonly
                        required>
                </div>

                <div class="form_field_normal">
                    <label>Harga Jual</label>
                    <input type="number"
                        name="harga_jual"
                        value="{{ $jenisOrder->harga_jual }}"
                        class="input_form"
                        required>
                </div>

                <div class="form_field_normal">
                    <label>Laba Bersih</label>
                    <input type="number"
                        name="laba_bersih"
                        value="{{ $jenisOrder->laba_bersih }}"
                        class="input_form"
                        required>
                </div>

            </div>

            <div class="dashboard_popup_order_btn">
                <button type="submit">Simpan Semua Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection