@extends('layouts.dashboard')

@section('title', 'Pengeluaran')

@section('content')
<style>
    /* CSS NATIVE - Tema Merah Tua dan Hitam Klasik */
    .pengeluaran-container {
        padding: 3vw 7vw;
        background-color: #1a1a1a;
        min-height: calc(100vh - 120px);
    }
    
    /* Header dengan filter dan tombol */
    .pengeluaran-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2vw;
        flex-wrap: wrap;
        gap: 1vw;
    }
    
    .filter-section {
        display: flex;
        gap: 1vw;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-label {
        color: #cccccc;
        font-size: 0.9vw;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-select {
        background: #000000;
        border: 1px solid #e40606;
        border-radius: 0.3vw;
        padding: 0.6vw 1vw;
        color: white;
        font-size: 0.9vw;
        min-width: 120px;
        cursor: pointer;
    }
    
    .filter-select:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(228, 6, 6, 0.3);
    }
    
    .filter-option {
        background: #000000;
        color: white;
        padding: 8px;
    }
    
    .action-buttons {
        display: flex;
        gap: 1vw;
    }
    
    .btn-print {
        background: #e40606;
        border: 1px solid #e40606;
        border-radius: 0.3vw;
        padding: 0.6vw 1.5vw;
        color: white;
        font-size: 0.9vw;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5vw;
    }
    
    .btn-print:hover {
        background: #b30505;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(228, 6, 6, 0.3);
    }
    
    .print-icon {
        width: 1vw;
        height: 1vw;
        fill: white;
    }
    
    .pengeluaran-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2vw;
    }
    
    .summary-item {
        background: #000000;
        border: .1vw solid #e40606;
        border-left: .85vw solid #e40606;
        border-radius: .3vw;
        padding: 2.3vw;
        color: white;
        transition: all 0.3s ease;
    }
    .summary-item:hover {
        box-shadow: 0 4px 15px #e40606;
        transform: translateY(-.6vw);
        transition: all 0.3s ease;
    }
    
    .summary-item h3 {
        color: #cccccc;
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.9;
    }
    
    .summary-item h1 {
        color: #ffffff;
        font-size: 2.5vw;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .summary-item h1::before {
        content: "Rp ";
        font-size: 20px;
        color: #e40606;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .pengeluaran-container {
            padding: 20px;
        }
        
        .pengeluaran-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .filter-section {
            width: 100%;
            justify-content: space-between;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: center;
        }
        
        .btn-print {
            padding: 10px 20px;
            font-size: 14px;
        }
        
        .pengeluaran-summary {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .summary-item h1 {
            font-size: 24px;
        }
        
        .filter-label {
            font-size: 12px;
        }
        
        .filter-select {
            font-size: 12px;
            padding: 8px 12px;
        }
        
        .print-icon {
            width: 16px;
            height: 16px;
        }
    }
    
    @media (max-width: 480px) {
        .pengeluaran-summary {
            grid-template-columns: 1fr;
        }
        
        .summary-item {
            padding: 20px 15px;
        }
        
        .summary-item h1 {
            font-size: 20px;
        }
        
        .btn-print {
            width: 100%;
            justify-content: center;
        }
        
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-select {
            width: 100%;
        }
    }
</style>

<div class="dashboard_banner dashboard_banner_print">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
</div>

<div class="pengeluaran-container">
    <!-- Header dengan Filter -->
    <div class="pengeluaran-header">
        <div class="filter-section">
            <span class="filter-label">Filter Periode:</span>
            <select id="filterBulan" class="filter-select">
                <option value="" class="filter-option">Pilih Bulan</option>
                <option value="1" class="filter-option">Januari</option>
                <option value="2" class="filter-option">Februari</option>
                <option value="3" class="filter-option">Maret</option>
                <option value="4" class="filter-option">April</option>
                <option value="5" class="filter-option">Mei</option>
                <option value="6" class="filter-option">Juni</option>
                <option value="7" class="filter-option">Juli</option>
                <option value="8" class="filter-option">Agustus</option>
                <option value="9" class="filter-option">September</option>
                <option value="10" class="filter-option">Oktober</option>
                <option value="11" class="filter-option">November</option>
                <option value="12" class="filter-option">Desember</option>
            </select>
            
            <select id="filterTahun" class="filter-select">
                <option value="" class="filter-option">Pilih Tahun</option>
                @php
                    $currentYear = date('Y');
                    for($year = $currentYear; $year >= $currentYear - 5; $year--) {
                        echo '<option value="' . $year . '" class="filter-option">' . $year . '</option>';
                    }
                @endphp
            </select>
        </div>
        
        <div class="action-buttons">
            <button class="btn-print" onclick="window.print()">
                <!-- SVG Print Icon -->
                <svg class="print-icon" viewBox="0 0 24 24">
                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                </svg>
                Cetak Laporan
            </button>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="pengeluaran-summary">
        <div class="summary-item">
            <h3>Total Bahan</h3>
            <h1>{{ number_format($grandTotalBahan, 0, ',', '.') }}</h1>
        </div>
        
        <div class="summary-item">
            <h3>Total Kertas</h3>
            <h1>{{ number_format($grandTotalKertas, 0, ',', '.') }}</h1>
        </div>

        <div class="summary-item">
            <h3>Total Asesoris</h3>
            <h1>{{ number_format($grandTotalAsesoris, 0, ',', '.') }}</h1>
        </div>

        <div class="summary-item">
            <h3>Total Ongkos Gawe</h3>
            <h1>{{ number_format($grandTotalOngkos, 0, ',', '.') }}</h1>
        </div>

        @if($grandTotalPengeluaranLain > 0)
            <div class="summary-item">
                <h3>Pengeluaran Lain</h3>
                <h1>{{ number_format($grandTotalPengeluaranLain, 0, ',', '.') }}</h1>
            </div>
        @endif

        @if($grandTotalCalo > 0)
            <div class="summary-item">
                <h3>Biaya Calo</h3>
                <h1>{{ number_format($grandTotalCalo, 0, ',', '.') }}</h1>
            </div>
        @endif

        <div class="summary-item" style="grid-column: span 3; border: .2vw solid #ff0000; background: #2a0a0a;">
            <h3>Total Semua Pengeluaran</h3>
            <h1>{{ number_format($grandTotalSemuaBiaya, 0, ',', '.') }}</h1>
        </div>
    </div>

    <!-- Detail Pengeluaran Lain per Kategori -->
    @if(count($biayaCategories) > 0 && $grandTotalPengeluaranLain > 0)
    <div class="detail-section" style="margin-top: 3vw; padding: 2vw; background: #000000; border: 1px solid #e40606; border-radius: 0.3vw;">
        <h3 style="color: #cccccc; font-size: 16px; font-weight: 600; margin-bottom: 1.5vw; text-transform: uppercase;">
            <i class="fas fa-list-alt"></i> Detail Pengeluaran Lain
        </h3>
        
        <div class="detail-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1vw;">
            @foreach($biayaCategories as $category)
                @if(($categoryTotals[$category] ?? 0) > 0)
                <div class="detail-item" style="background: #1a1a1a; padding: 1vw; border-left: 0.4vw solid #e40606; border-radius: 0.2vw;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #cccccc; font-size: 0.9vw;">{{ $category }}</span>
                        <span style="color: #ffffff; font-weight: 600; font-size: 1vw;">
                            Rp {{ number_format($categoryTotals[$category] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBulan = document.getElementById('filterBulan');
        const filterTahun = document.getElementById('filterTahun');
        
        // Set nilai default ke bulan dan tahun saat ini
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1;
        const currentYear = currentDate.getFullYear();
        
        filterBulan.value = currentMonth;
        filterTahun.value = currentYear;
        
        // Event listener untuk filter
        filterBulan.addEventListener('change', filterData);
        filterTahun.addEventListener('change', filterData);
        
        // Fungsi untuk filter data
        function filterData() {
            const bulan = filterBulan.value;
            const tahun = filterTahun.value;
            
            if (bulan && tahun) {
                // Tampilkan loading
                showLoading();
                
                // Kirim request AJAX ke server
                fetch(`/dashboard/pengeluaran/filter?bulan=${bulan}&tahun=${tahun}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
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
                    console.log('Data received from server:', data); // Debug log
                    // Update data pada halaman
                    updateData(data);
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                    alert('Terjadi kesalahan saat memuat data');
                });
            }
        }
        
        // Fungsi untuk menampilkan loading
        function showLoading() {
            const summaryItems = document.querySelectorAll('.summary-item h1');
            summaryItems.forEach(item => {
                item.innerHTML = '<span style="color: #e40606">Loading...</span>';
            });
        }
        
        // Fungsi untuk update data setelah filter - PERBAIKAN DI SINI
        function updateData(data) {
            console.log('Updating data with:', data); // Debug log
            
            // Format angka
            function formatRupiah(angka) {
                if (!angka && angka !== 0) angka = 0;
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Dapatkan semua elemen summary-item
            const summaryItems = document.querySelectorAll('.summary-item');
            
            // Update setiap item berdasarkan urutan yang muncul di HTML
            summaryItems.forEach((item, index) => {
                const titleElement = item.querySelector('h3');
                if (!titleElement) return;
                
                const title = titleElement.textContent.trim();
                const valueElement = item.querySelector('h1');
                
                if (!valueElement) return;
                
                // Cari data yang sesuai berdasarkan judul
                let value = 0;
                
                switch(title) {
                    case 'Total Bahan':
                        value = data.grandTotalBahan || 0;
                        break;
                    case 'Total Kertas':
                        value = data.grandTotalKertas || 0;
                        break;
                    case 'Total Asesoris':
                        value = data.grandTotalAsesoris || 0;
                        break;
                    case 'Total Ongkos Gawe':
                        value = data.grandTotalOngkos || 0;
                        break;
                    case 'Pengeluaran Lain':
                        value = data.grandTotalPengeluaranLain || 0;
                        break;
                    case 'Biaya Calo':
                        value = data.grandTotalCalo || 0;
                        break;
                    case 'Total Semua Pengeluaran':
                        value = data.grandTotalSemuaBiaya || 0;
                        break;
                }
                
                // Update nilai
                valueElement.innerHTML = formatRupiah(value);
                console.log(`Updated ${title}: ${value}`); // Debug log
            });
            
            // Update detail pengeluaran lain jika ada
            updateDetailBiaya(data.categoryTotals || {});
        }
        
        // Fungsi untuk update detail biaya
        function updateDetailBiaya(categoryTotals) {
            const detailSection = document.querySelector('.detail-section');
            if (!detailSection) return;
            
            const detailGrid = detailSection.querySelector('.detail-grid');
            if (!detailGrid) return;
            
            // Kosongkan konten lama
            detailGrid.innerHTML = '';
            
            // Buat elemen baru untuk setiap kategori
            Object.entries(categoryTotals).forEach(([category, total]) => {
                if (total > 0) {
                    const detailItem = document.createElement('div');
                    detailItem.className = 'detail-item';
                    detailItem.style.cssText = 'background: #1a1a1a; padding: 1vw; border-left: 0.4vw solid #e40606; border-radius: 0.2vw;';
                    
                    const content = document.createElement('div');
                    content.style.cssText = 'display: flex; justify-content: space-between; align-items: center;';
                    
                    const nameSpan = document.createElement('span');
                    nameSpan.style.cssText = 'color: #cccccc; font-size: 0.9vw;';
                    nameSpan.textContent = category;
                    
                    const valueSpan = document.createElement('span');
                    valueSpan.style.cssText = 'color: #ffffff; font-weight: 600; font-size: 1vw;';
                    valueSpan.textContent = 'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    
                    content.appendChild(nameSpan);
                    content.appendChild(valueSpan);
                    detailItem.appendChild(content);
                    detailGrid.appendChild(detailItem);
                }
            });
        }
        
        // Fungsi untuk hide loading
        function hideLoading() {
            // Loading akan dihilangkan saat updateData selesai
        }
    });
</script>

@endsection