<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Register (default role = tenant). For security, do NOT allow public admin creation here.
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['nullable', Rule::in(['tenant','house_owner'])], // Admins handled separately
        ]);

        $role = $data['role'] ?? 'tenant';

        // Create user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role, // optional if you want to keep column in sync
        ]);

        // Assign Spatie role (make sure role exists in DB first)
        $user->assignRole($role);

        // Generate Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message'    => 'User registered successfully',
            'data'       => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $role,
            ],
            'token'      => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

// Login: returns Bearer token
public function login(Request $request)
{
    $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // optional: revoke old tokens for this device
        // $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data'    => $user,
            'token'   => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    // Current authenticated user
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // Logout: revoke current token
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }
}
