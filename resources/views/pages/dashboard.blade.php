@extends('layouts.app')

@section('title', 'Dashboard - SIDESPIN')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-secondary-800">Selamat Datang, {{ Auth::user()->username }}!</h2>
            <p class="mt-2 text-secondary-600">Anda login sebagai {{ Auth::user()->getRoleNames()->first() }}</p>
        </div>

        <!-- Access Menu Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Data Penduduk Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-secondary-800 mb-4">Data Penduduk</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Data Penduduk
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Data Penduduk
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Penduduk
                    </a>
                </div>
            </div>

            <!-- Kartu Keluarga Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-secondary-800 mb-4">Kartu Keluarga</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Kartu Keluarga
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Kartu Keluarga
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Kartu Keluarga
                    </a>
                </div>
            </div>

            <!-- Identitas Rumah Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-secondary-800 mb-4">Identitas Rumah</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Lihat Data Rumah
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Data Rumah
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Rumah
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Reports Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-secondary-800 mb-4">Laporan</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Laporan
                    </a>
                    <a href="#" class="flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Data
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
