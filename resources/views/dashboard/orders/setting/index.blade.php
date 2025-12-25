@extends('layouts.dashboard')

@section('title', 'Order Setting')

@section('content')

<div class="bg-black">
    <img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">

    {{-- FORM INPUT --}}
    <form action="{{ route('jenis-order.store') }}" method="POST" class="mb-6">
        @csrf

        <div class="grid grid-cols-3 gap-4 px-4 z-20 relative mt-36">

            <!-- Input Nama -->
            <div>
                <label class="block text-sm font-medium text-white mb-1">Nama Jenis Order</label>
                <input type="text" name="nama_jenis" 
                    class="w-full border rounded px-3 py-2" 
                    placeholder="Contoh: Standard, Express, Premium" required>
            </div>

            <!-- Input Nilai -->
            <div>
                <label class="block text-sm font-medium text-white mb-1">Nilai (Multiplier)</label>
                <input type="number" name="nilai" 
                    class="w-full border rounded px-3 py-2" 
                    placeholder="Contoh: 2, 4, 5" required>
            </div>

            <!-- Select Kategori -->
            <div class="w-full z-20 relative">
                <label class="block text-sm font-medium text-white mb-1">Kategori Jenis Order</label>
                <div class="flex gap-2 w-full">

                    <select name="id_kategori_jenis_order" class="w-full" id="selectKategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>

                    <!-- Tombol tambah -->
                    <button type="button"
                        onclick="openModalKategori()"
                        class="px-3 py-2 bg-red-600 text-black rounded hover:bg-red-700">
                        +
                    </button>

                </div>
            </div>

        </div>

        <button type="submit"
            class="z-20 relative m-auto block mt-4 bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500">
            Simpan
        </button>
    </form>


    {{-- TABEL DATA --}}
    <div class="bg-white shadow rounded z-20 relative">
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
</div>

<!-- MODAL EDIT -->
<div id="modalEdit"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">

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
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">

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


@endsection