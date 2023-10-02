<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPointCategory extends Model
{
    use HasFactory;

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_point_category';

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'current_point',
        'total_point',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
