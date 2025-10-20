<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worker extends Model
{
    /** @use HasFactory<\Database\Factories\WorkerFactory> */
    use HasFactory;

    protected $fillable = [
        'department',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
