@extends('layouts.dashboard')

@section('title', 'Pelanggan Detail')

@section('content')
<style>
    a{
        text-decoration:none;
    }
    .btn_header_layout{
        display:flex;
        align-items:flex-end;
        gap:.8vw;
        justify-content:flex-end;
    }
    .btn_print_nota_yow{
        outline:none !important;
        display:flex !important;
        align-items:center !important;
        gap:.4vw !important;
        font-size:.85vw !important;
        font-weight:500 !important;
        color:white !important;
        border:.12vw solid #34D399 !important;
        border-radius:.4vw !important;
        padding:.16vw .7vw !important;
        margin-bottom:.6vw !important;
        background-color: #34D399 !important;
        cursor: pointer !important;
    }
    .btn_print_nota_yow_sc{
        outline:none !important;
        display:flex !important;
        align-items:center !important;
        gap:.4vw !important;
        font-size:.85vw !important;
        font-weight:500 !important;
        color:white !important;
        border:.12vw solid #3b82f6 !important;
        border-radius:.4vw !important;
        padding:.16vw .7vw !important;
        margin-bottom:.6vw !important;
        background-color: #3b82f6 !important;
        cursor: pointer !important;
    }
    .input_form{
        border-color:#3b82f6 !important;
    }
@media print {
    @page {
        size: A4 landscape !important; /* Orientasi landscape untuk ruang lebih */
        margin: 0.1cm !important;
    }
    .btn_print_nota_yow{
        display: none !important;
    }
    .btn_print_nota_yow_sc{
        display: none !important;
    }
    .dashboard_banner{
        display:block;
    }
    .active-order-title {
        font-size: 1.3vw;
    }
    .layout_black{
        padding: 0 0;
    }
    .dashboard_popup_order_wrapper{
        padding:0;
        width: 100%;
        height: max-content;
    }
    .dashboard_popup_order{
        position: relative;
    }
    .dashboard_popup_order_box {
        display: block;
        margin: auto;
        background-color: white;
        width: 100%;
        max-width: 100%;
        border-radius: 0;
        padding: 1.2vw 1.7vw;
        padding-top:1.3vw !important;
        z-index: 10;
    }
    .nota_main_layout {
        border: .12vw solid #C92B2F;
        padding: .8vw 1.3vw;
        border-radius: .8vw;
        margin-bottom: 1.5vw;
    }
    .form_field_normal{
        margin-bottom: 1.4vw;
    }
    .form_field_normal label {
        display: block;
        font-size: 1.3vw;
        font-weight: 500;
        color: #111;
        margin-bottom: .6vw;
    }
    .input_form {
        font-size: 1.3vw;
        width: 100%;
        border: .1vw solid rgb(153, 153, 153);
        border-radius: .5vw;
        padding: .8vw 1.3vw;
        outline: none;
    }
    .dashboard_popup_order_kategori{
        gap:1vw;
        margin-bottom:1.5vw;
    }
    .dashboard_popup_order_kategori_btn{
        font-size:1.3vw;
        padding: .6vw .8vw;
    }
    .dashboard_popup_order_kategori_layout{
        grid-gap:1vw;
    }
    .btn_kategori_order{
        font-size:1.3vw;
        padding: .68vw .8vw;
    }
    .order-badge {
        height: 2.5vw;
        font-size: 1.3vw;
        padding: 0 .6vw;
    }
    .nota_main_size_grid label {
        font-size: 1.3vw;
        margin-bottom: .6vw;
    }
    .nota_main_size_grid input {
        border-radius: .6vw;
        padding: .4vw .9vw;
        font-size: 1.3vw;
    }
    .active-order-header {
        padding-bottom: .8vw;
        margin-bottom: .8vw;
    }
    .harga-row span {
        font-size: 1.3vw;
    }
    .nota_main_size_grid {
        grid-gap: 1vw;
    }
    .total-harga-order {
        gap: 1.2vw;
        margin-top: 1.2vw;
    }

    .pembayaran-section{
        width: 100%;
        margin-top:.6vw;
    }
    .pembayaran-section h3{
        font-size:1.3vw;
    }
    .ringkasan-pembayaran{
        margin-top: .55vw;
        border-radius: 8px; 
        grid-gap:1.1vw;
        display:grid;
        grid-template-columns:repeat(4, 1fr);
    }
    .ringkasan-item{
        background: white; 
        padding: .9vw; 
        border-radius: .8vw; 
        border: .12vw solid #999999ff;
    }
    .ringkasan-item h5{
        font-size:1vw;
        color:black;
        font-weight:500;
        margin-bottom:.4vw;
    }
    .ringkasan-item span{
        font-size:1.5vw;
        font-weight:800;
    }
}
</style>


<div class="dashboard_banner">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
</div>
<div class="layout_black">
    <div id="popupModal" class="dashboard_popup_order popup_custom active" style="z-index:9 !important;">
        <div class="dashboard_popup_order_wrapper">
            <div class="dashboard_popup_order_box">
                <div class="btn_header_layout">
                    <button onclick="window.print()" class="btn_print_nota_yow hidden_print">
                        🖨 Print
                    </button>
                    <a href="{{ route('pelanggan.show', $pelanggan->id) }}">
                        <button class="btn_print_nota_yow_sc hidden_print">Invoice</button>
                    </a>
                    <a href="{{ route('pelanggan.nota', $pelanggan->id) }}">
                        <button class="btn_print_nota_yow_sc hidden_print" style="background-color:black !important; border-color:black !important;">Nota</button>
                    </a>
                </div>
                <div class="form_grid_4">
                    <div class="form_field_normal">
                        <label>Nama Konsumen</label>
                        <input
                            type="text"
                            class="input_form"
                            value="{{ $pelanggan->nama}}"
                            readonly
                        >
                    </div>
                    <div class="form_field_normal">
                        <label>Alamat</label>
                        <input
                            type="text"
                            class="input_form"
                            value="{{ $pelanggan->alamat ?? 'data belum diatur' }}"
                            readonly
                        >
                    </div>
                    <div class="form_field_normal">
                        <label>Tgl Masuk</label>
                        <input
                            type="text"
                            class="input_form"
                            value="{{ $order->tgl_masuk
                                        ? $order->tgl_masuk->locale('id')->translatedFormat('l, d F Y')
                                        : 'data belum diatur'
                                    }}"
                            readonly
                        >
                    </div>
                    <div class="form_field_normal">
                        <label>Tgl Selesai</label>
                        <input
                            type="text"
                            class="input_form"
                            value="{{ $tanggalSelesai ? $tanggalSelesai->locale('id')->translatedFormat('l, d F Y') : 'Belum bisa dihitung' }}"
                            readonly
                        >
                    </div>
                </div>
                @if ($pelanggan->orders->isNotEmpty())
                <div class="pelanggan_jenis_job">
                    <div class="pelanggan_jenis_job_nama_layout">
                        <h3 class="pelanggan_jenis_job_heading" style="color:black !important;">Jenis Job</h3>
                    </div>

                    <div class="oi"></div>

                    <div class="pelanggan_jenis_job_ukuran_layout">
                        <div class="pelanggan_jenis_job_ukuran_box">
                            <span class="pelanggan_jenis_job_heading" style="color:black !important;">QTY</span>
                        </div>

                        @php
                            $sizes = ['XS','S','M','L','XL','2XL','3XL','4XL','5XL','6XL'];
                        @endphp

                        @foreach ($sizes as $size)
                            <div class="pelanggan_jenis_job_ukuran_box">
                                <span class="pelanggan_jenis_job_heading" style="color:black !important;">{{ $size }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
               
                @forelse ($pelanggan->orders as $order)
                @php
                    $sizeData = $order->size;
                @endphp

                <div class="pelanggan_jenis_job">
                    <div class="pelanggan_jenis_job_nama_layout">
                        <div class="input_form input_form_big">
                            <span class="pelanggan_jenis_job_heading" style="color:black !important;">{{ $order->nama_job }} {{ optional($order->jenisOrder)->nama_jenis ?? '' }}</span>
                        </div>
                    </div>

                    <div class="oi"></div>

                    <div class="pelanggan_jenis_job_ukuran_layout">
                        <div class="pelanggan_jenis_job_ukuran_box">
                            <div class="input_form input_form_big">
                                <span class="pelanggan_jenis_job_heading" style="color:black !important;">{{ $order->qty }}</span>
                            </div>
                        </div>

                        @foreach (['xs','s','m','l','xl','2xl','3xl','4xl','5xl','6xl'] as $key)
                        <div class="pelanggan_jenis_job_ukuran_box">
                            <div class="input_form input_form_big">
                                <span class="pelanggan_jenis_job_heading" style="color:black !important;">{{ $sizeData->{$key} ?? 0 }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @empty
                <div class="pelanggan_jenis_job">
                    <p class="text-gray-500 text-sm">Belum ada order untuk pelanggan ini.</p>
                </div>
                @endforelse

                @php
                    // Cek apakah ada spesifikasi di semua orders
                    $hasAnySpecs = false;
                    foreach ($pelanggan->orders as $order) {
                        if ($order->spesifikasi->isNotEmpty()) {
                            $hasAnySpecs = true;
                            break;
                        }
                    }
                @endphp

                @if ($hasAnySpecs)
                    <div class="pelanggan_jenis_job_spek">
                        <h3 class="pelanggan_jenis_job_heading" style="color:black !important;">Rincian Spesifikasi</h3>
                        
                        @php
                            // Gabungkan semua spesifikasi dari semua orders
                            $allSpecs = collect();
                            foreach ($pelanggan->orders as $order) {
                                foreach ($order->spesifikasi as $spec) {
                                    $allSpecs->push($spec);
                                }
                            }
                            
                            // Kelompokkan berdasarkan jenis_spek_id
                            $groupedAllSpecs = $allSpecs->groupBy('jenis_spek_id');
                        @endphp
                        
                        @if ($groupedAllSpecs->isEmpty())
                            <p class="text-sm text-gray-600">Belum ada spesifikasi yang dipilih.</p>
                        @else
                            <div class="pelanggan_jenis_job_spek_layout">
                                @foreach ($groupedAllSpecs as $spekId => $items)
                                    @php
                                        $first = $items->first();
                                        $spekName = optional($first->jenisSpek)->nama_jenis_spek ?? ('Spek ' . $spekId);
                                        
                                        // Kumpulkan semua detail untuk spek ini (unik berdasarkan detail_id)
                                        $uniqueDetails = $items->unique('jenis_spek_detail_id');
                                    @endphp

                                    <div class="pelanggan_jenis_job_spek_box">
                                        <div class="pelanggan_jenis_job_spek_header">
                                            <h4 class="pelanggan_jenis_job_heading">{{ $spekName }}</h4>
                                        </div>
                                        
                                        <div class="pelanggan_jenis_job_spek_details">
                                            @foreach ($uniqueDetails as $item)
                                                @php
                                                    $detailName = optional($item->jenisSpekDetail)->nama_jenis_spek_detail ?? '-';
                                                    $img = optional($item->jenisSpekDetail)->gambar;
                                                @endphp
                                                
                                                <div class="pelanggan_jenis_job_spek_detail_item">
                                                    <div class="pelanggan_jenis_job_spek_img">
                                                        @if ($img)
                                                            <img src="{{ asset('storage/' . $img) }}"
                                                                alt="{{ $detailName }}">
                                                        @else
                                                            <div class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded">
                                                                <span class="text-xs text-gray-500">No Image</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p>{{ $detailName }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection