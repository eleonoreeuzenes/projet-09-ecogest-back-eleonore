<?php

namespace App\Models;
use App\Models\Tag;
use App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagPost extends Model
{
    use HasFactory;

    protected $table = 'tag_post';

    protected $fillable = [
        'id',
        'tag_id',
        'post_id'
    ];

    public function tags()
    {
        return $this->hasMany(Tag::class, 'tag_id', '');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_id');
    }


}
