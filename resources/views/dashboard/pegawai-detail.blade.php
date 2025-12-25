@extends('layouts.dashboard')

@section('title', 'Detail Pegawai - ' . $pegawai->nama)

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

    <!-- Informasi Pegawai -->
    <div class="pelanggan_detail_container">
        <div class="pelanggan_detail_layout" style="margin-bottom:0;">
            <div class="form_field_normal form_field_no_margin">
                <label>Nama</label>
                <input type="text" value="{{ $pegawai->nama }}" disabled class="input_form input_form_big">
            </div>
            <div class="form_field_normal form_field_no_margin">
                <label>Posisi</label>
                <input type="text" value="{{ $pegawai->posisi }}" disabled class="input_form input_form_big">
            </div>
            <div class="form_field_normal form_field_no_margin">
                <label>Nomor Rekening</label>
                <input type="text" value="{{ $pegawai->rekening ?? '-' }}" disabled class="input_form input_form_big">
            </div>
            <div class="form_field_normal form_field_no_margin">
                <label>Alamat</label>
                <input type="text" value="{{ $pegawai->alamat ?? '-' }}" disabled class="input_form input_form_big">
            </div>
        </div>
    </div>

    <!-- Riwayat Pekerjaan -->
    <div class="pelanggan_detail_container" style="padding-top:0;">
        <div class="pegawai_page_box_header">
            <h2>Riwayat Pekerjaan</h2>
            <div class="pegawai_page_box_header_btn form_field_normal form_field_no_margin hidden_print">
                <select id="sortHistory" class="input_form">
                    <option value="date_desc">Waktu Terbaru</option>
                    <option value="date_asc">Waktu Terlama</option>
                    <option value="jenis_pekerjaan_asc">Jenis Pekerjaan</option>
                    <option value="job_name_asc">Nama Job</option>
                </select>
                <button onclick="printHistory()" class="pegawai_page_btn pegawai_page_btn_print">
                    Print
                </button>
            </div>
        </div>

        <!-- Table History -->
        <div class="orders_table_container orders_table_container_big">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-800">
                        <th><div>Nama Job</div></th>
                        <th><div class="text-center">Jenis Pekerjaan</div></th>
                        <th><div class="text-center">Qty</div></th>
                        <th class="total-qty"><div class="text-center">Total Qty</div></th>
                        <th><div class="text-center">Total Harga</div></th>
                        <th class=""><div class="text-center">Keterangan</div></th>
                        <th class=""><div class="text-center">Waktu</div></th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="bg-white">
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="historyPagination" class="pegawai_pagination"></div>
    </div>
</div>

<script>
    // Data dari Laravel
    const allHistories = @json($allHistoriesForJs);
    const orders = @json($orders);
    const hargaJenisPekerjaan = @json($harga);
    const orderJenisMap = @json($orderJenisMap);
    const orderKonsumenMap = @json($orderKonsumenMap);
    const orderJenisMapNilai = @json($orderJenisMapNilai);

    const dateOptions = { 
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        timeZone: 'Asia/Jakarta'
    };

    const jobNameMap = {};
    orders.forEach(job => {
        jobNameMap[job.id] = job.nama_job;
    });

    const rowsPerPage = 8;
    let currentPage = 1;
    let filteredHistories = [];

    // Filter dan render history
    function filterAndDisplayHistory(page = 1) {
        currentPage = page;
        const sortSelect = document.getElementById('sortHistory');
        const sortCriteria = sortSelect ? sortSelect.value : 'date_desc';

        filteredHistories = [...allHistories];

        // Sorting
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

        renderHistoryTable(paginatedHistories, false);

        // Render pagination
        const paginationContainer = document.getElementById('historyPagination');
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `pegawai_pagination_btn ${i === currentPage ? 'bg-gray-800 text-white' : 'bg-white text-gray-800'}`;
            btn.addEventListener('click', () => filterAndDisplayHistory(i));
            paginationContainer.appendChild(btn);
        }
    }

    function renderHistoryTable(histories, isPrint = false) {
        const tableBody = document.getElementById('historyTableBody');
        tableBody.innerHTML = '';

        if (histories.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Tidak ada riwayat pekerjaan.</td></tr>`;
            return;
        }

        let grandTotalQty = 0;
        let grandTotalNilai = 0;
        let grandTotalAkhir = 0;
        const thTotalQty = document.querySelector('th.total-qty');
        const hasPrintOrPress = histories.some(h => h.jenis_pekerjaan === 'Print' || h.jenis_pekerjaan === 'Press');
        thTotalQty.textContent = hasPrintOrPress ? 'Total Qty' : 'Harga';

        const formatNumber = num => new Intl.NumberFormat('id-ID').format(num);

        histories.forEach((history, index) => {
            const isEven = index % 2 === 0;
            const rowBg = isEven ? 'bg-gray-200' : 'bg-white';

            const jobId = parseInt(history.order_id);
            const namaJenis = orderJenisMap[jobId] || 'Tidak Ada';
            const namaKonsumen = orderKonsumenMap[jobId] || 'Konsumen Tidak Ada';
            const nilaiJenis = Number(orderJenisMapNilai[jobId] || 0);
            const jobName = jobNameMap[jobId] || `ID ${jobId}`;

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
                    <td><div class="text-center">${history.jenis_pekerjaan}</div></td>
                    <td><div class="text-center">
                        ${history.qty} ${isPrintOrPress ? `x ${formatNumber(nilaiJenis)} (${namaJenis})` : ''}
                    </div></td>
                    <td><div class="text-center">
                        ${isPrintOrPress ? `${formatNumber(totalNilai)} x ${formatNumber(hargaJenis)}` : `x ${formatNumber(hargaJenis)}`}
                    </div></td>
                    <td><div class="text-center">${formatNumber(totalAkhir)}</div></td>
                    <td class="px-3 py-2 font-medium">${history.keterangan || '-'}</td>
                    <td class="px-3 py-2 font-medium whitespace-nowrap">${formattedTimestamp}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // Total row
        const totalRow = `
            <tr class="bg-gray-200 font-bold">
                <td colspan="2"><div class="text-center"><b>Total Keseluruhan</b></div></td>
                <td><div class="text-center"><b>${grandTotalQty}</b></div></td>
                <td><div class="text-center"><b>${formatNumber(grandTotalNilai)}</b></div></td>
                <td><div class="text-center"><b>${formatNumber(grandTotalAkhir)}</b></div></td>
                <td colspan="2"></td>
            </tr>
        `;
        tableBody.innerHTML += totalRow;
    }

    function printHistory() {
        // Render semua data untuk print
        renderHistoryTable(filteredHistories, true);
        
        // Sembunyikan pagination
        const pagination = document.getElementById('historyPagination');
        if (pagination) {
            pagination.style.display = 'none';
        }

        // Trigger print
        setTimeout(() => {
            window.print();
        }, 200);

        // Restore pagination setelah print
        window.addEventListener('afterprint', function restoreView() {
            const totalPages = Math.ceil(filteredHistories.length / rowsPerPage);
            const startIdx = (currentPage - 1) * rowsPerPage;
            const endIdx = startIdx + rowsPerPage;
            const paginatedHistories = filteredHistories.slice(startIdx, endIdx);
            
            renderHistoryTable(paginatedHistories, false);
            
            if (pagination) {
                pagination.style.display = 'flex';
            }
            
            window.removeEventListener('afterprint', restoreView);
        }, { once: true });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        const sortSelect = document.getElementById('sortHistory');
        if (sortSelect) {
            sortSelect.addEventListener('change', () => filterAndDisplayHistory(1));
        }
        
        filterAndDisplayHistory();
    });
</script>

@endsection
