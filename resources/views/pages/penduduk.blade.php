@extends('layouts.app')

@section('title', 'Data Anggota Keluarga')

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
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- KK Info Card -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Data Kartu Keluarga</h2>
                        <div class="mt-1 text-sm text-gray-600">
                            <p>Nomor KK: {{ $kartuKeluarga->nomor_kk }}</p>
                            <p>Kepala Keluarga: {{ $kartuKeluarga->kepalaKeluarga?->nama ?? 'Belum ada' }}</p>
                            <p>Tanggal Pembuatan: {{ $kartuKeluarga->tanggal_pembuatan->isoFormat('D MMMM Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('kartu-keluarga.index') }}"
                        class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span>Kembali ke Daftar KK</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Data Anggota Keluarga</h1>
                <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-4">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('penduduk.index', $kartuKeluarga->id_kk) }}"
                        class="w-full sm:w-auto">
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" name="search" value="{{ $search ?? '' }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    placeholder="Cari nama/NIK...">
                                @if ($search)
                                    <a href="{{ route('penduduk.index', $kartuKeluarga->id_kk) }}"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    @can('create-penduduk')
                        <button onclick="createModal.showModal()"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Anggota
                        </button>
                    @endcan
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIK
                                    </th>
                                    <th
                                        class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="hidden xl:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pendidikan
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($penduduk as $item)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $item->nama }}</p>
                                            <div class="sm:hidden mt-1 space-y-1.5">
                                                <div class="text-xs text-gray-500">NIK: {{ $item->nik }}</div>
                                                <div class="text-xs text-gray-500 capitalize">
                                                    Status: {{ str_replace('_', ' ', $item->status_keluarga) }}
                                                </div>
                                                <div class="text-xs text-gray-500 uppercase">
                                                    Pendidikan: {{ $item->pendidikan }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900">{{ $item->nik }}</p>
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900 capitalize">
                                                {{ str_replace('_', ' ', $item->status_keluarga) }}
                                            </p>
                                        </td>
                                        <td class="hidden xl:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900 uppercase">
                                                {{ $item->pendidikan }}
                                            </p>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                @can('edit-penduduk')
                                                    <button onclick='editPenduduk(@json($item))'
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

                                                @can('delete-penduduk')
                                                    <button onclick="deletePenduduk('{{ $item->id_penduduk }}')"
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
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada anggota keluarga
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
                <h3 class="text-lg font-bold">Tambah Anggota Keluarga</h3>
                <button type="button" onclick="createModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('penduduk.store', $kartuKeluarga->id_kk) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" maxlength="16" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="16 digit NIK">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required max="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status dalam Keluarga</label>
                        <select name="status_keluarga" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Status</option>
                            @foreach (App\Models\Penduduk::STATUS_KELUARGA as $status)
                                <option value="{{ $status }}">{{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                        <select name="pendidikan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Pendidikan</option>
                            @foreach (App\Models\Penduduk::PENDIDIKAN as $pendidikan)
                                <option value="{{ $pendidikan }}">{{ strtoupper($pendidikan) }}</option>
                            @endforeach
                        </select>
                    </div>
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
                <h3 class="text-lg font-bold">Edit Anggota Keluarga</h3>
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
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" id="edit_nik" maxlength="16" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="16 digit NIK">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" required
                            max="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="edit_alamat" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status dalam Keluarga</label>
                        <select name="status_keluarga" id="edit_status_keluarga" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Status</option>
                            @foreach (App\Models\Penduduk::STATUS_KELUARGA as $status)
                                <option value="{{ $status }}">{{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                        <select name="pendidikan" id="edit_pendidikan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Pilih Pendidikan</option>
                            @foreach (App\Models\Penduduk::PENDIDIKAN as $pendidikan)
                                <option value="{{ $pendidikan }}">{{ strtoupper($pendidikan) }}</option>
                            @endforeach
                        </select>
                    </div>
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
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus data anggota keluarga ini? Tindakan ini tidak
                dapat dibatalkan.</p>
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
            function editPenduduk(penduduk) {
                document.getElementById('editForm').action =
                `/kartu-keluarga/${penduduk.id_kk}/anggota/${penduduk.id_penduduk}`;
                document.getElementById('edit_nik').value = penduduk.nik;
                document.getElementById('edit_nama').value = penduduk.nama;
                document.getElementById('edit_tanggal_lahir').value = penduduk.tanggal_lahir.split('T')[0];
                document.getElementById('edit_alamat').value = penduduk.alamat;
                document.getElementById('edit_status_keluarga').value = penduduk.status_keluarga;
                document.getElementById('edit_pendidikan').value = penduduk.pendidikan;
                editModal.showModal();
            }

            function deletePenduduk(pendudukId) {
                const kartuKeluargaId = @json($kartuKeluarga->id_kk);
                document.getElementById('deleteForm').action = `/kartu-keluarga/${kartuKeluargaId}/anggota/${pendudukId}`;
                deleteModal.showModal();
            }

            // Handle alerts with better styling
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
