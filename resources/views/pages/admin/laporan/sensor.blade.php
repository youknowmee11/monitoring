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
                                @if (($ph1 >= 5.6 || $ph2 >= 5.6) && ($ph2 <= 6.2 || $ph2 <= 6.2))
                                    <span class="text-primary">Nutrisi tanah seimbang</span>
                                @elseif($ph1 < 5.6 && $ph2 < 5.6)
                                    <span class="text-danger">Nutrisi Tanah kehilangan kalsium(ca), magnesium(mg)</span>
                                @elseif($ph1 > 6.2 && $ph2 > 6.2)
                                    <span class="text-danger">Nutrisi Tanah kehilangan fosfor (p), mangan (mn)</span>
                                @else
                                    <span class="text-danger">Terjadi kesalahan</span>
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
