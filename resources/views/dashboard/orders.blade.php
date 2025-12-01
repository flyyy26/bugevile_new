@extends('layouts.dashboard')

@section('title', 'Pesanan')

@section('content')
    <div class="px-4 pt-6 flex flex-col justify-start mt-24 items-start">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">
        <div class="flex justify-end gap-3 z-10 relative">
            <a href="/dashboard/orders/setting" class="p-0 m-0"><button class="bg-white text-gray-800 font-bold px-4 py-1 rounded-lg text-sm shadow">Setting Jenis</button></a>
            <button onclick="openModal()" class="bg-white text-gray-800 font-bold px-4 py-1 rounded-lg text-sm shadow">Tambah Job</button>
        </div>
    </div>

    <div class="overflow-x-auto z-10 relative mt-5">
        <table class="min-w-full border border-gray-300 text-sm">
            
            <!-- HEADER -->
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="w-48 text-xs">Jenis Job</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="w-32 text-xs">Nama Konsumen</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="w-16 text-xs">Tanggal</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Qty</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Hari</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Deadline</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Setting</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-red-500 text-center"><div class="text-xs">Print</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-red-500 text-center"><div class="text-center">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-green-500 text-center"><div class="text-xs">Press</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-green-500 text-center"><div class="text-xs">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-yellow-500 text-center"><div class="text-xs">Cutting</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-yellow-500 text-center"><div class="text-xs">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-blue-300 text-center"><div class="text-xs">Jahit</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-blue-300 text-center"><div class="text-xs">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-yellow-500 text-center"><div class="text-xs">Finishing</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-yellow-500 text-center"><div class="text-xs">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 bg-yellow-500 text-center"><div class="text-xs">Packing</div></th>
                    <th class="border border-gray-600 px-0 py-2 bg-yellow-500 text-center"><div class="text-xs">S</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Est</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Keterangan</div></th>
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Aksi</div></th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                @foreach ($orders as $o)
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                        <td class="px-2 py-2 font-semibold whitespace-nowrap"><div class="text-xs">{{ $o->nama_job }} {{ $o->jenisOrder->nama_jenis }} </div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->nama_konsumen }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->created_at->format('Y-m-d') }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->qty }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ (float) $o->hari }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ (float) $o->deadline }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2">
                            <div class="text-xl">
                                <iconify-icon 
                                    icon="{{ $o->setting == 1 ? 'ion:checkmark' : 'ion:close' }}" 
                                    class="{{ $o->setting == 1 ? 'text-green-600' : 'text-red-600' }}">
                                </iconify-icon>
                            </div>
                        </td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->print }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-center text-xs">{{ $o->sisa_print }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->press }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->sisa_press }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->cutting }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->sisa_cutting }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->jahit }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->sisa_jahit }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->finishing }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->sisa_finishing }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->packing }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->sisa_packing }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2"><div class="text-xs">{{ $o->est }}</div></td>
                        <td class="text-start whitespace-nowrap px-0 py-2 text-xs"><div class="text-xs w-48">{{ $o->keterangan }}</div></td>
                        <td class="text-center whitespace-nowrap px-0 py-2">
                            <button class="text-xl text-red-500 btn-delete" data-id="{{ $o->id }}">
                                <iconify-icon icon="ion:trash"></iconify-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-300 font-bold">
                    <td class="border border-gray-300 px-4 py-2 text-center" colspan="3">TOTAL KESELURUHAN</td>
                    
                    <td id="total_qty" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_qty }}
                    </td>
                    <td id="total_hari" class="border border-gray-300 px-4 py-2 text-center">
                        {{ (float) $totals->total_hari }}
                    </td>
                    <td id="total_deadline" class="border border-gray-300 px-4 py-2 text-center">
                        {{ (float) $totals->total_deadline }}
                    </td>
                    <td id="total_setting" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_setting }}
                    </td>
                    <td id="total_print" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_print }}
                    </td>
                    <td id="total_sisa_print" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_print }}
                    </td>
                    <td id="total_press" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_press }}
                    </td>
                    <td id="total_sisa_press" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_press }}
                    </td>
                    <td id="total_cutting" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_cutting }}
                    </td>
                    <td id="total_sisa_cutting" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_cutting }}
                    </td>
                    <td id="total_jahit" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_jahit }}
                    </td>
                    <td id="total_sisa_jahit" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_jahit }}
                    </td>
                    <td id="total_finishing" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_finishing }}
                    </td>
                    <td id="total_sisa_finishing" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_finishing }}
                    </td>
                    <td id="total_packing" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_packing }}
                    </td>
                    <td id="total_sisa_packing" class="border border-gray-300 px-4 py-2 text-center">
                        {{ $totals->total_sisa_packing }}
                    </td>
                    
                    <td id="est" class="border border-gray-300 px-4 py-2 text-center">
                        {{ (float) $totals->total_hari }}
                    </td>
                    
                    <td class="border border-gray-300 px-4 py-2 text-center" colspan="2">
                        </td>
                </tr>
            </tfoot>
        </table>
    </div>

<div id="popupModal" class="hidden popup_custom fixed inset-0 flex bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg py-3 p-6">

        <!-- HEADER -->
        


        <!-- FORM -->
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="mb-3">
                <div class="flex justify-between items-center mb-0">
                    <label class="block text-xs font-medium">Nama Konsumen</label>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
                </div>
                <select id="select2-nama-konsumen" name="nama_konsumen" required>
                    <option value="" selected disabled>Pilih Nama Konsumen...</option>
                    @foreach ($uniqueKonsumens as $konsumenName)
                        <option value="{{ $konsumenName }}">{{ $konsumenName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Job -->
            <div class="mb-3">
                <div class="flex gap-2 border-b pb-2">

                    @foreach ($kategoriList as $kategori)
                        <button type="button"
                            onclick="openKategori({{ $kategori->id }})"
                            class="tab-btn px-3 py-1 text-xs border rounded">
                            {{ $kategori->nama }}
                        </button>
                    @endforeach

                </div>
                <select id="select2-nama-job" name="nama_job" required>
                    <option value="" selected disabled>Pilih Nama Job...</option>
                    @foreach ($jobs as $a)
                        <option value="{{ $a->nama_job }}">{{ $a->nama_job }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">

                @foreach ($kategoriList as $kategori)

                    <!-- WRAPPER PER KATEGORI -->
                    <div class="kategori-wrapper hidden" id="kategori-{{ $kategori->id }}">

                        <div class="grid grid-cols-5 gap-3">

                            @foreach ($jenisOrders->where('id_kategori_jenis_order', $kategori->id) as $jenis)

                                <input 
                                    type="radio" 
                                    id="jenis-{{ $jenis->id }}" 
                                    name="jenis_order_id" 
                                    value="{{ $jenis->id }}" 
                                    class="hidden js-pakaian-input"
                                >

                                <label 
                                    for="jenis-{{ $jenis->id }}"
                                    class="js-pakaian-label bg-gray-100 border border-gray-300 rounded-lg px-2 py-2 text-xs
                                        cursor-pointer text-gray-700 font-medium text-center transition">
                                    {{ $jenis->nama_jenis }}
                                </label>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

            <!-- Qty -->
            <div class="mb-3">
                <label class="block text-xs font-medium mb-1">Jumlah Quantity</label>
                <input type="number" 
                    name="qty"
                    class="w-full border text-xs rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Masukkan jumlah qty"
                    required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium mb-1">Keterangan</label>
                <div class="grid grid-cols-4 gap-3 mb-3">

                    <!-- JENIS BAHAN -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Bahan</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisBahan as $item)
                                <button 
                                    type="button"
                                    data-kategori="bahan"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('bahan', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS POLA -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Pola</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisPola as $item)
                                <button 
                                    type="button"
                                    data-kategori="pola"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('pola', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS KERAH -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Kerah</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisKerah as $item)
                                <button 
                                    type="button"
                                    data-kategori="kerah"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('kerah', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- JENIS JAHITAN -->
                    <div class="bg-[#00FF00] rounded-3xl py-3 px-3">
                        <p class="text-xs font-medium mb-2 text-center">Jenis Jahitan</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($jenisJahitan as $item)
                                <button 
                                    type="button"
                                    data-kategori="jahitan"
                                    data-id="{{ $item->id }}"
                                    onclick="selectSpec('jahitan', '{{ $item->nama }}', '{{ $item->id }}')"
                                    class="spec-btn flex flex-col items-center w-full"
                                >
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-12 h-12 object-cover rounded-full mb-1">
                                    <span class="text-[8px] text-center font-medium bg-[#009A00] text-white px-2 rounded-sm">{{ $item->nama }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="id_jenis_bahan" id="idJenisBahan">
                    <input type="hidden" name="id_jenis_pola" id="idJenisPola">
                    <input type="hidden" name="id_jenis_kerah" id="idJenisKerah">
                    <input type="hidden" name="id_jenis_jahitan" id="idJenisJahitan">

                </div>
                <input type="text" 
                    name="keterangan"
                    id="keteranganField"
                    class="w-full border text-xs rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Pilih Spesifikasi"
                    required>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded text-xs text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded text-xs bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    function openKategori(id) {
        // sembunyikan semua kategori
        document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

        // tampilkan kategori yang dipilih
        document.getElementById('kategori-' + id).classList.remove('hidden');

        // ubah style tab
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-gray-800', 'text-white'));
        event.target.classList.add('bg-gray-800', 'text-white');
    }

    // buka tab pertama otomatis
    document.addEventListener("DOMContentLoaded", () => {
        const firstTab = document.querySelector('.tab-btn');
        if (firstTab) firstTab.click();
    });
</script>

<script>
let selectedSpecs = {
    bahan: null,
    pola: null,
    kerah: null,
    jahitan: null,
};

function selectSpec(kategori, nama, id) {
    selectedSpecs[kategori] = nama;

    // simpan id ke input hidden
    document.getElementById("idJenis" + capitalize(kategori)).value = id;

    updateKeterangan();
    highlightSelectedButton(kategori, id);
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function updateKeterangan() {
    let parts = [];

    if (selectedSpecs.bahan)   parts.push("Bahan: " + selectedSpecs.bahan);
    if (selectedSpecs.pola)    parts.push("Pola: " + selectedSpecs.pola);
    if (selectedSpecs.kerah)   parts.push("Kerah: " + selectedSpecs.kerah);
    if (selectedSpecs.jahitan) parts.push("Jahitan: " + selectedSpecs.jahitan);

    document.getElementById("keteranganField").value = parts.join(" | ");
}

function highlightSelectedButton(kategori, id) {
    // Reset highlight kategori itu
    document.querySelectorAll(`button[data-kategori="${kategori}"]`).forEach(btn => {
        btn.classList.remove("ring-2", "ring-green-800", "rounded-lg");
    });

    // Pilih tombol berdasarkan data-id
    let activeBtn = document.querySelector(
        `button[data-kategori="${kategori}"][data-id="${id}"]`
    );

    if (activeBtn) {
        activeBtn.classList.add("ring-2", "ring-green-800", "rounded-lg");
    }
}

</script>




<script>
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function () {

                const id = this.dataset.id;

                if (!confirm("Yakin ingin menghapus order ini?")) return;

                fetch(`/dashboard/orders/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                })
                .then(res => {
                    if (!res.ok) throw new Error("Gagal menghapus");
                    return res.json();
                })
                .then(data => {
                    // Hapus baris tabel langsung
                    const row = this.closest("tr");
                    row.remove();

                    const t = data.totals || {};
                    if (t.total_qty !== undefined) document.querySelector("#total_qty").innerText = t.total_qty;
                    if (t.total_hari !== undefined) document.querySelector("#total_hari").innerText = t.total_hari;
                    if (t.total_deadline !== undefined) document.querySelector("#total_deadline").innerText = t.total_deadline;
                    if (t.total_setting !== undefined) document.querySelector("#total_setting").innerText = t.total_setting;
                    if (t.total_print !== undefined) document.querySelector("#total_print").innerText = t.total_print;
                    if (t.total_sisa_print !== undefined) document.querySelector("#total_sisa_print").innerText = t.total_sisa_print;
                    if (t.total_press !== undefined) document.querySelector("#total_press").innerText = t.total_press;
                    if (t.total_sisa_press !== undefined) document.querySelector("#total_sisa_press").innerText = t.total_sisa_press;
                    if (t.total_cutting !== undefined) document.querySelector("#total_cutting").innerText = t.total_cutting;
                    if (t.total_sisa_cutting !== undefined) document.querySelector("#total_sisa_cutting").innerText = t.total_sisa_cutting;
                    if (t.total_jahit !== undefined) document.querySelector("#total_jahit").innerText = t.total_jahit;
                    if (t.total_sisa_jahit !== undefined) document.querySelector("#total_sisa_jahit").innerText = t.total_sisa_jahit;
                    if (t.total_finishing !== undefined) document.querySelector("#total_finishing").innerText = t.total_finishing;
                    if (t.total_sisa_finishing !== undefined) document.querySelector("#total_sisa_finishing").innerText = t.total_sisa_finishing;
                    if (t.total_packing !== undefined) document.querySelector("#total_packing").innerText = t.total_packing;
                    if (t.total_sisa_packing !== undefined) document.querySelector("#total_sisa_packing").innerText = t.total_sisa_packing;
                    if (t.total_hari !== undefined) document.querySelector("#est").innerText = t.total_hari;

                    alert(data.message ?? "Order berhasil dihapus!");
                })
                .catch(err => {
                    console.error("Detail error:", err);
                    alert("Terjadi kesalahan!");
                });

            });
        });

    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // Ambil semua input & label
    const inputs = document.querySelectorAll('.js-pakaian-input');
    const labels = document.querySelectorAll('.js-pakaian-label');

    // Tambah event listener pada setiap radio
    inputs.forEach(input => {
        input.addEventListener('change', function () {

            // Reset semua label ke default
            labels.forEach(label => {
                label.classList.remove('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
                label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
            });

            // Label aktif
            const activeLabel = document.querySelector(`label[for="${this.id}"]`);
            activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
            activeLabel.classList.add('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
        });
    });

    // --- Inisialisasi awal: cek radio yang sudah checked ---
    const checkedInput = document.querySelector('.js-pakaian-input:checked');
    if (checkedInput) {
        const activeLabel = document.querySelector(`label[for="${checkedInput.id}"]`);
        activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
        activeLabel.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'border-blue-600');
    }

});
</script>


<script>
    $(document).ready(function() {
        $('#select2-nama-job').select2({
            placeholder: "Ketik atau pilih nama job",
            tags: true, // PENTING: Mengizinkan input nilai baru
            allowClear: true,

            width: '100%'
        });

        $('#select2-nama-konsumen').select2({
            placeholder: "Ketik atau pilih nama konsumen",
            tags: true, // PENTING: Mengizinkan input nilai baru
            allowClear: true,

            width: '100%'
        });
    });
</script>
<!-- SCRIPT OPEN/CLOSE -->
<script>
function openModal() {
    document.getElementById('popupModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('popupModal').classList.add('hidden');
}
</script>

@endsection