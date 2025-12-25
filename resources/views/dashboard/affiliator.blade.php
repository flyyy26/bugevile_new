@extends('layouts.dashboard')

@section('title','Affiliator')

@section('content')

    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <button onclick="openModal()">Tambah Affiliator</button>
        </div>
    </div>

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
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
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

                                <form 
                                    action="{{ route('affiliator.destroy', $o->id) }}" 
                                    method="POST" 
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus?')"
                                >
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
            </tbody>
        </table>
    </div>

<div id="popupEditModal" class="dashboard_popup_order popup_custom">
    
    <div onclick="closeEditModal()" class="overlay_close"></div>

    <div class="dashboard_popup_order_box">
        
        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Edit Affiliator</h2>
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
                    <input type="text" id="edit_kode" name="kode" class="input_form">
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
    <div onclick="closeModal()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">

        <!-- HEADER -->
        
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Affiliator</h2>
            <button onclick="closeModal()">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('affiliator.store') }}">
            @csrf
            <div class="affiliator_grid_layout">
                <div class="form_field_normal">
                    <div>
                        <label class="block text-md">Nama Affiliator</label>
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
                        
                        <!-- Input Kode Manual -->
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Kode Manual (Opsional)</label>
                            <input 
                                type="text" 
                                name="kode_manual" 
                                id="kode_manual"
                                value="{{ old('kode_manual') }}" 
                                class="input_form"
                                placeholder="Contoh: ABC, XYZ-123, etc."
                                maxlength="20"
                            >
                            <p class="text-xs text-gray-500 mt-1">Format: Kode Manual - Kode Sistem (ABC-{{ $kode }})</p>
                        </div>
                        
                        <!-- Input untuk menampilkan kode sistem -->
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Kode Sistem</label>
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
                        
                        <!-- Display Kode Gabungan -->
                        <div class="mt-3 p-3 bg-gray-50 rounded border">
                            <p class="text-sm text-gray-600 mb-1">Kode akhir yang akan disimpan:</p>
                            <p class="text-lg font-semibold text-blue-600" id="display_kode_gabungan">{{ old('kode', $kode) }}</p>
                        </div>
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

<script>
function openModal() {
    document.getElementById('popupModal').classList.add('active');
}
function closeModal() {
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
@endsection