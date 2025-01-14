@extends('layouts.app')

@section('title', 'Verifikasi Data Keluarga')

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
            <div class="p-4 sm:p-6">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Verifikasi Data Keluarga</h1>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No KK
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kepala Keluarga
                                    </th>
                                    <th
                                        class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Pengajuan
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($verifikasi as $index => $v)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $verifikasi->firstItem() + $index }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $v->kartuKeluarga->nomor_kk ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $v->kartuKeluarga->kepalaKeluarga->nama ?? '-' }}
                                            </div>
                                        </td>
                                        <td
                                            class="hidden lg:table-cell px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $v->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                <button onclick="showDetailModal('{{ $v->id_verifikasi }}')"
                                                    class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Detail</span>
                                                </button>

                                                @can('verify-documents')
                                                    <form action="{{ route('verifikasi.approve', $v->id_verifikasi) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-lg transition-colors duration-200">
                                                            <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span class="hidden sm:inline font-medium">Setuju</span>
                                                        </button>
                                                    </form>

                                                    <button onclick="showRejectModal('{{ $v->id_verifikasi }}')"
                                                        class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span class="hidden sm:inline font-medium">Tolak</span>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 sm:px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data yang perlu diverifikasi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="px-3 sm:px-6 py-4">
                {{ $verifikasi->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <dialog id="detailModal">
        <div class="modal-box max-w-5xl bg-white rounded-xl shadow-2xl">
            <!-- Header Modal -->
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary-100 rounded-lg">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Detail Data Keluarga</h3>
                </div>
                <button type="button" onclick="detailModal.close()"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <!-- KK Info -->
                <div class="bg-white p-5 rounded-xl border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="p-1.5 bg-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        Informasi KK
                    </h4>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-500 text-sm">Nomor KK</span>
                            <div class="font-semibold text-gray-900 mt-1" id="detail_nomor_kk"></div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-500 text-sm">Tanggal Pembuatan</span>
                            <div class="font-semibold text-gray-900 mt-1" id="detail_tanggal_pembuatan"></div>
                        </div>
                    </div>
                </div>

                <!-- Anggota Keluarga -->
                <div class="bg-white p-5 rounded-xl border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="p-1.5 bg-green-100 rounded-lg">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        Anggota Keluarga
                    </h4>
                    <div class="relative overflow-hidden rounded-xl border border-gray-200">
                        <div class="overflow-x-auto max-h-[300px]">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NIK</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Lahir</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pendidikan</th>
                                    </tr>
                                </thead>
                                <tbody id="detail_anggota_keluarga" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Identitas Rumah -->
                <div class="bg-white p-5 rounded-xl border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="p-1.5 bg-purple-100 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        Identitas Rumah
                    </h4>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4" id="detail_identitas_rumah">
                    </div>
                </div>
            </div>
        </div>
    </dialog>
    <!-- Reject Modal -->
    <dialog id="rejectModal">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Alasan Penolakan</h3>
                <button type="button" onclick="rejectModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" required rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="rejectModal.close()"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    @push('scripts')
        <script>
            function showDetailModal(id) {
                const verifikasi = @json($verifikasi);
                const data = verifikasi.data.find(v => v.id_verifikasi === id);

                if (data) {
                    // Set KK Info
                    document.getElementById('detail_nomor_kk').textContent = data.kartu_keluarga.nomor_kk;
                    document.getElementById('detail_tanggal_pembuatan').textContent = new Date(data.kartu_keluarga
                        .tanggal_pembuatan).toLocaleDateString('id-ID');

                    // Set Anggota Keluarga
                    const anggotaTable = document.getElementById('detail_anggota_keluarga');
                    anggotaTable.innerHTML = '';
                    data.kartu_keluarga.anggota_keluarga.forEach(anggota => {
                        const status = anggota.status_keluarga.split('_').map(word =>
                            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                        ).join(' ');

                        const pendidikan = anggota.pendidikan.toUpperCase();

                        anggotaTable.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">${anggota.nik}</td>
                        <td class="px-4 py-3 text-sm font-medium">${anggota.nama}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                ${anggota.status_keluarga === 'kepala_keluarga' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">
                                ${status}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">${new Date(anggota.tanggal_lahir).toLocaleDateString('id-ID')}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                ${pendidikan}
                            </span>
                        </td>
                    </tr>
                `;
                    });

                    // Set Identitas Rumah
                    const rumahDiv = document.getElementById('detail_identitas_rumah');
                    const rumah = data.kartu_keluarga.identitas_rumah;
                    if (rumah) {
                        const tipe_lantai = rumah.tipe_lantai.split('_').map(word =>
                            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                        ).join(' ');

                        const atap = rumah.atap.split('_').map(word =>
                            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                        ).join(' ');

                        rumahDiv.innerHTML = `
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <span class="block text-gray-500 mb-1">Alamat:</span>
                        <span class="font-medium text-gray-900">${rumah.alamat_rumah}</span>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <span class="block text-gray-500 mb-1">Tipe Lantai:</span>
                        <span class="font-medium text-gray-900">${tipe_lantai}</span>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <span class="block text-gray-500 mb-1">Jumlah Kamar Tidur:</span>
                        <span class="font-medium text-gray-900">${rumah.jumlah_kamar_tidur} Kamar</span>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <span class="block text-gray-500 mb-1">Jumlah Kamar Mandi:</span>
                        <span class="font-medium text-gray-900">${rumah.jumlah_kamar_mandi} Kamar</span>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <span class="block text-gray-500 mb-1">Atap:</span>
                        <span class="font-medium text-gray-900">${atap}</span>
                    </div>
                `;
                    } else {
                        rumahDiv.innerHTML = `
                    <div class="col-span-full">
                        <div class="bg-white p-4 rounded-md shadow-sm text-center">
                            <span class="text-gray-500">Data rumah belum tersedia</span>
                        </div>
                    </div>
                `;
                    }

                    detailModal.showModal();
                }
            }

            function showRejectModal(id) {
                document.getElementById('rejectForm').action = `/verifikasi/${id}/reject`;
                rejectModal.showModal();
            }

            // Handle alerts
            @if (session('success'))
                const successMessage = "{{ session('success') }}";
                const alertDiv = document.createElement('div');
                alertDiv.className =
                    'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50';
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">${successMessage}</span>
                <button class="ml-4 text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
                document.body.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 5000);
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
