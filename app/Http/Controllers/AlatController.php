<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\QueryException;
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

        try {
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
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
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
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $alat = Alat::find($id);
            $alat->delete();
            return redirect()->back()->withSuccess('Alat berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function print($id)
    {
        try {
            $data = Alat::find($id);
            $qr = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($data->code_alat));
            $pdf = \PDF::loadview('pages/admin/alat/print', ['data' => $data, 'qr' => $qr])->setPaper(array(0, 0, 160, 330));
            return $pdf->stream('alat_' . $data->code_alat . '.pdf');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
}
