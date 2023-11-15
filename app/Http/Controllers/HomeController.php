<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\DataLahan;
use App\Models\JenisJagung;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $petani = User::where('role', 'petani')->count();
        $lahan_petani = DataLahan::sum('luas_lahan');
        $alat = Alat::count();
        $JenisJagung = JenisJagung::count();

        $widget = [
            'petani' => $petani,
            'alat' => $alat,
            'jenis_jagung' => $JenisJagung,
            'lahan_petani' => $lahan_petani,
            'lahan' => DataLahan::where('id_user', Auth::user()->id)->get(),
        ];

        return view('pages.home', compact('widget'));
    }
    public function notifikasi()
    {
        $data = [
            'title' => 'Semua Notifikasi',
            'notifikasi' => Notifikasi::where('id_user', Auth::user()->id)->get(),
        ];
        return view('pages.notifikasi', $data);
    }
}
