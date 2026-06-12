@extends('layouts.app')

@section('content')
@php
    $backUrl = url()->previous() ?: route('superadmin.divisi.index');
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

        <h1 class="text-3xl font-bold mb-2">Edit Divisi</h1>
        <p class="text-gray-500 mb-8">Ubah informasi divisi <strong>{{ $divisi->nama }}</strong></p>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.divisi.update', $divisi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>
                    <label class="font-semibold">Nama Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $divisi->nama) }}"
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="font-semibold">Kode Divisi</label>
                    <input type="text" name="kode" value="{{ old('kode', $divisi->kode) }}"
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300 font-mono uppercase">
                </div>

                <div>
                    <label class="font-semibold">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('deskripsi', $divisi->deskripsi) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $divisi->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded accent-blue-600">
                    <label for="is_active" class="font-semibold cursor-pointer">Divisi Aktif</label>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ $backUrl }}"
                   class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
                <button class="inline-flex h-11 items-center rounded-2xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Update Divisi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection