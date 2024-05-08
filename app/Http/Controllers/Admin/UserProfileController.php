<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function index()
    {

        $user = auth()->user()->load('roles');

        return view('admin.sections.users.profile', [
            'title' => 'Profile',
            'menu_active' => 'profile',
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'The name field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $profile = $user->profile()->updateOrCreate([], [
            'experience' => $request->experience,
            'skills' => $request->skills,
            'location' => $request->location,
            'education' => $request->education,
        ]);

        if ($request->hasFile('avatar')) {
            $profile->addMediaFromRequest('avatar')
                ->usingFileName(Str::random(60) . '.' . $request->file('avatar')->extension())
                ->toMediaCollection('avatar');
        }

        if ($request->hasFile('resume')) {
            $profile->addMediaFromRequest('resume')
                ->usingFileName(Str::random(60) . '.' . $request->file('resume')->extension())
                ->toMediaCollection('resume');
        }

        $user = $user->load('profile');

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], Response::HTTP_OK);
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();


        return response()->json(['message' => 'Password updated successfully.']);
    }

}
