@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Group Order: {{ $groupOrder->kode_group }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('group-orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Kembali
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Print
                </button>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-600">
            <p>Tanggal: {{ $groupOrder->created_at->format('d/m/Y H:i') }}</p>
            <p>Pelanggan: {{ $groupOrder->pelanggan->nama }}</p>
            @if($groupOrder->affiliate)
            <p>Sales: {{ $groupOrder->affiliate->nama }} ({{ $groupOrder->affiliate->kode }})</p>
            @endif
        </div>
    </div>

    <!-- Info Group Order -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Group Order</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Total Harga Group</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalHargaGroup) }}</p>
            </div>
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Jumlah Order</p>
                <p class="text-xl font-bold text-blue-600">{{ $groupOrder->orders->count() }}</p>
            </div>
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Status Pembayaran</p>
                <p class="text-xl font-bold {{ $groupOrder->payment_status ? 'text-green-600' : 'text-orange-600' }}">
                    {{ $groupOrder->payment_status ? 'LUNAS' : 'BELUM LUNAS' }}
                </p>
            </div>
        </div>
        
        <!-- Info Nama Job -->
        @if(count($semuaNamaJob) > 0)
        <div class="mt-4">
            <p class="text-sm text-gray-500 mb-2">Nama Job:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($semuaNamaJob as $job)
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    {{ $job }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Detail Pembayaran -->
    @if($groupOrder->pembayaran)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Detail Pembayaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">DP</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($groupOrder->pembayaran->dp) }}</p>
            </div>
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Harus Dibayar</p>
                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($groupOrder->pembayaran->harus_dibayar) }}</p>
            </div>
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Sisa Bayar</p>
                <p class="text-xl font-bold {{ $groupOrder->pembayaran->sisa_bayar > 0 ? 'text-orange-600' : 'text-green-600' }}">
                    Rp {{ number_format($groupOrder->pembayaran->sisa_bayar) }}
                </p>
            </div>
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-xl font-bold {{ $groupOrder->pembayaran->status ? 'text-green-600' : 'text-orange-600' }}">
                    {{ $groupOrder->pembayaran->status ? 'LUNAS' : 'BELUM LUNAS' }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Order dalam Group -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Daftar Order ({{ $groupOrder->orders->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($groupOrder->orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ optional($order->jenisOrder)->nama_jenis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($order->harga_jual_satuan) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">Rp {{ number_format($order->harga_jual_total) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $order->status ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $order->status ? 'Selesai' : 'Proses' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-semibold">TOTAL:</td>
                        <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($totalHargaGroup) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection