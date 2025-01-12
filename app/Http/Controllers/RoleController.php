<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('view-roles');

        $roles = Role::with('permissions')
            ->where('name', '!=', 'Admin')
            ->get();
        $permissions = Permission::all();

        return view('pages.roles', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-roles');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,name']
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil ditambahkan');
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('edit-roles');

        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Role Admin tidak dapat diubah');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,name']
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete-roles');

        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Role Admin tidak dapat dihapus');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Role masih digunakan oleh beberapa user');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus');
    }
}