<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieActorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_actor', function (Blueprint $table) {
            // --- Khóa ngoại trỏ đến bảng movies ---
            // Kiểm tra kiểu dữ liệu movies.movie_id (giả sử là INT như trước)
            $table->integer('movie_id'); // <<< Điều chỉnh kiểu nếu movies.movie_id khác INT

            // --- Khóa ngoại trỏ đến bảng actors ---
            // **QUAN TRỌNG:** Kiểm tra kiểu dữ liệu của khóa chính trong bảng 'actors'
            // Giả sử khóa chính là 'actors_id' và có kiểu INT
            $table->integer('actor_id'); // <<< ĐIỀU CHỈNH KIỂU nếu actors.actors_id khác INT

            // --- Định nghĩa khóa ngoại ---
            $table->foreign('movie_id')
                  ->references('movie_id')->on('movies') // Kiểm tra tên cột và bảng movies
                  ->onDelete('cascade');

            // --- KIỂM TRA KHÓA CHÍNH BẢNG ACTORS ---
            $table->foreign('actor_id')
                  ->references('actors_id')->on('actors') // Kiểm tra tên cột và bảng actors
                  ->onDelete('cascade');

            // --- Định nghĩa khóa chính ---
            $table->primary(['movie_id', 'actor_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_actor');
    }
}