<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Đổi tên class để tránh xung đột
class AddBannedUntilToUsersTableSecond extends Migration
{
    public function up()
    {
        // Không làm gì vì migration trước đã thêm cột banned_until
    }

    public function down()
    {
        // Không làm gì
    }
}