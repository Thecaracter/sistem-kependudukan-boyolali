@extends('layouts.app')

@section('title', 'Data Rumah')

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
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Data Rumah</h1>
                @can('create-identitas-rumah')
                    @if (request('kk') && !$rumah->first())
                        <button onclick="createModal.showModal()"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Data Rumah
                        </button>
                    @endif
                @endcan
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        QR Code
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alamat
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kamar
                                    </th>
                                    <th
                                        class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe Bangunan
                                    </th>
                                    <th
                                        class="hidden xl:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        KK Aktif
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rumah as $item)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4">
                                            <img src="{{ asset('qrcodes/' . $item->id_rumah . '.png') }}" alt="QR Code"
                                                class="h-10 w-10 sm:h-12 sm:w-12">
                                        </td>
                                        <td class="px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900">{{ $item->alamat_rumah }}</p>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900">
                                                <span class="font-medium">Tidur:</span> {{ $item->jumlah_kamar_tidur }}<br>
                                                <span class="font-medium">Mandi:</span> {{ $item->jumlah_kamar_mandi }}
                                            </p>
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-6 py-4">
                                            <p class="text-sm text-gray-900">
                                                <span class="font-medium">Lantai:</span>
                                                {{ Str::title($item->tipe_lantai) }}<br>
                                                <span class="font-medium">Atap:</span> {{ Str::title($item->atap) }}
                                            </p>
                                        </td>
                                        <td class="hidden xl:table-cell px-3 sm:px-6 py-4">
                                            @if ($item->kartuKeluargaAktif)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $item->kartuKeluargaAktif->nomor_kk }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                <a href="{{ route('identitas-rumah.download', $item->id_rumah) }}"
                                                    class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    <span class="hidden sm:inline">QR</span>
                                                </a>

                                                @can('edit-identitas-rumah')
                                                    <button onclick='editRumah(@json($item))'
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
                                                @can('delete-identitas-rumah')
                                                    <button onclick="deleteRumah('{{ $item->id_rumah }}')"
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
                                            Belum ada data rumah
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
                <h3 class="text-lg font-bold">Tambah Data Rumah</h3>
                <button type="button" onclick="createModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('identitas-rumah.store') }}">
                @csrf
                <input type="hidden" name="kk_id" value="{{ $kkId }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Rumah</label>
                        <input type="text" name="alamat_rumah" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Kamar Tidur</label>
                            <input type="number" name="jumlah_kamar_tidur" required min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Kamar Mandi</label>
                            <input type="number" name="jumlah_kamar_mandi" required min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Lantai</label>
                            <select name="tipe_lantai" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Tipe Lantai</option>
                                <option value="keramik">Keramik</option>
                                <option value="ubin">Ubin</option>
                                <option value="kayu">Kayu</option>
                                <option value="tanah">Tanah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Atap</label>
                            <select name="atap" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Tipe Atap</option>
                                <option value="genteng">Genteng</option>
                                <option value="asbes">Asbes</option>
                                <option value="seng">Seng</option>
                                <option value="jerami">Jerami</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
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
                <h3 class="text-lg font-bold">Edit Data Rumah</h3>
                <button type="button" onclick="editModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="kk_id" value="{{ request('kk') }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Rumah</label>
                        <input type="text" name="alamat_rumah" id="edit_alamat_rumah" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Kamar Tidur</label>
                            <input type="number" name="jumlah_kamar_tidur" id="edit_jumlah_kamar_tidur" required
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Kamar Mandi</label>
                            <input type="number" name="jumlah_kamar_mandi" id="edit_jumlah_kamar_mandi" required
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Lantai</label>
                            <select name="tipe_lantai" id="edit_tipe_lantai" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Tipe Lantai</option>
                                <option value="keramik">Keramik</option>
                                <option value="ubin">Ubin</option>
                                <option value="kayu">Kayu</option>
                                <option value="tanah">Tanah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Atap</label>
                            <select name="atap" id="edit_atap" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Tipe Atap</option>
                                <option value="genteng">Genteng</option>
                                <option value="asbes">Asbes</option>
                                <option value="seng">Seng</option>
                                <option value="jerami">Jerami</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
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
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus data rumah ini? Tindakan ini tidak dapat
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
            function editRumah(rumahData) {
                document.getElementById('editForm').action = `/identitas-rumah/${rumahData.id_rumah}`;
                document.getElementById('edit_alamat_rumah').value = rumahData.alamat_rumah;
                document.getElementById('edit_jumlah_kamar_tidur').value = rumahData.jumlah_kamar_tidur;
                document.getElementById('edit_jumlah_kamar_mandi').value = rumahData.jumlah_kamar_mandi;
                document.getElementById('edit_tipe_lantai').value = rumahData.tipe_lantai;
                document.getElementById('edit_atap').value = rumahData.atap;

                editModal.showModal();
            }

            function deleteRumah(rumahId) {
                document.getElementById('deleteForm').action = `/identitas-rumah/${rumahId}`;
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

            @if ($errors->any())
                const errorMessages = @json($errors->all());
                const errorDiv = document.createElement('div');
                errorDiv.className =
                    'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
                errorDiv.role = 'alert';
                errorDiv.innerHTML = `
            <div class="flex">
                <span class="mr-2">⚠</span>
                <span>${errorMessages.join('<br>')}</span>
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
