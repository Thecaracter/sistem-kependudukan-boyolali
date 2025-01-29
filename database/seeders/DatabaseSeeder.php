<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                // DesaSeeder::class,
            RoleAndPermissionSeeder::class,
            IdentitasRumahSeeder::class,
            KartuKeluargaSeeder::class,
            PendudukSeeder::class,
            VerifikasiPendudukSeeder::class,
        ]);
    }
}
