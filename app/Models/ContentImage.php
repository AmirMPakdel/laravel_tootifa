<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'size',
    ];

    public function ContentImagable()
    {
        return $this->morphTo();
    }
}
