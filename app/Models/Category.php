<?php

namespace App\Models;

use App\Models\UserTrophy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'image'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function userPointCategory()
    {
        return $this->hasMany(UserPointCategory::class);
    }
    public function userTrophy()
    {
        return $this->hasMany(UserTrophy::class);
    }
}