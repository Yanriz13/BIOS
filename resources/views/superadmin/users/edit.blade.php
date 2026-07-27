@extends('layouts.app')

@section('content')

<div class="container mx-auto py-10">

    <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8">

            <h1 class="text-3xl font-black text-slate-800">
                Edit User
            </h1>

            <p class="text-slate-500 mt-2">
                Update data user perusahaan
            </p>

        </div>

        {{-- ERROR --}}
        @if ($errors->any())

            <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl mb-6">

                <ul class="list-disc pl-5 space-y-1">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- FORM --}}
        <form
            action="{{ route('superadmin.users.update', $user->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- NAMA --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                </div>

                {{-- EMAIL --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                </div>

                {{-- ROLE --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
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
                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- DIVISI --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Divisi
                    </label>

                    <select
                        name="divisi_id"
                        id="divisi_id"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisis as $div)
                            <option value="{{ $div->id }}"
                                {{ old('divisi_id', $user->divisi_id) == $div->id ? 'selected' : '' }}>
                                {{ $div->nama }}{{ $div->kode ? ' ('.$div->kode.')' : '' }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- DEPARTEMEN --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Departemen
                    </label>

                    <select
                        name="departemen_id"
                        id="departemen_id"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('departemen_id', $user->departemen_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- PASSWORD --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                    <small class="text-slate-400">
                        Kosongkan jika tidak ingin mengganti password
                    </small>

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex items-center justify-end gap-4 mt-10">

                <a
                    href="{{ route('superadmin.users.index') }}"
                    class="px-6 py-3 rounded-2xl bg-slate-200 hover:bg-slate-300 transition"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg transition"
                >
                    Update User
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