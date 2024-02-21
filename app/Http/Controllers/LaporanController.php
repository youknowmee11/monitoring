<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\QueryException;

class LaporanController extends Controller
{
    public function sensor()
    {
        if (Auth::user()->role == 'petani') {

            $code_alat = DataLahan::where('id_user', Auth::user()->id)->first()->code_alat ?? null;
        }
        $data = [
            'title' => 'Riwayat Data Sensor',
            'sensor' => Auth::user()->role == 'petani' ? Sensor::where('code_alat', $code_alat)->latest()->get() :  Sensor::latest()->get(),
            'petani' => User::where('role', 'petani')->latest()->get(),
            'from_date' => null,
            'to_date' => null,
            'id_petani' => null
        ];
        return view('pages.admin.laporan.sensor', $data);
    }
    public function cetak_sensor(Request $request)
    {
        try {
            $action = $request->input('action');


            if ($action === 'export') {

                $sensor = Sensor::where('created_at', '>=', $request->from_date)
                    ->where('created_at', '<=', $request->to_date)
                    ->latest();
                if (Auth::user()->role == 'admin') {
                    if ($request->petani != '-') {
                        $lahan = DataLahan::where('id_user', $request->petani)->first();
                        $petani = User::find($request->petani);
                        if ($lahan) {
                            $sensor = $sensor->where('code_alat', $lahan->code_alat);
                        } else {
                            return redirect()->back()->with('danger', 'Data sensort oleh akun petani : ' . $petani->name . ' tidak tersedia');
                        }
                    }
                }

                $data = $sensor->get();

                if ($data->isEmpty()) {
                    return redirect()->back()->with('danger', 'Data tidak tersedia');
                }

                $pdf = \PDF::loadview('pages/admin/laporan/pdf/pdf_sensor', [
                    'data' => $data,
                    'title' => 'Laporan Data Sensor Lahan',
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                ])
                    ->setPaper('a4', 'landscape');
                return $pdf->stream('Laporan_lahan_' . $request->from_date . '-' . $request->to_date . '.pdf');
            } else {
                if (Auth::user()->role == 'petani') {

                    $code_alat = DataLahan::where('id_user', Auth::user()->id)->first()->code_alat ?? null;
                }
                $sensor = Sensor::where('created_at', '>=', $request->from_date)
                    ->where('created_at', '<=', $request->to_date)
                    ->latest();

                if (Auth::user()->role == 'admin') {
                    if ($request->petani != '-') {
                        $lahan = DataLahan::where('id_user', $request->petani)->first();
                        $code_alat_admin = $lahan->code_alat ?? null;
                        $petani = User::find($request->petani);
                        $data = $sensor->where('code_alat', $code_alat_admin);
                    }
                } else {
                    $data = $sensor->where('code_alat', $code_alat);
                }

                $data = $sensor->get();

                $data = [
                    'title' => 'Riwayat Data Sensor',
                    'sensor' => $data,
                    'petani' => User::where('role', 'petani')->latest()->get(),
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'id_petani' => $request->petani
                ];
                return view('pages.admin.laporan.sensor', $data);
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
}
