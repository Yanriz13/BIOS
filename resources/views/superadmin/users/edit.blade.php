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

                        <option value="direksi"
                            {{ $user->role == 'direksi' ? 'selected' : '' }}>
                            Direksi
                        </option>

                        <option value="manager"
                            {{ $user->role == 'manager' ? 'selected' : '' }}>
                            Manager
                        </option>

                        <option value="staff"
                            {{ $user->role == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>

                    </select>

                </div>

                {{-- DIVISI --}}
                <div>

                    <label class="font-bold text-slate-700">
                        Divisi
                    </label>

                    <select
                        name="divisi"
                        class="w-full mt-2 border border-slate-300 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                        <option value="IT"
                            {{ $user->divisi == 'IT' ? 'selected' : '' }}>
                            IT
                        </option>

                        <option value="HRD"
                            {{ $user->divisi == 'HRD' ? 'selected' : '' }}>
                            HRD
                        </option>

                        <option value="Finance"
                            {{ $user->divisi == 'Finance' ? 'selected' : '' }}>
                            Finance
                        </option>

                        <option value="Purchasing"
                            {{ $user->divisi == 'Purchasing' ? 'selected' : '' }}>
                            Purchasing
                        </option>

                    </select>

                </div>

                {{-- PASSWORD --}}
                <div class="md:col-span-2">

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

@endsection