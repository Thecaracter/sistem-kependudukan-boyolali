<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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

            //roles
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

            // Verifikasi
            'verify-documents',
            'view-verifications',

            // Laporan
            'view-reports',
            'create-reports',
            'export-data'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
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
                    'view-reports',
                    'export-data'
                ],
                'users' => [
                    [
                        'username' => 'opdesa1',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'opdesa2',
                        'password' => 'password'
                    ]
                ]
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
                    'view-reports',
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
                    'verify-documents',
                    'view-verifications',
                    'view-reports'
                ],
                'users' => [
                    [
                        'username' => 'validator1',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'validator2',
                        'password' => 'password'
                    ]
                ]
            ],
            'Camat' => [
                'permissions' => [
                    'view-penduduk',
                    'view-kartu-keluarga',
                    'view-identitas-rumah',
                    'view-reports',
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
                    'view-reports',
                    'view-verifications'
                ],
                'users' => [
                    [
                        'username' => 'kades1',
                        'password' => 'password'
                    ],
                    [
                        'username' => 'kades2',
                        'password' => 'password'
                    ]
                ]
            ]
        ];

        foreach ($roles as $roleName => $roleData) {
            // Create Role
            $role = Role::create(['name' => $roleName]);

            // Assign Permissions
            if ($roleName === 'Admin') {
                $role->givePermissionTo($roleData['permissions']);
            } else {
                $role->givePermissionTo($roleData['permissions']);
            }

            // Create Users for this role
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