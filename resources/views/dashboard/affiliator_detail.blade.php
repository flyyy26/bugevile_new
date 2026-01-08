@extends('layouts.dashboard') 

@section('title','Sales Detail')

@section('content')
<div>
    <div class="dashboard_banner dashboard_banner_print">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
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
                    <!-- Box 1: Total Komisi -->
                    <div class="affiliate_omset_box">
                        <h3>Total Komisi</h3>
                        <h1 class="text-2xl font-semibold text-blue-600">
                            @if($totalKomisi > 0)
                                Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">Rp 0</span>
                            @endif
                        </h1>
                        <p>
                            ({{ $orders->count() }} order)
                        </p>
                    </div>
                    
                    <!-- Box 2: Komisi Siap Cair -->
                    <div class="affiliate_omset_box">
                        <h3>Komisi Siap Cair</h3>
                        <h1 class="text-2xl font-semibold text-green-600">
                            @if($completedKomisi > 0)
                                Rp {{ number_format($completedKomisi, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400">Rp 0</span>
                            @endif
                        </h1>
                        <p>
                            @if($completedCount > 0)
                                ({{ $completedCount }} order selesai)
                                <br>
                                <small class="text-gray-500">
                                    Dari laba bersih: Rp {{ number_format($completedLabaBersih, 0, ',', '.') }}
                                </small>
                            @else
                                <span class="text-gray-400">Belum ada order selesai</span>
                            @endif
                        </p>
                    </div>
                    <h3>Total QTY keseluruhan : {{ number_format($totalQtySemuaOrder) }}</h3>
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
                                <th><div class="text-center">Job</div></th>
                                <th><div class="text-center">Qty</div></th>
                                <th><div class="text-center">Komisi</div></th>
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
                                <td class="p-3 border-b"><div class="text-center">
                                    <div class="font-medium">{{ $order->nama_job }} {{ optional($order->jenisOrder)->nama_jenis ?? $order->jenis_order_id }}</div>
                                    <a href="{{ url('dashboard/' . $order->slug) }}" class="btn_more_style">
                                        Lihat Progress
                                    </a>
                                </td>
                                <td class="p-3 border-b text-center">
                                    <div class="text-center">{{ number_format($order->qty, 0, ',', '.') }}</div>
                                </td>
                                <td class="border-b"><div class="text-center">
                                    @if($order->laba_bersih_affiliate > 0)
                                        <div class="font-semibold text-green-600">
                                            Rp {{ number_format($order->laba_bersih_affiliate, 0, ',', '.') }}
                                        </div>
                                        @if($order->laba_bersih > 0)
                                            <small class="text-gray-500 text-xs">
                                                dari laba Rp {{ number_format($order->laba_bersih, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td><div class="text-center affiliate_aksi">
                                    @if( ($order->status ?? 1) == 1 )
                                        <button type="button" class="bg-green-500">
                                            Selesai
                                        </button>
                                        <p class="text-green-500">Siap cair</p>
                                    @else
                                        <button type="button" class="bg-red-500">
                                            Proses
                                        </button>
                                        <p class="text-red-500">Belum cair</p>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-400"><div class="text-center">
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