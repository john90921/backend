<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reply extends Model
{
    /** @use HasFactory<\Database\Factories\ReplyFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'taged_user_id',
        'comment_id',
        'content',
    ];
        protected $table = 'replies';

         public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
    public function taggedUser()
    {
    return $this->belongsTo(User::class, 'taged_user_id');
    }
    public function comment()
    {
        return $this->belongsTo(comment::class, 'comment_id');
    }
        public function likes()
    {
        return $this->morphMany(like::class, 'likeable');
    }
    public function reports()
    {
        return $this->morphMany(report::class, 'reportable');
    }

}
