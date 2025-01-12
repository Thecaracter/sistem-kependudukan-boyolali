<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PendudukController extends Controller
{
    public function index(Request $request, $id_kk)
    {
        try {
            $this->authorize('view-penduduk');

            $kartuKeluarga = KartuKeluarga::findOrFail($id_kk);
            $search = $request->search;

            $penduduk = Penduduk::with(['kartuKeluarga'])
                ->where('id_kk', $id_kk)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
                })
                ->orderByRaw("CASE 
                    WHEN status_keluarga = 'kepala_keluarga' THEN 1
                    WHEN status_keluarga = 'istri' THEN 2
                    WHEN status_keluarga = 'anak' THEN 3
                    ELSE 4 
                END")
                ->latest()
                ->get();

            return view('pages.penduduk', compact('penduduk', 'kartuKeluarga', 'search'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $id_kk)
    {
        try {
            $this->authorize('create-penduduk');

            $kartuKeluarga = KartuKeluarga::findOrFail($id_kk);

            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'string', 'size:16', 'unique:penduduk,nik'],
                'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
                'alamat' => ['required', 'string'],
                'status_keluarga' => ['required', 'string', 'in:' . implode(',', Penduduk::STATUS_KELUARGA)],
                'pendidikan' => ['required', 'string', 'in:' . implode(',', Penduduk::PENDIDIKAN)],
            ]);

            DB::beginTransaction();

            $penduduk = Penduduk::create([
                ...$validated,
                'id_kk' => $id_kk
            ]);

            // Jika status_keluarga adalah kepala_keluarga dan belum ada kepala keluarga
            if ($validated['status_keluarga'] === 'kepala_keluarga' && !$kartuKeluarga->kepala_keluarga_id) {
                $kartuKeluarga->update([
                    'kepala_keluarga_id' => $penduduk->id_penduduk
                ]);
            }

            DB::commit();

            return redirect()->route('penduduk.index', $id_kk)
                ->with('success', 'Data penduduk berhasil ditambahkan');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_kk, Penduduk $penduduk)
    {
        try {
            $this->authorize('edit-penduduk');

            // Pastikan penduduk adalah anggota dari KK yang dimaksud
            if ($penduduk->id_kk !== $id_kk) {
                throw new Exception('Data penduduk tidak ditemukan dalam kartu keluarga ini');
            }

            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'string', 'size:16', 'unique:penduduk,nik,' . $penduduk->id_penduduk . ',id_penduduk'],
                'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
                'alamat' => ['required', 'string'],
                'status_keluarga' => ['required', 'string', 'in:' . implode(',', Penduduk::STATUS_KELUARGA)],
                'pendidikan' => ['required', 'string', 'in:' . implode(',', Penduduk::PENDIDIKAN)],
            ]);

            DB::beginTransaction();

            // Jika sebelumnya kepala keluarga
            if ($penduduk->status_keluarga === 'kepala_keluarga') {
                // Dan status baru bukan kepala keluarga
                if ($validated['status_keluarga'] !== 'kepala_keluarga') {
                    // Hapus kepala keluarga dari kartu keluarga
                    $penduduk->kartuKeluargaSebagaiKepala()->update([
                        'kepala_keluarga_id' => null
                    ]);
                }
            }
            // Jika status baru adalah kepala keluarga dan belum ada kepala keluarga
            elseif ($validated['status_keluarga'] === 'kepala_keluarga' && !$penduduk->kartuKeluarga->kepala_keluarga_id) {
                // Update kartu keluarga dengan kepala keluarga baru
                $penduduk->kartuKeluarga()->update([
                    'kepala_keluarga_id' => $penduduk->id_penduduk
                ]);
            }

            $penduduk->update($validated);

            DB::commit();

            return redirect()->route('penduduk.index', $id_kk)
                ->with('success', 'Data penduduk berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id_kk, Penduduk $penduduk)
    {
        try {
            $this->authorize('delete-penduduk');

            // Pastikan penduduk adalah anggota dari KK yang dimaksud
            if ($penduduk->id_kk !== $id_kk) {
                throw new Exception('Data penduduk tidak ditemukan dalam kartu keluarga ini');
            }

            DB::beginTransaction();

            // Jika penduduk adalah kepala keluarga
            if ($penduduk->status_keluarga === 'kepala_keluarga') {
                // Hapus kepala keluarga dari kartu keluarga
                $penduduk->kartuKeluargaSebagaiKepala()->update([
                    'kepala_keluarga_id' => null
                ]);
            }

            $penduduk->delete();

            DB::commit();

            return redirect()->route('penduduk.index', $id_kk)
                ->with('success', 'Data penduduk berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}