@extends('layouts.dashboard')

@section('title', 'Pesanan Detail')

@section('content')
<div class="px-6 pt-7">
    <div class="flex justify-between items-end mb-4">
        <h1 class="text-3xl font-bold">
            Jenis Job : {{ $order->nama_job }}
        </h1>
        <!-- DROPDOWN PILIH JOB LAIN -->
        <div class="flex gap-2 items-center">
            <label class="text-sm font-semibold mb-1 block">Pilih Job Lain</label>

            <select 
                class="w-full md:w-64 border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                onchange="window.location.href='/dashboard/orders-detail/' + this.value"
            >

                @foreach($orders as $item)
                    <option 
                        value="{{ $item->slug }}" 
                        {{ $item->id == $order->id ? 'selected' : '' }}
                    >
                        {{ $item->nama_job }} ({{ $item->qty }})
                    </option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-xl font-semibold">Quantity</h2>
            <p class="text-3xl font-bold mt-2">{{ $order->qty }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-xl font-semibold">Hari</h2>
            <p class="text-3xl font-bold mt-2">{{ $order->hari }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-xl font-semibold">Deadline</h2>
            <p class="text-3xl font-bold mt-2">{{ $order->deadline }}</p>
        </div>

    </div>
    
    <!-- Ukuran yang dipilih -->
    <div class="mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-3">Ukuran</h2>

        @php
            $size = $order->size;
            $sizeItems = $size ? [
                'XS' => $size->xs ?? 0,
                'S'  => $size->s ?? 0,
                'M'  => $size->m ?? 0,
                'L'  => $size->l ?? 0,
                'XL' => $size->xl ?? 0,
                '2XL' => $size->{'2xl'} ?? 0,
                '3XL' => $size->{'3xl'} ?? 0,
                '4XL' => $size->{'4xl'} ?? 0,
                '5XL' => $size->{'5xl'} ?? 0,
                '6XL' => $size->{'6xl'} ?? 0,
            ] : [];
        @endphp

        @if(empty($sizeItems))
            <p class="text-sm text-gray-600">Belum ada data ukuran untuk order ini.</p>
        @else
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                @foreach($sizeItems as $label => $value)
                    <div class="p-3 border rounded text-center">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="text-lg font-bold">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-6">

        <!-- Total Setting -->
        <div class="p-6 rounded-lg shadow bg-white text-center">
            <h2 class="text-xl font-semibold">Setting</h2>
            <p class="text-4xl font-bold mt-2">
                11
            </p>
        </div>


        <!-- Total Print -->
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-red-500 text-center relative">
            <button onclick="openModal()" class="bg-red-500 flex items-center rounded-xl justify-center text-white absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
            <h2 class="text-xl font-semibold">Print</h2>
            <p class="text-4xl font-bold mt-2">
                0
            </p>
            <span class="bg-red-500 text-white mt-2 block p-1 rounded-lg">Sisa : {{ $order->sisa_print }}</span>
            <h5 class="mt-2">M. Rafli</h5>
        </div>

        <!-- Total Press -->
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-green-500 text-center relative">
            <button class="bg-green-500 flex items-center rounded-xl justify-center text-white absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
            <h2 class="text-xl font-semibold">Press</h2>
            <p class="text-4xl font-bold mt-2">
                0
            </p>
            <span class="bg-green-500 text-white mt-2 block p-1 rounded-lg">Sisa : {{ $order->sisa_press }}</span>
        </div>

        <!-- Total Cutting -->
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-500 text-center relative">
            <button class="bg-yellow-500 flex items-center rounded-xl justify-center text-white absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
            <h2 class="text-xl font-semibold">Cutting</h2>
            <p class="text-4xl font-bold mt-2">
                0
            </p>
            <span class="bg-yellow-500 text-white mt-2 block p-1 rounded-lg">Sisa : {{ $order->sisa_cutting }}</span>
        </div>

        <!-- Total Cutting -->
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-blue-400 text-center relative">
            <button class="bg-blue-500 flex items-center rounded-xl justify-center text-white absolute right-2 text-xl top-2"><iconify-icon icon="mynaui:plus-solid"></iconify-icon></button>
            <h2 class="text-xl font-semibold">Jahit</h2>
            <p class="text-4xl font-bold mt-2">
                0
            </p>
            <span class="bg-blue-400 text-white mt-2 block p-1 rounded-lg">Sisa : {{ $order->sisa_jahit }}</span>
        </div>

    </div>
    
    <!-- Spesifikasi terpilih -->
    <div class="mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-3">Spesifikasi Terpilih</h2>

        @php
            // Group by jenis_spek id to ensure one line per spek
            $groupedSpek = $order->spesifikasi->groupBy(function($s) {
                return $s->jenis_spek_id;
            });
        @endphp

        @if($groupedSpek->isEmpty())
            <p class="text-sm text-gray-600">Belum ada spesifikasi yang dipilih untuk order ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($groupedSpek as $spekId => $items)
                    @php
                        $first = $items->first();
                        $spekName = optional($first->jenisSpek)->nama_jenis_spek ?? ('Spek ' . $spekId);
                        $detailName = optional($first->jenisSpekDetail)->nama_jenis_spek_detail ?? '-';
                    @endphp

                    <div class="p-3 border rounded flex items-center gap-3">
                        @php $img = optional($first->jenisSpekDetail)->gambar; @endphp
                        <div class="w-16 h-16 flex-shrink-0">
                            @if($img)
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $detailName }}" class="w-16 h-16 object-cover rounded" />
                            @else
                                <div class="w-16 h-16 bg-gray-100 flex items-center justify-center text-xs text-gray-500 rounded">No Image</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">{{ $spekName }}</div>
                            <div class="text-lg font-bold">{{ $detailName }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<div class="px-6 py-6">
    <button id="toggleProgress" class="ml-auto block mb-4 px-4 py-2 bg-blue-600 text-white rounded">
        Tampilkan Progress Keseluruhan
    </button>
    <div class="progress_lainnya hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold">Total Quantity</h2>
                <p class="text-3xl font-bold mt-2">{{ $totals->total_qty }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold">Total Hari</h2>
                <p class="text-3xl font-bold mt-2">{{ $totals->total_hari }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold">Sisa Hari</h2>
                <p class="text-3xl font-bold mt-2">{{ $totals->total_deadline }}</p>
            </div>

        </div>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Setting -->
            <div class="bg-white p-6 rounded-lg shadow border-b-4 border-silver-500">
                <h2 class="text-xl font-semibold">Setting</h2>
                <p class="text-3xl font-bold mt-2">
                    {{ $totals->total_setting }} <span class="text-base font-normal">sisa 0</span>
                </p>
            </div>

            <!-- Total Print -->
            <div class="bg-white p-6 rounded-lg shadow border-b-4 border-red-500">
                <h2 class="text-xl font-semibold">Total Print</h2>
                <p class="text-3xl font-bold mt-2">
                    {{ $totals->total_print }} <span class="text-base font-normal">sisa {{ $totals->total_sisa_print }}</span>
                </p>
            </div>


            <!-- Total Press -->
            <div class="bg-white p-6 rounded-lg shadow border-b-4 border-green-500">
                <h2 class="text-xl font-semibold">Total Press</h2>
                <p class="text-3xl font-bold mt-2">
                    {{ $totals->total_press }} <span class="text-base font-normal">sisa {{ $totals->total_sisa_press }}</span>
                </p>

            </div>

            <!-- Total Cutting -->
            <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-500">
                <h2 class="text-xl font-semibold">Total Cutting</h2>
                <p class="text-3xl font-bold mt-2">
                    {{ $totals->total_cutting }} <span class="text-base font-normal">sisa {{ $totals->total_sisa_cutting }}</span>
                </p>

            </div>

            <!-- Total Jahit -->
            <div class="bg-white p-6 rounded-lg shadow border-b-4 border-gray-500">
                <h2 class="text-xl font-semibold">Total Jahit</h2>
                <p class="text-3xl font-bold mt-2">
                    {{ $totals->total_jahit }} <span class="text-base font-normal">sisa {{ $totals->total_sisa_jahit }}</span>
                </p>
            </div>

        </div>
    </div>
</div>

<div id="popupModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah Progress Baru</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-black text-2xl"><iconify-icon icon="iconamoon:close-duotone"></iconify-icon></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <!-- Nama Printing -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Printing</label>
                <input type="text" 
                    name="nama_job"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Pilih Jenis Printing"
                    required>
            </div>

            <!-- Progress -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jumlah Progress</label>
                <input type="number" 
                    name="qty"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Masukkan jumlah Progress"
                    required>
            </div>

            <!-- Qty -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Pekerja</label>
                <input type="number" 
                    name="qty"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" 
                    placeholder="Pilih Nama Pekerja"
                    required>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded text-sm text-white bg-red-500 hover:bg-red-700">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded text-sm bg-green-600 text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    document.getElementById('toggleProgress').addEventListener('click', function () {
        const progress = document.querySelector('.progress_lainnya');

        progress.classList.toggle('hidden');

        // Update text tombol
        if (!progress.classList.contains('hidden')) {
            this.textContent = 'Sembunyikan Progress Keseluruhan';
        } else {
            this.textContent = 'Tampilkan Progress Keseluruhan';
        }
    });
</script>
<script>
    function openModal() {
        document.getElementById('popupModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('popupModal').classList.add('hidden');
    }
</script>


@endsection
