@extends('layouts.app')

@section('title', 'Data Kartu Keluarga')

@push('styles')
    <style>
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.5);
        }

        dialog {
            padding: 0;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 42rem;
            width: 90%;
        }

        dialog:focus {
            outline: none;
        }

        .modal-box {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .status-indicator {
            transition: all 0.2s ease-in-out;
        }

        .status-indicator:hover {
            transform: translateY(-1px);
        }

        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .progress-line {
            flex: 1;
            height: 2px;
            position: relative;
        }

        .progress-step {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Status Filter Cards -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('kartu-keluarga.index') }}"
                class="p-4 rounded-xl border transition-all {{ !request('status') ? 'bg-gray-100 border-gray-300' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Total KK</p>
                    <span class="text-lg font-semibold text-gray-900">{{ $statusCount['total'] }}</span>
                </div>
            </a>

            <a href="{{ route('kartu-keluarga.index', ['status' => 'pending']) }}"
                class="p-4 rounded-xl border transition-all {{ request('status') === 'pending' ? 'bg-yellow-50 border-yellow-200' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                    <span class="inline-flex items-center gap-1">
                        <span class="text-lg font-semibold {{ request('status') === 'pending' ? 'text-yellow-600' : 'text-gray-900' }}">
                            {{ $statusCount['pending'] }}
                        </span>
                    </span>
                </div>
            </a>

            <a href="{{ route('kartu-keluarga.index', ['status' => 'verified']) }}"
                class="p-4 rounded-xl border transition-all {{ request('status') === 'verified' ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                    <span class="inline-flex items-center gap-1">
                        <span class="text-lg font-semibold {{ request('status') === 'verified' ? 'text-green-600' : 'text-gray-900' }}">
                            {{ $statusCount['verified'] }}
                        </span>
                    </span>
                </div>
            </a>

            <a href="{{ route('kartu-keluarga.index', ['status' => 'rejected']) }}"
                class="p-4 rounded-xl border transition-all {{ request('status') === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Ditolak</p>
                    <span class="inline-flex items-center gap-1">
                        <span class="text-lg font-semibold {{ request('status') === 'rejected' ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $statusCount['rejected'] }}
                        </span>
                    </span>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm">
            <div class="w-full bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Header Container -->
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                        <!-- Title -->
                        <h1 class="text-2xl font-semibold text-gray-900">Data Kartu Keluarga</h1>
                        
                        <!-- Actions Container -->
                        <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-4">
                            <!-- Export Button -->
                            @can('create-reports')
                            <a href="{{ route('kartu-keluarga.export') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Export Excel
                            </a>
                            @endcan
            
                            <!-- Search Form -->
                            <form method="GET" action="{{ route('kartu-keluarga.index') }}" class="flex-1 sm:flex-none">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" 
                                               name="search" 
                                               value="{{ $search ?? '' }}"
                                               class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10 py-2"
                                               placeholder="Cari no. KK/nama/NIK...">
                                        @if ($search)
                                            <a href="{{ route('kartu-keluarga.index') }}"
                                               class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
            
                            <!-- Add KK Button -->
                            @can('create-kartu-keluarga')
                                <button onclick="createModal.showModal()"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah KK
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor KK
                                    </th>
                                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kepala Keluarga
                                    </th>
                                    <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Pembuatan
                                    </th>
                                    <th class="hidden xl:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alamat
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($kartuKeluarga as $item)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $item->nomor_kk }}</p>
                                            <div class="sm:hidden mt-1 space-y-1.5">
                                                <!-- Nama Kepala Keluarga -->
                                                <div class="text-xs text-gray-500">
                                                    @if ($item->kepalaKeluarga)
                                                        {{ $item->kepalaKeluarga->nama }}
                                                    @else
                                                        <span class="text-gray-400">Belum ada kepala keluarga</span>
                                                    @endif
                                                </div>

                                                <!-- Tanggal Mobile -->
                                                <div class="text-xs text-gray-500">
                                                    {{ $item->tanggal_pembuatan->isoFormat('D MMMM Y') }}
                                                </div>

                                                <!-- Alamat Mobile -->
                                                <div class="text-xs">
                                                    @if ($item->identitasRumah)
                                                        <div class="text-gray-500">
                                                            {{ $item->identitasRumah->alamat_rumah }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                                            @if ($item->kepalaKeluarga)
                                                <p class="text-sm text-gray-900">{{ $item->kepalaKeluarga->nama }}</p>
                                            @else
                                                <span class="text-sm text-gray-500">Belum ada kepala keluarga</span>
                                            @endif
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900">
                                                {{ $item->tanggal_pembuatan->isoFormat('D MMMM Y') }}
                                            </p>
                                        </td>
                                        <td class="hidden xl:table-cell px-3 sm:px-6 py-4">
                                            @can('view-identitas-rumah')
                                                <a href="{{ route('identitas-rumah.index', ['kk' => $item->id_kk]) }}"
                                                    class="text-sm text-primary-600 hover:text-primary-700 hover:underline inline-flex items-center gap-1">
                                                    @if ($item->identitasRumah)
                                                        <span>Edit Alamat</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    @else
                                                        <span>Tambah Alamat</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    @endif
                                                </a>
                                            @endcan
                                        </td>

                                        <td class="px-3 sm:px-6 py-4">
                                            @php
                                                $status = $item->verifikasi->status ?? 'pending';
                                                $statusInfo = [
                                                    'pending' => [
                                                        'bg' => 'bg-yellow-100',
                                                        'text' => 'text-yellow-800',
                                                        'border' => 'border-yellow-200',
                                                        'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>',
                                                    ],
                                                    'verified' => [
                                                        'bg' => 'bg-green-100',
                                                        'text' => 'text-green-800',
                                                        'border' => 'border-green-200',
                                                        'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>',
                                                    ],
                                                    'rejected' => [
                                                        'bg' => 'bg-red-100',
                                                        'text' => 'text-red-800',
                                                        'border' => 'border-red-200',
                                                        'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>',
                                                    ],
                                                ][$status];
                                            @endphp

                                            <div class="flex flex-col gap-1">
                                                <!-- Status Badge -->
                                                <div class="flex items-center gap-2">
                                                    <div class="flex items-center px-2.5 py-1.5 rounded-full border gap-1.5
                                                        {{ $statusInfo['bg'] }} {{ $statusInfo['text'] }} {{ $statusInfo['border'] }}">
                                                        {!! $statusInfo['icon'] !!}
                                                        <span class="text-sm font-medium">{{ ucfirst($status) }}</span>
                                                    </div>
                                                </div>

                                                <!-- Additional Info -->
                                                @if ($item->verifikasi && $item->verifikasi->keterangan)
                                                    <p class="text-xs text-gray-500 line-clamp-1">
                                                        {{ $item->verifikasi->keterangan }}
                                                    </p>
                                                @endif

                                                <!-- Last Updated -->
                                                @if ($item->verifikasi)
                                                    <span class="text-xs text-gray-400">
                                                        {{ $item->verifikasi->updated_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                <!-- Tombol Lihat Anggota -->
                                                <a href="{{ route('penduduk.index', $item->id_kk) }}"
                                                    class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Anggota</span>
                                                </a>

                                                @can('edit-kartu-keluarga')
                                                    <button onclick='editKK(@json($item))'
                                                        class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Edit</span>
                                                    </button>
                                                @endcan

                                                @can('delete-kartu-keluarga')
                                                    <button onclick="deleteKK('{{ $item->id_kk }}')"
                                                        class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Hapus</span>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada data kartu keluarga
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <dialog id="createModal">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Tambah Kartu Keluarga</h3>
                <button type="button" onclick="createModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('kartu-keluarga.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor KK</label>
                        <input type="text" name="nomor_kk" maxlength="16" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="16 digit nomor KK">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pembuatan</label>
                        <input type="date" name="tanggal_pembuatan" required max="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    @role('Admin')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desa</label>
                        <select name="id_desa" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endrole
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="createModal.close()"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="editModal">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Kartu Keluarga</h3>
                <button type="button" onclick="editModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor KK</label>
                        <input type="text" name="nomor_kk" id="edit_nomor_kk" maxlength="16" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="16 digit nomor KK">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pembuatan</label>
                        <input type="date" name="tanggal_pembuatan" id="edit_tanggal_pembuatan" required
                            max="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    @role('Admin')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desa</label>
                        <select name="id_desa" id="edit_id_desa" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endrole
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="editModal.close()"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- Delete Modal -->
<dialog id="deleteModal">
    <div class="modal-box">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Konfirmasi Hapus</h3>
            <button type="button" onclick="deleteModal.close()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus kartu keluarga ini? Tindakan ini tidak dapat
            dibatalkan.</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="deleteModal.close()"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</dialog>

@push('scripts')
    <script>
        // Get all modals
        const createModal = document.getElementById('createModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        function editKK(kartuKeluarga) {
            document.getElementById('editForm').action = `/kartu-keluarga/${kartuKeluarga.id_kk}`;
            document.getElementById('edit_nomor_kk').value = kartuKeluarga.nomor_kk;
            document.getElementById('edit_tanggal_pembuatan').value = kartuKeluarga.tanggal_pembuatan.split('T')[0];
            
            // Isi field desa jika user adalah admin
            const desaSelect = document.getElementById('edit_id_desa');
            if (desaSelect) {
                desaSelect.value = kartuKeluarga.id_desa;
            }
            
            editModal.showModal();
        }

        function deleteKK(kartuKeluargaId) {
            document.getElementById('deleteForm').action = `/kartu-keluarga/${kartuKeluargaId}`;
            deleteModal.showModal();
        }

        // Handle alerts
        @if (session('success'))
            const successMessage = "{{ session('success') }}";
            const alertDiv = document.createElement('div');
            alertDiv.className =
                'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
               <div class="flex">
                   <span class="mr-2">✓</span>
                   <span>${successMessage}</span>
                   <span class="ml-4 cursor-pointer" onclick="this.parentElement.parentElement.remove()">×</span>
               </div>
           `;
            document.body.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 5000);
        @endif

        @if (session('error'))
            const errorMessage = "{{ session('error') }}";
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
            errorDiv.role = 'alert';
            errorDiv.innerHTML = `
               <div class="flex">
                   <span class="mr-2">⚠</span>
                   <span>${errorMessage}</span>
                   <span class="ml-4 cursor-pointer" onclick="this.parentElement.parentElement.remove()">×</span>
               </div>
           `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 5000);
        @endif

        // Close modal when clicking outside
        document.querySelectorAll('dialog').forEach(dialog => {
            dialog.addEventListener('click', (e) => {
                const dialogDimensions = dialog.getBoundingClientRect();
                if (
                    e.clientX < dialogDimensions.left ||
                    e.clientX > dialogDimensions.right ||
                    e.clientY < dialogDimensions.top ||
                    e.clientY > dialogDimensions.bottom
                ) {
                    dialog.close();
                }
            });
        });

        // Prevent modal content clicks from closing the modal
        document.querySelectorAll('dialog .modal-box').forEach(modalBox => {
            modalBox.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>
@endpush
@endsection