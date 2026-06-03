<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * List user
     */
    public function index()
    {
        $users = User::latest()->get();

        $totalUsers = User::count();

        $totalDivisi = User::distinct('divisi')->count('divisi');

        $totalManager = User::where('role', 'manager')->count();

        $totalDireksi = User::where('role', 'direksi')->count();

        $totalStaff = User::where('role', 'staff')->count();

        return view(
            'superadmin.users.index',
            compact(
                'users',
                'totalUsers',
                'totalDivisi',
                'totalManager',
                'totalDireksi',
                'totalStaff'
            )
        );
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('superadmin.users.create');
    }

    /**
     * Store user
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users,email',

            'role' => 'required',

            'divisi' => 'required',

            'password' => 'required|min:6'

        ]);

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'role' => $request->role,

            'divisi' => $request->divisi,

            'password' => Hash::make($request->password)

        ]);

        return redirect()
            ->route('superadmin.users.index')
            ->with(
                'success',
                'User berhasil dibuat'
            );
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view(
            'superadmin.users.edit',
            compact('user')
        );
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users,email,' . $id,

            'role' => 'required',

            'divisi' => 'required',

        ]);

        $user->update([

            'name' => $request->name,

            'email' => $request->email,

            'role' => $request->role,

            'divisi' => $request->divisi,

        ]);

        if ($request->password) {

            $user->update([

                'password' => Hash::make($request->password)

            ]);
        }

        return redirect()
            ->route('superadmin.users.index')
            ->with(
                'success',
                'User berhasil diupdate'
            );
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with(
            'success',
            'User berhasil dihapus'
        );
    }
}