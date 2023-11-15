<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class LaporanController extends Controller
{
    public function sensor()
    {
        if (Auth::user()->role == 'petani') {

            $code_alat = DataLahan::where('id_user', Auth::user()->id)->first()->code_alat;
        }
        $data = [
            'title' => 'Riwayat Data Sensor',
            'sensor' => Auth::user()->role == 'petani' ? Sensor::where('code_alat', $code_alat)->latest()->get() :  Sensor::latest()->get(),
        ];
        return view('pages.admin.laporan.sensor', $data);
    }
    public function cetak_sensor(Request $request)
    {

        $sensor = Sensor::where('created_at', '>=', $request->from_date)
            ->where('created_at', '<=', $request->to_date)
            ->get();

        if ($sensor->isEmpty()) {
            return redirect()->back()->with('danger', 'Data tidak tersedia');
        }
        $pdf = \PDF::loadview('pages/admin/laporan/pdf/pdf_sensor', [
            'data' => $sensor,
            'title' => 'Laporan Data Sensor Lahan',
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_lahan_' . $request->from_date . '-' . $request->to_date . '.pdf');
    }
}
