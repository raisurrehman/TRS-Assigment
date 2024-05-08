<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role as AppRole;

class UserController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email'])->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    return '<button class="btn btn-primary edit-user-btn" data-id="' . $user->id . '">Edit</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.sections.users.index', [
            'title' => 'Users',
            'menu_active' => 'users',
        ]);
    }

    public function getRoles()
    {
        $roles = AppRole::get();
        return response()->json(['roles' => $roles], 201);

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array|min:1',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'roles.required' => 'At least one role is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->roles()->detach();

        foreach ($request->roles as $roleId) {
            $role = AppRole::find($roleId);
            $user->assignRole($role);
        }

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function edit($id)
    {

        $user = User::find($id)->load('roles');
        $roles = AppRole::get();
        return response()->json(['user' => $user, 'roles' => $roles], 201);
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array|min:1',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'roles.required' => 'At least one role is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        $user->roles()->detach();

        foreach ($request->roles as $roleId) {
            $role = AppRole::find($roleId);
            $user->assignRole($role);
        }

        return response()->json(['message' => 'User updated successfully'], 201);
    }

}
