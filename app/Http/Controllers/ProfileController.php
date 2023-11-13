<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);


        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = $request->input('new_password');
            } else {
                return redirect()->back()->withInput();
            }
        }

        $user->save();

        return redirect()->route('profile')->withSuccess('Profile updated successfully.');
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'password' => 'required',
            'email' => 'required',
        ]);


        $user = new User();
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->active = 1;
        $user->role = 'admin';
        $user->save();

        return redirect()->back()->withSuccess('Profile updated successfully.');
    }
    public function validasi(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->active = $request->active;
        $user->save();
        return redirect()->back()->withSuccess('Profile updated successfully.');
    }
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->withSuccess('Profile deleted.');
    }
}
