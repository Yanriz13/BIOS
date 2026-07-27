<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['divisiRelasi', 'departemenRelasi'])->latest()->get();

        $totalUsers     = User::count();
        $totalDireksi   = User::where('role', 'direksi')->count();
        $totalGh        = User::where('role', 'gh')->count();
        $totalDivHead   = User::where('role', 'div_head')->count();
        $totalDeptHead  = User::where('role', 'dept_head')->count();
        $totalAdminDept = User::where('role', 'admin_dept')->count();
        $totalStaff     = User::where('role', 'staff')->count();

        $totalDivisi     = Divisi::active()->count();
        $totalDepartemen = Departemen::active()->count();

        return view('superadmin.users.index', compact(
            'users',
            'totalUsers',
            'totalDivisi',
            'totalDepartemen',
            'totalDireksi',
            'totalGh',
            'totalDivHead',
            'totalDeptHead',
            'totalAdminDept',
            'totalStaff'
        ));
    }

    public function create()
    {
        $divisis     = Divisi::active()->orderBy('nama')->get();
        $departemens = Departemen::active()->orderBy('nama')->get();

        return view('superadmin.users.create', compact('divisis', 'departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'role'          => 'required|in:super_admin,direksi,gh,div_head,dept_head,admin_dept,staff',
            'divisi_id'     => 'required|exists:divisis,id',
            'departemen_id' => 'nullable|exists:departemens,id',
            'password'      => 'required|min:6',
        ]);

        $divisiNama     = Divisi::findOrFail($request->divisi_id)->nama;
        $departemenNama = $request->departemen_id ? Departemen::find($request->departemen_id)?->nama : null;

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'role'          => $request->role,
            'divisi'        => $divisiNama,
            'divisi_id'     => $request->divisi_id,
            'departemen'    => $departemenNama,
            'departemen_id' => $request->departemen_id,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit($id)
    {
        $user        = User::findOrFail($id);
        $divisis     = Divisi::active()->orderBy('nama')->get();
        $departemens = Departemen::where('divisi_id', $user->divisi_id)->active()->orderBy('nama')->get();

        return view('superadmin.users.edit', compact('user', 'divisis', 'departemens'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'role'          => 'required|in:super_admin,direksi,gh,div_head,dept_head,admin_dept,staff',
            'divisi_id'     => 'required|exists:divisis,id',
            'departemen_id' => 'nullable|exists:departemens,id',
        ]);

        $divisiNama     = Divisi::findOrFail($request->divisi_id)->nama;
        $departemenNama = $request->departemen_id ? Departemen::find($request->departemen_id)?->nama : null;

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'role'          => $request->role,
            'divisi'        => $divisiNama,
            'divisi_id'     => $request->divisi_id,
            'departemen'    => $departemenNama,
            'departemen_id' => $request->departemen_id,
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