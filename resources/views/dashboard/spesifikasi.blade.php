@extends('layouts.dashboard')

@section('title','Spesifikasi')

@section('content')

<div>
    <div class="dashboard_banner dashboard_banner_print">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
    </div>
    <div class="spesifikasi_container">
        <div class="spesifikasi_layout_btn">
            @foreach($kategori as $index => $k)
                <div class="spesifikasi_layout_btn_grid_box">
                    <button
                        onclick="showTab({{ $k->id }})"
                        id="tab-{{ $k->id }}"
                        class="{{ $index === 0 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}"
                    >
                        {{ $k->nama }}
                    </button>
                    <button
                        onclick="showDeleteKategoriPopup({{ $k->id }}, '{{ $k->nama }}')"
                        title="Hapus kategori"
                    >
                        <img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon">
                    </button>
                </div>
            @endforeach
            <button
                onclick="openTambahKategoriModal()"
                class="bg-green-500 text-white"
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
                                class="spek-tab-btn {{ $index === 0 && $index2 === 0 ? 'bg-blue-500 text-white' : 'bg-white' }} pr-10"
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
                        class="bg-green-500"
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
                                                    @if($d->jenisOrder->count())
                                                        <div class="jenis_flex">
                                                            @foreach($d->jenisOrder as $jo)
                                                                <span class="bg-gray-800 text-white" style="font-weight:300;">
                                                                    {{ $jo->nama_jenis }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-gray-800">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn_table_action">
                                                        <button
                                                            onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, {{ $d->id }}, '{{ $d->nama_jenis_spek_detail }}', {{ $d->jenisOrder->pluck('id')->toJson() }})"
                                                            class="bg-blue-500"
                                                        >
                                                            Edit
                                                        </button>
                                                        <form 
                                                            action="{{ url('/dashboard/jenis-spek-detail/' . $d->id) }}" 
                                                            method="POST" 
                                                            style="display:inline;"
                                                            onsubmit="confirmDelete(event, this)"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="current_kategori_id" value="{{ $k->id }}">
                                                            <input type="hidden" name="current_spek_id" value="{{ $s->id }}">
                                                            <button type="submit"  class="bg-red">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button
                                    onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, null, '')"
                                    class="add_detail_btn bg-green-500"
                                >
                                    + Tambah Detail
                                </button>
                            </div>
                        @else
                            <div class="text-gray-500 italic mb-3">
                                Belum ada detail jenis spek
                            </div>
                            <button
                                onclick="openDetailModal({{ $k->id }}, {{ $s->id }}, null, '')"
                                class="add_detail_btn bg-green-500"
                            >
                                + Tambah Detail
                            </button>
                        @endif
                    </div>
                @endforeach

            </div>
        @endforeach
    </div>
</div>

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

        <form action="{{ route('jenis_spek.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_kategori_jenis_order" id="kategori_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_modal_input">

            <div class="form_field_normal">
                <label>Nama Jenis Spek</label>
                <input
                    type="text"
                    name="nama_jenis_spek"
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
                >
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL JENIS SPEK DETAIL -->
<div id="modalJenisSpekDetail"  class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeDetailModal()" class="overlay_close"></div>    
    <div class="dashboard_popup_order_box">

        <div class="dashboard_popup_order_heading">
            <h2 id="modalDetailTitle">Tambah Jenis Spek Detail</h2>
            <button onclick="closeDetailModal()">&times;</button>
        </div>

        <form id="formDetailSpek" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_jenis_spek" id="detail_spek_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_id_input">
            <input type="hidden" name="current_spek_id" id="current_spek_id_input">
            <input type="hidden" name="_method" id="detail_method_input" value="POST">

            <div class="form_field_normal">
                <label>Nama Detail</label>
                <input
                    type="text"
                    name="nama_jenis_spek_detail"
                    id="detail_nama_input"
                    class="input_form"
                    placeholder="Contoh: Benzema, Brazil ..."
                    required>
            </div>

            <div class="form_field_normal form_field_normal_link">
                <label>Kategori</label>
                <div id="jenisOrderCheckboxContainer" class="spek_checkbox_style">
                    <!-- Checkbox akan diisi oleh JavaScript saat modal dibuka -->
                </div>
                <a href="/dashboard/pengaturan#setting">Tambah Kategori</a>
            </div>

            <div class="form_field_normal">
                <label>Gambar (Opsional)</label>
                <input
                    type="file"
                    name="gambar"
                    id="detail_gambar_input"
                    accept="image/*"
                    class="input_form"> 
            </div>

            <div class="dashboard_popup_order_btn">
                <button 
                    type="button"
                    onclick="closeDetailModal()"
                >
                    Batal
                </button>

                <button 
                    type="submit"
                >
                    Simpan
                </button>
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

<script>
    // Variabel global untuk menyimpan state tab aktif
    let activeKategoriId = {{ $kategori->first()->id ?? 0 }};
    let activeSpekId = null;
    let currentForm = null;
    const modal = document.getElementById("myModal");
    const confirmBtn = document.getElementById("confirmBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    function confirmDelete(event, form) {
        event.preventDefault(); // Mencegah form terhapus otomatis
        currentForm = form;     // Simpan form yang diklik
        modal.style.display = "block"; // Munculkan modal
    }

    // Jika tombol Hapus di dalam modal diklik
    confirmBtn.onclick = function() {
        if (currentForm) currentForm.submit();
    }

    // Jika tombol Batal diklik
    cancelBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Jika klik di luar kotak modal, tutup modal
    window.onclick = function(event) {
        if (event.target == modal) modal.style.display = "none";
        if (event.target == document.getElementById('modalTambahKategori')) {
            closeTambahKategoriModal();
        }
    }
</script>

<script>
// CSRF Token untuk AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                 document.querySelector('input[name="_token"]')?.value ||
                 '{{ csrf_token() }}';

// Modal untuk hapus jenis spek
const deleteSpekModal = document.getElementById('deleteSpekModal');
const confirmDeleteSpekBtn = document.getElementById('confirmDeleteSpekBtn');
const cancelDeleteSpekBtn = document.getElementById('cancelDeleteSpekBtn');

// Modal untuk tambah kategori
const modalTambahKategori = document.getElementById('modalTambahKategori');

// Fungsi untuk membuka modal tambah kategori
function openTambahKategoriModal() {
    document.getElementById('namaKategoriInput').value = '';
    modalTambahKategori.style.display = "block";
}

// Fungsi untuk menutup modal tambah kategori
function closeTambahKategoriModal() {
    modalTambahKategori.style.display = "none";
}

// Handle form tambah kategori
// Handle form tambah kategori
document.getElementById('formTambahKategori').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const namaKategori = document.getElementById('namaKategoriInput').value;
    
    // Kirim request AJAX
    fetch('/dashboard/kategori-jenis-order/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nama: namaKategori
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tutup modal
            closeTambahKategoriModal();
            
            // Buat elemen tab baru
            const newKategoriId = data.data.id;
            const newKategoriNama = data.data.nama;
            
            // Buat button tab baru
            const newTabWrapper = document.createElement('div');
            newTabWrapper.className = 'spesifikasi_layout_btn_grid_box';
            
            const newTabButton = document.createElement('button');
            newTabButton.id = `tab-${newKategoriId}`;
            newTabButton.className = 'bg-gray-200 text-gray-700';
            newTabButton.textContent = newKategoriNama;
            newTabButton.onclick = function() { showTab(newKategoriId); };
            
            const deleteButton = document.createElement('button');
            deleteButton.title = 'Hapus kategori';
            deleteButton.onclick = function() { showDeleteKategoriPopup(newKategoriId, newKategoriNama); };
            deleteButton.innerHTML = '<img src="{{ asset('icons/trash-icon.svg') }}" alt="Icon">';
            
            newTabWrapper.appendChild(newTabButton);
            newTabWrapper.appendChild(deleteButton);
            
            // Sisipkan sebelum button tambah
            const tambahButton = document.querySelector('.spesifikasi_layout_btn button.bg-green-500');
            document.querySelector('.spesifikasi_layout_btn').insertBefore(newTabWrapper, tambahButton);
            
            // Buat konten untuk kategori baru
            const newContentDiv = document.createElement('div');
            newContentDiv.id = `content-${newKategoriId}`;
            newContentDiv.className = 'kategori-content hidden';
            
            newContentDiv.innerHTML = `
                <div class="spesifikasi_layout_btn_grid">
                    <button
                        onclick="openModal(${newKategoriId})"
                        class="bg-green-500 text-white"
                    >
                        + Tambah Spek
                    </button>
                </div>
                <div class="text-gray-500 italic mt-6">
                    Belum ada jenis spek. Silahkan tambah jenis spek terlebih dahulu.
                </div>
            `;
            
            // Tambahkan konten ke container
            document.querySelector('.spesifikasi_container').appendChild(newContentDiv);
            
            // Tampilkan tab baru
            showTab(newKategoriId);
            
            // Tidak perlu alert, sudah berhasil ditambahkan
        } else {
            console.error('Gagal menambahkan kategori:', data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// TAB FUNCTION
function showTab(id) {
    // Simpan kategori yang aktif
    activeKategoriId = id;
    
    // hide all content
    document.querySelectorAll('.kategori-content').forEach(el => {
        el.classList.add('hidden');
    });

    // reset all tab color
    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('bg-blue-500', 'text-white');
        el.classList.add('bg-gray-200', 'text-gray-700');
    });

    // show current
    const content = document.getElementById('content-' + id);
    if (content) {
        content.classList.remove('hidden');
    }

    // active tab
    let tab = document.getElementById('tab-' + id);
    if (tab) {
        tab.classList.remove('bg-gray-200', 'text-gray-700');
        tab.classList.add('bg-blue-500', 'text-white');
    }
    
    // Tampilkan spek pertama di kategori ini secara otomatis
    showFirstSpekInKategori(id);
}

// Tampilkan spek pertama dalam kategori
function showFirstSpekInKategori(kategoriId) {
    const contentDiv = document.getElementById('content-' + kategoriId);
    if (!contentDiv) return;
    
    // Cari semua spek content di kategori ini
    const allSpekContents = contentDiv.querySelectorAll('[id^="spek-content-"]');
    allSpekContents.forEach(el => el.classList.add('hidden'));
    
    // Reset semua spek tab button
    const allSpekTabs = contentDiv.querySelectorAll('[id^="spek-tab-"]');
    allSpekTabs.forEach(el => {
        el.classList.remove('bg-blue-500', 'text-white');
        el.classList.add('bg-white');
    });
    
    // Tampilkan spek pertama jika ada
    const firstSpekTab = contentDiv.querySelector('[id^="spek-tab-"]');
    if (firstSpekTab) {
        const firstSpekId = firstSpekTab.id.replace('spek-tab-', '');
        showSpekDetail(kategoriId, parseInt(firstSpekId));
    }
}

// MODAL
function openModal(kategoriId){
    document.getElementById('modalJenisSpek').classList.add('active');
    document.getElementById('kategori_id_input').value = kategoriId;
    document.getElementById('current_kategori_modal_input').value = activeKategoriId;
}

function closeModal(){
    document.getElementById('modalJenisSpek').classList.remove('active');
}

const deleteKategoriModal = document.getElementById('deleteKategoriModal');
const confirmDeleteKategoriBtn = document.getElementById('confirmDeleteKategoriBtn');
const cancelDeleteKategoriBtn = document.getElementById('cancelDeleteKategoriBtn');

// Fungsi untuk menampilkan popup hapus kategori
function showDeleteKategoriPopup(kategoriId, kategoriNama) {
    // Set data ke modal
    document.getElementById('kategoriIdToDelete').value = kategoriId;
    document.getElementById('kategoriNameToDelete').textContent = kategoriNama;
    
    // Tampilkan modal
    deleteKategoriModal.style.display = "block";
}

// Event listener untuk tombol batal hapus kategori
if (cancelDeleteKategoriBtn) {
    cancelDeleteKategoriBtn.onclick = function() {
        deleteKategoriModal.style.display = "none";
    }
}

// Event listener untuk tombol hapus kategori
if (confirmDeleteKategoriBtn) {
    confirmDeleteKategoriBtn.onclick = function() {
        const kategoriId = document.getElementById('kategoriIdToDelete').value;
        const kategoriNama = document.getElementById('kategoriNameToDelete').textContent;
        
        // Tutup modal
        deleteKategoriModal.style.display = "none";
        
        // Kirim request DELETE
        fetch(`/dashboard/kategori-jenis-order/${kategoriId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE' // Method spoofing
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data && data.success) {
                // Hapus tab dan konten dari DOM
                const tabElement = document.getElementById(`tab-${kategoriId}`);
                const contentElement = document.getElementById(`content-${kategoriId}`);
                
                if (tabElement) {
                    // Cari parent wrapper (gunakan selector yang sesuai)
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
                
                // Tidak perlu alert, sudah dihapus
            } else {
                console.error('Gagal menghapus kategori:', data?.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
}

// Jika klik di luar modal hapus kategori, tutup
window.onclick = function(event) {
    if (event.target == deleteKategoriModal) {
        deleteKategoriModal.style.display = "none";
    }
}

// MODAL DETAIL
function openDetailModal(kategoriId, spekId, detailId, detailNama, selectedJenisOrderIds = null){
    const modal = document.getElementById('modalJenisSpekDetail');
    const form = document.getElementById('formDetailSpek');
    const titleEl = document.getElementById('modalDetailTitle');
    const spekInput = document.getElementById('detail_spek_id_input');
    const namaInput = document.getElementById('detail_nama_input');
    const methodInput = document.getElementById('detail_method_input');
    const kategoriInput = document.getElementById('current_kategori_id_input');
    const currentSpekInput = document.getElementById('current_spek_id_input');

    // Simpan spek yang aktif
    activeSpekId = spekId;
    
    kategoriInput.value = kategoriId;
    currentSpekInput.value = spekId;
    spekInput.value = spekId;
    namaInput.value = detailNama || '';

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
        // Edit mode
        titleEl.textContent = 'Edit Jenis Spek Detail';
        methodInput.value = 'PUT';
        form.action = `/dashboard/jenis-spek-detail/${detailId}`;
    } else {
        // Create mode
        titleEl.textContent = 'Tambah Jenis Spek Detail';
        methodInput.value = 'POST';
        form.action = '/dashboard/jenis-spek-detail';
    }

    modal.classList.add('active');
}

function populateJenisOrderCheckbox(kategoriId, selectedJenisOrderIds = []) {
    const container = document.getElementById('jenisOrderCheckboxContainer');
    container.innerHTML = '';

    // Data jenis_order dari Blade
    const allJenisOrder = @json($jenisOrderList);
    
    // Filter berdasarkan kategori (hanya yang id_kategori_jenis_order-nya sesuai)
    const filteredJenisOrder = allJenisOrder.filter(jo => jo.id_kategori_jenis_order == kategoriId);

    if (filteredJenisOrder.length === 0) {
        container.innerHTML = '<p class="text-gray-800">Tidak ada jenis order untuk kategori ini</p>';
        return;
    }

    filteredJenisOrder.forEach(jo => {
        const div = document.createElement('div');
        div.className = 'spek_checkbox_item';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'id_jenis_order[]';
        checkbox.value = jo.id;
        checkbox.id = `jenis_order_${jo.id}`;
        if (selectedJenisOrderIds && selectedJenisOrderIds.includes(jo.id)) {
            checkbox.checked = true;
        }
        
        const label = document.createElement('label');
        label.htmlFor = `jenis_order_${jo.id}`;
        label.className = 'cursor-pointer';
        label.textContent = jo.nama_jenis;
        
        div.appendChild(checkbox);
        div.appendChild(label);
        container.appendChild(div);
    });
}

function closeDetailModal(){
    document.getElementById('modalJenisSpekDetail').classList.remove('active');
}

// Fungsi untuk menampilkan spek detail
function showSpekDetail(kategoriId, spekId) {
    activeKategoriId = kategoriId;
    activeSpekId = spekId;
    
    // Sembunyikan semua detail di kategori ini
    document.querySelectorAll(`#content-${kategoriId} [id^="spek-content-${kategoriId}-"]`)
        .forEach(el => el.classList.add('hidden'));

    // reset semua tombol spek di kategori ini
    document.querySelectorAll(`#content-${kategoriId} [id^="spek-tab-"]`)
        .forEach(el => {
            el.classList.remove('bg-blue-500', 'text-white');
            el.classList.add('bg-white');
        });

    // munculkan yang dipilih
    const content = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
    if (content) content.classList.remove('hidden');

    // aktifkan tabnya
    const tab = document.getElementById(`spek-tab-${spekId}`);
    if (tab) {
        tab.classList.remove('bg-white');
        tab.classList.add('bg-blue-500', 'text-white');
    }
}

// Fungsi untuk menampilkan popup hapus jenis spek
function showDeleteSpekPopup(spekId, kategoriId, spekName) {
    // Set data ke modal
    document.getElementById('spekIdToDelete').value = spekId;
    document.getElementById('kategoriIdToDelete').value = kategoriId;
    document.getElementById('spekNameToDelete').textContent = spekName;
    
    // Tampilkan modal
    deleteSpekModal.style.display = "block";
}

// Event listener untuk tombol batal
if (cancelDeleteSpekBtn) {
    cancelDeleteSpekBtn.onclick = function() {
        deleteSpekModal.style.display = "none";
    }
}

// Event listener untuk tombol hapus
if (confirmDeleteSpekBtn) {
    confirmDeleteSpekBtn.onclick = function() {
        const spekId = document.getElementById('spekIdToDelete').value;
        const kategoriId = document.getElementById('kategoriIdToDelete').value;
        
        // Tutup modal
        deleteSpekModal.style.display = "none";
        
        // Kirim request DELETE
        fetch(`/dashboard/jenis-spek/${spekId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                current_kategori_id: kategoriId,
                current_spek_id: spekId,
                _method: 'DELETE'
            })
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => {
                    throw new Error('Response bukan JSON: ' + text.substring(0, 100));
                });
            }
        })
        .then(data => {
            if (data && data.success) {
                // Hapus tab dan konten dari DOM
                const tabElement = document.getElementById(`spek-tab-${spekId}`);
                const contentElement = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
                
                if (tabElement) {
                    const wrapper = tabElement.closest('.spesifikasi_layout_btn_grid_box');
                    if (wrapper) wrapper.remove();
                }
                if (contentElement) contentElement.remove();
                
                // Tampilkan spek pertama yang tersisa
                const remainingTabs = document.querySelectorAll(`#content-${kategoriId} [id^="spek-tab-"]`);
                if (remainingTabs.length > 0) {
                    const firstTab = remainingTabs[0];
                    const firstSpekId = firstTab.id.replace('spek-tab-', '');
                    showSpekDetail(kategoriId, parseInt(firstSpekId));
                } else {
                    // Jika tidak ada tab lagi, sembunyikan semua konten
                    document.querySelectorAll(`#content-${kategoriId} [id^="spek-content-"]`)
                        .forEach(el => el.classList.add('hidden'));
                }
                
                // Tidak perlu alert
            } else {
                console.error('Gagal menghapus jenis spek:', data?.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
}

// Jika klik di luar modal, tutup
window.onclick = function(event) {
    if (event.target == deleteSpekModal) {
        deleteSpekModal.style.display = "none";
    }
    if (event.target == modalTambahKategori) {
        closeTambahKategoriModal();
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    @if($kategori->count() > 0)
        const firstKategoriId = {{ $kategori->first()->id }};
        activeKategoriId = firstKategoriId;
        
        @if($kategori->first()->jenisSpek->count() > 0)
            const firstSpekId = {{ $kategori->first()->jenisSpek->first()->id }};
            activeSpekId = firstSpekId;
            
            // Panggil showSpekDetail untuk menampilkan table secara otomatis
            setTimeout(() => {
                showSpekDetail(firstKategoriId, firstSpekId);
            }, 50);
        @endif
    @endif
});

// Tambahkan event listener untuk form submission agar tetap di tab yang sama
document.addEventListener('submit', function(e) {
    // Untuk form jenis spek
    if (e.target.closest('form[action*="jenis_spek"]')) {
        const currentKategoriInput = e.target.querySelector('input[name="current_kategori_id"]');
        if (currentKategoriInput && currentKategoriInput.value) {
            // Setelah submit, akan kembali ke tab yang sama
            localStorage.setItem('activeKategoriTab', currentKategoriInput.value);
            localStorage.setItem('activeSpekTab', activeSpekId);
        }
    }
    
    // Untuk form jenis spek detail
    if (e.target.closest('#formDetailSpek')) {
        const kategoriId = e.target.querySelector('#current_kategori_id_input').value;
        const spekId = e.target.querySelector('#current_spek_id_input').value;
        
        if (kategoriId && spekId) {
            localStorage.setItem('activeKategoriTab', kategoriId);
            localStorage.setItem('activeSpekTab', spekId);
        }
    }
    
    // Untuk form delete
    if (e.target.closest('form[action*="jenis-spek-detail"]')) {
        const currentKategoriInput = e.target.querySelector('input[name="current_kategori_id"]');
        const currentSpekInput = e.target.querySelector('input[name="current_spek_id"]');
        
        if (currentKategoriInput && currentKategoriInput.value) {
            localStorage.setItem('activeKategoriTab', currentKategoriInput.value);
        }
        
        if (currentSpekInput && currentSpekInput.value) {
            localStorage.setItem('activeSpekTab', currentSpekInput.value);
        }
    }
});

// Cek localStorage saat halaman dimuat untuk kembali ke tab yang sama
window.addEventListener('load', function() {
    const savedKategoriTab = localStorage.getItem('activeKategoriTab');
    const savedSpekTab = localStorage.getItem('activeSpekTab');
    
    if (savedKategoriTab) {
        // Tampilkan tab kategori yang disimpan
        showTab(parseInt(savedKategoriTab));
        
        if (savedSpekTab) {
            // Tunggu sebentar untuk memastikan konten sudah dimuat
            setTimeout(() => {
                showSpekDetail(parseInt(savedKategoriTab), parseInt(savedSpekTab));
            }, 100);
        }
        
        // Hapus dari localStorage setelah digunakan
        localStorage.removeItem('activeKategoriTab');
        localStorage.removeItem('activeSpekTab');
    }
});

// Pastikan table muncul saat halaman pertama kali dimuat
window.onload = function() {
    @if($kategori->count() > 0 && $kategori->first()->jenisSpek->count() > 0)
        // Jika tidak ada tab yang disimpan di localStorage, tampilkan tab pertama
        if (!localStorage.getItem('activeKategoriTab')) {
            const firstKategoriId = {{ $kategori->first()->id }};
            const firstSpekId = {{ $kategori->first()->jenisSpek->first()->id }};
            
            // Pastikan tab kategori pertama aktif
            const firstTab = document.getElementById('tab-' + firstKategoriId);
            if (firstTab) {
                firstTab.classList.add('bg-blue-500', 'text-white');
                firstTab.classList.remove('bg-gray-200', 'text-gray-700');
            }
            
            // Pastikan content kategori pertama ditampilkan
            const firstContent = document.getElementById('content-' + firstKategoriId);
            if (firstContent) {
                firstContent.classList.remove('hidden');
            }
            
            // Tampilkan spek pertama dalam kategori pertama
            showSpekDetail(firstKategoriId, firstSpekId);
        }
    @endif
};

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = type === 'success' ? 'success-message' : 'error-message';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

</script>

@endsection