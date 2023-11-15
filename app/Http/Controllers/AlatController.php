<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;
use QrCode;

class AlatController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Alat',
            'alat' => Alat::all(),
        ];
        return view('pages.admin.alat.index', $data);
    }
    public function store(Request $request)
    {

        $request->validate([
            'tanggal_buat' => 'string|max:255',
            'keterangan' => 'required',
        ]);


        $alat = new Alat();
        $alat->code_alat = Str::uuid();
        $alat->tanggal_buat = $request->input('tanggal_buat');
        $alat->keterangan = $request->input('keterangan');
        $alat->save();

        return redirect()->back()->withSuccess('Alat berhasil didaftarkan.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_buat' => 'string|max:255',
            'keterangan' => 'required',
        ]);
        $alat = Alat::find($id);
        $alat->code_alat = Str::uuid();
        $alat->tanggal_buat = $request->input('tanggal_buat');
        $alat->keterangan = $request->input('keterangan');
        $alat->save();

        return redirect()->back()->withSuccess('Alat berhasil diupdate.');
    }
    public function destroy($id)
    {
        $alat = Alat::find($id);
        $alat->delete();
        return redirect()->back()->withSuccess('Alat berhasil dihapus.');
    }
    public function print($id)
    {
        $data = Alat::find($id);
        $qr = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($data->code_alat));
        $pdf = \PDF::loadview('pages/admin/alat/print', ['data' => $data, 'qr' => $qr])->setPaper(array(0, 0, 160, 330));
        return $pdf->stream('alat_' . $data->code_alat . '.pdf');
    }
}
