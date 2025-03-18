<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // Tạo trường id tự động tăng và là khóa chính
            $table->string('title'); // Thêm trường title kiểu chuỗi
            $table->string('image')->nullable(); // Thêm trường image, có thể null
            $table->text('description')->nullable(); // Thêm trường description kiểu text, có thể null
            $table->dateTime('start_date'); // Thêm trường start_date kiểu datetime
            $table->dateTime('end_date'); // Thêm trường end_date kiểu datetime
            $table->timestamps(); // Tạo trường created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};