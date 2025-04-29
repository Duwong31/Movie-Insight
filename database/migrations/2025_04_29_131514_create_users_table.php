<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Tương đương int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            // Chọn MỘT trong hai: 'name' (chuẩn Laravel) hoặc 'fullname'
            // $table->string('name'); // Chuẩn Laravel
            $table->string('fullname', 100)->nullable(); // Giống project cũ
            $table->string('email', 100)->unique(); // unique() là quan trọng
            $table->string('phone', 20)->nullable(); // Thêm cột phone
            $table->timestamp('email_verified_at')->nullable(); // Chuẩn Laravel, giữ lại nếu muốn xác thực email
            $table->string('password'); // Giống project cũ (Laravel sẽ tự hash)
            $table->rememberToken(); // Cần cho chức năng "Remember Me" (thay thế cookie cũ)
            $table->tinyInteger('status')->default(0); // Thêm cột status, dùng tinyInteger hợp lý hơn
            $table->boolean('isAdmin')->default(false); // Thêm cột isAdmin, dùng boolean (0/1)
            $table->timestamps(); // Tạo cột created_at và updated_at

            // Bỏ qua forgotToken, activeToken - Laravel có cơ chế riêng
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}