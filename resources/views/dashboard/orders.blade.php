@extends('layouts.dashboard')

@section('title', 'Pesanan')

@section('content')
    <div class="px-4 pt-6 flex flex-col justify-start mt-24 items-start">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" class="w-full absolute z-0 -top-10 left-0" alt="Logo">
        <div class="flex justify-end gap-3 z-10 relative">
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
                    <th class="border border-gray-600 px-2 py-2 text-center"><div class="text-xs">Aksi</div></th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                @foreach ($orders as $o)
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}">
                        <td class="px-2 py-2 font-semibold whitespace-nowrap"><div class="text-xs"><a href="{{ url('/dashboard/orders-detail/' . $o->slug) }}">{{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }} </a></div></td>
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

    <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg py-3 p-6">

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
            <!-- SIZE INPUTS -->
            <div class="grid grid-cols-3 md:grid-cols-11 gap-2 mt-4 mb-2">
                <div>
                    <label class="block text-xs">XS</label>
                    <input type="number" name="xs" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">S</label>
                    <input type="number" name="s" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">M</label>
                    <input type="number" name="m" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">L</label>
                    <input type="number" name="l" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">XL</label>
                    <input type="number" name="xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">2XL</label>
                    <input type="number" name="2xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">3XL</label>
                    <input type="number" name="3xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">4XL</label>
                    <input type="number" name="4xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">5XL</label>
                    <input type="number" name="5xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">6XL</label>
                    <input type="number" name="6xl" value="0" oninput="hitungQty()"
                        class="w-full border text-xs rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-xs">QTY</label>
                    <input type="number" 
                        name="qty"
                        id="qty"
                        readonly
                        value="0"
                        class="w-full border bg-gray-100 text-xs rounded px-2 py-2"
                        placeholder="Otomatis dari size">
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium mb-1">Keterangan</label>
                <div class="grid grid-cols-4 gap-3 mb-3" id="keteranganContainer" style="display: none;">
                    <!-- Akan di-populate oleh JavaScript -->
                </div>
                <input type="text" name="keterangan" id="keteranganInput" class="w-full border text-xs rounded px-2 py-2" placeholder="Pilih keterangan dari kotak hijau..." readonly>
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
function hitungQty() {
    const sizes = [
        'xs', 's', 'm', 'l', 'xl',
        '2xl', '3xl', '4xl', '5xl', '6xl'
    ];

    let total = 0;

    sizes.forEach(function(size) {
        const input = document.querySelector(`[name="${size}"]`);
        const value = parseInt(input.value) || 0;

        // Jika minus, paksa jadi 0
        if (value < 0) {
            input.value = 0;
            total += 0;
        } else {
            total += value;
        }
    });

    document.getElementById('qty').value = total;
}
</script>

<script>
    function openKategori(id) {
        // sembunyikan semua kategori
        document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

        // tampilkan kategori yang dipilih
        document.getElementById('kategori-' + id).classList.remove('hidden');

        // ubah style tab
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-gray-800', 'text-white'));
        event.target.classList.add('bg-gray-800', 'text-white');

        // Tampilkan keterangan box berdasarkan kategori yang dipilih
        // Jika ada jenis_order yang sedang dipilih di dalam kategori ini, gunakan filter berdasarkan jenis_order
        const kategoriEl = document.getElementById('kategori-' + id);
        const checkedJenis = kategoriEl.querySelector('.js-pakaian-input:checked');
        if (checkedJenis && typeof updateKeteranganByJenisOrder === 'function') {
            updateKeteranganByJenisOrder(checkedJenis.value);
        } else {
            displayKeteranganByKategori(id);
        }
    }

    // Tidak auto-open tab pertama: biarkan user memilih kategori secara eksplisit
    // (keterangan akan tampil hanya setelah user klik kategori)
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Data dari Blade
    const allJenisSpek = @json($jenisSpek);
    const allJenisSpekDetail = @json($jenisSpekDetail);
    const allJenisOrders = @json($jenisOrders);

    // Ambil semua jenis_order inputs & labels
    const pakaianInputs = document.querySelectorAll('.js-pakaian-input');
    const pakaianLabels = document.querySelectorAll('.js-pakaian-label');

    // Event listener pada setiap jenis_order radio (untuk styling dan filter keterangan)
    pakaianInputs.forEach(input => {
        input.addEventListener('change', function () {
            // Reset semua label
            pakaianLabels.forEach(label => {
                label.classList.remove('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
                label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
            });

            // Label aktif
            const activeLabel = document.querySelector(`label[for="${this.id}"]`);
            activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
            activeLabel.classList.add('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');

            // Update keterangan berdasarkan jenis_order yang dipilih
            updateKeteranganByJenisOrder(this.value);
        });
    });

    // Function untuk filter dan update keterangan berdasarkan jenis_order yang dipilih
    window.updateKeteranganByJenisOrder = function(jenisOrderId) {
        const container = document.getElementById('keteranganContainer');
        const keteranganInput = document.getElementById('keteranganInput');
        const currentKategoriTab = document.querySelector('.tab-btn.bg-gray-800');
        if (!currentKategoriTab) return;

        const kategoriId = currentKategoriTab.getAttribute('onclick').match(/\d+/)[0];

        // Filter jenis_spek berdasarkan kategori
        const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

        // Filter jenis_spek_detail HANYA yang punya relasi dengan jenis_order yang dipilih
        const filteredDetails = allJenisSpekDetail.filter(detail => {
            // Cek apakah detail ada di kategori ini
            if (!jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek)) {
                return false;
            }
            // Cek apakah detail punya relasi dengan jenis_order yang dipilih
            return detail.jenis_order && detail.jenis_order.some(jo => jo.id == jenisOrderId);
        });

        if (filteredDetails.length === 0) {
            container.style.display = 'none';
            if (keteranganInput) keteranganInput.value = '';
            return;
        }

        // Tampilkan container
        container.style.display = 'grid';
        container.innerHTML = '';

        // Kelompokkan detail berdasarkan id_jenis_spek
        const groupedBySpek = {};
        filteredDetails.forEach(detail => {
            const spekId = detail.id_jenis_spek;
            if (!groupedBySpek[spekId]) {
                groupedBySpek[spekId] = [];
            }
            groupedBySpek[spekId].push(detail);
        });

        // Cari nama dan info jenis_spek dari allJenisSpek
        const spekMap = {};
        allJenisSpek.forEach(spek => {
            spekMap[spek.id] = spek;
        });

        // Generate kotak untuk setiap jenis_spek
        Object.keys(groupedBySpek).forEach((spekId) => {
            const spek = spekMap[spekId];
            const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
            const details = groupedBySpek[spekId];

            // Buat div kotak hijau
            const box = document.createElement('div');
            box.dataset.spekId = spekId; // simpan id spek pada box untuk aggregasi
            box.className = 'bg-[#00FF00] rounded-3xl py-2 px-2 mb-4';

            // Tambah title spek
            const title = document.createElement('p');
            title.className = 'text-[10px] font-semibold mt-0 mb-2 text-center text-black rounded py-1';
            title.textContent = spekName;
            box.appendChild(title);

            // Buat grid untuk detail
            const detailGrid = document.createElement('div');
            detailGrid.className = 'grid grid-cols-3 gap-1';

            // Tambahkan setiap detail ke grid
                details.forEach((detail, index) => {
                const radioId = `detail-${detail.id}`;

                // Hidden radio input (grouped per jenis_spek so user can pick one per spek)
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.id = radioId;
                // submit as speks[<jenis_spek_id>] => <jenis_spek_detail_id>
                radio.name = `speks[${detail.id_jenis_spek}]`;
                radio.value = detail.id; // store detail id for persistence
                radio.dataset.label = detail.nama_jenis_spek_detail; // keep human label for keterangan
                radio.className = 'hidden';

                // Button/Label (visual)
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'flex flex-col items-center w-full rounded cursor-pointer transition hover:opacity-80';
                
                // Konten button
                if (detail.gambar) {
                    const img = document.createElement('img');
                    img.src = '/storage/' + detail.gambar;
                    img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                    button.appendChild(img);
                } else {
                    const noImg = document.createElement('div');
                    noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                    noImg.textContent = 'No Img';
                    button.appendChild(noImg);
                }

                const span = document.createElement('span');
                span.className = 'text-[7px] bg-red text-center font-regular bg-[#009A00] text-white px-1 rounded-sm';
                span.textContent = detail.nama_jenis_spek_detail;
                button.appendChild(span);

                // Event listener pada button
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    radio.checked = true;

                    // Update styling
                    detailGrid.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-green-700');
                    });
                    button.classList.add('ring-2', 'ring-green-700');

                    // Update aggregated keterangan per spek
                    updateKeteranganField();
                });

                detailGrid.appendChild(radio);
                detailGrid.appendChild(button);

                // Tidak auto-select: biarkan user memilih detail secara manual
            });

            box.appendChild(detailGrid);
            container.appendChild(box);
        });
    }

    // Kumpulkan pilihan detail dari setiap kotak (per jenis_spek) dan tulis ke input keterangan
    window.updateKeteranganField = function() {
        const container = document.getElementById('keteranganContainer');
        const keteranganInput = document.getElementById('keteranganInput');
        if (!container || !keteranganInput) return;

        const boxes = Array.from(container.querySelectorAll('div[data-spek-id]'));
        const pairs = [];

        boxes.forEach(box => {
            const spekId = box.dataset.spekId;
            const spekTitleEl = box.querySelector('p');
            const spekName = spekTitleEl ? spekTitleEl.textContent.trim() : `Spek ${spekId}`;
            const checked = box.querySelector(`input[name="speks[${spekId}]"]:checked`);
            if (checked) {
                const label = checked.dataset.label || checked.value;
                pairs.push(`${spekName}: ${label}`);
            }
        });

        keteranganInput.value = pairs.join(' | ');
    }

    // Function untuk menampilkan keterangan berdasarkan kategori (tanpa filter jenis_order)
    window.displayKeteranganByKategori = function(kategoriId) {
        const container = document.getElementById('keteranganContainer');
        const keteranganInput = document.getElementById('keteranganInput');

        // Filter jenis_spek berdasarkan kategori_jenis_order
        const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

        // Filter jenis_spek_detail yang ada di kategori ini
        const filteredDetails = allJenisSpekDetail.filter(detail => {
            return jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek);
        });

        if (filteredDetails.length === 0) {
            container.style.display = 'none';
            if (keteranganInput) keteranganInput.value = '';
            return;
        }

        // Tampilkan container
        container.style.display = 'grid';
        container.innerHTML = '';

        // Kelompokkan detail berdasarkan id_jenis_spek
        const groupedBySpek = {};
        filteredDetails.forEach(detail => {
            const spekId = detail.id_jenis_spek;
            if (!groupedBySpek[spekId]) {
                groupedBySpek[spekId] = [];
            }
            groupedBySpek[spekId].push(detail);
        });

        // Cari nama dan info jenis_spek dari allJenisSpek
        const spekMap = {};
        allJenisSpek.forEach(spek => {
            spekMap[spek.id] = spek;
        });

        // Generate kotak untuk setiap jenis_spek
        Object.keys(groupedBySpek).forEach((spekId) => {
            const spek = spekMap[spekId];
            const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
            const details = groupedBySpek[spekId];

            // Buat div kotak hijau
            const box = document.createElement('div');
            box.dataset.spekId = spekId; // simpan id spek juga di sini
            box.className = 'bg-[#00FF00] rounded-3xl py-2 px-2 mb-4';

            // Tambah title spek
            const title = document.createElement('p');
            title.className = 'text-[10px] font-semibold mt-0 mb-2 text-center text-black rounded py-1';
            title.textContent = spekName;
            box.appendChild(title);

            // Buat grid untuk detail
            const detailGrid = document.createElement('div');
            detailGrid.className = 'grid grid-cols-3 gap-1';

            // Tambahkan setiap detail ke grid
                details.forEach((detail, index) => {
                const radioId = `detail-${detail.id}`;

                // Hidden radio input (grouped per jenis_spek)
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.id = radioId;
                radio.name = `speks[${detail.id_jenis_spek}]`;
                radio.value = detail.id;
                radio.dataset.label = detail.nama_jenis_spek_detail;
                radio.className = 'hidden';

                // Button/Label (visual)
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'flex flex-col items-center w-full rounded cursor-pointer transition hover:opacity-80';
                
                // Konten button
                if (detail.gambar) {
                    const img = document.createElement('img');
                    img.src = '/storage/' + detail.gambar;
                    img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                    button.appendChild(img);
                } else {
                    const noImg = document.createElement('div');
                    noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                    noImg.textContent = 'No Img';
                    button.appendChild(noImg);
                }

                const span = document.createElement('span');
                span.className = 'text-[7px] text-center font-regular bg-[#009A00] text-white px-1 rounded-sm';
                span.textContent = detail.nama_jenis_spek_detail;
                button.appendChild(span);

                // Event listener pada button
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    radio.checked = true;

                    // Update styling
                    detailGrid.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-green-700');
                    });
                    button.classList.add('ring-2', 'ring-green-700');

                    // Update aggregated keterangan per spek
                    updateKeteranganField();
                });

                detailGrid.appendChild(radio);
                detailGrid.appendChild(button);

                // Tidak auto-select: biarkan user memilih detail secara manual
            });

            box.appendChild(detailGrid);
            container.appendChild(box);
        });
    }

});
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