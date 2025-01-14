<?php

namespace App\Http\Controllers;

use App\Models\IdentitasRumah;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class IdentitasRumahController extends Controller
{
    public function index()
    {
        try {
            Log::info('Accessing IdentitasRumah index', [
                'kk_id' => request('kk'),
                'user_id' => auth()->id()
            ]);

            $this->authorize('view-identitas-rumah');

            $kkId = request('kk');

            if ($kkId) {
                Log::info('Fetching specific KK data', ['kk_id' => $kkId]);
                $kartuKeluarga = KartuKeluarga::findOrFail($kkId);
                $rumah = collect([$kartuKeluarga->identitasRumah])->filter();
            } else {
                Log::info('Fetching all IdentitasRumah data');
                $rumah = IdentitasRumah::with(['kartuKeluargaAktif'])
                    ->latest()
                    ->get();
            }

            return view('pages.identitas-rumah', compact('rumah', 'kkId'));

        } catch (Exception $e) {
            Log::error('Error in IdentitasRumah index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'kk_id' => request('kk')
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Attempting to store new IdentitasRumah', [
                'kk_id' => $request->kk_id,
                'user_id' => auth()->id()
            ]);

            $this->authorize('create-identitas-rumah');

            // Log semua request data untuk debug
            Log::info('Request data:', [
                'all' => $request->all(),
                'query' => $request->query(),
                'route' => $request->route()->parameters(),
                'kk' => $request->query('kk'),
                'kk_id' => $request->input('kk_id')
            ]);

            // Coba ambil kk_id dari berbagai sumber
            $kkId = $request->input('kk_id') ??
                $request->query('kk') ??
                $request->route('kk');

            if ($kkId) {
                $request->merge(['kk_id' => $kkId]);
            }

            $validated = $request->validate([
                'kk_id' => ['required', 'exists:kartu_keluarga,id_kk'],
                'alamat_rumah' => ['required', 'string', 'max:255'],
                'tipe_lantai' => ['required', 'in:keramik,ubin,kayu,tanah,lainnya'],
                'jumlah_kamar_tidur' => ['required', 'integer', 'min:0'],
                'jumlah_kamar_mandi' => ['required', 'integer', 'min:0'],
                'atap' => ['required', 'in:genteng,asbes,seng,jerami,lainnya'],
            ]);

            $kk = KartuKeluarga::findOrFail($validated['kk_id']);

            if ($kk->identitasRumah) {
                Log::warning('Attempted to create duplicate IdentitasRumah', [
                    'kk_id' => $validated['kk_id'],
                    'existing_rumah_id' => $kk->identitasRumah->id_rumah
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'KK ini sudah memiliki alamat rumah');
            }

            DB::beginTransaction();
            Log::info('Starting transaction for IdentitasRumah creation');

            $rumah = IdentitasRumah::create([
                'alamat_rumah' => $validated['alamat_rumah'],
                'tipe_lantai' => $validated['tipe_lantai'],
                'jumlah_kamar_tidur' => $validated['jumlah_kamar_tidur'],
                'jumlah_kamar_mandi' => $validated['jumlah_kamar_mandi'],
                'atap' => $validated['atap'],
                'id_kk' => $validated['kk_id'],
                'barcode' => 'temp'
            ]);

            Log::info('IdentitasRumah created', ['rumah_id' => $rumah->id_rumah]);

            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_L,
                'scale' => 5,
                'imageBase64' => true,
            ]);

            $qrcode = new QRCode($options);
            $qrImage = $qrcode->render($rumah->id_rumah);

            Log::info('QR Code generated', ['rumah_id' => $rumah->id_rumah]);

            $qrImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrImage));
            $qrPath = public_path('qrcodes/' . $rumah->id_rumah . '.png');

            if (!file_put_contents($qrPath, $qrImage)) {
                throw new Exception('Failed to save QR Code image');
            }

            Log::info('QR Code saved', ['path' => $qrPath]);

            $rumah->update([
                'barcode' => $rumah->id_rumah
            ]);

            $kk->update([
                'id_rumah' => $rumah->id_rumah
            ]);

            DB::commit();
            Log::info('Transaction committed successfully');

            return redirect()->back()
                ->with('success', 'Data rumah berhasil ditambahkan ke KK');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in storing IdentitasRumah', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, IdentitasRumah $identitasRumah)
    {
        try {
            Log::info('Attempting to update IdentitasRumah', [
                'rumah_id' => $identitasRumah->id_rumah,
                'user_id' => auth()->id()
            ]);

            $this->authorize('edit-identitas-rumah');

            $validated = $request->validate([
                'alamat_rumah' => ['required', 'string', 'max:255'],
                'tipe_lantai' => ['required', 'in:keramik,ubin,kayu,tanah,lainnya'],
                'jumlah_kamar_tidur' => ['required', 'integer', 'min:0'],
                'jumlah_kamar_mandi' => ['required', 'integer', 'min:0'],
                'atap' => ['required', 'in:genteng,asbes,seng,jerami,lainnya'],
            ]);

            DB::beginTransaction();
            Log::info('Starting transaction for IdentitasRumah update');

            $identitasRumah->update($validated);

            DB::commit();
            Log::info('IdentitasRumah updated successfully', [
                'rumah_id' => $identitasRumah->id_rumah
            ]);

            return redirect()->back()
                ->with('success', 'Data rumah berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in updating IdentitasRumah', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'rumah_id' => $identitasRumah->id_rumah,
                'request_data' => $request->except(['_token']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(IdentitasRumah $identitasRumah)
    {
        try {
            $this->authorize('delete-identitas-rumah');

            DB::beginTransaction();

            // Cek KK yang terkait dan set id_rumah mereka menjadi null
            DB::table('kartu_keluarga')
                ->where('id_rumah', $identitasRumah->id_rumah)
                ->update(['id_rumah' => null]);

            // Delete QR Code file if exists
            $qrPath = public_path('qrcodes/' . $identitasRumah->id_rumah . '.png');
            if (file_exists($qrPath)) {
                if (!unlink($qrPath)) {
                    Log::warning('Failed to delete QR Code file', ['path' => $qrPath]);
                    throw new Exception('Gagal menghapus file QR Code');
                } else {
                    Log::info('QR Code file deleted', ['path' => $qrPath]);
                }
            }

            // Log before delete
            Log::info('Attempting to delete IdentitasRumah', [
                'rumah_id' => $identitasRumah->id_rumah,
                'alamat' => $identitasRumah->alamat_rumah
            ]);

            $identitasRumah->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Data rumah berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in deleting IdentitasRumah', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'rumah_id' => $identitasRumah->id_rumah,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withErrors([$e->getMessage()]);
        }
    }

    public function download(IdentitasRumah $identitasRumah)
    {
        try {
            $this->authorize('view-identitas-rumah');
            Log::info('Attempting to download QR Code', [
                'rumah_id' => $identitasRumah->id_rumah,
                'user_id' => auth()->id()
            ]);

            $this->authorize('view-identitas-rumah');

            $qrPath = public_path('qrcodes/' . $identitasRumah->id_rumah . '.png');

            if (!file_exists($qrPath)) {
                Log::warning('QR Code file not found, regenerating', [
                    'path' => $qrPath
                ]);

                $options = new QROptions([
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel' => QRCode::ECC_L,
                    'scale' => 5,
                    'imageBase64' => true,
                ]);

                $qrcode = new QRCode($options);
                $qrImage = $qrcode->render($identitasRumah->id_rumah);

                $qrImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrImage));
                if (!file_put_contents($qrPath, $qrImage)) {
                    throw new Exception('Failed to save regenerated QR Code');
                }

                Log::info('QR Code regenerated successfully');
            }

            return response()->download($qrPath, 'QR_Code_Rumah_' . $identitasRumah->id_rumah . '.png');

        } catch (Exception $e) {
            Log::error('Error in downloading QR Code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'rumah_id' => $identitasRumah->id_rumah,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh QR Code: ' . $e->getMessage());
        }
    }
}