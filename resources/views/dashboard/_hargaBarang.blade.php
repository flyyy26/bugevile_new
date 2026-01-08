<div class="orders_table_container">
    <table>
        <thead class="bg-gray-800">
            <tr>
                <th>No</th>
                <th>Nama Jenis</th>
                <th><div class="text-center">Kategori</div></th>
                <th><div class="text-center">Nilai</div></th>
                <th><div class="text-center">Bahan</div></th>
                <th><div class="text-center">Kerta & Cat</div></th>
                <th><div class="text-center">Asesoris</div></th>
                <th><div class="text-center">Ongkos Gawe</div></th>
                <th><div class="text-center">Sales</div></th>
                <th><div class="text-center">Harga Produksi</div></th>
                <th><div class="text-center">Harga Jual</div></th>
                <th><div class="text-center">Laba Bersih</div></th>
                <th><div class="text-center">Aksi</div></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jenisOrders as $jo)
                <tr class="{{ $loop->even ? 'bg_gray_200' : 'bg-white' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jo->nama_jenis }}</td>
                    <td><div class="text-center">{{ $jo->kategori->nama ?? '-' }}</div></td>
                    <td><div class="text-center">{{ $jo->nilai }}</div></td>
                    <td><div class="text-center">{{ number_format(($jo->belanja->bahan_harga ?? 0) * ($jo->nilai ?? 1), 0, ',', '.') }}</div></td>
                    <td><div class="text-center">{{ number_format(($jo->belanja->kertas_harga ?? 0) * ($jo->nilai ?? 1), 0, ',', '.') }}</div></td>
                    <td>
                        <div class="text-center">
                            @if($jo->belanja && $jo->belanja->asesoris)
                                {{ number_format($jo->belanja->asesoris->sum('harga'), 0, ',', '.') }}
                            @else
                                0
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            @php
                                $total = 0;
                                if ($jo->hargaJenisPekerjaan) {
                                    $fields = [
                                        'harga_setting',
                                        'harga_print' => fn($h) => $h * ($jo->nilai ?? 1),
                                        'harga_press' => fn($h) => $h * ($jo->nilai ?? 1),
                                        'harga_cutting',
                                        'harga_jahit',
                                        'harga_finishing',
                                        'harga_packing'
                                    ];
                                    
                                    foreach ($fields as $key => $field) {
                                        if (is_string($field)) { // field tanpa perkalian
                                            $harga = $jo->hargaJenisPekerjaan->$field ?? 0;
                                            $total += $harga;
                                        } else { // field dengan perkalian (harga_print, harga_press)
                                            $harga = $jo->hargaJenisPekerjaan->$key ?? 0;
                                            $total += $field($harga); // panggil closure untuk perkalian
                                        }
                                    }
                                }
                                echo number_format($total, 0, ',', '.');
                            @endphp
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            @if($jo->komisi_affiliate > 0)
                                <span>
                                    {{ number_format($jo->komisi_affiliate, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                    <td><div class="text-center">{{ number_format($jo->harga_barang ?? 0, 0, ',', '.') }}</div></td>
                    <td><div class="text-center">{{ number_format($jo->harga_jual ?? 0, 0, ',', '.') }}</div></td>
                    <td><div class="text-center">{{ number_format($jo->laba_bersih ?? 0, 0, ',', '.') }}</div></td>
                    <td>
                        <div class="btn_table_action">
                            <button type="button"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded btn-laba"
                                    data-id="{{ $jo->id }}"
                                    data-nama="{{ $jo->nama_jenis }}"
                                    data-harga-barang="{{ $jo->harga_barang ?? 0 }}"
                                    data-harga-jual="{{ $jo->harga_jual ?? 0 }}"
                                    data-laba-bersih="{{ $jo->laba_bersih ?? 0 }}"
                                    data-persentase-affiliate="{{ $jo->persentase_affiliate ?? 0 }}"
                                    data-biaya="{{ json_encode($jo->biaya ?? []) }}">
                                Laba
                            </button>
                            <button type="button"
                                    class="bg-blue-500 text-white px-2 py-1 rounded btn-ubah"
                                    data-id="{{ $jo->id }}"
                                    data-nama="{{ $jo->nama_jenis }}"
                                    data-nilai="{{ $jo->nilai }}"
                                    data-kategori="{{ $jo->id_kategori_jenis_order }}"
                                    data-belanja-id="{{ $jo->belanja_id }}"
                                    data-bahan-harga="{{ $jo->belanja->bahan_harga ?? 0 }}"
                                    data-kertas-harga="{{ $jo->belanja->kertas_harga ?? 0 }}"
                                    data-harga-setting="{{ $jo->hargaJenisPekerjaan->harga_setting ?? 0 }}"
                                    data-harga-print="{{ $jo->hargaJenisPekerjaan->harga_print ?? 0 }}"
                                    data-harga-press="{{ $jo->hargaJenisPekerjaan->harga_press ?? 0 }}"
                                    data-harga-cutting="{{ $jo->hargaJenisPekerjaan->harga_cutting ?? 0 }}"
                                    data-harga-jahit="{{ $jo->hargaJenisPekerjaan->harga_jahit ?? 0 }}"
                                    data-harga-finishing="{{ $jo->hargaJenisPekerjaan->harga_finishing ?? 0 }}"
                                    data-harga-packing="{{ $jo->hargaJenisPekerjaan->harga_packing ?? 0 }}"
                                    data-asesoris="{{ json_encode($jo->belanja->asesoris ?? []) }}">
                                Ubah
                            </button>
                            <button
                                onclick="showDeleteJenisOrderModal({{ $jo->id }}, '{{ addslashes($jo->nama_jenis) }}', event)"
                                class="bg-red delete-jenis-order-btn"
                                data-id="{{ $jo->id }}"
                                data-nama="{{ $jo->nama_jenis }}"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button onclick="openModalJenisOrder()" class="setting_add_table">
        + Tambah Kategori
    </button>
</div>

<div id="modalEditSc" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">
            <div class="dashboard_popup_order_heading">
                <h2>Edit Kategori</h2>
                <button onclick="closeEditModalSc()">&times;</button>
            </div>

            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="editId" name="id">
                <input type="hidden" id="belanjaId" name="belanja_id">
                <input type="hidden" id="editIdKategori" name="id_kategori_jenis_order">
                
                <!-- Hidden inputs untuk harga jenis pekerjaan per item -->
                <input type="hidden" id="hargaSetting" name="harga_setting" value="0">
                <input type="hidden" id="hargaPrint" name="harga_print" value="0">
                <input type="hidden" id="hargaPress" name="harga_press" value="0">
                <input type="hidden" id="hargaCutting" name="harga_cutting" value="0">
                <input type="hidden" id="hargaJahit" name="harga_jahit" value="0">
                <input type="hidden" id="hargaFinishing" name="harga_finishing" value="0">
                <input type="hidden" id="hargaPacking" name="harga_packing" value="0">

                <div class="field_two_style">
                     <!-- Nama -->
                    <div class="form_field_normal">
                        <label>Nama</label>
                        <input type="text" id="editNama" name="nama_jenis" class="input_form" required>
                    </div>

                    <!-- Nilai -->
                    <div class="form_field_normal">
                        <label>Nilai</label>
                        <input type="number" id="editNilai" name="nilai" class="input_form" required min="1">
                    </div>
                </div>

                <div class="form_field_normal">
                    <label>Kategori</label>
                    <select id="editKategori" name="id_kategori_jenis_order" class="input_form" required>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bahan -->
                <div class="form_field_normal">
                    <label>Harga Bahan</label>
                    <div class="harga_barang_style">
                        <input type="number" id="bahanHarga" name="bahan_harga" class="input_form" placeholder="Harga Bahan" required>
                        <span>x</span><span id="nilaiDisplayBahan"> 1</span> <span>=</span> <span id="totalBahanDisplay">0</span>
                    </div>
                </div>

                <!-- Kertas -->
                <div class="form_field_normal">
                    <label>Harga Kertas</label>
                    <div class="harga_barang_style">
                        <input type="number" id="kertasHarga" name="kertas_harga" class="input_form" placeholder="Harga Kertas" required>
                        <span>x</span><span id="nilaiDisplayKertas"> 1</span> <span>=</span> <span id="totalKertasDisplay">0</span>
                    </div>
                </div>

                <!-- Asesoris -->
                <div class="form_field_normal" id="asesorisWrapper">
                    <div class="form_between_heading">
                        <label style="margin:0;">Asesoris</label>
                        <button type="button" onclick="tambahAsesorisEdit()">
                            + Tambah Asesoris
                        </button>
                    </div>
                </div>

                <!-- Harga Jenis Pekerjaan (TOTAL) -->
                <div class="form_field_normal">
                    <label>Ongkos Gawe</label>
                    <input type="text" id="totalHargaJenisPekerjaan" class="input_form" readonly
                        value="Rp 0">
                </div>

                <!-- Total Harga Barang -->
                <div class="form_field_normal">
                    <label>Total Harga Barang</label>
                    <input type="text" id="totalHarga" class="input_form" readonly
                        value="Rp 0">
                </div>

                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeEditModalSc()">Batal</button>
                    <button type="submit" id="submitBtn">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalLaba" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">
            <div class="dashboard_popup_order_heading">
                <h2>Kelola Laba</h2>
                <button type="button" onclick="closeModalLaba()">&times;</button>
            </div>

            <form id="formEditLaba" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="editIdLaba" name="id">
                <input type="hidden" id="inputHargaBarang" name="harga_barang" value="0">
                <input type="hidden" id="inputLabaBersih" name="laba_bersih" value="0">
                <input type="hidden" id="inputKomisiAffiliate" name="komisi_affiliate" value="0">

                <!-- Nama Jenis Order -->
                <div class="form_field_normal">
                    <label>Nama Jenis Order</label>
                    <input type="text" id="editNamaLaba" class="input_form" readonly>
                </div>

                <!-- Harga Produksi -->
                <div class="form_field_normal">
                    <label>Harga Produksi</label>
                    <input type="text" id="hargaBarangDisplay" class="input_form" readonly>
                </div>

                <!-- Harga Jual -->
                <div class="form_field_normal">
                    <label>Harga Jual</label>
                    <input type="number" 
                           id="editHargaJual" 
                           name="harga_jual"
                           class="input_form"
                           min="0"
                           step="100"
                           oninput="hitungLabaBersih()"
                           required>
                </div>

                <!-- Biaya -->
                <div class="form_field_normal" id="biayaWrapper">
                    <div class="form_between_heading">
                        <label>Pengeluaran Lain</label>
                        <button type="button" onclick="tambahBiaya()">+ Tambah Biaya</button>
                    </div>
                </div>

                <!-- Persentase Affiliate -->
                <div class="form_field_normal">
                    <label>Persentase Komisi Sales</label>
                    <div class="flex items-center gap-2">
                        <input type="number"
                            id="persentaseAffiliate"
                            name="persentase_affiliate"
                            class="input_form"
                            min="0"
                            max="100"
                            step="0.1"
                            value="0"
                            oninput="hitungLabaBersih()">
                        <span>%</span>
                    </div>
                    <!-- Tambahkan elemen untuk pesan error -->
                    <div id="persentaseError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Komisi Affiliate -->
                <div class="form_field_normal">
                    <label>Komisi Sales</label>
                    <input type="text"
                           id="displayKomisiAffiliate"
                           class="input_form"
                           readonly
                           style="background:#eef6ff;font-weight:600;color:#2563eb;">
                </div>

                <!-- Laba Bersih -->
                <div class="form_field_normal">
                    <label>Laba Bersih</label>
                    <input type="text"
                           id="displayLabaBersih"
                           class="input_form"
                           readonly
                           style="background:#f0fdf4;font-weight:600;color:#16a34a;">
                </div>

                <div class="dashboard_popup_order_btn">
                    <button type="button" onclick="closeModalLaba()">Batal</button>
                    <button type="submit" id="submitBtnLaba">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toast" class="toast hidden">
    <div id="toastMessage" class="toast-message"></div>
</div>

<style>
    /* Style untuk asesoris row */
    .asesorisRow {
        display: flex;
        gap: 10px;
        margin-bottom: 8px;
        align-items: center;
    }

    .asesorisRow .input_form {
        flex: 1;
    }

    .asesorisRow button {
        background-color: #e53e3e;
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .asesorisRow button:hover {
        background-color: #c53030;
    }

     .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #10b981;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    }

    .toast.hidden {
        display: none;
    }

    .toast.error {
        background-color: #ef4444;
    }

    .toast.warning {
        background-color: #f59e0b;
    }

    .toast.info {
        background-color: #3b82f6;
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
    // --- Fungsi modal ---
    function openEditModalSc(id, nama, nilai, id_kategori_jenis_order, belanjaId, bahanHarga = 0, kertasHarga = 0, hargaSetting = 0, hargaPrint = 0, hargaPress = 0, hargaCutting = 0, hargaJahit = 0, hargaFinishing = 0, hargaPacking = 0, asesoris = []) {
        console.log('Opening modal for:', {id, nama, nilai, bahanHarga, kertasHarga, hargaPrint, hargaPress});
        
        // Set input utama
        document.getElementById('editId').value = id;
        document.getElementById('editNama').value = nama ?? '';
        document.getElementById('editNilai').value = nilai ?? 1;
        document.getElementById('editIdKategori').value = id_kategori_jenis_order ?? 1;
        document.getElementById('editKategori').value = id_kategori_jenis_order ?? 1;
        document.getElementById('belanjaId').value = belanjaId ?? '';
        document.getElementById('bahanHarga').value = bahanHarga ?? 0;
        document.getElementById('kertasHarga').value = kertasHarga ?? 0;
        
        // Set harga jenis pekerjaan per item
        document.getElementById('hargaSetting').value = hargaSetting ?? 0;
        document.getElementById('hargaPrint').value = hargaPrint ?? 0;
        document.getElementById('hargaPress').value = hargaPress ?? 0;
        document.getElementById('hargaCutting').value = hargaCutting ?? 0;
        document.getElementById('hargaJahit').value = hargaJahit ?? 0;
        document.getElementById('hargaFinishing').value = hargaFinishing ?? 0;
        document.getElementById('hargaPacking').value = hargaPacking ?? 0;

        // Reset dan render asesoris
        const wrapper = document.getElementById('asesorisWrapper');
        wrapper.innerHTML = `
            <div class="form_between_heading">
                <label style="margin:0;">Asesoris</label>
                <button type="button" onclick="tambahAsesorisEdit()">
                    + Tambah Asesoris
                </button>
            </div>
        `;

        // Parse asesoris data
        let asesorisData = [];
        try {
            if (asesoris && typeof asesoris === 'string') {
                asesorisData = JSON.parse(asesoris);
            } else if (Array.isArray(asesoris)) {
                asesorisData = asesoris;
            }
        } catch (e) {
            console.error('Error parsing asesoris:', e);
        }

        if (asesorisData && asesorisData.length > 0) {
            asesorisData.forEach(a => {
                tambahAsesorisEdit(a.nama || '', a.harga || 0);
            });
        } else {
            // minimal 1 row kosong
            tambahAsesorisEdit('', 0);
        }

        // Show modal
        document.getElementById('modalEditSc').classList.add('active');
        
        // Hitung dan update display
        updateDisplay();
    }

    function closeEditModalSc() {
        document.getElementById('modalEditSc').classList.remove('active');
    }

    // --- Tambah baris asesoris ---
    function tambahAsesorisEdit(nama = '', harga = 0) {
        const wrapper = document.getElementById('asesorisWrapper');
        const div = document.createElement('div');
        div.classList.add('asesorisRow');
        div.innerHTML = `
            <input type="text" name="asesoris_nama[]" class="asesorisNama input_form" placeholder="Nama Asesoris" value="${nama}">
            <input type="number" name="asesoris_harga[]" class="asesorisHarga input_form" placeholder="Harga" value="${harga}" oninput="updateDisplay()">
            <button type="button" onclick="hapusAsesorisRowEdit(this)">-</button>
        `;
        wrapper.appendChild(div);
    }

    function hapusAsesorisRowEdit(button) {
        const row = button.closest('.asesorisRow');
        if (row) {
            row.remove();
            updateDisplay();
        }
    }

    // --- Fungsi utama untuk update semua display ---
    function updateDisplay() {
        const nilai = parseFloat(document.getElementById('editNilai').value) || 1;
        
        // Update display nilai
        document.getElementById('nilaiDisplayBahan').textContent = nilai;
        document.getElementById('nilaiDisplayKertas').textContent = nilai;
        
        // Hitung bahan dan kertas
        const bahan = parseFloat(document.getElementById('bahanHarga').value) || 0;
        const kertas = parseFloat(document.getElementById('kertasHarga').value) || 0;
        const totalBahan = bahan * nilai;
        const totalKertas = kertas * nilai;
        
        // Update display bahan dan kertas
        document.getElementById('totalBahanDisplay').textContent = formatNumber(totalBahan);
        document.getElementById('totalKertasDisplay').textContent = formatNumber(totalKertas);
        
        // Hitung harga jenis pekerjaan (Ongkos Gawe)
        const hargaSetting = parseFloat(document.getElementById('hargaSetting').value) || 0;
        const hargaPrint = parseFloat(document.getElementById('hargaPrint').value) || 0;
        const hargaPress = parseFloat(document.getElementById('hargaPress').value) || 0;
        const hargaCutting = parseFloat(document.getElementById('hargaCutting').value) || 0;
        const hargaJahit = parseFloat(document.getElementById('hargaJahit').value) || 0;
        const hargaFinishing = parseFloat(document.getElementById('hargaFinishing').value) || 0;
        const hargaPacking = parseFloat(document.getElementById('hargaPacking').value) || 0;
        
        // Print dan Press dikalikan dengan nilai
        const totalPrint = hargaPrint * nilai;
        const totalPress = hargaPress * nilai;
        
        // Total harga jenis pekerjaan
        const totalHargaJenisPekerjaan = hargaSetting + totalPrint + totalPress + 
                                        hargaCutting + hargaJahit + hargaFinishing + hargaPacking;
        
        // Update display Ongkos Gawe
        document.getElementById('totalHargaJenisPekerjaan').value = 'Rp ' + formatNumber(totalHargaJenisPekerjaan);
        
        // Hitung total asesoris
        let totalAsesoris = 0;
        document.querySelectorAll('.asesorisHarga').forEach(input => {
            totalAsesoris += parseFloat(input.value) || 0;
        });
        
        // Hitung grand total
        const total = totalBahan + totalKertas + totalHargaJenisPekerjaan + totalAsesoris;
        document.getElementById('totalHarga').value = 'Rp ' + formatNumber(total);
    }

    function formatNumber(angka) {
        if (!angka) angka = 0;
        return angka.toLocaleString('id-ID');
    }

    // --- Event listener untuk semua button ubah ---
    document.addEventListener('DOMContentLoaded', function() {
        // Event listeners untuk auto calculation
        const nilaiInput = document.getElementById('editNilai');
        const bahanInput = document.getElementById('bahanHarga');
        const kertasInput = document.getElementById('kertasHarga');
        
        if (nilaiInput) {
            nilaiInput.addEventListener('input', updateDisplay);
        }
        
        if (bahanInput) {
            bahanInput.addEventListener('input', updateDisplay);
        }
        
        if (kertasInput) {
            kertasInput.addEventListener('input', updateDisplay);
        }

        document.querySelectorAll('.btn-ubah').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const nilai = parseFloat(this.getAttribute('data-nilai')) || 1;
                const id_kategori_jenis_order = parseFloat(this.getAttribute('data-kategori')) || 1;
                const belanjaId = this.getAttribute('data-belanja-id') || '';
                const bahanHarga = parseFloat(this.getAttribute('data-bahan-harga')) || 0;
                const kertasHarga = parseFloat(this.getAttribute('data-kertas-harga')) || 0;
                const hargaSetting = parseFloat(this.getAttribute('data-harga-setting')) || 0;
                const hargaPrint = parseFloat(this.getAttribute('data-harga-print')) || 0;
                const hargaPress = parseFloat(this.getAttribute('data-harga-press')) || 0;
                const hargaCutting = parseFloat(this.getAttribute('data-harga-cutting')) || 0;
                const hargaJahit = parseFloat(this.getAttribute('data-harga-jahit')) || 0;
                const hargaFinishing = parseFloat(this.getAttribute('data-harga-finishing')) || 0;
                const hargaPacking = parseFloat(this.getAttribute('data-harga-packing')) || 0;
                const asesoris = this.getAttribute('data-asesoris');
                
                openEditModalSc(id, nama, nilai, id_kategori_jenis_order, belanjaId, 
                                bahanHarga, kertasHarga,
                                hargaSetting, hargaPrint, hargaPress, hargaCutting,
                                hargaJahit, hargaFinishing, hargaPacking,
                                asesoris);
            });
        });
        
        // Event listeners untuk asesoris
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('asesorisHarga')) {
                updateDisplay();
            }
        });
        
        // Close modal when clicking outside
        const modal = document.getElementById('modalEditSc');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('overlay_close')) {
                    closeEditModalSc();
                }
            });
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModalSc();
            }
        });
    });

    // --- Submit form dengan AJAX ---
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('editId').value;
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        
        // Disable button dan tampilkan loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
        
        // Kumpulkan data form
        const formData = new FormData(this);
        formData.set('_method', 'PUT');
        
        // Harga print dan press sudah dikalikan dengan nilai di frontend saat submit
        // Tapi di backend akan dihitung ulang dengan nilai yang baru
        
        fetch(`/dashboard/orders/setting/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                throw new Error('Response tidak valid');
            }
        })
        .then(data => {
            if (data.success) {
                // Tampilkan toast sukses
                showToast('success', 'Data berhasil diperbarui!');
                
                // Close modal
                closeEditModalSc();
                
                // Reload halaman setelah delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('error', 'Gagal menyimpan: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan saat menyimpan data');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
</script>

<script>
    // Data global untuk menyimpan laba dari database
    let modalData = {
        hargaBarang: 0,
        persentaseAffiliate: 0
    };

    // Fungsi untuk buka modal laba
    function openModalLaba(id, nama, hargaBarang = 0, hargaJual = 0, labaBersih = 0, persentaseAffiliate = 0, biayaData = []) {
        console.log('Opening modal laba for:', {id, nama, hargaBarang, hargaJual, labaBersih, persentaseAffiliate});
        
        try {
            // Simpan data ke global variable
            modalData.hargaBarang = parseFloat(hargaBarang) || 0;
            modalData.persentaseAffiliate = parseFloat(persentaseAffiliate) || 0;
            
            // Set input utama
            document.getElementById('editIdLaba').value = id;
            document.getElementById('editNamaLaba').value = nama ?? '';
            document.getElementById('editHargaJual').value = hargaJual ?? 0;
            document.getElementById('inputHargaBarang').value = modalData.hargaBarang;
            document.getElementById('inputLabaBersih').value = labaBersih ?? 0;
            
            // Set persentase affiliate
            document.getElementById('persentaseAffiliate').value = modalData.persentaseAffiliate;
            
            // Set display harga barang
            document.getElementById('hargaBarangDisplay').value = formatRupiah(modalData.hargaBarang);
            
            // Reset dan render biaya
            const wrapper = document.getElementById('biayaWrapper');
            if (!wrapper) {
                console.error('Element biayaWrapper tidak ditemukan!');
                return;
            }
            
            wrapper.innerHTML = `
                <div class="form_between_heading">
                    <label style="margin:0;">Pengeluaran Lain</label>
                    <button type="button" onclick="tambahBiaya()">
                        + Tambah Biaya
                    </button>
                </div>
            `;

            // Parse biaya data
            let biayaArray = [];
            try {
                if (biayaData && typeof biayaData === 'string') {
                    biayaArray = JSON.parse(biayaData);
                } else if (Array.isArray(biayaData)) {
                    biayaArray = biayaData;
                }
            } catch (e) {
                console.error('Error parsing biaya:', e);
            }

            if (biayaArray && biayaArray.length > 0) {
                biayaArray.forEach(b => {
                    tambahBiaya(b.nama || '', b.harga || 0);
                });
            } else {
                // minimal 1 row kosong
                tambahBiaya('', 0);
            }

            // Hitung laba bersih awal
            hitungLabaBersih();
            
            // Show modal
            const modal = document.getElementById('modalLaba');
            if (modal) {
                modal.classList.add('active');
                console.log('Modal should be visible now');
            } else {
                console.error('Modal element tidak ditemukan!');
            }
            
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Gagal membuka modal: ' + error.message);
        }
    }

    function closeModalLaba() {
        const modal = document.getElementById('modalLaba');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    // Fungsi hitung laba bersih lengkap dengan persentase affiliate
    function hitungLabaBersih() {
        try {
            // Ambil elemen error
            const errorElement = document.getElementById('persentaseError');
            
            // Ambil nilai dari input
            const hargaBarang = modalData.hargaBarang || 0;
            const hargaJual = parseFloat(document.getElementById('editHargaJual').value) || 0;
            const persentaseKomisi = parseFloat(document.getElementById('persentaseAffiliate').value) || 0;
            
            // Reset pesan error
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            
            // Validasi input
            if (hargaBarang < 0) {
                showToast('error', 'Harga barang tidak boleh negatif')
                return;
            }
            
            if (hargaJual < 0) {
                showToast('error', 'Harga jual tidak boleh negatif');
                return;
            }
            
            // Validasi persentase komisi dengan pesan error
            if (persentaseKomisi < 0) {
                errorElement.textContent = 'Persentase komisi tidak boleh negatif';
                errorElement.classList.remove('hidden');
                document.getElementById('persentaseAffiliate').value = 0;
                return;
            }
            
            if (persentaseKomisi > 100) {
                errorElement.textContent = 'Persentase komisi tidak boleh lebih dari 100%';
                errorElement.classList.remove('hidden');
                document.getElementById('persentaseAffiliate').value = 100;
                return;
            }
            
            // Hitung total biaya tambahan
            let totalBiaya = 0;
            document.querySelectorAll('.biayaHarga').forEach(input => {
                const biaya = parseFloat(input.value) || 0;
                if (biaya < 0) {
                    input.value = 0;
                } else {
                    totalBiaya += biaya;
                }
            });

            // Hitung laba kotor: Harga Jual - Harga Barang - Total Biaya
            const labaKotor = hargaJual - hargaBarang - totalBiaya;
            
            // Hitung komisi affiliate (persentase dari laba kotor)
            let komisiAffiliate = 0;
            if (labaKotor > 0 && persentaseKomisi > 0) {
                komisiAffiliate = (labaKotor * persentaseKomisi) / 100;
            }
            
            // Hitung laba bersih setelah komisi: Laba kotor - Komisi
            const labaBersih = labaKotor - komisiAffiliate;

            document.getElementById('displayLabaBersih').value = formatRupiah(labaBersih);
            document.getElementById('displayKomisiAffiliate').value = formatRupiah(komisiAffiliate);
            
            // Update hidden inputs
            document.getElementById('inputLabaBersih').value = labaBersih;
            document.getElementById('inputKomisiAffiliate').value = komisiAffiliate;
            
            // Warna indikator berdasarkan profit/loss
            setProfitIndicator(labaBersih);
            
            // Log untuk debugging
            console.log('Perhitungan Laba:', {
                hargaBarang,
                hargaJual,
                persentaseKomisi: persentaseKomisi + '%',
                totalBiaya,
                labaKotor,
                komisiAffiliate,
                labaBersih
            });
            
            return {
                labaKotor,
                labaBersih,
                komisiAffiliate
            };
            
        } catch (error) {
            console.error('Error dalam hitungLabaBersih:', error);
            document.getElementById('displayLabaBersih').value = 'Error';
            document.getElementById('displayKomisiAffiliate').value = 'Error';
            return {
                labaKotor: 0,
                labaBersih: 0,
                komisiAffiliate: 0
            };
        }
    }

    // Fungsi untuk set warna indikator profit/loss
    function setProfitIndicator(labaBersih) {
        const labaBersihInput = document.getElementById('displayLabaBersih');
        if (labaBersih > 0) {
            labaBersihInput.style.backgroundColor = '#f0fdf4';
            labaBersihInput.style.color = '#16a34a';
            labaBersihInput.style.fontWeight = '600';
        } else if (labaBersih < 0) {
            labaBersihInput.style.backgroundColor = '#fef2f2';
            labaBersihInput.style.color = '#dc2626';
            labaBersihInput.style.fontWeight = '600';
        } else {
            labaBersihInput.style.backgroundColor = '#f9fafb';
            labaBersihInput.style.color = '#6b7280';
            labaBersihInput.style.fontWeight = '400';
        }
    }

    // Submit form laba dengan AJAX
    document.getElementById('formEditLaba')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtnLaba');
        const originalText = submitBtn.textContent;
        const id = document.getElementById('editIdLaba').value;
        
        // Validasi sebelum submit
        const hargaJual = parseFloat(document.getElementById('editHargaJual').value) || 0;
        if (hargaJual <= 0) {
            alert('Harga jual harus lebih dari 0');
            return;
        }
        
        // Hitung ulang untuk memastikan data valid
        const perhitungan = hitungLabaBersih();
        if (perhitungan.labaBersih < 0) {
            const konfirmasi = confirm('Laba bersih bernilai negatif (rugi). Apakah Anda yakin ingin menyimpan?');
            if (!konfirmasi) {
                return;
            }
        }
        
        // Disable button dan tampilkan loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
        
        // Kumpulkan semua data biaya
        const biayaNama = [];
        const biayaHarga = [];
        document.querySelectorAll('.biayaNama').forEach(input => {
            biayaNama.push(input.value);
        });
        document.querySelectorAll('.biayaHarga').forEach(input => {
            biayaHarga.push(parseFloat(input.value) || 0);
        });
        
        // Kumpulkan data form
        const formData = new FormData(this);
        formData.set('_method', 'PUT');
        
        // Tambahkan data biaya sebagai JSON
        const biayaData = [];
        for (let i = 0; i < biayaNama.length; i++) {
            if (biayaNama[i] || biayaHarga[i] > 0) {
                biayaData.push({
                    nama: biayaNama[i],
                    harga: biayaHarga[i]
                });
            }
        }
        formData.append('biaya', JSON.stringify(biayaData));
        
        // Gunakan route yang benar
        fetch(`/dashboard/orders/setting/laba/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Tampilkan toast sukses (jika ada fungsi toast)
                showToast('success', data.message || 'Data laba berhasil diperbarui!');
                
                // Close modal
                closeModalLaba();
                
                // Reload halaman setelah delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Tampilkan error
                showToast('error', data.message || 'Gagal menyimpan data');
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Tampilkan error
            showToast('error', 'Terjadi kesalahan saat menyimpan data');
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });

    // Event listener untuk semua button laba
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-laba').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const hargaBarang = parseFloat(this.getAttribute('data-harga-barang')) || 0;
                const hargaJual = parseFloat(this.getAttribute('data-harga-jual')) || 0;
                const labaBersih = parseFloat(this.getAttribute('data-laba-bersih')) || 0;
                const persentaseAffiliate = parseFloat(this.getAttribute('data-persentase-affiliate')) || 0;
                const biaya = this.getAttribute('data-biaya');
                
                console.log('Data dari button laba:', { 
                    id, nama, hargaBarang, hargaJual, labaBersih, persentaseAffiliate
                });
                
                openModalLaba(id, nama, hargaBarang, hargaJual, labaBersih, persentaseAffiliate, biaya);
            });
        });
    });

    // Fungsi tambah biaya row
    function tambahBiaya(nama = '', harga = 0) {
        const wrapper = document.getElementById('biayaWrapper');
        if (!wrapper) {
            console.error('Element biayaWrapper tidak ditemukan!');
            return;
        }
        
        const div = document.createElement('div');
        div.classList.add('biayaRow', 'flex', 'gap-2', 'mb-2');
        div.innerHTML = `
            <div class="remove_row_style">
                <input type="text" name="biaya_nama[]" class="biayaNama input_form flex-1" 
                    placeholder="Nama Biaya" value="${nama}" oninput="hitungLabaBersih()">
                <input type="number" name="biaya_harga[]" class="biayaHarga input_form w-32" 
                    placeholder="Harga" value="${harga}" min="0" step="100" oninput="hitungLabaBersih()">
                <button type="button" onclick="hapusBiayaRow(this)" class="delete_row_style"><p>x</p></button>
            </div>
        `;
        wrapper.appendChild(div);
        
        // Hitung ulang laba bersih setelah menambah biaya
        hitungLabaBersih();
    }

    // Hapus baris biaya
    function hapusBiayaRow(button) {
        const row = button.closest('.biayaRow');
        if (row) {
            row.remove();
            hitungLabaBersih();
        }
    }

    // Format Rupiah
    function formatRupiah(angka) {
        if (isNaN(angka)) return 'Rp 0';
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        return formatter.format(angka);
    }

    // Auto hitung saat input berubah
    document.addEventListener('DOMContentLoaded', function() {
        // Auto hitung saat harga jual berubah
        const hargaJualInput = document.getElementById('editHargaJual');
        if (hargaJualInput) {
            hargaJualInput.addEventListener('input', function() {
                hitungLabaBersih();
            });
        }
        
        // Auto hitung saat persentase affiliate berubah
        const persentaseInput = document.getElementById('persentaseAffiliate');
        if (persentaseInput) {
            persentaseInput.addEventListener('input', function() {
                // Batasi maksimal 100%
                if (this.value > 100) {
                    this.value = 100;
                }
                hitungLabaBersih();
            });
        }
        
        // Delegasi event untuk biaya yang ditambahkan dinamis
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('biayaHarga') || e.target.classList.contains('biayaNama')) {
                hitungLabaBersih();
            }
        });
    });

    // Fungsi untuk reset form (opsional)
    function resetFormLaba() {
        document.getElementById('formEditLaba').reset();
        document.getElementById('biayaWrapper').innerHTML = `
            <div class="form_between_heading">
                <label style="margin:0;">Pengeluaran Lain</label>
                <button type="button" onclick="tambahBiaya()">
                    + Tambah Biaya
                </button>
            </div>
        `;
        tambahBiaya('', 0);
        hitungLabaBersih();
    }
</script>
<script>
        // Fungsi toast yang lebih lengkap
    function showToast(type, message, duration = 3000) {
        // Hapus toast yang sudah ada
        const existingToast = document.getElementById('toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Buat elemen toast baru
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = `toast ${type}`;
        
        // Tambahkan ikon berdasarkan tipe
        let icon = '';
        switch(type) {
            case 'success':
                icon = '✓';
                break;
            case 'error':
                icon = '✗';
                break;
            case 'warning':
                icon = '⚠';
                break;
            case 'info':
                icon = 'ℹ';
                break;
        }
        
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
            </div>
        `;
        
        // Tambahkan ke body
        document.body.appendChild(toast);
        
        // Tampilkan dengan animasi
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        
        // Sembunyikan setelah duration
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, duration);
    }

    // Style untuk toast yang lebih lengkap
    const toastStyle = document.createElement('style');
    toastStyle.textContent = `
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            max-width: 350px;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .toast.success {
            background-color: #10b981;
        }
        
        .toast.error {
            background-color: #ef4444;
        }
        
        .toast.warning {
            background-color: #f59e0b;
        }
        
        .toast.info {
            background-color: #3b82f6;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .toast-icon {
            font-weight: bold;
            font-size: 18px;
        }
        
        .toast-message {
            font-size: 14px;
            line-height: 1.4;
        }
    `;
    document.head.appendChild(toastStyle);
</script>