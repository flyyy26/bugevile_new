@extends('layouts.dashboard')

@section('title', 'Pesanan')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Spesifikasi Produksi</h1>

    {{-- ======== TABEL ========== --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- JENIS BAHAN --}}
        <div>
            <div class="flex justify-between mb-2">
                <h2 class="font-bold">Jenis Bahan</h2>
                <button onclick="openModal('modalBahan')" class="bg-blue-600 text-white px-3 py-1 rounded">+ Tambah</button>
            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Gambar</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($bahan as $jb)
                        <tr>
                            <td class="border px-4 py-2">
                                @if ($jb->gambar)
                                    <img src="{{ asset('storage/' . $jb->gambar) }}" 
                                        alt="{{ $jb->nama }}" 
                                        class="h-16 w-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>

                            <td class="border px-4 py-2">{{ $jb->nama }}</td>

                            <td class="border px-4 py-2">
                                <button 
                                    type="button"
                                    onclick="openEditModalBahan(this)"
                                    data-id-bahan="{{ $jb->id }}"
                                    data-nama-bahan="{{ $jb->nama }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit
                                </button>
                                <form action="{{ url('/spesifikasi/bahan/' . $jb->id) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jenis bahan ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- JENIS POLA --}}
        <div>
            <div class="flex justify-between mb-2">
                <h2 class="font-bold">Jenis Pola</h2>
                <button onclick="openModal('modalPola')" class="bg-blue-600 text-white px-3 py-1 rounded">+ Tambah</button>
            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Gambar</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pola as $jp)
                        <tr>
                            <td class="border px-4 py-2">
                                @if ($jp->gambar)
                                    <img src="{{ asset('storage/' . $jp->gambar) }}" 
                                        alt="{{ $jp->nama }}" 
                                        class="h-16 w-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>

                            <td class="border px-4 py-2">{{ $jp->nama }}</td>

                            <td class="border px-4 py-2">
                                <button 
                                    type="button"
                                    onclick="openEditModalPola(this)"
                                    data-id-pola="{{ $jp->id }}"
                                    data-nama-pola="{{ $jp->nama }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit
                                </button>
                                <form action="{{ url('/spesifikasi/pola/' . $jp->id) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jenis pola ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- JENIS KERAH --}}
        <div>
            <div class="flex justify-between mb-2">
                <h2 class="font-bold">Jenis Kerah</h2>
                <button onclick="openModal('modalKerah')" class="bg-blue-600 text-white px-3 py-1 rounded">+ Tambah</button>
            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Gambar</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($kerah as $jk)
                        <tr>
                            <td class="border px-4 py-2">
                                @if ($jk->gambar)
                                    <img src="{{ asset('storage/' . $jk->gambar) }}" 
                                        alt="{{ $jk->nama }}" 
                                        class="h-16 w-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>

                            <td class="border px-4 py-2">{{ $jk->nama }}</td>

                            <td class="border px-4 py-2">
                                <button 
                                    type="button"
                                    onclick="openEditModalKerah(this)"
                                    data-id-kerah="{{ $jk->id }}"
                                    data-nama-kerah="{{ $jk->nama }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit
                                </button>
                                <form action="{{ url('/spesifikasi/kerah/' . $jk->id) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jenis kerah ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- JENIS JAHITAN --}}
        <div>
            <div class="flex justify-between mb-2">
                <h2 class="font-bold">Jenis Jahitan</h2>
                <button onclick="openModal('modalJahitan')" class="bg-blue-600 text-white px-3 py-1 rounded">+ Tambah</button>
            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Gambar</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($jahitan as $jk)
                        <tr>
                            <td class="border px-4 py-2">
                                @if ($jk->gambar)
                                    <img src="{{ asset('storage/' . $jk->gambar) }}" 
                                        alt="{{ $jk->nama }}" 
                                        class="h-16 w-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>

                            <td class="border px-4 py-2">{{ $jk->nama }}</td>

                            <td class="border px-4 py-2">
                                <!-- Tombol EDIT -->
                                <button 
                                    type="button"
                                    onclick="openEditModal(this)"
                                    data-id="{{ $jk->id }}"
                                    data-nama="{{ $jk->nama }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit
                                </button>
                                <form action="{{ url('/spesifikasi/jahitan/' . $jk->id) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jenis jahitan ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- ======== POPUP MODAL ======== --}}
    @include('dashboard.spesifikasi-modals')

</div>

<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-96 p-6">
        <h2 class="text-lg font-bold mb-4">Edit Jenis Jahitan</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">Nama Jahitan</label>
                <input
                    type="text"
                    name="nama"
                    id="editNama"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-3">
                <label class="text-sm">Ganti Gambar (optional)</label>
                <input
                    type="file"
                    name="gambar"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-3 py-1 bg-gray-400 text-white rounded"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-3 py-1 bg-green-500 text-white rounded"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="editModalBahan" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-96 p-6">
        <h2 class="text-lg font-bold mb-4">Edit Jenis Bahan</h2>

        <form id="editFormBahan" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">Nama Bahan</label>
                <input
                    type="text"
                    name="nama"
                    id="editNamaBahan"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-3">
                <label class="text-sm">Ganti Gambar (optional)</label>
                <input
                    type="file"
                    name="gambar"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeEditModalBahan()"
                    class="px-3 py-1 bg-gray-400 text-white rounded"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-3 py-1 bg-green-500 text-white rounded"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="editModalPola" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-96 p-6">
        <h2 class="text-lg font-bold mb-4">Edit Jenis Pola</h2>

        <form id="editFormPola" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">Nama Pola</label>
                <input
                    type="text"
                    name="nama"
                    id="editNamaPola"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-3">
                <label class="text-sm">Ganti Gambar (optional)</label>
                <input
                    type="file"
                    name="gambar"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeEditModalPola()"
                    class="px-3 py-1 bg-gray-400 text-white rounded"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-3 py-1 bg-green-500 text-white rounded"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="editModalKerah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-96 p-6">
        <h2 class="text-lg font-bold mb-4">Edit Jenis Kerah</h2>

        <form id="editFormKerah" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">Nama Kerah</label>
                <input
                    type="text"
                    name="nama"
                    id="editNamaKerah"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-3">
                <label class="text-sm">Ganti Gambar (optional)</label>
                <input
                    type="file"
                    name="gambar"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeEditModalKerah()"
                    class="px-3 py-1 bg-gray-400 text-white rounded"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-3 py-1 bg-green-500 text-white rounded"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>

<script>
function openEditModal(button) {
    const id   = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');

    const modal = document.getElementById('editModal');
    const form  = document.getElementById('editForm');
    const inputNama = document.getElementById('editNama');

    form.action = `/spesifikasi/jahitan/${id}`;
    inputNama.value = nama;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
<script>
function openEditModalBahan(button) {
    const idBahan   = button.getAttribute('data-id-bahan');
    const namaBahan = button.getAttribute('data-nama-bahan');

    const modalBahan = document.getElementById('editModalBahan');
    const formBahan  = document.getElementById('editFormBahan');
    const inputNamaBahan = document.getElementById('editNamaBahan');

    formBahan.action = `/spesifikasi/bahan/${idBahan}`;
    inputNamaBahan.value = namaBahan;

    modalBahan.classList.remove('hidden');
    modalBahan.classList.add('flex');
}

function closeEditModalBahan() {
    const modalBahan = document.getElementById('editModalBahan');

    modalBahan.classList.add('hidden');
    modalBahan.classList.remove('flex');
}
</script>
<script>
function openEditModalKerah(button) {
    const idKerah   = button.getAttribute('data-id-kerah');
    const namaKerah = button.getAttribute('data-nama-kerah');

    const modalKerah = document.getElementById('editModalKerah');
    const formKerah  = document.getElementById('editFormKerah');
    const inputNamaKerah = document.getElementById('editNamaKerah');

    formKerah.action = `/spesifikasi/kerah/${idKerah}`;
    inputNamaKerah.value = namaKerah;

    modalKerah.classList.remove('hidden');
    modalKerah.classList.add('flex');
}

function closeEditModalKerah() {
    const modalKerah = document.getElementById('editModalKerah');

    modalKerah.classList.add('hidden');
    modalKerah.classList.remove('flex');
}
</script>
<script>
function openEditModalPola(button) {
    const idPola   = button.getAttribute('data-id-pola');
    const namaPola = button.getAttribute('data-nama-pola');

    const modalPola = document.getElementById('editModalPola');
    const formPola  = document.getElementById('editFormPola');
    const inputNamaPola = document.getElementById('editNamaPola');

    formPola.action = `/spesifikasi/pola/${idPola}`;
    inputNamaPola.value = namaPola;

    modalPola.classList.remove('hidden');
    modalPola.classList.add('flex');
}

function closeEditModalPola() {
    const modalPola = document.getElementById('editModalPola');

    modalPola.classList.add('hidden');
    modalPola.classList.remove('flex');
}
</script>

@endsection
