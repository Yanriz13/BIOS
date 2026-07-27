@extends('layouts.app')

@section('content')
@php
    $backUrl = route('superadmin.departemen.index');
@endphp
<div class="container mx-auto py-10">
    <div class="mx-auto mb-4 max-w-xl">
        <a href="{{ $backUrl }}"
           class="inline-flex h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <x-icon name="back" class="w-4 h-4" />
            <span>Back</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-xl mx-auto">

        <h1 class="text-3xl font-bold mb-2">Tambah Departemen</h1>
        <p class="text-gray-500 mb-8">Buat departemen baru di bawah divisi yang dipilih</p>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.departemen.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="font-semibold">Pilih Divisi <span class="text-red-500">*</span></label>
                    <select name="divisi_id" required
                            class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-semibold">Nama Departemen <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           placeholder="cth: Digital Marketing, Frontend Dev"
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="font-semibold">Kode Departemen</label>
                    <input type="text" name="kode" value="{{ old('kode') }}"
                           placeholder="cth: DMKT, FED (maks 10 karakter)"
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono uppercase">
                </div>

                <div>
                    <label class="font-semibold">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                               placeholder="Deskripsi singkat tentang departemen ini"
                               class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="w-5 h-5 rounded accent-blue-600">
                    <label for="is_active" class="font-semibold cursor-pointer">Departemen Aktif</label>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ $backUrl }}"
                   class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
                <button class="inline-flex h-11 items-center rounded-2xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Simpan Departemen
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
