<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Desa;
use Illuminate\Http\Request;
use App\Models\KartuKeluarga;
use Illuminate\Validation\Rule;
use App\Models\VerifikasiPenduduk;
use Illuminate\Support\Facades\DB;
use App\Exports\KartuKeluargaExport;
use Maatwebsite\Excel\Facades\Excel;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $this->authorize('view-kartu-keluarga');

            $search = $request->search;
            $status = $request->status;

            // Query dasar
            $query = KartuKeluarga::with(['kepalaKeluarga', 'identitasRumah', 'verifikasi']);

            // Filter berdasarkan desa yang login
            if (auth()->user()->id_desa) {
                $query->where('id_desa', auth()->user()->id_desa);
            }

            // Filter pencarian
            $query->when($search, function ($query) use ($search) {
                $query->where('nomor_kk', 'like', "%{$search}%")
                    ->orWhereHas('kepalaKeluarga', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    })
                    ->orWhereHas('anggotaKeluarga', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            });

            // Filter status
            $query->when($status, function ($query) use ($status) {
                $query->whereHas('verifikasi', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            });

            $kartuKeluarga = $query->latest()->get();

            $statusCount = [
                'total' => $kartuKeluarga->count(),
                'pending' => $kartuKeluarga->filter(fn($item) => $item->verifikasi->status === 'pending')->count(),
                'verified' => $kartuKeluarga->filter(fn($item) => $item->verifikasi->status === 'verified')->count(),
                'rejected' => $kartuKeluarga->filter(fn($item) => $item->verifikasi->status === 'rejected')->count(),
            ];

            // Tambahkan data desa untuk admin
            $desas = auth()->user()->hasRole('Admin') ? Desa::all() : collect();

            return view('pages.kartu-keluarga', compact('kartuKeluarga', 'search', 'status', 'statusCount', 'desas'));
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
                'id_desa' => [
                    Rule::requiredIf(fn() => auth()->user()->hasRole('Admin')),
                    'exists:desa,id'
                ],
            ]);

            DB::beginTransaction();

            // Gunakan id_desa dari request jika admin, atau dari user jika bukan admin
            $id_desa = auth()->user()->hasRole('Admin') ? $validated['id_desa'] : auth()->user()->id_desa;

            $kartuKeluarga = KartuKeluarga::create([
                'nomor_kk' => $validated['nomor_kk'],
                'tanggal_pembuatan' => $validated['tanggal_pembuatan'],
                'id_desa' => $id_desa,
            ]);

            VerifikasiPenduduk::create([
                'id_kk' => $kartuKeluarga->id_kk,
                'status' => 'pending',
                'keterangan' => 'Menunggu verifikasi oleh petugas'
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

            // Reset verification status if KK data is updated
            $kartuKeluarga->verifikasi()->update([
                'status' => 'pending',
                'keterangan' => 'Menunggu verifikasi ulang setelah perubahan data'
            ]);

            DB::commit();

            return redirect()->route('kartu-keluarga.index')
                ->with('success', 'Kartu Keluarga berhasil diperbarui dan menunggu verifikasi ulang');

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

            if ($kartuKeluarga->anggotaKeluarga()->exists()) {
                throw new Exception('Tidak dapat menghapus KK yang masih memiliki anggota');
            }

            // Delete verifikasi first due to foreign key constraint
            $kartuKeluarga->verifikasi()->delete();
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
    public function export()
    {
        try {
            $this->authorize('create-reports');

            return Excel::download(new KartuKeluargaExport, 'data-kartu-keluarga-' . date('Y-m-d') . '.xlsx');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }
}