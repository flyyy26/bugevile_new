@extends('layouts.dashboard')

@section('title', 'Pengaturan')

@section('content')
<img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">
<div class="progress_custom">
    <div class="px-4 pt-6 flex flex-col-reverse justify-between items-start mt-16 gap-2">

        <div class="relative z-20 mb-3">
            <button
                id="tabOngkos"
                onclick="switchTab('ongkos')"
                class="px-4 py-2 rounded-lg bg-green-400 text-black font-semibold"
            >
                Ongkos Karyawan
            </button>

            <button
                id="tabSetting"
                onclick="switchTab('setting')"
                class="px-4 py-2 rounded-lg bg-white text-black"
            >
                Setting Jenis
            </button>

            <button
                id="tabJob"
                onclick="switchTab('job')"
                class="px-4 py-2 rounded-lg bg-white text-black"
            >
                Jenis Job
            </button>

        </div>

        {{-- BUTTON LOGOUT --}}
        <form action="/dashboard/logout" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm z-20 relative">
                Logout
            </button>
        </form>


    </div>

    <div id="content-ongkos" class="w-full p-4 grid grid-cols-1 md:grid-cols-4 gap-6 z-20 relative bg_custom">

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
        <div class="bg-white shadow rounded-lg p-6 border">
            <form action="{{ route('harga.update', $field) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Harga {{ $label }}</label>
                    <input type="number"
                        name="value"
                        value="{{ $harga->$field ?? '' }}"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contoh: 3000"
                        required>
                </div>
                <button class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                    Simpan
                </button>
            </form>
        </div>
        @endforeach

    </div>
    
    <div id="content-setting" class="hidden w-full z-20 relative mt-10">
        <button onclick="openModalJenisOrder()"
            class="px-4 py-2 bg-white ml-auto block mr-4 mb-4 text-black rounded hover:bg-white">
            + Tambah Jenis Order
        </button>

        <table class="w-full text-sm border">
            <thead class="bg-gray-800">
                <tr>
                    <th class="p-2 border text-white">No</th>
                    <th class="p-2 border text-white">Nama Jenis</th>
                    <th class="p-2 border text-white text-center">Kategori Jenis</th>
                    <th class="p-2 border text-white">Nilai</th>
                    <th class="p-2 border text-white w-20">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jenisOrders as $jo)
                <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                    <td class="p-2 text-center">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $jo->nama_jenis }}</td>
                    <td class="p-2 text-center">{{ $jo->kategori->nama ?? '-' }}</td>
                    <td class="p-2 text-center">{{ $jo->nilai }}</td>
                    <td class="p-2 text-center">
                        <div class="flex items-center gap-1">
                            <button type="button"
                                onclick="openEditModal({{ $jo->id }}, '{{ $jo->nama_jenis }}', {{ $jo->nilai }}, {{ $jo->id_kategori_jenis_order }})"
                                class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                                Edit
                            </button>
                            <form action="{{ route('jenis-order.destroy', $jo->id) }}" method="POST"
                                onsubmit="return confirm('Hapus jenis order?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if ($jenisOrders->isEmpty())
                <tr>
                    <td colspan="4" class="text-center p-3">Belum ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div id="content-job" class="hidden w-full z-20 relative mt-10">
        <div class="mb-4">
            <button onclick="openJobCreateModal()"
                class="px-4 py-2 bg-white ml-auto block mr-4 mb-4 text-black rounded hover:bg-white">
                + Tambah Job
            </button>
        </div>

        <table class="w-full text-sm border">
            <thead class="bg-gray-800">
                <tr>
                    <th class="p-2 border text-white">No</th>
                    <th class="p-2 border text-white">Nama Job</th>
                    <th class="p-2 border text-white w-20">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($job as $jobs)
                <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                    <td class="p-2 text-center">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $jobs->nama_job }}</td>
                        <td class="p-2 text-center">
                            <div class="flex items-center gap-1">
                                <button type="button"
                                    onclick="openJobEditModal({{ $jobs->id }}, '{{ $jobs->nama_job }}')"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                                    Edit
                                </button>
                                <form action="{{ route('pengaturan.job.destroy', $jobs->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus job?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                </tr>
                @endforeach

                @if ($job->isEmpty())
                <tr>
                    <td colspan="4" class="text-center p-3">Belum ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- JOB MODAL (create/edit) -->
<div id="modalJob" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white p-5 rounded shadow-lg w-96">
        <h3 id="modalJobTitle" class="text-lg font-bold mb-3">Tambah Job</h3>
        <form id="formJob" onsubmit="simpanJob(event)">
            @csrf
            <input type="hidden" id="jobId" value="">
            <div class="mb-3">
                <label class="text-sm">Nama Job</label>
                <input type="text" id="jobNama" class="w-full border px-3 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeJobModal()" class="px-3 py-1">Batal</button>
                <button class="bg-green-600 text-white px-3 py-1 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL BACKDROP -->
<div id="modalJenisOrder"
    class="hidden fixed inset-0 bg-black/60 z-40 flex items-center justify-center">

    <!-- MODAL BOX -->
    <div class="bg-white rounded-lg w-1/2 p-6 relative">

        <!-- CLOSE BUTTON -->
        <button onclick="closeModalJenisOrder()"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold mb-4">Tambah Jenis Order</h2>

        <form action="{{ route('jenis-order.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-3 gap-4">

                <!-- Input Nama -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Jenis Order</label>
                    <input type="text" name="nama_jenis"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contoh: Standard, Express, Premium"
                        required>
                </div>

                <!-- Input Nilai -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nilai (Multiplier)</label>
                    <input type="number" name="nilai"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contoh: 2, 4, 5"
                        required>
                </div>

                <!-- Select Kategori -->
                <div class="w-full">
                    <label class="block text-sm font-medium mb-1">
                        Kategori Jenis Order
                    </label>

                    <div class="flex gap-2">
                        <select name="id_kategori_jenis_order"
                            class="w-full border rounded px-3 py-2"
                            id="selectKategori" required>

                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->nama }}
                                </option>
                            @endforeach

                        </select>

                        <button type="button"
                            onclick="openModalKategori()"
                            class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            +
                        </button>
                    </div>
                </div>

            </div>

            <div class="text-center pt-4">
                <button type="submit"
                    class="bg-yellow-400 text-black px-6 py-2 rounded hover:bg-yellow-500">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<!-- MODAL EDIT -->
<div id="modalEdit"
    class="fixed inset-0 z-20 bg-black bg-opacity-50 flex items-center justify-center hidden">

    <div class="bg-white p-5 rounded shadow-lg w-96">
        <h2 class="text-lg font-bold mb-3">Edit Jenis Order</h2>

        <form id="formEdit" onsubmit="simpanEdit(event)">
            @csrf

            <input type="hidden" id="editId">

            <!-- Nama -->
            <label class="block text-sm font-medium mb-1">Nama Jenis Order</label>
            <input type="text" id="editNama" class="w-full border rounded px-3 py-2 mb-3" required>

            <!-- Nilai -->
            <label class="block text-sm font-medium mb-1">Nilai (Multiplier)</label>
            <input type="number" id="editNilai" class="w-full border rounded px-3 py-2 mb-3" required>

            <!-- Kategori -->
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select id="editKategori" class="w-full border rounded px-3 py-2 mb-3" required>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeEditModal()"
                    class="px-3 py-2 bg-gray-300 rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Background -->
<div id="modalKategori"
    class="fixed z-20 inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">

    <div class="bg-white p-5 rounded shadow-lg w-80">
        <h2 class="text-lg font-bold mb-3">Tambah Kategori Baru</h2>

        <form id="formKategoriBaru" onsubmit="simpanKategoriBaru(event)">
            <input type="text" id="inputKategoriBaru"
                class="w-full border rounded px-3 py-2"
                placeholder="Nama kategori..." required>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModalKategori()"
                    class="px-3 py-2 bg-gray-300 rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">
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
        job: document.getElementById('content-job')
    };

    const tabs = {
        ongkos: document.getElementById('tabOngkos'),
        setting: document.getElementById('tabSetting'),
        job: document.getElementById('tabJob')
    };

    // Hide all content panels
    Object.values(contents).forEach(c => { if (c) c.classList.add('hidden'); });

    // Reset all tab styles (unselected)
    Object.values(tabs).forEach(t => {
        if (!t) return;
        t.classList.remove('bg-green-400');
        t.classList.add('bg-white');
    });

    // Show selected content and mark selected tab
    if (contents[tab]) contents[tab].classList.remove('hidden');
    if (tabs[tab]) {
        tabs[tab].classList.add('bg-green-400');
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
            if (saved && ['ongkos','setting','job'].includes(saved)) {
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
    function openEditModal(id, nama, nilai, kategori) {
        document.getElementById('editId').value = id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editNilai').value = nilai;
        document.getElementById('editKategori').value = kategori;

        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
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
        document.getElementById("modalJenisOrder").classList.remove("hidden")
    }

    function closeModalJenisOrder() {
        document.getElementById("modalJenisOrder").classList.add("hidden")
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
    document.getElementById('modalKategori').classList.remove('hidden');
}

function closeModalKategori() {
    document.getElementById('modalKategori').classList.add('hidden');
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
        document.getElementById('modalJob').classList.remove('hidden');
    }

    function openJobEditModal(id, nama) {
        document.getElementById('modalJobTitle').textContent = 'Edit Job';
        document.getElementById('jobId').value = id;
        document.getElementById('jobNama').value = nama;
        document.getElementById('modalJob').classList.remove('hidden');
    }

    function closeJobModal() {
        document.getElementById('modalJob').classList.add('hidden');
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
