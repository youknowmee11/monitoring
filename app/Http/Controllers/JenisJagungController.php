<?php

namespace App\Http\Controllers;

use App\Models\JenisJagung;
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

        $request->validate([
            'jenis_jagung' => 'required|string|max:255',
        ]);

        $jenis_jagung = new JenisJagung();
        $jenis_jagung->jenis_jagung = $request->input('jenis_jagung');
        $jenis_jagung->save();

        return redirect()->back()->withSuccess('Alat berhasil didaftarkan.');
    }
    public function update(Request $request, $id)
    {
        $$request->validate([
            'jenis_jagung' => 'required|string|max:255',
        ]);
        $jenis_jagung = JenisJagung::find($id);
        $jenis_jagung->jenis_jagung = $request->input('jenis_jagung');
        $jenis_jagung->save();

        return redirect()->back()->withSuccess('Alat berhasil diupdate.');
    }
    public function destroy($id)
    {
        $JenisJagung = JenisJagung::find($id);
        $JenisJagung->delete();
        return redirect()->back()->withSuccess('Alat berhasil dihapus.');
    }
}
