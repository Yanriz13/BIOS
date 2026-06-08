<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('divisiRelasi')->latest()->get();

        $totalUsers     = User::count();
        $totalDireksi   = User::where('role', 'direksi')->count();
        $totalManager   = User::where('role', 'manager')->count();
        $totalStaff     = User::where('role', 'staff')->count();
        $totalSupervisor = User::where('role', 'supervisor')->count();

        // Untuk card "Total Divisi" (pakai jumlah divisi aktif, bukan distinct)
        $totalDivisi = Divisi::active()->count();

        return view('superadmin.users.index', compact(
            'users',
            'totalUsers',
            'totalDivisi',
            'totalManager',
            'totalDireksi',
            'totalStaff',
            'totalSupervisor'
        ));
    }

    public function create()
    {
        // Kirim daftar divisi aktif ke form
        $divisis = Divisi::active()->orderBy('nama')->get();

        return view('superadmin.users.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'role'      => 'required',
            'divisi_id' => 'required|exists:divisis,id',
            'password'  => 'required|min:6',
        ]);

        $divisiNama = Divisi::findOrFail($request->divisi_id)->nama;

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'divisi'    => $divisiNama,
            'divisi_id' => $request->divisi_id,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit($id)
    {
        $user    = User::findOrFail($id);
        $divisis = Divisi::active()->orderBy('nama')->get();

        return view('superadmin.users.edit', compact('user', 'divisis'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $id,
            'role'      => 'required',
            'divisi_id' => 'required|exists:divisis,id',
        ]);

        $divisiNama = Divisi::findOrFail($request->divisi_id)->nama;

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'divisi'    => $divisiNama,
            'divisi_id' => $request->divisi_id,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}