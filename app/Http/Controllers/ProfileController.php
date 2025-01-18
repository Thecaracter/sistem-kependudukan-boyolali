<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use File;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.profile', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();



        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id_pengguna, 'id_pengguna'),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            // Update username
            $user->username = $validated['username'];


            // Handle photo upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');


                // Validate if file is really an image
                if (!$foto->isValid()) {

                    return back()->withErrors(['foto' => 'File foto tidak valid'])->withInput();
                }

                $fotoName = time() . '_' . str_replace(' ', '_', $user->username) . '.' . $foto->getClientOriginalExtension();


                // Create directory if it doesn't exist
                $path = public_path('fotoProfile');
                if (!File::isDirectory($path)) {

                    File::makeDirectory($path, 0777, true, true);
                }

                // Delete old photo if exists
                if ($user->foto && File::exists(public_path('fotoProfile/' . $user->foto))) {

                    File::delete(public_path('fotoProfile/' . $user->foto));
                }

                // Move new photo with absolute path


                if ($foto->move($path, $fotoName)) {
                    $user->foto = $fotoName;

                } else {
                    Log::error('Failed to move photo');
                    return back()->withErrors(['foto' => 'Gagal mengupload foto'])->withInput();
                }
            }

            $user->save();


            return redirect()
                ->route('profile.edit')
                ->with('success', 'Profile berhasil diperbarui');

        } catch (\Exception $e) {

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();



        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            if (!Hash::check($validated['current_password'], $user->password)) {

                return back()
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();


            return redirect()
                ->route('profile.edit')
                ->with('success', 'Password berhasil diperbarui');

        } catch (\Exception $e) {

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mengupdate password'])
                ->withInput();
        }
    }
}