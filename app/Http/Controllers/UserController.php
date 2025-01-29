<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-users');

        $users = User::with(['roles', 'desa'])
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Admin');
            })
            ->latest()
            ->get();

        $roles = Role::where('name', '!=', 'Admin')->get();
        $desas = Desa::all();

        return view('pages.users', compact('users', 'roles', 'desas'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'id_desa' => ['required_if:role,Operator Desa,Validator Desa,Kades', 'exists:desa,id'],
            'foto' => ['nullable', 'image', 'max:2048']
        ]);

        $data = [
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ];

        if (in_array($request->role, ['Operator Desa', 'Validator Desa', 'Kades'])) {
            $data['id_desa'] = $request->id_desa;
        }

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
        try {
            $this->authorize('edit-users');

            $validationRules = [
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id_pengguna . ',id_pengguna'],
                'password' => ['nullable', Password::defaults()],
                'role' => ['required', 'exists:roles,name'],
                'id_desa' => [
                    'nullable',
                    function ($attribute, $value, $fail) use ($request) {
                        if (in_array($request->role, ['Operator Desa', 'Validator Desa', 'Kades'])) {
                            if (empty($value)) {
                                $fail('The desa field is required when role is Operator Desa, Validator Desa, or Kades.');
                            } else {
                                if (!\App\Models\Desa::where('id', $value)->exists()) {
                                    $fail('The selected desa is invalid.');
                                }
                            }
                        }
                    }
                ],
                'foto' => ['nullable', 'image', 'max:2048']
            ];

            $request->validate($validationRules);

            $data = ['username' => $request->username];

            if (in_array($request->role, ['Operator Desa', 'Validator Desa', 'Kades'])) {
                $data['id_desa'] = $request->id_desa;
            } else {
                $data['id_desa'] = null;
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('foto')) {
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
            $user->syncRoles([$request->role]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-users');

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