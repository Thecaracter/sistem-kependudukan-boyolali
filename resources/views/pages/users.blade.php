@extends('layouts.app')

@section('title', 'Manajemen User')

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
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Manajemen User</h1>
                @can('create-users')
                    <button onclick="createModal.showModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
                    </button>
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
                                        Username
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th
                                        class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Dibuat
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if ($user->foto)
                                                    <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg object-cover"
                                                        src="{{ asset('fotoProfile/' . $user->foto) }}"
                                                        alt="{{ $user->username }}">
                                                @else
                                                    <div
                                                        class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <span class="text-xs sm:text-sm font-semibold text-gray-600">
                                                            {{ strtoupper(substr($user->username, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="ml-2 sm:ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->username }}
                                                    </div>
                                                    <!-- Mobile: Show role here -->
                                                    <div class="sm:hidden mt-1">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                            {{ $user->getRoleNames()->first() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                {{ $user->getRoleNames()->first() }}
                                            </span>
                                        </td>
                                        <td
                                            class="hidden lg:table-cell px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                @can('edit-users')
                                                    <button
                                                        data-user="{{ json_encode([
                                                            'id' => $user->id_pengguna,
                                                            'username' => $user->username,
                                                            'role' => $user->getRoleNames()->first(),
                                                            'foto' => $user->foto,
                                                        ]) }}"
                                                        onclick="editUser(JSON.parse(this.dataset.user))"
                                                        class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        <span class="hidden sm:inline font-medium">Edit</span>
                                                    </button>
                                                @endcan

                                                @can('delete-users')
                                                    <button onclick="deleteUser('{{ $user->id_pengguna }}')"
                                                        class="inline-flex items-center p-1 sm:px-3 sm:py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="hidden sm:inline font-medium">Hapus</span>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
                <h3 class="text-lg font-bold">Tambah User Baru</h3>
                <button type="button" onclick="createModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto</label>
                        <input type="file" name="foto" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-primary-50 file:text-primary-700
                                  hover:file:bg-primary-100">
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
                <h3 class="text-lg font-bold">Edit User</h3>
                <button type="button" onclick="editModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="edit_username" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="edit_role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto</label>
                        <input type="file" name="foto" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-primary-50 file:text-primary-700
                                  hover:file:bg-primary-100">
                        <div id="current_photo" class="mt-2"></div>
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
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.
            </p>
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
            function editUser(userData) {
                document.getElementById('editForm').action = `/users/${userData.id}`;
                document.getElementById('edit_username').value = userData.username;
                document.getElementById('edit_role').value = userData.role;

                const currentPhotoDiv = document.getElementById('current_photo');
                if (userData.foto) {
                    currentPhotoDiv.innerHTML = `
                <img src="/fotoProfile/${userData.foto}" alt="Current photo" 
                     class="h-20 w-20 object-cover rounded-lg">
                <p class="text-sm text-gray-500 mt-1">Foto saat ini</p>
            `;
                } else {
                    currentPhotoDiv.innerHTML = '';
                }

                editModal.showModal();
            }

            function deleteUser(userId) {
                document.getElementById('deleteForm').action = `/users/${userId}`;
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
