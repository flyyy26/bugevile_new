@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
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
                    @foreach ($ordersSelect as $o)
                        <option value="{{ $o->slug }}">
                            {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} - {{ $o->nama_konsumen }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="dashboard_content">
        <div class="dashboard_top_progress">
            <div class="dashboard_top_progress_card">
                <h2 id="nama_job">Jumlah Kabeh Pesenan</h2>
                <p id="qty">{{ $totals->total_qty ?? 0 }}</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>Lilana Migawean</h2>
                <p id="hari">{{ (float) ($totals?->total_hari ?? 0) }} POE</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>Kudu Beresna</h2>
                <p id="deadline">{{ (float) ($totals?->total_deadline ?? 0) }} Poe Deui</p>
            </div>
        </div>

        <div class="dashboard_bottom_progress">
            <div class="dashboard_bottom_progress_layout">

                <!-- Total Setting -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalSetting()" class="dashboard_bottom_progress_btn dashboard_setting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Setting', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_setting_card"><img src="{{ asset('icons/history-icon.svg') }}" alt="Icon"></button>
                    <h2>Setting</h2>
                    
                    <p id="setting">
                        @if ($totals?->total_sisa_setting == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_setting }}
                        @endif
                    </p>
                    
                    <span class="dashboard_setting_card" id="sisa_setting">
                        {{ $totals?->total_sisa_setting == 0 ? 'Selesai' : 'Proses'}}
                    </span>
                    
                    <h5>{{ $totals->latest_setting_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Print -->
                <div id="cardPrint" class="dashboard_bottom_progress_card">
                    <button onclick="openModal()" class="dashboard_bottom_progress_btn dashboard_print_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Print', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_print_card"><img src="{{ asset('icons/history-icon.svg') }}" alt="Icon"></button>
                    <h2>Print</h2>
                    <p id="print">
                        @if ($totals?->total_sisa_print == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_print }}
                        @endif
                    </p>
                    <span class="dashboard_print_card" id="sisa_print">{{ $totals?->total_sisa_print == 0 ? 'Selesai' : 'Proses' }}</span>
                    <h5>{{ $totals->latest_print_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Press -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalPress()" class="dashboard_bottom_progress_btn dashboard_press_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Press', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_press_card"><img src="{{ asset('icons/history-icon.svg') }}" alt="Icon"></button>
                    <h2>Press</h2>
                    <p id="press">
                        @if ($totals?->total_sisa_press == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_press }}
                        @endif
                    </p>
                    <span class="dashboard_press_card" id="sisa_press">{{ $totals?->total_sisa_press == 0 ? 'Selesai' : 'Proses'}}</span>
                    <h5>{{ $totals->latest_press_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Cutting -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalCutting()" class="dashboard_bottom_progress_btn dashboard_cutting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Cutting', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_cutting_card"><img src="{{ asset('icons/history-icon.svg') }}" alt="Icon"></button>
                    <h2>Cutting</h2>
                    <p id="cutting">
                        @if ($totals?->total_sisa_cutting == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_cutting }}
                        @endif
                    </p>
                    <span class="dashboard_cutting_card" id="sisa_cutting">{{ $totals?->total_sisa_cutting == 0 ? 'Selesai' : 'Proses'}}</span>
                    <h5>{{ $totals->latest_cutting_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Jahit -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalJahit()" class="dashboard_bottom_progress_btn dashboard_jahit_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Jahit', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_jahit_card">
                        <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                    </button>
                    <h2>Jahit</h2>
                    <p id="jahit">
                        @if ($totals?->total_sisa_jahit == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_jahit }}
                        @endif
                    </p>
                    <span class="dashboard_jahit_card" id="sisa_jahit">{{ $totals?->total_sisa_jahit == 0 ? 'Selesai' : 'Proses'}}</span>
                    <h5>{{ $totals->latest_jahit_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Finishing -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalFinishing()" class="dashboard_bottom_progress_btn dashboard_cutting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Finishing', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_cutting_card">
                        <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                    </button>
                    <h2>Finishing</h2>
                    <p id="finishing">
                        @if ($totals?->total_sisa_finishing == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_finishing }}
                        @endif
                    </p>
                    <span class="dashboard_cutting_card" id="sisa_finishing">{{ $totals?->total_sisa_finishing == 0 ? 'Selesai' : 'Proses'}}</span>
                    <h5>{{ $totals->latest_finishing_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

                <!-- Total Packing -->
                <div class="dashboard_bottom_progress_card">
                    <button onclick="openModalPacking()" class="dashboard_bottom_progress_btn dashboard_jahit_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                    <button onclick="openHistory('Packing', this)" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_jahit_card">
                        <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                    </button>
                    <h2>Packing</h2>
                    <p id="packing">
                        @if ($totals?->total_sisa_packing == 0)
                            <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                        @else
                            {{ $totals->total_packing }}
                        @endif
                    </p>
                    <span class="dashboard_jahit_card" id="sisa_packing">{{ $totals?->total_sisa_packing == 0 ? 'Selesai' : 'Proses'}}</span>
                    <h5>{{ $totals->latest_packing_history->pegawai->nama ?? 'Belum' }}</h5>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="popupModalSetting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalSetting()"></div>
    <div class="dashboard_popup_progress_layout">

        <div class="dashboard_popup_order_heading">
            <h2>Tambah Setting Baru</h2>
            <button onclick="closeModalSetting()" type="button"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}" class="space-y-5">
            @csrf

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
                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;
                            $lastJobInfo = $lastOrder
                                ? "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}"
                                : "Belum ada input";
                            $jobDisplay  = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-setting="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>
                    @endforeach

                </select>
            </div>


            {{-- PILIH JOB --}}
            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectSetting" 
                    class="select2-custom"
                    required
                >
                    <option value="">Pilih Job</option>
                    @foreach ($orders as $o)
                        @php
                            // Tentukan status teks berdasarkan nilai boolean/integer di $o->setting
                            $statusText = $o->setting == 1 ? 'Setting Selesai' : 'Belum Setting';
                        @endphp
                        
                        <option value="{{ $o->id }}">
                            {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} - {{ $o->nama_konsumen }} | {{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }} ({{ $statusText }})
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- STATUS SETTING --}}
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
                <textarea name="keterangan" id="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
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

<div id="popupModal" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModal()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Print Baru</h2>
            <button onclick="closeModal()" type="button"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>
        
            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPrint" 
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        {{-- Logika: Setting harus 1 DAN sisa_print harus lebih dari 0 --}}
                        @if ($o->setting == 1 && $o->sisa_print > 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                                data-nilai="{{ optional($o->jenisOrder)->nilai ?? '' }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa print : {{ $o->sisa_print }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Print</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="qtyTersedia">
                            Print Qty
                        </p>
                    </div>
                    
                    <input
                        type="number"
                        name="qty"
                        id="qtyPrintInput" data-max-value-print="0"
                        value="0"
                        min="0"
                        class="input_form input_form_dashboard"
                        placeholder="Masukkan Jumlah Progress"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPrint">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="printSelesai" data-print-done="0">
                            Hasil Print: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPrint">
                            Sisa Print: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanPrint" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRINEUN
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_progress_bottom_print">
                <span id="hasilQtyJenis">
                    0
                </span>
                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeModal()">Batal</button>
                    <button type="submit" id="submitButtonPrint">Simpan</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="popupModalPress" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPress()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Press Baru</h2>
            <button onclick="closeModalPress()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
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
        
            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPress"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_print == 0 && $o->sisa_press != 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                                data-nilai-press="{{ optional($o->jenisOrder)->nilai ?? '' }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa press : {{ max($o->print - $o->press, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Press</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="printTersedia">
                            Presseun
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPressInput" data-max-value-press="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />
                    
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPress">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="pressSelesai" data-press-done="0">
                            Hasil Press: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPress">
                            Sisa Press: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanPress" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRESSEUN !!
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_progress_bottom_print">
                <span id="hasilQtyJenisPress">
                    0
                </span>
                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeModalPress()">Batal</button>
                    <button type="submit" id="submitButtonPress">Simpan</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="popupModalCutting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalCutting()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Cutting Baru</h2>
            <button onclick="closeModalCutting()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
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

            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectCutting"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_press == 0 && $o->sisa_cutting != 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa cutting : {{ max($o->press - $o->cutting, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Cutting</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="pressTersedia">
                            Cuttingeun
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyCuttingInput" data-max-value-cutting="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />
                    
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanCutting">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="cuttingSelesai" data-cutting-done="0">
                            Hasil Cutting: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaCutting">
                            Sisa Cutting: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanCutting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI CUTTINGEUN !!
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalCutting()">Batal</button>
                <button type="submit" id="submitButtonCutting">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalJahit" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalJahit()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Jahit Baru</h2>
            <button onclick="closeModalJahit()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
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
        
            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectJahit"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_cutting == 0 && $o->sisa_jahit != 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa jahit : {{ max($o->cutting - $o->jahit, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Jahit</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="cuttingTersedia">
                            Jahiteun
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyJahitInput" data-max-value-jahit="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />
                    
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanJahit">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="jahitSelesai" data-jahit-done="0">
                            Hasil Jahit: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaJahit">
                            Sisa Jahit: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanJahit" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI JAHITEUN !!
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalJahit()">Batal</button>
                <button type="submit" id="submitButtonJahit">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalFinishing" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalFinishing()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Finishing Baru</h2>
            <button onclick="closeModalFinishing()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
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
        
            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectFinishing"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_jahit == 0 && $o->sisa_finishing != 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa finishing : {{ max($o->jahit - $o->finishing, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Finishing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="jahitTersedia">
                            Pinisingeun
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyFinishingInput" data-max-value-finishing="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />
                    
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanFinishing">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="finishingSelesai" data-finishing-done="0">
                            Hasil Finishing: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaFinishing">
                            Sisa Finishing: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PINISINGEUN !!
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalFinishing()">Batal</button>
                <button type="submit" id="submitButtonFinishing">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalPacking" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPacking()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Packing Baru</h2>
            <button onclick="closeModalPacking()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

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

                            $namaJenis = $lastOrder?->jenisOrder?->nama_jenis;

                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} {$namaJenis} - {$customerName}";
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

            <div class="form_field_normal">
                <label>Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPacking"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_finishing == 0 && $o->sisa_packing != 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} 
                                (sisa packing : {{ max($o->finishing - $o->packing, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form_field_normal">
                <label>Jumlah Packing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="finishingTersedia">
                            Pekingeun
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPackingInput" data-max-value-packing="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />
                    
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPacking">
                            Qty: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="packingSelesai" data-packing-done="0">
                            Hasil Packing: 0
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPacking">
                            Sisa Packing: 0
                        </p>
                    </div>
                </div>
                <span id="errorPesanPacking" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PEKINGEUN !!
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalPacking()">Batal</button>
                <button type="submit" id="submitButtonPacking">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="historyPane">
    <div class="history_pane_box">
        
        <h3 id="historyTitle">Riwayat Pekerjaan: Print</h3>

        <div class="history_pane_box_header">
            <h2 id="selectedJobNameDisplay">
                Semua Job
            </h2>
            <div class="history_pane_box_header_select">
                <select id="historyJobSelect" class="input_form">
                    <option value="">Semua Job</option>
                    @foreach ($orders as $o)
                        <option value="{{ $o->id }}">{{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} </option>
                    @endforeach
                </select>
            </div>
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
                        <select id="select2-nama-konsumen" name="nama_konsumen" required>
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

<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiSetting').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectSetting').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiSetting').on('select2:select', function (e) {

            let selectedOptionSetting = e.params.data.element;
            let lastJobIdSetting = $(selectedOptionSetting).data('last-job-id-setting');

            if (lastJobIdSetting) {
                $('#jobSelectSetting').val(lastJobIdSetting).trigger('change');
            } else {
                $('#jobSelectSetting').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawai').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectPrint').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawai').on('select2:select', function (e) {

            let selectedOption = e.params.data.element;
            let lastJobId = $(selectedOption).data('last-job-id');

            if (lastJobId) {
                $('#jobSelectPrint').val(lastJobId).trigger('change');
            } else {
                $('#jobSelectPrint').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiPress').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectPress').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiPress').on('select2:select', function (e) {

            let selectedOptionPress = e.params.data.element;
            let lastJobIdPress = $(selectedOptionPress).data('last-job-id-press');

            if (lastJobIdPress) {
                $('#jobSelectPress').val(lastJobIdPress).trigger('change');
            } else {
                $('#jobSelectPress').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiCutting').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectCutting').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiCutting').on('select2:select', function (e) {

            let selectedOptionCutting = e.params.data.element;
            let lastJobIdCutting = $(selectedOptionCutting).data('last-job-id-cutting');

            if (lastJobIdCutting) {
                $('#jobSelectCutting').val(lastJobIdCutting).trigger('change');
            } else {
                $('#jobSelectCutting').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiJahit').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectJahit').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiJahit').on('select2:select', function (e) {

            let selectedOptionJahit = e.params.data.element;
            let lastJobIdJahit = $(selectedOptionJahit).data('last-job-id-jahit');

            if (lastJobIdJahit) {
                $('#jobSelectJahit').val(lastJobIdJahit).trigger('change');
            } else {
                $('#jobSelectJahit').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiFinishing').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectFinishing').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiFinishing').on('select2:select', function (e) {

            let selectedOptionFinishing = e.params.data.element;
            let lastJobIdFinishing = $(selectedOptionFinishing).data('last-job-id-finishing');

            if (lastJobIdFinishing) {
                $('#jobSelectFinishing').val(lastJobIdFinishing).trigger('change');
            } else {
                $('#jobSelectFinishing').val('').trigger('change');
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiPacking').select2({
            placeholder: "Pilih Pegawai",
            allowClear: false,
            width: '100%'
        });

        // Aktifkan select2 pada job
        $('#jobSelectPacking').select2({
            placeholder: "Pilih Job Untuk Input Progress",
            width: '100%'
        });

        // Ketika pegawai dipilih → otomatis ubah select Job
        $('#pilihPegawaiPacking').on('select2:select', function (e) {

            let selectedOptionPacking = e.params.data.element;
            let lastJobIdPacking = $(selectedOptionPacking).data('last-job-id-packing');

            if (lastJobIdPacking) {
                $('#jobSelectPacking').val(lastJobIdPacking).trigger('change');
            } else {
                $('#jobSelectPacking').val('').trigger('change');
            }
        });

    });
</script>

<script>
    const jenisOrderMap = @json(
        $ordersForMap->mapWithKeys(function($o) {
            return [
                $o->id => optional($o->jenisOrder)->nama_jenis ?? '-'
            ];
        })
    ) || {};
</script>

<script>
    // Data dari Laravel
    const allHistories = @json($allHistories ?? []) || []; // Dapatkan semua riwayat
    const allPegawais = @json($pegawais ?? []) || [];
    const jobData = @json($orders ?? []) || [];
    
    // Debug: log data ke console
    console.log('allHistories loaded:', allHistories.length, 'items');
    console.log('allPegawais loaded:', allPegawais.length, 'items');
    console.log('jobData loaded:', jobData.length, 'items');
    const customerNameMap = {};
    jobData.forEach(job => {
        customerNameMap[job.id] = job.nama_konsumen;
    });
    
    // Konversi array pegawai menjadi objek map ID => Nama untuk lookup cepat
    const pegawaiMap = {};
    allPegawais.forEach(p => {
        pegawaiMap[p.id] = p.nama;
    });

    let currentJobId = null; // ID Job yang sedang aktif di filter
    let currentKategori = null; // Kategori yang sedang aktif (Print/Press/etc)

    function openHistory(kategori, buttonElement) {
        const pane = document.getElementById('historyPane');
        const title = document.getElementById('historyTitle');
        
        // 1. Toggle: jika sudah aktif dan kategori sama, tutup
        if (pane.classList.contains('active') && currentKategori === kategori) {
            pane.classList.remove('active');
            return;
        }

        // 2. Set Global State & Tampilan
        currentKategori = kategori;
        title.textContent = `Riwayat Pekerjaan: ${kategori}`;
        pane.classList.add('active');

        // 3. Muat Data Default (untuk kategori ini)
        filterAndDisplayHistory();
    }

    function filterAndDisplayHistory() {
        const tableBody = document.getElementById('historyTableBody');
        const selectedJobNameDisplay = document.getElementById('selectedJobNameDisplay');
        const jobSelect = document.getElementById('historyJobSelect');

        const dateOptions = {
            weekday: 'long',    // 'Kamis'
            day: 'numeric',     // '11'
            month: 'long',      // 'November'
            year: 'numeric',    // '2025'
            hour: '2-digit',    // '14'
            minute: '2-digit',  // '46'
            second: '2-digit',  // '17'
            timeZone: 'Asia/Jakarta' // Menggunakan WIB (Waktu Indonesia Barat)
        };

        if (!selectedJobNameDisplay) {
            console.error("Elemen selectedJobNameDisplay tidak ditemukan di HTML!");
            // Lanjutkan tanpa mengupdate display jika elemen hilang
        }
        
        const selectedOption = jobSelect.options[jobSelect.selectedIndex];

        let rawText = selectedOption.textContent;
    
        // Hapus semua teks yang berada setelah tanda kurung buka '(' (termasuk spasi)
        let jobName = rawText.split('(')[0].trim();

        if (jobName === "") {
            jobName = rawText.trim();
        }

        if (selectedJobNameDisplay) {
            selectedJobNameDisplay.textContent = jobName;
        }
        
        const selectedJobId = jobSelect.value ? parseInt(jobSelect.value) : null;

        // 1. Filter Data
        let filteredHistories = allHistories.filter(history => {
            const matchesCategory = history.jenis_pekerjaan === currentKategori;
            const matchesJob = selectedJobId ? history.order_id === selectedJobId : true; // Tampilkan semua jika tidak ada Job terpilih
            return matchesCategory && matchesJob;
        });

        // Urutkan berdasarkan waktu terbaru
        filteredHistories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // 2. Render Tabel
        tableBody.innerHTML = '';
        
        if (filteredHistories.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-white font-medium">Tidak ada riwayat untuk ${currentKategori}.</td></tr>`;
            return;
        }

        filteredHistories.forEach(history => {
            const pegawaiName = pegawaiMap[history.pegawai_id] || 'Unknown';

            const orderId = history.order_id ? parseInt(history.order_id) : null;
            // Perbaikan pada baris customerName: Tambahkan fallback nilai
            const customerName = orderId ? customerNameMap[orderId] || `ID ${orderId}` : 'N/A';

            const namaJenis = orderId ? jenisOrderMap[orderId] || '-' : '-';

            const formattedTimestamp = new Date(history.created_at).toLocaleString('id-ID', dateOptions);
            
            const row = `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${pegawaiName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${history.nama_job_snapshot} ${namaJenis}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${customerName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium text-center"><div class="w-28">${history.jenis_pekerjaan}</div></td>
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
    let totalData = {
        qty: "{{ $totals?->total_qty }}",
        hari: "{{ $totals?->total_hari }}",
        deadline: "{{ $totals?->total_deadline }}",
        nama_job: "Total Quantity",
        setting: "{{ $totals?->total_setting }}",
        print: "{{ $totals?->total_print }}",
        press: "{{ $totals?->total_press }}",
        cutting: "{{ $totals?->total_cutting }}",
        jahit: "{{ $totals?->total_jahit }}",
        finishing: "{{ $totals?->total_finishing }}",
        packing: "{{ $totals?->total_packing }}",
        sisa_print: "{{ $totals?->total_sisa_print }}",
        sisa_press: "{{ $totals?->total_sisa_press }}",
        sisa_cutting: "{{ $totals?->total_sisa_cutting }}",
        sisa_jahit: "{{ $totals?->total_sisa_jahit }}"
        sisa_finishing: "{{ $totals?->total_sisa_finishing }}"
        sisa_packing: "{{ $totals?->total_sisa_packing }}"
        sisa_setting: "{{ $totals?->total_sisa_setting }}"
    };
</script>

<script>
    $(document).ready(function() {
        // --- INISIALISASI SELECT2 ---
        $('#selectJob').select2({
            placeholder: "Pilih Job Lain...", 
            allowClear: false, 
            width: '100%',
        });

        // --- LISTENER PENGALIHAN (REDIRECTION) ---
        // Gunakan event Select2:select untuk kepastian
        $('#selectJob').on('select2:select', function (e) {
            // Nilai (slug) diambil dari data.id
            let slug = e.params.data.id; 

            if (slug === 'Progress Keseluruhan') {
                window.location.href = '/dashboard';
            } else {
                // Pastikan slug tidak kosong
                if (slug) {
                    window.location.href = '/dashboard/' + slug;
                }
            }
        });
        
        // --- LISTENER UNTUK ALLOW CLEAR (PENTING) ---
        // Tambahkan listener ini jika Anda ingin menangani klik tombol Hapus (Clear)
        $('#selectJob').on('select2:clear', function (e) {
             window.location.href = '/dashboard';
        });
        
    });
</script>

<script>
    const jobDataSetting = @json($orders);

    // --- UPDATE DETAIL (dipanggil saat pilih job) ---
    function updateJobDetailsSetting() {

        const selectedJobIdSetting = document.getElementById('jobSelectSetting').value;

        const qtyInputSetting = document.getElementById('qtySettingInput');
        const submitButtonSetting = document.getElementById('submitButtonSetting');
        const errorSpanSetting = document.getElementById('errorPesanSetting');

        const settingBelumBtn = document.getElementById("settingBelumBtn");
        const settingSelesaiBtn = document.getElementById("settingSelesaiBtn");

        // RESET
        qtyInputSetting.value = "";
        submitButtonSetting.disabled = true;
        errorSpanSetting.classList.add('hidden');

        if (!selectedJobIdSetting) {
            return;
        }

        const job = jobDataSetting.find(j => j.id == selectedJobIdSetting);
        if (!job) return;

        const settingDone = Number(job.setting);  // 0 atau 1

        // Isi hidden input
        qtyInputSetting.value = settingDone;

        // --- SET BUTTON ACTIVE SESUAI DATABASE ---
        if (settingDone === 1) {
            setActiveSetting(settingSelesaiBtn);
            submitButtonSetting.disabled = true; // Tidak bisa ubah lagi
        } else {
            setActiveSetting(settingBelumBtn);
            submitButtonSetting.disabled = true; // Baru bisa submit kalau pilih SELESAI
        }
    }


    // --- FUNGSI SET ACTIVE UNTUK 2 BUTTON ---
    function setActiveSetting(button) {

        const settingBelumBtn = document.getElementById("settingBelumBtn");
        const settingSelesaiBtn = document.getElementById("settingSelesaiBtn");
        const qtySettingInput = document.getElementById("qtySettingInput");
        const submitButtonSetting = document.getElementById("submitButtonSetting");

        // Reset semua tombol
        [settingBelumBtn, settingSelesaiBtn].forEach(btn => {
            btn.classList.remove("red_bg", "green_bg", "text-white");
            btn.classList.add("background_gray_young", "text-gray-700");
        });

        // Ambil value dari tombol yang diklik
        const value = button.dataset.value;

        // Tentukan warna active
        const activeColor = value === "1" ? "green_bg" : "red_bg";

        // Set warna tombol active
        button.classList.remove("background_gray_young", "text-gray-700");
        button.classList.add(activeColor, "text-white");

        // Set input hidden
        qtySettingInput.value = value;

        // Enable submit hanya jika selesai (1)
        submitButtonSetting.disabled = value !== "1";
    }


    // --- SELECT2 & EVENT ---
    $(document).ready(function () {

        $('#pilihPegawaiSetting, #jobSelectSetting').select2({
            placeholder: "Pilih...",
            allowClear: false,
            width: "100%"
        });

        // AUTO PILIH JOB TERAKHIR
        $('#pilihPegawaiSetting').on('select2:select', function (e) {
            let lastJob = $(e.params.data.element).data('last-job-id-setting');
            $("#jobSelectSetting").val(lastJob ?? "").trigger("change");
        });

        // Update detail saat pilih job
        $("#jobSelectSetting").on("change", updateJobDetailsSetting);

        // Load awal
        updateJobDetailsSetting();
    });


    // --- EVENT LISTENER BUTTON ---
    document.addEventListener("DOMContentLoaded", function () {

        const settingBelumBtn = document.getElementById("settingBelumBtn");
        const settingSelesaiBtn = document.getElementById("settingSelesaiBtn");

        // Klik tombol Belum
        settingBelumBtn.addEventListener("click", () => setActiveSetting(settingBelumBtn));

        // Klik tombol Selesai
        settingSelesaiBtn.addEventListener("click", () => setActiveSetting(settingSelesaiBtn));
    });
</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataPrint = @json($orders);

    function updateJobDetailsPrint() {
        const selectElementPrint = document.getElementById('jobSelectPrint');
        const selectedJobIdPrint = selectElementPrint.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const qtyTersediaSpan = document.getElementById('qtyTersedia');
        const printSelesaiSpan = document.getElementById('printSelesai');
        const sisaPrintSpan = document.getElementById('sisaPrint');
        const totalQtySpanPrint = document.getElementById('totalQtySpanPrint');
        const qtyInputPrint = document.getElementById('qtyPrintInput');
        const hasilQtyJenisSpan = document.getElementById('hasilQtyJenis');
        
        // Inisialisasi variabel dengan nilai default untuk digunakan secara aman
        let totalJobQuantityPrint = 0;
        let printDone = 0;
        let sisaQty = 0;
        let rawSisaPrintDB = 0; // Sisa Print dari DB
        let nilaiJenis = 1;
        
        // Reset/Nonaktifkan input secara default
        qtyInputPrint.disabled = true;
        qtyInputPrint.value = 0; 
        hasilQtyJenisSpan.innerText = 0;
        
        // --- AMBIL DATA JOB YANG DIPILIH ---
        if (selectedJobIdPrint) {
            const selectedJobPrint = jobDataPrint.find(job => job.id == selectedJobIdPrint);

            if (selectedJobPrint) {
                totalJobQuantityPrint = parseFloat(selectedJobPrint.qty);
                printDone = parseFloat(selectedJobPrint.print);
                sisaQty = totalJobQuantityPrint;
                rawSisaPrintDB = parseFloat(selectedJobPrint.sisa_print);
                nilaiJenis = parseFloat(selectedJobPrint.jenis_order?.nilai) || 1;
            }
        } 
        // ------------------------------------

        // Hitung sisa nyata untuk input (Total Qty - Print Selesai)
        const remainingToPrint = sisaQty - printDone; 

        // Set Data Attribute untuk Validasi checkMaxQtyPrint
        qtyInputPrint.setAttribute('data-max-value-print', sisaQty);
        printSelesaiSpan.setAttribute('data-print-done', printDone);
        qtyInputPrint.setAttribute('data-nilai-jenis', nilaiJenis);

        // --- LOGIKA AKTIVASI INPUT ---
        if (remainingToPrint > 0) {
            // Aktifkan Input 
            qtyInputPrint.disabled = false;
            // Batas maksimum input yang diizinkan adalah SISA NYATA
            qtyInputPrint.max = remainingToPrint; 
        } else {
            // Jika 100% Selesai, Nonaktifkan
            qtyInputPrint.disabled = true;
            qtyInputPrint.value = 0;
        }
        // ----------------------------------------
        
        // --- Update Tampilan ---
        totalQtySpanPrint.innerHTML = `Qty: ${totalJobQuantityPrint}`;
        qtyTersediaSpan.innerHTML = `Prineun: ${remainingToPrint}`; // Total Job Qty
        printSelesaiSpan.innerHTML = `Print selesai: ${printDone}`;
        sisaPrintSpan.innerHTML = `Sisa Print: ${rawSisaPrintDB}`; // Sisa DB
        
        updateHasilQtyJenis(); 
        checkMaxQtyPrint();
    }

    function updateHasilQtyJenis() {
        const qtyInputPrint = document.getElementById('qtyPrintInput');
        const hasilQtyJenisSpan = document.getElementById('hasilQtyJenis');

        const inputQty = parseFloat(qtyInputPrint.value) || 0;
        const nilaiJenis = parseFloat(qtyInputPrint.getAttribute('data-nilai-jenis')) || 1;

        const hasil = inputQty * nilaiJenis;

        // Tampilkan proses perkaliannya
        hasilQtyJenisSpan.innerText = `Total Lembar : ${inputQty} × ${nilaiJenis} = ${hasil}`;
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectPrint = document.getElementById('jobSelectPrint');
        if (jobSelectPrint) {
            jobSelectPrint.addEventListener('change', updateJobDetailsPrint);
        }
        updateJobDetailsPrint();

        const qtyInputPrint = document.getElementById('qtyPrintInput');
        if (qtyInputPrint) {
            // Listener ON INPUT (Real-time)
            qtyInputPrint.addEventListener('input', checkMaxQtyPrint);
        }
    });

    function checkMaxQtyPrint() {
        const qtyInputPrint = document.getElementById('qtyPrintInput');
        const errorSpanPrint = document.getElementById('errorPesanPrint');
        const printSelesaiSpan = document.getElementById('printSelesai');

        const submitButtonPrint = document.getElementById('submitButtonPrint');
        
        const sisaPrintTotal = parseFloat(qtyInputPrint.getAttribute('data-max-value-print')) || 0; 

        // 2. Ambil Press yang Sudah Selesai
        const printDoneSaatIni = parseFloat(printSelesaiSpan.getAttribute('data-print-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruPrint = parseFloat(qtyInputPrint.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressPrintBaru = printDoneSaatIni + inputBaruPrint;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressPrintBaru > sisaPrintTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanPrint.classList.remove('hidden');
            qtyInputPrint.classList.add('border-red-500');
            qtyInputPrint.classList.remove('border-gray-300');

            submitButtonPrint.disabled = true;
        } else {
            // Jika valid
            errorSpanPrint.classList.add('hidden');
            qtyInputPrint.classList.remove('border-red-500');
            qtyInputPrint.classList.add('border-gray-300');

            submitButtonPrint.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputPrint.disabled && inputBaruPrint > 0) {
            qtyInputPrint.value = 0;
        }

        updateHasilQtyJenis();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const jobSelectPrint = document.getElementById("jobSelectPrint");
        const qtyInputPrint = document.getElementById('qtyPrintInput');

        jobSelectPrint.addEventListener("change", function () {
            const selectedOptionPrint = jobSelectPrint.options[jobSelectPrint.selectedIndex];
            const settingValuePrint = selectedOptionPrint.dataset.setting;

            if (settingValuePrint === "0") {
                alert(`${selectedOptionPrint.dataset.text} belum di setting`);
                jobSelectPrint.value = ""; // reset kembali
            }
        });
        qtyInputPrint.addEventListener('input', checkMaxQtyPrint);

        updateJobDetailsPrint();
    });


    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsPrint = $('#jobSelectPrint');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsPrint.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsPrint.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsPrint(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsPrint(); 

    });

</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataPress = @json($orders);

    function updateJobDetailsPress() {
        const selectElementPress = document.getElementById('jobSelectPress');
        const selectedJobIdPress = selectElementPress.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const printTersediaSPan = document.getElementById('printTersedia');
        const pressSelesaiSpan = document.getElementById('pressSelesai');
        const sisaPressSpan = document.getElementById('sisaPress');
        const qtyInputPress = document.getElementById('qtyPressInput');

        const totalQtySpanPress = document.getElementById('totalQtySpanPress');
        const hasilQtyJenisSpanPress = document.getElementById('hasilQtyJenisPress');
        
        // Reset/Nonaktifkan input secara default
        qtyInputPress.disabled = true;
        qtyInputPress.value = 0; // Set value ke 0
        hasilQtyJenisSpanPress.innerText = 0;

        if (!selectedJobIdPress) {
            printTersediaSPan.innerHTML = 'Presseun: 0';
            pressSelesaiSpan.innerHTML = 'Hasil Press: 0';
            totalQtySpanPress.innerHTML = 'Qty: 0';
            sisaPressSpan.innerHTML = 'Sisa Press: 0';
            return;
        }

        const selectedJobPress = jobDataPress.find(job => job.id == selectedJobIdPress);

        if (selectedJobPress) {
            const totalJobQuantityPress = parseFloat(selectedJobPress.qty);

            const sisaPrint = parseFloat(selectedJobPress.print);
            const pressDone = parseFloat(selectedJobPress.press);

            const rawSisaPressDB = parseFloat(selectedJobPress.sisa_press);

            const nilaiJenisPress = parseFloat(selectedJobPress.jenis_order?.nilai) || 1;

            const printAvailableForPress = sisaPrint - pressDone;

            qtyInputPress.setAttribute('data-max-value-press', sisaPrint);
            pressSelesaiSpan.setAttribute('data-press-done', pressDone);
            qtyInputPress.setAttribute('data-nilai-press', nilaiJenisPress);

            // --- Terapkan Logika Disbled di SINI ---
            if (printAvailableForPress > 0) {
                // Jika sisa > 0, aktifkan input
                qtyInputPress.disabled = false;
                qtyInputPress.max = printAvailableForPress;
            } else {
                // Jika sisa = 0, nonaktifkan
                qtyInputPress.disabled = true;
                qtyInputPress.value = 0;
            }
            // ----------------------------------------
            
            // --- Update Tampilan ---
            totalQtySpanPress.innerHTML = `Qty: ${totalJobQuantityPress}`;
            printTersediaSPan.innerHTML = `Presseun: ${printAvailableForPress}`;
            pressSelesaiSpan.innerHTML = `Hasil Press: ${pressDone}`;
            sisaPressSpan.innerHTML = `Sisa Press: ${rawSisaPressDB}`;

            checkMaxQtyPress();
            
        } else {
            qtyInputPress.setAttribute('data-max-value-press', 0);
            printTersediaSPan.innerHTML = 'Data tidak ditemukan.';
            pressSelesaiSpan.innerHTML = 'Data tidak ditemukan.';
            sisaPressSpan.innerHTML = 'Sisa Jahit: 0';
            totalQtySpanPress.innerHTML = 'Total: 0';

            updateHasilQtyJenisPress(); 
            checkMaxQtyPress();
        }
    }

    function updateHasilQtyJenisPress() {
        const qtyInputPress = document.getElementById('qtyPressInput');
        const hasilQtyJenisSpanPress = document.getElementById('hasilQtyJenisPress');

        const inputQtyPress = parseFloat(qtyInputPress.value) || 0;
        const nilaiJenisPress = parseFloat(qtyInputPress.getAttribute('data-nilai-press')) || 1;

        const hasilPress = inputQtyPress * nilaiJenisPress;

        hasilQtyJenisSpanPress.innerText = `Total Lembar : ${inputQtyPress} × ${nilaiJenisPress} = ${hasilPress}`;
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectPress = document.getElementById('jobSelectPress');
        if (jobSelectPress) {
            jobSelectPress.addEventListener('change', updateJobDetailsPress);
        }
        updateJobDetailsPress();

        const qtyInputPress = document.getElementById('qtyPressInput');
        if (qtyInputPress) {
            // Listener ON INPUT (Real-time)
            qtyInputPress.addEventListener('input', checkMaxQtyPress);
        }
    });

    function checkMaxQtyPress() {
        const qtyInputPress = document.getElementById('qtyPressInput');
        const errorSpanPress = document.getElementById('errorPesanPress');
        const pressSelesaiSpan = document.getElementById('pressSelesai');

        const submitButtonPress = document.getElementById('submitButtonPress');
        
        const sisaPressTotal = parseFloat(qtyInputPress.getAttribute('data-max-value-press')) || 0; 

        // 2. Ambil Press yang Sudah Selesai
        const pressDoneSaatIni = parseFloat(pressSelesaiSpan.getAttribute('data-press-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruPress = parseFloat(qtyInputPress.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressPressBaru = pressDoneSaatIni + inputBaruPress;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressPressBaru > sisaPressTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanPress.classList.remove('hidden');
            qtyInputPress.classList.add('border-red-500');
            qtyInputPress.classList.remove('border-gray-300');

            submitButtonPress.disabled = true;
        } else {
            // Jika valid
            errorSpanPress.classList.add('hidden');
            qtyInputPress.classList.remove('border-red-500');
            qtyInputPress.classList.add('border-gray-300');

            submitButtonPress.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputPress.disabled && inputBaruPress > 0) {
            qtyInputPress.value = 0;
        }

        updateHasilQtyJenisPress();
    }

    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsPress = $('#jobSelectPress');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsPress.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsPress.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsPress(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsPress(); 

    });
</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataCutting = @json($orders);

    function updateJobDetailsCutting() {
        const selectElementCutting = document.getElementById('jobSelectCutting');
        const selectedJobIdCutting = selectElementCutting.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const pressTersediaSPan = document.getElementById('pressTersedia');
        const cuttingSelesaiSpan = document.getElementById('cuttingSelesai');
        const sisaCuttingSpan = document.getElementById('sisaCutting');
        const qtyInputCutting = document.getElementById('qtyCuttingInput');

        const totalQtySpanCutting = document.getElementById('totalQtySpanCutting');
        
        // Reset/Nonaktifkan input secara default
        qtyInputCutting.disabled = true;
        qtyInputCutting.value = 0; // Set value ke 0

        if (!selectedJobIdCutting) {
            pressTersediaSPan.innerHTML = 'Cuttingeun: 0';
            cuttingSelesaiSpan.innerHTML = 'Hasil Cutting: 0';
            totalQtySpanCutting.innerHTML = 'Qty: 0';
            sisaCuttingSpan.innerHTML = 'Sisa Cutting: 0';
            return;
        }

        const selectedJobCutting = jobDataCutting.find(job => job.id == selectedJobIdCutting);

        if (selectedJobCutting) {
            const totalJobQuantityCutting = parseFloat(selectedJobCutting.qty);

            const sisaPress = parseFloat(selectedJobCutting.press);
            const cuttingDone = parseFloat(selectedJobCutting.cutting);

            const rawSisaCuttingDB = parseFloat(selectedJobCutting.sisa_cutting);

            const pressAvailableForCutting = sisaPress - cuttingDone;

            qtyInputCutting.setAttribute('data-max-value-cutting', sisaPress);
            cuttingSelesaiSpan.setAttribute('data-cutting-done', cuttingDone);

            // --- Terapkan Logika Disbled di SINI ---
            if (pressAvailableForCutting > 0) {
                // Jika sisa > 0, aktifkan input
                qtyInputCutting.disabled = false;
                qtyInputCutting.max = pressAvailableForCutting;
            } else {
                // Jika sisa = 0, nonaktifkan
                qtyInputCutting.disabled = true;
                qtyInputCutting.value = 0;
            }
            // ----------------------------------------
            
            // --- Update Tampilan ---
            totalQtySpanCutting.innerHTML = `Qty: ${totalJobQuantityCutting}`;
            pressTersediaSPan.innerHTML = `Cuttingeun: ${pressAvailableForCutting}`;
            cuttingSelesaiSpan.innerHTML = `Hasil Cutting: ${cuttingDone}`;
            sisaCuttingSpan.innerHTML = `Sisa Cutting: ${rawSisaCuttingDB}`;

            checkMaxQtyCutting();
            
        } else {
            qtyInputCutting.setAttribute('data-max-value-cutting', 0);
            pressTersediaSPan.innerHTML = 'Data tidak ditemukan.';
            cuttingSelesaiSpan.innerHTML = 'Data tidak ditemukan.';
            sisaCuttingSpan.innerHTML = 'Sisa Cutting: 0';
            totalQtySpanCutting.innerHTML = 'Total: 0';
            checkMaxQtyCutting();
        }
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectCutting = document.getElementById('jobSelectCutting');
        if (jobSelectCutting) {
            jobSelectCutting.addEventListener('change', updateJobDetailsCutting);
        }
        updateJobDetailsCutting();

        const qtyInputCutting = document.getElementById('qtyCuttingInput');
        if (qtyInputCutting) {
            // Listener ON INPUT (Real-time)
            qtyInputCutting.addEventListener('input', checkMaxQtyCutting);
        }
    });

    function checkMaxQtyCutting() {
        const qtyInputCutting = document.getElementById('qtyCuttingInput');
        const errorSpanCutting = document.getElementById('errorPesanCutting');
        const cuttingSelesaiSpan = document.getElementById('cuttingSelesai');

        const submitButtonCutting = document.getElementById('submitButtonCutting');
        
        const sisaCuttingTotal = parseFloat(qtyInputCutting.getAttribute('data-max-value-cutting')) || 0; 

        // 2. Ambil Cutting yang Sudah Selesai
        const cuttingDoneSaatIni = parseFloat(cuttingSelesaiSpan.getAttribute('data-cutting-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruCutting = parseFloat(qtyInputCutting.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressCuttingBaru = cuttingDoneSaatIni + inputBaruCutting;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressCuttingBaru > sisaCuttingTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanCutting.classList.remove('hidden');
            qtyInputCutting.classList.add('border-red-500');
            qtyInputCutting.classList.remove('border-gray-300');

            submitButtonCutting.disabled = true;
        } else {
            // Jika valid
            errorSpanCutting.classList.add('hidden');
            qtyInputCutting.classList.remove('border-red-500');
            qtyInputCutting.classList.add('border-gray-300');

            submitButtonCutting.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputCutting.disabled && inputBaruCutting > 0) {
            qtyInputCutting.value = 0;
        }
    }

    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsCutting = $('#jobSelectCutting');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsCutting.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsCutting.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsCutting(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsCutting(); 

    });
</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataJahit = @json($orders);

    function updateJobDetailsJahit() {
        const selectElementJahit = document.getElementById('jobSelectJahit');
        const selectedJobIdJahit = selectElementJahit.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const cuttingTersediaSPan = document.getElementById('cuttingTersedia');
        const jahitSelesaiSpan = document.getElementById('jahitSelesai');
        const sisaJahitSpan = document.getElementById('sisaJahit');
        const qtyInputJahit = document.getElementById('qtyJahitInput');

        const totalQtySpanJahit = document.getElementById('totalQtySpanJahit');
        
        // Reset/Nonaktifkan input secara default
        qtyInputJahit.disabled = true;
        qtyInputJahit.value = 0; // Set value ke 0

        if (!selectedJobIdJahit) {
            cuttingTersediaSPan.innerHTML = 'Jahiteun: 0';
            jahitSelesaiSpan.innerHTML = 'Hasil Jahit: 0';
            totalQtySpanJahit.innerHTML = 'Qty: 0';
            sisaJahitSpan.innerHTML = 'Sisa Jahit: 0';
            return;
        }

        const selectedJobJahit = jobDataJahit.find(job => job.id == selectedJobIdJahit);

        if (selectedJobJahit) {
            const totalJobQuantityJahit = parseFloat(selectedJobJahit.qty);

            const sisaCutting = parseFloat(selectedJobJahit.cutting);
            const jahitDone = parseFloat(selectedJobJahit.jahit);

            const rawSisaJahitDB = parseFloat(selectedJobJahit.sisa_jahit);

            const cuttingAvailableForJahit = sisaCutting - jahitDone;

            qtyInputJahit.setAttribute('data-max-value-jahit', sisaCutting);
            jahitSelesaiSpan.setAttribute('data-jahit-done', jahitDone);

            // --- Terapkan Logika Disbled di SINI ---
            if (cuttingAvailableForJahit > 0) {
                // Jika sisa > 0, aktifkan input
                qtyInputJahit.disabled = false;
                qtyInputJahit.max = cuttingAvailableForJahit;
            } else {
                // Jika sisa = 0, nonaktifkan
                qtyInputJahit.disabled = true;
                qtyInputJahit.value = 0;
            }
            // ----------------------------------------
            
            // --- Update Tampilan ---
            totalQtySpanJahit.innerHTML = `Qty: ${totalJobQuantityJahit}`;
            cuttingTersediaSPan.innerHTML = `Jahiteun: ${cuttingAvailableForJahit}`;
            jahitSelesaiSpan.innerHTML = `Hasil Jahit: ${jahitDone}`;
            sisaJahitSpan.innerHTML = `Sisa Jahit: ${rawSisaJahitDB}`;

            checkMaxQtyJahit();
            
        } else {
            qtyInputJahit.setAttribute('data-max-value-jahit', 0);
            cuttingTersediaSPan.innerHTML = 'Data tidak ditemukan.';
            jahitSelesaiSpan.innerHTML = 'Data tidak ditemukan.';
            sisaJahitSpan.innerHTML = 'Sisa Jahit: 0';
            totalQtySpanJahit.innerHTML = 'Total: 0';
            checkMaxQtyJahit();
        }
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectJahit = document.getElementById('jobSelectJahit');
        if (jobSelectJahit) {
            jobSelectJahit.addEventListener('change', updateJobDetailsJahit);
        }
        updateJobDetailsJahit();

        const qtyInputJahit = document.getElementById('qtyJahitInput');
        if (qtyInputJahit) {
            // Listener ON INPUT (Real-time)
            qtyInputJahit.addEventListener('input', checkMaxQtyJahit);
        }
    });

    function checkMaxQtyJahit() {
        const qtyInputJahit = document.getElementById('qtyJahitInput');
        const errorSpanJahit = document.getElementById('errorPesanJahit');
        const jahitSelesaiSpan = document.getElementById('jahitSelesai');

        const submitButtonJahit = document.getElementById('submitButtonJahit');
        
        const sisaJahitTotal = parseFloat(qtyInputJahit.getAttribute('data-max-value-jahit')) || 0; 

        // 2. Ambil Press yang Sudah Selesai
        const jahitDoneSaatIni = parseFloat(jahitSelesaiSpan.getAttribute('data-jahit-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruJahit = parseFloat(qtyInputJahit.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressJahitBaru = jahitDoneSaatIni + inputBaruJahit;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressJahitBaru > sisaJahitTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanJahit.classList.remove('hidden');
            qtyInputJahit.classList.add('border-red-500');
            qtyInputJahit.classList.remove('border-gray-300');

            submitButtonJahit.disabled = true;
        } else {
            // Jika valid
            errorSpanJahit.classList.add('hidden');
            qtyInputJahit.classList.remove('border-red-500');
            qtyInputJahit.classList.add('border-gray-300');

            submitButtonJahit.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputJahit.disabled && inputBaruJahit > 0) {
            qtyInputJahit.value = 0;
        }
    }

    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsJahit = $('#jobSelectJahit');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsJahit.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsJahit.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsJahit(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsJahit(); 

    });
</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataFinishing = @json($orders);

    function updateJobDetailsFinishing() {
        const selectElementFinishing = document.getElementById('jobSelectFinishing');
        const selectedJobIdFinishing = selectElementFinishing.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const jahitTersediaSPan = document.getElementById('jahitTersedia');
        const finishingSelesaiSpan = document.getElementById('finishingSelesai');
        const sisaFinishingSpan = document.getElementById('sisaFinishing');
        const qtyInputFinishing = document.getElementById('qtyFinishingInput');

        const totalQtySpanFinishing = document.getElementById('totalQtySpanFinishing');
        
        // Reset/Nonaktifkan input secara default
        qtyInputFinishing.disabled = true;
        qtyInputFinishing.value = 0; // Set value ke 0

        if (!selectedJobIdFinishing) {
            jahitTersediaSPan.innerHTML = 'Pinisingeun: 0';
            finishingSelesaiSpan.innerHTML = 'Hasil Finishing: 0';
            totalQtySpanFinishing.innerHTML = 'Qty: 0';
            sisaFinishingSpan.innerHTML = 'Sisa Finishing: 0';
            return;
        }

        const selectedJobFinishing = jobDataFinishing.find(job => job.id == selectedJobIdFinishing);

        if (selectedJobFinishing) {
            const totalJobQuantityFinishing = parseFloat(selectedJobFinishing.qty);

            const sisaJahit = parseFloat(selectedJobFinishing.jahit);
            const finishingDone = parseFloat(selectedJobFinishing.finishing);

            const rawSisaFinishingDB = parseFloat(selectedJobFinishing.sisa_finishing);

            const jahitAvailableForFinishing = sisaJahit - finishingDone;

            qtyInputFinishing.setAttribute('data-max-value-finishing', sisaJahit);
            finishingSelesaiSpan.setAttribute('data-finishing-done', finishingDone);

            // --- Terapkan Logika Disbled di SINI ---
            if (jahitAvailableForFinishing > 0) {
                // Jika sisa > 0, aktifkan input
                qtyInputFinishing.disabled = false;
                qtyInputFinishing.max = jahitAvailableForFinishing;
            } else {
                // Jika sisa = 0, nonaktifkan
                qtyInputFinishing.disabled = true;
                qtyInputFinishing.value = 0;
            }
            // ----------------------------------------
            
            // --- Update Tampilan ---
            totalQtySpanFinishing.innerHTML = `Qty: ${totalJobQuantityFinishing}`;
            jahitTersediaSPan.innerHTML = `Pinisingeun: ${jahitAvailableForFinishing}`;
            finishingSelesaiSpan.innerHTML = `Hasil Finishing: ${finishingDone}`;
            sisaFinishingSpan.innerHTML = `Sisa Finishing: ${rawSisaFinishingDB}`;

            checkMaxQtyFinishing();
            
        } else {
            qtyInputFinishing.setAttribute('data-max-value-finishing', 0);
            jahitTersediaSPan.innerHTML = 'Data tidak ditemukan.';
            finishingSelesaiSpan.innerHTML = 'Data tidak ditemukan.';
            sisaFinishingSpan.innerHTML = 'Sisa Finishing: 0';
            totalQtySpanFinishing.innerHTML = 'Total: 0';
            checkMaxQtyFinishing();
        }
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectFinishing = document.getElementById('jobSelectFinishing');
        if (jobSelectFinishing) {
            jobSelectFinishing.addEventListener('change', updateJobDetailsFinishing);
        }
        updateJobDetailsFinishing();

        const qtyInputFinishing = document.getElementById('qtyFinishingInput');
        if (qtyInputFinishing) {
            // Listener ON INPUT (Real-time)
            qtyInputFinishing.addEventListener('input', checkMaxQtyFinishing);
        }
    });

    function checkMaxQtyFinishing() {
        const qtyInputFinishing = document.getElementById('qtyFinishingInput');
        const errorSpanFinishing = document.getElementById('errorPesanFinishing');
        const finishingSelesaiSpan = document.getElementById('finishingSelesai');

        const submitButtonFinishing = document.getElementById('submitButtonFinishing');
        
        const sisaFinishingTotal = parseFloat(qtyInputFinishing.getAttribute('data-max-value-finishing')) || 0; 

        // 2. Ambil Press yang Sudah Selesai
        const finishingDoneSaatIni = parseFloat(finishingSelesaiSpan.getAttribute('data-finishing-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruFinishing = parseFloat(qtyInputFinishing.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressFinishingBaru = finishingDoneSaatIni + inputBaruFinishing;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressFinishingBaru > sisaFinishingTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanFinishing.classList.remove('hidden');
            qtyInputFinishing.classList.add('border-red-500');
            qtyInputFinishing.classList.remove('border-gray-300');

            submitButtonFinishing.disabled = true;
        } else {
            // Jika valid
            errorSpanFinishing.classList.add('hidden');
            qtyInputFinishing.classList.remove('border-red-500');
            qtyInputFinishing.classList.add('border-gray-300');

            submitButtonFinishing.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputFinishing.disabled && inputBaruFinishing > 0) {
            qtyInputFinishing.value = 0;
        }
    }

    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsFinishing = $('#jobSelectFinishing');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsFinishing.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsFinishing.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsFinishing(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsFinishing(); 

    });
</script>

<script>
    // Data Job dari Laravel yang sudah di JSON-kan (asumsi ini sudah ada)
    const jobDataPacking = @json($orders);

    function updateJobDetailsPacking() {
        const selectElementPacking = document.getElementById('jobSelectPacking');
        const selectedJobIdPacking = selectElementPacking.value;
        
        // Dapatkan elemen SPAN dan INPUT QTY
        const finishingTersediaSPan = document.getElementById('finishingTersedia');
        const packingSelesaiSpan = document.getElementById('packingSelesai');
        const sisaPackingSpan = document.getElementById('sisaPacking');
        const qtyInputPacking = document.getElementById('qtyPackingInput');

        const totalQtySpanPacking = document.getElementById('totalQtySpanPacking');
        
        // Reset/Nonaktifkan input secara default
        qtyInputPacking.disabled = true;
        qtyInputPacking.value = 0; // Set value ke 0

        if (!selectedJobIdPacking) {
            finishingTersediaSPan.innerHTML = 'Pekingeun: 0';
            packingSelesaiSpan.innerHTML = 'Hasil Packing: 0';
            totalQtySpanPacking.innerHTML = 'Qty: 0';
            sisaPackingSpan.innerHTML = 'Sisa Packing: 0';
            return;
        }

        const selectedJobPacking = jobDataPacking.find(job => job.id == selectedJobIdPacking);

        if (selectedJobPacking) {
            const totalJobQuantityPacking = parseFloat(selectedJobPacking.qty);

            const sisaFinishing = parseFloat(selectedJobPacking.finishing);
            const packingDone = parseFloat(selectedJobPacking.packing);

            const rawSisaPackingDB = parseFloat(selectedJobPacking.sisa_packing);

            const finishingAvailableForPacking = sisaFinishing - packingDone;

            qtyInputPacking.setAttribute('data-max-value-packing', sisaFinishing);
            packingSelesaiSpan.setAttribute('data-packing-done', packingDone);

            // --- Terapkan Logika Disbled di SINI ---
            if (finishingAvailableForPacking > 0) {
                // Jika sisa > 0, aktifkan input
                qtyInputPacking.disabled = false;
                qtyInputPacking.max = finishingAvailableForPacking;
            } else {
                // Jika sisa = 0, nonaktifkan
                qtyInputPacking.disabled = true;
                qtyInputPacking.value = 0;
            }
            // ----------------------------------------
            
            // --- Update Tampilan ---
            totalQtySpanPacking.innerHTML = `Qty: ${totalJobQuantityPacking}`;
            finishingTersediaSPan.innerHTML = `Pekingeun: ${finishingAvailableForPacking}`;
            packingSelesaiSpan.innerHTML = `Hasil Packing: ${packingDone}`;
            sisaPackingSpan.innerHTML = `Sisa Packing: ${rawSisaPackingDB}`;

            checkMaxQtyPacking();
            
        } else {
            qtyInputPacking.setAttribute('data-max-value-packing', 0);
            finishingTersediaSPan.innerHTML = 'Data tidak ditemukan.';
            packingSelesaiSpan.innerHTML = 'Data tidak ditemukan.';
            sisaPackingSpan.innerHTML = 'Sisa Packing: 0';
            totalQtySpanPacking.innerHTML = 'Total: 0';
            checkMaxQtyPacking();
        }
    }

    // Event Listener (Biarkan sama)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelectPacking = document.getElementById('jobSelectPacking');
        if (jobSelectPacking) {
            jobSelectPacking.addEventListener('change', updateJobDetailsPacking);
        }
        updateJobDetailsPacking();

        const qtyInputPacking = document.getElementById('qtyPackingInput');
        if (qtyInputPacking) {
            // Listener ON INPUT (Real-time)
            qtyInputPacking.addEventListener('input', checkMaxQtyPacking);
        }
    });

    function checkMaxQtyPacking() {
        const qtyInputPacking = document.getElementById('qtyPackingInput');
        const errorSpanPacking = document.getElementById('errorPesanPacking');
        const packingSelesaiSpan = document.getElementById('packingSelesai');

        const submitButtonPacking = document.getElementById('submitButtonPacking');
        
        const sisaPackingTotal = parseFloat(qtyInputPacking.getAttribute('data-max-value-packing')) || 0; 

        // 2. Ambil Press yang Sudah Selesai
        const packingDoneSaatIni = parseFloat(packingSelesaiSpan.getAttribute('data-packing-done')) || 0; 
        
        // 3. Ambil Input Baru
        const inputBaruPacking = parseFloat(qtyInputPacking.value) || 0;

        // 4. Hitung Total Progress Press Baru (Sudah Selesai + Input Baru)
        const totalProgressPackingBaru = packingDoneSaatIni + inputBaruPacking;
        
        // --- LOGIKA VALIDASI UTAMA ---
        
        if (totalProgressPackingBaru > sisaPackingTotal) {
            // Jika Total Press (Lama + Baru) MELEBIHI Total Print (Sisa Print Total)
            errorSpanPacking.classList.remove('hidden');
            qtyInputPacking.classList.add('border-red-500');
            qtyInputPacking.classList.remove('border-gray-300');

            submitButtonPacking.disabled = true;
        } else {
            // Jika valid
            errorSpanPacking.classList.add('hidden');
            qtyInputPacking.classList.remove('border-red-500');
            qtyInputPacking.classList.add('border-gray-300');

            submitButtonPacking.disabled = false;
        }

        // --- LOGIKA DISABLE INPUT (Pencegahan double-entry) ---
        // Logika ini menjaga agar input tetap 0 jika dinonaktifkan
        if (qtyInputPacking.disabled && inputBaruPacking > 0) {
            qtyInputPacking.value = 0;
        }
    }

    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        const jobSelectElementsPacking = $('#jobSelectPacking');
        
        // 1. Inisialisasi Select2 pada semua elemen
        jobSelectElementsPacking.select2({
            placeholder: "Pilih Job Untuk Input Progress",
            allowClear: false, 
            width: '100%', 
            
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });

        // 2. Pasang Listener 'change' setelah inisialisasi Select2 selesai
        jobSelectElementsPacking.on('change', function() {
            // Panggil fungsi yang mengupdate semua field form
            updateJobDetailsPacking(); 
        });
        
        // 3. JALANKAN INISIALISASI AWAL
        // Panggil fungsi sekali di akhir $(document).ready() untuk memastikan data awal ditampilkan
        updateJobDetailsPacking(); 

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
            allowClear: false,

            width: '100%'
        });

        $('#select2-nama-konsumen').select2({
            placeholder: "Ketik atau pilih nama konsumen",
            tags: true, // PENTING: Mengizinkan input nilai baru
            allowClear: false,

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

@endsection
