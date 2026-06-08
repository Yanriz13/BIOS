@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Divisi</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola divisi yang tersedia di sistem</p>
        </div>
        <a href="{{ route('superadmin.divisi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">
            + Tambah Divisi
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5">{{ session('error') }}</div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-900 text-white">
                <tr>
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">Nama Divisi</th>
                    <th class="px-6 py-4 text-left">Kode</th>
                    <th class="px-6 py-4 text-left">Deskripsi</th>
                    <th class="px-6 py-4 text-center">Jumlah User</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($divisis as $divisi)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $divisi->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm font-mono">
                                {{ $divisi->kode ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm">{{ $divisi->deskripsi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                {{ $divisi->users_count }} user
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($divisi->is_active)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('superadmin.divisi.edit', $divisi->id) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">
                                Edit
                            </a>

                            <form action="{{ route('superadmin.divisi.destroy', $divisi->id) }}"
                                  method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus divisi {{ $divisi->nama }}?')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            Belum ada divisi. <a href="{{ route('superadmin.divisi.create') }}" class="text-blue-600 underline">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection