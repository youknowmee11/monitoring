<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\JenisJagung;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class JenisJagungController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Jenis Jagung',
            'jenis_jagung' => JenisJagung::all(),
        ];
        return view('pages.admin.jenis_jagung.index', $data);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'jenis_jagung' => 'required|string|max:255',
            ]);

            $jenis_jagung = new JenisJagung();
            $jenis_jagung->jenis_jagung = $request->input('jenis_jagung');
            if ($jenis_jagung->save()) {
                return redirect()->back()->withSuccess('Alat berhasil didaftarkan.');
            } else {
                return redirect()->back()->withErrors('Alat gagal didaftarkan.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_jagung' => 'required|string|max:255',
            ]);
            $jenis_jagung = JenisJagung::find($id);
            $jenis_jagung->jenis_jagung = $request->input('jenis_jagung');
            if ($jenis_jagung->save()) {
                return redirect()->back()->withSuccess('Alat berhasil didaftarkan.');
            } else {
                return redirect()->back()->withErrors('Alat gagal didaftarkan.');
            }

            return redirect()->back()->withSuccess('Alat berhasil diupdate.');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {

            $JenisJagung = JenisJagung::find($id);

            if (DataLahan::where('id_jenis_jagung', $JenisJagung->id)->count() > 0) {
                return redirect()->back()->withErrors('Tidak dapat menghapus jenis jagung karena terdapat data lahan yang menggunakan jenis jagung ini.');
            } else {
                $JenisJagung->delete();
                return redirect()->back()->withSuccess('Jagung berhasil dihapus.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
}
