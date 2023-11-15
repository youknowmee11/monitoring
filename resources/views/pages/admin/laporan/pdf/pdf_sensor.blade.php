<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Sensor </title>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="stylesheet" href="{{ public_path('css') }}/pdf/bootstrap.min.css" media="all" />
    <style>
        body {
            font-family: 'times new roman';

        }
    </style>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"> --}}
</head>

<body>
    <main>
        <table class="table table-borderless">
            <tr>
                <td style="width: 20%">
                    <img style="width: 100px;" src="{{ public_path('img') }}/favicon.png">
                </td>
                <td class="text-center" style="width: 80%">
                    LAPORAN DATA SENSOR<br>
                    <strong> Implementasi Teknologi IoT Pada Sistem Pemantauan Kondisi pH Tanah Pada Tanaman
                        Jagung</strong>
                    <br>Tanggal {{ $from_date }} sampai {{ $to_date }}
                </td>
                <td style="width: 20%"></td>
            </tr>
        </table>
        <hr style="border: 1px solid black;">

        <table class="table table-bordered mb-0 lara-dataTable" style="font-size: 12px;">
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
                @forelse ($data as $item)
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
                @empty
                    <tr>
                        <td>Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </main>

</body>

</html>
