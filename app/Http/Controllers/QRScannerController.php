<?php

namespace App\Http\Controllers;

use App\Models\IdentitasRumah;
use Illuminate\Http\Request;

class QRScannerController extends Controller
{
    public function index()
    {
        return view('pages.qr-scanner');
    }

    public function scan(Request $request)
    {
        try {
            $qrCode = $request->input('qr_code');

            // Load identitas rumah dengan relasi KK dan penduduk
            $identitasRumah = IdentitasRumah::with([
                'kartuKeluargaAktif',
                'kartuKeluargaAktif.anggotaKeluarga',
                'kartuKeluargaAktif.verifikasi'
            ])
                ->where('id_rumah', $qrCode)
                ->orWhere('barcode', $qrCode)
                ->first();

            if (!$identitasRumah) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak terdaftar dalam sistem'
                ], 404);
            }

            // Format response data
            $response = [
                'success' => true,
                'data' => [
                    'identitas_rumah' => [
                        'id_rumah' => $identitasRumah->id_rumah,
                        'alamat_rumah' => $identitasRumah->alamat_rumah,
                        'tipe_lantai' => ucfirst($identitasRumah->tipe_lantai),
                        'jumlah_kamar_tidur' => $identitasRumah->jumlah_kamar_tidur,
                        'jumlah_kamar_mandi' => $identitasRumah->jumlah_kamar_mandi,
                        'atap' => ucfirst($identitasRumah->atap)
                    ]
                ]
            ];

            // Add KK data if exists
            if ($identitasRumah->kartuKeluargaAktif) {
                $kk = $identitasRumah->kartuKeluargaAktif;
                $response['data']['kartu_keluarga'] = [
                    'nomor_kk' => $kk->nomor_kk,
                    'tanggal_pembuatan' => $kk->tanggal_pembuatan->format('d F Y'),
                ];

                // Add anggota keluarga
                $response['data']['anggota_keluarga'] = $kk->anggotaKeluarga->map(function ($anggota) {
                    return [
                        'nama' => $anggota->nama,
                        'nik' => $anggota->nik,
                        'tanggal_lahir' => $anggota->tanggal_lahir->format('d F Y'),
                        'status_keluarga' => ucwords(str_replace('_', ' ', $anggota->status_keluarga)),
                        'pendidikan' => strtoupper($anggota->pendidikan)
                    ];
                });

                // Add verifikasi status if exists
                if ($kk->verifikasi) {
                    $response['data']['verifikasi'] = [
                        'status' => ucfirst($kk->verifikasi->status),
                        'keterangan' => $kk->verifikasi->keterangan,
                        'updated_at' => $kk->verifikasi->updated_at->format('d F Y H:i')
                    ];
                }
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 400);
        }
    }
}