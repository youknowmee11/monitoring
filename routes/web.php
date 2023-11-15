<?php

use App\Http\Controllers\DataLahanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Notifikasi
    Route::put('/read_notif/{id}', [NotifikasiController::class, 'read'])->name('read_notif');
    Route::put('/read_all/{id}', [NotifikasiController::class, 'read_all'])->name('read_all');
    Route::get('/notifikasi', [HomeController::class, 'notifikasi'])->name('notifikasi');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/about', function () {
        return view('about');
    })->name('about');
});
Route::middleware('role:admin')->group(function () {

    //jenis jagung
    Route::get('jenis_jagung', 'JenisJagungController@index')->name('jenis_jagung');
    Route::post('jenis_jagung/store', 'JenisJagungController@store')->name('jenis_jagung.store');
    Route::put('jenis_jagung/update/{id}', 'JenisJagungController@update')->name('jenis_jagung.update');
    Route::delete('jenis_jagung/destroy/{id}', 'JenisJagungController@destroy')->name('jenis_jagung.destroy');
    //alat
    Route::get('alat', 'AlatController@index')->name('alat');
    Route::post('alat/store', 'AlatController@store')->name('alat.store');
    Route::put('alat/update/{id}', 'AlatController@update')->name('alat.update');
    Route::delete('alat/destroy/{id}', 'AlatController@destroy')->name('alat.destroy');
    Route::get('alat/print/{id}', 'AlatController@print')->name('alat.print');
    //pengguna
    Route::get('petani', 'ProfileController@petani')->name('petani');
    Route::get('admin', 'ProfileController@admin')->name('admin');
    Route::get('profile/tambah_admin', 'ProfileController@tambah_admin')->name('profile.tambah_admin');
    Route::put('/validasi/{id}', 'ProfileController@validasi')->name('validasi');
    Route::delete('profile/delete/{id}', 'ProfileController@delete')->name('profile.delete');
    Route::post('profile/create', 'ProfileController@create')->name('profile.create');
});
Route::middleware('role:admin,petani')->group(function () {
    //laporan
    Route::get('laporan/sensor', [LaporanController::class, 'sensor'])->name('laporan.sensor');
    Route::get('laporan/cetak_sensor', [LaporanController::class, 'cetak_sensor'])->name('laporan.cetak_sensor');
    //data lahan
    Route::get('data_lahan', [DataLahanController::class, 'index'])->name('data_lahan');
    Route::post('data_lahan/store', [DataLahanController::class, 'store'])->name('data_lahan.store');
    Route::put('data_lahan/update/{id}', [DataLahanController::class, 'update'])->name('data_lahan.update');
});
