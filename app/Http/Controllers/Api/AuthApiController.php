<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Constants\RoleConstants;

class AuthApiController extends Controller
{
    /**
     * Đăng ký người dùng mới
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                // 'password_confirmation' => Hash::make($request->password_confirmation),
                'role' => 'Customer',
            ]);

            // Gán quyền Customer mặc định cho người dùng mới
            $user->assignRole(RoleConstants::ROLE_CUSTOMER);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => $user
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Đăng nhập và cấp token
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login failed. Invalid email or password.'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // Lấy danh sách quyền của người dùng
            $roles = $user->getRoleNames();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,  
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Đăng xuất (hủy token)
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Lấy thông tin người dùng hiện tại
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}