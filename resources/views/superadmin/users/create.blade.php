@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl mx-auto">

        <h1 class="text-3xl font-bold mb-2">Tambah User</h1>
        <p class="text-gray-500 mb-8">Tambahkan akun user baru</p>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="font-semibold">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-semibold">Role</label>
                    <select name="role" class="w-full border rounded-xl px-4 py-3 mt-2">
                        @foreach(['direksi','manager','supervisor','staff'] as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-semibold">Divisi</label>
                    <select name="divisi_id" class="w-full border rounded-xl px-4 py-3 mt-2">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama }}{{ $divisi->kode ? ' ('.$divisi->kode.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @if($divisis->isEmpty())
                        <p class="text-red-500 text-xs mt-1">
                            Belum ada divisi. 
                            <a href="{{ route('superadmin.divisi.create') }}" class="underline">Tambah divisi dulu</a>
                        </p>
                    @endif
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">Password</label>
                    <input type="password" name="password"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('superadmin.users.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl">
                    Kembali
                </a>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">
                    Simpan User
                </button>
            </div>

        </form>
    </div>
</div>
@endsection