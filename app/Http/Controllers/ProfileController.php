<?php

namespace App\Http\Controllers;

use App\Models\DataLahan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.profile');
    }
    public function admin()
    {
        $data = [
            'title' => 'Data admin',
            'user' => User::where('role', 'admin')->get(),
        ];
        return view('pages.admin.user.index', $data);
    }

    public function petani()
    {
        $data = [
            'title' => 'Data Petani',
            'user' => User::where('role', 'petani')->get(),
        ];
        return view('pages.admin.user.petani', $data);
    }
    public function tambah_admin()
    {
        $data = [
            'title' => 'Tambah Admin',
            'user' => User::where('role', 'user')->get(),
        ];
        return view('pages.admin.user.tambah_admin', $data);
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'string', 'max:255'],
                'email' => 'required|string|email|max:255',
                'current_password' => 'nullable|required_with:new_password',
                'new_password' => 'nullable|min:8|max:12|required_with:current_password',
                'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
            ], [
                'nama.required' => 'nama harus diisi',
                'last_name.required' => 'last name harus diisi',
                'tempat_lahir.required' => 'tempat lahir harus diisi',
                'tanggal_lahir.required' => 'tanggal lahir harus diisi',
                'email.required' => 'email harus diisi',
            ]);


            $user = User::findOrFail(Auth::user()->id);
            $user->name = $request->input('name');
            $user->last_name = $request->input('last_name');
            $user->last_name = $request->input('tempat_lahir');
            $user->last_name = $request->input('tanggal_lahir');
            $user->email = $request->input('email');

            if (!is_null($request->input('current_password'))) {
                if (Hash::check($request->input('current_password'), $user->password)) {
                    $user->password = $request->input('new_password');
                } else {
                    return redirect()->back()->withInput();
                }
            }

            if ($user->save()) {
                return redirect()->back()->withSuccess('Profile updated successfully.');
            } else {
                return redirect()->back()->withErrors('Profile updated failed.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'password' => 'required',
                'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            ]);


            $user = new User();
            $user->name = $request->input('name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->active = 1;
            $user->role = 'admin';
            if ($user->save()) {
                return redirect()->back()->withSuccess('akun created successfully.');
            } else {
                return redirect()->back()->withErrors('akun create failed');
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function validasi(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->active = $request->active;
            $user->save();
            return redirect()->back()->withSuccess('akun validation successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $user = User::find($id);

            if (DataLahan::where('id_user', $user->id)->count() > 0) {
                return redirect()->back()->withErrors('Tidak dapat menghapus akun dikarnakan akun ini telah memiliki lahan');
            } else {
                $user->delete();
                return redirect()->back()->withSuccess('Akun deleted.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan :' . $e->getMessage());
        }
    }
}
