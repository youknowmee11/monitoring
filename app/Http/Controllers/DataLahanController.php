<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataLahanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'data lahan',
            'petani'=>User::where('role','user')->get(),
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
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|string|max:255',
            'data_lahan' => 'required|string|max:255',
        ]);


        $DataLahan = new DataLahan();

        $DataLahan->id_user = Auth::user()->id;
        $DataLahan->nama_lahan = $request->input('nama_lahan');
        $DataLahan->luas_lahan = $request->input('luas_lahan');
        $DataLahan->data_lahan = $request->input('data_lahan');
        $DataLahan->save();

        return redirect()->back()->withSuccess('Data berhasil disimpan.');
    }
}