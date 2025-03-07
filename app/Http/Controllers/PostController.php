<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function post()
    {
        $posts = DB::select("SELECT * FROM posts WHERE id = :id", [
            'id' => 1
        ]);
        if (empty($posts)) {
            return abort(404, "Không tìm thấy bài viết");
        }
        $post = $posts[0];

        return view('post.post', ['post' => $post]);
    }
}
