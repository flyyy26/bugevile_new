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
                        id="tabSetting"
                        onclick="switchTab('setting')"
                    >
                        Kategori
                    </button>

                    <button
                        id="tabJob"
                        onclick="switchTab('job')"
                    >
                        Jenis Job
                    </button>

                    <button
                        id="tabAffiliator"
                        onclick="switchTab('affiliator')"
                    >
                        Affiliator
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
        
        <div id="content-setting" class="hidden">
            <div class="orders_table_container">
                <table>
                    <thead class="bg-gray-800">
                        <tr>
                            <th><div class="text-center">No</div></th>
                            <th>Nama Jenis</th>
                            <th><div class="text-center">Kategori</div></th>
                            <th><div class="text-center">Nilai</div></th>
                            <th><div class="text-center">Aksi</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jenisOrders as $jo)
                        <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                            <td><div class="text-center">{{ $loop->iteration }}</div></td>
                            <td>{{ $jo->nama_jenis }}</td>
                            <td><div class="text-center">{{ $jo->kategori->nama ?? '-' }}</div></td>
                            <td><div class="text-center">{{ $jo->nilai }}</div></td>
                            <td>
                                <div class="btn_table_action">
                                    <button type="button"
                                        onclick="openEditModal({{ $jo->id }}, '{{ $jo->nama_jenis }}', {{ $jo->nilai }}, {{ $jo->id_kategori_jenis_order }})"
                                        class="bg-blue-500">
                                        Edit
                                    </button>
                                    <form action="{{ route('jenis-order.destroy', $jo->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus jenis order?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if ($jenisOrders->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <button onclick="openModalJenisOrder()" class="setting_add_table">
                    + Tambah Kategori
                </button>
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
                                    <form action="{{ route('pengaturan.job.destroy', $jobs->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus job?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red">
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

        <div id="content-affiliator" class="ongkos_layout_container">
            <form action="{{ route('pengaturan.affiliator.update', $komisi->id) }}" method="POST">
                @csrf
                {{-- Method PUT wajib ada untuk proses Update di Laravel --}}
                @method('PUT') 

                <div class="form_field_normal">
                    <div class="form_field_normal form_field_no_margin">
                        <label>Harga Affiliator (Komisi)</label>
                        <input type="number"
                            name="harga" 
                            value="{{ old('harga', (int)($komisi->harga ?? 0)) }}"
                            class="input_form"
                            placeholder="Contoh: 3000"
                            step="1"
                            required>
                    </div>
                </div>
                
                <div class="dashboard_popup_order_btn" style="display:block; margin-right:auto;">
                    <button type="submit">
                        Update Harga
                    </button>
                </div>
            </form>
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
    <div onclick="closeModalJenisOrder()" class="overlay_close"></div>
    <!-- MODAL BOX -->
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Jenis Order</h2>
            <button onclick="closeModalJenisOrder()">&times;</button>
        </div>

        <form action="{{ route('jenis-order.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Input Nama -->
            <div class="form_field_normal">
                <label>Nama Jenis Order</label>
                <input type="text" name="nama_jenis"
                    class="input_form"
                    placeholder="Contoh: Standard, Express, Premium"
                    required>
            </div>

            <!-- Input Nilai -->
            <div class="form_field_normal">
                <label>Nilai (Multiplier)</label>
                <input type="number" name="nilai"
                    class="input_form"
                    placeholder="Contoh: 2, 4, 5"
                    required>
            </div>

            <!-- Select Kategori -->
            <div class="form_field_normal">
                <label>
                    Kategori Jenis Order
                </label>

                <div class="flex kategori_popup_style" style="gap:.7rem;">
                    <select name="id_kategori_jenis_order"
                        class="input_form"
                        id="selectKategori" required>

                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->nama }}
                            </option>
                        @endforeach

                    </select>

                    <button type="button"
                        onclick="openModalKategori()">
                        +
                    </button>
                </div>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalJenisOrder()">
                    Batal
                </button>

                <button type="submit">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<!-- MODAL EDIT -->
<div id="modalEdit" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeEditModal()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Edit Jenis Order</h2>
            <button onclick="closeEditModal()">&times;</button>
        </div>

        <form id="formEdit" onsubmit="simpanEdit(event)">
            @csrf

            <input type="hidden" id="editId">

            <!-- Nama -->
            <div class="form_field_normal">
                <label>Nama Jenis Order</label>
                <input type="text" id="editNama" class="input_form" required>
            </div>

            <!-- Nilai -->
            <div class="form_field_normal">
                <label>Nilai (Multiplier)</label>
                <input type="number" id="editNilai" class="input_form" required>
            </div>

            <!-- Kategori -->
            <div class="form_field_normal">
                <label>Kategori</label>
                <select id="editKategori" class="input_form" required>
                    @foreach ($kategoriList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeEditModal()">
                    Batal
                </button>

                <button type="submit">
                    Simpan Perubahan
                </button>
            </div>
        </form>

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

<script>
function switchTab(tab) {
    const contents = {
        ongkos: document.getElementById('content-ongkos'),
        setting: document.getElementById('content-setting'),
        job: document.getElementById('content-job'),
        affiliator: document.getElementById('content-affiliator')
    };

    const tabs = {
        ongkos: document.getElementById('tabOngkos'),
        setting: document.getElementById('tabSetting'),
        job: document.getElementById('tabJob'),
        affiliator: document.getElementById('tabAffiliator')
    };

    // Hide all content panels
    Object.values(contents).forEach(c => { if (c) c.classList.add('hidden'); });

    // Reset all tab styles (unselected)
    Object.values(tabs).forEach(t => {
        if (!t) return;
        t.classList.remove('bg-green-500');
        t.classList.add('bg-white');
    });

    // Show selected content and mark selected tab
    if (contents[tab]) contents[tab].classList.remove('hidden');
    if (tabs[tab]) {
        tabs[tab].classList.add('bg-green-500');
        tabs[tab].classList.remove('bg-white');
    }
    // Persist selected tab so page reloads keep the same tab
    try {
        localStorage.setItem('pengaturanActiveTab', tab);
    } catch (e) {
        // ignore
    }
}
</script>
<script>
    // Restore last active tab after page load (if any)
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const saved = localStorage.getItem('pengaturanActiveTab');
            if (saved && ['ongkos','setting','job', 'affiliator'].includes(saved)) {
                switchTab(saved);
                return;
            }
        } catch (e) {
            // ignore localStorage errors
        }

        // default
        switchTab('ongkos');
    });
</script>
<script>
    // Check if URL has hash #setting
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#setting') {
            // Switch to setting tab
            switchTab('setting');
            
            // Optional: scroll to the form section
            const settingContent = document.getElementById('content-setting');
            if (settingContent) {
                settingContent.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
<script>
    function openEditModal(id, nama, nilai, kategori) {
        document.getElementById('editId').value = id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editNilai').value = nilai;
        document.getElementById('editKategori').value = kategori;

        document.getElementById('modalEdit').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.remove('active');
    }

    function simpanEdit(event) {
        event.preventDefault();

        const id = document.getElementById('editId').value;

        const payload = {
            nama_jenis: document.getElementById('editNama').value,
            nilai: document.getElementById('editNilai').value,
            id_kategori_jenis_order: document.getElementById('editKategori').value,
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };

        fetch(`/dashboard/orders/setting/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Berhasil diperbarui!");
                location.reload();
            } else {
                alert("Gagal menyimpan perubahan!");
            }
        })
        .catch(err => console.error(err));
    }

</script>

<script>
    function openModalJenisOrder() {
        document.getElementById("modalJenisOrder").classList.add("active")
    }

    function closeModalJenisOrder() {
        document.getElementById("modalJenisOrder").classList.remove("active")
    }

    // Tutup saat klik background
    document.getElementById("modalJenisOrder").addEventListener("click", function(e) {
        if (e.target === this) {
            closeModalJenisOrder()
        }
    })
</script>


<script>
function openModalKategori() {
    document.getElementById('modalKategori').classList.add('active');
}

function closeModalKategori() {
    document.getElementById('modalKategori').classList.remove('active');
    document.getElementById('inputKategoriBaru').value = '';
}

function simpanKategoriBaru(event) {
    event.preventDefault();

    const nama = document.getElementById('inputKategoriBaru').value.trim();
    if (!nama) return alert("Nama kategori wajib diisi!");

    // Kirim ke server
    fetch("{{ route('kategori-jenis-order.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ nama })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            const select = document.getElementById('selectKategori');

            // Tambahkan opsi baru
            const option = document.createElement('option');
            option.value = data.data.id;
            option.textContent = data.data.nama;
            option.selected = true;

            select.appendChild(option);

            closeModalKategori();
        }
    })
    .catch(err => console.error(err));
}
</script>
<script>
    function openJobCreateModal() {
        document.getElementById('modalJobTitle').textContent = 'Tambah Job';
        document.getElementById('jobId').value = '';
        document.getElementById('jobNama').value = '';
        document.getElementById('modalJob').classList.add('active');
    }

    function openJobEditModal(id, nama) {
        document.getElementById('modalJobTitle').textContent = 'Edit Job';
        document.getElementById('jobId').value = id;
        document.getElementById('jobNama').value = nama;
        document.getElementById('modalJob').classList.add('active');
    }

    function closeJobModal() {
        document.getElementById('modalJob').classList.remove('active');
    }

    function simpanJob(e) {
        e.preventDefault();
        const id = document.getElementById('jobId').value;
        const nama = document.getElementById('jobNama').value.trim();
        if (!nama) return alert('Nama job wajib diisi');
        const url = id ? `/dashboard/pengaturan/job/${id}` : '/dashboard/pengaturan/job';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
            alert('Gagal menyimpan job. Cek console (Network/Response) untuk detail.');
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    }
</script>


@endsection
