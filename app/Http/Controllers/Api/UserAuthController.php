<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\NewUserNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json(['message' => 'Logged in successfully', 'token' => $token, 'user' => $user], 200);
            }

            return response()->json(['message' => 'Invalid email or password. Please try again.'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function register(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:6|max:255',
            ], [
                'name.required' => 'The name field is required.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('authToken')->plainTextToken;

            Mail::to('raisurrehman034@gmail.com')->queue(new NewUserNotification($user));

            return response()->json(['message' => 'User registered successfully', 'token' => $token, 'user' => new UserResource($user)], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }
}
