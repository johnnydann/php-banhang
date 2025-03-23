<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lưu lại dữ liệu từ cột role hiện tại
        $users = DB::table('users')->select('id', 'role')->get();
        $userData = [];
        
        foreach ($users as $user) {
            $userData[$user->id] = $user->role;
        }
        
        // 1. Kiểm tra xem cột role có tồn tại không
        if (Schema::hasColumn('users', 'role')) {
            // 2. Nếu có, xóa cột đó
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        // 3. Tạo lại cột role
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
        });
        
        // 4. Khôi phục dữ liệu
        foreach ($userData as $id => $role) {
            DB::table('users')
                ->where('id', $id)
                ->update(['role' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Trong trường hợp rollback, chúng ta không cần làm gì đặc biệt
        // vì cột role đã tồn tại trước migration này
    }
};