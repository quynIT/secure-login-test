<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Tên bảng trong database (mặc định Laravel sẽ dùng 'posts')
    protected $table = 'posts';

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = ['title', 'content', 'author_id'];

    // Ẩn các trường khi trả về JSON
    protected $hidden = ['updated_at'];

    // Định nghĩa quan hệ (Ví dụ: Một bài post thuộc về một User)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
