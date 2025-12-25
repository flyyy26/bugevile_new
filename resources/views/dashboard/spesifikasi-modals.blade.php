{{-- Modal Tambah Bahan --}}
<div id="modalBahan" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="font-bold mb-3">Tambah Jenis Bahan</h2>

        <form action="/spesifikasi/bahan" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="block mb-2 text-sm font-medium">Nama Bahan</label>
            <input type="text" name="nama" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2 text-sm font-medium">Gambar (Opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="mb-4">

            <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            <button type="button" onclick="closeModal('modalBahan')" class="ml-2 text-gray-600">Batal</button>
        </form>
    </div>
</div>

{{-- Modal Tambah Pola --}}
<div id="modalPola" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="font-bold mb-3">Tambah Jenis Pola</h2>

        <form action="/spesifikasi/pola" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="block mb-2 text-sm font-medium">Nama Pola</label>
            <input type="text" name="nama" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2 text-sm font-medium">Gambar (Opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="mb-4">

            <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            <button type="button" onclick="closeModal('modalPola')" class="ml-2 text-gray-600">Batal</button>
        </form>
    </div>
</div>

{{-- Modal Tambah Kerah --}}
<div id="modalKerah" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="font-bold mb-3">Tambah Jenis Kerah</h2>

        <form action="/spesifikasi/kerah" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="block mb-2 text-sm font-medium">Nama Kerah</label>
            <input type="text" name="nama" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2 text-sm font-medium">Gambar (Opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="mb-4">

            <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            <button type="button" onclick="closeModal('modalKerah')" class="ml-2 text-gray-600">Batal</button>
        </form>
    </div>
</div>

{{-- Modal Tambah Jahitan --}}
<div id="modalJahitan" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="font-bold mb-3">Tambah Jenis Jahitan</h2>

        <form action="/spesifikasi/jahitan" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label class="block mb-2 text-sm font-medium">Nama Jahitan</label>
            <input type="text" name="nama" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2 text-sm font-medium">Gambar (Opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="mb-4">

            <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            <button type="button" onclick="closeModal('modalJahitan')" class="ml-2 text-gray-600">Batal</button>
        </form>
    </div>
</div>