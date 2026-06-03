@extends('layouts.app')

@section('content')

    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition duration-300">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Total User
                        </p>

                        <h2 class="text-4xl font-bold text-slate-800 mt-3">
                            {{ $totalUsers }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5V4H2v16h5m10 0v-5a3 3 0 00-6 0v5m6 0H8" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition duration-300">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Total Direksi
                        </p>

                        <h2 class="text-4xl font-bold text-slate-800 mt-3">
                            {{ $totalDireksi }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition duration-300">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Total Manager
                        </p>

                        <h2 class="text-4xl font-bold text-slate-800 mt-3">
                            {{ $totalManager }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-yellow-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3-1.343 3-3 3-3-1.343-3-3zm-6 8v-1a4 4 0 014-4h4a4 4 0 014 4v1" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition duration-300">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Total Staff
                        </p>

                        <h2 class="text-4xl font-bold text-slate-800 mt-3">
                            {{ $totalStaff }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A8 8 0 1118.88 6.196 8 8 0 015.12 17.804z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>
        <div class="flex justify-between items-center mb-6">



            <a href="{{ route('superadmin.users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">
                + Tambah User
            </a>

        </div>

        @if(session('success'))

            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5">

                {{ session('success') }}

            </div>

        @endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full">

                <thead class="bg-slate-900 text-white">

                    <tr>

                        <th class="px-6 py-4 text-left">No</th>

                        <th class="px-6 py-4 text-left">Nama</th>

                        <th class="px-6 py-4 text-left">Email</th>

                        <th class="px-6 py-4 text-left">Role</th>

                        <th class="px-6 py-4 text-left">Divisi</th>

                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-6 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-6 py-4 font-semibold">

                                {{ $user->name }}

                            </td>

                            <td class="px-6 py-4">

                                {{ $user->email }}

                            </td>

                            <td class="px-6 py-4">

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                                    {{ $user->role }}

                                </span>

                            </td>

                            <td class="px-6 py-4">

                                {{ $user->divisi }}

                            </td>

                            <td class="px-6 py-4 text-center space-x-2">

                                <a href="{{ route('superadmin.users.edit', $user->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">
                                    Edit
                                </a>

                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Hapus user ini?')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection