<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission as AppPermissions;
use Spatie\Permission\Models\Role as AppRole;

class RolePermissionsController extends Controller
{
    /**
     * Display a listing of the Role
     *
     */

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $roles = AppRole::with('permissions')->select(['id', 'name'])->get();

            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($role) {
                    return '<button class="btn btn-info edit-role-btn" data-id="' . $role->id . '">Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('admin.sections.role-permissions.index', [
            'title' => 'Roles',
            'menu_active' => 'roles',
        ]);
    }

    public function getPermissions()
    {
        $permissions = AppPermissions::get();
        return response()->json(['permissions' => $permissions], 201);

    }

    // Store Role
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
        ], [
            'name.required' => 'Name field is required.',
            'name.unique' => 'Role name has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = new AppRole();
        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $permissions = AppPermissions::whereIn('id', $request->input('permissions'))->pluck('id');
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return response()->json(['role' => $role]);
    }

    // Show the form for editing the specified role
    public function edit($id)
    {

        $role = AppRole::find($id);
        $role = $role->load('permissions');
        $permissions = AppPermissions::get();

        return response()->json(['role' => $role, 'permissions' => $permissions]);
    }

    // Update the role
    public function update(Request $request, $id)
    {

        $role = AppRole::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id,
        ], [
            'name.required' => 'Name field is required.',
            'name.unique' => 'Role name has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $permissions = AppPermissions::whereIn('id', $request->input('permissions'))->pluck('id');
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return response()->json(['role' => $role->id]);
    }

}
