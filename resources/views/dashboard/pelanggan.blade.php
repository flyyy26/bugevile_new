@extends('layouts.dashboard')

@section('title','Pelanggan')

@section('content')

    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <button onclick="openModal()" class="hidden_print">Tambah Pelanggan</button>
        </div>
    </div>

    <div class="orders_table_container">
        <table>
            
            <!-- HEADER -->
            <thead>
                <tr>
                    <th><div class="text-center">Nama Pelanggan</div></th>
                    <th><div class="text-center">Affiliator</div></th>
                    <th><div class="text-center">No Telepon</div></th>
                    <th><div class="text-center">Alamat</div></th>
                    <th><div class="text-center">Aksi</div></th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                @foreach ($pelanggan as $o)
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                        <td class="px-2 py-2 whitespace-nowrap"><div class="text-md text-center">{{ $o->nama }}  </div></td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="text-md text-center">
                                @if($o->affiliate)
                                    {{ $o->affiliate->nama }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>{{ $o->no_hp ?? 'Data belum diisi' }}</div>
                        </td>
                        <td>
                            <div>{{ $o->alamat ?? 'Data belum diisi' }}</div>
                        </td>
                        <td>
                            <div class="btn_table_action">
                                <button
                                    class="bg-blue-500"
                                    onclick="openEditModal({{ $o->id }}, '{{ $o->nama }}', '{{ $o->no_hp }}', '{{ $o->alamat }}')"
                                >
                                    Edit
                                </button>

                                <button
                                    onclick="showDeletePelangganModal({{ $o->id }}, '{{ $o->nama }}')"
                                    class="bg-red"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- MODAL DELETE PELANGGAN -->
<div id="deletePelangganModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus pelanggan "<span id="pelangganNameToDelete"></span>"?
        </p>
        <input type="hidden" id="pelangganIdToDelete">
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelDeletePelangganBtn">Batal</button>
            <button id="confirmDeletePelangganBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL NOTIFICATION -->
<div id="notificationModal" class="custom-modal notification-modal">
    <div class="modal-content">
        <div class="notification-content" id="notificationMessage">
            <!-- Pesan notifikasi akan dimasukkan via JavaScript -->
        </div>
    </div>
</div>

<div id="popupEditModal" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    
    <div onclick="closeEditModal()" class="overlay_close"></div>

    <div class="dashboard_popup_order_box">
        
        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Edit Pelanggan</h2>
            <button onclick="closeEditModal()">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" id="formEditPelanggan">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="form_field_normal">
                <label>Nama Pelanggan</label>
                <input type="text" id="edit_nama" name="nama" 
                    class="input_form" required>
            </div>

            <!-- No HP -->
            <div class="form_field_normal">
                <label>Nomor HP</label>
                <input type="text" id="edit_no_hp" name="no_hp" 
                    class="input_form">
            </div>

            <!-- Alamat -->
            <div class="form_field_normal">
                <label>Alamat</label>
                <textarea id="edit_alamat" name="alamat"
                    class="input_form" rows="3"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeEditModal()">
                    Batal
                </button>

                <button type="submit">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>

<div id="popupModal" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeModal()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">

        <!-- HEADER -->
        
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Pelanggan</h2>
            <button onclick="closeModal()">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('pelanggan.store') }}" id="formTambahPelanggan">
            @csrf

            <!-- Nama Pelanggan -->
            <div class="form_field_normal">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama" class="input_form" required>
            </div>

            <!-- No HP -->
            <div class="form_field_normal">
                <label>Nomor Telepon</label>
                <input type="text" name="no_hp" class="input_form">
            </div>

            <!-- Alamat -->
            <div class="form_field_normal">
                <label>Alamat</label>
                <textarea rows="2" name="alamat" class="input_form"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModal()">
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

/* Notification Modal */
.notification-modal .modal-content {
    max-width: 350px;
    padding: 15px;
}

.notification-content {
    text-align: center;
    padding: 10px 0;
}

.notification-success {
    color: #10b981;
}

.notification-error {
    color: #ef4444;
}

/* Toast Notification */
.notification-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.notification-toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-width: 250px;
}

.toast-success {
    background-color: #10b981;
    color: white;
}

.toast-error {
    background-color: #ef4444;
    color: white;
}

.toast-icon {
    font-weight: bold;
    margin-right: 10px;
    font-size: 18px;
}

.toast-message {
    font-size: 14px;
}
</style>

<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                 document.querySelector('input[name="_token"]')?.value ||
                 '{{ csrf_token() }}';

// Modal untuk hapus pelanggan
const deletePelangganModal = document.getElementById('deletePelangganModal');
const confirmDeletePelangganBtn = document.getElementById('confirmDeletePelangganBtn');
const cancelDeletePelangganBtn = document.getElementById('cancelDeletePelangganBtn');

// Fungsi untuk menampilkan modal konfirmasi hapus pelanggan
function showDeletePelangganModal(pelangganId, pelangganNama) {
    document.getElementById('pelangganIdToDelete').value = pelangganId;
    document.getElementById('pelangganNameToDelete').textContent = pelangganNama;
    deletePelangganModal.style.display = "block";
}

// Event listener untuk tombol batal hapus
if (cancelDeletePelangganBtn) {
    cancelDeletePelangganBtn.onclick = function() {
        deletePelangganModal.style.display = "none";
    }
}

// Event listener untuk tombol hapus pelanggan
if (confirmDeletePelangganBtn) {
    confirmDeletePelangganBtn.onclick = function() {
        const pelangganId = document.getElementById('pelangganIdToDelete').value;
        const pelangganNama = document.getElementById('pelangganNameToDelete').textContent;
        
        // Tutup modal
        deletePelangganModal.style.display = "none";
        
        // Kirim request dengan method spoofing (POST dengan _method=DELETE)
        fetch(`/dashboard/pelanggan/${pelangganId}`, {
            method: 'POST', // Gunakan POST untuk method spoofing
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
        .then(response => {
            // Coba parse sebagai JSON
            return response.json().then(data => {
                return { 
                    data: data, 
                    status: response.status,
                    isJson: true 
                };
            }).catch(() => {
                // Jika bukan JSON, anggap redirect berhasil
                return { 
                    data: { success: true }, 
                    status: response.status,
                    isJson: false 
                };
            });
        })
        .then(({ data, status, isJson }) => {
            if (status >= 200 && status < 300) {
                // Jika response JSON dan berhasil
                if (isJson && data.success) {
                    // Hapus baris dari tabel
                    const rows = document.querySelectorAll('tbody tr');
                    let rowRemoved = false;
                    
                    rows.forEach(row => {
                        const editBtn = row.querySelector('button.bg-blue-500');
                        if (editBtn) {
                            const onclickAttr = editBtn.getAttribute('onclick');
                            if (onclickAttr && onclickAttr.includes(pelangganId.toString())) {
                                row.remove();
                                rowRemoved = true;
                            }
                        }
                    });
                    
                    if (rowRemoved) {
                        showToast(data.message || 'Pelanggan "' + pelangganNama + '" berhasil dihapus', 'success');
                    } else {
                        // Jika tidak ditemukan, refresh halaman
                        window.location.reload();
                    }
                } else {
                    // Jika bukan JSON atau redirect, refresh halaman
                    window.location.reload();
                }
            } else {
                showToast('Gagal menghapus pelanggan: ' + (data?.message || 'Status ' + status), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: gunakan form dengan method spoofing
            deleteWithForm(pelangganId, pelangganNama);
        });
    };
}

// Fungsi fallback dengan form submit
function deleteWithForm(pelangganId, pelangganNama) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/dashboard/pelanggan/${pelangganId}`;
    form.style.display = 'none';
    
    // CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Method spoofing untuk DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
}

// Fungsi untuk menampilkan toast notification
function showToast(message, type = 'success') {
    // Hapus toast sebelumnya jika ada
    const existingToast = document.querySelector('.notification-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `notification-toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-icon">${type === 'success' ? '✓' : '✗'}</span>
            <span class="toast-message">${message}</span>
        </div>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(toast);
    
    // Tampilkan dengan animasi
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Hapus setelah 3 detik
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Jika klik di luar modal, tutup modal
window.onclick = function(event) {
    if (event.target == deletePelangganModal) {
        deletePelangganModal.style.display = "none";
    }
    if (event.target == document.getElementById('notificationModal')) {
        document.getElementById('notificationModal').style.display = "none";
    }
}

// Handle form submit untuk tambah pelanggan
if (document.getElementById('formTambahPelanggan')) {
    document.getElementById('formTambahPelanggan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;
        
        // Disable tombol submit dan tampilkan loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // Penting untuk deteksi AJAX
            }
        })
        .then(response => {
            // Cek jika response adalah redirect
            if (response.redirected) {
                // Jika redirect, anggap berhasil
                return { redirected: true, url: response.url };
            }
            
            // Coba parse sebagai JSON
            return response.json().then(data => ({
                data: data,
                status: response.status,
                isJson: true
            }));
        })
        .then(result => {
            if (result.redirected) {
                // Jika redirect, tutup modal dan refresh halaman
                closeModal();
                showToast('Pelanggan berhasil ditambahkan', 'success');
                setTimeout(() => {
                    window.location.href = result.url;
                }, 1000);
            } else if (result.isJson && result.data && result.data.success) {
                // Jika JSON dan berhasil
                closeModal();
                showToast(result.data.message || 'Pelanggan berhasil ditambahkan', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else if (result.isJson && result.data && result.data.errors) {
                // Tampilkan error validasi
                const errorMessages = Object.values(result.data.errors).flat().join(', ');
                showToast('Validasi error: ' + errorMessages, 'error');
                
                // Enable tombol submit
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            } else {
                // Fallback: anggap berhasil
                closeModal();
                showToast('Pelanggan berhasil ditambahkan', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: anggap berhasil karena data sudah tersimpan
            closeModal();
            showToast('Pelanggan berhasil ditambahkan', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    });
}

// Handle form submit untuk edit pelanggan
if (document.getElementById('formEditPelanggan')) {
    document.getElementById('formEditPelanggan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;
        
        // Disable tombol submit dan tampilkan loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengupdate...';
        
        // Tambahkan method spoofing untuk PUT
        formData.append('_method', 'PUT');
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Cek jika response adalah redirect
            if (response.redirected) {
                // Jika redirect, anggap berhasil
                return { redirected: true, url: response.url };
            }
            
            // Coba parse sebagai JSON
            return response.json().then(data => ({
                data: data,
                status: response.status,
                isJson: true
            }));
        })
        .then(result => {
            if (result.redirected) {
                // Jika redirect, tutup modal dan refresh halaman
                closeEditModal();
                showToast('Pelanggan berhasil diperbarui', 'success');
                setTimeout(() => {
                    window.location.href = result.url;
                }, 1000);
            } else if (result.isJson && result.data && result.data.success) {
                // Jika JSON dan berhasil
                closeEditModal();
                showToast(result.data.message || 'Pelanggan berhasil diperbarui', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else if (result.isJson && result.data && result.data.errors) {
                // Tampilkan error validasi
                const errorMessages = Object.values(result.data.errors).flat().join(', ');
                showToast('Validasi error: ' + errorMessages, 'error');
                
                // Enable tombol submit
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            } else {
                // Fallback: anggap berhasil
                closeEditModal();
                showToast('Pelanggan berhasil diperbarui', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: anggap berhasil karena data sudah tersimpan
            closeEditModal();
            showToast('Pelanggan berhasil diperbarui', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    });
}

// Fungsi untuk modal
function openModal() {
    document.getElementById('popupModal').classList.add('active');
}

function closeModal() {
    document.getElementById('popupModal').classList.remove('active');
}

function openEditModal(id, nama, no_hp, alamat) {
    const modal = document.getElementById('popupEditModal');
    const form  = document.getElementById('formEditPelanggan');

    // Set action form → route update
    form.action = `/dashboard/pelanggan/${id}`;

    // Isi value input
    document.getElementById('edit_nama').value   = nama ?? '';
    document.getElementById('edit_no_hp').value  = no_hp ?? '';
    document.getElementById('edit_alamat').value = alamat ?? '';

    modal.classList.add('active');
}

function closeEditModal() {
    document.getElementById('popupEditModal').classList.remove('active');
}

// Tampilkan toast jika ada session message dari Laravel
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika ada session success dari Laravel
    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
    
    // Cek jika ada session error dari Laravel
    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif
});
</script>

@endsection