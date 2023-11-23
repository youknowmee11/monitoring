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
            'informasi_lahan' => 'required|string|max:255',
            'terakhir_tanam' => 'required|string|max:255',
            'id_jenis_jagung' => 'required|string|max:255',
            'latidude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        ]);


        $DataLahan = new DataLahan();

        $DataLahan->id_user = Auth::user()->id;
        $DataLahan->code_alat = $request->input('code_alat');
        $DataLahan->nama_lahan = $request->input('nama_lahan');
        $DataLahan->luas_lahan = $request->input('luas_lahan');
        $DataLahan->informasi_lahan = $request->input('informasi_lahan');
        $DataLahan->terakhir_tanam = $request->input('terakhir_tanam');
        $DataLahan->id_jenis_jagung = $request->input('id_jenis_jagung');
        $DataLahan->latitude = $request->input('latitude');
        $DataLahan->longitude = $request->input('longitude');
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
            'informasi_lahan' => 'required|string|max:255',
            'terakhir_tanam' => 'required|string|max:255',
            'id_jenis_jagung' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        ], [
            'required' => ':attribute harus diisi', // Catch-all message for 'required' rule

            'code_alat.required' => 'Code Alat harus diisi',
            'code_alat.string' => 'Code Alat harus berupa teks',
            'code_alat.max' => 'Code Alat tidak boleh lebih dari :max karakter',
            'code_alat.exists' => 'Code Alat tidak valid',

            'nama_lahan.required' => 'Nama Lahan harus diisi',
            'nama_lahan.string' => 'Nama Lahan harus berupa teks',
            'nama_lahan.max' => 'Nama Lahan tidak boleh lebih dari :max karakter',

            'luas_lahan.required' => 'Luas Lahan harus diisi',
            'luas_lahan.string' => 'Luas Lahan harus berupa teks',
            'luas_lahan.max' => 'Luas Lahan tidak boleh lebih dari :max karakter',

            'informasi_lahan.required' => 'Informasi Lahan harus diisi',
            'informasi_lahan.string' => 'Informasi Lahan harus berupa teks',
            'informasi_lahan.max' => 'Informasi Lahan tidak boleh lebih dari :max karakter',

            'terakhir_tanam.required' => 'Data Terakhir Tanam harus diisi',
            'terakhir_tanam.string' => 'Data Terakhir Tanam harus berupa teks',
            'terakhir_tanam.max' => 'Data Terakhir Tanam tidak boleh lebih dari :max karakter',

            'id_jenis_jagung.required' => 'Data Jenis Jagung harus diisi',
            'id_jenis_jagung.string' => 'Data Jenis Jagung harus berupa teks',
            'id_jenis_jagung.max' => 'Data Jenis Jagung tidak boleh lebih dari :max karakter',

            'latitude.required' => 'Latitude harus diisi',
            'latitude.string' => 'Latitude harus berupa teks',
            'latitude.max' => 'Latitude tidak boleh lebih dari :max karakter',

            'longitude.required' => 'Longitude harus diisi',
            'longitude.string' => 'Longitude harus berupa teks',
            'longitude.max' => 'Longitude tidak boleh lebih dari :max karakter',
        ]);


        $DataLahan = DataLahan::find($id);

        $DataLahan->id_user = Auth::user()->id;
        $DataLahan->code_alat = $request->input('code_alat');
        $DataLahan->nama_lahan = $request->input('nama_lahan');
        $DataLahan->luas_lahan = $request->input('luas_lahan');
        $DataLahan->informasi_lahan = $request->input('informasi_lahan');
        $DataLahan->terakhir_tanam = $request->input('terakhir_tanam');
        $DataLahan->id_jenis_jagung = $request->input('id_jenis_jagung');
        $DataLahan->latitude = $request->input('latitude');
        $DataLahan->longitude = $request->input('longitude');
        $DataLahan->save();

        return redirect()->back()->withSuccess('Data berhasil disimpan.');
    }
}
