<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\VerifikasiPenduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifikasiController extends Controller
{
    public function index()
    {
        try {
            $this->authorize('verify-documents');

            $query = VerifikasiPenduduk::where('status', 'pending')
                ->with(['kartuKeluarga.anggotaKeluarga', 'kartuKeluarga.identitasRumah', 'kartuKeluarga.kepalaKeluarga'])
                ->latest();

            // Filter berdasarkan desa jika bukan admin
            if (auth()->user()->id_desa) {
                $query->whereHas('kartuKeluarga', function ($q) {
                    $q->where('id_desa', auth()->user()->id_desa);
                });
            }

            $verifikasi = $query->paginate(10);

            return view('pages.verifikasi', compact('verifikasi'));

        } catch (\Exception $e) {
            Log::error('Error in verifikasi index: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data verifikasi');
        }
    }

    public function approve($id_verifikasi)
    {
        try {
            $this->authorize('verify-documents');

            $verifikasi = VerifikasiPenduduk::findOrFail($id_verifikasi);
            $verifikasi->update([
                'status' => 'verified',
                'keterangan' => 'Diverifikasi oleh ' . auth()->user()->username . ' pada ' . now()->format('d/m/Y H:i:s')
            ]);

            return redirect()->route('verifikasi.index')
                ->with('success', 'Data keluarga berhasil diverifikasi');

        } catch (\Exception $e) {
            Log::error('Error in verifikasi approve: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memverifikasi data');
        }
    }

    public function reject($id_verifikasi, Request $request)
    {
        try {
            $this->authorize('verify-documents');

            $request->validate([
                'alasan_penolakan' => 'required|string|max:255'
            ]);

            $verifikasi = VerifikasiPenduduk::findOrFail($id_verifikasi);
            $verifikasi->update([
                'status' => 'rejected',
                'keterangan' => $request->alasan_penolakan
            ]);

            return redirect()->route('verifikasi.index')
                ->with('success', 'Data keluarga ditolak');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Error in verifikasi reject: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak data');
        }
    }
}