<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Progress</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="dashboard_wrapper progress_custom">
    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">

        <div class="dashboard_banner_btn">
            <button onclick="openModalOrder()">
                Tambah Pesanan
            </button>
            <div class="dashboard_banner_select">
                <label>Pilih Job Lain</label>
                <select id="selectJob">
                    <option value="Progress Keseluruhan">Progress Keseluruhan</option>
                    @foreach ($orders as $o)
                        <option value="{{ $o->slug }}" {{ $o->slug == $job->slug ? 'selected' : '' }}>{{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }}  - {{ $o->nama_konsumen }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="dashboard_content">
        <div class="dashboard_top_progress">
            <div class="dashboard_top_progress_card">
                <h2 id="nama_job">{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }}</h2>
                <p id="qty">{{ $job->qty }}</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>Lilana Migawean</h2>
                <p id="hari">{{ (float) $job->hari }} Poe</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>
                    {{ $job->sisa_jahit == 0 ? 'Pagawean Beres' : 'Kudu Beresna' }}
                </h2>

                <p id="deadline">
                    @if ($job->sisa_jahit == 0)
                        BERES
                    @else
                        {{ (float) $job->deadline }} POE DEUI
                    @endif
                </p>
            </div>
        </div>

        <div class="dashboard_bottom_progress">
            <div class="dashboard_bottom_progress_layout">

            <!-- Total Setting -->
            <div class="dashboard_bottom_progress_card">
                <h2>Setting</h2>

                <p id="setting">
                    @if ($job->setting == 1)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        <img src="{{ asset('icons/close-icon.svg') }}" alt="Icon">
                    @endif
                </p>

                <span class="dashboard_setting_card">
                    {{ $job->setting == 1 ? 'Selesai' : 'Belum' }}
                </span>

                <h5>
                    {{ $job->latestSettingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                $isComplete = $job->setting == 1;
                
                $jsAction = $isComplete 
                    ? 'openModal()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Print -->
            <div class="dashboard_bottom_progress_card">
                <h2>Print</h2>
                <p id="print">
                    @if ($job->sisa_print == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->print }}
                    @endif
                </p>
                <span 
                    class="dashboard_print_card"
                    id="sisa_print"
                >
                    {{ $job->sisa_print == 0 ? 'Selesai' : 'Proses'}}
                </span>

                <h5>
                    {{ $job->latestPrintHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionPress = $isComplete 
                    ? 'openModalPress()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Press -->
            <div class="dashboard_bottom_progress_card">
                <h2>Press</h2>
                <p id="press">
                    @if ($job->sisa_press == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->press }}
                    @endif
                </p>
                <span class="dashboard_press_card" id="sisa_press">{{ $job->sisa_press == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestPressHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionCutting = $isComplete 
                    ? 'openModalCutting()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Cutting -->
            <div class="dashboard_bottom_progress_card">
                <h2>Cutting</h2>
                <p id="cutting">
                    @if ($job->sisa_cutting == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->cutting }}
                    @endif
                </p>
                <span class="dashboard_cutting_card" id="sisa_cutting">{{ $job->sisa_cutting == 0 ? 'Selesai' : 'Proses' }}</span>
                <h5>
                    {{ $job->latestCuttingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionJahit = $isComplete 
                    ? 'openModalJahit()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Jahit -->
            <div class="dashboard_bottom_progress_card">
                <h2>Jahit</h2>
                <p id="jahit">
                    @if ($job->sisa_jahit == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->jahit }}
                    @endif
                </p>
                <span class="dashboard_jahit_card" id="sisa_jahit">{{ $job->sisa_jahit == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestJahitHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionFinishing = $isComplete 
                    ? 'openModalFinishing()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Finishing -->
            <div class="dashboard_bottom_progress_card">
                <h2>Finishing</h2>
                <p id="finishing">
                    @if ($job->sisa_finishing == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->finishing }}
                    @endif
                </p>
                <span class="dashboard_cutting_card" id="sisa_finishing">{{ $job->sisa_finishing == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestFinishingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionPacking = $isComplete 
                    ? 'openModalPacking()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Packing -->
            <div class="dashboard_bottom_progress_card">
                <h2>Packing</h2>
                <p id="packing">
                    @if ($job->sisa_packing == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->packing }}
                    @endif
                </p>
                <span class="dashboard_jahit_card" id="sisa_packing">{{ $job->sisa_packing == 0 ? 'Selesai' : 'Proses' }}</span>
                <h5>
                    {{ $job->latestPackingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>
</div>
</div>
    </div>
</div>

<div id="popupModalOrder" class="dashboard_popup_order popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">
            <!-- FORM -->
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="form_field">
                    <div class="dashboard_popup_order_heading">
                        <label>Nama Konsumen</label>
                        <button onclick="closeModalOrder()" type="button"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
                    </div>
                    <div class="dashboard_popup_order_konsumen">
                        <select class="col-7 w-full" id="select2-nama-konsumen" name="nama_konsumen" required>
                            <option value="" selected disabled>Pilih Nama Konsumen...</option>
                            @foreach ($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                        <div></div>
                        <input type="text" name="affiliator_kode" class="input_form" placeholder="Kode Affiliator (Opsional)">
                    </div>
                </div>

                <!-- Nama Job -->
                <div class="form_field">
                    <div class="dashboard_popup_order_kategori">

                        @foreach ($kategoriList as $kategori)
                            <button type="button"
                                onclick="openKategori({{ $kategori->id }})"
                                class="dashboard_popup_order_kategori_btn tab-btn">
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
                            id="qtyOrders"
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
                    <button type="button" onclick="closeModalOrder()">
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

<div id="historyPane">
    <div class="history_pane_box">
        
        <h3 id="historyTitle">Riwayat Pekerjaan: Print</h3>

        <div class="history_pane_box_header">
            <h2 id="selectedJobNameDisplay">
                Semua Job
            </h2>
        </div>

        <div class="history_pane_box_table">
            <table>
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Nama Job</th>
                        <th>Nama Konsumen</th>
                        <th>Jenis</th>
                        <th>Jumlah (Qty)</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="bg-white divide-y divide-gray-800">
                    <tr><td colspan="7" class="text-center py-4 text-white">Pilih Job untuk melihat riwayat.</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Setting -->
<div id="popupModalSetting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalSetting()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Setting Baru</h2>
            <button onclick="closeModalSetting()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="setting">

            {{-- PILIH PEGAWAI --}}
            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiSetting"
                    name="pegawai_id"
                    class="select2-custom"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php $kategori = strtolower("setting"); @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)
                        @php
                            $lastHistory = $p->latestHistory;
                            $lastJobType = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty  = $lastHistory?->qty;
                            $lastOrder   = $lastHistory?->order;
                            $jobName     = $lastOrder?->nama_job;
                            $customerName= $lastOrder?->nama_konsumen;
                            $lastJobInfo = $lastOrder
                                ? "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}"
                                : "Belum ada input";
                            $jobDisplay  = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-setting="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }}" readonly
                    class="input_form">
            </div>

            <div class="form_field_normal">
                <label>Jumlah Setting</label>

                <div class="dashboard_popup_order_btn_setting">
                    <button 
                        type="button" 
                        id="settingBelumBtn"
                        data-value="0">
                        Belum
                    </button>

                    <button 
                        type="button" 
                        id="settingSelesaiBtn"
                        data-value="1">
                        Selesai
                    </button>
                </div>

                <input type="hidden" name="qty" id="qtySettingInput" value="0">

                <span id="errorPesanSetting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    Input hanya boleh 0 atau 1.
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button 
                    type="button" 
                    onclick="closeModalSetting()">
                    Batal
                </button>

                <button 
                    type="submit" 
                    id="submitButtonSetting"
                    disabled>
                    Simpan
                </button>
            </div>
        </form>


    </div>
</div>

<!-- PRINT -->
<div id="popupModal" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModal()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Print</h2>
            <button onclick="closeModal()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPrint">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="print">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawai"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("print");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-print="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa print : {{ $job->sisa_print }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Print</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="qtyTersedia">
                            Prineun: {{ $job->qty }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPrint"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPrint">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="printSelesai">
                            Print selesai: {{ $job->print }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPrint">
                            Sisa Print: {{ $job->sisa_print }}
                        </p>
                    </div>
                </div>
                <span id="warningPrint" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRINEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModal()">
                    Batal
                </button>
                <button id="submitBtnPrint" type="button">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PRESS -->
<div id="popupModalPress" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPress()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Press</h2>
            <button onclick="closeModalPress()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPress">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="press">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiPress"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("press");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-press="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa press : {{ $job->sisa_press }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Press</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="printTersedia">
                            Presseun: {{ $job->print - $job->press }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPress"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPress">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="pressSelesai">
                            Press selesai: {{ $job->press }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPress">
                            Sisa Press: {{ $job->sisa_press }}
                        </p>
                    </div>
                </div>
                <span id="warningPress" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRESSEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalPress()"
                    >
                    Batal
                </button>
                <button id="submitBtnPress" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- CUTTING -->
<div id="popupModalCutting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalCutting()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Cutting</h2>
            <button onclick="closeModalCutting()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formCutting">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="cutting">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiCutting"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("cutting");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-cutting="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa cutting : {{ $job->sisa_cutting }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Cutting</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="pressTersedia">
                            Cuttingeun: {{ $job->press - $job->cutting }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyCutting"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanCutting">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="cuttingSelesai">
                           Cutting selesai: {{ $job->cutting }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaCutting">
                            Sisa Cutting: {{ $job->sisa_cutting }}
                        </p>
                    </div>
                </div>
                <span id="warningCutting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI CUTTINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalCutting()"
                    >
                    Batal
                </button>
                <button id="submitBtnCutting" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- JAHIT -->
<div id="popupModalJahit" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalJahit()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Jahit</h2>
            <button onclick="closeModalJahit()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formJahit">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="jahit">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiJahit"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("jahit");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-jahit="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa jahit : {{ $job->sisa_jahit }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Jahit</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="cuttingTersedia">
                            Jahiteun: {{ $job->cutting - $job->jahit }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyJahit"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanJahit">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="jahitSelesai">
                            Cutting selesai: {{ $job->jahit }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaJahit">
                            Sisa Cutting: {{ $job->sisa_jahit }}
                        </p>
                    </div>
                </div>
                <span id="warningJahit" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI JAHITEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalJahit()"
                    >
                    Batal
                </button>
                <button id="submitBtnJahit" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- FINISHING -->
<div id="popupModalFinishing" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalFinishing()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Finishing</h2>
            <button onclick="closeModalFinishing()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formFinishing">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="finishing">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiFinishing"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("finishing");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-finishing="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa finishing : {{ $job->sisa_finishing }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Finishing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="jahitTersedia">
                            Pinisingeun: {{ $job->jahit - $job->finishing }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyFinishing"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanFinishing">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="finishingSelesai">
                           Finishing selesai: {{ $job->finishing }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaFinishing">
                            Sisa Finishing: {{ $job->sisa_finishing }}
                        </p>
                    </div>
                </div>
                <span id="warningFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PINISINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalFinishing()"
                    >
                    Batal
                </button>
                <button id="submitBtnFinishing" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PACKING -->
<div id="popupModalPacking" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPacking()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Packing</h2>
            <button onclick="closeModalPacking()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPacking">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="packing">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiPacking"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("packing");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-packing="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa packing : {{ $job->sisa_packing }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Packing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="finishingTersedia">
                            Pekingeun: {{ $job->finishing - $job->packing }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPacking"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPacking">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="packingSelesai">
                            Packing selesai: {{ $job->packing }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisa_packing">
                            Sisa Packing: {{ $job->sisa_packing }}
                        </p>
                    </div>
                </div>
                <span id="warningFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PEKINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalPacking()"
                    >
                    Batal
                </button>
                <button id="submitBtnPacking" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // Pastikan jQuery dan library Select2 sudah di-load di layout Anda.
    
    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        $('#pilihPegawaiSetting, #pilihPegawai, #pilihPegawaiPress, #pilihPegawaiCutting, #pilihPegawaiJahit, #pilihPegawaiFinishing, #pilihPegawaiPacking').select2({
            
            // 1. Fitur Utama: Mengaktifkan scrollbar dan pencarian
            placeholder: "Pilih Pegawai",
            allowClear: true, 
            
            // 2. Perbaikan Layout: Memastikan lebar 100%
            width: '100%', 

            // 3. Template
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });
    });
    
    // Catatan: Jika Anda tidak menggunakan jQuery, Anda harus mengganti $(document).ready
    // dengan event listener DOMContentLoaded dan menggunakan syntax Select2 murni.
</script>

<script>
    // Data dari Laravel
    const allHistories = @json($allHistories); // Dapatkan semua riwayat
    const allPegawais = @json($pegawais);   
    
    const jobData = @json($orders);

    const dateOptions = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'Asia/Jakarta'
    };
    
    // Konversi array pegawai menjadi objek map ID => Nama untuk lookup cepat
    const pegawaiMap = {};
    allPegawais.forEach(p => {
        pegawaiMap[p.id] = p.nama;
    });

    let currentJobId = null; // ID Job yang sedang aktif di filter
    let currentKategori = null; // Kategori yang sedang aktif (Print/Press/etc)

    function openHistory(kategori, buttonElement, jobId) { 
        const pane = document.getElementById('historyPane');
        const title = document.getElementById('historyTitle');
        
        // 1. Periksa apakah sudah terbuka (Toggle)
        if (pane.classList.contains('active') && currentKategori === kategori) {
            pane.classList.remove('active');
            return;
        }

        // 2. Set Global State & Tampilan
        currentKategori = kategori;
        currentJobId = jobId;
        
        pane.classList.add('active');

        // 3. Muat Data Default (untuk kategori ini)
        filterAndDisplayHistory();
    }

    function filterAndDisplayHistory() {
        const tableBody = document.getElementById('historyTableBody');
        const selectedJobNameDisplay = document.getElementById('selectedJobNameDisplay');
        const title = document.getElementById('historyTitle'); // Ambil title element

        // 1. Temukan Nama Job untuk Display (Menggunakan jobData yang dimuat di awal)
        const selectedJob = jobData.find(job => job.id === currentJobId);
        let jobName = selectedJob ? selectedJob.nama_job : 'Unknown Job';
        let customerName = '';
        let jenisOrderName = '';

        if (selectedJob) {
            jobName = selectedJob.nama_job;
            customerName = selectedJob.nama_konsumen;
            // Ambil nama jenis order jika relasi tersedia
            jenisOrderName = selectedJob.jenis_order?.nama_jenis || '';
        }
    
        // Update Judul & Nama Job Display
        title.textContent = `Riwayat Pekerjaan: ${currentKategori}`;
        if (selectedJobNameDisplay) {
            // Tampilkan 'Nama Job - Jenis Order - Nama Konsumen'
            let displayText = jobName;
            if (jenisOrderName) {
                displayText += ` ${jenisOrderName}`;
            }
            displayText += ` - ${customerName}`;
            selectedJobNameDisplay.textContent = displayText; 
        }
        
        // 2. Filter Data (Hanya berdasarkan Job ID dan Kategori saat ini)
        let filteredHistories = allHistories.filter(history => {
            const matchesCategory = history.jenis_pekerjaan === currentKategori;
            // Filter diaktifkan hanya jika Job ID sudah diset di openHistory
            const matchesJob = currentJobId ? history.order_id === currentJobId : true; 
            return matchesCategory && matchesJob;
        });

        // Urutkan berdasarkan waktu terbaru
        filteredHistories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // 3. Render Tabel
        // ... (Logika rendering tabel tetap sama, menggunakan dateOptions dan pegawaiMap) ...

        tableBody.innerHTML = '';
        
        if (filteredHistories.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-800 font-medium">Tidak ada riwayat untuk Job ${jobName} di tahap ${currentKategori}.</td></tr>`;
            return;
        }

        filteredHistories.forEach(history => {
            const pegawaiName = pegawaiMap[history.pegawai_id] || 'Unknown';

            const formattedTimestamp = new Date(history.created_at).toLocaleString('id-ID', dateOptions);
            
            const row = `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${pegawaiName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${history.jenis_pekerjaan}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${customerName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${jenisOrderName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium text-center"><div class="w-28">${history.qty}</div></td>
                    <td class="px-3 py-2 font-medium">${history.keterangan || '-'}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${formattedTimestamp}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // 3. Tambahkan Event Listener (untuk filter Job di dalam pane)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelect = document.getElementById('historyJobSelect');
        if (jobSelect) {
            jobSelect.addEventListener('change', filterAndDisplayHistory);
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. AMBIL SEMUA ELEMEN DI SCOPE DOMContentLoaded ---
    const inputSetting = document.getElementById("qtySettingInput");
    const warningSetting = document.getElementById("errorPesanSetting");
    const submitBtnSetting = document.getElementById("submitButtonSetting"); // Tombol yang bermasalah
    const form = document.getElementById("formSetting"); // Asumsi ID form Anda
    
    const settingBelumBtn = document.getElementById("settingBelumBtn");
    const settingSelesaiBtn = document.getElementById("settingSelesaiBtn");


    // --- FUNGSI HELPER: SET NILAI INPUT & STATUS VISUAL ---
    function setSettingValueAndActive(button, value) {
        // Fungsi ini harus didefinisikan di luar atau menggunakan variabel dari scope luar
        
        // Reset Visual Semua Tombol (Logika Warna)
        [settingBelumBtn, settingSelesaiBtn].forEach(btn => {
            btn.classList.remove("green_bg", "red_bg", "text-white");
            btn.classList.add("background_gray_young", "text-gray-700");
        });

        // 2. Tentukan Warna Aktif
        const activeColor = (value === "1") ? "green_bg" : "red_bg";
        
        // 3. Set Visual Tombol Aktif
        button.classList.remove("background_gray_young", "text-gray-700");
        button.classList.add(activeColor, "text-white");

        // 4. Update Nilai Input Tersembunyi
        inputSetting.value = value;
        
        // 5. KONTROL KRITIS: Update Status Tombol Submit
        // Aktifkan jika nilai = "1" (Selesai), Nonaktifkan jika "0" (Belum)
        submitBtnSetting.disabled = (value !== "1"); 
        warningSetting.classList.add("hidden"); 
    }
    
    // --- LISTENER PENTING: TOMBOL BELUM/SELESAI (PENGGANTI INPUT) ---
    if (settingBelumBtn && settingSelesaiBtn) {
        settingBelumBtn.addEventListener("click", () => {
            setSettingValueAndActive(settingBelumBtn, settingBelumBtn.dataset.value); 
        });

        settingSelesaiBtn.addEventListener("click", () => {
            setSettingValueAndActive(settingSelesaiBtn, settingSelesaiBtn.dataset.value); 
        });
    }
    
    // --- LISTENER SUBMIT (Tetap Sama) ---
    submitBtnSetting.addEventListener("click", function (event) {
        if (inputSetting.value !== "1") {
            warningSetting.textContent = "Status harus diatur Selesai (1) untuk menyimpan.";
            warningSetting.classList.remove("hidden");
            event.preventDefault();
            return;
        }
        
        if (!form.checkValidity() || submitBtnSetting.disabled) {
            event.preventDefault(); 
            return;
        }

        form.submit();
    });

    // --- PENTING: INISIALISASI AWAL ---
    // Atur status awal tombol berdasarkan nilai dari database
    const initialSetting = String({{ $job->setting }});
    if (settingBelumBtn && settingSelesaiBtn) {
        if (initialSetting === "1") {
            setSettingValueAndActive(settingSelesaiBtn, "1");
        } else {
            setSettingValueAndActive(settingBelumBtn, "0");
        }
    }
});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let qtyPrint = {{ $job->qty }};
    let printSelesai = {{ $job->print }};
    let sisaPrint = qtyPrint - printSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputPrint = document.getElementById("qtyPrint");
    let warningPrint = document.getElementById("warningPrint");
    let submitBtnPrint = document.getElementById("submitBtnPrint");
    
    // Asumsi: form memiliki ID formPrint
    let form = document.getElementById("formPrint"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputPrint.max = sisaPrint; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputPrint.addEventListener("input", function() {
        let qtyPrint = parseFloat(inputPrint.value);

        // 1. Cek apakah inputPrint valid dan positif
        if (isNaN(qtyPrint) || qtyPrint <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPrint.classList.add("hidden"); 
            submitBtnPrint.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPrint) && inputPrint.value !== "") {
                 inputPrint.classList.add("border-red-500");
            } else {
                 inputPrint.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah inputPrint melebihi Sisa Stok Nyata
        if (qtyPrint > sisaPrint) {
            // ERROR STATE: Melebihi batas
            warningPrint.textContent = "MELEBIHI PRINEUN"; // Pesan yang diminta
            warningPrint.classList.remove("hidden");
            
            // Tambahkan visual error
            inputPrint.classList.add("border-red-500");
            inputPrint.classList.remove("border-gray-300");
             
            submitBtnPrint.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: InputPrint aman (positif dan dalam batas)
            warningPrint.classList.add("hidden");
            
            // Hapus visual error
            inputPrint.classList.remove("border-red-500");
            inputPrint.classList.add("border-gray-300");
            
            submitBtnPrint.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPrint.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!form.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'inputPrint', cegah submit
        if (submitBtnPrint.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPrint.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        form.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let printSelesai = {{ $job->print }};
    let pressSelesai = {{ $job->press }};
    let sisaPress = printSelesai - pressSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let input = document.getElementById("qtyPress");
    let warningPress = document.getElementById("warningPress");
    let submitBtnPress = document.getElementById("submitBtnPress");
    
    // Asumsi: form memiliki ID formPress
    let form = document.getElementById("formPress"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    input.max = sisaPress; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    input.addEventListener("input", function() {
        let qtyPress = parseFloat(input.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyPress) || qtyPress <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPress.classList.add("hidden"); 
            submitBtnPress.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPress) && input.value !== "") {
                 input.classList.add("border-red-500");
            } else {
                 input.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyPress > sisaPress) {
            // ERROR STATE: Melebihi batas
            warningPress.textContent = "MELEBIHI PRESSEUN"; // Pesan yang diminta
            warningPress.classList.remove("hidden");
            
            // Tambahkan visual error
            input.classList.add("border-red-500");
            input.classList.remove("border-gray-300");
            
            submitBtnPress.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningPress.classList.add("hidden");
            
            // Hapus visual error
            input.classList.remove("border-red-500");
            input.classList.add("border-gray-300");
            
            submitBtnPress.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPress.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!form.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnPress.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPress.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        form.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let pressSelesai = {{ $job->press }};
    let cuttingSelesai = {{ $job->cutting }};
    let sisaCutting = pressSelesai - cuttingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputCutting = document.getElementById("qtyCutting");
    let warningCutting = document.getElementById("warningCutting");
    let submitBtnCutting = document.getElementById("submitBtnCutting");
    
    // Asumsi: form memiliki ID formPress
    let formCutting = document.getElementById("formCutting"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputCutting.max = sisaCutting; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputCutting.addEventListener("input", function() {
        let qtyCutting = parseFloat(inputCutting.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyCutting) || qtyCutting <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningCutting.classList.add("hidden"); 
            submitBtnCutting.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyCutting) && inputCutting.value !== "") {
                 inputCutting.classList.add("border-red-500");
            } else {
                 inputCutting.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyCutting > sisaCutting) {
            // ERROR STATE: Melebihi batas
            warningCutting.textContent = "MELEBIHI CUTTINGEUN"; // Pesan yang diminta
            warningCutting.classList.remove("hidden");
            
            // Tambahkan visual error
            inputCutting.classList.add("border-red-500");
            inputCutting.classList.remove("border-gray-300");
            
            submitBtnCutting.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningCutting.classList.add("hidden");
            
            // Hapus visual error
            inputCutting.classList.remove("border-red-500");
            inputCutting.classList.add("border-gray-300");
            
            submitBtnCutting.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnCutting.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formCutting.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnCutting.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningCutting.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formCutting.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let cuttingSelesai = {{ $job->cutting }};
    let jahitSelesai = {{ $job->jahit }};
    let sisaJahit = cuttingSelesai - jahitSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputJahit = document.getElementById("qtyJahit");
    let warningJahit = document.getElementById("warningJahit");
    let submitBtnJahit = document.getElementById("submitBtnJahit");
    
    // Asumsi: form memiliki ID formPress
    let formJahit = document.getElementById("formJahit"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputJahit.max = sisaJahit; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputJahit.addEventListener("input", function() {
        let qtyJahit = parseFloat(inputJahit.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyJahit) || qtyJahit <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningJahit.classList.add("hidden"); 
            submitBtnJahit.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyJahit) && inputJahit.value !== "") {
                 inputJahit.classList.add("border-red-500");
            } else {
                 inputJahit.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyJahit > sisaJahit) {
            // ERROR STATE: Melebihi batas
            warningJahit.textContent = "MELEBIHI JAHITEUN"; // Pesan yang diminta
            warningJahit.classList.remove("hidden");
            
            // Tambahkan visual error
            inputJahit.classList.add("border-red-500");
            inputJahit.classList.remove("border-gray-300");
            
            submitBtnJahit.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningJahit.classList.add("hidden");
            
            // Hapus visual error
            inputJahit.classList.remove("border-red-500");
            inputJahit.classList.add("border-gray-300");
            
            submitBtnJahit.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnJahit.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formJahit.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnJahit.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningJahit.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formJahit.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let jahitSelesai = {{ $job->jahit }};
    let finishingSelesai = {{ $job->finishing }};
    let sisaFinishing = jahitSelesai - finishingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputFinishing = document.getElementById("qtyFinishing");
    let warningFinishing = document.getElementById("warningFinishing");
    let submitBtnFinishing = document.getElementById("submitBtnFinishing");
    
    // Asumsi: form memiliki ID formPress
    let formFinishing = document.getElementById("formFinishing"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputFinishing.max = sisaFinishing; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputFinishing.addEventListener("input", function() {
        let qtyFinishing = parseFloat(inputFinishing.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyFinishing) || qtyFinishing <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningFinishing.classList.add("hidden"); 
            submitBtnFinishing.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyFinishing) && inputFinishing.value !== "") {
                 inputFinishing.classList.add("border-red-500");
            } else {
                 inputFinishing.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyFinishing > sisaFinishing) {
            // ERROR STATE: Melebihi batas
            warningFinishing.textContent = "MELEBIHI PINISINGEUN"; // Pesan yang diminta
            warningFinishing.classList.remove("hidden");
            
            // Tambahkan visual error
            inputFinishing.classList.add("border-red-500");
            inputFinishing.classList.remove("border-gray-300");
            
            submitBtnFinishing.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningFinishing.classList.add("hidden");
            
            // Hapus visual error
            inputFinishing.classList.remove("border-red-500");
            inputFinishing.classList.add("border-gray-300");
            
            submitBtnFinishing.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnFinishing.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formFinishing.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnFinishing.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningFinishing.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formFinishing.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let finishingSelesai = {{ $job->finishing }};
    let packingSelesai = {{ $job->packing }};
    let sisaPacking = finishingSelesai - packingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputPacking = document.getElementById("qtyPacking");
    let warningPacking = document.getElementById("warningPacking");
    let submitBtnPacking = document.getElementById("submitBtnPacking");
    
    // Asumsi: form memiliki ID formPress
    let formPacking = document.getElementById("formPacking"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputPacking.max = sisaPacking; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputPacking.addEventListener("input", function() {
        let qtyPacking = parseFloat(inputPacking.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyPacking) || qtyPacking <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPacking.classList.add("hidden"); 
            submitBtnPacking.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPacking) && inputPacking.value !== "") {
                 inputPacking.classList.add("border-red-500");
            } else {
                 inputPacking.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyPacking > sisaPacking) {
            // ERROR STATE: Melebihi batas
            warningPacking.textContent = "MELEBIHI PEKINGEUN"; // Pesan yang diminta
            warningPacking.classList.remove("hidden");
            
            // Tambahkan visual error
            inputPacking.classList.add("border-red-500");
            inputPacking.classList.remove("border-gray-300");
            
            submitBtnPacking.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningPacking.classList.add("hidden");
            
            // Hapus visual error
            inputPacking.classList.remove("border-red-500");
            inputPacking.classList.add("border-gray-300");
            
            submitBtnPacking.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPacking.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formPacking.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnPacking.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPacking.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formPacking.submit();
    });

});
</script>

<script>
    function openModal() {
        document.getElementById('popupModal').classList.add('active');
    }
    function openModalSetting() {
        document.getElementById('popupModalSetting').classList.add('active');
    }
    function openModalPress() {
        document.getElementById('popupModalPress').classList.add('active');
    }
    function openModalCutting() {
        document.getElementById('popupModalCutting').classList.add('active');
    }
    function openModalJahit() {
        document.getElementById('popupModalJahit').classList.add('active');
    }
    function openModalFinishing() {
        document.getElementById('popupModalFinishing').classList.add('active');
    }
    function openModalPacking() {
        document.getElementById('popupModalPacking').classList.add('active');
    }
    function closeModal() {
        document.getElementById('popupModal').classList.remove('active');
    }
    function closeModalSetting() {
        document.getElementById('popupModalSetting').classList.remove('active');
    }
    function closeModalPress() {
        document.getElementById('popupModalPress').classList.remove('active');
    }
    function closeModalCutting() {
        document.getElementById('popupModalCutting').classList.remove('active');
    }
    function closeModalJahit() {
        document.getElementById('popupModalJahit').classList.remove('active');
    }
    function closeModalFinishing() {
        document.getElementById('popupModalFinishing').classList.remove('active');
    }
    function closeModalPacking() {
        document.getElementById('popupModalPacking').classList.remove('active');
    }
</script>

<script>
    $(document).ready(function() {
        // --- INISIALISASI SELECT2 ---
        $('#selectJob').select2({
            placeholder: "Pilih Job Lain...", 
            allowClear: true, 
            width: '100%',
        });

        // --- LISTENER PENGALIHAN (REDIRECTION) ---
        // Gunakan event Select2:select untuk kepastian
        $('#selectJob').on('select2:select', function (e) {
            // Nilai (slug) diambil dari data.id
            let slug = e.params.data.id; 

            if (slug === 'Progress Keseluruhan') {
                window.location.href = '/lihat-progres';
            } else {
                // Pastikan slug tidak kosong
                if (slug) {
                    window.location.href = '/lihat-progres/' + slug;
                }
            }
        });
        
        // --- LISTENER UNTUK ALLOW CLEAR (PENTING) ---
        // Tambahkan listener ini jika Anda ingin menangani klik tombol Hapus (Clear)
        $('#selectJob').on('select2:clear', function (e) {
             window.location.href = '/lihat-progres';
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

        document.getElementById('qtyOrders').value = total;
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
function openModalOrder() {
    document.getElementById('popupModalOrder').classList.add('active');
}
function closeModalOrder() {
    document.getElementById('popupModalOrder').classList.remove('active');
}
</script>

</body>
</html>

