<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_genre', function (Blueprint $table) {
            // --- Khóa ngoại trỏ đến bảng movies ---
            // Tên cột 'movie_id' khớp với tham số thứ 3 trong belongsToMany
            // Kiểu dữ liệu phải khớp với khóa chính của bảng movies
            // Nếu khóa chính movies là 'movie_id' kiểu INT thông thường:
            // $table->integer('movie_id')->unsigned(); // Hoặc chỉ integer nếu không phải unsigned
            // Nếu khóa chính movies là 'movie_id' kiểu BIGINT UNSIGNED (tạo bởi $table->id() hoặc tương tự):
            $table->integer('movie_id');

            // --- Khóa ngoại trỏ đến bảng genres ---
            // Tên cột 'genre_id' khớp với tham số thứ 4 trong belongsToMany
            // Kiểu dữ liệu phải khớp với khóa chính của bảng genres ('genres_id')
            // Giả sử genres_id là INT:
            // $table->integer('genre_id')->unsigned();
            // Giả sử genres_id là BIGINT UNSIGNED:
            $table->integer('genre_id');


            // --- Định nghĩa khóa ngoại (Quan trọng!) ---
            // Tham số 1: Tên cột trong bảng này ('movie_genre')
            // Tham số 2: Tên cột khóa chính trong bảng kia ('movies')
            // Tham số 3: Tên bảng kia
             $table->foreign('movie_id')
                  ->references('movie_id') // <<< Khóa chính của bảng movies
                  ->on('movies')
                  ->onDelete('cascade'); // Tự động xóa dòng này nếu movie bị xóa

             // Tham số 1: Tên cột trong bảng này ('movie_genre')
             // Tham số 2: Tên cột khóa chính trong bảng kia ('genres')
             // Tham số 3: Tên bảng kia
            $table->foreign('genre_id')
                  ->references('genres_id') // <<< Khóa chính của bảng genres
                  ->on('genres')
                  ->onDelete('cascade'); // Tự động xóa dòng này nếu genre bị xóa

            // --- Định nghĩa khóa chính (Primary Key) ---
            // Khóa chính nên là sự kết hợp của cả hai khóa ngoại để đảm bảo
            // mỗi cặp movie-genre là duy nhất.
            $table->primary(['movie_id', 'genre_id']);

            // Bạn không cần timestamps (created_at, updated_at) cho bảng pivot này
            // trừ khi bạn có lý do đặc biệt.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_genre');
    }
}