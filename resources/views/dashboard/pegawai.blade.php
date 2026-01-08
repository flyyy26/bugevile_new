@extends('layouts.dashboard')

@section('title', 'Pegawai')

@section('content')
<div class="">
    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <button onclick="openModal()">Tambah Pegawai</button>
            <div class="dashboard_banner_select">
                <label for="monthFilter">Filter Bulan:</label>
                <select id="monthFilter" onchange="filterByMonth(this.value)">
                    <option value="all">Semua Bulan</option>
                    @foreach ($availableMonths as $m)
                        @php
                            $val = $m->year . '-' . str_pad($m->month, 2, '0', STR_PAD_LEFT);
                            $current = \Carbon\Carbon::now()->format('Y-m');
                        @endphp
                        <option value="{{ $val }}" {{ $val === $current ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate($m->year, $m->month, 1)->translatedFormat('F Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="pegawai_page_container">
        @php
            $tabColors = [
                'setting' => 'bg-gray-400',
                'print' => 'bg-red',
                'press' => 'bg-green-500',
                'cutting' => 'bg-yellow-500',
                'jahit' => 'bg-blue-500',
                'finishing' => 'bg-yellow-500',
                'packing' => 'bg-blue-500',
            ];
            $first = true;
        @endphp
        
        <!-- TABS -->
        <div class="pegawai_page_btn_layout">
            @foreach ($pegawaiByPosisi as $posisi => $listPegawai)
                @php
                    $posisiSlug = Str::slug($posisi);
                    $bgColor = $tabColors[strtolower($posisiSlug)] ?? 'bg-gray-200';
                @endphp

                <button 
                    class="tab-btn pegawai_page_btn
                        {{ $first ? 'active border-black text-black' : 'border-transparent text-black' }} {{ $bgColor }}"
                    data-tab="{{ $posisiSlug }}"
                    onclick="openTab('{{ $posisiSlug }}', this)">
                    {{ $posisi }}
                </button>

                @php $first = false; @endphp
            @endforeach
        </div>

        <!-- TAB CONTENT -->
        @php $first = true; @endphp
        <div id="tab-contents">
            @foreach ($pegawaiByPosisi as $posisi => $listPegawai)
                <div id="tab-{{ Str::slug($posisi) }}" class="tab-content {{ $first ? '' : 'hidden' }}">
                    @foreach ($listPegawai as $p)
                    <div class="pegawai_page_box" id="pegawai-{{ $p->id }}" data-pegawai-id="{{ $p->id }}">
                        <div class="pegawai_page_box_header">
                            <h2>{{ $p->nama }}</h2>

                            <div class="pegawai_page_box_header_btn">
                                @if(Auth::user()->role === 'admin')
                                    <button onclick="showDeletePegawaiModal({{ $p->id }}, '{{ $p->nama }}')" 
                                            class="bg-red text-black pegawai_page_btn">
                                        Hapus
                                    </button>
                                @endif

                                <a href="{{ route('pegawai.show', $p->id) }}">
                                    <button class="bg-gray-800 text-white pegawai_page_btn">
                                        Lihat Histori
                                    </button>
                                </a>

                                <button onclick='openEditModal(@json($p))'
                                    class="bg-green-500 text-black pegawai_page_btn">
                                    Edit Pegawai
                                </button>

                                <button onclick="openCasbonModal({{ $p->id }}, '{{ $p->nama }}')"
                                    class="bg-blue-500 text-black pegawai_page_btn">
                                    + Casbon
                                </button>

                                <button onclick="openCasbonHistoryModal({{ $p->id }}, '{{ $p->nama }}')"
                                    class="bg-purple-500 text-black pegawai_page_btn">
                                    Lihat Casbon
                                </button>
                            </div>
                        </div>

                        @php
                            $rekapData = $rekapPerPegawai[$p->id] ?? null;
                            
                            if ($rekapData) {
                                $rekapJenis = $rekapData->rekapJenis ?? collect();
                                $rowspan = max($rekapJenis->count(), 1); // Minimal 1 untuk rowspan
                                
                                $totalKeseluruhan = $rekapData->totalKeseluruhan ?? 0;
                                $totalCasbon = $rekapData->totalCasbon ?? 0;
                                $totalSisa = $rekapData->totalSisa ?? 0;
                            } else {
                                $rekapJenis = collect();
                                $rowspan = 1;
                                $totalKeseluruhan = 0;
                                $totalCasbon = 0;
                                $totalSisa = 0;
                            }
                            
                            $rowIndex = 0;
                            $hasData = $rekapJenis->count() > 0;
                        @endphp
                        <div class="orders_table_container orders_table_container_small orders_table_container_big">
                            <table id="table-{{ $p->id }}" data-total-sisa="{{ $totalSisa }}">
                                <thead>
                                    <tr class="bg-gray-800 text-white">
                                        <th class="border">Jenis Pekerjaan</th>
                                        <th class="border"><div class="text-center">Total Qty</div></th>
                                        <th class="border"><div class="text-center">Total Lembar</div></th>
                                        <th class="border"><div class="text-center">Total Harga</div></th>
                                        <th class="border"><div class="text-center">Total Keseluruhan</div></th>
                                        <th class="border"><div class="text-center">Casbon</div></th>
                                        <th class="border"><div class="text-center">Sisa</div></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-{{ $p->id }}">
                                    @if ($hasData)
                                        @foreach ($rekapJenis as $r)
                                            @php $rowIndex++; @endphp
                                            <tr>
                                                <td class="border">{{ $r->jenis_pekerjaan }}</td>
                                                <td class="border"><div class="text-center">{{ $r->total_qty }}</div></td>
                                                <td class="border"><div class="text-center">
                                                    @if (in_array($r->jenis_pekerjaan, ['Print', 'Press']))
                                                        {{ $r->total_lembar ?? 0 }}
                                                    @else
                                                        -
                                                    @endif
                                                </div></td>
                                                <td class="border font-bold"><div class="text-center">{{ number_format($r->total) }}</div></td>

                                                @if ($rowIndex === 1)
                                                    <td class="border font-bold" rowspan="{{ $rowspan }}"><div class="text-center">
                                                        <p id="total-keseluruhan-{{ $p->id }}">{{ number_format($totalKeseluruhan) }}</p>
                                                    </div></td>
                                                    <td class="border font-bold" rowspan="{{ $rowspan }}"><div class="text-center">
                                                        <p id="total-casbon-{{ $p->id }}">{{ number_format($totalCasbon) }}</p>
                                                    </div></td>
                                                    <td class="border font-bold" rowspan="{{ $rowspan }}"><div class="text-center">
                                                        <p id="total-sisa-{{ $p->id }}">{{ number_format($totalSisa) }}</p>
                                                    </div></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- Tampilkan baris kosong dengan tetap menampilkan total --}}
                                        <tr>
                                            <td class="border">-</td>
                                            <td class="border"><div class="text-center">0</div></td>
                                            <td class="border"><div class="text-center">-</div></td>
                                            <td class="border font-bold"><div class="text-center">0</div></td>
                                            <td class="border font-bold"><div class="text-center">
                                                <span id="total-keseluruhan-{{ $p->id }}">{{ number_format($totalKeseluruhan) }}</span>
                                            </div></td>
                                            <td class="border font-bold"><div class="text-center">
                                                <span id="total-casbon-{{ $p->id }}">{{ number_format($totalCasbon) }}</span>
                                            </div></td>
                                            <td class="border font-bold"><div class="text-center">
                                                <span id="total-sisa-{{ $p->id }}">{{ number_format($totalSisa) }}</span>
                                            </div></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
                @php $first = false; @endphp
            @endforeach
        </div>
    </div>
</div>

<!-- Modal for Casbon History -->
<div id="modalCasbonHistory" class="dashboard_popup_order popup_custom">
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Riwayat Casbon: <span id="casbonHistoryPegawaiName"></span></h2>
            <button onclick="closeCasbonHistoryModal()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <div class="casbon-history-container" style="max-height: 400px; overflow-y: auto;">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-2 py-2 text-center">Tanggal</th>
                        <th class="px-2 py-2 text-center">Jumlah</th>
                        <th class="px-2 py-2 text-center">Keterangan</th>
                        @if(Auth::user()->role === 'admin')
                        <th class="px-2 py-2 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="casbonHistoryTableBody">
                    <!-- Data akan diisi via JavaScript -->
                </tbody>
            </table>
            <div id="casbonHistoryEmpty" class="text-center py-4 text-gray-500 hidden">
                Tidak ada riwayat casbon
            </div>
        </div>

        <div class="mt-4">
            <h3 class="font-bold mb-2">Total Casbon: <span id="totalCasbonAmount" class="text-red">0</span></h3>
        </div>

        <div class="dashboard_popup_order_btn">
            <button type="button" onclick="closeCasbonHistoryModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deletePegawaiForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="pegawai_id" id="deletePegawaiId">
</form>

<!-- Modal for Casbon -->
<div id="modalCasbon" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Casbon</h2>
            <button onclick="closeCasbonModal()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <form id="formCasbon" method="POST" action="{{ route('pegawai.casbon.store') }}">
            @csrf
            <input type="hidden" name="pegawai_id" id="casbonPegawaiId">

            <div class="form_field_normal">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="input_form" required>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="input_form">
            </div>

            <div class="form_field_normal">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="input_form" required>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeCasbonModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Add Pegawai -->
<div id="popupModal" class="dashboard_popup_order popup_custom">
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Pegawai Baru</h2>
            <button onclick="closeModal()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <form id="formTambahPegawai" action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <div class="form_field_normal">
                <label>Nama</label>
                <input type="text" 
                    name="nama"
                    class="input_form"
                    placeholder="Masukkan nama pegawai"
                    required>
            </div>

            <div class="form_field_normal">
                <label>Posisi</label>
                <select
                    name="posisi"
                    class="input_form"
                    required>
                    <option value="" selected disabled>Pilih Posisi</option>
                    <option value="Setting">Setting</option>
                    <option value="Print">Print</option>
                    <option value="Press">Press</option>
                    <option value="Cutting">Cutting</option>
                    <option value="Jahit">Jahit</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Packing">Packing</option>
                </select>
            </div>

            <div class="form_field_normal">
                <label>Rekening</label>
                <input type="text" 
                    name="rekening"
                    class="input_form"
                    placeholder="Masukkan nomor rekening">
            </div>

            <div class="form_field_normal">
                <label>Alamat</label>
                <textarea
                    name="alamat"
                    class="input_form"
                    placeholder="Masukkan alamat"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Edit Pegawai -->
<div id="editModal" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Edit Pegawai</h2>
            <button onclick="closeEditModal()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <form id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">

            <div class="form_field_normal">
                <label>Nama</label>
                <input type="text" 
                    id="edit_nama" name="nama"
                    class="input_form"
                    required>
            </div>

            <div class="form_field_normal">
                <label>Posisi</label>
                <select
                    id="edit_posisi" name="posisi"
                    class="input_form"
                    required>
                    <option value="">Pilih Posisi</option>
                    <option value="Setting">Setting</option>
                    <option value="Print">Print</option>
                    <option value="Press">Press</option>
                    <option value="Cutting">Cutting</option>
                    <option value="Jahit">Jahit</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Packing">Packing</option>
                </select>
            </div>

            <div class="form_field_normal">
                <label>Rekening</label>
                <input type="text" 
                    id="edit_rekening" name="rekening"
                    class="input_form"
                    placeholder="Masukkan nomor rekening">
            </div>

            <div class="form_field_normal">
                <label>Alamat</label>
                <textarea
                    id="edit_alamat" name="alamat"
                    class="input_form"
                    placeholder="Masukkan alamat"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeEditModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Pegawai Modal -->
<div id="deletePegawaiModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus pegawai "<span id="pegawaiNameToDelete"></span>"?
            Semua riwayat pekerjaan terkait juga akan terhapus.
        </p>
        <input type="hidden" id="pegawaiIdToDelete">
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelDeletePegawaiBtn">Batal</button>
            <button id="confirmDeletePegawaiBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
/* Modal styling */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-title {
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: bold;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.btn-cancel {
    background-color: #6b7280;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}

.btn-confirm {
    background-color: #ef4444;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}

.btn-cancel:hover {
    background-color: #4b5563;
}

.btn-confirm:hover {
    background-color: #dc2626;
}

/* Toast Notification */
.toast {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
    min-width: 300px;
}

.toast-success {
    background-color: #10b981;
    color: white;
}

.toast-error {
    background-color: #ef4444;
    color: white;
}

.toast-info {
    background-color: #3b82f6;
    color: white;
}

.toast-icon {
    font-weight: bold;
    font-size: 18px;
}

.toast-message {
    flex: 1;
    font-size: 14px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

/* Tab Styling */
.tab-content {
    display: block;
}

.tab-content.hidden {
    display: none;
}

.tab-btn.active {
    border-bottom: 3px solid #000;
    font-weight: bold;
}

button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.popup_custom.active {
    display: flex !important;
}
.casbon-history-container {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 10px;
}

.casbon-history-container table {
    width: 100%;
    border-collapse: collapse;
}

.casbon-history-container th {
    background-color: #1f2937;
    color: white;
    padding: 8px 12px;
    font-size: 12px;
}

.casbon-history-container td {
    padding: 8px 12px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 12px;
}

.casbon-history-container tr:hover {
    background-color: #f9fafb;
}

.bg-purple-500 {
    background-color: #8b5cf6;
}
.bg-purple-500:hover {
    background-color: #7c3aed;
}
</style>


<script>
    function openCasbonHistoryModal(pegawaiId, pegawaiNama) {
        document.getElementById('casbonHistoryPegawaiName').textContent = pegawaiNama;
        document.getElementById('modalCasbonHistory').classList.add('active');
        
        // Reset konten
        document.getElementById('casbonHistoryTableBody').innerHTML = '';
        document.getElementById('totalCasbonAmount').textContent = '0';
        document.getElementById('casbonHistoryEmpty').classList.add('hidden');
        
        // Tampilkan loading
        showToast('Memuat data casbon...', 'info');
        
        // Load data casbon via AJAX
        loadCasbonHistory(pegawaiId);
    }

    function closeCasbonHistoryModal() {
        document.getElementById('modalCasbonHistory').classList.remove('active');
    }

    // Fungsi untuk memuat data casbon
    function loadCasbonHistory(pegawaiId) {
        fetch(`/dashboard/pegawai/${pegawaiId}/casbon`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCasbonHistory(data.casbons, data.total_casbon);
            } else {
                showToast('Gagal memuat data casbon', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat memuat data', 'error');
        });
    }

    // Fungsi untuk merender data casbon
    function renderCasbonHistory(casbons, totalCasbon) {
        const tableBody = document.getElementById('casbonHistoryTableBody');
        const emptyMessage = document.getElementById('casbonHistoryEmpty');
        
        if (!casbons || casbons.length === 0) {
            tableBody.innerHTML = '';
            emptyMessage.classList.remove('hidden');
            document.getElementById('totalCasbonAmount').textContent = formatNumber(0);
            return;
        }
        
        emptyMessage.classList.add('hidden');
        
        let html = '';
        let total = 0;
        
        casbons.forEach(casbon => {
            total += parseFloat(casbon.jumlah);
            
            html += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-2 py-2 text-center">${formatDate(casbon.tanggal)}</td>
                    <td class="px-2 py-2 text-center font-bold">${formatNumber(casbon.jumlah)}</td>
                    <td class="px-2 py-2 text-center">${casbon.keterangan || '-'}</td>
                    @if(Auth::user()->role === 'admin')
                    <td class="px-2 py-2 text-center">
                        <div class="btn_table_action">
                            <button onclick="deleteCasbon(${casbon.id}, ${casbon.pegawai_id})" class="bg-red">
                                Hapus
                            </button>
                        </div>
                    </td>
                    @endif
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        document.getElementById('totalCasbonAmount').textContent = formatNumber(totalCasbon || total);
    }

    // Fungsi untuk menghapus casbon
    function deleteCasbon(casbonId, pegawaiId) {
        
        showToast('Menghapus casbon...', 'info');
        
        fetch(`/dashboard/pegawai/casbon/${casbonId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' 
            },
            body: JSON.stringify({
                _method: 'DELETE' // Method spoofing
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Casbon berhasil dihapus', 'success');
                // Reload data casbon
                loadCasbonHistory(pegawaiId);
                // Update total di tabel utama
                updateCasbonTotal(pegawaiId, data.total_casbon, data.total_sisa);
            } else {
                showToast(data.message || 'Gagal menghapus casbon', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus casbon', 'error');
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
</script>
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                 document.querySelector('input[name="_token"]')?.value ||
                 '{{ csrf_token() }}';

// Tab Management
let currentTab = '';
function openTab(tabId, element) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.remove('border-black');
        btn.classList.add('border-transparent');
    });
    
    // Add active class to clicked tab
    element.classList.add('active');
    element.classList.add('border-black');
    element.classList.remove('border-transparent');
    
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show selected tab content
    const tabContent = document.getElementById(`tab-${tabId}`);
    if (tabContent) {
        tabContent.classList.remove('hidden');
    }
    
    currentTab = tabId;
}

// Toast Notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;
    
    // Remove previous toasts
    const existingToasts = toastContainer.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let icon = '✓';
    if (type === 'error') icon = '✗';
    if (type === 'info') icon = '⏳';
    
    toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <span class="toast-message">${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) toast.remove();
    }, 3000);
}

// Modal Functions
function openModal() {
    document.getElementById('popupModal').classList.add('active');
}

function closeModal() {
    document.getElementById('popupModal').classList.remove('active');
    document.getElementById('formTambahPegawai').reset();
}

function openEditModal(data) {
    document.getElementById('editModal').classList.add('active');
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_posisi').value = data.posisi || '';
    document.getElementById('edit_rekening').value = data.rekening || '';
    document.getElementById('edit_alamat').value = data.alamat || '';
    
    // Reset submit button text
    const submitBtn = document.querySelector('#editForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    // Reset submit button state
    const submitBtn = document.querySelector('#editForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }
}

function openCasbonModal(id, nama) {
    document.getElementById('casbonPegawaiId').value = id;
    document.getElementById('modalCasbon').classList.add('active');
    document.getElementById('formCasbon').reset();
    
    // Reset submit button state
    const submitBtn = document.querySelector('#formCasbon button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }
}

function closeCasbonModal() {
    document.getElementById('modalCasbon').classList.remove('active');
    // Reset submit button state
    const submitBtn = document.querySelector('#formCasbon button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }
}

// Delete Pegawai Modal
const deletePegawaiModal = document.getElementById('deletePegawaiModal');
const confirmDeletePegawaiBtn = document.getElementById('confirmDeletePegawaiBtn');
const cancelDeletePegawaiBtn = document.getElementById('cancelDeletePegawaiBtn');

function showDeletePegawaiModal(pegawaiId, pegawaiNama) {
    document.getElementById('pegawaiIdToDelete').value = pegawaiId;
    document.getElementById('pegawaiNameToDelete').textContent = pegawaiNama;
    deletePegawaiModal.style.display = "block";
}

if (cancelDeletePegawaiBtn) {
    cancelDeletePegawaiBtn.onclick = function() {
        deletePegawaiModal.style.display = "none";
    }
}

if (confirmDeletePegawaiBtn) {
    confirmDeletePegawaiBtn.onclick = function() {
        const pegawaiId = document.getElementById('pegawaiIdToDelete').value;
        const pegawaiNama = document.getElementById('pegawaiNameToDelete').textContent;
        
        deletePegawaiModal.style.display = "none";
        showToast('Menghapus pegawai ' + pegawaiNama + '...', 'info');
        
        fetch(`/dashboard/pegawai/${pegawaiId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE' // Method spoofing
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove pegawai element
                const pegawaiElement = document.getElementById(`pegawai-${pegawaiId}`);
                if (pegawaiElement) {
                    pegawaiElement.remove();
                    showToast('Pegawai berhasil dihapus', 'success');
                    
                    // Check if the tab is now empty
                    const tabContent = pegawaiElement.closest('.tab-content');
                    if (tabContent && tabContent.querySelectorAll('.pegawai_page_box').length === 0) {
                        // Hide empty tab
                        tabContent.classList.add('hidden');
                        
                        // Also hide the tab button if needed
                        const tabSlug = tabContent.id.replace('tab-', '');
                        const tabBtn = document.querySelector(`.tab-btn[data-tab="${tabSlug}"]`);
                        if (tabBtn) {
                            tabBtn.style.display = 'none';
                        }
                        
                        // Switch to first visible tab
                        const firstVisibleTab = document.querySelector('.tab-btn:not([style*="display: none"])');
                        if (firstVisibleTab) {
                            const tabId = firstVisibleTab.getAttribute('data-tab');
                            openTab(tabId, firstVisibleTab);
                        }
                    }
                } else {
                    window.location.reload();
                }
            } else {
                showToast(data.message || 'Gagal menghapus pegawai', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus pegawai', 'error');
        });
    };
}

// Filter by Month
function filterByMonth(month) {
    const params = new URLSearchParams(window.location.search);
    if (month === 'all') {
        params.delete('month');
    } else {
        params.set('month', month);
    }
    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

// Helper Functions
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num || 0);
}

// Fungsi utama untuk membuat element pegawai
function createPegawaiElement(pegawai, rekapData = null, returnElementOnly = false, targetTabContent = null) {
    const rekapJenis = rekapData?.rekapJenis || [];
    const rowspan = Math.max(rekapJenis.length, 1);
    
    let tableRows = '';
    if (rekapJenis.length > 0) {
        rekapJenis.forEach((r, index) => {
            tableRows += `
                <tr>
                    <td class="border px-3 py-2">${r.jenis_pekerjaan || '-'}</td>
                    <td class="border text-center px-3 py-2">${r.total_qty || 0}</td>
                    <td class="border text-center px-3 py-2">${['print', 'press'].includes((r.jenis_pekerjaan || '').toLowerCase()) ? (r.total_lembar || 0) : '-'}</td>
                    <td class="border text-center px-3 py-2 font-bold">${formatNumber(r.total || 0)}</td>
                    ${index === 0 ? `
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">
                        <span id="total-keseluruhan-${pegawai.id}">${formatNumber(rekapData?.totalKeseluruhan || 0)}</span>
                    </td>
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">
                        <span id="total-casbon-${pegawai.id}">${formatNumber(rekapData?.totalCasbon || 0)}</span>
                    </td>
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">
                        <span id="total-sisa-${pegawai.id}">${formatNumber(rekapData?.totalSisa || 0)}</span>
                    </td>
                    ` : ''}
                </tr>
            `;
        });
    } else {
        tableRows = `
            <tr>
                <td class="border px-3 py-2">-</td>
                <td class="border text-center px-3 py-2">0</td>
                <td class="border text-center px-3 py-2">-</td>
                <td class="border text-center px-3 py-2 font-bold">0</td>
                <td class="border text-center px-3 py-2 font-bold" rowspan="1">
                    <span id="total-keseluruhan-${pegawai.id}">0</span>
                </td>
                <td class="border text-center px-3 py-2 font-bold" rowspan="1">
                    <span id="total-casbon-${pegawai.id}">0</span>
                </td>
                <td class="border text-center px-3 py-2 font-bold" rowspan="1">
                    <span id="total-sisa-${pegawai.id}">0</span>
                </td>
            </tr>
        `;
    }
    
    const pegawaiElement = document.createElement('div');
    pegawaiElement.className = 'pegawai_page_box';
    pegawaiElement.id = `pegawai-${pegawai.id}`;
    pegawaiElement.setAttribute('data-pegawai-id', pegawai.id);
    
    // Gunakan nama yang aman untuk string JavaScript
    const safeNama = pegawai.nama.replace(/'/g, "\\'").replace(/"/g, '\\"');
    
    pegawaiElement.innerHTML = `
        <div class="pegawai_page_box_header">
            <h2>${pegawai.nama}</h2>
            <div class="pegawai_page_box_header_btn">
                <button onclick="showDeletePegawaiModal(${pegawai.id}, '${safeNama}')" 
                        class="bg-red text-black pegawai_page_btn">
                    Hapus
                </button>
                <a href="/dashboard/pegawai/${pegawai.id}">
                    <button class="bg-gray-800 text-white pegawai_page_btn">
                        Lihat Histori
                    </button>
                </a>
                <button onclick='openEditModal(${JSON.stringify(pegawai).replace(/'/g, "\\'")})'
                    class="bg-green-500 text-black pegawai_page_btn">
                    Edit Pegawai
                </button>
                <button onclick="openCasbonModal(${pegawai.id}, '${safeNama}')"
                    class="bg-blue-500 text-black pegawai_page_btn">
                    + Casbon
                </button>
                <button onclick="openCasbonHistoryModal({{ $p->id }}, '{{ $p->nama }}')"
                    class="bg-purple-500 text-black pegawai_page_btn">
                    Lihat Casbon
                </button>
            </div>
        </div>
        <div class="orders_table_container orders_table_container_big">
            <table id="table-${pegawai.id}" data-total-sisa="${rekapData?.totalSisa || 0}">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="border px-4 py-2 text-center">Jenis Pekerjaan</th>
                        <th class="border px-4 py-2 text-center">Total Qty</th>
                        <th class="border px-4 py-2 text-center">Total Lembar</th>
                        <th class="border px-4 py-2 text-center">Total Harga</th>
                        <th class="border px-4 py-2 text-center">Total Keseluruhan</th>
                        <th class="border px-4 py-2 text-center">Casbon</th>
                        <th class="border px-4 py-2 text-center">Sisa</th>
                    </tr>
                </thead>
                <tbody id="tbody-${pegawai.id}">
                    ${tableRows}
                </tbody>
            </table>
        </div>
    `;
    
    // Jika hanya ingin return element saja
    if (returnElementOnly) {
        return pegawaiElement;
    }
    
    // Jika ada targetTabContent, tambahkan ke situ
    if (targetTabContent) {
        targetTabContent.appendChild(pegawaiElement);
        return pegawaiElement;
    }
    
    // Cari atau buat tab sesuai posisi
    const posisiSlug = pegawai.posisi.toLowerCase();
    const tabId = `tab-${posisiSlug}`;
    let tabContent = document.getElementById(tabId);
    let tabBtn = document.querySelector(`.tab-btn[data-tab="${posisiSlug}"]`);
    
    // Buat tab button jika belum ada
    if (!tabBtn) {
        tabBtn = document.createElement('button');
        tabBtn.className = 'tab-btn pegawai_page_btn border-transparent text-black bg-gray-200';
        tabBtn.setAttribute('data-tab', posisiSlug);
        tabBtn.textContent = pegawai.posisi;
        tabBtn.onclick = () => openTab(posisiSlug, tabBtn);
        document.querySelector('.pegawai_page_btn_layout').appendChild(tabBtn);
    }
    
    // Buat tab content jika belum ada
    if (!tabContent) {
        tabContent = document.createElement('div');
        tabContent.id = tabId;
        tabContent.className = 'tab-content';
        document.getElementById('tab-contents').appendChild(tabContent);
    }
    
    // Tambahkan pegawai ke tab content
    tabContent.appendChild(pegawaiElement);
    
    return pegawaiElement;
}

function updatePegawaiElement(pegawai) {
    const pegawaiElement = document.getElementById(`pegawai-${pegawai.id}`);
    if (!pegawaiElement) return;
    
    // Update name in header
    const nameElement = pegawaiElement.querySelector('h2');
    if (nameElement) nameElement.textContent = pegawai.nama;
    
    // Update buttons dengan data baru
    const safeNama = pegawai.nama.replace(/'/g, "\\'").replace(/"/g, '\\"');
    
    const deleteBtn = pegawaiElement.querySelector('button.bg-red');
    if (deleteBtn) {
        deleteBtn.setAttribute('onclick', `showDeletePegawaiModal(${pegawai.id}, '${safeNama}')`);
    }
    
    const editBtn = pegawaiElement.querySelector('button.bg-green-500');
    if (editBtn) {
        editBtn.setAttribute('onclick', `openEditModal(${JSON.stringify(pegawai).replace(/'/g, "\\'")})`);
    }
    
    const casbonBtn = pegawaiElement.querySelector('button.bg-blue-500');
    if (casbonBtn) {
        casbonBtn.setAttribute('onclick', `openCasbonModal(${pegawai.id}, '${safeNama}')`);
    }
}

function updateCasbonTotal(pegawaiId, totalCasbon, totalSisa) {
    const totalCasbonElement = document.getElementById(`total-casbon-${pegawaiId}`);
    const totalSisaElement = document.getElementById(`total-sisa-${pegawaiId}`);

    // Pastikan selalu angka
    totalCasbon = parseFloat(totalCasbon) || 0;
    totalSisa = parseFloat(totalSisa) || 0;

    if (totalCasbonElement) totalCasbonElement.textContent = formatNumber(totalCasbon);
    if (totalSisaElement) totalSisaElement.textContent = formatNumber(totalSisa);
}

// Form Submissions
document.addEventListener('DOMContentLoaded', function() {
    // Add Pegawai Form
    const formTambahPegawai = document.getElementById('formTambahPegawai');
    if (formTambahPegawai) {
        formTambahPegawai.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                
                if (data.success === true || data.message || data.pegawai) {
                    closeModal();
                    showToast(data.message || 'Pegawai berhasil ditambahkan', 'success');
                    
                    // Cek apakah pegawai sudah ada
                    const existingPegawai = document.getElementById(`pegawai-${data.pegawai.id}`);
                    if (!existingPegawai) {
                        // Buat element pegawai dan tambahkan ke tab yang sesuai posisi
                        createPegawaiElement(data.pegawai, data.rekapData || { 
                            rekapJenis: [], 
                            totalKeseluruhan: 0, 
                            totalCasbon: 0, 
                            totalSisa: 0 
                        });
                        
                        // Otomatis pindah ke tab posisi pegawai yang baru ditambahkan
                        const posisiSlug = data.pegawai.posisi.toLowerCase();
                        const tabBtn = document.querySelector(`.tab-btn[data-tab="${posisiSlug}"]`);
                        if (tabBtn) {
                            openTab(posisiSlug, tabBtn);
                        }
                    }
                    
                    // Reset form
                    this.reset();
                } else {
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showToast('Validasi error: ' + errorMessages, 'error');
                    } else {
                        showToast('Gagal menambahkan pegawai: ' + (data.message || 'Unknown error'), 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                showToast('Gagal menambahkan pegawai', 'error');
            });
        });
    }
    
    // Edit Pegawai Form
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const pegawaiId = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengupdate...';
            
            // Using FormData to include method spoofing
            formData.append('_method', 'PUT');
            
            fetch(`/dashboard/pegawai/${pegawaiId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Always reset button state
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
                
                if (data.success) {
                    closeEditModal();
                    showToast('Pegawai berhasil diperbarui', 'success');
                    
                    // Update existing pegawai element
                    updatePegawaiElement(data.pegawai);
                } else {
                    showToast(data.message || 'Gagal mengupdate pegawai', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
                showToast('Terjadi kesalahan', 'error');
            });
        });
    }
    
    // Casbon Form
    const formCasbon = document.getElementById('formCasbon');
    if (formCasbon) {
        formCasbon.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;

                if (data.success) {
                    closeCasbonModal();
                    showToast('Casbon berhasil ditambahkan', 'success');
                    updateCasbonTotal(data.pegawai_id, data.total_casbon, data.total_sisa);
                } else {
                    showToast(data.message || 'Gagal menambahkan casbon', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                showToast('Terjadi kesalahan', 'error');
            });
        });
    }
    
    // Initialize first tab as active
    const firstTabBtn = document.querySelector('.tab-btn');
    if (firstTabBtn) {
        const tabId = firstTabBtn.getAttribute('data-tab');
        openTab(tabId, firstTabBtn);
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('popup_custom')) {
            closeModal();
            closeEditModal();
            closeCasbonModal();
        }
        if (event.target == deletePegawaiModal) {
            deletePegawaiModal.style.display = "none";
        }
    });
    
    // Show session messages
    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif
});
</script>
@endsection