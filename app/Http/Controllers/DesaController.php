<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesaController extends Controller
{
    public function index()
    {
        try {
            $this->authorize('view-desa');

            $desas = Desa::latest()->get();
            return view('pages.desa', compact('desas'));

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create-desa');

            $validated = $request->validate([
                'nama_desa' => ['required', 'string', 'max:255', 'unique:desa,nama_desa'],
            ]);

            DB::beginTransaction();

            Desa::create($validated);

            DB::commit();

            return redirect()->route('desa.index')
                ->with('success', 'Desa berhasil ditambahkan');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Desa $desa)
    {
        try {
            $this->authorize('edit-desa');

            $validated = $request->validate([
                'nama_desa' => ['required', 'string', 'max:255', 'unique:desa,nama_desa,' . $desa->id . ',id'],
            ]);

            DB::beginTransaction();

            $desa->update($validated);

            DB::commit();

            return redirect()->route('desa.index')
                ->with('success', 'Desa berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Desa $desa)
    {
        try {
            $this->authorize('delete-desa');

            DB::beginTransaction();

            // Check if desa has related data
            if ($desa->kartuKeluarga()->exists()) {
                throw new Exception('Tidak dapat menghapus desa yang masih memiliki data kartu keluarga');
            }

            if ($desa->users()->exists()) {
                throw new Exception('Tidak dapat menghapus desa yang masih memiliki data pengguna');
            }

            $desa->delete();

            DB::commit();

            return redirect()->route('desa.index')
                ->with('success', 'Desa berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}