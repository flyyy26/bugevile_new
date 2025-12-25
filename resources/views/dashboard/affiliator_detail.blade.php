@extends('layouts.dashboard') 

@section('title','Affiliator Detail')

@section('content')
<div>
    <div class="dashboard_banner dashboard_banner_print">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <a style="text-decoration:none;" href="{{ route('affiliator.index') }}">
                <button
                    class="hidden_print"
                >
                    ← Kembali
                </button> 
            </a>
        </div>
    </div>

    <div class="affiliate_detail_container">
        
        <div>
            <div class="affiliate_detail_card affiliate_detail_card_one">
                <div class="affiliate_detail_card_heading">
                    <h2>{{ $affiliate->nama }}</h2>
                    <p>{{ $affiliate->alamat }}</p>
                </div>

                <div class="affiliate_detail_card_box">
                    <div class="affiliate_detail_card_box_text">
                        <label>Kode Referal</label>
                        <div class="affiliate_detail_card_box_kode">
                            <p>{{ $affiliate->kode }}</p>
                        </div>
                    </div>

                    <div class="affiliate_detail_card_box_text">
                        <label>WhatsApp</label>
                        <a href="https://wa.me/{{ $affiliate->nomor_whatsapp }}" target="_blank" class="text-green-600 hover:underline">
                            {{ $affiliate->nomor_whatsapp }}
                        </a>
                    </div>

                    <div class="affiliate_detail_card_box_text">
                        <label>Info Rekening</label>
                        <div class="affiliate_detail_card_box_kode bg-gray-100">
                            <h3>{{ $affiliate->nama_bank }}</h3>
                            <h3>{{ $affiliate->nomor_rekening }}</h3>
                            <h3>a.n {{ $affiliate->nama_rekening }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="affiliate_detail_card affiliate_detail_card_two">
                <div class="affiliate_omset">
                    <h3>Total Omset Masuk</h3>
                    <div class="affiliate_omset_box">
                        <h1>
                            Rp {{ number_format($totalOmset, 0, ',', '.') }}
                        </h1>
                        <p>
                            {{ $orders->count() }} order × Rp {{ number_format($harga, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="affiliate_omset_box">
                        <h3>Omset Dapat Dicairkan</h3>
                        <h1 class="text-2xl font-semibold text-blue-600">
                            @if(($completedOmset ?? 0) > 0)
                                Rp {{ number_format($completedOmset, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">Rp 0</span>
                            @endif
                        </h1>
                        <p>
                            @if(($completedCount ?? 0) > 0)
                                {{ $completedCount }} order selesai × Rp {{ number_format($harga, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">Belum ada order selesai</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="affiliate_table_layout">
            <div class="affiliate_detail_card">
                <div class="affiliate_detail_card_heading" style="border:none; padding-bottom:0;">
                    <h2>Riwayat Order (Kode: {{ $affiliate->kode }})</h2>
                </div>

                <div class="orders_table_container orders_table_container_small">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                                <th><div class="text-center">Tanggal</div></th>
                                <th><div class="text-center">Nama Customer</div></th>
                                <th><div class="text-center">Jenis Job</div></th>
                                <th><div class="text-center">Estimasi</div></th>
                                <th><div class="text-center">Status</div></th> 
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">
                                    {{ $order->created_at->format('d M Y') }}
                                    <br>
                                    <small class="text-gray-400">{{ $order->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td class="p-3 border-b font-medium"><div class="text-center">
                                    {{ $order->nama_konsumen ?? 'Guest' }}
                                </td>
                                <td class="p-3 border-b font-medium"><div class="text-center">
                                    {{ $order->nama_job }} {{ optional($order->jenisOrder)->nama_jenis ?? $order->jenis_order_id }}
                                    <a href="{{ url('dashboard/' . $order->slug) }}" style="color:blue;"  class="ml-2 bg-blue-600 text-xs p-1 rounded">
                                        Lihat job
                                    </a>
                                </td>
                                <td class="border-b text-center"><div class="text-center">
                                    @php
                                        // 1. Ambil angka bulat sebagai Hari
                                        $hari = floor($order->est);
                                        
                                        // 2. Ambil sisa desimal, lalu kali 24 untuk jadi Jam (dibulatkan)
                                        $jam = round(($order->est - $hari) * 24);
                                    @endphp

                                    {{-- Tampilkan Hari jika lebih dari 0 --}}
                                    @if($hari > 0)
                                        {{ $hari }} Hari
                                    @endif

                                    {{-- Tampilkan Jam jika lebih dari 0 --}}
                                    @if($jam > 0)
                                        {{ $jam }} Jam
                                    @endif

                                    {{-- Jika datanya 0 atau kosong --}}
                                    @if($hari == 0 && $jam == 0)
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 border-b"><div class="text-center">
                                    @if( ($order->status ?? 1) == 1 )
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-800">
                                            Proses
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400"><div class="text-center">
                                    Belum ada order masuk dengan kode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection