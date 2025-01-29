<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Desa;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Roles 
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',

            // Penduduk
            'view-penduduk',
            'create-penduduk',
            'edit-penduduk',
            'delete-penduduk',

            // Kartu Keluarga
            'view-kartu-keluarga',
            'create-kartu-keluarga',
            'edit-kartu-keluarga',
            'delete-kartu-keluarga',

            // Identitas Rumah
            'view-identitas-rumah',
            'create-identitas-rumah',
            'edit-identitas-rumah',
            'delete-identitas-rumah',
            'download-qr-code',

            // Verifikasi
            'verify-documents',
            'view-verifications',

            // QR Scanner
            'scan-qr',

            // Laporan
            'create-reports',
            'export-data',

            //desa
            'view-desa',
            'create-desa',
            'edit-desa',
            'delete-desa',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create desa
        $desas = [
            ['nama_desa' => 'Desa Sukamaju'],
            ['nama_desa' => 'Desa Sidomukti'],
            ['nama_desa' => 'Desa Harapan'],
            ['nama_desa' => 'Desa Sejahtera'],
            ['nama_desa' => 'Desa Makmur']
        ];

        $created_desas = [];
        foreach ($desas as $desa_data) {
            $created_desas[] = Desa::create([
                'nama_desa' => $desa_data['nama_desa']
            ]);
        }

        // Create Roles and Default Users 
        $roles = [
            'Admin' => [
                'permissions' => Permission::all(),
                'users' => [
                    [
                        'username' => 'admin',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'admin2',
                        'password' => 'password'
                    ]
                ]
            ],
            'Operator Desa' => [
                'permissions' => [
                    'view-penduduk',
                    'create-penduduk',
                    'edit-penduduk',
                    'view-kartu-keluarga',
                    'create-kartu-keluarga',
                    'edit-kartu-keluarga',
                    'view-identitas-rumah',
                    'create-identitas-rumah',
                    'edit-identitas-rumah',
                    'download-qr-code',
                    'scan-qr',
                    'create-reports',
                    'export-data'
                ],
                'create_per_desa' => true,
                'prefix' => 'opdesa'
            ],
            'Operator Kecamatan' => [
                'permissions' => [
                    'view-penduduk',
                    'create-penduduk',
                    'edit-penduduk',
                    'view-kartu-keluarga',
                    'create-kartu-keluarga',
                    'edit-kartu-keluarga',
                    'view-identitas-rumah',
                    'create-identitas-rumah',
                    'edit-identitas-rumah',
                    'download-qr-code',
                    'scan-qr',
                    'create-reports',
                    'export-data'
                ],
                'users' => [
                    [
                        'username' => 'opkec1',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'opkec2',
                        'password' => 'password'
                    ]
                ]
            ],
            'Validator Desa' => [
                'permissions' => [
                    'view-penduduk',
                    'view-kartu-keluarga',
                    'view-identitas-rumah',
                    'download-qr-code',
                    'verify-documents',
                    'view-verifications',
                    'scan-qr',
                    'create-reports',
                ],
                'create_per_desa' => true,
                'prefix' => 'validator'
            ],
            'Camat' => [
                'permissions' => [
                    'view-penduduk',
                    'view-kartu-keluarga',
                    'view-identitas-rumah',
                    'download-qr-code',
                    'scan-qr',
                    'create-reports',
                    'view-verifications'
                ],
                'users' => [
                    [
                        'username' => 'camat1',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'camat2',
                        'password' => 'password'
                    ]
                ]
            ],
            'Kades' => [
                'permissions' => [
                    'view-penduduk',
                    'view-kartu-keluarga',
                    'view-identitas-rumah',
                    'download-qr-code',
                    'scan-qr',
                    'create-reports',
                    'view-verifications'
                ],
                'create_per_desa' => true,
                'prefix' => 'kades'
            ]
        ];

        // Create roles and users
        foreach ($roles as $roleName => $roleData) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($roleData['permissions']);

            if (isset($roleData['create_per_desa']) && $roleData['create_per_desa']) {
                // Create users for each desa
                foreach ($created_desas as $desa) {
                    $username = $roleData['prefix'] . '_' . strtolower(str_replace(' ', '', $desa->nama_desa)) . '1';
                    $user = User::create([
                        'username' => $username,
                        'password' => Hash::make('password'),
                        'id_desa' => $desa->id
                    ]);
                    $user->assignRole($roleName);
                }
            } elseif (isset($roleData['users'])) {
                // Create regular users without desa
                foreach ($roleData['users'] as $userData) {
                    $user = User::create([
                        'username' => $userData['username'],
                        'password' => Hash::make($userData['password'])
                    ]);
                    $user->assignRole($roleName);
                }
            }
        }
    }
}