<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'category_id',
        'author_id',
        'tag',
        'title',
        'description',
        'image',
        'position',
        'type',
        'level',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}