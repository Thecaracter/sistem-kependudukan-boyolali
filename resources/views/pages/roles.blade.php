@extends('layouts.app')

@section('title', 'Role & Permission')

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

        .group-permissions {
            border: 1px solid #e5e7eb;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .group-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        @media (max-width: 640px) {
            .permissions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Role & Permission</h1>
                @can('create-roles')
                    <button onclick="createModal.showModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Role
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
                                        Nama Role
                                    </th>
                                    <th
                                        class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Permissions
                                    </th>
                                    <th
                                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                            <!-- Mobile: Show permissions here -->
                                            <div class="sm:hidden mt-2">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($role->permissions as $permission)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                            {{ $permission->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($role->permissions as $permission)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($role->name !== 'Admin')
                                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                                    @can('edit-roles')
                                                        <button
                                                            onclick="editRole({
                                                            id: '{{ $role->id }}',
                                                            name: '{{ $role->name }}',
                                                            permissions: {{ $role->permissions->pluck('name') }}
                                                        })"
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

                                                    @can('delete-roles')
                                                        <button onclick="deleteRole('{{ $role->id }}')"
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
                                            @endif
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
                <h3 class="text-lg font-bold">Tambah Role Baru</h3>
                <button type="button" onclick="createModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                        <input type="text" name="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                            <!-- Role Management -->
                            <div class="group-permissions">
                                <div class="group-title">Role Management</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'roles')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- User Management -->
                            <div class="group-permissions">
                                <div class="group-title">User Management</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'users')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Penduduk -->
                            <div class="group-permissions">
                                <div class="group-title">Data Penduduk</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'penduduk')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kartu Keluarga -->
                            <div class="group-permissions">
                                <div class="group-title">Kartu Keluarga</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'kartu-keluarga')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Identitas Rumah -->
                            <div class="group-permissions">
                                <div class="group-title">Identitas Rumah</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'identitas-rumah') || str_contains($p->name, 'qr-code')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Verifikasi -->
                            <div class="group-permissions">
                                <div class="group-title">Verifikasi</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'verify-documents')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Laporan -->
                            <div class="group-permissions">
                                <div class="group-title">Laporan</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'reports')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- QR Scanner -->
                            <div class="group-permissions">
                                <div class="group-title">QR Scanner</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'scan-qr') || str_contains($p->name, 'export-scan')) as $permission)
                                        <div class="flex items-center">
                                            <!-- Untuk Modal Create -->
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="create_{{ $permission->id }}"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="create_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
                <h3 class="text-lg font-bold">Edit Role</h3>
                <button type="button" onclick="editModal.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokelinecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                        <input type="text" name="name" id="edit_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                            <!-- Role Management -->
                            <div class="group-permissions">
                                <div class="group-title">Role Management</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'roles')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- User Management -->
                            <div class="group-permissions">
                                <div class="group-title">User Management</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'users')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Penduduk -->
                            <div class="group-permissions">
                                <div class="group-title">Data Penduduk</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'penduduk')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kartu Keluarga -->
                            <div class="group-permissions">
                                <div class="group-title">Kartu Keluarga</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'kartu-keluarga')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Identitas Rumah -->
                            <div class="group-permissions">
                                <div class="group-title">Identitas Rumah</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'identitas-rumah') || str_contains($p->name, 'qr-code')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Verifikasi -->
                            <div class="group-permissions">
                                <div class="group-title">Verifikasi</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'verify-documents')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Laporan -->
                            <div class="group-permissions">
                                <div class="group-title">Laporan</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'reports')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Scan QR -->
                            <div class="group-permissions">
                                <div class="group-title">QR Scanner</div>
                                <div class="permissions-grid">
                                    @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'scan-qr') || str_contains($p->name, 'export-scan')) as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="edit_{{ $permission->id }}"
                                                class="permission-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label for="edit_{{ $permission->id }}"
                                                class="ml-2 block text-sm text-gray-900">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus role ini? Tindakan ini tidak dapat dibatalkan.
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
            function editRole(roleData) {
                document.getElementById('editForm').action = `/roles/${roleData.id}`;
                document.getElementById('edit_name').value = roleData.name;

                // Reset all checkboxes
                document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check the permissions that the role has
                roleData.permissions.forEach(permission => {
                    const checkbox = document.querySelector(
                        `input[type="checkbox"][value="${permission}"].permission-checkbox`);
                    if (checkbox) checkbox.checked = true;
                });

                editModal.showModal();
            }

            function deleteRole(roleId) {
                document.getElementById('deleteForm').action = `/roles/${roleId}`;
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
