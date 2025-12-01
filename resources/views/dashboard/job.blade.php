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
            <div class="flex flex-col justify-center items-center gap-0 w-max">
                <label class="text-sm font-semibold text-white w-36 ml-1">Pilih Job Lain</label>
                <select id="selectJob" class="border border-gray-300 px-3 py-2 rounded">
                    <option value="Progress Keseluruhan">Progress Keseluruhan</option>
                    @foreach ($orders as $o)
                        <option value="{{ $o->slug }}" {{ $o->slug == $job->slug ? 'selected' : '' }}>{{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }}  - {{ $o->nama_konsumen }}</option>
                    @endforeach
                </select>
            </div>
            <div class=""></div>
        </div>
    </div>
    <div class="progress_custom relative z-10 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold" id="nama_job">{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }}</h2>
                <p id="qty" class="text-3xl font-bold mt-2">{{ $job->qty }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold">Lilana Migawean</h2>
                <p id="hari" class="text-3xl font-bold mt-2">{{ (float) $job->hari }} Poe</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-xl font-semibold">
                    {{ $job->sisa_jahit == 0 ? 'Pagawean Beres' : 'Kudu Beresna' }}
                </h2>

                <p id="deadline" class="text-3xl font-bold mt-2">
                    @if ($job->sisa_jahit == 0)
                        BERES
                    @else
                        {{ (float) $job->deadline }} POE DEUI
                    @endif
                </p>
            </div>
        </div>

        <div class="w-full pb-2">
            <div class="mt-5 grid w-full grid-cols-7 gap-5">

            <!-- Total Setting -->
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="openModalSetting()" class="bg-gray-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Setting', this, {{ $job->id }})" class="bg-gray-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Setting</h2>
                <p class="text-4xl font-bold mt-2" id="print">
                    @if ($job->setting == 1)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        <iconify-icon class="text-red-500" icon="carbon:close-filled"></iconify-icon> 
                    @endif
                </p>
                <span class="bg-gray-500 text-black mt-2 block p-1 rounded-lg">
                    {{ $job->setting == 1 ? 'Selesai' : 'Belum' }}
                </span>
                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsAction }}" class="bg-red-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Print', this, {{ $job->id }})" class="bg-red-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Print</h2>
                <p class="text-4xl font-bold mt-2" id="print">
                    @if ($job->sisa_print == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->print }}
                    @endif
                </p>
                <span 
                    class="bg-red-500 text-black mt-2 block p-1 rounded-lg" 
                    id="sisa_print"
                >
                    {{ $job->sisa_print == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_print }}
                </span>

                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsActionPress }}" class="bg-green-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Press', this, {{ $job->id }})" class="bg-green-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Press</h2>
                <p class="text-4xl font-bold mt-2" id="press">
                    @if ($job->sisa_press == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->press }}
                    @endif
                </p>
                <span class="bg-green-500 text-black mt-2 block p-1 rounded-lg" id="sisa_press">{{ $job->sisa_press == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_press }}</span>
                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsActionCutting }}" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Cutting', this, {{ $job->id }})" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Cutting</h2>
                <p class="text-4xl font-bold mt-2" id="cutting">
                    @if ($job->sisa_cutting == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->cutting }}
                    @endif
                </p>
                <span class="bg-yellow-500 text-black mt-2 block p-1 rounded-lg" id="sisa_cutting">{{ $job->sisa_cutting == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_cutting }}</span>
                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsActionJahit }}" class="bg-blue-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Jahit', this, {{ $job->id }})" class="bg-blue-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Jahit</h2>
                <p class="text-4xl font-bold mt-2" id="jahit">
                    @if ($job->sisa_jahit == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->jahit }}
                    @endif
                </p>
                <span class="bg-blue-400 text-black mt-2 block p-1 rounded-lg" id="sisa_jahit">{{ $job->sisa_jahit == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_jahit }}</span>
                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsActionFinishing }}" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Finishing', this, {{ $job->id }})" class="bg-yellow-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Finishing</h2>
                <p class="text-4xl font-bold mt-2" id="finishing">
                    @if ($job->sisa_finishing == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->finishing }}
                    @endif
                </p>
                <span class="bg-yellow-400 text-black mt-2 block p-1 rounded-lg" id="sisa_finishing">{{ $job->sisa_finishing == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_finishing }}</span>
                <h5 class="mt-2">
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
            <div class="bg-white p-6 px-5 rounded-lg shadow text-center relative">
                <button onclick="{{ $jsActionPacking }}" class="bg-blue-500 flex items-center rounded-xl justify-center text-black absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
                <button onclick="openHistory('Packing', this, {{ $job->id }})" class="bg-blue-500 flex items-center rounded-xl justify-center text-black absolute left-2 text-xl top-2">
                    <iconify-icon icon="prime:history"></iconify-icon>
                </button>
                <h2 class="text-xl font-semibold">Packing</h2>
                <p class="text-4xl font-bold mt-2" id="packing">
                    @if ($job->sisa_packing == 0)
                        <iconify-icon class="text-green-500" icon="lets-icons:check-fill"></iconify-icon>
                    @else
                        {{ $job->packing }}
                    @endif
                </p>
                <span class="bg-blue-400 text-black mt-2 block p-1 rounded-lg" id="sisa_packing">{{ $job->sisa_packing == 0 ? 'Selesai' : 'Sisa : ' . $job->sisa_packing }}</span>
                <h5 class="mt-2">
                    {{ $job->latestPackingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>
</div>
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
                    <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
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

<div id="historyPane" class="w-full mt-4 pt-0 p-6 hidden">
    <div class="w-full z-20 rounded-lg p-4 bg-white border shadow border-gray-200">
        
        <h3 id="historyTitle" class="text-xl font-bold mb-4 border-b pb-2">Riwayat Pekerjaan: Print</h3>

        <div class="mb-4 flex items-center justify-between space-x-3">
            <h2 class="flex items-center space-x-2">
                <span id="selectedJobNameDisplay" class="text-xl font-bold text-gray-800">Semua Job</span>
            </h2>
        </div>

        <div class="w-full overflow-y-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-800 sticky top-0">
                        <th class="px-3 py-2 text-left text-white">Pegawai</th>
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

<!-- Setting -->
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

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }}" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

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

            <div>
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea 
                    name="keterangan" 
                    id="keterangan" 
                    placeholder="Masukkan Keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                </textarea>
            </div>

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

<!-- PRINT -->
<div id="popupModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModal()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Print</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPrint">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa print : {{ $job->sisa_print }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Print</label>
                </div>
                <div class="flex items-center">
                    <span id="qtyTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Prineun: {{ $job->qty }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPrint"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanPrint"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="printSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Print selesai: {{ $job->print }}
                    </span>
                    <span id="sisaPrint"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Print: {{ $job->sisa_print }}
                    </span>
                </div>
                <span id="warningPrint" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRINEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnPrint" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PRESS -->
<div id="popupModalPress" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModalPress()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Press</h2>
            <button onclick="closeModalPress()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPress">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa press : {{ $job->sisa_press }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Press</label>
                </div>
                <div class="flex items-center">
                    <span id="printTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Presseun: {{ $job->print - $job->press }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPress"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanPress"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="pressSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Press selesai: {{ $job->press }}
                    </span>
                    <span id="sisaPress"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Press: {{ $job->sisa_press }}
                    </span>
                </div>
                <span id="warningPress" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PRESSEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalPress()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnPress" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- CUTTING -->
<div id="popupModalCutting" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModalCutting()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Cutting</h2>
            <button onclick="closeModalCutting()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formCutting">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa cutting : {{ $job->sisa_cutting }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Cutting</label>
                </div>
                <div class="flex items-center">
                    <span id="pressTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Cuttingeun: {{ $job->press - $job->cutting }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyCutting"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanCutting"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="cuttingSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Cutting selesai: {{ $job->cutting }}
                    </span>
                    <span id="sisaCutting"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Cutting: {{ $job->sisa_cutting }}
                    </span>
                </div>
                <span id="warningCutting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI CUTTINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalCutting()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnCutting" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- JAHIT -->
<div id="popupModalJahit" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModalJahit()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Jahit</h2>
            <button onclick="closeModalJahit()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formJahit">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa jahit : {{ $job->sisa_jahit }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Jahit</label>
                </div>
                <div class="flex items-center">
                    <span id="cuttingTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Jahiteun: {{ $job->cutting - $job->jahit }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyJahit"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanJahit"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="jahitSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Cutting selesai: {{ $job->jahit }}
                    </span>
                    <span id="sisaJahit"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Cutting: {{ $job->sisa_jahit }}
                    </span>
                </div>
                <span id="warningJahit" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI JAHITEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalJahit()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnJahit" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- FINISHING -->
<div id="popupModalFinishing" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModalFinishing()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Finishing</h2>
            <button onclick="closeModalFinishing()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formFinishing">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa finishing : {{ $job->sisa_finishing }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Finishing</label>
                </div>
                <div class="flex items-center">
                    <span id="jahitTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Pinisingeun: {{ $job->jahit - $job->finishing }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyFinishing"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanFinishing"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="finishingSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Finishing selesai: {{ $job->finishing }}
                    </span>
                    <span id="sisa_finishing"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Finishing: {{ $job->sisa_finishing }}
                    </span>
                </div>
                <span id="warningFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PINISINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalFinishing()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnFinishing" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PACKING -->
<div id="popupModalPacking" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="w-full h-full absolute left-0 top-0 z-10 bg-black/50" onclick="closeModalPacking()"></div>
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 z-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Packing</h2>
            <button onclick="closeModalPacking()" class="text-gray-500 hover:text-black text-2xl">
                <iconify-icon icon="iconamoon:close-duotone"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPacking">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
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
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ $job->jenisOrder->nama_jenis }} - {{ $job->nama_konsumen }} (sisa packing : {{ $job->sisa_packing }})" readonly
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium">Jumlah Packing</label>
                </div>
                <div class="flex items-center">
                    <span id="finishingTersedia"
                        class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-800 text-white">
                        Pekingeun: {{ $job->finishing - $job->packing }}
                    </span>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPacking"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        required
                    />

                    <span id="totalQtySpanPacking"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Qty: {{ $job->qty }}
                    </span>
                    <span id="packingSelesai"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Packing selesai: {{ $job->packing }}
                    </span>
                    <span id="sisa_packing"
                        class="inline-flex bg-green-100 text-green-700 px-2 py-2 font-bold rounded-r-md border border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Sisa Packing: {{ $job->sisa_packing }}
                    </span>
                </div>
                <span id="warningFinishing" class="text-sm text-red-600 mt-1 font-bold hidden">
                    MELEBIHI PEKINGEUN
                </span>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModalPacking()"
                    class="px-4 py-2 rounded text-sm bg-red-500 text-white hover:bg-red-700">
                    Batal
                </button>
                <button id="submitBtnPacking" type="button"
                    class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
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
        $('#pilihPegawai, #pilihPegawaiPress, #pilihPegawaiCutting, #pilihPegawaiJahit, #pilihPegawaiFinishing, #pilihPegawaiPacking').select2({
            
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
        const isCurrentlyOpen = pane.classList.contains('hidden');
        
        // Jika pane sudah terbuka dan Job/Kategori SAMA, tutup.
        if (!isCurrentlyOpen && currentKategori === kategori && currentJobId === jobId) {
            pane.classList.add('hidden'); 
            return;
        }

        // 2. Set Posisi Pane (Absolute)
        const cardRect = buttonElement.closest('.relative').getBoundingClientRect();
        pane.style.top = (cardRect.bottom + window.scrollY) + 'px'; 
        pane.style.left = cardRect.left + 'px'; 

        // 3. Set Global State & Tampilan
        currentKategori = kategori;
        currentJobId = jobId;
        
        pane.classList.remove('hidden');

        // 4. Muat Data Default (untuk kategori ini)
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

        if (selectedJob) {
            jobName = selectedJob.nama_job;
            customerName = selectedJob.nama_konsumen;
        }
    
        // Update Judul & Nama Job Display
        title.textContent = `Riwayat Pekerjaan: ${currentKategori}`;
        if (selectedJobNameDisplay) {
            // Tampilkan 'Nama Job (Nama Konsumen)'
            selectedJobNameDisplay.textContent = `${jobName} - ${customerName}`; 
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
            btn.classList.remove("bg-green-500", "bg-red-500", "text-white");
            btn.classList.add("bg-gray-200", "text-gray-800");
        });

        // 2. Tentukan Warna Aktif
        const activeColor = (value === "1") ? "bg-green-500" : "bg-red-500";
        
        // 3. Set Visual Tombol Aktif
        button.classList.remove("bg-gray-200", "text-gray-800");
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
    // Atur status awal tombol berdasarkan nilai default (0) saat dimuat
    if (settingBelumBtn && submitBtnSetting) {
        setSettingValueAndActive(settingBelumBtn, "0"); 
        // Ini memastikan tombol submit dinonaktifkan di awal karena nilai awal = 0.
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
