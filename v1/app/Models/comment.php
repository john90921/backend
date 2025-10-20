<?php

namespace App\Models;
use App\Models\User;
use App\Models\post;
use App\Models\like;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;


    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];
    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(post::class);
    }

    public function likes()
    {
        return $this->morphMany(like::class, 'likeable');
    }
    public function replies(){
        return $this->hasMany(reply::class);
    }

}
