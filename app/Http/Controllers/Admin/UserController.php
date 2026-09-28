<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        $karyawans = User::where('role','karyawan')->get();
        $products  = Product::with('variants')->latest()->take(10)->get();

        return view('admin.dashboard', compact('karyawans','products'));
    }

    // ================= INDEX =================
    public function index()
    {
        $karyawans = User::where('role','karyawan')->latest()->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.karyawan.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'gender'=>'required|in:L,P',
            'password'=>'required|min:6'
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'gender'=>$request->gender,
            'role'=>'karyawan',
            'password'=>bcrypt($request->password),
            'is_active'=>true
        ]);

        return redirect()->route('admin.karyawan.index')
            ->with('success','Karyawan berhasil ditambahkan!');
    }

    // ================= EDIT =================
    public function edit(User $user)
    {
        return view('admin.karyawan.edit', compact('user'));
    }

    // ================= UPDATE =================
   // Update data karyawan
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|in:L,P',
            'password' => 'nullable|min:6'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;

        // Jika password diisi → update
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diupdate!');
    }

    // ================= DELETE =================
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.karyawan.index')
            ->with('success','Karyawan berhasil dihapus!');
    }
}