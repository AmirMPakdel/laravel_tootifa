<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qa extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
