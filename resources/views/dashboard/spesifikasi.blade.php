@extends('layouts.dashboard')

@section('title','Spesifikasi')

@section('content')

<div class="w-full p-4">

    {{-- TAB --}}
    <div class="flex flex-wrap gap-2 mb-6 border-b pb-2">
        @foreach($kategori as $index => $k)
            <button
                onclick="showTab({{ $k->id }})"
                id="tab-{{ $k->id }}"
                class="px-4 py-2 rounded-lg text-sm font-semibold bg-gray-200 text-gray-700"
            >
                {{ $k->nama }}
            </button>
        @endforeach
    </div>

    {{-- CONTENT --}}
    @foreach($kategori as $index => $k)
        <div class="kategori-content hidden" id="content-{{ $k->id }}">

            <div class="grid grid-cols-1 md:grid-cols-8 gap-4 items-center">

                @foreach($k->jenisSpek as $index => $s)
                    <button
                        onclick="showSpekDetail({{ $k->id }}, {{ $s->id }})"
                        id="spek-tab-{{ $s->id }}"
                        class="p-2 py-2 shadow rounded-lg border text-center transition {{ $index === 0 ? 'bg-blue-600 text-white' : 'bg-white' }}"
                    >
                        <h3 class="font-semibold text-sm">
                            {{ $s->nama_jenis_spek }}
                        </h3>
                    </button>
                @endforeach


                {{-- BUTTON TAMBAH — SELALU ADA & SEJAJAR --}}
                <button
                    onclick="openModal({{ $k->id }})"
                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-2 rounded-lg text-sm h-full"
                >
                    + Tambah Spek
                </button>

            </div>

            @foreach($k->jenisSpek as $s)
                <div 
                    id="spek-content-{{ $k->id }}-{{ $s->id }}" 
                    class="hidden mt-6"
                >
                    @if(optional($s->detail)->count())
                        <div class="overflow-x-auto">
                            <table class="w-full border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2">No</th>
                                        <th class="border p-2">Nama Detail</th>
                                        <th class="border p-2">Gambar</th>
                                        <th class="border p-2">Jenis Order</th>
                                        <th class="border p-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($s->detail as $i => $d)
                                        <tr>
                                            <td class="border p-2">{{ $i + 1 }}</td>
                                            <td class="border p-2">{{ $d->nama_jenis_spek_detail }}</td>
                                            <td class="border p-2 text-center">
                                                @if($d->gambar)
                                                    <img src="{{ asset('storage/' . $d->gambar) }}" alt="Gambar" class="w-12 h-12 object-cover rounded block m-auto">
                                                @else
                                                    <span class="text-gray-400 italic text-sm">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td class="border p-2">
                                                @if($d->jenisOrder->count())
                                                    <div class="flex flex-wrap justify-center gap-1">
                                                        @foreach($d->jenisOrder as $jo)
                                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                                                {{ $jo->nama_jenis }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic text-sm">-</span>
                                                @endif
                                            </td>
                                            <td class="border p-2 text-center">
                                                <button
                                                    onclick="openDetailModal({{ $s->id }}, {{ $d->id }}, '{{ $d->nama_jenis_spek_detail }}', {{ $d->jenisOrder->pluck('id')->toJson() }})"
                                                    class="text-blue-500 hover:text-blue-700 text-sm font-medium"
                                                >
                                                    Edit
                                                </button>
                                                <form 
                                                    action="{{ url('/jenis-spek-detail/' . $d->id) }}" 
                                                    method="POST" 
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Yakin ingin menghapus?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium ml-2">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button
                                onclick="openDetailModal({{ $s->id }}, null, '')"
                                class="mt-3 w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm"
                            >
                                + Tambah Detail
                            </button>
                        </div>
                    @else
                        <div class="text-gray-500 italic mb-3">
                            Belum ada detail jenis spek
                        </div>
                        <button
                            onclick="openDetailModal({{ $s->id }}, null, '')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm"
                        >
                            + Tambah Detail
                        </button>
                    @endif
                </div>
            @endforeach


        </div>
    @endforeach


</div>


<!-- MODAL JENIS SPEK -->
<div 
    id="modalJenisSpek" 
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-lg">

        <h2 class="text-xl font-bold mb-4">Tambah Jenis Spek</h2>

        <form action="{{ route('jenis_spek.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_kategori_jenis_order" id="kategori_id_input">

            <div class="mb-4">
                <label class="block mb-2 font-medium">Nama Jenis Spek</label>
                <input
                    type="text"
                    name="nama_jenis_spek"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring"
                    placeholder="Contoh: Ukuran, Warna, Bahan..."
                    required>
            </div>

            <div class="flex justify-end gap-2">
                <button 
                    type="button"
                    onclick="closeModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
                >
                    Batal
                </button>

                <button 
                    type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL JENIS SPEK DETAIL -->
<div 
    id="modalJenisSpekDetail" 
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-lg">

        <h2 class="text-xl font-bold mb-4" id="modalDetailTitle">Tambah Jenis Spek Detail</h2>

        <form id="formDetailSpek" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_jenis_spek" id="detail_spek_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_id_input">
            <input type="hidden" name="_method" id="detail_method_input" value="POST">

            <div class="mb-4">
                <label class="block mb-2 font-medium">Nama Detail</label>
                <input
                    type="text"
                    name="nama_jenis_spek_detail"
                    id="detail_nama_input"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring"
                    placeholder="Contoh: S, M, L..."
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Jenis Order</label>
                <div id="jenisOrderCheckboxContainer" class="border p-3 rounded max-h-40 overflow-y-auto">
                    <!-- Checkbox akan diisi oleh JavaScript saat modal dibuka -->
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Gambar (Opsional)</label>
                <input
                    type="file"
                    name="gambar"
                    id="detail_gambar_input"
                    accept="image/*"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring">
            </div>

            <div class="flex justify-end gap-2">
                <button 
                    type="button"
                    onclick="closeDetailModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
                >
                    Batal
                </button>

                <button 
                    type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<script>
// TAB FUNCTION
function showTab(id) {

    // hide all content
    document.querySelectorAll('.kategori-content').forEach(el => {
        el.classList.add('hidden')
    })

    // reset all tab color
    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('bg-blue-600','text-white')
        el.classList.add('bg-gray-200','text-gray-700')
    })

    // show current
    document.getElementById('content-'+id).classList.remove('hidden')

    // active tab
    let tab = document.getElementById('tab-'+id)
    tab.classList.remove('bg-gray-200','text-gray-700')
    tab.classList.add('bg-blue-600','text-white')
}


// MODAL
function openModal(kategoriId){
    document.getElementById('modalJenisSpek').classList.remove('hidden')
    document.getElementById('kategori_id_input').value = kategoriId
}

function closeModal(){
    document.getElementById('modalJenisSpek').classList.add('hidden')
}

// MODAL DETAIL
function openDetailModal(spekId, detailId, detailNama, selectedJenisOrderIds = null){
    const modal = document.getElementById('modalJenisSpekDetail');
    const form = document.getElementById('formDetailSpek');
    const titleEl = document.getElementById('modalDetailTitle');
    const spekInput = document.getElementById('detail_spek_id_input');
    const namaInput = document.getElementById('detail_nama_input');
    const methodInput = document.getElementById('detail_method_input');
    const kategoriInput = document.getElementById('current_kategori_id_input');

    // Dapatkan kategori_id dari element spek yang sedang ditampilkan
    const currentKategoriId = getCurrentActiveKategoriId();
    kategoriInput.value = currentKategoriId;

    spekInput.value = spekId;
    namaInput.value = detailNama;

    // Convert selectedJenisOrderIds ke array jika bukan null
    let selectedIds = [];
    if (selectedJenisOrderIds && Array.isArray(selectedJenisOrderIds)) {
        selectedIds = selectedJenisOrderIds;
    } else if (selectedJenisOrderIds) {
        selectedIds = [selectedJenisOrderIds];
    }

    // Populate jenis_order checkbox berdasarkan kategori
    populateJenisOrderCheckbox(currentKategoriId, selectedIds);

    if (detailId) {
        // Edit mode
        titleEl.textContent = 'Edit Jenis Spek Detail';
        methodInput.value = 'PUT';
        form.action = `/jenis-spek-detail/${detailId}`;
    } else {
        // Create mode
        titleEl.textContent = 'Tambah Jenis Spek Detail';
        methodInput.value = 'POST';
        form.action = '/jenis-spek-detail';
    }

    modal.classList.remove('hidden');
}

function getCurrentActiveKategoriId() {
    // Cari kategori yang sedang ditampilkan
    const activeContent = document.querySelector('.kategori-content:not(.hidden)');
    if (activeContent) {
        const id = activeContent.id.replace('content-', '');
        return id;
    }
    return null;
}

function populateJenisOrderCheckbox(kategoriId, selectedJenisOrderIds = []) {
    const container = document.getElementById('jenisOrderCheckboxContainer');
    container.innerHTML = '';

    // Data jenis_order dari Blade
    const allJenisOrder = @json($jenisOrderList);
    
    // Filter berdasarkan kategori (hanya yang id_kategori_jenis_order-nya sesuai)
    const filteredJenisOrder = allJenisOrder.filter(jo => jo.id_kategori_jenis_order == kategoriId);

    if (filteredJenisOrder.length === 0) {
        container.innerHTML = '<p class="text-gray-400 italic text-sm">Tidak ada jenis order untuk kategori ini</p>';
        return;
    }

    filteredJenisOrder.forEach(jo => {
        const div = document.createElement('div');
        div.className = 'flex items-center mb-2';
        
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
        label.className = 'ml-2 cursor-pointer text-sm';
        label.textContent = jo.nama_jenis;
        
        div.appendChild(checkbox);
        div.appendChild(label);
        container.appendChild(div);
    });
}

function closeDetailModal(){
    document.getElementById('modalJenisSpekDetail').classList.add('hidden');
}

// default buka tab pertama
@foreach($kategori as $index => $k)
    @if($index == 0)
        showTab({{ $k->id }})
    @endif
    @if($k->jenisSpek->count() > 0)
        const firstSpekId{{ $k->id }} = {{ $k->jenisSpek->first()->id }};
        document.getElementById(`spek-content-{{ $k->id }}-${firstSpekId{{ $k->id }}}`).classList.remove('hidden');
    @endif
@endforeach
</script>

<script>
function showSpekDetail(kategoriId, spekId) {

    // Sembunyikan semua detail di kategori ini
    document.querySelectorAll(`#content-${kategoriId} [id^="spek-content-${kategoriId}-"]`)
        .forEach(el => el.classList.add('hidden'));

    // reset semua tombol spek
    document.querySelectorAll(`#content-${kategoriId} [id^="spek-tab-"]`)
        .forEach(el => {
            el.classList.remove('bg-blue-600','text-white');
            el.classList.add('bg-white');
        });

    // munculkan yang dipilih
    const content = document.getElementById(`spek-content-${kategoriId}-${spekId}`);
    if (content) content.classList.remove('hidden');

    // aktifkan tabnya
    const tab = document.getElementById(`spek-tab-${spekId}`);
    if (tab) {
        tab.classList.remove('bg-white');
        tab.classList.add('bg-blue-600','text-white');
    }
}
</script>


@endsection
