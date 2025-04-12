<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Constants\RoleConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:' . RoleConstants::ROLE_ADMIN]);
    }
    
    /**
     * Get all users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        $listUsers = User::all();
        return response()->json($listUsers);
    }
    public function getUserById($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        }
        return response()->json(['error' => 'User not found'], 404);
    }
    
    public function updateUser(Request $request, $id)
    {
        try {
            // Kiểm tra người dùng tồn tại hay không
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Validate dữ liệu nhập vào
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'phone_number' => 'nullable|string|max:15', // Validation cho số điện thoại
            ]);

            // Cập nhật thông tin người dùng
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->phone_number = $validatedData['phone_number'] ?? $user->phone_number; // Cập nhật số điện thoại nếu có

            $user->save(); // Lưu vào database

            return response()->json(['success' => true, 'message' => 'User updated successfully', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }


    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'User not found'], 404);
    }
    /**
     * Ban a user
     *
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function banUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => "User with ID {$userId} not found."], 404);
        }
        
        // Sử dụng DB::raw với hàm MySQL
        try {
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'banned_until' => DB::raw('DATE_ADD(NOW(), INTERVAL 10 YEAR)'),
                    // Tắt timestamps tự động cập nhật
                    'updated_at' => DB::raw('NOW()')
                ]);
                
            return response()->json([
                'success' => true,
                'message' => "User {$user->email} has been banned."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Không thể cấm người dùng: " . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Unban a user
     *
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbanUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => "User with ID {$userId} not found."], 404);
        }
        
        // Remove ban by setting banned_until to null
        $user->banned_until = null;
        $user->save();
        
        return response()->json(['message' => "User {$user->email} has been unbanned."]);
    }
}