@extends('layouts.app')

@section('content')

<div class="container mx-auto py-10">

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl mx-auto">

        <h1 class="text-3xl font-bold mb-2">
            Tambah User
        </h1>

        <p class="text-gray-500 mb-8">
            Tambahkan akun user baru
        </p>

        <form
            action="{{ route('superadmin.users.store') }}"
            method="POST"
        >
            @csrf

            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="font-semibold">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="w-full border rounded-xl px-4 py-3 mt-2"
                    >
                </div>

                <div>
                    <label class="font-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="w-full border rounded-xl px-4 py-3 mt-2"
                    >
                </div>

                <div>
                    <label class="font-semibold">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full border rounded-xl px-4 py-3 mt-2"
                    >
                        <option value="direksi">Direksi</option>

                        <option value="manager">Manager</option>

                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold">
                        Divisi
                    </label>

                    <select
                        name="divisi"
                        class="w-full border rounded-xl px-4 py-3 mt-2"
                    >
                        <option value="IT">IT</option>

                        <option value="HRD">HRD</option>

                        <option value="Finance">Finance</option>

                        <option value="Purchasing">Purchasing</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="font-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded-xl px-4 py-3 mt-2"
                    >
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">

                <a
                    href="{{ route('superadmin.users.index') }}"
                    class="bg-gray-200 px-5 py-3 rounded-xl"
                >
                    Kembali
                </a>

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl"
                >
                    Simpan User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection