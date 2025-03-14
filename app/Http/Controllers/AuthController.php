<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user             = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name']  = $user->name;

            return response()->json(['status' => true, 'message' => 'User login successfully', 'data' => $success], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }
    }
    public function user()
    {
        return response()->json(Auth::user());
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
