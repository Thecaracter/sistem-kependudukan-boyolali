<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\KartuKeluarga;
use App\Models\VerifikasiPenduduk;
use Illuminate\Support\Facades\DB;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $this->authorize('view-kartu-keluarga');

            $search = $request->search;

            $kartuKeluarga = KartuKeluarga::with(['kepalaKeluarga', 'identitasRumah'])
                ->when($search, function ($query) use ($search) {
                    $query->where('nomor_kk', 'like', "%{$search}%")
                        ->orWhereHas('kepalaKeluarga', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                        })
                        ->orWhereHas('anggotaKeluarga', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                        });
                })
                ->latest()
                ->get();

            return view('pages.kartu-keluarga', compact('kartuKeluarga', 'search'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create-kartu-keluarga');

            $validated = $request->validate([
                'nomor_kk' => ['required', 'string', 'size:16', 'unique:kartu_keluarga,nomor_kk'],
                'tanggal_pembuatan' => ['required', 'date', 'before_or_equal:today'],
            ]);

            DB::beginTransaction();

            // Create Kartu Keluarga
            $kartuKeluarga = KartuKeluarga::create([
                'nomor_kk' => $validated['nomor_kk'],
                'tanggal_pembuatan' => $validated['tanggal_pembuatan'],
            ]);

            // Create Verifikasi Penduduk
            VerifikasiPenduduk::create([
                'id_kk' => $kartuKeluarga->id_kk,
                'status' => 'pending',
                'keterangan' => 'Menunggu verifikasi'
            ]);

            DB::commit();

            return redirect()->route('kartu-keluarga.index')
                ->with('success', 'Kartu Keluarga berhasil ditambahkan dan menunggu verifikasi');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, KartuKeluarga $kartuKeluarga)
    {
        try {
            $this->authorize('edit-kartu-keluarga');

            $validated = $request->validate([
                'nomor_kk' => ['required', 'string', 'size:16', 'unique:kartu_keluarga,nomor_kk,' . $kartuKeluarga->id_kk . ',id_kk'],
                'tanggal_pembuatan' => ['required', 'date', 'before_or_equal:today'],
            ]);

            DB::beginTransaction();

            $kartuKeluarga->update([
                'nomor_kk' => $validated['nomor_kk'],
                'tanggal_pembuatan' => $validated['tanggal_pembuatan'],
            ]);

            DB::commit();

            return redirect()->route('kartu-keluarga.index')
                ->with('success', 'Kartu Keluarga berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(KartuKeluarga $kartuKeluarga)
    {
        try {
            $this->authorize('delete-kartu-keluarga');

            DB::beginTransaction();

            // Cek apakah memiliki anggota keluarga
            if ($kartuKeluarga->anggotaKeluarga()->exists()) {
                throw new Exception('Tidak dapat menghapus KK yang masih memiliki anggota');
            }

            $kartuKeluarga->delete();

            DB::commit();

            return redirect()->route('kartu-keluarga.index')
                ->with('success', 'Kartu Keluarga berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}