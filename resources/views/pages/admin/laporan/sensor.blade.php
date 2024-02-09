@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="my-3">
        <form action="{{ route('laporan.cetak_sensor') }}" method="GET" enctype="multipart/form-data" class="form-inline">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tanggal </span>
                </div>
                <input type="date" class="form-control" name="from_date" value="{{ date('Y-m-d') }}">
                <div class="input-group-prepend">
                    <span class="input-group-text">Sampai </span>
                </div>
                <input type="date" class="form-control" name="to_date" value="{{ date('Y-m-d') }}">
            </div>
            <button type="submit" class="btn btn-primary mx-2"> <i class="fa fa-print"></i> Export Data</button>
        </form>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th style="width:10px;">No</th>
                        <th style="width:350px;">Code Alat</th>
                        <th>Lahan</th>
                        <th>Temestamp</th>
                        <th>PH</th>
                        <th>TDS</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sensor as $item)
                        @php
                            $lahan = App\Models\DataLahan::where('code_alat', $item->code_alat)->first();
                            $ph1 = number_format($item->ph1, 1);
                            $ph2 = number_format($item->ph2, 1);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code_alat }}</td>
                            <td>
                                <strong>Lahan {{ $lahan->nama_lahan }}</strong><br>
                                Luas : {{ $lahan->luas_lahan }} Ha
                            </td>
                            <td>
                                {{ $item->created_at->format('d F Y') }}<br>
                                Jam {{ $item->created_at->format('H:i:s') }}
                            </td>
                            <td>
                                <strong>PH 1 : </strong>{{ $item->ph1 }}<br>
                                <strong>PH 2 : </strong>{{ $item->ph2 }}<br>
                            </td>
                            <td>
                                <strong>TDS 1 : </strong>{{ $item->salinitas1 }}<br>
                                <strong>TDS 2 : </strong>{{ $item->salinitas2 }}<br>
                            </td>
                            <td>
                                @if ($ph1 == 5.0 && $ph2 == 5.0)
                                    <span class="text-primary">Nitrogen tidak tersedia</span>
                                @elseif($ph1 >= 5.0 && $ph2 >= 5.0 && ($ph1 <= 5.5 && $ph2 <= 5.5))
                                    <span class="text-primary">Phosfor tidak tersedia dan Kalium tidak tersedia</span>
                                @elseif($ph1 >= 5.0 && $ph2 >= 5.0 && ($ph1 <= 6.4 && $ph2 <= 6.4))
                                    <span class="text-primary">Magnesium dan kalsium tidak tersedia</span>
                                @elseif($ph1 >= 5.1 && $ph2 >= 5.1 && ($ph1 <= 5.9 && $ph2 <= 5.9))
                                    <span class="text-primary">Nitrogen tidak memenuhi</span>
                                @elseif($ph1 >= 5.6 && $ph2 >= 5.6 && ($ph1 <= 5.9 && $ph2 <= 5.9))
                                    <span class="text-primary">Phospor tidak memenuhi dan Kalium tidak memenuhi</span>
                                @elseif($ph1 >= 6.5 && $ph2 >= 6.5 && ($ph1 <= 8.8 && $ph2 <= 8.8))
                                    <span class="text-primary">Magnesium dan Kalsium memenuhi penyerapan</span>
                                @else
                                    <span class="text-primary">tidak diketahui</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('pages.admin.data_lahan.modal_create')
@endsection
