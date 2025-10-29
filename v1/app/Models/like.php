<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory; //sad

    protected $fillable = [
        'user_id',
    ];

    public function likeable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
