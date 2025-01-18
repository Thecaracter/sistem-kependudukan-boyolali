<?php

namespace App\Exports;

use App\Models\KartuKeluarga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KartuKeluargaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return KartuKeluarga::with([
            'kepalaKeluarga',
            'anggotaKeluarga',
            'identitasRumah',
            'verifikasi'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'No KK',
            'Tanggal Pembuatan',
            'Status Verifikasi',
            'Keterangan Verifikasi',
            'Nama Kepala Keluarga',
            'NIK Kepala Keluarga',
            'Jumlah Anggota',
            'Alamat Rumah',
            'Tipe Lantai',
            'Jumlah Kamar Tidur',
            'Jumlah Kamar Mandi',
            'Tipe Atap',
            'Data Anggota Keluarga'
        ];
    }

    public function map($kk): array
    {
        // Format data anggota keluarga
        $anggota = $kk->anggotaKeluarga->map(function ($anggota) {
            return sprintf(
                "Nama: %s\nNIK: %s\nStatus: %s\nPendidikan: %s",
                $anggota->nama,
                $anggota->nik,
                str_replace('_', ' ', ucwords($anggota->status_keluarga)),
                strtoupper($anggota->pendidikan)
            );
        })->implode("\n\n");

        return [
            $kk->nomor_kk,
            $kk->tanggal_pembuatan->format('d/m/Y'),
            $kk->verifikasi ? ucfirst($kk->verifikasi->status) : 'Belum Verifikasi',
            $kk->verifikasi ? $kk->verifikasi->keterangan : '-',
            $kk->kepalaKeluarga ? $kk->kepalaKeluarga->nama : '-',
            $kk->kepalaKeluarga ? $kk->kepalaKeluarga->nik : '-',
            $kk->anggotaKeluarga->count(),
            $kk->identitasRumah ? $kk->identitasRumah->alamat_rumah : '-',
            $kk->identitasRumah ? ucfirst($kk->identitasRumah->tipe_lantai) : '-',
            $kk->identitasRumah ? $kk->identitasRumah->jumlah_kamar_tidur : '-',
            $kk->identitasRumah ? $kk->identitasRumah->jumlah_kamar_mandi : '-',
            $kk->identitasRumah ? ucfirst($kk->identitasRumah->atap) : '-',
            $anggota ?: '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header style
            'A1:M1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set wrap text for all cells
                $sheet->getDelegate()->getStyle('A1:M' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);

                // Auto height for all rows
                foreach ($sheet->getDelegate()->getRowDimensions() as $row) {
                    $row->setRowHeight(-1);
                }
            }
        ];
    }
}