@extends('layouts.dashboard')

@section('title', 'Total Transaksi per Jenis Job')

@section('content')
<div class="dashboard_banner dashboard_banner_print">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
</div>
<style>
    .compact-col {
        width: 10px;
        min-width: 10px;
        max-width: 10px;
        text-align: center;
    }
    
    .compact-col div {
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
</style>
<!-- Table -->
<div class="orders_table_container">
    <table>
        <thead class="bg-gray-800">
            <tr>
                <th class="compact-col">
                    <div class="text-center">No</div>
                </th>
                <th>
                    <div class="text-center">Jenis Job</div>
                </th>
                <th>
                    <div class="text-center">Qty Calo</div>
                </th>
                <th>
                    <div class="text-center">Total Qty</div>
                </th>
                <th>
                    <div class="text-center">OMSet</div> 
                </th>
                <th>
                    <div class="text-center">Bahan</div>
                </th>
                <th>
                    <div class="text-center">Kertas</div>
                </th>
                <th>
                    <div class="text-center">Asesoris</div>
                </th>
                <th>
                    <div class="text-center">Ongkos Gawe</div>
                </th>
                <!-- KOLOM BIAYA DINAMIS -->
                @if(isset($biayaCategories) && count($biayaCategories) > 0)
                    @foreach($biayaCategories as $category)
                        <th class="biaya-col">
                            <div class="text-center">{{ ucfirst($category) }}</div>
                        </th>
                    @endforeach
                @endif
                <th>
                    <div class="text-center">Calo</div>
                </th>
                <th>
                    <div class="text-center">Total Biaya</div>
                </th>
                <th>
                    <div class="text-center">Laba Bersih</div>
                </th>
                <th>
                    <div class="text-center">Margin</div>
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse($totals as $item)
            @php
                $jo = $item['jenis_order'];
            @endphp
            <tr class="{{ $loop->even ? 'bg_gray_200' : 'bg-white' }}">
                <td class="compact-col"><div class="text-center">{{ $loop->iteration }}</div></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">{{ $jo->nama_jenis }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    @if($item['total_qty_calo'] > 0)
                        <div class="text-center">
                            {{ number_format($item['total_qty_calo'], 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-gray-400">-</div>
                    @endif
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($item['total_qty'], 0, ',', '.') }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($item['total_revenue'], 0, ',', '.') }}</div>
                </td>
                
                @if($jo->belanja)
                    @php
                        $nilai = $jo->nilai ?? 1;
                        $bahanHarga = (($jo->belanja->bahan_harga ?? 0) * $nilai) * $item['total_qty'];
                        $kertasHarga = (($jo->belanja->kertas_harga ?? 0) * $nilai) * $item['total_qty'];
                        $totalAsesoris = ($jo->belanja->asesoris->sum('harga') ?? 0) * $item['total_qty'];
                        $angkaPekerjaan = $item['harga_pekerjaan'] * $item['total_qty'];
                    @endphp
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="text-center">{{ number_format($bahanHarga, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="text-center">{{ number_format($kertasHarga, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="text-center">{{ number_format($totalAsesoris, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="text-center">{{ number_format($angkaPekerjaan, 0, ',', '.') }}</div>
                    </td>
                @else
                    <td class="px-4 py-3 whitespace-nowrap text-center">-</td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">-</td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">-</td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">-</td>
                @endif
                
                <!-- KOLOM BIAYA PER KATEGORI -->
                @if(isset($biayaCategories) && count($biayaCategories) > 0)
                    @foreach($biayaCategories as $category)
                        <td class="px-3 py-3 whitespace-nowrap text-center biaya-col">
                            @php
                                $biayaKategori = ($item['biaya_per_kategori'][$category] ?? 0) * $item['total_qty'];
                            @endphp
                            @if($biayaKategori > 0)
                                <div class="text-center">
                                    {{ number_format($biayaKategori, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="text-gray-400">-</div>
                            @endif
                        </td>
                    @endforeach
                    
                    
                @endif
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">
                            {{ number_format($item['total_komisi_calo'], 0, ',', '.') }}
                    </div>
                </td>

                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">
                        {{ number_format($item['total_biaya'], 0, ',', '.') }}
                    </div>
                </td>
                
                <!-- LABA BERSIH -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="{{ $item['profit'] >= 0 ? 'text-green-500 font-medium' : 'text-red-500 font-medium' }} text-center">
                        {{ number_format($item['profit'], 0, ',', '.') }}
                    </div>
                </td>
                
                <!-- MARGIN -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mb-1">
                            <div class="bg-{{ $item['profit_margin'] >= 0 ? 'green' : 'red' }}-500 h-2 rounded-full" 
                                style="width: {{ min(abs($item['profit_margin']), 100) }}%">
                            </div>
                        </div>
                        <span class="text-sm {{ $item['profit_margin'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ number_format($item['profit_margin'], 1) }}%
                        </span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ isset($biayaCategories) ? count($biayaCategories) + 14 : 12 }}" 
                    class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data transaksi
                </td>
            </tr>
            @endforelse
        </tbody>

        <!-- FOOTER/GRAND TOTAL -->
        <tfoot class="bg-gray-800 text-white">
            <tr class="font-bold">
                <td class="compact-col"><div class="text-center">TOTAL</div></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">{{ count($totals) }} Jenis Job</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center border-t">
                    <div class="text-center">{{ number_format($grandTotalQtyCalo, 0, ',', '.') }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($grandTotalQty, 0, ',', '.') }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($grandTotalRevenue, 0, ',', '.') }}</div>
                </td>
                
                <!-- BAHAN TOTAL -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($totals->sum(function($item) {
                        $jo = $item['jenis_order'];
                        $nilai = $jo->nilai ?? 1;
                        return (($jo->belanja->bahan_harga ?? 0) * $nilai) * $item['total_qty'];
                    }), 0, ',', '.') }}</div>
                </td>
                
                <!-- KERTAS TOTAL -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($totals->sum(function($item) {
                        $jo = $item['jenis_order'];
                        $nilai = $jo->nilai ?? 1;
                        return (($jo->belanja->kertas_harga ?? 0) * $nilai) * $item['total_qty'];
                    }), 0, ',', '.') }}</div>
                </td>
                
                <!-- ASESORIS TOTAL -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($totals->sum(function($item) {
                        $jo = $item['jenis_order'];
                        
                        // Cek jika belanja tidak null dan asesoris ada
                        if ($jo->belanja && $jo->belanja->asesoris) {
                            return ($jo->belanja->asesoris->sum('harga') ?? 0) * $item['total_qty'];
                        }
                        
                        return 0;
                        
                    }), 0, ',', '.') }}</div>
                </td>
                
                <!-- ONGKOS GAWE TOTAL -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">{{ number_format($totals->sum(function($item) {
                        return $item['harga_pekerjaan'] * $item['total_qty'];
                    }), 0, ',', '.') }}</div>
                </td>
                
                <!-- TOTAL PER KATEGORI BIAYA -->
                @if(isset($biayaCategories) && count($biayaCategories) > 0)
                    @foreach($biayaCategories as $category)
                        <td class="px-3 py-3 whitespace-nowrap text-center biaya-col">
                            <div class="text-center">
                                {{ number_format($categoryTotals[$category] ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                    @endforeach
                @endif
                <td><div class="text-center">{{ number_format($grandTotalKomisiCalo, 0, ',', '.') }}</div></td>
                
                <!-- TOTAL SEMUA BIAYA -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="text-center">
                        {{ number_format($grandTotalBiaya, 0, ',', '.') }}
                    </div>
                </td>
                
                <!-- GRAND TOTAL LABA -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    <div class="{{ $grandTotal >= 0 ? 'text-green-300' : 'text-red-300' }} text-center">
                        {{ number_format($grandTotal, 0, ',', '.') }}
                    </div>
                </td>
                
                <!-- MARGIN RATA-RATA -->
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    @php
                        $avgMargin = $grandTotalRevenue > 0 ? ($grandTotal / $grandTotalRevenue) * 100 : 0;
                    @endphp
                    <div class="text-center">
                        {{ number_format($avgMargin, 1) }}%
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

<style>
    /* CSS untuk membuat tabel lebih rapih */
    .compact-col {
        width: 50px;
        min-width: 50px;
        max-width: 60px;
        text-align: center;
        padding: 8px 4px !important;
    }
    
    .biaya-col {
        min-width: 80px;
        max-width: 100px;
        font-size: 0.875rem;
        padding: 8px 4px !important;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    th {
        padding: 12px 8px;
        text-align: center;
        font-weight: 600;
        color: white;
        background-color: #1f2937;
    }
    
    td {
        padding: 10px 8px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .bg_gray_200 {
        background-color: #f3f4f6;
    }
    
    .bg-white {
        background-color: white;
    }
    
    /* Untuk tabel yang terlalu lebar */
    .table-container {
        overflow-x: auto;
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    /* Responsive untuk layar kecil */
    @media (max-width: 768px) {
        .biaya-col {
            min-width: 70px;
            font-size: 0.75rem;
        }
        
        th, td {
            padding: 6px 4px;
        }
    }
</style>
</div>

<!-- JavaScript untuk Export -->
<script>
function exportToExcel() {
    // Implementasi export Excel
    alert('Fitur export Excel akan diimplementasikan');
    // Anda bisa menggunakan library seperti SheetJS atau mengarahkan ke route export
    // window.location.href = '/dashboard/total-transaksi/export';
}
</script>

<style>
    /* Tambahan styling */
    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }
</style>
@endsection