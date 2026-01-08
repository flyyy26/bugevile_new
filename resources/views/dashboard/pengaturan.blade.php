@extends('layouts.dashboard')

@section('title', 'Pengaturan')

@section('content')

    <div class="dashboard_banner dashboard_banner_small">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn dashboard_banner_btn_setting">
            
            <div class="setting_btn_container"> 
                <div class="setting_btn_layout">
                    <button
                        id="tabOngkos"
                        onclick="switchTab('ongkos')"
                    >
                        Ongkos Karyawan
                    </button>

                    <button
                        id="tabKemampuanProduksi"
                        onclick="switchTab('kemampuanProduksi')"
                    >
                        Kemampuan Produksi
                    </button>

                    <button
                        id="tabAffiliator"
                        onclick="switchTab('affiliator')"
                    >
                        Sales
                    </button>

                    <button
                        id="tabJob"
                        onclick="switchTab('job')"
                    >
                        Jenis Barang
                    </button>

                    <button
                        id="tabSpesifikasi"
                        onclick="switchTab('spesifikasi')"
                    >
                        Spek Barang
                    </button>

                    <button
                        id="tabHargaBarang"
                        onclick="switchTab('hargaBarang')"
                    >
                        Harga Barang
                    </button>

                </div>
            </div>
        </div>
        
    </div>
    <div>

        <div id="content-ongkos" class="ongkos_layout_container">
            <form action="{{ route('harga.update') }}" method="POST">
                @csrf
                
                <div class="pelanggan_detail_layout">
                    @php
                        $fields = [
                            'harga_setting'   => 'Setting / Item',
                            'harga_print'     => 'Print / Lembar',
                            'harga_press'     => 'Press / Lembar',
                            'harga_cutting'   => 'Cutting / Item',
                            'harga_jahit'     => 'Jahit / Item',
                            'harga_finishing' => 'Finishing / Item',
                            'harga_packing'   => 'Packing / Item',
                        ];
                    @endphp

                    @foreach ($fields as $field => $label)
                        <div class="form_field_normal form_field_no_margin">
                            <label>Harga {{ $label }}</label>
                            <input type="number"
                                name="{{ $field }}" 
                                value="{{ $harga->$field ?? '' }}"
                                class="input_form"
                                placeholder="Contoh: 3000"
                                required>
                        </div>
                    @endforeach
                    
                </div>

                <div class="dashboard_popup_order_btn" style="display:block; margin-right:auto;">
                    <button type="submit">
                        Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div id="content-kemampuanProduksi" class="ongkos_layout_container">
            
                <form action="{{ route('kemampuan-produksi.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="pelanggan_detail_layout">
                        <!-- Print -->
                        <div class="form_field_normal form_field_no_margin">
                            <label>Kemampuan Jahit</label>
                            <input type="number"
                                name="print"
                                value="{{ $print->nilai_kemampuan ?? 30 }}"
                                class="input_form"
                                placeholder="Jumlah print per hari"
                                min="0"
                                step="1"
                                required>
                        </div>
                        
                        <!-- Packing & Finishing -->
                        <div class="form_field_normal form_field_no_margin">
                            <label>Spare Waktu</label>
                            <input type="number"
                                name="packing_finishing"
                                value="{{ $packingFinishing->nilai_kemampuan ?? 25 }}"
                                class="input_form"
                                placeholder="Tambah Spare Waktu"
                                min="0"
                                step="1"
                                required>
                        </div>
                    </div>
                    
                    <div class="dashboard_popup_order_btn" style="display:block; margin-right:auto;">
                        <button type="submit">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="content-job" class="hidden">
            <div class="orders_table_container">
                <table>
                    <thead class="bg-gray-800">
                        <tr>
                            <th><div class="text-center">No</div></th>
                            <th><div class="text-center">Nama Job</div></th>
                            <th><div class="text-center">Aksi</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($job as $jobs)
                        <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                            <td><div class="text-center">{{ $loop->iteration }}</div></td>
                            <td><div class="text-center">{{ $jobs->nama_job }}</div></td>
                            <td>
                                <div class="btn_table_action">
                                    <button type="button"
                                        onclick="openJobEditModal({{ $jobs->id }}, '{{ $jobs->nama_job }}')"
                                        class="bg-blue-500">
                                        Edit
                                    </button>
                                    <form action="{{ route('pengaturan.job.destroy', $jobs->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if ($job->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <button onclick="openJobCreateModal()" class="setting_add_table">
                    + Tambah Job
                </button>
            </div>
        </div>

        <div id="content-affiliator" class="hidden">
            @include('dashboard.affiliator')
        </div>

        <div id="content-spesifikasi" class="hidden">
            <div>
                <div class="spesifikasi_container">
                    @php
                    $tabColors = [
                        'tab-blue',
                        'tab-green',
                        'tab-purple',
                        'tab-orange',
                        'tab-pink',
                        'tab-teal',
                        'tab-indigo',
                        'tab-red',
                        'tab-yellow',
                        'tab-cyan',
                    ];
                    @endphp

                    <div class="spesifikasi_layout_btn">
                        @foreach($kategori as $index => $k)
                            @php $colorClass = $tabColors[$index % 10]; @endphp
                            <div class="spesifikasi_layout_btn_grid_box">
                                <button
                                    onclick="showTab({{ $k->id }})"
                                    id="tab-{{ $k->id }}"
                                    data-color="{{ $colorClass }}"
                                    class="tab-kategori-btn {{ $colorClass }}"
                                >
                                    {{ $k->nama }}
                                </button>
                                <button
                                    onclick="showDeleteKategoriPopup({{ $k->id }}, '{{ $k->nama }}')"
                                    title="Hapus kategori"
                                    style="background-color:transparent;"
                                >
                                    <img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon">
                                </button>
                            </div>
                        @endforeach
                        <button
                            onclick="openTambahKategoriModal()"
                            class="btn-tambah-kategori btn-add-style"
                        >
                            + Tambah
                        </button>

                    </div>

                    @foreach($kategori as $index => $k)
                        <div class="kategori-content {{ $index !== 0 ? 'hidden' : '' }}" id="content-{{ $k->id }}">

                            <div class="spesifikasi_layout_btn_grid">

                                @foreach($k->jenisSpek as $index2 => $s)
                                    <div class="spesifikasi_layout_btn_grid_box">
                                        <button
                                            onclick="showSpekDetail({{ $k->id }}, {{ $s->id }})"
                                            id="spek-tab-{{ $s->id }}"
                                            class="spek-tab-btn {{ $index === 0 && $index2 === 0 ? 'text-black' : 'bg-white' }} pr-10"
                                        >
                                            {{ $s->nama_jenis_spek }}
                                        </button>
                                        <button
                                            onclick="showDeleteSpekPopup({{ $s->id }}, {{ $k->id }}, '{{ $s->nama_jenis_spek }}')"
                                            title="Hapus jenis spek"
                                        >
                                            <img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon" class="w-full h-full">
                                        </button>
                                    </div>
                                @endforeach

                                <button
                                    onclick="openModal({{ $k->id }})"
                                    class="btn-add-style"
                                >
                                    + Tambah Spek
                                </button>

                            </div>

                            @foreach($k->jenisSpek as $index2 => $s)
                                <div 
                                    id="spek-content-{{ $k->id }}-{{ $s->id }}" 
                                    class="{{ $index === 0 && $index2 === 0 ? '' : 'hidden' }} mt-6"
                                >
                                    @if(optional($s->detail)->count())
                                        <div class="orders_table_container">
                                            <table class="w-full border">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th><div class="text-center">No</div></th>
                                                        <th><div class="text-center">Nama Detail</div></th>
                                                        <th><div class="text-center">Gambar</div></th>
                                                        <th>Kategori</th>
                                                        <th><div class="text-center">Aksi</div></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($s->detail as $i => $d)
                                                        <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                                                            <td><div class="text-center">{{ $i + 1 }}</div></td>
                                                            <td>{{ $d->nama_jenis_spek_detail }}</td>
                                                            <td><div class="text-center table_img">
                                                                @if($d->gambar)
                                                                    <img src="{{ asset('storage/' . $d->gambar) }}" alt="Gambar">
                                                                @else
                                                                    <span>Tidak ada</span>
                                                                @endif
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    // TOTAL jenis order di kategori ini
                                                                    $totalJenisOrder = $jenisOrderList
                                                                        ->where('id_kategori_jenis_order', $k->id)
                                                                        ->count();

                                                                    // JUMLAH yang dipilih di detail
                                                                    $selectedCount = $d->jenisOrder->count();
                                                                @endphp

                                                                @if($selectedCount === 0)
                                                                    <span class="text-gray-800">-</span>

                                                                @elseif($selectedCount === $totalJenisOrder)
                                                                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-light">
                                                                        Semua
                                                                    </span>

                                                                @else
                                                                    <div class="jenis_flex">
                                                                        @foreach($d->jenisOrder as $jo)
                                                                            <span class="px-3 py-1 bg-gray-800 text-white rounded-full text-sm font-light">
                                                                                {{ $jo->nama_jenis }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </td>


                                                            <td>
                                                                <div class="btn_table_action">
                                                                    <button onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, {{ $d->id }}, '{{ $d->nama_jenis_spek_detail }}', {{ $d->jenisOrder->pluck('id')->toJson() }})"
                                                                            class="bg-blue-500">
                                                                        Edit
                                                                    </button>
                                                                    <!-- HAPUS FORM, GANTI DENGAN BUTTON AJAX -->
                                                                    <button 
                                                                        type="button" 
                                                                        onclick="deleteDetail({{ $d->id }}, {{ $k->id }}, {{ $s->id }}, this)"
                                                                        class="bg-red delete-detail-btn"
                                                                        data-detail-id="{{ $d->id }}"
                                                                        data-kategori-id="{{ $k->id }}"
                                                                        data-spek-id="{{ $s->id }}"
                                                                    >
                                                                        Hapus
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, null, '')"
                                                    class="add_detail_btn bg-green-500">
                                                + Tambah Detail
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-gray-500 italic mb-3">
                                            Belum ada detail jenis spek
                                        </div>
                                        <button onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, null, '')"
                                                class="add_detail_btn bg-green-500">
                                            + Tambah Detail
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="content-harga-barang" class="hidden">
            @include('dashboard._hargaBarang')
        </div>

    </div>
<style>
    .input_group {
        position: relative;
        width:max-content;
    }

    .input_group .input_form {
        padding-right: 35px;
        width: max-content;
    }

    .input_suffix {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        pointer-events: none;
        font-weight: 600;
    }

</style>

<style>
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
        background-color: #10b981;
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
        background-color: #059669;
    }
</style>

<!-- MODAL TAMBAH KATEGORI -->
<div id="modalTambahKategori" class="custom-modal">
    <div class="modal-content" style="text-align:start;">
        <h3>Tambah Kategori</h3>
        <form id="formTambahKategori">
            @csrf
            <div style="margin-bottom: 15px;">
                <input
                    type="text"
                    name="nama"
                    id="namaKategoriInput"
                    class="input_form"
                    placeholder="Masukkan nama kategori"
                    required>
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeTambahKategoriModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DELETE JENIS SPEK -->
<div id="deleteSpekModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus jenis spek "<span id="spekNameToDelete"></span>"?
            Semua detail di dalamnya juga akan terhapus.
        </p>
        <input type="hidden" id="spekIdToDelete">
        <input type="hidden" id="kategoriIdToDelete">
        <div class="dashboard_popup_order_btn">
            <button id="cancelDeleteSpekBtn">Batal</button>
            <button id="confirmDeleteSpekBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL DELETE CONFIRMATION -->
<div id="myModal" class="custom-modal">
    <div class="modal-content">
        <p style="margin-bottom: 20px;">Yakin ingin menghapus data ini?</p>
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelBtn">Batal</button>
            <button id="confirmBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL JENIS SPEK -->
<div id="modalJenisSpek" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeModal()" class="overlay_close"></div>    
    <div class="dashboard_popup_order_box">

        <div class="dashboard_popup_order_heading">
            <h2>Tambah Jenis Spek</h2>
            <button onclick="closeModal()">&times;</button>
        </div>

        <form id="formTambahSpek" method="POST">
            @csrf
            <input type="hidden" name="id_kategori_jenis_order" id="kategori_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_modal_input">

            <div class="form_field_normal">
                <label>Nama Jenis Spek</label>
                <input
                    type="text"
                    name="nama_jenis_spek"
                    id="nama_jenis_spek_input"
                    class="input_form"
                    placeholder="Contoh: Ukuran, Warna, Bahan..."
                    required>
            </div>

            <div class="dashboard_popup_order_btn">
                <button 
                    type="button"
                    onclick="closeModal()"
                >
                    Batal
                </button>

                <button 
                    type="submit"
                    id="btnSubmitSpek"
                >
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL JENIS SPEK DETAIL -->
<div id="modalJenisSpekDetail" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeDetailModal()" class="overlay_close"></div>    
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2 id="modalDetailTitle">Tambah Jenis Spek Detail</h2>
            <button onclick="closeDetailModal()">&times;</button>
        </div>

        <!-- PERBAIKI: Tambahkan action dan id -->
        <form id="formDetailSpek" method="POST" enctype="multipart/form-data" 
              action="{{ route('jenis_spek_detail.store') }}">
            @csrf
            <input type="hidden" name="id_jenis_spek" id="detail_spek_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_id_input">
            <input type="hidden" name="current_spek_id" id="current_spek_id_input">
            <input type="hidden" name="_method" id="detail_method_input" value="POST">
            <!-- Tambahkan hidden input untuk detail_id jika edit -->
            <input type="hidden" name="detail_id" id="detail_id_input" value="">

            <div class="form_field_normal">
                <label>Nama Detail</label>
                <input type="text" name="nama_jenis_spek_detail" id="detail_nama_input"
                    class="input_form" placeholder="Contoh: Benzema, Brazil ..." required>
            </div>

            <div class="form_field_normal form_field_normal_link">
                <label>Kategori</label>
                <div id="jenisOrderCheckboxContainer" class="spek_checkbox_style">
                    <!-- Checkbox akan diisi oleh JavaScript saat modal dibuka -->
                </div>
                <a href="/dashboard/pengaturan#hargaBarang">Tambah Kategori</a>
            </div>

            <div class="form_field_normal">
                <label>Gambar (Opsional)</label>
                <input type="file" name="gambar" id="detail_gambar_input" 
                    accept="image/*" class="input_form">
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeDetailModal()">Batal</button>
                <button type="submit" id="submitDetailBtn">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DELETE KATEGORI -->
<div id="deleteKategoriModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus Kategori</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus kategori "<span id="kategoriNameToDelete"></span>"?
            Semua jenis spek dan detail di dalamnya juga akan terhapus.
        </p>
        <input type="hidden" id="kategoriIdToDelete">
        <div class="dashboard_popup_order_btn">
            <button id="cancelDeleteKategoriBtn">Batal</button>
            <button id="confirmDeleteKategoriBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL DELETE JENIS ORDER -->
<div id="deleteJenisOrderModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus jenis order "<span id="jenisOrderNameToDelete"></span>"?
        </p>
        <input type="hidden" id="jenisOrderIdToDelete">
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelDeleteJenisOrderBtn" class="btn-cancel">Batal</button>
            <button id="confirmDeleteJenisOrderBtn" class="btn-confirm">Hapus</button>
        </div>
    </div>
</div>

<!-- JOB MODAL (create/edit) -->
<div id="modalJob"class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeJobModal()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2 id="modalJobTitle">Tambah Job</h2>
            <button onclick="closeJobModal()">&times;</button>
        </div>
        <form id="formJob" onsubmit="simpanJob(event)">
            @csrf
            <input type="hidden" id="jobId" value="">
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" id="jobNama" class="input_form" required>
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeJobModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL BACKDROP -->
<div id="modalJenisOrder" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">
            <div class="dashboard_popup_order_heading">
                <h2>Tambah Jenis Order</h2>
                <button onclick="closeModalJenisOrder()">&times;</button>
            </div>

            <form id="formStoreJenisOrder" class="space-y-4">
                @csrf

                <!-- Input Nama -->
                <div class="form_field_normal">
                    <label>Nama Jenis Order</label>
                    <input type="text" name="nama_jenis" id="storeNama"
                        class="input_form"
                        placeholder="Contoh: Panjang, Pendek, Jaket"
                        required>
                </div>

                <!-- Input Nilai -->
                <div class="form_field_normal">
                    <label>Nilai (Multiplier)</label>
                    <input type="number" name="nilai" id="storeNilai"
                        class="input_form"
                        placeholder="Contoh: 2, 4, 5"
                        value="1"
                        required>
                </div>

                <!-- Select Kategori -->
                <div class="form_field_normal">
                    <label>Kategori Jenis Order</label>
                    <div class="flex kategori_popup_style" style="gap:.7rem;">
                        <select name="id_kategori_jenis_order" id="storeKategori"
                            class="input_form" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openModalKategori()">+</button>
                    </div>
                </div>

                <!-- Bahan Harga -->
                <div class="form_field_normal">
                    <label>Harga Bahan</label>
                    <div class="harga_barang_style">
                        <input type="number" name="bahan_harga" id="storeBahanHarga"
                            class="input_form"
                            placeholder="Harga bahan"
                            value="0">
                        <span>x</span><span id="nilaiDisplayBahanStore"> 1</span> <span>=</span> <span id="totalBahanDisplayStore">0</span>
                    </div>
                </div>

                <!-- Kertas Harga -->
                <div class="form_field_normal">
                    <label>Harga Kertas</label>
                    <div class="harga_barang_style">
                        <input type="number" name="kertas_harga" id="storeKertasHarga"
                            class="input_form"
                            placeholder="Harga kertas"
                            value="0">
                        <span>x</span><span id="nilaiDisplayKertasStore"> 1</span> <span>=</span> <span id="totalKertasDisplayStore">0</span>
                    </div>
                </div>

                <!-- Asesoris Section -->
                <div id="storeAsesorisWrapper">
                    <div class="form_between_heading">
                        <label style="margin:0;">Asesoris</label>
                        <button type="button" onclick="tambahAsesorisStore()">
                            + Tambah Asesoris
                        </button>
                    </div>
                    <!-- Baris asesoris akan ditambahkan di sini -->
                </div>

                <!-- Harga Jenis Pekerjaan (Display) -->
                <div class="form_field_normal">
                    <label>Ongkos Gawe</label>
                    <input type="number" id="storeOngkosGawe" name="ongkos_gawe" 
                        class="input_form" 
                        value="0"
                        readonly
                        style="background-color: #f8fafc; cursor: not-allowed;">
                </div>

                <!-- Display Total Harga -->
                <div class="form_field_normal">
                    <label>Total Harga Barang</label>
                    <input type="text" id="storeTotalHarga" class="input_form" readonly
                        value="Rp 0">
                </div>

                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeModalJenisOrder()">
                        Batal
                    </button>
                    <button type="submit" id="submitStoreBtn">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Background -->
<div id="modalKategori" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeModalKategori()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Kategori Baru</h2>
            <button onclick="closeModalKategori()">&times;</button>
        </div>
        <form id="formKategoriBaru" onsubmit="simpanKategoriBaru(event)">
            <div class="form_field_normal">
                <input type="text" id="inputKategoriBaru"
                    class="input_form"
                    placeholder="Nama kategori..." required>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalKategori()">
                    Batal
                </button>

                <button type="submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Modal styling */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .custom-modal.active {
        display: block !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 25px;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease;
        position: relative;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-title {
        margin: 0 0 15px 0;
        font-size: 20px;
        color: #333;
        font-weight: 600;
    }

    .dashboard_popup_order_btn_center {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-cancel {
        background-color: #6b7280;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 80px;
    }

    .btn-confirm {
        background-color: #ef4444;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 80px;
    }

    .btn-cancel:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }

    .btn-confirm:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
    }

    /* Toast Notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
        min-width: 300px;
        max-width: 400px;
        border-left: 4px solid;
        background: white;
    }

    .toast-success {
        border-left-color: #10b981;
        background: linear-gradient(to right, #f0fdf4, #d1fae5);
        color: #065f46;
    }

    .toast-error {
        border-left-color: #ef4444;
        background: linear-gradient(to right, #fef2f2, #fee2e2);
        color: #991b1b;
    }

    .toast-info {
        border-left-color: #3b82f6;
        background: linear-gradient(to right, #eff6ff, #dbeafe);
        color: #1e40af;
    }

    .toast-warning {
        border-left-color: #f59e0b;
        background: linear-gradient(to right, #fffbeb, #fef3c7);
        color: #92400e;
    }

    .toast-icon {
        font-weight: bold;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-message {
        flex: 1;
        font-size: 14px;
        line-height: 1.4;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
        line-height: 1;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        margin-left: 10px;
    }

    .toast-close:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.1);
    }

    @keyframes slideInRight {
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
</style>

<script>
    // SATU DOMContentLoaded untuk SEMUA JavaScript
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM fully loaded - Initializing all scripts');
        
        // ==================== 1. VARIABEL GLOBAL ====================
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value || 
                        '{{ csrf_token() }}';
        
        // Modal elements - Semua modal dideklarasikan di sini
        const modal = document.getElementById("myModal");
        const confirmBtn = document.getElementById("confirmBtn");
        const cancelBtn = document.getElementById("cancelBtn");
        
        const deleteJenisOrderModal = document.getElementById('deleteJenisOrderModal');
        const confirmDeleteJenisOrderBtn = document.getElementById('confirmDeleteJenisOrderBtn');
        const cancelDeleteJenisOrderBtn = document.getElementById('cancelDeleteJenisOrderBtn');
        
        const modalTambahKategori = document.getElementById('modalTambahKategori');
        const deleteKategoriModal = document.getElementById('deleteKategoriModal');
        const confirmDeleteKategoriBtn = document.getElementById('confirmDeleteKategoriBtn');
        const cancelDeleteKategoriBtn = document.getElementById('cancelDeleteKategoriBtn');
        
        const deleteSpekModal = document.getElementById('deleteSpekModal');
        const confirmDeleteSpekBtn = document.getElementById('confirmDeleteSpekBtn');
        const cancelDeleteSpekBtn = document.getElementById('cancelDeleteSpekBtn');
        
        const modalJenisSpek = document.getElementById('modalJenisSpek');
        const modalJenisSpekDetail = document.getElementById('modalJenisSpekDetail');
        const modalJob = document.getElementById('modalJob');
        const modalJenisOrder = document.getElementById('modalJenisOrder');
        const modalKategori = document.getElementById('modalKategori');

        const storeBahanHarga = document.getElementById('storeBahanHarga');
        const storeKertasHarga = document.getElementById('storeKertasHarga');
        const storeNilai = document.getElementById('storeNilai');
        
        // State variables
        let activeKategoriId = {{ $kategori->first()->id ?? 0 }};
        let activeSpekId = null;
        let currentForm = null;
        let currentJenisOrderId = null;
        let currentJenisOrderRow = null;

        if (storeBahanHarga) {
            storeBahanHarga.addEventListener('input', hitungTotalStore);
        }
        
        if (storeKertasHarga) {
            storeKertasHarga.addEventListener('input', hitungTotalStore);
        }
        
        if (storeNilai) {
            storeNilai.addEventListener('input', hitungTotalStore);
        }
        
        // Untuk modal edit (dari file _hargaBarang)
        const editBahanHarga = document.getElementById('bahanHarga');
        const editKertasHarga = document.getElementById('kertasHarga');
        const editNilai = document.getElementById('editNilai');
        
        if (editBahanHarga) {
            editBahanHarga.addEventListener('input', updateDisplay);
        }
        
        if (editKertasHarga) {
            editKertasHarga.addEventListener('input', updateDisplay);
        }
        
        if (editNilai) {
            editNilai.addEventListener('input', updateDisplay);
        }

        document.addEventListener('submit', function(e) {
            // Cek jika target adalah formDetailSpek
            if (e.target && e.target.id === 'formDetailSpek') {
                e.preventDefault();
                handleDetailSpekSubmit(e);
            }
        });

        function handleDetailSpekSubmit(e) {
            const form = e.target;
            
            console.log('Form detail spek submit');
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Disable button dan tampilkan loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="loading-spinner"></div> Memproses...';
            
            // Ambil data dari form
            const formData = new FormData(form);
            const method = document.getElementById('detail_method_input').value;
            const isEditMode = method === 'PUT';
            
            console.log('Is edit mode:', isEditMode);
            
            // Tentukan URL dari action form
            const url = form.action;
            
            // Kirim data dengan AJAX
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data && data.success) {
                    // Ambil kategori dan spek ID dari response atau dari form
                    const currentKategoriId = data.current_kategori_id || 
                                            document.getElementById('current_kategori_id_input').value;
                    const currentSpekId = data.current_spek_id || 
                                        document.getElementById('current_spek_id_input').value;
                    
                    if (isEditMode) {
                        // Mode edit: Update row yang ada
                        updateDetailRow(data.detail || data.data, currentKategoriId, currentSpekId);
                    } else {
                        // Mode tambah: Tambah row baru
                        addNewDetailRow(data.detail || data.data, currentKategoriId, currentSpekId);
                    }
                    
                    showSuccessToast(data.message || 'Data berhasil disimpan!');
                    
                    // **Hanya tutup modal, JANGAN panggil closeDetailModal jika itu akan mengubah tab**
                    // Tutup modal secara langsung
                    if (modalJenisSpekDetail) {
                        modalJenisSpekDetail.classList.remove('active');
                    }
                    
                } else {
                    // Tampilkan error dari server
                    let errorMessage = 'Gagal menyimpan data';
                    if (data && data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    } else if (data && data.message) {
                        errorMessage = data.message;
                    }
                    showErrorToast(errorMessage);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error detail:', error);
                showErrorToast('Terjadi kesalahan: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        }

        // ==================== FUNGSI DELETE DETAIL VIA AJAX ====================
        window.deleteDetail = function(detailId, kategoriId, spekId, button) {
            console.log('Deleting detail:', detailId, 'Kategori:', kategoriId, 'Spek:', spekId);
            
            // Simpan informasi row yang akan dihapus
            const row = button.closest('tr');
            
            // Tampilkan loading
            button.disabled = true;
            const originalText = button.textContent;
            button.innerHTML = '<span class="loading-spinner-small"></span>';
            
            // Kirim request DELETE via AJAX
            fetch(`/dashboard/jenis-spek-detail/${detailId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    current_kategori_id: kategoriId,
                    current_spek_id: spekId,
                    _method: 'DELETE'
                })
            })
            .then(response => {
                // Cek status response
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response:', data);
                
                if (data && data.success) {
                    // Hapus row dari tabel
                    if (row) {
                        row.remove();
                        
                        // Update nomor urut
                        const tableBody = row.closest('tbody');
                        if (tableBody) {
                            const rows = tableBody.querySelectorAll('tr');
                            rows.forEach((row, index) => {
                                const numberCell = row.cells[0];
                                if (numberCell) {
                                    numberCell.querySelector('div').textContent = index + 1;
                                }
                            });
                            
                            // Cek jika tabel kosong
                            if (rows.length === 0) {
                                const tableContainer = tableBody.closest('.orders_table_container');
                                if (tableContainer) {
                                    // Hapus elemen yang tidak perlu
                                    const existingEmptyMessage = tableContainer.querySelector('.empty-message');
                                    if (existingEmptyMessage) existingEmptyMessage.remove();
                                    
                                    const existingAddButton = tableContainer.querySelector('.add_detail_btn');
                                    if (existingAddButton) existingAddButton.remove();
                                    
                                    // Tambahkan pesan kosong
                                    const emptyMessage = document.createElement('div');
                                    emptyMessage.className = 'text-gray-500 italic mb-3 empty-message';
                                    emptyMessage.textContent = 'Belum ada detail jenis spek';
                                    tableContainer.insertBefore(emptyMessage, tableBody);
                                    
                                    // Tambah button tambah detail
                                    const addButton = document.createElement('button');
                                    addButton.className = 'add_detail_btn bg-green-500';
                                    addButton.textContent = '+ Tambah Detail';
                                    addButton.onclick = function() {
                                        openDetailModal(kategoriId, spekId, null, '');
                                    };
                                    tableContainer.appendChild(addButton);
                                }
                            }
                        }
                    }
                    
                    // Tampilkan notifikasi sukses tanpa alert
                    showSuccessToast('Data berhasil dihapus');
                    
                } else {
                    throw new Error(data?.message || 'Gagal menghapus data');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                
                // Tampilkan notifikasi error tanpa alert
                showErrorToast('Gagal menghapus data: ' + error.message);
                
                // Restore button
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }


        // Handle form submit untuk modal tambah
        const formStoreJenisOrder = document.getElementById('formStoreJenisOrder');
        if (formStoreJenisOrder) {
            formStoreJenisOrder.addEventListener('submit', function(e) {
                e.preventDefault();
                
                console.log('Form submit dipanggil'); // Debug
                
                const submitBtn = document.getElementById('submitStoreBtn');
                const originalText = submitBtn.textContent;
                
                // Disable button dan tampilkan loading
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
                
                // Kumpulkan data form
                const formData = new FormData(this);
                
                // Debug: Log data yang akan dikirim
                console.log('Data yang akan dikirim:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, ':', value);
                }
                
                // Tampilkan loading
                showToast('info', 'Menyimpan data...', 2000);
                
                // Kirim data
                fetch('/dashboard/orders/setting', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug
                    
                    // Cek jika response adalah HTML (biasanya redirect atau error)
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.includes("application/json")) {
                        return response.json();
                    } else {
                        // Jika bukan JSON, baca sebagai text
                        return response.text().then(text => {
                            console.log('Response bukan JSON:', text.substring(0, 200)); // Debug
                            throw new Error('Response tidak valid - mungkin terjadi redirect');
                        });
                    }
                })
                .then(data => {
                    console.log('Response data:', data); // Debug
                    
                    if (data && data.success) {
                        showSuccessToast('Data berhasil ditambahkan!');
                        closeModalJenisOrder();
                        
                        // Reload halaman setelah delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Tampilkan error dari server
                        const errorMessage = data.message || data.errors || 'Gagal menyimpan data';
                        showErrorToast('Gagal menyimpan: ' + errorMessage);
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error detail:', error); // Debug
                    
                    // Cek jika error adalah network error
                    if (error.message.includes('Failed to fetch')) {
                        showErrorToast('Koneksi jaringan terputus. Periksa koneksi internet Anda.');
                    } else if (error.message.includes('redirect')) {
                        // Jika terjadi redirect, mungkin berhasil
                        showSuccessToast('Data berhasil ditambahkan!');
                        closeModalJenisOrder();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showErrorToast('Terjadi kesalahan saat menyimpan data: ' + error.message);
                    }
                    
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            });
        }
        
        // ==================== 2. TOAST NOTIFICATION ====================
        function showToast(message, type = 'success', duration = 3000) {
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.className = 'toast-container';
                document.body.appendChild(toastContainer);
            }
            
            const existingToasts = toastContainer.querySelectorAll('.toast');
            if (existingToasts.length > 3) {
                existingToasts[0].remove();
            }
            
            let icon = '✓';
            if (type === 'error') icon = '✗';
            if (type === 'info') icon = 'ℹ';
            if (type === 'warning') icon = '⚠';
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'fadeOut 0.3s ease-out forwards';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, duration);
            
            return toast;
        }

        window.tambahAsesorisStore = function(nama = '', harga = 0) {
            const wrapper = document.getElementById('storeAsesorisWrapper');
            if (!wrapper) {
                console.error('Element storeAsesorisWrapper tidak ditemukan!');
                return;
            }
            
            console.log('Menambahkan asesoris row ke store modal');
            
            const div = document.createElement('div');
            div.classList.add('asesorisRow');
            div.innerHTML = `
                <input type="text" name="asesoris_nama[]" class="asesorisNama input_form" 
                    placeholder="Nama Asesoris" value="${nama}">
                <input type="number" name="asesoris_harga[]" class="asesorisHarga input_form" 
                    placeholder="Harga" value="${harga}">
                <button type="button" onclick="hapusAsesorisRowStore(this)">-</button>
            `;
            wrapper.appendChild(div);
            
            // Tambahkan event listener untuk input harga
            const hargaInput = div.querySelector('.asesorisHarga');
            if (hargaInput) {
                hargaInput.addEventListener('input', hitungTotalStore);
            }
        }

        window.hapusAsesorisRowStore = function(button) {
            const row = button.closest('.asesorisRow');
            if (row) {
                row.remove();
                hitungTotalStore();
            }
        }

        // Toast helper functions
        window.showSuccessToast = function(message, duration = 3000) {
            return showToast(message, 'success', duration);
        };
        
        window.showErrorToast = function(message, duration = 4000) {
            return showToast(message, 'error', duration);
        };
        
        window.showInfoToast = function(message, duration = 3000) {
            return showToast(message, 'info', duration);
        };
        
        window.showWarningToast = function(message, duration = 3500) {
            return showToast(message, 'warning', duration);
        };
        
        // Handle session messages dari Laravel
        @if(session('success'))
            showSuccessToast('{{ session("success") }}');
        @endif
        
        @if(session('error'))
            showErrorToast('{{ session("error") }}');
        @endif
        
        @if(session('info'))
            showInfoToast('{{ session("info") }}');
        @endif
        
        @if(session('warning'))
            showWarningToast('{{ session("warning") }}');
        @endif
        
        // ==================== 3. JENIS ORDER MODAL ====================
        window.showDeleteJenisOrderModal = function (jenisOrderId, jenisOrderName, event) {
            console.log('SHOW JENIS ORDER MODAL:', jenisOrderId);
            
            if (!deleteJenisOrderModal) {
                console.error('Modal tidak ditemukan di DOM');
                return;
            }
            
            currentJenisOrderId = jenisOrderId;
            currentJenisOrderRow = event?.target?.closest('tr') || null;
            
            document.getElementById('jenisOrderIdToDelete').value = jenisOrderId;
            document.getElementById('jenisOrderNameToDelete').textContent = jenisOrderName;
            
            deleteJenisOrderModal.style.display = 'block';
            deleteJenisOrderModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        
        function closeDeleteJenisOrderModal() {
            if (deleteJenisOrderModal) {
                deleteJenisOrderModal.style.display = 'none';
                deleteJenisOrderModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
            currentJenisOrderId = null;
            currentJenisOrderRow = null;
        }
        
        if (cancelDeleteJenisOrderBtn) {
            cancelDeleteJenisOrderBtn.addEventListener('click', closeDeleteJenisOrderModal);
        }
        
        if (confirmDeleteJenisOrderBtn) {
            confirmDeleteJenisOrderBtn.addEventListener('click', function () {
                if (!currentJenisOrderId) return;
                
                fetch(`/dashboard/orders/setting/${currentJenisOrderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(res => res.json())
                .then(() => {
                    closeDeleteJenisOrderModal();
                    
                    // hapus row
                    if (currentJenisOrderRow) {
                        currentJenisOrderRow.remove();
                    }
                    
                    // update nomor
                    updateTableNumbers();
                    
                    // cek kosong
                    checkEmptyTable();
                    
                    showSuccessToast('Data berhasil dihapus');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                });
            });
        }
        
        // Fungsi untuk update nomor urut tabel
        function updateTableNumbers(tbody) {
            if (!tbody) return;
            
            const rows = tbody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                const numberCell = row.cells[0];
                if (numberCell) {
                    numberCell.querySelector('div').textContent = index + 1;
                }
            });
        }
        
        function checkEmptyTable() {
            const tbody = document.getElementById('body-setting');
            if (!tbody) return;
            
            const rows = tbody.querySelectorAll('tr');
            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            Belum ada data
                        </td>
                    </tr>
                `;
            }
        }
        
        // ==================== 4. MODAL UMUM ====================
        function confirmDelete(event, form) {
            event.preventDefault();
            
            // TAMBAHKAN: Ambil informasi tab aktif SEBELUM melakukan delete
            const activeTab = document.querySelector('.kategori-tab-active');
            const activeSpekTab = document.querySelector('.spek-tab-active');
            
            let currentKategoriId = null;
            let currentSpekId = null;
            
            if (activeTab) {
                currentKategoriId = activeTab.id.replace('tab-', '');
            }
            
            if (activeSpekTab) {
                currentSpekId = activeSpekTab.id.replace('spek-tab-', '');
            }
            
            console.log('Active tab before delete:', currentKategoriId, currentSpekId);
            
            if (!confirm('Yakin ingin menghapus data ini?')) {
                return;
            }
            
            const deleteBtn = form.querySelector('button[type="submit"]');
            const originalText = deleteBtn.textContent;
            
            // Tampilkan loading
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<div class="loading-spinner"></div> Menghapus...';
            
            const url = form.action;
            const formData = new FormData(form);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus row dari tabel
                    const row = form.closest('tr');
                    if (row) {
                        row.remove();
                        
                        // Update nomor urut
                        updateTableNumbers(row.closest('tbody'));
                        
                        // Cek jika tabel kosong
                        const tableBody = row.closest('tbody');
                        if (tableBody && tableBody.querySelectorAll('tr').length === 0) {
                            const tableContainer = tableBody.closest('.orders_table_container');
                            if (tableContainer) {
                                // Hapus elemen yang tidak perlu
                                const existingEmptyMessage = tableContainer.querySelector('.empty-message');
                                if (existingEmptyMessage) existingEmptyMessage.remove();
                                
                                const existingAddButton = tableContainer.querySelector('.add_detail_btn');
                                if (existingAddButton) existingAddButton.remove();
                                
                                // Tambahkan pesan kosong
                                const emptyMessage = document.createElement('div');
                                emptyMessage.className = 'text-gray-500 italic mb-3 empty-message';
                                emptyMessage.textContent = 'Belum ada detail jenis spek';
                                tableContainer.insertBefore(emptyMessage, tableBody);
                                
                                // Tambah button tambah detail
                                const addButton = document.createElement('button');
                                addButton.className = 'add_detail_btn bg-green-500';
                                addButton.textContent = '+ Tambah Detail';
                                addButton.onclick = function() {
                                    openDetailModal(currentKategoriId, currentSpekId, null, '');
                                };
                                tableContainer.appendChild(addButton);
                            }
                        }
                    }
                    
                    // Tampilkan notifikasi SUCCESS
                    showSuccessToast(data.message || 'Data berhasil dihapus');
                    
                    // **JANGAN** panggil fungsi apa pun yang bisa mengubah tab
                    // Tetap di tab yang sama, tidak perlu reload
                    
                } else {
                    showErrorToast(data.message || 'Gagal menghapus data');
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Terjadi kesalahan saat menghapus data');
                deleteBtn.disabled = false;
                deleteBtn.textContent = originalText;
            });
        }
        
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                if (currentForm) currentForm.submit();
            }
        }
        
        if (cancelBtn) {
            cancelBtn.onclick = function() {
                if (modal) {
                    modal.style.display = "none";
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        }
        
        // ==================== 5. MODAL TAMBAH KATEGORI ====================
        window.openTambahKategoriModal = function() {
            console.log('Opening tambah kategori modal');
            if (modalTambahKategori) {
                document.getElementById('namaKategoriInput').value = '';
                modalTambahKategori.style.display = "block";
                modalTambahKategori.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        window.closeTambahKategoriModal = function() {
            console.log('Closing tambah kategori modal');
            if (modalTambahKategori) {
                modalTambahKategori.style.display = "none";
                modalTambahKategori.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
        
        // Handle form tambah kategori
        const formTambahKategori = document.getElementById('formTambahKategori');
        if (formTambahKategori) {
            formTambahKategori.addEventListener('submit', function (e) {
                e.preventDefault();
                
                const namaKategori = document.getElementById('namaKategoriInput').value;
                
                fetch('/dashboard/kategori-jenis-order/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ nama: namaKategori })
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP Error ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (!data?.data?.id) {
                        console.error('Response tidak sesuai', data);
                        return;
                    }
                    
                    const newKategoriId = data.data.id;
                    const newKategoriNama = data.data.nama;
                    
                    const colors = [
                        'tab-blue', 'tab-green', 'tab-purple', 'tab-orange', 'tab-pink',
                        'tab-teal', 'tab-indigo', 'tab-red', 'tab-yellow', 'tab-cyan'
                    ];
                    
                    const index = document.querySelectorAll('.tab-kategori-btn').length;
                    const colorClass = colors[index % colors.length];
                    
                    // TAB BARU
                    const wrapper = document.createElement('div');
                    wrapper.className = 'spesifikasi_layout_btn_grid_box';
                    
                    const tabBtn = document.createElement('button');
                    tabBtn.id = `tab-${newKategoriId}`;
                    tabBtn.className = `tab-kategori-btn ${colorClass}`;
                    tabBtn.dataset.color = colorClass;
                    tabBtn.textContent = newKategoriNama;
                    tabBtn.onclick = () => showTab(newKategoriId);
                    
                    const delBtn = document.createElement('button');
                    delBtn.title = 'Hapus kategori';
                    delBtn.onclick = () => showDeleteKategoriPopup(newKategoriId, newKategoriNama);
                    delBtn.innerHTML = `<img src="/icons/trash-icon.svg">`;
                    
                    wrapper.appendChild(tabBtn);
                    wrapper.appendChild(delBtn);
                    
                    const container = document.querySelector('.spesifikasi_layout_btn');
                    const tambahBtn = container.querySelector('.btn-tambah-kategori');
                    container.insertBefore(wrapper, tambahBtn);
                    
                    // CONTENT BARU
                    const content = document.createElement('div');
                    content.id = `content-${newKategoriId}`;
                    content.className = 'kategori-content hidden';
                    content.innerHTML = `
                        <div class="spesifikasi_layout_btn_grid">
                            <button onclick="openModal(${newKategoriId})" class="bg-green-500 text-white">
                                + Tambah Spek
                            </button>
                        </div>
                        <div class="text-gray-500 italic mt-6">
                            Belum ada jenis spek.
                        </div>
                    `;
                    
                    document.querySelector('.spesifikasi_container').appendChild(content);
                    
                    closeTambahKategoriModal();
                    showTab(newKategoriId);
                })
                .catch(err => {
                    console.error('FETCH ERROR:', err);
                    showErrorToast('Gagal menambahkan kategori');
                });
            });
        }
        
        // ==================== 6. MODAL DELETE KATEGORI ====================
        window.showDeleteKategoriPopup = function(kategoriId, kategoriNama) {
            console.log('Show delete kategori popup:', kategoriId, kategoriNama);
            
            if (deleteKategoriModal) {
                document.getElementById('kategoriIdToDelete').value = kategoriId;
                document.getElementById('kategoriNameToDelete').textContent = kategoriNama;
                
                deleteKategoriModal.style.display = "block";
                deleteKategoriModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        if (cancelDeleteKategoriBtn) {
            cancelDeleteKategoriBtn.onclick = function() {
                if (deleteKategoriModal) {
                    deleteKategoriModal.style.display = "none";
                    deleteKategoriModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        }
        
        if (confirmDeleteKategoriBtn) {
            confirmDeleteKategoriBtn.onclick = function() {
                const kategoriId = document.getElementById('kategoriIdToDelete').value;
                const kategoriNama = document.getElementById('kategoriNameToDelete').textContent;
                
                console.log('Deleting kategori:', kategoriId, kategoriNama);
                
                if (deleteKategoriModal) {
                    deleteKategoriModal.style.display = "none";
                    deleteKategoriModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
                
                // Kirim request DELETE
                fetch(`/dashboard/kategori-jenis-order/${kategoriId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        // Hapus tab dan konten dari DOM
                        const tabElement = document.getElementById(`tab-${kategoriId}`);
                        const contentElement = document.getElementById(`content-${kategoriId}`);
                        
                        if (tabElement) {
                            const wrapper = tabElement.closest('.spesifikasi_layout_btn_grid_box');
                            if (wrapper) wrapper.remove();
                        }
                        
                        if (contentElement) contentElement.remove();
                        
                        // Tampilkan kategori pertama yang tersisa
                        const remainingTabs = document.querySelectorAll('.spesifikasi_layout_btn [id^="tab-"]');
                        console.log('Remaining tabs:', remainingTabs.length);
                        
                        if (remainingTabs.length > 0) {
                            const firstTab = remainingTabs[0];
                            const firstKategoriId = firstTab.id.replace('tab-', '');
                            showTab(parseInt(firstKategoriId));
                        } else {
                            // Jika tidak ada kategori lagi, tampilkan pesan
                            const container = document.querySelector('.spesifikasi_container');
                            
                            // Hapus semua konten kategori yang masih ada
                            document.querySelectorAll('.kategori-content').forEach(el => el.remove());
                            
                            // Buat konten kosong
                            const emptyContent = document.createElement('div');
                            emptyContent.className = 'kategori-content';
                            emptyContent.innerHTML = '<p class="text-center text-gray-500 mt-10">Tidak ada kategori. Silahkan tambah kategori terlebih dahulu.</p>';
                            
                            // Cari tempat yang tepat untuk menambahkan konten
                            const spesifikasiContainer = document.querySelector('.spesifikasi_container');
                            const afterBtnGrid = document.querySelector('.spesifikasi_layout_btn');
                            
                            if (afterBtnGrid && spesifikasiContainer) {
                                spesifikasiContainer.insertBefore(emptyContent, afterBtnGrid.nextSibling);
                            }
                        }
                        
                        showSuccessToast('Kategori berhasil dihapus');
                    } else {
                        showErrorToast('Gagal menghapus kategori: ' + (data?.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Terjadi kesalahan saat menghapus kategori');
                });
            };
        }
        
        // ==================== 7. TAB & SPEK MANAGEMENT ====================
        window.showTab = function(id) {
            activeKategoriId = id;
            
            document.querySelectorAll('.kategori-content')
                .forEach(el => el.classList.add('hidden'));
            
            document.querySelectorAll('.tab-kategori-btn')
                .forEach(el => el.classList.remove('kategori-tab-active'));
            
            const tab = document.getElementById('tab-' + id);
            if (!tab) return;
            
            tab.classList.add('kategori-tab-active');
            
            const content = document.getElementById('content-' + id);
            if (content) content.classList.remove('hidden');
            
            // ambil warna
            const colorClass = tab.dataset.color;
            
            // tab bawah ikut warna
            content.querySelectorAll('.spek-tab-btn').forEach(btn => {
                btn.className = `spek-tab-btn ${colorClass}`;
            });
            
            showFirstSpekInKategori(id);
        }
        
        // Tampilkan spek pertama dalam kategori
        function showFirstSpekInKategori(kategoriId) {
            const content = document.getElementById('content-' + kategoriId);
            if (!content) return;
            
            const firstSpek = content.querySelector('.spek-tab-btn');
            if (!firstSpek) return;
            
            const spekId = firstSpek.id.replace('spek-tab-', '');
            showSpekDetail(kategoriId, spekId);
        }
        
        window.showSpekDetail = function(kategoriId, spekId) {
            const content = document.getElementById('content-' + kategoriId);
            if (!content) return;
            
            // ❌ JANGAN reset ke bg-white
            content.querySelectorAll('.spek-tab-btn').forEach(btn => {
                btn.classList.remove('spek-tab-active');
            });
            
            // ✅ Aktifkan spek
            const activeBtn = document.getElementById('spek-tab-' + spekId);
            if (activeBtn) {
                activeBtn.classList.add('spek-tab-active');
            }
            
            // Tampilkan konten spek
            document
                .querySelectorAll(`[id^="spek-content-${kategoriId}-"]`)
                .forEach(el => el.classList.add('hidden'));
            
            const spekContent = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
            if (spekContent) spekContent.classList.remove('hidden');
            
            activeSpekId = spekId;
        }
        
        // ==================== 8. MODAL TAMBAH SPEK ====================
        window.openModal = function(kategoriId){
            console.log('Opening modal for kategori:', kategoriId);
            if (modalJenisSpek) {
                document.getElementById('kategori_id_input').value = kategoriId;
                document.getElementById('current_kategori_modal_input').value = activeKategoriId;
                document.getElementById('nama_jenis_spek_input').value = '';
                modalJenisSpek.classList.add('active');
            }
        }

        function hitungTotalStore() {
            console.log('hitungTotalStore called'); // Debug
            
            const nilai = parseFloat(document.getElementById('storeNilai').value) || 1;
            
            // Update display nilai
            document.getElementById('nilaiDisplayBahanStore').textContent = nilai;
            document.getElementById('nilaiDisplayKertasStore').textContent = nilai;
            
            // Hitung bahan dan kertas
            const bahan = parseFloat(document.getElementById('storeBahanHarga').value) || 0;
            const kertas = parseFloat(document.getElementById('storeKertasHarga').value) || 0;
            const totalBahan = bahan * nilai;
            const totalKertas = kertas * nilai;
            
            // Update display bahan dan kertas
            document.getElementById('totalBahanDisplayStore').textContent = formatNumber(totalBahan);
            document.getElementById('totalKertasDisplayStore').textContent = formatNumber(totalKertas);
            
            // Hitung ongkos gawe
            const totalOngkosGawe = 
                (hargaJenisPekerjaanData.harga_setting || 0) +
                ((hargaJenisPekerjaanData.harga_print || 0) * nilai) +
                ((hargaJenisPekerjaanData.harga_press || 0) * nilai) +
                (hargaJenisPekerjaanData.harga_cutting || 0) +
                (hargaJenisPekerjaanData.harga_jahit || 0) +
                (hargaJenisPekerjaanData.harga_finishing || 0) +
                (hargaJenisPekerjaanData.harga_packing || 0);
            
            // Update input ongkos gawe
            document.getElementById('storeOngkosGawe').value = totalOngkosGawe;
            
            // Hitung total asesoris
            let totalAsesoris = 0;
            document.querySelectorAll('#storeAsesorisWrapper .asesorisHarga').forEach(input => {
                totalAsesoris += parseFloat(input.value) || 0;
            });
            
            // Hitung grand total
            const total = totalBahan + totalKertas + totalOngkosGawe + totalAsesoris;
            document.getElementById('storeTotalHarga').value = 'Rp ' + formatNumber(total);
            
            console.log('Calculation results:', { // Debug
                nilai, bahan, kertas, totalBahan, totalKertas,
                totalOngkosGawe, totalAsesoris, total
            });
        }

        // Tambahkan fungsi formatNumber jika belum ada
        function formatNumber(angka) {
            if (!angka) angka = 0;
            return angka.toLocaleString('id-ID');
        }
        
        window.closeModal = function(){
            if (modalJenisSpek) {
                modalJenisSpek.classList.remove('active');
            }
        }
        
        // Handle form tambah spek
        const formTambahSpek = document.getElementById('formTambahSpek');
        const btnSubmitSpek = document.getElementById('btnSubmitSpek');
        
        if (formTambahSpek) {
            formTambahSpek.addEventListener('submit', async function (e) {
                e.preventDefault();
                
                const kategoriId = document.getElementById('kategori_id_input').value;
                const namaSpek = document.getElementById('nama_jenis_spek_input').value.trim();
                
                if (!namaSpek) {
                    showErrorToast('Nama jenis spek tidak boleh kosong');
                    return;
                }
                
                // Tampilkan loading
                const originalText = btnSubmitSpek.textContent;
                btnSubmitSpek.innerHTML = '<div class="loading-spinner"></div> Memproses...';
                btnSubmitSpek.disabled = true;
                
                try {
                    console.log('Sending AJAX request...', {
                        id_kategori_jenis_order: kategoriId,
                        nama_jenis_spek: namaSpek,
                        current_kategori_id: activeKategoriId
                    });
                    
                    const response = await fetch('/dashboard/jenis-spek/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            id_kategori_jenis_order: parseInt(kategoriId),
                            nama_jenis_spek: namaSpek,
                            current_kategori_id: parseInt(activeKategoriId)
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP ${response.status}: ${response.statusText}`);
                    }
                    
                    if (data.success) {
                        // Tambahkan spek baru ke tampilan tanpa reload
                        addNewSpekToUI(data.spek, kategoriId);
                        
                        // Reset form dan tutup modal
                        document.getElementById('nama_jenis_spek_input').value = '';
                        closeModal();
                        
                        // Tampilkan notifikasi
                        showSuccessToast(data.message || 'Jenis spek berhasil ditambahkan!');
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan spek');
                    }
                    
                } catch (error) {
                    console.error('Error in AJAX request:', error);
                    showErrorToast('Gagal menambahkan spek: ' + error.message);
                } finally {
                    // Restore button
                    if (btnSubmitSpek) {
                        btnSubmitSpek.textContent = originalText;
                        btnSubmitSpek.disabled = false;
                    }
                }
            });
        }
        
        function addNewSpekToUI(spekData, kategoriId) {
            const kategoriContent = document.getElementById('content-' + kategoriId);
            if (!kategoriContent) return;
            
            // 1. Tambahkan tab button untuk spek baru
            const spekTabGrid = kategoriContent.querySelector('.spesifikasi_layout_btn_grid');
            if (!spekTabGrid) return;
            
            // Cari warna dari kategori tab aktif
            const activeKategoriTab = document.getElementById('tab-' + activeKategoriId);
            const colorClass = activeKategoriTab ? activeKategoriTab.dataset.color : 'tab-blue';
            
            // Buat elemen tab button baru
            const spekTabWrapper = document.createElement('div');
            spekTabWrapper.className = 'spesifikasi_layout_btn_grid_box';
            
            spekTabWrapper.innerHTML = `
                <button
                    onclick="showSpekDetail(${kategoriId}, ${spekData.id})"
                    id="spek-tab-${spekData.id}"
                    class="spek-tab-btn ${colorClass}"
                >
                    ${spekData.nama_jenis_spek}
                </button>
                <button
                    onclick="showDeleteSpekPopup(${spekData.id}, ${kategoriId}, '${spekData.nama_jenis_spek}')"
                    title="Hapus jenis spek"
                >
                    <img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon" class="w-full h-full">
                </button>
            `;
            
            // Sisipkan sebelum tombol "Tambah Spek"
            const tambahSpekBtn = spekTabGrid.querySelector('.btn-add-style');
            if (tambahSpekBtn) {
                spekTabGrid.insertBefore(spekTabWrapper, tambahSpekBtn);
            } else {
                spekTabGrid.appendChild(spekTabWrapper);
            }
            
            // 2. Tambahkan konten untuk spek baru
            const spekContentWrapper = document.createElement('div');
            spekContentWrapper.id = `spek-content-${kategoriId}-${spekData.id}`;
            spekContentWrapper.className = 'hidden mt-6';
            spekContentWrapper.innerHTML = `
                <div class="text-gray-500 italic mb-3">
                    Belum ada detail jenis spek
                </div>
                <button
                    onclick="openDetailModal(${kategoriId}, ${spekData.id}, null, '')"
                    class="add_detail_btn bg-green-500"
                >
                    + Tambah Detail
                </button>
            `;
            
            // Tambahkan ke konten kategori
            kategoriContent.appendChild(spekContentWrapper);
            
            // 3. Tampilkan spek yang baru ditambahkan
            setTimeout(() => {
                showSpekDetail(kategoriId, spekData.id);
            }, 100);
        }
        
        // ==================== 9. MODAL DELETE SPEK ====================
        window.showDeleteSpekPopup = function(spekId, kategoriId, spekName) {
            console.log('Show delete spek popup:', spekId, kategoriId, spekName);
            
            if (deleteSpekModal) {
                document.getElementById('spekIdToDelete').value = spekId;
                document.getElementById('kategoriIdToDelete').value = kategoriId;
                document.getElementById('spekNameToDelete').textContent = spekName;
                
                deleteSpekModal.style.display = "block";
                deleteSpekModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        if (cancelDeleteSpekBtn) {
            cancelDeleteSpekBtn.onclick = function() {
                if (deleteSpekModal) {
                    deleteSpekModal.style.display = "none";
                    deleteSpekModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        }
        
        if (confirmDeleteSpekBtn) {
            confirmDeleteSpekBtn.onclick = function() {
                const spekId = document.getElementById('spekIdToDelete').value;
                const kategoriId = document.getElementById('kategoriIdToDelete').value;
                
                console.log('Deleting spek:', spekId, kategoriId);
                
                if (deleteSpekModal) {
                    deleteSpekModal.style.display = "none";
                    deleteSpekModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
                
                // Simpan informasi tab yang aktif
                const activeTabBtn = document.querySelector('.kategori-tab-active');
                let shouldStayInSameTab = false;
                let activeKategoriIdAfterDelete = null;
                
                if (activeTabBtn && activeTabBtn.id === `tab-${kategoriId}`) {
                    // Kita berada di tab yang sama dengan yang akan dihapus
                    shouldStayInSameTab = true;
                }
                
                // Kirim AJAX DELETE request
                fetch(`/dashboard/jenis-spek/${spekId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        current_kategori_id: kategoriId,
                        _method: 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hapus dari UI
                        const tabElement = document.getElementById(`spek-tab-${spekId}`);
                        const contentElement = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
                        
                        if (tabElement && tabElement.parentElement) {
                            tabElement.parentElement.remove();
                        }
                        if (contentElement) {
                            contentElement.remove();
                        }
                        
                        showSuccessToast('Jenis spek berhasil dihapus');
                        
                        // JANGAN otomatis pindah ke spek pertama
                        // Jika kita di tab yang sama, tetap di tab itu tanpa menunjukkan spek apa pun
                        if (shouldStayInSameTab) {
                            // Cek apakah masih ada spek lain di kategori ini
                            const remainingTabs = document.querySelectorAll(`#content-${kategoriId} [id^="spek-tab-"]`);
                            if (remainingTabs.length > 0) {
                                // Masih ada spek lain, bisa tetap di tab yang sama
                                // Atau biarkan kosong, sesuai preferensi
                                console.log('Masih ada spek lain, tetap di tab yang sama');
                            } else {
                                // Tidak ada spek lagi, tampilkan pesan kosong
                                const kategoriContent = document.getElementById(`content-${kategoriId}`);
                                if (kategoriContent) {
                                    // Hapus semua konten spek yang mungkin masih ada
                                    kategoriContent.querySelectorAll('[id^="spek-content-"]').forEach(el => el.remove());
                                    
                                    // Tampilkan pesan
                                    const emptyMessage = document.createElement('div');
                                    emptyMessage.className = 'text-gray-500 italic mt-6';
                                    emptyMessage.textContent = 'Tidak ada jenis spek. Silakan tambah spek terlebih dahulu.';
                                    
                                    // Tambahkan tombol tambah spek
                                    const addButton = document.createElement('button');
                                    addButton.className = 'bg-green-500 text-white px-4 py-2 rounded mt-3';
                                    addButton.textContent = '+ Tambah Spek';
                                    addButton.onclick = function() {
                                        openModal(kategoriId);
                                    };
                                    
                                    // Cari container yang tepat untuk menambahkan pesan
                                    const btnGrid = kategoriContent.querySelector('.spesifikasi_layout_btn_grid');
                                    if (btnGrid) {
                                        kategoriContent.insertBefore(emptyMessage, btnGrid.nextSibling);
                                        kategoriContent.appendChild(addButton);
                                    }
                                }
                            }
                        }
                    } else {
                        showErrorToast('Gagal menghapus: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Terjadi kesalahan saat menghapus');
                });
            };
        }

        let originalFormDetailSpek = null;
        
        // ==================== 10. MODAL DETAIL SPEK ====================
        window.openDetailModal = function(kategoriId, spekId, detailId, detailNama, selectedJenisOrderIds = null) {
            if (!modalJenisSpekDetail) return;
            
            console.log('Opening detail modal:', { kategoriId, spekId, detailId, detailNama });
            
            // Simpan form asli jika belum disimpan
            if (!originalFormDetailSpek) {
                originalFormDetailSpek = document.getElementById('formDetailSpek');
            }
            
            // Clone form untuk menghindari event listener stacking
            const formContainer = document.querySelector('#modalJenisSpekDetail form');
            if (formContainer) {
                const newForm = originalFormDetailSpek.cloneNode(true);
                newForm.id = 'formDetailSpek';
                newForm.reset(); // Reset form cloned
                
                // Ganti form lama dengan yang baru
                formContainer.parentNode.replaceChild(newForm, formContainer);
            }
            
            // Sekarang ambil form yang baru
            const form = document.getElementById('formDetailSpek');
            if (!form) {
                console.error('Form tidak ditemukan setelah clone!');
                return;
            }
            
            const methodInput = document.getElementById('detail_method_input');
            const detailIdInput = document.getElementById('detail_id_input');
            const spekInput = document.getElementById('detail_spek_id_input');
            const kategoriInput = document.getElementById('current_kategori_id_input');
            const currentSpekInput = document.getElementById('current_spek_id_input');
            const namaInput = document.getElementById('detail_nama_input');
            const titleEl = document.getElementById('modalDetailTitle');
            const submitBtn = document.getElementById('submitDetailBtn');
            const gambarInput = document.getElementById('detail_gambar_input');
            
            // Reset form
            form.reset();
            
            // Set nilai default
            if (kategoriInput) kategoriInput.value = kategoriId;
            if (currentSpekInput) currentSpekInput.value = spekId;
            if (spekInput) spekInput.value = spekId;
            if (namaInput) namaInput.value = detailNama || '';
            if (detailIdInput) detailIdInput.value = detailId || '';
            if (gambarInput) gambarInput.value = '';
            
            // Convert selectedJenisOrderIds ke array jika bukan null
            let selectedIds = [];
            if (selectedJenisOrderIds && Array.isArray(selectedJenisOrderIds)) {
                selectedIds = selectedJenisOrderIds;
            } else if (selectedJenisOrderIds) {
                selectedIds = [selectedJenisOrderIds];
            }
            
            // Populate jenis_order checkbox berdasarkan kategori
            populateJenisOrderCheckbox(kategoriId, selectedIds);
            
            if (detailId) {
                // EDIT MODE
                if (titleEl) titleEl.textContent = 'Edit Jenis Spek Detail';
                if (methodInput) methodInput.value = 'PUT';
                if (submitBtn) submitBtn.textContent = 'Update';
                
                // Set action untuk update
                form.action = `/dashboard/jenis-spek-detail/${detailId}`;
                
            } else {
                // CREATE MODE
                if (titleEl) titleEl.textContent = 'Tambah Jenis Spek Detail';
                if (methodInput) methodInput.value = 'POST';
                if (submitBtn) submitBtn.textContent = 'Simpan';
                
                // Set action untuk create
                form.action = "{{ route('jenis_spek_detail.store') }}";
            }
            
            console.log('Form action set to:', form.action);
            console.log('Method set to:', methodInput ? methodInput.value : 'N/A');
            
            modalJenisSpekDetail.classList.add('active');
        };

        // Fungsi untuk menambah row detail baru ke tabel
        function addNewDetailRow(detailData, kategoriId, spekId) {
            console.log('Adding new detail row - Kategori:', kategoriId, 'Spek:', spekId);
            
            // Cari tabel yang sesuai
            const tableBody = document.querySelector(`#spek-content-${kategoriId}-${spekId} tbody`);
            
            if (!tableBody) {
                console.log('Table body tidak ditemukan, membuat struktur baru...');
                
                // Cari konten spek
                const spekContent = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
                if (!spekContent) {
                    console.error('Spek content tidak ditemukan');
                    return;
                }
                
                // Hapus konten lama jika ada (pesan kosong, dll)
                const oldMessages = spekContent.querySelectorAll('.text-gray-500.italic, .text-gray-500, .empty-message, .add_detail_btn');
                oldMessages.forEach(el => el.remove());
                
                // Buat struktur tabel baru
                const tableContainer = document.createElement('div');
                tableContainer.className = 'orders_table_container';
                
                tableContainer.innerHTML = `
                    <table class="w-full border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th><div class="text-center">No</div></th>
                                <th><div class="text-center">Nama Detail</div></th>
                                <th><div class="text-center">Gambar</div></th>
                                <th>Kategori</th>
                                <th><div class="text-center">Aksi</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows akan ditambahkan di sini -->
                        </tbody>
                    </table>
                    <button onclick="openDetailModal(${kategoriId}, ${spekId}, null, '')"
                            class="add_detail_btn bg-green-500">
                        + Tambah Detail
                    </button>
                `;
                
                spekContent.appendChild(tableContainer);
                
                // Dapatkan tableBody yang baru
                const newTableBody = tableContainer.querySelector('tbody');
                
                // Tambahkan row pertama
                const newRow = createDetailRow(detailData, kategoriId, spekId, 1);
                newTableBody.appendChild(newRow);
                
                console.log('Struktur tabel baru dibuat dan row ditambahkan');
                
            } else {
                // Jika tabel sudah ada, tambahkan row baru
                const rowCount = tableBody.querySelectorAll('tr').length;
                const newRowNumber = rowCount + 1;
                
                const newRow = createDetailRow(detailData, kategoriId, spekId, newRowNumber);
                tableBody.appendChild(newRow);
                
                // Hapus pesan kosong jika ada
                const emptyMessage = tableBody.closest('.orders_table_container').querySelector('.text-gray-500.italic, .empty-message');
                if (emptyMessage) {
                    emptyMessage.remove();
                }
                
                console.log('Row baru ditambahkan ke tabel yang sudah ada');
            }
            
            // **JANGAN** panggil showSpekDetail atau fungsi lain yang mengubah tab
        }

        function createDetailRow(detailData, kategoriId, spekId, rowNumber) {
            // Format jenis order yang dipilih
            let jenisOrderHtml = '';
            if (detailData.jenis_order && detailData.jenis_order.length > 0) {
                // ... (kode format jenis order sama seperti sebelumnya)
                const selectedCount = detailData.jenis_order.length;
                const totalJenisOrder = @json($jenisOrderList)
                    .filter(jo => jo.id_kategori_jenis_order == kategoriId).length;
                
                if (selectedCount === totalJenisOrder) {
                    jenisOrderHtml = '<span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-light">Semua</span>';
                } else {
                    jenisOrderHtml = '<div class="jenis_flex">';
                    detailData.jenis_order.forEach(jo => {
                        if (jo && jo.nama_jenis) {
                            jenisOrderHtml += `<span class="px-3 py-1 bg-gray-800 text-white rounded-full text-sm font-light">${jo.nama_jenis}</span>`;
                        }
                    });
                    jenisOrderHtml += '</div>';
                }
            } else {
                jenisOrderHtml = '<span class="text-gray-800">-</span>';
            }
            
            // Format gambar
            const gambarHtml = detailData.gambar 
                ? `<div class="text-center table_img"><img src="/storage/${detailData.gambar}" alt="Gambar" style="width: 50px; height: 50px; object-fit: cover;"></div>`
                : '<span>Tidak ada</span>';
            
            // Buat row
            const newRow = document.createElement('tr');
            newRow.className = rowNumber % 2 === 0 ? 'bg-gray-200' : 'bg-white';
            newRow.innerHTML = `
                <td><div class="text-center">${rowNumber}</div></td>
                <td>${detailData.nama_jenis_spek_detail}</td>
                <td>${gambarHtml}</td>
                <td>${jenisOrderHtml}</td>
                <td>
                    <div class="btn_table_action">
                        <button onclick="openDetailModal(${kategoriId}, ${spekId}, ${detailData.id}, '${detailData.nama_jenis_spek_detail.replace(/'/g, "\\'")}', ${JSON.stringify(detailData.jenis_order ? detailData.jenis_order.map(jo => jo.id) : [])})"
                            class="bg-blue-500">
                            Edit
                        </button>
                        <button 
                            type="button" 
                            onclick="deleteDetail(${detailData.id}, ${kategoriId}, ${spekId}, this)"
                            class="bg-red delete-detail-btn"
                            data-detail-id="${detailData.id}"
                            data-kategori-id="${kategoriId}"
                            data-spek-id="${spekId}"
                        >
                            Hapus
                        </button>
                    </div>
                </td>
            `;
            
            return newRow;
        }

        function updateDetailRow(detailData, kategoriId, spekId) {
            console.log('Updating detail row:', detailData);
            
            if (!detailData || !detailData.id) {
                console.error('Detail data tidak valid:', detailData);
                return;
            }
            
            const detailId = detailData.id;
            
            // Cari semua button di tabel yang sesuai
            const buttons = document.querySelectorAll(`#spek-content-${kategoriId}-${spekId} button`);
            let targetRow = null;
            
            // Cari button yang memiliki onclick dengan detailId
            for (let button of buttons) {
                const onclickAttr = button.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes(`openDetailModal`) && onclickAttr.includes(`${detailId}`)) {
                    targetRow = button.closest('tr');
                    break;
                }
            }
            
            if (!targetRow) {
                console.error('Row tidak ditemukan untuk detail ID:', detailId);
                console.error('Mencari di spek-content-', kategoriId, '-', spekId);
                return;
            }
            
            console.log('Found row:', targetRow);
            
            // Update data di row
            // Update nama (cell index 1)
            const namaCell = targetRow.cells[1];
            if (namaCell) {
                namaCell.textContent = detailData.nama_jenis_spek_detail;
            }
            
            // Update gambar (cell index 2)
            const gambarCell = targetRow.cells[2];
            if (gambarCell) {
                if (detailData.gambar) {
                    gambarCell.innerHTML = `<div class="text-center table_img"><img src="/storage/${detailData.gambar}" alt="Gambar" style="width: 50px; height: 50px; object-fit: cover;"></div>`;
                } else {
                    gambarCell.innerHTML = '<span>Tidak ada</span>';
                }
            }
            
            // Update jenis order (cell index 3)
            const jenisOrderCell = targetRow.cells[3];
            if (jenisOrderCell) {
                let jenisOrderHtml = '';
                if (detailData.jenis_order && detailData.jenis_order.length > 0) {
                    jenisOrderHtml = '<div class="jenis_flex">';
                    detailData.jenis_order.forEach(jo => {
                        if (jo && jo.nama_jenis) {
                            jenisOrderHtml += `<span class="px-3 py-1 bg-gray-800 text-white rounded-full text-sm font-light">${jo.nama_jenis}</span>`;
                        }
                    });
                    jenisOrderHtml += '</div>';
                } else {
                    jenisOrderHtml = '<span class="text-gray-800">-</span>';
                }
                jenisOrderCell.innerHTML = jenisOrderHtml;
            }
            
            // Update button onclick dengan data baru
            const editButton = targetRow.querySelector('.btn_table_action .bg-blue-500');
            if (editButton) {
                const selectedIds = detailData.jenis_order ? detailData.jenis_order.map(jo => jo.id) : [];
                const escapedNama = detailData.nama_jenis_spek_detail.replace(/'/g, "\\'");
                editButton.setAttribute('onclick', 
                    `openDetailModal(${kategoriId}, ${spekId}, ${detailId}, '${escapedNama}', ${JSON.stringify(selectedIds)})`
                );
            }
            
            // Update button delete
            const deleteButton = targetRow.querySelector('.delete-detail-btn');
            if (deleteButton) {
                deleteButton.setAttribute('onclick', `deleteDetail(${detailId}, ${kategoriId}, ${spekId}, this)`);
                deleteButton.dataset.detailId = detailId;
                deleteButton.dataset.kategoriId = kategoriId;
                deleteButton.dataset.spekId = spekId;
            }
            
            console.log('Row updated successfully');
        }
        
        function populateJenisOrderCheckbox(kategoriId, selectedJenisOrderIds = []) {
            const container = document.getElementById('jenisOrderCheckboxContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            const allJenisOrder = @json($jenisOrderList);
            const filteredJenisOrder = allJenisOrder.filter(
                jo => jo.id_kategori_jenis_order == kategoriId
            );
            
            if (filteredJenisOrder.length === 0) {
                container.innerHTML = '<p class="text-none-style">Tambah Kategori dulu!</p>';
                return;
            }
            
            /* CHECKBOX PILIH SEMUA */
            const selectAllWrapper = document.createElement('div');
            selectAllWrapper.className = 'spek_checkbox_item mb-2';
            
            const selectAllCheckbox = document.createElement('input');
            selectAllCheckbox.type = 'checkbox';
            selectAllCheckbox.id = 'select_all_jenis_order';
            
            const selectAllLabel = document.createElement('label');
            selectAllLabel.htmlFor = 'select_all_jenis_order';
            selectAllLabel.textContent = 'Pilih Semua';
            selectAllLabel.className = 'cursor-pointer font-semibold';
            
            selectAllWrapper.appendChild(selectAllCheckbox);
            selectAllWrapper.appendChild(selectAllLabel);
            container.appendChild(selectAllWrapper);
            
            /* CHECKBOX ITEM */
            const itemCheckboxes = [];
            
            filteredJenisOrder.forEach(jo => {
                const div = document.createElement('div');
                div.className = 'spek_checkbox_item';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'id_jenis_order[]';
                checkbox.value = jo.id;
                checkbox.id = `jenis_order_${jo.id}`;
                
                if (selectedJenisOrderIds.includes(jo.id)) {
                    checkbox.checked = true;
                }
                
                const label = document.createElement('label');
                label.htmlFor = checkbox.id;
                label.className = 'cursor-pointer';
                label.textContent = jo.nama_jenis;
                
                div.appendChild(checkbox);
                div.appendChild(label);
                container.appendChild(div);
                
                itemCheckboxes.push(checkbox);
            });
            
            /* LOGIC PILIH SEMUA */
            // Jika klik "Pilih Semua"
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
            });
            
            // Jika item di klik manual
            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    selectAllCheckbox.checked =
                        itemCheckboxes.length > 0 &&
                        itemCheckboxes.every(i => i.checked);
                });
            });
            
            // Set awal kondisi "Pilih Semua"
            selectAllCheckbox.checked =
                itemCheckboxes.length > 0 &&
                itemCheckboxes.every(i => i.checked);
        }
        
        window.closeDetailModal = function(){
            if (modalJenisSpekDetail) {
                modalJenisSpekDetail.classList.remove('active');
            }
        }
        
        // ==================== 11. JOB MANAGEMENT ====================
        // Tangani semua form delete job
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const jobId = form.action.split('/').pop();
                
                // Kirim request
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
        
        // ==================== 12. TAB SWITCHING ====================
        window.switchTab = function(tab) {
            const contents = {
                ongkos: document.getElementById('content-ongkos'),
                job: document.getElementById('content-job'),
                affiliator: document.getElementById('content-affiliator'),
                spesifikasi: document.getElementById('content-spesifikasi'),
                hargaBarang: document.getElementById('content-harga-barang'),
                kemampuanProduksi: document.getElementById('content-kemampuanProduksi')
            };
            
            const tabs = {
                ongkos: document.getElementById('tabOngkos'),
                job: document.getElementById('tabJob'),
                affiliator: document.getElementById('tabAffiliator'),
                spesifikasi: document.getElementById('tabSpesifikasi'),
                hargaBarang: document.getElementById('tabHargaBarang'),
                kemampuanProduksi: document.getElementById('tabKemampuanProduksi')
            };
            
            // hide semua content
            Object.values(contents).forEach(c => c && c.classList.add('hidden'));
            
            // reset semua tab
            Object.values(tabs).forEach(t => {
                if (!t) return;
                t.classList.remove('bg-green-500');
                t.classList.add('bg-white');
            });
            
            // aktifkan tab
            contents[tab]?.classList.remove('hidden');
            tabs[tab]?.classList.add('bg-green-500');
            tabs[tab]?.classList.remove('bg-white');
            
            // update hash URL
            history.replaceState(null, '', '#' + tab);
            
            // simpan state
            localStorage.setItem('pengaturanActiveTab', tab);
        }
        
        // Restore last active tab after page load
        const allowedTabs = ['ongkos','job','affiliator','spesifikasi', 'hargaBarang', 'kemampuanProduksi'];
        
        // 1️⃣ cek hash URL
        const hashTab = window.location.hash.replace('#', '');
        if (allowedTabs.includes(hashTab)) {
            switchTab(hashTab);
        } else {
            // 2️⃣ cek localStorage
            try {
                const savedTab = localStorage.getItem('pengaturanActiveTab');
                if (allowedTabs.includes(savedTab)) {
                    switchTab(savedTab);
                } else {
                    // 3️⃣ default
                    switchTab('ongkos');
                }
            } catch (e) {
                switchTab('ongkos');
            }
        }
        
        window.addEventListener('hashchange', function () {
            const tab = window.location.hash.replace('#', '');
            if (allowedTabs.includes(tab)) {
                switchTab(tab);
            }
        });
        
        // ==================== 13. HARGA BARANG SCRIPTS ====================
        const hargaJenisPekerjaanData = {
            harga_setting: {{ $harga->harga_setting ?? 5000 }},
            harga_print: {{ $harga->harga_print ?? 10000 }},
            harga_press: {{ $harga->harga_press ?? 8000 }},
            harga_cutting: {{ $harga->harga_cutting ?? 3000 }},
            harga_jahit: {{ $harga->harga_jahit ?? 7000 }},
            harga_finishing: {{ $harga->harga_finishing ?? 4000 }},
            harga_packing: {{ $harga->harga_packing ?? 2000 }}
        };

        function tambahAsesorisStore(nama = '', harga = 0) {
            const wrapper = document.getElementById('storeAsesorisWrapper');
            const div = document.createElement('div');
            div.classList.add('asesorisRow');
            div.innerHTML = `
                <input type="text" name="asesoris_nama[]" class="asesorisNama input_form" 
                    placeholder="Nama Asesoris" value="${nama}">
                <input type="number" name="asesoris_harga[]" class="asesorisHarga input_form" 
                    placeholder="Harga" value="${harga}" oninput="hitungTotalStore()">
                <button type="button" onclick="hapusAsesorisRowStore(this)">-</button>
            `;
            wrapper.appendChild(div);
        }

        function hapusAsesorisRowStore(button) {
            const row = button.closest('.asesorisRow');
            if (row) {
                row.remove();
                hitungTotalStore();
            }
        }
        
        window.openModalJenisOrder = function() {
            // Reset form
            if (document.getElementById('formStoreJenisOrder')) {
                document.getElementById('formStoreJenisOrder').reset();
                document.getElementById('storeNilai').value = 1;
                document.getElementById('storeBahanHarga').value = 0;
                document.getElementById('storeKertasHarga').value = 0;
                
                const nilai = 1;
                
                // Update display nilai
                document.getElementById('nilaiDisplayBahanStore').textContent = nilai;
                document.getElementById('nilaiDisplayKertasStore').textContent = nilai;
                
                // Hitung ongkos gawe awal
                hitungTotalStore();
                
                // Reset asesoris
                const wrapper = document.getElementById('storeAsesorisWrapper');
                if (wrapper) {
                    wrapper.innerHTML = `
                        <div class="form_between_heading">
                            <label style="margin:0;">Asesoris</label>
                            <button type="button" onclick="tambahAsesorisStore()">
                                + Tambah Asesoris
                            </button>
                        </div>
                    `;
                    tambahAsesorisStore(); // Tambah 1 baris kosong
                }
            }
            
            // Tampilkan modal
            if (modalJenisOrder) {
                modalJenisOrder.classList.add('active');
            }
        }
        
        window.closeModalJenisOrder = function() {
            if (modalJenisOrder) {
                modalJenisOrder.classList.remove('active');
            }
        }
        
        // ==================== 14. JOB MODAL FUNCTIONS ====================
        window.openJobCreateModal = function() {
            if (modalJob) {
                document.getElementById('modalJobTitle').textContent = 'Tambah Job';
                document.getElementById('jobId').value = '';
                document.getElementById('jobNama').value = '';
                modalJob.classList.add('active');
            }
        }
        
        window.openJobEditModal = function(id, nama) {
            if (modalJob) {
                document.getElementById('modalJobTitle').textContent = 'Edit Job';
                document.getElementById('jobId').value = id;
                document.getElementById('jobNama').value = nama;
                modalJob.classList.add('active');
            }
        }
        
        window.closeJobModal = function() {
            if (modalJob) {
                modalJob.classList.remove('active');
            }
        }
        
        window.simpanJob = function(e) {
            e.preventDefault();
            const id = document.getElementById('jobId').value;
            const nama = document.getElementById('jobNama').value.trim();
            if (!nama) {
                showErrorToast('Nama job wajib diisi');
                return;
            }
            
            const url = id ? `/dashboard/pengaturan/job/${id}` : '/dashboard/pengaturan/job';
            const method = id ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nama_job: nama })
            })
            .then(async res => {
                const text = await res.text();
                let data = null;
                try { data = JSON.parse(text); } catch (e) { /* not JSON */ }
                if (res.ok && data && data.success) {
                    location.reload();
                    return;
                }
                
                console.error('Job save failed', res.status, text, data);
                showErrorToast('Gagal menyimpan job');
            })
            .catch(err => {
                console.error(err);
                showErrorToast('Terjadi kesalahan');
            });
        }
        
        // ==================== 15. CLOSE MODAL ON CLICK OUTSIDE ====================
        document.addEventListener('click', function(event) {
            // Tutup modal jika klik di luar modal content
            if (modalTambahKategori && event.target === modalTambahKategori) {
                closeTambahKategoriModal();
            }
            if (deleteKategoriModal && event.target === deleteKategoriModal) {
                deleteKategoriModal.style.display = "none";
                deleteKategoriModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
            if (deleteSpekModal && event.target === deleteSpekModal) {
                deleteSpekModal.style.display = "none";
                deleteSpekModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
            if (modal && event.target === modal) {
                modal.style.display = "none";
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
            if (deleteJenisOrderModal && event.target === deleteJenisOrderModal) {
                closeDeleteJenisOrderModal();
            }
        });
        
        const currentHash = window.location.hash.replace('#', '');

        // ==================== 16. INISIALISASI AWAL ====================
        if (currentHash === 'spesifikasi') {
            @if($kategori->count() > 0 && $kategori->first()->jenisSpek->count() > 0)
                // Hanya inisialisasi jika belum ada tab aktif
                const hasActiveTab = document.querySelector('.kategori-tab-active') || 
                                    document.querySelector('.spek-tab-active');
                
                if (!hasActiveTab) {
                    setTimeout(() => {
                        const firstKategoriId = {{ $kategori->first()->id }};
                        const firstSpekId = {{ $kategori->first()->jenisSpek->first()->id }};
                        showTab(firstKategoriId);
                        showSpekDetail(firstKategoriId, firstSpekId);
                    }, 100);
                }
            @endif
        }
        
        // Tambahkan CSS untuk notification jika belum ada
        if (!document.querySelector('style#notification-style')) {
            const style = document.createElement('style');
            style.id = 'notification-style';
            style.textContent = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 5px;
                    color: white;
                    font-weight: bold;
                    z-index: 10000;
                    display: none;
                    animation: slideIn 0.3s ease;
                }
                
                .notification-success {
                    background-color: #4CAF50;
                }
                
                .notification-error {
                    background-color: #f44336;
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
            `;
            document.head.appendChild(style);
        }
        
        console.log('All scripts initialized successfully');
    });
</script>

@endsection
