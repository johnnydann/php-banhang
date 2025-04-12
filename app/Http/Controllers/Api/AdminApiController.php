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