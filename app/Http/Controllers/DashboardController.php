<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\IdentitasRumah;
use App\Models\VerifikasiPenduduk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik Umum dengan Persentase Pertumbuhan
            $startOfMonth = Carbon::now()->startOfMonth();

            $totalKK = KartuKeluarga::count();
            $totalKKLastMonth = KartuKeluarga::where('created_at', '<', $startOfMonth)->count();
            $kkGrowth = $totalKKLastMonth > 0 ? (($totalKK - $totalKKLastMonth) / $totalKKLastMonth) * 100 : 0;

            $totalPenduduk = Penduduk::count();
            $totalPendudukLastMonth = Penduduk::where('created_at', '<', $startOfMonth)->count();
            $pendudukGrowth = $totalPendudukLastMonth > 0 ? (($totalPenduduk - $totalPendudukLastMonth) / $totalPendudukLastMonth) * 100 : 0;

            $totalRumah = IdentitasRumah::count();
            $totalRumahLastMonth = IdentitasRumah::where('created_at', '<', $startOfMonth)->count();
            $rumahGrowth = $totalRumahLastMonth > 0 ? (($totalRumah - $totalRumahLastMonth) / $totalRumahLastMonth) * 100 : 0;

            // Statistik Verifikasi
            $verifikasiStats = VerifikasiPenduduk::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Statistik Verifikasi Trend
            $verifikasiTrend = VerifikasiPenduduk::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                'status',
                DB::raw('count(*) as total')
            )
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month', 'status')
                ->orderBy('month')
                ->get()
                ->groupBy('month');

            // Statistik Demografi
            $now = Carbon::now();
            $demografiStats = [
                'usia' => [
                    'balita' => Penduduk::where('tanggal_lahir', '>', $now->copy()->subYears(5))->count(),
                    'anak' => Penduduk::whereBetween('tanggal_lahir', [$now->copy()->subYears(12), $now->copy()->subYears(5)])->count(),
                    'remaja' => Penduduk::whereBetween('tanggal_lahir', [$now->copy()->subYears(25), $now->copy()->subYears(12)])->count(),
                    'dewasa' => Penduduk::whereBetween('tanggal_lahir', [$now->copy()->subYears(45), $now->copy()->subYears(25)])->count(),
                    'lansia' => Penduduk::where('tanggal_lahir', '<', $now->copy()->subYears(45))->count(),
                ],
                'komposisi_keluarga' => Penduduk::select('status_keluarga', DB::raw('count(*) as total'))
                    ->groupBy('status_keluarga')
                    ->pluck('total', 'status_keluarga')
                    ->toArray(),
                'pertumbuhan_bulanan' => Penduduk::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('count(*) as total')
                )
                    ->whereYear('created_at', $now->year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month')
                    ->toArray()
            ];

            // Statistik Pendidikan (Tanpa Gender)
            $pendidikanStats = Penduduk::select('pendidikan', DB::raw('count(*) as total'))
                ->groupBy('pendidikan')
                ->pluck('total', 'pendidikan')
                ->toArray();

            // Statistik Rumah
            $rumahStats = [
                'tipe_lantai' => IdentitasRumah::select('tipe_lantai', DB::raw('count(*) as total'))
                    ->groupBy('tipe_lantai')
                    ->pluck('total', 'tipe_lantai')
                    ->toArray(),
                'atap' => IdentitasRumah::select('atap', DB::raw('count(*) as total'))
                    ->groupBy('atap')
                    ->pluck('total', 'atap')
                    ->toArray(),
                'distribusi_kamar' => [
                    'kamar_tidur' => IdentitasRumah::select('jumlah_kamar_tidur', DB::raw('count(*) as total'))
                        ->groupBy('jumlah_kamar_tidur')
                        ->pluck('total', 'jumlah_kamar_tidur')
                        ->toArray(),
                    'kamar_mandi' => IdentitasRumah::select('jumlah_kamar_mandi', DB::raw('count(*) as total'))
                        ->groupBy('jumlah_kamar_mandi')
                        ->pluck('total', 'jumlah_kamar_mandi')
                        ->toArray(),
                ],
                'avg_kamar_tidur' => round(IdentitasRumah::avg('jumlah_kamar_tidur'), 1),
                'avg_kamar_mandi' => round(IdentitasRumah::avg('jumlah_kamar_mandi'), 1),
                'mode_tipe_lantai' => IdentitasRumah::select('tipe_lantai')
                    ->groupBy('tipe_lantai')
                    ->orderByRaw('COUNT(*) DESC')
                    ->first(),
                'mode_atap' => IdentitasRumah::select('atap')
                    ->groupBy('atap')
                    ->orderByRaw('COUNT(*) DESC')
                    ->first()
            ];

            // Data KK Terbaru
            $recentKK = KartuKeluarga::with([
                'kepalaKeluarga',
                'identitasRumah',
                'anggotaKeluarga',
                'verifikasi'
            ])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($kk) {
                    return [
                        'id' => $kk->id_kk,
                        'nomor_kk' => $kk->nomor_kk,
                        'kepala_keluarga' => $kk->kepalaKeluarga ? [
                            'nama' => $kk->kepalaKeluarga->nama,
                            'nik' => $kk->kepalaKeluarga->nik,
                        ] : null,
                        'jumlah_anggota' => $kk->anggotaKeluarga->count(),
                        'alamat' => $kk->identitasRumah ? $kk->identitasRumah->alamat_rumah : null,
                        'status_verifikasi' => $kk->verifikasi ? $kk->verifikasi->status : 'belum_verifikasi',
                        'tanggal_pembuatan' => $kk->created_at->format('d M Y')
                    ];
                });

            // Data Verifikasi Pending
            $pendingVerifikasi = VerifikasiPenduduk::with([
                'kartuKeluarga.kepalaKeluarga',
                'kartuKeluarga.identitasRumah'
            ])
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($verifikasi) {
                    return [
                        'id' => $verifikasi->id_verifikasi,
                        'kk' => [
                            'nomor' => $verifikasi->kartuKeluarga->nomor_kk,
                            'kepala_keluarga' => $verifikasi->kartuKeluarga->kepalaKeluarga?->nama,
                            'alamat' => $verifikasi->kartuKeluarga->identitasRumah?->alamat_rumah,
                        ],
                        'keterangan' => $verifikasi->keterangan,
                        'tanggal_pengajuan' => $verifikasi->created_at->format('d M Y')
                    ];
                });

            return view('pages.dashboard', compact(
                'totalKK',
                'totalPenduduk',
                'totalRumah',
                'kkGrowth',
                'pendudukGrowth',
                'rumahGrowth',
                'verifikasiStats',
                'verifikasiTrend',
                'demografiStats',
                'pendidikanStats',
                'rumahStats',
                'recentKK',
                'pendingVerifikasi'
            ));

        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}