@extends('layouts.dashboard')

@section('title', 'Pelanggan Detail')

@section('content')

<style>

.dashboard_popup_order_kategori_btn.active {
    background: #C92B2F;
    border-color: #C92B2F;
    color: #ffffffff;
    font-weight: 600;
}
.tab-btn {
    padding: .4vw .66vw;
    border: .12vw solid rgb(153, 153, 153);
    background: #f3f4f6;
    color: #6b7280;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: .4vw;
    position: relative;
}

.tab-btn.tab-active {
    background: #C92B2F !important;
    color: white !important;
    border-color: #C92B2F !important;
    position: relative;
    z-index: 10;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
}

.tab-btn.tab-inactive {
    background: #f9fafb;
    color: #9ca3af;
    border-bottom: 1px solid rgb(153, 153, 153);
}
.js-jenis-order-btn {
    position: relative;
    padding: .55vw .66vw;
    border: .12vw solid #C92B2F;
    background: #C92B2F;
    color: #ffffffff;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: .6vw;
    text-align: center;
    box-shadow: 0 8px 15px -3px #c92b305d;
}

/* Button jenis order aktif (sedang diedit) */
.js-jenis-order-btn.jenis-active {
    background: #C92B2F !important;
    color: white !important;
    border-color: #C92B2F !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px -3px #c92b305d;
}

/* Button jenis order yang sudah ada data */
.js-jenis-order-btn.jenis-has-order {
    background: #C92B2F !important;
    color: white !important;
    border-color: #C92B2F !important;
}

.js-jenis-order-btn.jenis-has-order:hover {
    background: #C92B2F !important;
    border-color: #C92B2F !important;
}

.order-badge {
    position: absolute;
    top: -.7vw;
    right: -.5vw;
    background: white;
    color: #C92B2F;
    width:max-content;
    height:1.8vw;
    border-radius: 100vw;
    font-size: .8vw;
    font-weight: 700;
    padding:0 .3vw;
    display: flex;
    align-items: center;
    justify-content: center;
    border: .12vw solid #C92B2F;
    z-index: 10;
}
.harga-satuan-display {
    color: #C92B2F;
    font-weight: 600;
}

.harga-total-display {
    color: #C92B2F;
    font-weight: 700;
}

#grandTotalDisplay{
    color: #C92B2F;
    font-weight:800;
}


.active-order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: .3vw;
    margin-bottom: .3vw;
    border-bottom: .12vw solid #e5e7eb;
    width: 100%;
}

.active-order-title {
    font-weight: 700;
    color: black;
    margin: 0;
    font-size: 1.1vw;
}

.total-harga-order {
    background: #f8fafc;
    display:flex;
    gap:1vw;
    width: 100%;
    margin-top:.8vw;
}

.harga-row {
    display: flex;
    align-items: center;
    justify-content:flex-start;
    gap:.4vw;
    border:.12vw solid rgb(153, 153, 153);
    border-radius:.4vw;
    padding: .6vw 1vw;
    width:100%;
    margin-bottom:1vw;
}
.harga-row span{
    font-size:.95vw;
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
}
a{
    text-decoration:none;
}
.btn_header_layout{
    display:flex;
    align-items:flex-end;
    gap:.8vw;
}
.btn_print_nota_yow_sc{
    outline:none !important;
    display:flex !important;
    align-items:center !important;
    gap:.4vw !important;
    font-size:.85vw !important;
    font-weight:500 !important;
    color:white !important;
    border:.12vw solid #C92B2F !important;
    border-radius:.4vw !important;
    padding:.16vw .7vw !important;
    margin-bottom:.6vw !important;
    background-color: #C92B2F !important;
}
.badge-job {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    margin: 0.125rem;
}

/* Untuk details/summary */
details summary {
    list-style: none;
}
details summary::-webkit-details-marker {
    display: none;
}
details summary::after {
    content: '▶';
    display: inline-block;
    margin-left: 0.5rem;
    transition: transform 0.2s;
}
details[open] summary::after {
    transform: rotate(90deg);
}
.pembayaran-section{
    width: 100%;
    margin-top:.4vw;
}
.pembayaran-section h3{
    font-size:1vw;
}
.ringkasan-pembayaran{
    margin-top: .4vw;
    border-radius: 8px; 
    grid-gap:.9vw;
    display:grid;
    grid-template-columns:repeat(4, 1fr);
}
.ringkasan-item{
    background: white; 
    padding: .8vw; 
    border-radius: .6vw; 
    border: .12vw solid #999999ff;
}
.ringkasan-item-box{
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.ringkasan-item-box button{
    outline:none;
    border:none;
    border-radius:100vw;
    width:1.6vw;
    height:1.6vw;
    font-size:1vw;
    display:flex;
    align-items:center;
    justify-content:center;
    background-color:;
}
.ringkasan-item h5{
    font-size:.85vw;
    color:black;
    font-weight:500;
    margin-bottom:.4vw;
}
.ringkasan-item span{
    font-size:1.2vw;
    font-weight:800;
}

@media print {
    @page {
        size: A4 portrait !important; /* Orientasi landscape untuk ruang lebih */
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
<div class="dashboard_banner dashboard_banner_print">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
</div>
<div class="layout_black">
    <div id="popupModal" class="dashboard_popup_order popup_custom active" style="z-index:9 !important;">
        <div class="dashboard_popup_order_wrapper">
            <div class="dashboard_popup_order_box">
                <div class="form_field_normal">
                    <div class="dashboard_popup_order_heading">
                        <label>Nama Konsumen</label>
                        <div class="btn_header_layout">
                            <button onclick="window.print()" class="btn_print_nota_yow hidden_print">
                                🖨 Print
                            </button>
                            <a href="{{ route('pelanggan.show', $pelanggan->id) }}">
                                <button class="btn_print_nota_yow_sc hidden_print" style="background-color:black !important; border-color:black !important;">Invoice</button>
                            </a>
                            <a href="{{ route('pelanggan.nota', $pelanggan->id) }}">
                                <button class="btn_print_nota_yow_sc hidden_print">Nota</button>
                            </a>
                        </div>
                    </div>
                    <input
                        type="text"
                        class="input_form"
                        value="{{ 
                            $pelanggan->affiliate
                                ? $pelanggan->nama . ' (Sales : ' . $pelanggan->affiliate->nama . ')'
                                : $pelanggan->nama
                        }}"
                        readonly
                    >
                </div>
                <div class="form_field_normal">
                    <div class="dashboard_popup_order_heading">
                        <label>Nama Job</label>
                    </div>
                    <div class="form_field_normal">
                        @if(count($semuaNamaJob) > 0)
                            <textarea class="input_form" rows="1" readonly>{{ implode(', ', $semuaNamaJob) }}</textarea>
                        @else
                            <input type="text" class="input_form" value="Tidak ada data job" readonly>
                        @endif
                    </div>
                </div>
                <div class="form_field_normal">
                    <div class="dashboard_popup_order_kategori">
                        @foreach ($allKategori as $kategori)
                            <button
                                type="button"
                                class="tab-btn dashboard_popup_order_kategori_btn
                                    {{ $selectedKategoriIds->contains($kategori->id) ? 'active' : '' }}"
                            >
                                {{ $kategori->nama }}
                            </button>
                        @endforeach
                    </div>
                    <div class="kategori-wrapper">
                        <div class="dashboard_popup_order_kategori_layout">
                            @foreach ($groupedOrders as $jenisOrderId => $orders)
                                <button type="button"
                                    class="js-jenis-order-btn btn_kategori_order background_gray_young">

                                    {{ $orders->first()->jenisOrder->nama_jenis ?? '-' }}

                                    <span class="order-badge">
                                        {{ $orders->sum('qty') }}
                                    </span>
                                </button>
                            @endforeach


                        </div>
                    </div>
                </div>

                <!-- Loop semua orders dari pelanggan -->
               

                @if($groupedOrders->count() > 0)
                    @foreach ($groupedOrders as $jenisOrderId => $orders)
                    <div class="nota_order_item">
                        @php
                            $jenisOrder  = $orders->first()->jenisOrder;
                            $totalQty    = $orders->sum('qty');
                            $hargaSatuan = $jenisOrder->harga_jual ?? 0;
                            $totalHarga  = $totalQty * $hargaSatuan;
                            
                            // Cek apakah order ini sudah lunas
                            $isOrderLunas = $orders->every(function($order) {
                                $pembayaranOrder = \App\Models\Pembayaran::where('order_id', $order->id)->first();
                                return $pembayaranOrder && $pembayaranOrder->status;
                            });
                        @endphp

                        <div class="nota_main_layout {{ $isOrderLunas ? 'order-lunas' : '' }}" id="orderFormTemplate">

                            <div class="active-order-header">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h3 class="active-order-title">{{ $jenisOrder->nama_jenis }}</h3>
                                </div>
                            </div>

                            {{-- SIZE --}}
                            <div class="nota_main_size">
                                <div class="nota_main_size_grid">
                                    @foreach (['XS','S','M','L','XL','2XL','3XL','4XL','5XL','6XL'] as $size)
                                        <label>{{ $size }}</label>
                                    @endforeach
                                </div>

                                <div class="nota_main_size_grid">
                                    @foreach (['xs','s','m','l','xl','2xl','3xl','4xl','5xl','6xl'] as $key)
                                        <input type="number" class="input_form" value="{{ $orders->sum(fn($o) => optional($o->size)->{$key} ?? 0) }}" readonly>
                                    @endforeach
                                </div>
                            </div>

                            <div class="total-harga-order">
                                <div class="harga-row">
                                    <span>Harga Satuan:</span>
                                    <span class="harga-satuan-display"><strong>Rp {{ number_format($hargaSatuan) }}</strong></span>
                                </div>
                                <div class="harga-row">
                                    <span>Total Qty:</span>
                                    <span class="harga-total-display"><strong>{{ $totalQty }}</strong></span>
                                </div>
                                <div class="harga-row">
                                    <span>Total Harga:</span>
                                    <span class="harga-total-display"><strong>Rp {{ number_format($totalHarga) }}</strong></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Jika tidak ada order sama sekali -->
                    <div class="nota_order_item text-center" style="padding: 40px; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 48px; color: #9ca3af; margin-bottom: 20px;">📭</div>
                        <h3 style="color: #6b7280; font-size: 24px; font-weight: bold;">TIDAK ADA ORDER</h3>
                        <p style="color: #9ca3af; margin-top: 10px;">Pelanggan ini belum memiliki order</p>
                    </div>
                @endif

                @if($adaOrderBelumLunas)
                    <!-- Jika ada order yang belum lunas -->
                    <div class="ringkasan-pembayaran" id="ringkasanPembayaran">
                        <div class="ringkasan-item">
                            <h5>Total Harus Dibayar</h5>
                            <span id="totalHarusDibayar">Rp {{ number_format($totalHarusDibayarBelumLunas) }}</span>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Total Sudah Dibayar (DP)</h5>
                            <span class="text-green-500" id="totalSudahDibayar">Rp {{ number_format($totalSudahDibayarBelumLunas) }}</span>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Total Sisa Bayar</h5>
                            <div class="ringkasan-item-box">
                                <span class="text-red-500" id="totalSisaBayarDisplay">Rp {{ number_format($totalSisaBayarBelumLunas) }}</span>
                                <button type="button" 
                                    onclick="openEditSisaBayarModal()"
                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                    title="Edit sisa bayar">
                                    ✏️
                                </button>
                            </div>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Status</h5>
                            <span id="statusPembayaran" class="{{ $statusPembayaranClass }}">
                                {{ $statusPembayaranText }}
                            </span>
                        </div>
                    </div>
                @else
                    <!-- Jika semua order sudah lunas -->
                    <div class="ringkasan-pembayaran">
                        <div class="ringkasan-item">
                            <h5>Total Order</h5>
                            <span style="font-size: 20px; font-weight: bold;">{{ $totalOrderCount }}</span>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Total Nilai Order</h5>
                            <span style="font-size: 18px; font-weight: bold; color: #10b981;">
                                Rp {{ number_format($totalKeseluruhanHargaAll) }}
                            </span>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Total DP/Dibayar</h5>
                            <span style="font-size: 18px; font-weight: bold; color: #3b82f6;">
                                Rp {{ number_format($totalDPAll) }}
                            </span>
                        </div>
                        
                        <div class="ringkasan-item">
                            <h5>Status</h5>
                            <span style="font-size: 18px; font-weight: bold; color: #10b981;">
                                LUNAS
                            </span>
                        </div>
                    </div>
                    
                @endif

                <!-- Modal Edit Sisa Bayar -->
                <div id="editSisaBayarModal" class="modal-overlay" style="display: none;">
                    <div class="modal-content-tagihan">
                        <button type="button" onclick="closeEditModal()" class="modal-close">&times;</button>
                        <div class="modal-body">
                            <form id="editSisaBayarForm">
                                @csrf
                                <input type="hidden" id="pelanggan_id" value="{{ $pelanggan->id }}">
                                
                                <!-- Total Harus Dibayar -->
                                <div class="form_field_normal">
                                    <label>Total Harus Dibayar</label>
                                    <input type="text" 
                                        id="totalHarusDibayarModal" 
                                        class="input_form bg-gray-50 font-bold" 
                                        value="Rp {{ number_format($totalHarusDibayarBelumLunas) }}" 
                                        readonly>
                                </div>

                                <!-- Total Sudah Dibayar (DP) -->
                                <div class="form_field_normal">
                                    <label>Total Sudah Dibayar (DP)</label>
                                    <input type="text" 
                                        id="totalSudahDibayarModal" 
                                        class="input_form bg-green-50" 
                                        value="Rp {{ number_format($totalSudahDibayarBelumLunas) }}" 
                                        readonly>
                                </div>

                                <!-- Sisa Bayar Setelah Pembayaran -->
                                <div class="form_field_normal">
                                    <label>Sisa Bayar Setelah Pembayaran</label>
                                    <input type="text" 
                                        id="sisaBayarSetelah" 
                                        class="input_form bg-yellow-50 font-bold text-lg" 
                                        value="Rp {{ number_format($totalSisaBayarBelumLunas) }}" 
                                        readonly>
                                </div>
                                
                                <!-- Status Setelah Pembayaran -->
                                <div class="form_field_normal hidden" id="statusSection">
                                    <label>Status Setelah Pembayaran</label>
                                    <div id="statusSetelah" class="mt-2 p-3 rounded border">
                                        <div class="flex items-center">
                                            <span class="text-red-600 font-bold">BELUM LUNAS</span>
                                            <span class="text-gray-500 text-sm ml-2">• Sisa bayar: Rp {{ number_format($totalSisaBayarBelumLunas) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 my-4 pt-4">
                                    <div class="form_field_normal">
                                        <label for="pembayaranBaruInput">Jumlah Pembayaran</label>
                                        <div class="relative">
                                            <input type="text" 
                                                id="pembayaranBaruInput" 
                                                name="pembayaran_baru"
                                                class="input_form pl-10 border-blue-300"
                                                value="0"
                                                placeholder="0"
                                                required
                                                data-raw-value="0">
                                        </div>
                                    </div>
                                    
                                    <!-- Quick Payment Buttons -->
                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        <button type="button" 
                                            onclick="setPembayaranPenuh()"
                                            class="bayar_lunas">
                                            Bayar Lunas
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="dashboard_popup_order_btn">
                                    <button type="button" onclick="closeEditModal()" class="btn-secondary">Batal</button>
                                    <button type="submit" class="btn-primary">
                                        Simpan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentSisaBayar = {{ $totalSisaBayarBelumLunas }};
let totalSudahDibayar = {{ $totalSudahDibayarBelumLunas }};
let totalHarusDibayar = {{ $totalHarusDibayarBelumLunas }};
let pelangganId = {{ $pelanggan->id }};

// Format Rupiah
function formatRupiah(angka) {
    if (!angka) return '0';
    
    // Remove all non-digit characters
    angka = angka.toString().replace(/[^\d]/g, '');
    
    // Format with dots
    const reverse = angka.toString().split('').reverse().join('');
    const ribuan = reverse.match(/\d{1,3}/g);
    const formatted = ribuan ? ribuan.join('.').split('').reverse().join('') : '0';
    
    return formatted;
}

// Parse Rupiah to number
function parseRupiah(rupiahString) {
    if (!rupiahString) return 0;
    
    // Remove all non-digit characters
    const angka = rupiahString.toString().replace(/[^\d]/g, '');
    return parseInt(angka) || 0;
}

// Update input with formatted value
function updateInputFormat(inputElement) {
    const rawValue = inputElement.getAttribute('data-raw-value') || '0';
    const formattedValue = formatRupiah(rawValue);
    
    // Update displayed value
    inputElement.value = formattedValue;
    
    // Update calculations
    updateCalculations();
}

// Modal functions
function openEditSisaBayarModal() {
    document.getElementById('editSisaBayarModal').style.display = 'flex';
    resetForm();
    
    // Focus on input
    setTimeout(() => {
        const input = document.getElementById('pembayaranBaruInput');
        if (input) {
            input.focus();
            input.select();
        }
    }, 100);
}

function closeEditModal() {
    document.getElementById('editSisaBayarModal').style.display = 'none';
    resetForm();
}

function resetForm() {
    const input = document.getElementById('pembayaranBaruInput');
    if (input) {
        input.setAttribute('data-raw-value', '0');
        input.value = '0';
    }
    updateCalculations();
}

// Update calculations when payment input changes
function updateCalculations() {
    const input = document.getElementById('pembayaranBaruInput');
    if (!input) return;
    
    const rawValue = input.getAttribute('data-raw-value') || '0';
    const pembayaranBaru = parseInt(rawValue) || 0;
    
    // Validate max payment
    if (pembayaranBaru > currentSisaBayar) {
        // Auto-correct to max
        input.setAttribute('data-raw-value', currentSisaBayar.toString());
        updateInputFormat(input);
        return;
    }
    
    // Calculate new sisa bayar and total sudah dibayar
    const sisaSetelah = currentSisaBayar - pembayaranBaru;
    const totalSudahDibayarBaru = totalSudahDibayar + pembayaranBaru;
    
    // Update display
    const sisaBayarElement = document.getElementById('sisaBayarSetelah');
    if (sisaBayarElement) {
        sisaBayarElement.value = 'Rp ' + sisaSetelah.toLocaleString('id-ID');
    }
    
    // Update status in modal (if element exists)
    const statusDiv = document.getElementById('statusSetelah');
    if (statusDiv) {
        if (sisaSetelah <= 0) {
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <span class="text-green-600 font-bold">LUNAS ✓</span>
                    <span class="text-green-500 text-sm ml-2">• Semua tagihan terlunasi</span>
                </div>
                <div class="text-xs text-green-600 mt-1">
                    Total dibayar: Rp ${totalSudahDibayarBaru.toLocaleString('id-ID')}
                </div>
            `;
            statusDiv.className = 'mt-2 p-3 rounded border border-green-200 bg-green-50';
        } else {
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <span class="text-red-600 font-bold">BELUM LUNAS</span>
                    <span class="text-gray-500 text-sm ml-2">• Sisa bayar: Rp ${sisaSetelah.toLocaleString('id-ID')}</span>
                </div>
                <div class="text-xs text-gray-600 mt-1">
                    Total dibayar: Rp ${totalSudahDibayarBaru.toLocaleString('id-ID')} dari Rp ${totalHarusDibayar.toLocaleString('id-ID')}
                </div>
            `;
            statusDiv.className = 'mt-2 p-3 rounded border border-red-200 bg-red-50';
        }
    }
}

// Quick payment buttons
function setPembayaranPenuh() {
    const input = document.getElementById('pembayaranBaruInput');
    if (input) {
        input.setAttribute('data-raw-value', currentSisaBayar.toString());
        updateInputFormat(input);
    }
}

// Handle input events for formatting
function handlePaymentInput(e) {
    const input = e.target;
    
    // Get current value without formatting
    let rawValue = input.getAttribute('data-raw-value') || '0';
    
    // Get the new character(s)
    const newChar = e.data || '';
    
    // Handle backspace/delete
    if (e.inputType === 'deleteContentBackward') {
        // Remove last character
        rawValue = rawValue.slice(0, -1);
        if (!rawValue) rawValue = '0';
    } else if (newChar.match(/\d/)) {
        // Add new digit, remove leading zero
        if (rawValue === '0') {
            rawValue = newChar;
        } else {
            rawValue += newChar;
        }
    }
    
    // Store raw value
    input.setAttribute('data-raw-value', rawValue);
    
    // Format and update display
    const formattedValue = formatRupiah(rawValue);
    input.value = formattedValue;
    
    // Move cursor to end
    setTimeout(() => {
        input.setSelectionRange(formattedValue.length, formattedValue.length);
    }, 0);
    
    // Update calculations
    updateCalculations();
}

// Handle paste event
function handlePaymentPaste(e) {
    e.preventDefault();
    const input = e.target;
    const pastedData = e.clipboardData.getData('text');
    
    // Extract numbers only
    const numbersOnly = pastedData.replace(/[^\d]/g, '');
    
    if (numbersOnly) {
        // Remove leading zeros
        const cleanValue = numbersOnly.replace(/^0+/, '') || '0';
        input.setAttribute('data-raw-value', cleanValue);
        updateInputFormat(input);
    }
}

// Submit payment
async function submitPembayaran(e) {
    e.preventDefault();
    
    const input = document.getElementById('pembayaranBaruInput');
    if (!input) return;
    
    const rawValue = input.getAttribute('data-raw-value') || '0';
    const pembayaranBaru = parseInt(rawValue) || 0;
    
    if (pembayaranBaru <= 0) {
        showToast('Masukkan jumlah pembayaran yang valid', 'error');
        return;
    }
    
    if (pembayaranBaru > currentSisaBayar) {
        showToast('Pembayaran tidak boleh melebihi sisa bayar', 'error');
        return;
    }
    
    // Calculate new sisa bayar
    const sisaBaru = currentSisaBayar - pembayaranBaru;
    
    try {
        showToast('Menyimpan pembayaran...', 'info');
        
        const response = await fetch(`/api/pembayaran/pelanggan/${pelangganId}/bayar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                pembayaran_baru: pembayaranBaru,
                sisa_bayar_baru: sisaBaru
            })
        });
        
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error('Non-JSON response:', text.substring(0, 200));
            throw new Error('Server error: ' + response.status);
        }
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan');
        }
        
        if (result.success) {
            showToast(result.message, 'success');
            
            // Update display on page
            const totalSisaBayarDisplay = document.getElementById('totalSisaBayarDisplay');
            const totalSudahDibayarDisplay = document.getElementById('totalSudahDibayar');
            const statusPembayaranDisplay = document.getElementById('statusPembayaran');
            
            if (totalSisaBayarDisplay) {
                totalSisaBayarDisplay.textContent = result.data.total_sisa_bayar_formatted;
            }
            
            if (totalSudahDibayarDisplay) {
                totalSudahDibayarDisplay.textContent = result.data.total_dp_formatted;
            }
            
            if (statusPembayaranDisplay) {
                statusPembayaranDisplay.textContent = result.data.status_text;
                statusPembayaranDisplay.className = result.data.status_class;
            }
            
            // Update current values for modal
            currentSisaBayar = result.data.total_sisa_bayar;
            totalSudahDibayar = result.data.total_dp;
            
            // Close modal
            closeEditModal();
            
            // Reload page after 2 seconds to update all data
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message, 'error');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submission
    const form = document.getElementById('editSisaBayarForm');
    if (form) {
        form.addEventListener('submit', submitPembayaran);
    }
    
    // Payment input event listeners
    const inputPembayaran = document.getElementById('pembayaranBaruInput');
    if (inputPembayaran) {
        // Handle input events
        inputPembayaran.addEventListener('input', handlePaymentInput);
        
        // Handle paste
        inputPembayaran.addEventListener('paste', handlePaymentPaste);
        
        // Prevent non-numeric input
        inputPembayaran.addEventListener('keydown', function(e) {
            // Allow: backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 67 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 86 && (e.ctrlKey || e.metaKey)) ||
                (e.keyCode === 88 && (e.ctrlKey || e.metaKey)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
                (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Enter key submits form
        inputPembayaran.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (form) {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });
        
        // Focus behavior
        inputPembayaran.addEventListener('focus', function() {
            if (this.value === '0') {
                this.setSelectionRange(this.value.length, this.value.length);
            }
        });
    }
    
    // Close modal on outside click
    const modal = document.getElementById('editSisaBayarModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeEditModal();
        }
    });
});

// Toast notification
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialize when page loads
function initPaymentModal() {
    console.log('Payment modal initialized');
    
    // Check if modal elements exist
    const modal = document.getElementById('editSisaBayarModal');
    const input = document.getElementById('pembayaranBaruInput');
    const form = document.getElementById('editSisaBayarForm');
    
    if (!modal) console.warn('Modal element not found');
    if (!input) console.warn('Payment input element not found');
    if (!form) console.warn('Form element not found');
    
    // Initialize input value
    if (input) {
        input.setAttribute('data-raw-value', '0');
        input.value = '0';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initPaymentModal);
</script>

<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content-tagihan {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    animation: modalSlideIn 0.3s ease;
    max-width:40%;
    position: relative;
}

@keyframes modalSlideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.7vw;
    cursor: pointer;
    color: #6b7280;
    position:absolute;
    right:.7vw;
    top:0;
}

.modal-close:hover {
    color: #374151;
}

.modal-body {
    padding: 20px;
}

.bayar_lunas{
    padding:.5vw .7vw;
    outline:none;
    border:none;
    font-size:.9vw;
    background-color:#10b981;
    color:white;
    cursor: pointer;
    border-radius:.3vw;
    margin-top:-.7vw;
}

/* Toast Styles */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 4px;
    color: white;
    z-index: 1001;
    animation: toastSlideIn 0.3s ease;
}

.toast.success {
    background: #10b981;
}

.toast.error {
    background: #ef4444;
}

.toast.info {
    background: #3b82f6;
}

@keyframes toastSlideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Form Styles */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #374151;
}
</style>

@endsection