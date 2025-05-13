<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ];

        User::create($data);

        return response()->json([
            'message' => 'User registered successfully',
            'status'  => 201,
            'success' => true,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $token = auth()->attempt($request->only('email', 'password'));
        $user  = auth()->user();

        if (!$token) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'status'  => 422,
                'success' => false,
            ], 422);
        }

        return response()->json([
            'user'    => $user,
            'token'   => $token,
            'message' => 'User logged in successfully',
            'status'  => 200,
            'success' => true,
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user'    => auth()->user(),
            'message' => 'User profile retrieved successfully',
            'status'  => 200,
            'success' => true,
        ]);
    }

    public function refreshToken(Request $request)
    {
        $token = auth()->refresh();

        return response()->json([
            'token'   => $token,
            'message' => 'Token refreshed successfully',
            'status'  => 200,
            'success' => true,
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'message' => 'User logged out successfully',
            'status'  => 200,
            'success' => true,
        ]);
    }
}
