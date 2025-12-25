@if(optional($jenisSpek->detail)->count())
    <div class="orders_table_container">
        <table class="w-full border" id="spek-table-{{ $jenisSpek->id }}">
            <thead class="bg-gray-100">
                <tr>
                    <th><div class="text-center">No</div></th>
                    <th><div class="text-center">Nama Detail</div></th>
                    <th><div class="text-center">Gambar</div></th>
                    <th>Kategori</th>
                    <th><div class="text-center">Aksi</div></th>
                </tr>
            </thead>
            <tbody id="spek-table-body-{{ $jenisSpek->id }}">
                @foreach($jenisSpek->detail as $i => $d)
                    <tr class="{{ $loop->even ? 'bg-gray-200' : 'bg-white' }}" id="detail-row-{{ $d->id }}">
                        <td><div class="text-center">{{ $i + 1 }}</div></td>
                        <td>{{ $d->nama_jenis_spek_detail }}</td>
                        <td><div class="text-center table_img">
                            @if($d->gambar)
                                <img src="{{ asset('storage/' . $d->gambar) }}" alt="Gambar" class="max-h-16 mx-auto">
                            @else
                                <span>Tidak ada</span>
                            @endif
                            </div>
                        </td>
                        <td>
                            @if($d->jenisOrder->count())
                                <div class="jenis_flex">
                                    @foreach($d->jenisOrder as $jo)
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
                                    onclick="openDetailModal({{ $jenisSpek->id }}, {{ $d->id }}, '{{ addslashes($d->nama_jenis_spek_detail) }}', {{ $d->jenisOrder->pluck('id')->toJson() }})"
                                    class="bg-blue-500"
                                >
                                    Edit
                                </button>
                                <button 
                                    onclick="confirmDelete({{ $d->id }}, {{ $jenisSpek->id }})"
                                    class="bg-red"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-gray-500 italic mb-3" id="empty-message-{{ $jenisSpek->id }}">
        Belum ada detail jenis spek
    </div>
@endif