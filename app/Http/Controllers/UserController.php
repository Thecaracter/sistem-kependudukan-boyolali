<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-users');

        $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Admin');
            })
            ->latest()
            ->get();

        $roles = Role::where('name', '!=', 'Admin')->get();

        return view('pages.users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'foto' => ['nullable', 'image', 'max:2048']
        ]);

        $data = [
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotoProfile'), $fotoName);
            $data['foto'] = $fotoName;
        }

        $user = User::create($data);
        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit-users');

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id_pengguna . ',id_pengguna'],
            'password' => ['nullable', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'foto' => ['nullable', 'image', 'max:2048']
        ]);

        $data = ['username' => $request->username];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto) {
                $oldPhotoPath = public_path('fotoProfile/' . $user->foto);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotoProfile'), $fotoName);
            $data['foto'] = $fotoName;
        }

        $user->update($data);

        // Update role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-users');

        // Delete photo if exists
        if ($user->foto) {
            $photoPath = public_path('fotoProfile/' . $user->foto);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}