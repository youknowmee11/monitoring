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
                    <strong> Implementasi Teknologi IoT Pada Sistem Monitoring Kondisi pH Tanah Pada Tanaman
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
                @php
                    $previousItemTime = null;
                @endphp

                @foreach ($data as $item)
                    @php
                        $lahan = App\Models\DataLahan::where('code_alat', $item->code_alat)->first();
                        $ph1 = number_format($item->ph1, 1);
                        $ph2 = number_format($item->ph2, 1);
                        $itemTime = $item->created_at; // Waktu pembuatan data
                    @endphp

                    @if ($previousItemTime === null || $itemTime->diffInMinutes($previousItemTime) >= 5)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code_alat }}</td>
                            <td>
                                <strong>Lahan {{ $lahan->nama_lahan }}</strong><br>
                                Luas : {{ $lahan->luas_lahan }} Ha
                            </td>
                            <td>
                                {{ $itemTime->format('d F Y') }}<br>
                                Jam {{ $itemTime->format('H:i:s') }}
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
                        @php
                            $previousItemTime = $itemTime;
                        @endphp
                    @endif
                @endforeach
            </tbody>
        </table>


    </main>

</body>

</html>
