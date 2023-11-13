<?php

use App\Http\Controllers\DataLahanController;
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
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/about', function () {
        return view('about');
    })->name('about');
});
Route::middleware('role:admin')->group(function () {

    //pengguna
    Route::get('petani', 'ProfileController@petani')->name('petani');
    Route::get('admin', 'ProfileController@admin')->name('admin');
    Route::get('profile/tambah_admin', 'ProfileController@tambah_admin')->name('profile.tambah_admin');
    Route::put('/validasi/{id}', 'ProfileController@validasi')->name('validasi');
    Route::delete('profile/delete/{id}', 'ProfileController@delete')->name('profile.delete');
    Route::post('profile/create', 'ProfileController@create')->name('profile.create');
});
Route::middleware('role:admin,petani')->group(function () {
    //data lahan
    Route::get('data_lahan', [DataLahanController::class, 'index'])->name('data_lahan');
    Route::get('data_lahan/create', [DataLahanController::class, 'create'])->name('data_lahan.create');
    Route::post('data_lahan/store', [DataLahanController::class, 'store'])->name('data_lahan.store');
});
