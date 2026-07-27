<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartemenController extends Controller
{
    /**
     * List semua departemen
     */
    public function index()
    {
        $departemens = Departemen::with('divisi')->withCount('users')->latest()->get();

        return view('superadmin.departemen.index', compact('departemens'));
    }

    /**
     * Form tambah departemen
     */
    public function create()
    {
        $divisis = Divisi::active()->orderBy('nama')->get();

        return view('superadmin.departemen.create', compact('divisis'));
    }

    /**
     * Simpan departemen baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'nama'      => 'required|string|max:100',
            'kode'      => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Departemen::create([
            'divisi_id' => $request->divisi_id,
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('superadmin.departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * Form edit departemen
     */
    public function edit($id)
    {
        $departemen = Departemen::findOrFail($id);
        $divisis    = Divisi::active()->orderBy('nama')->get();

        return view('superadmin.departemen.edit', compact('departemen', 'divisis'));
    }

    /**
     * Update departemen
     */
    public function update(Request $request, $id)
    {
        $departemen = Departemen::findOrFail($id);

        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'nama'      => 'required|string|max:100',
            'kode'      => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $departemen->update([
            'divisi_id' => $request->divisi_id,
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('superadmin.departemen.index')
            ->with('success', 'Departemen berhasil diupdate');
    }

    /**
     * Hapus departemen
     */
    public function destroy($id)
    {
        $departemen = Departemen::withCount('users')->findOrFail($id);

        if ($departemen->users_count > 0) {
            return back()->with(
                'error',
                "Departemen tidak bisa dihapus karena masih memiliki {$departemen->users_count} user aktif."
            );
        }

        $departemen->delete();

        return back()->with('success', 'Departemen berhasil dihapus');
    }

    /**
     * API JSON untuk get departemen berdasarkan divisi_id (digunakan di form AJAX/JS)
     */
    public function getByDivisi($divisiId)
    {
        $departemens = Departemen::where('divisi_id', $divisiId)->active()->orderBy('nama')->get();

        return response()->json($departemens);
    }
}
