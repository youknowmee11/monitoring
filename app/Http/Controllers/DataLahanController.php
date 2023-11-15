<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\User;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DataLahanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'data lahan',
            'lahan' => Auth::user()->role == 'admin' ? DataLahan::all() : DataLahan::where('id_user', Auth::user()->id)->get(),
        ];
        return view('pages.admin.data_lahan.index', $data);
    }
    public function create()
    {

        $data = [
            'title' => 'Form input data lahan',
        ];
        return view('pages.admin.data_lahan.create', $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'code_alat' => [
                'required',
                'string',
                'max:255',
                Rule::exists(Alat::class, 'code_alat'),
            ],
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|string|max:255',
            'data_lahan' => 'required|string|max:255',
            'terakhir_tanam' => 'required|string|max:255',
            'id_jenis_jagung' => 'required|string|max:255',
        ]);


        $DataLahan = new DataLahan();

        $DataLahan->id_user = Auth::user()->id;
        $DataLahan->code_alat = $request->input('code_alat');
        $DataLahan->nama_lahan = $request->input('nama_lahan');
        $DataLahan->luas_lahan = $request->input('luas_lahan');
        $DataLahan->data_lahan = $request->input('data_lahan');
        $DataLahan->terakhir_tanam = $request->input('terakhir_tanam');
        $DataLahan->id_jenis_jagung = $request->input('id_jenis_jagung');
        $DataLahan->save();

        return redirect()->back()->withSuccess('Data berhasil disimpan.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'code_alat' => [
                'required',
                'string',
                'max:255',
                Rule::exists(Alat::class, 'code_alat'),
            ],
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|string|max:255',
            'data_lahan' => 'required|string|max:255',
            'terakhir_tanam' => 'required|string|max:255',
            'id_jenis_jagung' => 'required|string|max:255',
        ]);


        $DataLahan = DataLahan::find($id);

        $DataLahan->id_user = Auth::user()->id;
        $DataLahan->code_alat = $request->input('code_alat');
        $DataLahan->nama_lahan = $request->input('nama_lahan');
        $DataLahan->luas_lahan = $request->input('luas_lahan');
        $DataLahan->data_lahan = $request->input('data_lahan');
        $DataLahan->terakhir_tanam = $request->input('terakhir_tanam');
        $DataLahan->id_jenis_jagung = $request->input('id_jenis_jagung');
        $DataLahan->save();

        return redirect()->back()->withSuccess('Data berhasil disimpan.');
    }
}
