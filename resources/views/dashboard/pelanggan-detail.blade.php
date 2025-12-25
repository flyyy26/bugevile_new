@extends('layouts.dashboard')

@section('title', 'Pelanggan Detail')

@section('content')

    <div class="dashboard_banner dashboard_banner_print">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">
        <div class="dashboard_banner_btn">
            <button
                onclick="window.print()"
                class="hidden_print"
            >
                Print Preview
            </button> 
        </div>
    </div>

<div class="pelanggan_detail_container">
    <div class="pelanggan_detail_layout">
        <div class="form_field_normal form_field_no_margin">
            <label for="Nama">Konsumen</label>
            <input type="text" value="{{ $pelanggan->nama}}" disabled class="input_form input_form_big">
        </div>
        <div class="form_field_normal form_field_no_margin">
            <label for="Alamat">Alamat</label>
            <input type="text" value="{{ $pelanggan->alamat ?? 'data belum diatur' }}" disabled class="input_form input_form_big">
        </div>
        <div class="form_field_normal form_field_no_margin">
            <label for="Tgl Masuk">Tgl Masuk</label>
            <input
                type="text"
                value="{{ $pelanggan->created_at
                    ? \Carbon\Carbon::parse($pelanggan->created_at)->locale('id')->translatedFormat('l, d F Y')
                    : 'data belum diatur'
                }}"
                disabled
                class="input_form input_form_big"
            >
        </div>

        <div class="form_field_normal form_field_no_margin">
            <label for="Tgl Selesai">Tgl Selesai</label>
            <input
                type="text"
                value="{{ $pelanggan->updated_at
                    ? \Carbon\Carbon::parse($pelanggan->updated_at)->locale('id')->translatedFormat('l, d F Y')
                    : 'data belum diatur'
                }}"
                disabled
                class="input_form input_form_big"
            >
        </div>

    </div>
    
    @if ($pelanggan->orders->isNotEmpty())
    <div class="pelanggan_jenis_job">
        <div class="pelanggan_jenis_job_nama_layout">
            <h3 class="pelanggan_jenis_job_heading">Jenis Job</h3>
        </div>

        <div class="oi"></div>

        <div class="pelanggan_jenis_job_ukuran_layout">
            <div class="pelanggan_jenis_job_ukuran_box">
                <span class="pelanggan_jenis_job_heading">QTY</span>
            </div>

            @php
                $sizes = ['XS','S','M','L','XL','2XL','3XL','4XL','5XL','6XL'];
            @endphp

            @foreach ($sizes as $size)
                <div class="pelanggan_jenis_job_ukuran_box">
                    <span class="pelanggan_jenis_job_heading">{{ $size }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Loop semua orders dari pelanggan -->
    @forelse ($pelanggan->orders as $order)
    @php
        $sizeData = $order->size;
    @endphp

    <div class="pelanggan_jenis_job">
        <div class="pelanggan_jenis_job_nama_layout">
            <div class="input_form input_form_big">
                <span class="pelanggan_jenis_job_heading">{{ $order->nama_job }} {{ optional($order->jenisOrder)->nama_jenis ?? '' }}</span>
            </div>
        </div>

        <div class="oi"></div>

        <div class="pelanggan_jenis_job_ukuran_layout">
            <div class="pelanggan_jenis_job_ukuran_box">
                <div class="input_form input_form_big">
                    <span class="pelanggan_jenis_job_heading">{{ $order->qty }}</span>
                </div>
            </div>

            @foreach (['xs','s','m','l','xl','2xl','3xl','4xl','5xl','6xl'] as $key)
            <div class="pelanggan_jenis_job_ukuran_box">
                <div class="input_form input_form_big">
                    <span class="pelanggan_jenis_job_heading">{{ $sizeData->{$key} ?? 0 }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @empty
    <div class="pelanggan_jenis_job">
        <p class="text-gray-500 text-sm">Belum ada order untuk pelanggan ini.</p>
    </div>
    @endforelse


    @php
        $printed = false;
        $groupedSpek = collect();
    @endphp

    @foreach ($pelanggan->orders as $order)
        @php
            $groupedSpek = $groupedSpek->merge(
                $order->spesifikasi->groupBy('jenis_spek_id')
            );
        @endphp

        @if (!$printed && $loop->last)
            @php
                // gabungkan ulang berdasarkan jenis_spek_id
                $groupedSpek = $groupedSpek
                    ->flatten(1)
                    ->groupBy('jenis_spek_id');
                $printed = true;
            @endphp
            <div class="pelanggan_jenis_job_spek">
                <h3 class="pelanggan_jenis_job_heading">Rincian Spesifikasi</h3>
                <div>
                    
                    @if ($groupedSpek->isEmpty())
                        <p class="text-sm text-gray-600">
                            Belum ada spesifikasi yang dipilih.
                        </p>
                    @else
                        <div class="pelanggan_jenis_job_spek_layout">
                            @foreach ($groupedSpek as $spekId => $items)
                                @php
                                    $first = $items->first();
                                    $spekName = optional($first->jenisSpek)->nama_jenis_spek ?? ('Spek ' . $spekId);
                                    $detailName = optional($first->jenisSpekDetail)->nama_jenis_spek_detail ?? '-';
                                    $img = optional($first->jenisSpekDetail)->gambar;
                                @endphp

                                <div class="pelanggan_jenis_job_spek_box">
                                    <div class="pelanggan_jenis_job_spek_img">
                                        @if ($img)
                                            <img src="{{ asset('storage/' . $img) }}"
                                                alt="{{ $detailName }}">
                                        @else
                                            <div>
                                                No Image
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="pelanggan_jenis_job_heading">{{ $spekName }}</div>
                                        <div class="pelanggan_jenis_job_heading">{{ $detailName }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach

</div>


@endsection
