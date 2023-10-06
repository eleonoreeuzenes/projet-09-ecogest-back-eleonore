<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPostParticipation extends Model
{
    use HasFactory;


    protected $table = 'user_post_participation';

    protected $fillable = [
        'id',
        'participant_id',
        'post_id',
        'is_completed'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function posts()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
