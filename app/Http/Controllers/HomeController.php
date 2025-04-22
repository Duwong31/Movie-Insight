<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Logic để lấy dữ liệu cho trang chủ
        // Ví dụ tạm:
        return view('home.index', [
            'news_array' => [], // Truyền mảng rỗng hoặc dữ liệu thật
            'movies_array' => [],
            'tv_shows_array' => [],
            'actors_array' => [],
        ]);
    }
}
