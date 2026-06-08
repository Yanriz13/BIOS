<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisiController extends Controller
{
    /**
     * List semua divisi
     */
    public function index()
    {
        $divisis = Divisi::withCount('users')->latest()->get();

        return view('superadmin.divisi.index', compact('divisis'));
    }

    /**
     * Form tambah divisi
     */
    public function create()
    {
        return view('superadmin.divisi.create');
    }

    /**
     * Simpan divisi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100|unique:divisis,nama',
            'kode'      => 'nullable|string|max:10|unique:divisis,kode',
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Divisi::create([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('superadmin.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan');
    }

    /**
     * Form edit divisi
     */
    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);

        return view('superadmin.divisi.edit', compact('divisi'));
    }

    /**
     * Update divisi
     */
    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:100|unique:divisis,nama,' . $id,
            'kode'      => 'nullable|string|max:10|unique:divisis,kode,' . $id,
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $divisi->update([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('superadmin.divisi.index')
            ->with('success', 'Divisi berhasil diupdate');
    }

    /**
     * Hapus divisi
     */
    public function destroy($id)
    {
        $divisi = Divisi::withCount('users')->findOrFail($id);

        // Cegah hapus jika masih ada user
        if ($divisi->users_count > 0) {
            return back()->with(
                'error',
                "Divisi tidak bisa dihapus karena masih memiliki {$divisi->users_count} user aktif."
            );
        }

        $divisi->delete();

        return back()->with('success', 'Divisi berhasil dihapus');
    }
}