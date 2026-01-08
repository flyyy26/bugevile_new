<div class="orders_table_container orders_table_container_small">
        <table>
            
            <!-- HEADER -->
            <thead class="bg-gray-800 text-white text-center">
                <tr>
                    <th><div class="text-center">Nama</div></th>
                    <th><div class="text-center">Kode</div></th>
                    <th><div class="text-center">Total Order</div></th>
                    <th><div class="text-center">Nomor Whatsapp</div></th>
                    <th><div class="text-center">Alamat</div></th>
                    <th><div class="text-center">Aksi</div></th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                @foreach ($affiliates as $o)
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}" data-affiliate-id="{{ $o->id }}">
                        <td class="px-2 py-2 whitespace-nowrap"><div class="text-md text-center"><a href="{{ route('affiliator.show', $o->id) }}" class="text-black"><b>{{ $o->nama }}</b></a> </div></td>
                        <td class="px-2 py-2 whitespace-nowrap"><div class="text-md text-center">{{ $o->kode }}  </div></td>
                        <td class="px-2 py-2 whitespace-nowrap"><div class="text-md text-center">{{ $o->orders_via_kode_count }} Transaksi</div></td>
                        <td>
                            <div class="text-center">{{ $o->nomor_whatsapp ?? 'Data belum diisi' }}</div>
                        </td>
                        <td>
                            <div class="text-center">{{ $o->alamat ?? 'Data belum diisi' }}</div>
                        </td>
                        <td>
                            <div class="btn_table_action">
                                <button
                                    class="bg-blue-500"
                                    onclick="openEditModal({{ $o->id }}, '{{ $o->nama }}', '{{ $o->kode }}', '{{ $o->nomor_whatsapp }}', '{{ $o->nama_bank }}', '{{ $o->nomor_rekening }}', '{{ $o->nama_rekening }}', '{{ $o->alamat }}')"
                                >
                                    Edit
                                </button>

                                <button class="bg-red" onclick="showDeleteAffiliateModal({{ $o->id }}, '{{ addslashes($o->nama) }}', this)">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button onclick="openModalAffiliator()" class="setting_add_table">
            + Tambah Sales
        </button>
    </div>

<div id="popupEditModal" class="dashboard_popup_order popup_custom">
    
    <div onclick="closeEditModal()" class="overlay_close"></div>

    <div class="dashboard_popup_order_box">
        
        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Edit Sales</h2>
            <button onclick="closeEditModal()">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" id="formEditAffiliator">
            @csrf
            @method('PUT')

            <div class="affiliator_grid_layout">
                <!-- Nama -->
                <div class="form_field_normal">
                    <label>Nama</label>
                    <input type="text" id="edit_nama" name="nama" class="input_form">
                </div>

                <!-- Kode -->
                <div class="form_field_normal">
                    <label>Kode</label>
                    <input type="text" id="edit_kode" name="kode" class="input_form" readonly>
                </div>

                <!-- No HP -->
                <div class="form_field_normal">
                    <label>Nomor Whatsapp</label>
                    <input type="text" id="edit_nomor_whatsapp" name="nomor_whatsapp" class="input_form">
                </div>
            </div>

            <div class="affiliator_grid_layout">
                <!-- Nama Bank -->
                <div class="form_field_normal">
                    <label>Nama Bank</label>
                    <input type="text" id="edit_nama_bank" name="nama_bank" class="input_form">
                </div>

                <!-- Nomor Rekening -->
                <div class="form_field_normal">
                    <label>Nomor Rekening</label>
                    <input type="text" id="edit_nomor_rekening" name="nomor_rekening" class="input_form">
                </div>

                <!-- Nama Rekening -->
                <div class="form_field_normal">
                    <label>Atas Nama Rekening</label>
                    <input type="text" id="edit_nama_rekening" name="nama_rekening" class="input_form">
                </div>
            </div>

            <!-- Alamat -->
            <div class="form_field_normal">
                <label>Alamat</label>
                <textarea id="edit_alamat" name="alamat" class="input_form" rows="3"></textarea>
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

<div id="popupModal" class="dashboard_popup_order popup_custom">
    <div onclick="closeModalAffiliator()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">

        <!-- HEADER -->
        
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Sales</h2>
            <button onclick="closeModalAffiliator()">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('affiliator.store') }}">
            @csrf
            <div class="affiliator_grid_layout">
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Nama Sales</label>
                        <input type="text" name="nama" class="input_form">
                    </div>
                </div>

                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Kode</label>
                        
                        <!-- Kode Sistem (hidden untuk diambil nilai aslinya) -->
                        <input 
                            type="hidden" 
                            id="kode_system" 
                            value="{{ old('kode', $kode) }}"
                        >
                        
                        <div class="kode_layout_input">
                            <input 
                                type="text" 
                                name="kode_manual" 
                                id="kode_manual"
                                value="{{ old('kode_manual') }}" 
                                class="input_form"
                                placeholder="Contoh: ABC, XYZ-123, etc."
                                maxlength="20"
                            >
                            <input 
                                type="text" 
                                value="{{ old('kode', $kode) }}" 
                                class="input_form bg-gray-100"
                                readonly  
                            >
                        </div>
                        
                        <!-- Input Hidden untuk Kode Gabungan -->
                        <input 
                            type="hidden" 
                            name="kode" 
                            id="kode_gabungan"
                            value="{{ old('kode', $kode) }}"
                        >
                    </div>
                </div>

                <!-- No HP -->
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Nomor Whatsapp</label>
                        <input type="text" name="nomor_whatsapp" class="input_form">
                    </div>
                </div>
            </div>

            <div class="affiliator_grid_layout">
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Nama Bank</label>
                        <input type="text" name="nama_bank" class="input_form">
                    </div>
                </div>

                <!-- Nomor Rekening -->
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="input_form">
                    </div>
                </div>

                <!-- Nama Rekening -->
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Atas Nama Rekening</label>
                        <input type="text" name="nama_rekening" class="input_form">
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form_field_normal">
                <div>
                    <label class="block text-md">Alamat</label>
                    <textarea rows="2" name="alamat" class="input_form"></textarea>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalAffiliator()">
                    Batal
                </button>

                <button type="submit">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<!-- MODAL DELETE PELANGGAN -->
<div id="deleteAffiliateModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus pelanggan "<span id="affiliateNameToDelete"></span>"?
        </p>
        <input type="hidden" id="affiliateIdToDelete">
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelDeleteAffiliateBtn">Batal</button>
            <button id="confirmDeleteAffiliateBtn">Hapus</button>
        </div>
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

// Modal untuk hapus affiliate
const deleteAffiliateModal = document.getElementById('deleteAffiliateModal');
const confirmDeleteAffiliateBtn = document.getElementById('confirmDeleteAffiliateBtn');
const cancelDeleteAffiliateBtn = document.getElementById('cancelDeleteAffiliateBtn');

// Fungsi untuk menampilkan modal konfirmasi hapus affiliate
function showDeleteAffiliateModal(affiliateId, affiliateNama, rowElement = null) {
    document.getElementById('affiliateIdToDelete').value = affiliateId;
    document.getElementById('affiliateNameToDelete').textContent = affiliateNama;
    deleteAffiliateModal.style.display = "block";
    
    // Simpan row element untuk dihapus nanti
    if (rowElement) {
        deleteAffiliateModal.dataset.rowElement = rowElement.closest('tr').id || null;
    } else {
        // Cari row berdasarkan affiliateId
        const row = document.querySelector(`tr[data-affiliate-id="${affiliateId}"]`);
        if (row) {
            deleteAffiliateModal.dataset.rowElement = row.id;
        }
    }
}

// Event listener untuk tombol batal hapus
if (cancelDeleteAffiliateBtn) {
    cancelDeleteAffiliateBtn.onclick = function() {
        deleteAffiliateModal.style.display = "none";
    }
}

// Event listener untuk tombol hapus affiliate
if (confirmDeleteAffiliateBtn) {
    confirmDeleteAffiliateBtn.onclick = function() {
        const affiliateId = document.getElementById('affiliateIdToDelete').value;
        const affiliateNama = document.getElementById('affiliateNameToDelete').textContent;
        
        // Tutup modal
        deleteAffiliateModal.style.display = "none";
        
        // Cari row element yang akan dihapus
        let rowToRemove = null;
        
        // Coba dapatkan dari data attribute modal
        const rowElementId = deleteAffiliateModal.dataset.rowElement;
        if (rowElementId) {
            rowToRemove = document.getElementById(rowElementId);
        }
        
        // Jika tidak ditemukan, cari berdasarkan data-affiliate-id
        if (!rowToRemove) {
            rowToRemove = document.querySelector(`tr[data-affiliate-id="${affiliateId}"]`);
        }
        
        // Kirim request delete
        fetch(`/dashboard/affiliator/${affiliateId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE' 
            })
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json().then(data => ({
                    success: true,
                    data: data
                }));
            } else {
                // If not JSON, assume it's a redirect
                return response.text().then(() => ({
                    success: true,
                    data: { message: 'Sales berhasil dihapus' }
                }));
            }
        })
        .then(result => {
            if (result.success) {
                // Hapus row dari tabel jika ditemukan
                if (rowToRemove && rowToRemove.parentNode) {
                    rowToRemove.remove();
                    showToast(result.data.message || `Sales "${affiliateNama}" berhasil dihapus`, 'success');
                    
                    // Update row zebra striping
                    updateTableStriping();
                } else {
                    // Fallback: reload halaman jika tidak bisa hapus row
                    window.location.reload();
                }
            } else {
                showToast('Gagal menghapus sales: ' + (result.data?.message || 'Terjadi kesalahan'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus sales', 'error');
        });
    };
}

// Fungsi untuk update zebra striping setelah menghapus row
function updateTableStriping() {
    const tbody = document.querySelector('tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr:not([style*="display: none"])');
    
    rows.forEach((row, index) => {
        // Reset classes
        row.classList.remove('bg-gray-200', 'bg-white');
        
        // Apply zebra striping
        if (index % 2 === 0) {
            row.classList.add('bg-white');
        } else {
            row.classList.add('bg-gray-200');
        }
    });
}

// Fungsi fallback dengan form submit
// Fungsi fallback dengan form submit (untuk browser lama)
function deleteWithForm(affiliateId, affiliateNama) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/dashboard/affiliator/${affiliateId}`;
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
    const existingToasts = document.querySelectorAll('.notification-toast');
    existingToasts.forEach(toast => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    });
    
    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `notification-toast toast-${type}`;
    
    // Ikon berdasarkan tipe
    let icon = '✓';
    if (type === 'error') icon = '✗';
    if (type === 'warning') icon = '⚠️';
    if (type === 'info') icon = 'ℹ️';
    
    toast.innerHTML = `
        <div class="toast-content ${type}">
            <span class="toast-icon">${icon}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(toast);
    
    // Tampilkan dengan animasi
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Auto-hapus setelah 5 detik
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }
    }, 5000);
}

const toastStyle = document.createElement('style');
toastStyle.textContent = `
    .toast-content {
        position: relative;
        padding-right: 40px;
    }
    .toast-close {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }
    .toast-close:hover {
        opacity: 1;
    }
`;
document.head.appendChild(toastStyle);

// Jika klik di luar modal, tutup modal
window.onclick = function(event) {
    if (event.target == deleteAffiliateModal) {
        deleteAffiliateModal.style.display = "none";
    }
    if (event.target == document.getElementById('notificationModal')) {
        document.getElementById('notificationModal').style.display = "none";
    }
}
</script>

<script>
function openModalAffiliator() {
    document.getElementById('popupModal').classList.add('active');
}
function closeModalAffiliator() {
    document.getElementById('popupModal').classList.remove('active');
}
</script>
<script>
    function openEditModal(id, nama, kode, nomor_whatsapp, nama_bank, nomor_rekening, nama_rekening, alamat) {
        const modal = document.getElementById('popupEditModal');
        const form  = document.getElementById('formEditAffiliator');

        // Set action form → route update
        form.action = `/dashboard/affiliator/${id}`;

        // Isi value input
        document.getElementById('edit_nama').value   = nama ?? '';
        document.getElementById('edit_kode').value   = kode ?? '';
        document.getElementById('edit_nomor_whatsapp').value  = nomor_whatsapp ?? '';
        document.getElementById('edit_nama_bank').value  = nama_bank ?? '';
        document.getElementById('edit_nomor_rekening').value  = nomor_rekening ?? '';
        document.getElementById('edit_nama_rekening').value  = nama_rekening ?? '';
        document.getElementById('edit_alamat').value = alamat ?? '';

        modal.classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('popupEditModal').classList.remove('active');
    }

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kodeSystemInput = document.getElementById('kode_system');
    const kodeManualInput = document.getElementById('kode_manual');
    const kodeGabunganInput = document.getElementById('kode_gabungan');
    const displayKodeGabungan = document.getElementById('display_kode_gabungan');
    
    // Fungsi untuk menggabungkan kode dengan format: KODE_MANUAL - KODE_SISTEM
    function gabungkanKode() {
        const kodeSystem = kodeSystemInput.value.trim();
        let kodeManual = kodeManualInput.value.trim();
        
        // Bersihkan karakter khusus yang tidak diinginkan, biarkan tanda hubung
        // Hapus semua karakter kecuali huruf, angka, dan tanda hubung
        kodeManual = kodeManual.replace(/[^a-zA-Z0-9\-]/g, '');
        
        // Hapus spasi dan ubah ke uppercase
        kodeManual = kodeManual.toUpperCase().replace(/\s+/g, '');
        
        // Gabungkan kode dengan format: KODE_MANUAL - KODE_SISTEM
        let kodeGabungan;
        
        if (kodeManual) {
            // Pastikan tidak ada tanda hubung berlebihan
            kodeManual = kodeManual.replace(/-+$/, ''); // Hapus tanda hubung di akhir
            kodeManual = kodeManual.replace(/^-+/, ''); // Hapus tanda hubung di awal
            
            // Gabungkan dengan format: ABC-12345
            kodeGabungan = kodeManual + '-' + kodeSystem;
        } else {
            // Jika tidak ada kode manual, gunakan kode sistem saja
            kodeGabungan = kodeSystem;
        }
        
        // Update input hidden dan display
        kodeGabunganInput.value = kodeGabungan;
        displayKodeGabungan.textContent = kodeGabungan;
        
        // Update juga input manual jika ada perubahan pembersihan
        if (kodeManual !== kodeManualInput.value.toUpperCase().replace(/\s+/g, '')) {
            kodeManualInput.value = kodeManual;
        }
    }
    
    // Event listener
    kodeManualInput.addEventListener('input', gabungkanKode);
    kodeManualInput.addEventListener('change', gabungkanKode);
    
    // Inisialisasi awal
    gabungkanKode();
});
</script>