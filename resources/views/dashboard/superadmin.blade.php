@extends('layouts.app')

@section('content')
    <!-- CONTENT -->
    <div class="flex-1 p-8">

        <!-- CARD -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <h2 class="text-gray-500 text-sm">
                    Total User
                </h2>

                <h1 class="text-4xl font-bold text-gray-800 mt-3">
                    150
                </h1>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                <h2 class="text-gray-500 text-sm">
                    Total Divisi
                </h2>

                <h1 class="text-4xl font-bold text-gray-800 mt-3">
                    12
                </h1>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <h2 class="text-gray-500 text-sm">
                    Total Manager
                </h2>

                <h1 class="text-4xl font-bold text-gray-800 mt-3">
                    25
                </h1>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <h2 class="text-gray-500 text-sm">
                    Total Staff
                </h2>

                <h1 class="text-4xl font-bold text-gray-800 mt-3">
                    100
                </h1>
            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b">

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Data User
                    </h2>

                    <p class="text-gray-500 mt-1">
                        Seluruh user berdasarkan role dan divisi
                    </p>
                </div>

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold transition duration-300">
                    + Tambah User
                </button>
            </div>

            <div class="overflow-x-auto">
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

                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4 font-semibold">Budi Santoso</td>
                            <td class="px-6 py-4">budi@gmail.com</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                    Manager
                                </span>
                            </td>
                            <td class="px-6 py-4">IT</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">
                                    Edit
                                </button>

                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4 font-semibold">Andi Saputra</td>
                            <td class="px-6 py-4">andi@gmail.com</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    Staff
                                </span>
                            </td>
                            <td class="px-6 py-4">HRD</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">
                                    Edit
                                </button>

                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    </div>
@endsection