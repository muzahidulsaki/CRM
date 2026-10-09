<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions', 'users')->get();
        $permissions = Permission::all()->groupBy('module');

        return view('roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' updated successfully.");
    }
}
