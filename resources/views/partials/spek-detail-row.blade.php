<tr class="{{ $loop->even ?? false ? 'bg-gray-200' : 'bg-white' }}">
    <td><div class="text-center">{{ $index + 1 }}</div></td>
    <td>{{ $detail->nama_jenis_spek_detail }}</td>
    <td><div class="text-center table_img">
        @if($detail->gambar)
            <img src="{{ asset('storage/' . $detail->gambar) }}" alt="Gambar" class="max-h-16 mx-auto">
        @else
            <span>Tidak ada</span>
        @endif
        </div>
    </td>
    <td>
        @if($detail->jenisOrder->count())
            <div class="jenis_flex">
                @foreach($detail->jenisOrder as $jo)
                    <span class="bg-gray-800 text-white" style="font-weight:300;">
                        {{ $jo->nama_jenis }}
                    </span>
                @endforeach
            </div>
        @else
            <span class="text-gray-800">-</span>
        @endif
    </td>
    <td>
        <div class="btn_table_action">
            <button
                onclick="openDetailModal({{ $detail->id_jenis_spek }}, {{ $detail->id }}, '{{ addslashes($detail->nama_jenis_spek_detail) }}', {{ $detail->jenisOrder->pluck('id')->toJson() }})"
                class="bg-blue-500"
            >
                Edit
            </button>
            <button 
                onclick="confirmDelete({{ $detail->id }}, {{ $detail->id_jenis_spek }})"
                class="bg-red"
            >
                Hapus
            </button>
        </div>
    </td>
</tr>