@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="progress_custom h-screen">
    <div class="flex justify-between items-center mb-4">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">
        <!-- DROPDOWN PILIH JOB LAIN  -->
        <div class="grid grid-cols-3 relative z-10 mt-28 ml-4 items-end w-full justify-items-center">
            <button onclick="openModalOrder()"
                class="bg-white me-auto transition-all duration-300 text-gray-800 px-3 py-1 font-medium rounded-md text-sm shadow ">
                Tambah Pesanan
            </button>
            <div class="flex flex-col justify-center items-center gap-0 w-max ">
                <label class="text-sm font-semibold text-white w-36 ml-1">Pilih Job Lain</label>
                <select id="selectJob" class="border border-gray-300 px-3 py-2 text-sm rounded">
                    <option value="Progress Keseluruhan">Progress Keseluruhan</option>
                    @foreach ($ordersSelect as $o)
                        <option value="{{ $o->slug }}">{{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }}  - {{ $o->nama_konsumen }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="progress_custom relative z-10 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white py-6 p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold" id="nama_job">Jumlah Kabeh Pesenan</h2>
                <p id="qty" class="text-3xl font-bold mt-2">{{ $totals->total_qty }}</p>
            </div>

            <div class="bg-white py-6 p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold">Lilana Migawean</h2>
                <p id="hari" class="text-3xl font-bold mt-2">{{ (float) $totals->total_hari }} POE</p>
            </div>

            <div class="bg-white py-6 p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold">Kudu Beresna</h2>
                <p id="deadline" class="text-3xl font-bold mt-2">{{ (float) $totals->total_deadline }} Poe Deui</p>
            </div>
        </div>

        <div class="w-full pb-2">
            <div class="mt-5 grid w-full grid-cols-7 gap-5">

                <!-- Total Setting -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalSetting()" class="bg-gray-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Setting', this)" class="bg-gray-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2"><iconify-icon icon="prime:history"></iconify-icon></button>
                    <h2 class="text-xl font-semibold">Setting</h2>
                    
                    <p class="text-4xl font-bold mt-2" id="setting">
                        @if ($totals->total_sisa_setting == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_setting }}
                        @endif
                    </p>
                    
                    <span class="bg-gray-500 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_setting">
                        {{ $totals->total_sisa_setting == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_setting }}
                    </span>
                    
                    <h5 class="mt-2">{{ $totals->latest_setting_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Print -->
                <div id="cardPrint" class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModal()" class="bg-red-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Print', this)" class="bg-red-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2"><iconify-icon icon="prime:history"></iconify-icon></button>
                    <h2 class="text-xl font-semibold">Print</h2>
                    <p class="text-4xl font-bold mt-2" id="print">
                        @if ($totals->total_sisa_print == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_print }}
                        @endif
                    </p>
                    <span class="bg-red-500 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_print">{{ $totals->total_sisa_print == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_print }}</span>
                    <h5 class="mt-2">{{ $totals->latest_print_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Press -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalPress()" class="bg-green-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Press', this)" class="bg-green-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2">
                        <iconify-icon icon="prime:history"></iconify-icon>
                    </button>
                    <h2 class="text-xl font-semibold">Press</h2>
                    <p class="text-4xl font-bold mt-2" id="press">
                        @if ($totals->total_sisa_press == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_press }}
                        @endif
                    </p>
                    <span class="bg-green-500 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_press">{{ $totals->total_sisa_press == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_press }}</span>
                    <h5 class="mt-2">{{ $totals->latest_press_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Cutting -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalCutting()" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Cutting', this)" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2">
                        <iconify-icon icon="prime:history"></iconify-icon>
                    </button>
                    <h2 class="text-xl font-semibold">Cutting</h2>
                    <p class="text-4xl font-bold mt-2" id="cutting">
                        @if ($totals->total_sisa_cutting == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_cutting }}
                        @endif
                    </p>
                    <span class="bg-yellow-500 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_cutting">{{ $totals->total_sisa_cutting == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_cutting }}</span>
                    <h5 class="mt-2">{{ $totals->latest_cutting_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Jahit -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalJahit()" class="bg-blue-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Jahit', this)" class="bg-blue-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2">
                        <iconify-icon icon="prime:history"></iconify-icon>
                    </button>
                    <h2 class="text-xl font-semibold">Jahit</h2>
                    <p class="text-4xl font-bold mt-2" id="jahit">
                        @if ($totals->total_sisa_jahit == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_jahit }}
                        @endif
                    </p>
                    <span class="bg-blue-400 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_jahit">{{ $totals->total_sisa_jahit == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_jahit }}</span>
                    <h5 class="mt-2">{{ $totals->latest_jahit_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Finishing -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalFinishing()" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Finishing', this)" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2">
                        <iconify-icon icon="prime:history"></iconify-icon>
                    </button>
                    <h2 class="text-xl font-semibold">Finishing</h2>
                    <p class="text-4xl font-bold mt-2" id="finishing">
                        @if ($totals->total_sisa_finishing == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_finishing }}
                        @endif
                    </p>
                    <span class="bg-yellow-500 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_finishing">{{ $totals->total_sisa_finishing == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_finishing }}</span>
                    <h5 class="mt-2">{{ $totals->latest_finishing_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

                <!-- Total Packing -->
                <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                    <button onclick="openModalPacking()" class="bg-blue-500 flex items-center rounded-xl justify-center text-black font-medium absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                    <button onclick="openHistory('Packing', this)" class="bg-blue-500 flex items-center rounded-xl justify-center text-black font-medium absolute left-2 text-xl top-2">
                        <iconify-icon icon="prime:history"></iconify-icon>
                    </button>
                    <h2 class="text-xl font-semibold">Packing</h2>
                    <p class="text-4xl font-bold mt-2" id="packing">
                        @if ($totals->total_sisa_packing == 0)
                            <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                        @else
                            {{ $totals->total_packing }}
                        @endif
                    </p>
                    <span class="bg-blue-400 text-black font-medium mt-2 block p-1 rounded-lg" id="sisa_packing">{{ $totals->total_sisa_packing == 0 ? 'Selesai' : 'Sisa : ' . $totals->total_sisa_packing }}</span>
                    <h5 class="mt-2">{{ $totals->latest_packing_history->pegawai->nama ?? 'Belum Ada Progress' }}</h5>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="popupModalSetting" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Setting Baru</h2>
            <button onclick="closeModalSetting()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="kategori" value="setting">

            {{-- PILIH PEGAWAI --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>
                <select 
                    id="pilihPegawaiSetting"
                    name="pegawai_id"
                    class="select2-custom w-full border rounded px-3 py-2"
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
            <div>
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectSetting" 
                    class="select2-custom w-full border rounded px-3 py-2"
                    required
                >
                    <option value="">Pilih Job</option>
                    @foreach ($orders as $o)
                        @php
                            // Tentukan status teks berdasarkan nilai boolean/integer di $o->setting
                            $statusText = $o->setting == 1 ? 'Setting Selesai' : 'Belum Setting';
                        @endphp
                        
                        <option value="{{ $o->id }}">
                            {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} - {{ $o->nama_konsumen }} | {{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }} ({{ $statusText }})
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- STATUS SETTING --}}
            <div>
                <label class="block text-sm font-medium mb-1">Jumlah Setting</label>

                <div class="flex gap-2 mt-1">
                    <button 
                        type="button" 
                        id="settingBelumBtn"
                        class="w-full settingBtn px-4 py-2 border rounded font-semibold"
                        data-value="0">
                        Belum
                    </button>

                    <button 
                        type="button" 
                        id="settingSelesaiBtn"
                        class="w-full settingBtn px-4 py-2 border rounded font-semibold"
                        data-value="1">
                        Selesai
                    </button>
                </div>

                <input type="hidden" name="qty" id="qtySettingInput" value="0">

                <span id="errorPesanSetting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    Input hanya boleh 0 atau 1.
                </span>
            </div>


            {{-- KETERANGAN --}}
            <div>
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea 
                    name="keterangan" 
                    id="keterangan" 
                    placeholder="Masukkan Keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                </textarea>
            </div>


            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pt-3">
                <button 
                    type="button" 
                    onclick="closeModalSetting()" 
                    class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button 
                    type="submit" 
                    id="submitButtonSetting"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700" 
                    disabled>
                    Simpan
                </button>
            </div>
        </form>


    </div>
</div>

<div id="popupModal" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Print Baru</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="print"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawai"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPrint" 
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                                data-nilai="{{ $o->jenisOrder->nilai }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa print : {{ $o->sisa_print }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Print</label>
                </div>
                <div class="flex items-center">
                    <span id="qtyTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Print Qty
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPrintInput" data-max-value-print="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanPrint"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="printSelesai" data-print-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Print: 0
                    </span>
                    <span id="sisaPrint"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Print: 0
                    </span>
                </div>
                <span id="errorPesanPrint" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRINEUN
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-between items-center">
                <span id="hasilQtyJenis"
                    class="inline-flex bg-blue-100 text-blue-700 px-2 py-2 font-bold border border-blue-300 rounded">
                    0
                </span>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                    <button type="submit" id="submitButtonPrint" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="popupModalPress" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Press Baru</h2>
            <button onclick="closeModalPress()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="press"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawaiPress"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPress" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_print == 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                                data-nilai-press="{{ $o->jenisOrder->nilai }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa press : {{ max($o->print - $o->press, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Press</label>
                </div>
                <div class="flex items-center">
                    <span id="printTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Presseun
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPressInput" data-max-value-press="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />
                    
                    <span id="totalQtySpanPress"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="pressSelesai" data-press-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Press: 0
                    </span>
                    <span id="sisaPress"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Press: 0
                    </span>
                </div>
                <span id="errorPesanPress" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRESSEUN !!
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-between items-center">
                <span id="hasilQtyJenisPress"
                    class="inline-flex bg-blue-100 text-blue-700 px-2 py-2 font-bold border border-blue-300 rounded">
                    0
                </span>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModalPress()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                    <button type="submit" id="submitButtonPress" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="popupModalCutting" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Cutting Baru</h2>
            <button onclick="closeModalCutting()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="cutting"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawaiCutting"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectCutting" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_press == 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa cutting : {{ max($o->press - $o->cutting, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Cutting</label>
                </div>
                <div class="flex items-center">
                    <span id="pressTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Cuttingeun
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyCuttingInput" data-max-value-cutting="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />
                    
                    <span id="totalQtySpanCutting"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="cuttingSelesai" data-cutting-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Cutting: 0
                    </span>
                    <span id="sisaCutting"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Cutting: 0
                    </span>
                </div>
                <span id="errorPesanCutting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI CUTTINGEUN !!
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalCutting()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                <button type="submit" id="submitButtonCutting" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalJahit" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Jahit Baru</h2>
            <button onclick="closeModalJahit()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="jahit"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawaiJahit"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectJahit" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_cutting == 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa jahit : {{ max($o->cutting - $o->jahit, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Jahit</label>
                </div>
                <div class="flex items-center">
                    <span id="cuttingTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Jahiteun
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyJahitInput" data-max-value-jahit="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />
                    
                    <span id="totalQtySpanJahit"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="jahitSelesai" data-jahit-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Jahit: 0
                    </span>
                    <span id="sisaJahit"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Jahit: 0
                    </span>
                </div>
                <span id="errorPesanJahit" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI JAHITEUN !!
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalJahit()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                <button type="submit" id="submitButtonJahit" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalFinishing" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Finishing Baru</h2>
            <button onclick="closeModalFinishing()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="finishing"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawaiFinishing"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectFinishing" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_jahit == 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa finishing : {{ max($o->jahit - $o->finishing, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Finishing</label>
                </div>
                <div class="flex items-center">
                    <span id="jahitTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Pinisingeun
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyFinishingInput" data-max-value-finishing="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />
                    
                    <span id="totalQtySpanFinishing"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="finishingSelesai" data-finishing-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Finishing: 0
                    </span>
                    <span id="sisaFinishing"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Finishing: 0
                    </span>
                </div>
                <span id="errorPesanFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PINISINGEUN !!
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalFinishing()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                <button type="submit" id="submitButtonFinishing" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="popupModalPacking" class="hidden fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Packing Baru</h2>
            <button onclick="closeModalPacking()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}">
            @csrf

            <input type="hidden" name="kategori" value="packing"> 
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pegawai</label>

                <select 
                    id="pilihPegawaiPacking"
                    name="pegawai_id"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
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
        
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <select 
                    name="job_id" 
                    id="jobSelectPacking" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Pilih Job</option>

                    @foreach ($orders as $o)
                        @if ($o->setting == 1 && $o->sisa_finishing == 0)
                            <option 
                                value="{{ $o->id }}"
                                data-setting="{{ $o->setting }}"
                                data-text="{{ $o->nama_job }} - {{ $o->nama_konsumen }}"
                            >
                                {{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} 
                                (sisa packing : {{ max($o->finishing - $o->packing, 0) }}) - 
                                {{ $o->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($o->created_at)->locale('id')->translatedFormat('l, d F Y') }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Packing</label>
                </div>
                <div class="flex items-center">
                    <span id="finishingTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Pekingeun
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPackingInput" data-max-value-packing="0"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />
                    
                    <span id="totalQtySpanPacking"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: 0
                    </span>
                    <span id="packingSelesai" data-packing-done="0"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Hasil Packing: 0
                    </span>
                    <span id="sisaPacking"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Packing: 0
                    </span>
                </div>
                <span id="errorPesanPacking" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PEKINGEUN !!
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalPacking()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">Batal</button>
                <button type="submit" id="submitButtonPacking" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>

    </div>
</div>

<div id="historyPane" class="w-full mt-4 pt-0 p-6 hidden">
    <div class="w-full z-20 rounded-lg p-4 bg-white border shadow border-gray-200">
        
        <h3 id="historyTitle" class="text-xl font-bold mb-4 border-b pb-2">Riwayat Pekerjaan: Print</h3>

        <div class="mb-4 flex items-center justify-between space-x-3">
            <h2 class="flex items-center space-x-2">
                <span id="selectedJobNameDisplay" class="text-xl font-bold text-gray-800">Semua Job</span>
            </h2>
            <div>
                <label for="historyJobSelect" class="text-sm font-medium">Pilih Job:</label>
                <select id="historyJobSelect" class="border rounded px-3 py-1 text-sm bg-gray-50">
                    <option value="">Semua Job</option>
                    @foreach ($orders as $o)
                        <option value="{{ $o->id }}">{{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="w-full overflow-y-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-800 sticky top-0">
                        <th class="px-3 py-2 text-left text-white">Pegawai</th>
                        <th class="px-3 py-2 text-left text-white">Nama Job</th>
                        <th class="px-3 py-2 text-left text-white">Nama Konsumen</th>
                        <th class="px-3 py-2 text-center text-white"><div class="w-28">Jenis</div></th>
                        <th class="px-3 py-2 text-center text-white"><div class="w-28">Jumlah (Qty)</div></th>
                        <th class="px-3 py-2 text-left text-white"><div class="w-56">Keterangan</div></th>
                        <th class="px-3 py-2 text-left text-white">Waktu</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="bg-white divide-y divide-gray-800">
                    <tr><td colspan="5" class="text-center py-4 text-white">Pilih Job untuk melihat riwayat.</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<div id="popupModalOrder" class="hidden popup_custom fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg py-3 p-6">

        <!-- HEADER -->

        <!-- FORM -->
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="mb-3">
                <div class="flex justify-between items-center mb-0">
                    <label class="block text-xs font-medium">Nama Konsumen</label>
                    <button onclick="closeModalOrder()" type="button" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
                </div>
                <select id="select2-nama-konsumen" name="nama_konsumen" required>
                    <option value="" selected disabled>Pilih Nama Konsumen...</option>
                    @foreach ($uniqueKonsumens as $konsumenName)
                        <option value="{{ $konsumenName }}">{{ $konsumenName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Job -->
            <div class="mb-3">
                <div class="flex gap-2 border-b pb-2">

                    @foreach ($kategoriList as $kategori)
                        <button type="button"
                            onclick="openKategori({{ $kategori->id }})"
                            class="tab-btn px-3 py-1 text-xs border rounded">
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

            <div class="mb-3">

                @foreach ($kategoriList as $kategori)

                    <!-- WRAPPER PER KATEGORI -->
                    <div class="kategori-wrapper hidden" id="kategori-{{ $kategori->id }}">

                        <div class="grid grid-cols-5 gap-3">

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
                                    class="js-pakaian-label bg-gray-100 border border-gray-300 rounded-lg px-2 py-2 text-xs
                                        cursor-pointer text-gray-700 font-medium text-center transition">
                                    {{ $jenis->nama_jenis }}
                                </label>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

            <!-- Qty -->
            <div class="mb-3">
                <label class="block text-xs font-medium mb-1">Jumlah Quantity</label>
                <input type="number" 
                    name="qty"
                    class="w-full border text-xs rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Masukkan jumlah qty"
                    required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium mb-1">Keterangan</label>
                <div class="grid grid-cols-4 gap-3 mb-3">

                    <!-- JENIS BAHAN -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Bahan</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisBahan as $item)
                                <button 
                                    type="button"
                                    data-kategori="bahan"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('bahan', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS POLA -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Pola</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisPola as $item)
                                <button 
                                    type="button"
                                    data-kategori="pola"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('pola', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS KERAH -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Kerah</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisKerah as $item)
                                <button 
                                    type="button"
                                    data-kategori="kerah"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('kerah', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS JAHITAN -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Jahitan</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisJahitan as $item)
                                <button 
                                    type="button"
                                    data-kategori="jahitan"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('jahitan', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="id_jenis_bahan" id="idJenisBahan">
                    <input type="hidden" name="id_jenis_pola" id="idJenisPola">
                    <input type="hidden" name="id_jenis_kerah" id="idJenisKerah">
                    <input type="hidden" name="id_jenis_jahitan" id="idJenisJahitan">

                </div>
                <input type="text" 
                    name="keterangan"
                    id="keteranganField"
                    class="w-full border text-xs rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Pilih Spesifikasi"
                    required>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalOrder()" class="px-4 py-2 rounded text-xs text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded text-xs bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    $(document).ready(function() {

        // Aktifkan select2 pada pegawai
        $('#pilihPegawaiSetting').select2({
            placeholder: "Pilih Pegawai",
            allowClear: true,
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
            allowClear: true,
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
            allowClear: true,
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
            allowClear: true,
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
            allowClear: true,
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
            allowClear: true,
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
            allowClear: true,
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
        $ordersSelect->mapWithKeys(function($o) {
            return [
                $o->id => $o->jenisOrder->nama_jenis
            ];
        })
    );
</script>

<script>
    // Data dari Laravel
    const allHistories = @json($allHistories); // Dapatkan semua riwayat
    const allPegawais = @json($pegawais);
    const jobData = @json($orders);
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
    const tableBody = document.getElementById('historyTableBody');
    const title = document.getElementById('historyTitle');
    
    // 1. Periksa apakah sudah terbuka (Toggle)
    const isCurrentlyOpen = pane.classList.contains('hidden');
    if (!isCurrentlyOpen && currentKategori === kategori) {
        pane.classList.add('hidden'); // Tutup jika kategori sama
        return;
    }

    // 2. Set Posisi Pane (Absolute)
    const cardRect = buttonElement.closest('.relative').getBoundingClientRect();
        pane.style.top = (cardRect.bottom + window.scrollY) + 'px'; // Posisi di bawah kartu
        pane.style.left = cardRect.left + 'px'; // Sejajarkan dengan tepi kiri kartu

        // 3. Set Global State & Tampilan
        currentKategori = kategori;
        title.textContent = `Riwayat Pekerjaan: ${kategori}`;
        pane.classList.remove('hidden');

        // 4. Muat Data Default (untuk kategori ini)
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
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-800 font-medium">Tidak ada riwayat untuk ${currentKategori}.</td></tr>`;
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
        document.getElementById('popupModal').classList.remove('hidden');
    }
    function openModalSetting() {
        document.getElementById('popupModalSetting').classList.remove('hidden');
    }
    function openModalPress() {
        document.getElementById('popupModalPress').classList.remove('hidden');
    }
    function openModalCutting() {
        document.getElementById('popupModalCutting').classList.remove('hidden');
    }
    function openModalJahit() {
        document.getElementById('popupModalJahit').classList.remove('hidden');
    }
    function openModalFinishing() {
        document.getElementById('popupModalFinishing').classList.remove('hidden');
    }
    function openModalPacking() {
        document.getElementById('popupModalPacking').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('popupModal').classList.add('hidden');
    }
    function closeModalSetting() {
        document.getElementById('popupModalSetting').classList.add('hidden');
    }
    function closeModalPress() {
        document.getElementById('popupModalPress').classList.add('hidden');
    }
    function closeModalCutting() {
        document.getElementById('popupModalCutting').classList.add('hidden');
    }
    function closeModalJahit() {
        document.getElementById('popupModalJahit').classList.add('hidden');
    }
    function closeModalFinishing() {
        document.getElementById('popupModalFinishing').classList.add('hidden');
    }
    function closeModalPacking() {
        document.getElementById('popupModalPacking').classList.add('hidden');
    }
</script>

<script>
    let totalData = {
        qty: "{{ $totals->total_qty }}",
        hari: "{{ $totals->total_hari }}",
        deadline: "{{ $totals->total_deadline }}",
        nama_job: "Total Quantity",
        setting: "{{ $totals->total_setting }}",
        print: "{{ $totals->total_print }}",
        press: "{{ $totals->total_press }}",
        cutting: "{{ $totals->total_cutting }}",
        jahit: "{{ $totals->total_jahit }}",
        finishing: "{{ $totals->total_finishing }}",
        packing: "{{ $totals->total_packing }}",
        sisa_print: "{{ $totals->total_sisa_print }}",
        sisa_press: "{{ $totals->total_sisa_press }}",
        sisa_cutting: "{{ $totals->total_sisa_cutting }}",
        sisa_jahit: "{{ $totals->total_sisa_jahit }}"
        sisa_finishing: "{{ $totals->total_sisa_finishing }}"
        sisa_packing: "{{ $totals->total_sisa_packing }}"
        sisa_setting: "{{ $totals->total_sisa_setting }}"
    };
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
            btn.classList.remove("bg-red-500", "bg-green-500", "text-white");
            btn.classList.add("bg-gray-200", "text-gray-800");
        });

        // Ambil value dari tombol yang diklik
        const value = button.dataset.value;

        // Tentukan warna active
        const activeColor = value === "1" ? "bg-green-500" : "bg-red-500";

        // Set warna tombol active
        button.classList.remove("bg-gray-200", "text-gray-800");
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
            allowClear: true,
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
            allowClear: true, 
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
            allowClear: true, 
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
            allowClear: true, 
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
            allowClear: true, 
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
            allowClear: true, 
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
            allowClear: true, 
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
    function openKategori(id) {
        // sembunyikan semua kategori
        document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

        // tampilkan kategori yang dipilih
        document.getElementById('kategori-' + id).classList.remove('hidden');

        // ubah style tab
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-gray-800', 'text-white'));
        event.target.classList.add('bg-gray-800', 'text-white');
    }

    // buka tab pertama otomatis
    document.addEventListener("DOMContentLoaded", () => {
        const firstTab = document.querySelector('.tab-btn');
        if (firstTab) firstTab.click();
    });
</script>

<script>
let selectedSpecs = {
    bahan: null,
    pola: null,
    kerah: null,
    jahitan: null,
};

function selectSpec(kategori, nama, id) {
    selectedSpecs[kategori] = nama;

    // simpan id ke input hidden
    document.getElementById("idJenis" + capitalize(kategori)).value = id;

    updateKeterangan();
    highlightSelectedButton(kategori, id);
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function updateKeterangan() {
    let parts = [];

    if (selectedSpecs.bahan)   parts.push("Bahan: " + selectedSpecs.bahan);
    if (selectedSpecs.pola)    parts.push("Pola: " + selectedSpecs.pola);
    if (selectedSpecs.kerah)   parts.push("Kerah: " + selectedSpecs.kerah);
    if (selectedSpecs.jahitan) parts.push("Jahitan: " + selectedSpecs.jahitan);

    document.getElementById("keteranganField").value = parts.join(" | ");
}

function highlightSelectedButton(kategori, id) {
    // Reset highlight kategori itu
    document.querySelectorAll(`button[data-kategori="${kategori}"]`).forEach(btn => {
        btn.classList.remove("ring-2", "ring-green-800", "rounded-lg");
    });

    // Pilih tombol berdasarkan data-id
    let activeBtn = document.querySelector(
        `button[data-kategori="${kategori}"][data-id="${id}"]`
    );

    if (activeBtn) {
        activeBtn.classList.add("ring-2", "ring-green-800", "rounded-lg");
    }
}

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
            activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
            activeLabel.classList.add('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
        });
    });

    // --- Inisialisasi awal: cek radio yang sudah checked ---
    const checkedInput = document.querySelector('.js-pakaian-input:checked');
    if (checkedInput) {
        const activeLabel = document.querySelector(`label[for="${checkedInput.id}"]`);
        activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
        activeLabel.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'border-blue-600');
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
    document.getElementById('popupModalOrder').classList.remove('hidden');
}
function closeModalOrder() {
    document.getElementById('popupModalOrder').classList.add('hidden');
}
</script>

@endsection
