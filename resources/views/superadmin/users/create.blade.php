@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl mx-auto">

        <h1 class="text-3xl font-bold mb-2">Tambah User</h1>
        <p class="text-gray-500 mb-8">Tambahkan akun user baru ke dalam sistem</p>

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
                    <label class="font-semibold">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="font-semibold">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="font-semibold">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @php
                            $roles = [
                                'direksi'    => 'Direksi',
                                'gh'         => 'GH (Group Head)',
                                'div_head'   => 'Div Head (Division Head)',
                                'dept_head'  => 'Dept Head (Department Head)',
                                'admin_dept' => 'Admin Dept',
                                'staff'      => 'Staff',
                                'super_admin'=> 'Super Admin',
                            ];
                        @endphp
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-semibold">Divisi <span class="text-red-500">*</span></label>
                    <select name="divisi_id" id="divisi_id" required class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
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

                <div>
                    <label class="font-semibold">Departemen</label>
                    <select name="departemen_id" id="departemen_id" class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-semibold">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full border rounded-xl px-4 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
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

<script>
document.getElementById('divisi_id').addEventListener('change', function() {
    const divisiId = this.value;
    const deptSelect = document.getElementById('departemen_id');
    deptSelect.innerHTML = '<option value="">-- Pilih Departemen --</option>';

    if (divisiId) {
        fetch(`/super-admin/divisi/${divisiId}/departemens`)
            .then(res => res.json())
            .then(data => {
                data.forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept.id;
                    opt.textContent = dept.nama;
                    deptSelect.appendChild(opt);
                });
            });
    }
});
</script>
@endsection