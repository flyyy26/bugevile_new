@extends('layouts.dashboard')

@section('title', 'Pegawai')

@section('content')
<div class="">
<div class="px-6 pt-6 flex flex-col justify-between items-start">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">
    <div class="flex items-center justify-start z-20 relative mt-28">
        <div class="px-6 flex items-center justify-end">
            <label for="monthFilter" class="mr-2 text-sm font-medium text-white">Filter Bulan:</label>
            <select id="monthFilter" class="border rounded px-2 py-1 text-sm bg-gray-50">
                <option value="all">Semua Bulan</option>

                @foreach ($availableMonths as $m)
                    <option value="{{ $m->year }}-{{ str_pad($m->month, 2, '0', STR_PAD_LEFT) }}">
                        {{ \Carbon\Carbon::createFromDate($m->year, $m->month, 1)->translatedFormat('F Y') }}
                    </option>
                @endforeach
            </select>
        </div>
        <button onclick="openModal()" class="bg-white text-gray-800 px-3 py-1 rounded text-sm shadow hover:bg-gray-700">Tambah Pegawai</button>
    </div>
</div>
@php
$first = true;
@endphp

<div class="px-6 pt-3 z-20 relative bg-white mt-3">
    @php $first = true; @endphp
    @php
        $tabColors = [
            'setting' => 'bg-gray-500',
            'print' => 'bg-red-500',
            'press' => 'bg-green-500',
            'cutting' => 'bg-yellow-500',
            'jahit' => 'bg-blue-500',
            'finishing' => 'bg-yellow-500',
            'packing' => 'bg-blue-500',
        ];
        $first = true;
    @endphp
        <div class="flex gap-3 border-b mb-6">
            @foreach ($pegawaiByPosisi as $posisi => $listPegawai)
                @php
                    $posisiSlug = Str::slug($posisi);
                    $bgColor = $tabColors[strtolower($posisiSlug)] ?? 'bg-gray-200'; // default jika tidak ada
                @endphp

                <button 
                    class="tab-btn px-3 py-1 font-semibold border rounded-lg text-sm 
                        {{ $first ? 'border-black text-black' : 'border-transparent text-black' }} {{ $bgColor }}"
                    onclick="openTab('{{ $posisiSlug }}', this)">
                    {{ $posisi }}
                </button>

                @php $first = false; @endphp
            @endforeach
        </div>

@php $first = true; @endphp
@foreach ($pegawaiByPosisi as $posisi => $listPegawai)
    <div id="tab-{{ Str::slug($posisi) }}" class="{{ $first ? '' : 'hidden' }}">
        
        @foreach ($listPegawai as $p)
        <div class="border-b border-gray-500 mb-4 pb-0">
            <div class="flex justify-between items-center mb-2">
                <h2 class="font-bold text-xl">{{ $p->nama }}</h2>

                <div class="flex justify-end gap-3 items-center">
                    <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $p->nama }}?');">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Hapus</button>
                    </form>

                    <button onclick="openPegawaiHistory({{ $p->id }}, '{{ $p->nama }}', this)"
                        class="bg-gray-800 text-white px-3 py-1 rounded text-sm">
                        Lihat Histori
                    </button>

                    <button onclick='openEditModal(@json($p))'
                        class="bg-green-400 text-white px-3 py-1 rounded text-sm hover:bg-green-500">
                        Edit Pegawai
                    </button>

                    <button onclick="openCasbonModal({{ $p->id }}, '{{ $p->nama }}')"
                        class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                        + Casbon
                    </button>
                </div>
            </div>

            @php
                $rekapData = $rekapPerPegawai[$p->id];
                $rekapJenis = $rekapData->rekapJenis;
                $rowspan = $rekapJenis->count();
                $rowIndex = 0;

                $totalKeseluruhan = $rekapData->totalKeseluruhan;
                $totalCasbon = $rekapData->totalCasbon;
                $totalSisa = $rekapData->totalSisa;
            @endphp

            <table id="table-{{ $p->id }}" class="min-w-full border border-gray-300 text-sm mb-6" data-total-sisa="{{ $totalSisa }}">
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
                <tbody id="tbody-{{ $p->id }}">
                    @foreach ($rekapJenis as $r)
                        @php $rowIndex++; @endphp
                        <tr>
                            <td class="border px-3 py-2">{{ $r->jenis_pekerjaan }}</td>
                            <td class="border text-center px-3 py-2">{{ $r->total_qty }}</td>
                            <td class="border text-center px-3 py-2">
                                @if (in_array($r->jenis_pekerjaan, ['Print', 'Press']))
                                    {{ $r->total_lembar ?? 0 }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border text-center px-3 py-2 font-bold">{{ number_format($r->total) }}</td>

                            @if ($rowIndex === 1)
                                <td class="border text-center px-3 py-2 font-bold" rowspan="{{ $rowspan }}">
                                    {{ number_format($totalKeseluruhan) }}
                                </td>
                                <td class="border text-center px-3 py-2 font-bold" rowspan="{{ $rowspan }}">
                                    {{ number_format($totalCasbon) }}
                                </td>
                                <td class="border text-center px-3 py-2 font-bold" rowspan="{{ $rowspan }}">
                                    {{ number_format($totalSisa) }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        @endforeach

    </div>
    @php $first = false; @endphp
@endforeach
</div>
</div>

<div id="modalCasbon" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white p-5 rounded shadow-lg w-80">
        <h3 class="text-lg font-bold mb-3">Tambah Casbon</h3>

        <form method="POST" action="{{ route('pegawai.casbon.store') }}">
            @csrf

            <input type="hidden" name="pegawai_id" id="casbonPegawaiId">

            <div class="mb-3">
                <label class="text-sm">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="text-sm">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border px-3 py-2" required>
            </div>

            <div class="text-right">
                <button type="button" onclick="closeCasbonModal()" class="mr-2 px-3 py-1">Batal</button>
                <button class="bg-green-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="popupModal" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Pegawai Baru</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf

            <!-- Nama Pegawai -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" 
                    name="nama"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nama pegawai"
                    required>
            </div>

            <!-- posisi -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Posisi</label>
                <select
                    name="posisi"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan posisi">
                    <option value="" selected default>Pilih Posisi</option>
                    <option value="Setting">Setting</option>
                    <option value="Print">Print</option>
                    <option value="Press">Press</option>
                    <option value="Cutting">Cutting</option>
                    <option value="Jahit">Jahit</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Packing">Packing</option>
                </select>
            </div>

            <!-- Rekening -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Rekening</label>
                <input type="text" 
                    name="rekening"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nomor rekening">
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Alamat</label>
                <textarea
                    name="alamat"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan alamat"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<div id="editModal" class="hidden fixed flex inset-0 bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Edit Pegawai</h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form id="editForm">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_id">

            <!-- Nama Pegawai -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" 
                    id="edit_nama" name="nama"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required>
            </div>

            <!-- Posisi -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Posisi</label>
                <input type="text" 
                    id="edit_posisi" name="posisi"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan posisi">
            </div>

            <!-- Rekening -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Rekening</label>
                <input type="text" 
                    id="edit_rekening" name="rekening"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nomor rekening">
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Alamat</label>
                <textarea
                    id="edit_alamat" name="alamat"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan alamat"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<div id="pegawaiHistoryPane" 
     class="hidden w-full fixed inset-0 flex bg-black/50 items-center justify-center z-50">
    <div class="bg-white w-full max-w-full h-full shadow-lg p-6">
        
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold w-full">
                Riwayat Pekerjaan: <span id="pegawaiNameTitle"></span>
            </h3>
            <div class="flex justify-end items-center gap-3 w-max">
                <div class="flex justify-end items-center w-max">
                    <label for="sortHistory" class="text-sm font-medium mr-2">Urutkan Berdasarkan:</label>
                    <select id="sortHistory" class="border rounded px-2 py-1 text-sm">
                        <option value="date_desc">Waktu Terbaru</option>
                        <option value="date_asc">Waktu Terlama</option>
                        <option value="jenis_pekerjaan_asc">Jenis Pekerjaan</option>
                        <option value="job_name_asc">Nama Job</option>
                    </select>
                </div>
                <button onclick="closePegawaiHistory()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
            </div>
        </div>

        <div class="max-h-[450px] overflow-y-auto">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="px-1 py-2 text-center text-white"><div class="text-xs"> Nama Job</div></th>
                        <th class="px-1 py-2 text-center text-white"><div class="text-xs"> Jenis Pekerjaan</div></th>
                        <th class="px-1 py-2 text-center text-white"><div class="text-xs"> Qty</div></th>
                        <th class="px-1 py-2 text-center text-white total-qty"><div class="text-xs"> Total Qty</div></th>
                        <th class="px-1 py-2 text-center text-white"><div class="text-xs"> Total Harga</div></th>
                        <th class="px-1 py-2 text-left text-white"><div class="text-xs"> Keterangan</div></th>
                        <th class="px-1 py-2 text-left text-white"><div class="text-xs"> Waktu</div></th>
                    </tr>
                </thead>
                <tbody id="pegawaiHistoryTableBody" class="bg-white">
                </tbody>
            </table>
            <div id="pegawaiHistoryPagination" class="mt-3 flex justify-center gap-2"></div>
        </div>
    </div>
</div>

<script>
    // --- 1. DATA INJEKSI DARI LARAVEL ---
    const allHistories = @json($allHistoriesForJs); 
    const allPegawais = @json($pegawais);       
    const jobData = @json($orders);
    const hargaJenisPekerjaan = @json($harga);
    
    // Variabel untuk Date Formatting
    const dateOptions = { 
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        timeZone: 'Asia/Jakarta'
    };
    
    // Map untuk Lookup Nama Pegawai
    const pegawaiMap = {};
    allPegawais.forEach(p => {
        pegawaiMap[p.id] = p.nama;
    });

    // Map untuk Lookup Nama Job
    const jobNameMap = {};
    jobData.forEach(job => {
        jobNameMap[job.id] = job.nama_job;
    });

    let currentPegawaiId = null;

    // --- FUNGSI 1: MEMBUKA HISTORI PEGAWAI ---
    function openPegawaiHistory(pegawaiId, pegawaiName, buttonElement) {
        const pane = document.getElementById('pegawaiHistoryPane');
        const titleSpan = document.getElementById('pegawaiNameTitle');
        
        const isCurrentlyOpen = pane.classList.contains('hidden');
        
        if (!isCurrentlyOpen && currentPegawaiId === pegawaiId) {
            pane.classList.add('hidden'); 
            return;
        }

        currentPegawaiId = pegawaiId;
        titleSpan.textContent = pegawaiName;
        pane.classList.remove('hidden');

        // Reset sorting ke default (Waktu Terbaru) saat modal dibuka
        const sortSelect = document.getElementById('sortHistory');
        if (sortSelect) {
            sortSelect.value = 'date_desc';
        }
        
        filterAndDisplayPegawaiHistory();
    }

    // --- FUNGSI 2: FILTER DAN RENDER TABEL ---
    const rowsPerPage = 8; // batas data per halaman
    let currentPage = 1; // halaman aktif

    function filterAndDisplayPegawaiHistory(page = 1) {
        currentPage = page; // update halaman aktif
        const orderJenisMap = @json($orderJenisMap);  
        const orderKonsumenMap = @json($orderKonsumenMap);  
        const orderJenisMapNilai = @json($orderJenisMapNilai);  
        const tableBody = document.getElementById('pegawaiHistoryTableBody');

        const sortSelect = document.getElementById('sortHistory');
        const sortCriteria = sortSelect ? sortSelect.value : 'date_desc';

        let filteredHistories = allHistories.filter(history => parseInt(history.pegawai_id) === currentPegawaiId);

        // Sorting (sama seperti sebelumnya)
        if (sortCriteria === 'jenis_pekerjaan_asc') {
            filteredHistories.sort((a, b) => {
                const compareJenis = a.jenis_pekerjaan.localeCompare(b.jenis_pekerjaan);
                return compareJenis !== 0 ? compareJenis : new Date(b.created_at) - new Date(a.created_at);
            });
        } else if (sortCriteria === 'job_name_asc') {
            filteredHistories.sort((a, b) => {
                const nameA = jobNameMap[a.order_id] || '';
                const nameB = jobNameMap[b.order_id] || '';
                const compareJobName = nameA.localeCompare(nameB);
                return compareJobName !== 0 ? compareJobName : new Date(b.created_at) - new Date(a.created_at);
            });
        } else if (sortCriteria === 'date_asc') {
            filteredHistories.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        } else {
            filteredHistories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }

        // Pagination
        const totalPages = Math.ceil(filteredHistories.length / rowsPerPage);
        const startIdx = (currentPage - 1) * rowsPerPage;
        const endIdx = startIdx + rowsPerPage;
        const paginatedHistories = filteredHistories.slice(startIdx, endIdx);

        // Render tabel
        tableBody.innerHTML = '';
        if (paginatedHistories.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-800 font-medium">Tidak ada riwayat pekerjaan yang ditemukan.</td></tr>`;
            return;
        }

        let grandTotalQty = 0;
        let grandTotalNilai = 0;
        let grandTotalAkhir = 0;
        const thTotalQty = document.querySelector('th.total-qty');
        const hasPrintOrPress = filteredHistories.some(h => h.jenis_pekerjaan === 'Print' || h.jenis_pekerjaan === 'Press');
        thTotalQty.textContent = hasPrintOrPress ? 'Total Qty' : 'Harga';

        const formatNumber = num => new Intl.NumberFormat('id-ID').format(num);

        paginatedHistories.forEach((history, index) => {
            const isEven = index % 2 === 0; // genap = true
            const rowBg = isEven ? 'bg-gray-200' : 'bg-white'; // bisa diganti warna lain

            const pegawaiName = pegawaiMap[history.pegawai_id] || 'Unknown';
            const jobId = parseInt(history.order_id);

            const namaJenis = orderJenisMap[jobId] || 'Tidak Ada';
            const namaKonsumen = orderKonsumenMap[jobId] || 'Konsumen Tidak Ada';
            const nilaiJenis = Number(orderJenisMapNilai[jobId] || 0);
            const jobName = jobNameMap[jobId] || `ID ${jobId} (Not Found)`;

            const formattedTimestamp = new Date(history.created_at).toLocaleString('id-ID', dateOptions);
            const hargaJenis = Number(hargaJenisPekerjaan[history.jenis_pekerjaan] || 0);

            let totalNilai = history.qty * nilaiJenis;
            let totalAkhir = 0;
            const isPrintOrPress = history.jenis_pekerjaan === 'Print' || history.jenis_pekerjaan === 'Press';

            if (isPrintOrPress) {
                totalAkhir = totalNilai * hargaJenis;
            } else {
                totalNilai = 0;
                totalAkhir = history.qty * hargaJenis;
            }

            grandTotalQty += history.qty;
            grandTotalNilai += totalNilai;
            grandTotalAkhir += totalAkhir;

            const row = `
                <tr class="${rowBg}">
                    <td class="px-3 py-2 font-medium whitespace-nowrap text-start">
                        ${jobName} ${isPrintOrPress ? namaJenis : ''}  
                        <span class="text-xs text-gray-600">| ${namaKonsumen}</span>
                    </td>
                    <td class="px-3 py-2 font-medium whitespace-nowrap text-center">${history.jenis_pekerjaan}</td>
                    <td class="px-3 py-2 font-medium whitespace-nowrap text-center">
                        ${history.qty} ${isPrintOrPress ? `x ${formatNumber(nilaiJenis)} (${namaJenis})` : ''}
                    </td>
                    <td class="px-3 py-2 font-medium text-center">
                        ${isPrintOrPress ? `${formatNumber(totalNilai)} x ${formatNumber(hargaJenis)}` : `x ${formatNumber(hargaJenis)}`}
                    </td>
                    <td class="px-3 py-2 font-medium text-center">${formatNumber(totalAkhir)}</td>
                    <td class="px-3 py-2 font-medium">${history.keterangan || '-'}</td>
                    <td class="px-3 py-2 font-medium whitespace-nowrap">${formattedTimestamp}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });


        // Total row (tetap ditampilkan di halaman ini)
        const totalRow = `
            <tr class="bg-gray-200 font-bold">
                <td colspan="2" class="px-3 py-2 text-center">Total Keseluruhan</td>
                <td class="px-3 py-2 text-center">${grandTotalQty}</td>
                <td class="px-3 py-2 text-center">${formatNumber(grandTotalNilai)}</td>
                <td class="px-3 py-2 text-center">${formatNumber(grandTotalAkhir)}</td>
                <td colspan="2"></td>
            </tr>
        `;
        tableBody.innerHTML += totalRow;

        // Render pagination
        const paginationContainer = document.getElementById('pegawaiHistoryPagination');
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded border ${i === currentPage ? 'bg-gray-800 text-white' : 'bg-white text-gray-800'}`;
            btn.addEventListener('click', () => filterAndDisplayPegawaiHistory(i));
            paginationContainer.appendChild(btn);
        }
    }

    // --- FUNGSI 3: CLOSE HISTORY ---
    function closePegawaiHistory() {
        document.getElementById('pegawaiHistoryPane').classList.add('hidden');
    }

    // --- 4. EVENT LISTENERS ---
    document.addEventListener('DOMContentLoaded', () => {
        const sortSelect = document.getElementById('sortHistory');
        
        if (sortSelect) {
            // Listener untuk memicu filter dan render ulang tabel saat kriteria sortasi berubah
            sortSelect.addEventListener('change', filterAndDisplayPegawaiHistory);
        }
    });
</script>

<script>
    // Data yang dibutuhkan di JS
    const globalPegawaiData = @json($rekapPerPegawai);
    const hargaMap = @json($harga);
    // Map order data for quick lookup (used to get total lembar for Print/Press)
    const jobMap = {};
    (typeof jobData !== 'undefined' ? jobData : []).forEach(j => { jobMap[j.id] = j; });

    // Fungsi untuk merender ulang tabel berdasarkan filter bulan
    function renderTable(pegawaiId, filteredHistories) {
        const tbody = document.getElementById(`tbody-${pegawaiId}`);
        const table = document.getElementById(`table-${pegawaiId}`);
        if (!tbody || !table) return;

        // 1. Hitung ulang rekapitulasi berdasarkan riwayat yang difilter
        let newTotalKeseluruhan = 0;
        let rekapGrouped = {};

        filteredHistories.forEach(history => {
            const jenis = history.jenis_pekerjaan;
            const qty = parseFloat(history.qty) || 0; // Pastikan QTY adalah float/number

            if (!rekapGrouped[jenis]) {
                rekapGrouped[jenis] = { total_qty: 0, jenis_pekerjaan: jenis, total_lembar: 0 };
            }

            rekapGrouped[jenis].total_qty += qty;

            // Untuk Print / Press, tambahkan total lembar dari order terkait (jika tersedia)
            if (jenis === 'Print' || jenis === 'Press') {
                const orderId = history.order_id;
                const order = jobMap[orderId];
                if (order) {
                    if (jenis === 'Print') {
                        rekapGrouped[jenis].total_lembar += Number(order.total_lembar_print || 0);
                    } else if (jenis === 'Press') {
                        rekapGrouped[jenis].total_lembar += Number(order.total_lembar_press || 0);
                    }
                }
            }
        });

        // 2. Hitung Total Harga
        let rekapJenisArray = Object.values(rekapGrouped).map(r => {
            const hargaSatuan = hargaMap[r.jenis_pekerjaan] || 0;
            let total = 0;

            if (r.jenis_pekerjaan === 'Print' || r.jenis_pekerjaan === 'Press') {
                total = r.total_lembar * hargaSatuan;
            } else {
                total = r.total_qty * hargaSatuan;
            }

            newTotalKeseluruhan += total;
            return {
                ...r,
                hargaSatuan: hargaSatuan,
                total: total
            };
        });
        
        // 3. Render Ulang Baris Tabel
        tbody.innerHTML = '';
        const totalCasbon = globalPegawaiData[pegawaiId].totalCasbon;
        const totalSisa = newTotalKeseluruhan - totalCasbon;
        const rowspan = rekapJenisArray.length || 1;
        
        if (rekapJenisArray.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Tidak ada data untuk bulan ini.</td></tr>`;
            table.setAttribute('data-total-sisa', 0);
            return;
        }

        rekapJenisArray.forEach((r, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border px-3 py-2">${r.jenis_pekerjaan}</td>
                <td class="border text-center px-3 py-2">${r.total_qty}</td>
                <td class="border text-center px-3 py-2">${(r.total_lembar || 0)}</td>
                <td class="border text-right px-3 py-2 font-bold">${r.total.toLocaleString('id-ID')}</td>
                ${index === 0 ? `
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">${newTotalKeseluruhan.toLocaleString('id-ID')}</td>
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">${totalCasbon.toLocaleString('id-ID')}</td>
                    <td class="border text-center px-3 py-2 font-bold" rowspan="${rowspan}">${totalSisa.toLocaleString('id-ID')}</td>
                ` : ''}
            `;
            tbody.appendChild(row);
        });

        // Update data attribute sisa untuk akurasi data
        table.setAttribute('data-total-sisa', totalSisa);
    }

    // Fungsi Utama Filter
    function applyMonthFilter() {
        const filterValue = document.getElementById('monthFilter').value;

        for (const pegawaiId in globalPegawaiData) {
            if (!globalPegawaiData.hasOwnProperty(pegawaiId)) continue;

            // Ambil daftar history untuk pegawai ini.
            // Preferensi: top-level `raw_histories` di globalPegawaiData,
            // jika tidak ada, coba kumpulan `raw_histories` dalam tiap `rekapJenis`.
            // Jika masih kosong, fallback ke `allHistories` yang di-inject global.
            let histories = [];
            const gp = globalPegawaiData[pegawaiId];
            if (gp) {
                if (gp.raw_histories && Array.isArray(gp.raw_histories)) {
                    histories = gp.raw_histories;
                } else if (gp.rekapJenis && Array.isArray(gp.rekapJenis)) {
                    gp.rekapJenis.forEach(g => {
                        if (g.raw_histories && Array.isArray(g.raw_histories)) {
                            histories = histories.concat(g.raw_histories);
                        }
                    });
                }
            }

            if (!histories.length) {
                histories = allHistories.filter(h => parseInt(h.pegawai_id) === parseInt(pegawaiId));
            }

            // Kalau pilih Semua Bulan
            if (filterValue === 'all') {
                renderTable(pegawaiId, histories);
                continue;
            }

            const [year, month] = filterValue.split('-');

            const filteredHistories = histories.filter(h => {
                if (!h) return false;

                const createdDate = h.created_at ? new Date(h.created_at.replace(' ', 'T')) : null;
                const updatedDate = h.updated_at ? new Date(h.updated_at.replace(' ', 'T')) : null;

                const matches = dt => dt && dt.getFullYear().toString() === year && (dt.getMonth() + 1).toString().padStart(2, '0') === month;

                return matches(createdDate) || matches(updatedDate);
            });

            renderTable(pegawaiId, filteredHistories);
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('monthFilter');

        if (select) {
            select.addEventListener('change', applyMonthFilter);
            applyMonthFilter();
        }
    });

</script>

<script>
    function openCasbonModal(id, nama) {
        document.getElementById("casbonPegawaiId").value = id;
        document.getElementById("modalCasbon").classList.remove("hidden");
    }
    function closeCasbonModal() {
        document.getElementById("modalCasbon").classList.add("hidden");
    }
</script>

<script>
    function openModal() {
        document.getElementById('popupModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('popupModal').classList.add('hidden');
    }

    function openEditModal(data) {

        // Tampilkan modal langsung
        document.getElementById('editModal').classList.remove('hidden');

        // Isi data
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit_posisi').value = data.posisi;
        document.getElementById('edit_rekening').value = data.rekening;
        document.getElementById('edit_alamat').value = data.alamat;

        // Set action form
        document.getElementById('editForm').setAttribute("data-id", data.id);
    }

    document.getElementById("editForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let id = this.getAttribute("data-id");
        let formData = new FormData(this);

        fetch(`/dashboard/pegawai/${id}`, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                // Tutup modal
                document.getElementById("editModal").classList.add("hidden");

                window.location.reload();

                // Update row tanpa reload
                document.querySelector(`#row-${id} .nama`).innerText = data.pegawai.nama;
                document.querySelector(`#row-${id} .posisi`).innerText = data.pegawai.posisi;
                document.querySelector(`#row-${id} .rekening`).innerText = data.pegawai.rekening;
                document.querySelector(`#row-${id} .alamat`).innerText = data.pegawai.alamat;
            }
        });
    });

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

<script>
function openTab(id, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(tab => tab.classList.add('hidden'));
    document.getElementById('tab-' + id).classList.remove('hidden');

    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-black', 'text-black');
        b.classList.add('border-transparent');
    });

    btn.classList.add('border-black', 'text-black');
    btn.classList.remove('border-transparent');
}
</script>

@endsection