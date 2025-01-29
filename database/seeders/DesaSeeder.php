<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        // Data desa
        $desas = [
            ['nama_desa' => 'Banyuanyar'],
            ['nama_desa' => 'Candi'],
            ['nama_desa' => 'Gondang Slamet'],
            ['nama_desa' => 'Ngampon'],
            ['nama_desa' => 'Ngargosari'],
            ['nama_desa' => 'Ngenden'],
            ['nama_desa' => 'Selodoko'],
            ['nama_desa' => 'Sidomulyo'],
            ['nama_desa' => 'Tanduk'],
            ['nama_desa' => 'Urutsewu']
        ];

        foreach ($desas as $desa_data) {
            // Buat desa
            Desa::create([
                'nama_desa' => $desa_data['nama_desa']
            ]);
        }
    }
}