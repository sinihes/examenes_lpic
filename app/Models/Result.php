<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'category', 'score', 'total', 'details'
    ];
    protected $casts = ['details' => 'array'];
    protected $table = 'results';

    public function user() {
        return $this->belongsTo(User::class);
    }

}
