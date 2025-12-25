@extends('layouts.dashboard')

@section('title', 'Pesanan')

@section('content')
    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <button onclick="openModal()">Tambah Job</button>
            <select id="select2-pelanggan" class="w-64">
                <option value="">Cari Nama Pelanggan...</option>
                @foreach ($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->id }}">
                        {{ $pelanggan->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="orders_table_container">
        <table>
            
            <!-- HEADER -->
            <thead>
                <tr>
                    <th><div class="text-center">No</div></th>
                    <th><div class="table_width">Nama Konsumen</div></th>
                    <th><div class="table_width_tgl text-center">Affiliator</div></th>
                    <th><div class="table_width_jenis text-center">Jenis Job</div></th>
                    <th><div class="table_width_tgl text-center">Kategori</div></th>
                    <th><div class="table_width_tgl text-center">Tanggal</div></th>
                    <th><div class="text-center">Qty</div></th>
                    <th><div class="text-center">Hari</div></th>
                    <th>Deadline</th>
                    <th>Setting</th>
                    <th>Print</th>
                    <th>S</th>
                    <th>Press</th>
                    <th>S</th>
                    <th>Cutting</th>
                    <th>S</th>
                    <th><div class="text-center">Jahit</div></th>
                    <th>S</th>
                    <th>Finishing</th>
                    <th>S</th>
                    <th>Packing</th>
                    <th><div class="text-center">S</div></th>
                    <th><div class="text-center">Est</div></th>
                    <th><div class="text-center">Status</div></th>
                    <th><div class="text-center">Aksi</div></th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody id="orders-table-body">
                @foreach ($orders as $o)
                    <tr class="{{ $loop->even ? 'bg_gray_200' : 'bg-white' }}">
                        <td><div class="text-center iteration-number">{{ $loop->iteration }}</div></td>
                        @php
                            $pivot = \DB::table('pelanggan_orders')->where('order_id', $o->id)->first();
                            $pelanggan = $pivot ? \App\Models\Pelanggan::find($pivot->pelanggan_id) : null;
                        @endphp
                        <td>
                            <div class="text-xs">
                                @if($pelanggan)
                                    <a href="{{ route('pelanggan.show', $pelanggan->id) }}" class="text-black font-semibold hover:underline">
                                        {{ $pelanggan->nama }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                @if($o->affiliates->isNotEmpty())
                                    {{ $o->affiliates->first()->nama }}
                                    <br>
                                    <small class="text-muted">({{ $o->affiliator_kode }})</small>
                                @elseif($o->affiliator_kode)
                                    {{ $o->affiliator_kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td><div class="text-center">{{ $o->nama_job }} </div></td>
                        <td><div class="text-center">{{ optional($o->jenisOrder)->nama_jenis ?? '' }}</div></td>
                        <td><div class="text-center">{{ $o->created_at->format('Y-m-d') }}</div></td>
                        <td><div class="text-center">{{ $o->qty }}</div></td>
                        <td><div class="text-center">{{ (float) $o->hari }}</div></td>
                        <td><div class="text-center">{{ (float) $o->deadline }}</div></td>
                        <td>
                            <div class="text-center">
                                <img 
                                    src="{{ asset($o->setting == 1 ? 'icons/check-icon.svg' : 'icons/close-icon.svg') }}"
                                    alt="{{ $o->setting == 1 ? 'Check' : 'Close' }}"
                                    class="orders_icon_status"
                                >
                            </div>
                        </td>
                        <td><div class="text-center">{{ $o->print }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_print }}</div></td>
                        <td><div class="text-center">{{ $o->press }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_press }}</div></td>
                        <td><div class="text-center">{{ $o->cutting }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_cutting }}</div></td>
                        <td><div class="text-center">{{ $o->jahit }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_jahit }}</div></td>
                        <td><div class="text-center">{{ $o->finishing }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_finishing }}</div></td>
                        <td><div class="text-center">{{ $o->packing }}</div></td>
                        <td><div class="text-center">{{ $o->sisa_packing }}</div></td>
                        <td><div class="text-center">{{ $o->est }}</div></td>
                        <td>
                            @if($o->status)
                                <span class="status-badge bg-green-100 text-green" data-id="{{ $o->id }}">Lunas</span>
                            @else
                                <span class="status-badge bg-red-100 text-red" data-id="{{ $o->id }}">Belum</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn_orders_action">
                                <button 
                                    class="btn_change btn-toggle-status {{ !$o->status && $o->sisa_packing != 0 ? 'bg-gray-400 text-gray-600' : 'bg-blue-500 text-white' }}" 
                                    data-id="{{ $o->id }}" 
                                    data-status="{{ $o->status ? 'true' : 'false' }}"
                                    {{ !$o->status && $o->sisa_packing != 0 ? 'disabled title="Tidak bisa tandai lunas, sisa packing masih ada"' : '' }}>
                                    {{ $o->status ? 'Tandai Belum' : 'Tandai Lunas' }}
                                </button>

                                <button class="text-red btn-delete" data-id="{{ $o->id }}">
                                    <img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon">
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-400 font-bold">
                    <td class="text-center" colspan="6">TOTAL KESELURUHAN</td>
                    
                    <td id="total_qty">
                        <div class="text-center">
                            <b>{{ $totals?->total_qty }}</b>
                        </div>
                    </td>
                    <td id="total_hari">
                        <div class="text-center">
                        <b>{{ (float) $totals?->total_hari }}</b>
</div>
                    </td>
                    <td id="total_deadline">
                        <div class="text-center">
                        <b>{{ (float) $totals?->total_deadline }}</b>
</div>
                    </td>
                    <td id="total_setting">
                        <div class="text-center">
                        <b>{{ $totals?->total_setting }}</b>
</div>
                    </td>
                    <td id="total_print">
                        <div class="text-center">
                        <b>{{ $totals?->total_print }}</b>
</div>
                    </td>
                    <td id="total_sisa_print">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_print }}</b>
</div>
                    </td>
                    <td id="total_press">
                        <div class="text-center">
                        <b>{{ $totals?->total_press }}</b>
</div>
                    </td>
                    <td id="total_sisa_press">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_press }}</b>
</div>
                    </td>
                    <td id="total_cutting">
                        <div class="text-center">
                        <b>{{ $totals?->total_cutting }}</b>
</div>
                    </td>
                    <td id="total_sisa_cutting">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_cutting }}</b>
</div>
                    </td>
                    <td id="total_jahit">
                        <div class="text-center">
                        <b>{{ $totals?->total_jahit }}</b>
</div>
                    </td>
                    <td id="total_sisa_jahit">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_jahit }}</b>
</div>
                    </td>
                    <td id="total_finishing">
                        <div class="text-center">
                        <b>{{ $totals?->total_finishing }}</b>
</div>
                    </td>
                    <td id="total_sisa_finishing">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_finishing }}</b>
</div>
                    </td>
                    <td id="total_packing">
                        <div class="text-center">
                        <b>{{ $totals?->total_packing }}</b>
</div>
                    </td>
                    <td id="total_sisa_packing">
                        <div class="text-center">
                        <b>{{ $totals?->total_sisa_packing }}</b>
</div>
                    </td>
                    
                    <td id="est">
                        <div class="text-center">
                        <b>{{ (float) $totals?->total_hari }}</b>
</div>
                    </td>
                    
                    <td class="border border-gray-300 px-4 py-2 text-center" colspan="2">
                        </td>
                </tr>
            </tfoot>
        </table>
    </div>

<!-- Modal Login untuk Toggle Status -->
<div id="loginModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 15% auto; padding: 20px; width: 320px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 20px 0; color: #333;">Login untuk Mengubah Status</h3>
        
        <div id="loginError" style="color: #e53e3e; background: #fed7d7; padding: 10px; border-radius: 5px; margin-bottom: 15px; display: none;"></div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #4a5568;">Username:</label>
            <input type="text" id="modalUsername" 
                   style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 5px; font-size: 14px;"
                   placeholder="Masukkan username">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; color: #4a5568;">Password:</label>
            <input type="password" id="modalPassword" 
                   style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 5px; font-size: 14px;"
                   placeholder="Masukkan password">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="processLogin()" 
                    style="flex: 1; background: #4299e1; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Login
            </button>
            <button onclick="closeLoginModal()" 
                    style="flex: 1; background: #a0aec0; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
                Batal
            </button>
        </div>
    </div>
</div>

<div id="popupModal" class="dashboard_popup_order popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">

            <!-- HEADER -->
            


            <!-- FORM -->
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="form_field">
                    <div class="dashboard_popup_order_heading">
                        <label>Nama Konsumen</label>
                        <button onclick="closeModal()" type="button"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
                    </div>
                    <div class="dashboard_popup_order_konsumen">
                        <select id="select2-nama-konsumen" name="nama_konsumen" required>
                            <option value="" selected disabled>Pilih Nama Konsumen...</option>
                            @foreach ($pelanggans as $pelanggan)
                                @php
                                    $affiliateKode = $pelanggan->affiliate ? $pelanggan->affiliate->kode : null;
                                    $affiliateNama = $pelanggan->affiliate ? $pelanggan->affiliate->nama : null;
                                @endphp
                                <option value="{{ $pelanggan->id }}" 
                                    @if($affiliateKode)
                                        data-affiliate-kode="{{ $affiliateKode }}"
                                        data-affiliate-nama="{{ $affiliateNama }}"
                                        data-has-affiliate="true"
                                    @else
                                        data-has-affiliate="false"
                                    @endif>
                                    {{ $pelanggan->nama }}
                                    @if($affiliateNama)
                                        (Affiliator : {{ $affiliateNama }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div></div>
                        
                        <!-- Input untuk kode affiliate -->
                        <input type="text" name="affiliator_kode" id="affiliator_kode_input" 
                            class="input_form affiliate-input" 
                            placeholder="Kode Affiliator (Opsional)" value="">
                    </div>
                </div>

                <!-- Nama Job -->
                <div class="form_field">
                    <div class="dashboard_popup_order_kategori">

                        @foreach ($kategoriList as $kategori)
                            <button type="button"
                                onclick="openKategori({{ $kategori->id }})"
                                class="tab-btn dashboard_popup_order_kategori_btn">
                                {{ $kategori->nama }}
                            </button>
                        @endforeach

                    </div>
                    <select id="select2-nama-job" name="nama_job" required>
                        <option value="" selected disabled>Pilih Nama Job...</option>
                        @foreach ($jobs as $a)
                            <option value="{{ $a->nama_job }}">{{ $a->nama_job }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form_field">

                    @foreach ($kategoriList as $kategori)

                        <!-- WRAPPER PER KATEGORI -->
                        <div class="kategori-wrapper hidden" id="kategori-{{ $kategori->id }}">

                            <div class="dashboard_popup_order_kategori_layout">

                                @foreach ($jenisOrders->where('id_kategori_jenis_order', $kategori->id) as $jenis)

                                    <input 
                                        type="radio" 
                                        id="jenis-{{ $jenis->id }}" 
                                        name="jenis_order_id" 
                                        value="{{ $jenis->id }}" 
                                        class="hidden js-pakaian-input"
                                    >

                                    <label 
                                        for="jenis-{{ $jenis->id }}"
                                        class="js-pakaian-label btn_kategori_order background_gray_young">
                                        {{ $jenis->nama_jenis }}
                                    </label>

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>

                <!-- Qty -->
                <!-- SIZE INPUTS -->
                <div class="dashboard_popup_order_size form_field">
                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">XS</label>
                        <input type="number" name="xs" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">S</label>
                        <input type="number" name="s" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">M</label>
                        <input type="number" name="m" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">L</label>
                        <input type="number" name="l" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">XL</label>
                        <input type="number" name="xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">2XL</label>
                        <input type="number" name="2xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">3XL</label>
                        <input type="number" name="3xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">4XL</label>
                        <input type="number" name="4xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">5XL</label>
                        <input type="number" name="5xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">6XL</label>
                        <input type="number" name="6xl" value="0" oninput="hitungQty()">
                    </div>

                    <div class="dashboard_popup_order_size_box">
                        <label class="block text-xs">QTY</label>
                        <input type="number" 
                            name="qty"
                            id="qty"
                            readonly
                            value="0"
                            class="border bg-gray-100 text-xs rounded px-2 py-2"
                            placeholder="Otomatis dari size">
                    </div>
                </div>

                <div class="form_field">
                    <div class="dashboard_popup_order_heading">
                        <label>Keterangan</label>
                    </div>
                    <div class="keterangan_option" id="keteranganContainer" style="display: none;">
                        <!-- Akan di-populate oleh JavaScript -->
                    </div>
                    <input type="text" name="keterangan" id="keteranganInput" class="input_form" placeholder="Pilih keterangan dari kotak hijau..." readonly>
                </div>

                <!-- BUTTON -->
                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeModal()">
                        Batal
                    </button>

                    <button type="submit">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- MODAL DELETE ORDER -->
<div id="deleteOrderModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus order ini?
        </p>
        <input type="hidden" id="orderIdToDelete">
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelDeleteOrderBtn">Batal</button>
            <button id="confirmDeleteOrderBtn">Hapus</button>
        </div>
    </div>
</div>

<style>
/* Toast Notification - Versi Sederhana */
.notification-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.notification-toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-width: 250px;
}

.toast-success {
    background-color: #10b981;
    color: white;
}

.toast-error {
    background-color: #ef4444;
    color: white;
}

.toast-warning {
    background-color: #f59e0b;
    color: white;
}

.toast-info {
    background-color: #3b82f6;
    color: white;
}

.toast-icon {
    font-weight: bold;
    margin-right: 10px;
    font-size: 18px;
}

.toast-message {
    font-size: 14px;
    flex-grow: 1;
}

.toast-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    font-size: 16px;
    margin-left: 8px;
    padding: 0;
    line-height: 1;
}

.toast-close:hover {
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectPelanggan = document.getElementById('select2-nama-konsumen');
    const inputAffiliator = document.getElementById('affiliator_kode_input');
    
    if (selectPelanggan && inputAffiliator) {
        selectPelanggan.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const affiliateKode = selectedOption.getAttribute('data-affiliate-kode');
            const affiliateNama = selectedOption.getAttribute('data-affiliate-nama');
            const hasAffiliate = selectedOption.getAttribute('data-has-affiliate') === 'true';
            
            if (hasAffiliate && affiliateKode) {
                // Pelanggan sudah punya affiliate
                // 1. Isi dengan kode affiliate
                inputAffiliator.value = affiliateKode;
                
                // 2. Buat READONLY dan styling khusus
                inputAffiliator.readOnly = true;
                inputAffiliator.style.backgroundColor = '#f0f0f0';
                inputAffiliator.style.cursor = 'not-allowed';
                inputAffiliator.style.borderColor = '#ccc';
                inputAffiliator.title = "Kode affiliate sudah terhubung dengan pelanggan ini";
                
            } else {
                // Pelanggan TIDAK punya affiliate
                // 1. Kosongkan input
                inputAffiliator.value = '';
                
                // 2. Buat EDITABLE dan styling normal
                inputAffiliator.readOnly = false;
                inputAffiliator.style.backgroundColor = '';
                inputAffiliator.style.cursor = '';
                inputAffiliator.style.borderColor = '';
                inputAffiliator.title = "";
                inputAffiliator.placeholder = "Kode Affiliator (Opsional)";
            }
        });
        
        // Optional: Tambahkan icon lock untuk visual feedback
        const style = document.createElement('style');
        style.textContent = `
            .affiliate-input[readonly] {
                background-color: #f0f0f0 !important;
                cursor: not-allowed !important;
                border-color: #ccc !important;
                color: #555 !important;
                position: relative;
                padding-right: 35px;
            }
            .affiliate-input[readonly]::after {
                content: "🔒";
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 14px;
                opacity: 0.6;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Select2 version dengan styling lebih baik
    if (typeof $.fn.select2 !== 'undefined' && $('#select2-nama-konsumen').length) {
        $(document).ready(function() {
            $('#select2-nama-konsumen').select2({
                placeholder: "Pilih Nama Konsumen...",
                allowClear: true,
                width: '100%',
                tags: true,
            });
            
            // Styling untuk input readonly
            $('<style>').text(`
                .affiliate-locked {
                    background-color: #f5f5f5 !important;
                    border-color: #ddd !important;
                    color: #666 !important;
                    cursor: not-allowed !important;
                    position: relative;
                    padding-right: 35px;
                }
                .affiliate-locked::before {
                    content: "🔒";
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 14px;
                    opacity: 0.6;
                }
                .affiliate-info {
                    font-size: 11px;
                    color: #666;
                    margin-top: 3px;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }
                .affiliate-info span {
                    background: #e9ecef;
                    padding: 1px 6px;
                    border-radius: 3px;
                    font-size: 10px;
                }
            `).appendTo('head');
            
            $('#select2-nama-konsumen').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const affiliateKode = selectedOption.data('affiliate-kode');
                const affiliateNama = selectedOption.data('affiliate-nama');
                const hasAffiliate = selectedOption.data('has-affiliate');
                const $input = $('#affiliator_kode_input');
                const $container = $input.parent();
                
                // Hapus info sebelumnya
                $container.find('.affiliate-info').remove();
                
                if (hasAffiliate && affiliateKode) {
                    // READONLY - pelanggan sudah punya affiliate
                    $input.val(affiliateKode)
                        .addClass('affiliate-locked')
                        .prop('readonly', true)
                        .attr('title', `Kode affiliate terikat dengan pelanggan ini`);
                    
                } else {
                    // EDITABLE - pelanggan tidak punya affiliate
                    $input.val('')
                        .removeClass('affiliate-locked')
                        .prop('readonly', false)
                        .attr('title', '')
                        .attr('placeholder', 'Kode Affiliator (Opsional)');
                }
            });
            
            // Optional: Prevent user from editing readonly field
            $('#affiliator_kode_input').on('keydown', function(e) {
                if ($(this).prop('readonly')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }
});
</script>

<script>
$(document).ready(function() {
    $('#select2-pelanggan').select2({
        placeholder: "Cari Nama Pelanggan...",
        allowClear: true,
        width: 'resolve'
    });

    // ✅ Redirect ketika option diklik
    $('#select2-pelanggan').on('change', function() {
        let pelangganId = $(this).val();
        if (pelangganId) {
            window.location.href = "{{ url('/dashboard/pelanggan') }}/" + pelangganId;
        }
    });

    // ❌ Blok ENTER supaya tidak submit
    $(document).on('keydown', '.select2-search__field', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            return false;
        }
    });

});
</script>

<script>
    function hitungQty() {
        const sizes = [
            'xs', 's', 'm', 'l', 'xl',
            '2xl', '3xl', '4xl', '5xl', '6xl'
        ];

        let total = 0;

        sizes.forEach(function(size) {
            const inputQty = document.querySelector(`[name="${size}"]`);
            const value = parseInt(inputQty.value) || 0;

            // Jika minus, paksa jadi 0
            if (value < 0) {
                inputQty.value = 0;
                total += 0;
            } else {
                total += value;
            }
        });

        document.getElementById('qty').value = total;
    }
</script>

<script>
    function openKategori(id) {
        // sembunyikan semua kategori
        document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

        // tampilkan kategori yang dipilih
        document.getElementById('kategori-' + id).classList.remove('hidden');

        // ubah style tab
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('background_gray', 'text-white'));
        event.target.classList.add('background_gray', 'text-white');

        // Tampilkan keterangan box berdasarkan kategori yang dipilih
        // Jika ada jenis_order yang sedang dipilih di dalam kategori ini, gunakan filter berdasarkan jenis_order
        const kategoriEl = document.getElementById('kategori-' + id);
        const checkedJenis = kategoriEl.querySelector('.js-pakaian-input:checked');
        if (checkedJenis && typeof updateKeteranganByJenisOrder === 'function') {
            updateKeteranganByJenisOrder(checkedJenis.value);
        } else {
            displayKeteranganByKategori(id);
        }
    }

    // Tidak auto-open tab pertama: biarkan user memilih kategori secara eksplisit
    // (keterangan akan tampil hanya setelah user klik kategori)
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // Data dari Blade
        const allJenisSpek = @json($jenisSpek);
        const allJenisSpekDetail = @json($jenisSpekDetail);
        const allJenisOrders = @json($jenisOrders);

        // Ambil semua jenis_order inputs & labels
        const pakaianInputs = document.querySelectorAll('.js-pakaian-input');
        const pakaianLabels = document.querySelectorAll('.js-pakaian-label');

        // Event listener pada setiap jenis_order radio (untuk styling dan filter keterangan)
        pakaianInputs.forEach(input => {
            input.addEventListener('change', function () {
                // Reset semua label
                pakaianLabels.forEach(label => {
                    label.classList.remove('background_gray', 'text-white', 'border_gray');
                    label.classList.add('background_gray_young', 'text-gray-700', 'border_gray_young');
                });

                // Label aktif
                const activeLabel = document.querySelector(`label[for="${this.id}"]`);
                activeLabel.classList.remove('background_gray_young', 'text-gray-700', 'border_gray_young');
                activeLabel.classList.add('background_gray', 'text-white', 'border_gray');

                // Update keterangan berdasarkan jenis_order yang dipilih
                updateKeteranganByJenisOrder(this.value);
            });
        });

        // Array warna untuk kotak spek berdasarkan kategori
        const categoryColors = [
            'bg_green_category',      // Kategori 1: Hijau
            'bg_magenta_category',      // Kategori 2: Magenta
            'bg_cyan_category',      // Kategori 3: Cyan
            'bg_yellow_category',      // Kategori 4: Kuning
            'bg_pink_category',      // Kategori 5: Merah muda
            'bg_teal_category',      // Kategori 6: Teal
        ];

        // Function untuk ambil warna berdasarkan kategori_jenis_order
        window.getCategoryColor = function(kategoriId) {
            const colorIndex = (kategoriId - 1) % categoryColors.length;
            return categoryColors[colorIndex];
        };

        // Function untuk filter dan update keterangan berdasarkan jenis_order yang dipilih
        window.updateKeteranganByJenisOrder = function(jenisOrderId) {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            const currentKategoriTab = document.querySelector('.tab-btn.background_gray');
            if (!currentKategoriTab) return;

            const kategoriId = currentKategoriTab.getAttribute('onclick').match(/\d+/)[0];
            const boxColor = getCategoryColor(kategoriId);

            // Filter jenis_spek berdasarkan kategori
            const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

            // Filter jenis_spek_detail HANYA yang punya relasi dengan jenis_order yang dipilih
            const filteredDetails = allJenisSpekDetail.filter(detail => {
                // Cek apakah detail ada di kategori ini
                if (!jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek)) {
                    return false;
                }
                // Cek apakah detail punya relasi dengan jenis_order yang dipilih
                return detail.jenis_order && detail.jenis_order.some(jo => jo.id == jenisOrderId);
            });

            if (filteredDetails.length === 0) {
                container.style.display = 'none';
                if (keteranganInput) keteranganInput.value = '';
                return;
            }

            // Tampilkan container
            container.style.display = 'grid';
            container.innerHTML = '';

            // Kelompokkan detail berdasarkan id_jenis_spek
            const groupedBySpek = {};
            filteredDetails.forEach(detail => {
                const spekId = detail.id_jenis_spek;
                if (!groupedBySpek[spekId]) {
                    groupedBySpek[spekId] = [];
                }
                groupedBySpek[spekId].push(detail);
            });

            // Cari nama dan info jenis_spek dari allJenisSpek
            const spekMap = {};
            allJenisSpek.forEach(spek => {
                spekMap[spek.id] = spek;
            });

            // Generate kotak untuk setiap jenis_spek
            Object.keys(groupedBySpek).forEach((spekId) => {
                const spek = spekMap[spekId];
                const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
                const details = groupedBySpek[spekId];

                // Buat div kotak dengan warna berdasarkan kategori
                const box = document.createElement('div');
                box.dataset.spekId = spekId; // simpan id spek pada box untuk aggregasi
                box.className = `${boxColor} box_kategori_spek`;

                // Tambah title spek
                const title = document.createElement('p');
                title.className = 'box_kategori_spek_title';
                title.textContent = spekName;
                box.appendChild(title);

                // Buat grid untuk detail
                const detailGrid = document.createElement('div');
                detailGrid.className = 'box_kategori_spek_grid';

                // Tambahkan setiap detail ke grid
                    details.forEach((detail, index) => {
                    const radioId = `detail-${detail.id}`;

                    // Hidden radio input (grouped per jenis_spek so user can pick one per spek)
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.id = radioId;
                    // submit as speks[<jenis_spek_id>] => <jenis_spek_detail_id>
                    radio.name = `speks[${detail.id_jenis_spek}]`;
                    radio.value = detail.id; // store detail id for persistence
                    radio.dataset.label = detail.nama_jenis_spek_detail; // keep human label for keterangan
                    radio.className = 'hidden';

                    // Button/Label (visual)
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'box_kategori_spek_btn';
                    
                    // Konten button
                    if (detail.gambar) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + detail.gambar;
                        img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                        button.appendChild(img);
                    } else {
                        const noImg = document.createElement('div');
                        noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                        noImg.textContent = 'No Img';
                        button.appendChild(noImg);
                    }

                    const span = document.createElement('span');
                    span.className = 'box_kategori_spek_span';
                    span.textContent = detail.nama_jenis_spek_detail;
                    button.appendChild(span);

                    // Event listener pada button
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        // Toggle logic: if already checked, uncheck; otherwise check and remove ring from siblings
                        if (radio.checked) {
                            // Deselect
                            radio.checked = false;
                            button.classList.remove('ring-2', 'ring-green-700');
                        } else {
                            // Select
                            radio.checked = true;
                            // Update styling: remove ring from all buttons in this grid, add to current
                            detailGrid.querySelectorAll('button').forEach(btn => {
                                btn.classList.remove('ring-2', 'ring-green-700');
                            });
                            button.classList.add('ring-2', 'ring-green-700');
                        }

                        // Update aggregated keterangan per spek
                        updateKeteranganField();
                    });

                    detailGrid.appendChild(radio);
                    detailGrid.appendChild(button);

                    // Tidak auto-select: biarkan user memilih detail secara manual
                });

                box.appendChild(detailGrid);
                container.appendChild(box);
            });
        }

        // Kumpulkan pilihan detail dari setiap kotak (per jenis_spek) dan tulis ke input keterangan
        window.updateKeteranganField = function() {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            if (!container || !keteranganInput) return;

            const boxes = Array.from(container.querySelectorAll('div[data-spek-id]'));
            const pairs = [];

            boxes.forEach(box => {
                const spekId = box.dataset.spekId;
                const spekTitleEl = box.querySelector('p');
                const spekName = spekTitleEl ? spekTitleEl.textContent.trim() : `Spek ${spekId}`;
                const checked = box.querySelector(`input[name="speks[${spekId}]"]:checked`);
                if (checked) {
                    const label = checked.dataset.label || checked.value;
                    pairs.push(`${spekName}: ${label}`);
                }
            });

            keteranganInput.value = pairs.join(' | ');
        }

        // Function untuk menampilkan keterangan berdasarkan kategori (tanpa filter jenis_order)
        window.displayKeteranganByKategori = function(kategoriId) {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            const boxColor = getCategoryColor(kategoriId);

            // Filter jenis_spek berdasarkan kategori_jenis_order
            const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

            // Filter jenis_spek_detail yang ada di kategori ini
            const filteredDetails = allJenisSpekDetail.filter(detail => {
                return jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek);
            });

            if (filteredDetails.length === 0) {
                container.style.display = 'none';
                if (keteranganInput) keteranganInput.value = '';
                return;
            }

            // Tampilkan container
            container.style.display = 'grid';
            container.innerHTML = '';

            // Kelompokkan detail berdasarkan id_jenis_spek
            const groupedBySpek = {};
            filteredDetails.forEach(detail => {
                const spekId = detail.id_jenis_spek;
                if (!groupedBySpek[spekId]) {
                    groupedBySpek[spekId] = [];
                }
                groupedBySpek[spekId].push(detail);
            });

            // Cari nama dan info jenis_spek dari allJenisSpek
            const spekMap = {};
            allJenisSpek.forEach(spek => {
                spekMap[spek.id] = spek;
            });

            // Generate kotak untuk setiap jenis_spek
            Object.keys(groupedBySpek).forEach((spekId) => {
                const spek = spekMap[spekId];
                const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
                const details = groupedBySpek[spekId];

                // Buat div kotak dengan warna berdasarkan kategori
                const box = document.createElement('div');
                box.dataset.spekId = spekId; // simpan id spek juga di sini
                box.className = `${boxColor} box_kategori_spek`;

                // Tambah title spek
                const title = document.createElement('p');
                title.className = 'box_kategori_spek_title';
                title.textContent = spekName;
                box.appendChild(title);

                // Buat grid untuk detail
                const detailGrid = document.createElement('div');
                detailGrid.className = 'box_kategori_spek_grid';

                // Tambahkan setiap detail ke grid
                    details.forEach((detail, index) => {
                    const radioId = `detail-${detail.id}`;

                    // Hidden radio input (grouped per jenis_spek)
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.id = radioId;
                    radio.name = `speks[${detail.id_jenis_spek}]`;
                    radio.value = detail.id;
                    radio.dataset.label = detail.nama_jenis_spek_detail;
                    radio.className = 'hidden';

                    // Button/Label (visual)
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'box_kategori_spek_btn';
                    
                    // Konten button
                    if (detail.gambar) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + detail.gambar;
                        img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                        button.appendChild(img);
                    } else {
                        const noImg = document.createElement('div');
                        noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                        noImg.textContent = 'No Img';
                        button.appendChild(noImg);
                    }

                    const span = document.createElement('span');
                    span.className = 'box_kategori_spek_span';
                    span.textContent = detail.nama_jenis_spek_detail;
                    button.appendChild(span);

                    // Event listener pada button
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        // Toggle logic: if already checked, uncheck; otherwise check and remove ring from siblings
                        if (radio.checked) {
                            // Deselect
                            radio.checked = false;
                            button.classList.remove('ring-2', 'ring-green-700');
                        } else {
                            // Select
                            radio.checked = true;
                            // Update styling: remove ring from all buttons in this grid, add to current
                            detailGrid.querySelectorAll('button').forEach(btn => {
                                btn.classList.remove('ring-2', 'ring-green-700');
                            });
                            button.classList.add('ring-2', 'ring-green-700');
                        }

                        // Update aggregated keterangan per spek
                        updateKeteranganField();
                    });

                    detailGrid.appendChild(radio);
                    detailGrid.appendChild(button);

                    // Tidak auto-select: biarkan user memilih detail secara manual
                });

                box.appendChild(detailGrid);
                container.appendChild(box);
            });
        }

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // Ambil semua input & label
        const inputs = document.querySelectorAll('.js-pakaian-input');
        const labels = document.querySelectorAll('.js-pakaian-label');

        // Tambah event listener pada setiap radio
        inputs.forEach(input => {
            input.addEventListener('change', function () {

                // Reset semua label ke default
                labels.forEach(label => {
                    label.classList.remove('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
                    label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
                });

                // Label aktif
                const activeLabel = document.querySelector(`label[for="${this.id}"]`);
                activeLabel.classList.remove('background_gray_young', 'text-gray-700', 'border_gray_young');
                activeLabel.classList.add('background_gray', 'text-white', 'border_gray');
            });
        });

        // --- Inisialisasi awal: cek radio yang sudah checked ---
        const checkedInput = document.querySelector('.js-pakaian-input:checked');
        if (checkedInput) {
            const activeLabel = document.querySelector(`label[for="${checkedInput.id}"]`);
            activeLabel.classList.remove('background_gray_young', 'text-gray-700', 'border_gray_young');
            activeLabel.classList.add('background_gray', 'text-white', 'border_gray');
        }

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Modal untuk hapus order
        const deleteOrderModal = document.getElementById('deleteOrderModal');
        const confirmDeleteOrderBtn = document.getElementById('confirmDeleteOrderBtn');
        const cancelDeleteOrderBtn = document.getElementById('cancelDeleteOrderBtn');
        let currentOrderId = null;
        let currentOrderButton = null;

        // Event listener untuk tombol hapus order
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                
                currentOrderId = this.dataset.id;
                currentOrderButton = this;
                
                // Tampilkan modal konfirmasi
                deleteOrderModal.style.display = "block";
            });
        });

        // Event listener untuk tombol batal di modal
        if (cancelDeleteOrderBtn) {
            cancelDeleteOrderBtn.onclick = function() {
                deleteOrderModal.style.display = "none";
                currentOrderId = null;
                currentOrderButton = null;
            }
        }

        // Event listener untuk tombol hapus di modal
        if (confirmDeleteOrderBtn) {
            confirmDeleteOrderBtn.onclick = function() {
                if (!currentOrderId) return;
                
                // Tutup modal
                deleteOrderModal.style.display = "none";
                
                // Kirim request DELETE
                fetch(`/dashboard/orders/${currentOrderId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                })
                .then(res => {
                    if (!res.ok) throw new Error("Gagal menghapus");
                    return res.json();
                })
                .then(data => {
                    // Hapus baris tabel langsung
                    const row = currentOrderButton.closest("tr");
                    if (row) row.remove();

                    updateIterationNumbers();

                    // Update totals
                    const t = data.totals || {};
                    if (t.total_qty !== undefined) document.querySelector("#total_qty").innerText = t.total_qty;
                    if (t.total_hari !== undefined) document.querySelector("#total_hari").innerText = t.total_hari;
                    if (t.total_deadline !== undefined) document.querySelector("#total_deadline").innerText = t.total_deadline;
                    if (t.total_setting !== undefined) document.querySelector("#total_setting").innerText = t.total_setting;
                    if (t.total_print !== undefined) document.querySelector("#total_print").innerText = t.total_print;
                    if (t.total_sisa_print !== undefined) document.querySelector("#total_sisa_print").innerText = t.total_sisa_print;
                    if (t.total_press !== undefined) document.querySelector("#total_press").innerText = t.total_press;
                    if (t.total_sisa_press !== undefined) document.querySelector("#total_sisa_press").innerText = t.total_sisa_press;
                    if (t.total_cutting !== undefined) document.querySelector("#total_cutting").innerText = t.total_cutting;
                    if (t.total_sisa_cutting !== undefined) document.querySelector("#total_sisa_cutting").innerText = t.total_sisa_cutting;
                    if (t.total_jahit !== undefined) document.querySelector("#total_jahit").innerText = t.total_jahit;
                    if (t.total_sisa_jahit !== undefined) document.querySelector("#total_sisa_jahit").innerText = t.total_sisa_jahit;
                    if (t.total_finishing !== undefined) document.querySelector("#total_finishing").innerText = t.total_finishing;
                    if (t.total_sisa_finishing !== undefined) document.querySelector("#total_sisa_finishing").innerText = t.total_sisa_finishing;
                    if (t.total_packing !== undefined) document.querySelector("#total_packing").innerText = t.total_packing;
                    if (t.total_sisa_packing !== undefined) document.querySelector("#total_sisa_packing").innerText = t.total_sisa_packing;
                    if (t.total_hari !== undefined) document.querySelector("#est").innerText = t.total_hari;

                    // Tampilkan notifikasi sukses
                    showToast("Order berhasil dihapus!", 'success');
                })
                .catch(err => {
                    console.error("Detail error:", err);
                    // Tampilkan notifikasi error
                    showToast("Terjadi kesalahan saat menghapus order!", 'error');
                })
                .finally(() => {
                    currentOrderId = null;
                    currentOrderButton = null;
                });
            };
        }

        function updateIterationNumbers() {
            const rows = document.querySelectorAll('#orders-table-body tr');
            
            rows.forEach((row, index) => {
                const iterationCell = row.querySelector('.iteration-number');
                if (iterationCell) {
                    iterationCell.textContent = index + 1;
                }
            });
        }

        // Jika klik di luar modal, tutup modal
        window.onclick = function(event) {
            if (event.target == deleteOrderModal) {
                deleteOrderModal.style.display = "none";
                currentOrderId = null;
                currentOrderButton = null;
            }
        }

        // Fungsi toast sederhana
        function showToast(message, type = 'success', duration = 3000) {
            // Hapus toast sebelumnya jika ada (opsional)
            const existingToast = document.querySelector('.notification-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Buat elemen toast
            const toast = document.createElement('div');
            toast.className = `notification-toast toast-${type}`;
            
            // Icon berdasarkan type
            const icons = {
                success: '✓',
                error: '✗',
                warning: '⚠',
                info: 'ℹ'
            };
            
            toast.innerHTML = `
                <div class="toast-content">
                    <span class="toast-icon">${icons[type] || icons.success}</span>
                    <span class="toast-message">${message}</span>
                    <button class="toast-close" onclick="this.closest('.notification-toast').remove()">&times;</button>
                </div>
            `;
            
            // Tambahkan ke body
            document.body.appendChild(toast);
            
            // Tampilkan dengan animasi
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Auto remove setelah duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300); // Waktu untuk animasi keluar
            }, duration);
        }
        
        // Fungsi helper untuk tipe yang berbeda
        function showSuccess(message, duration = 3000) {
            showToast(message, 'success', duration);
        }
        
        function showError(message, duration = 4000) {
            showToast(message, 'error', duration);
        }
        
        function showWarning(message, duration = 3500) {
            showToast(message, 'warning', duration);
        }
        
        function showInfo(message, duration = 3000) {
            showToast(message, 'info', duration);
        }

        // Export ke global scope jika perlu
        window.showToast = showToast;
        window.showSuccess = showSuccess;
        window.showError = showError;
        window.showWarning = showWarning;
        window.showInfo = showInfo;
    });
</script>


<script>
    $(document).ready(function() {
        $('#select2-nama-job').select2({
            placeholder: "Ketik atau pilih nama job",
            tags: true, // PENTING: Mengizinkan input nilai baru
            allowClear: true,

            width: '100%'
        });

        $('#select2-nama-konsumen').select2({
            placeholder: "Ketik atau pilih nama konsumen",
            tags: true, // PENTING: Mengizinkan input nilai baru
            allowClear: true,

            width: '100%'
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var buttons = document.querySelectorAll('.btn-toggle-status');

    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function (e) {
            e.preventDefault();

            if (this.disabled) {
                showError('Tidak bisa tandai lunas, sisa packing masih ada!');
                return;
            }

            var orderId = this.getAttribute('data-id');
            var currentStatus = this.getAttribute('data-status') === 'true';

            toggleStatus(orderId, currentStatus);
        });
    }

});

function toggleStatus(orderId, currentStatus) {
    var newStatus = !currentStatus;

    fetch(`/dashboard/orders/${orderId}/status`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(function (res) {
        return res.json();
    })
    .then(function (data) {
        if (data.success) {
            updateUI(orderId, newStatus);
            showSuccess('Status berhasil diubah!');
        } else {
            showError(data.message || 'Gagal mengubah status');
        }
    })
    .catch(function () {
        showError('Terjadi kesalahan jaringan');
    });
}

function updateUI(orderId, newStatus) {

    var btn = document.querySelector('.btn-toggle-status[data-id="' + orderId + '"]');
    if (btn) {
        btn.innerHTML = newStatus ? 'Tandai Belum' : 'Tandai Lunas';
        btn.setAttribute('data-status', newStatus);

        btn.className = 'btn_change btn-toggle-status bg-blue-500 text-white';
    }

    var badge = document.querySelector('.status-badge[data-id="' + orderId + '"]');
    if (badge) {
        badge.innerHTML = newStatus ? 'Lunas' : 'Belum';
        badge.className = 'status-badge ' + (newStatus
            ? 'bg-green-100 text-green'
            : 'bg-red-100 text-red');
    }
}
</script>

<!-- SCRIPT OPEN/CLOSE -->
<script>
function openModal() {
    document.getElementById('popupModal').classList.add('active');
}
function closeModal() {
    document.getElementById('popupModal').classList.remove('active');
}
</script>

@endsection