<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentVoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'size',
    ];

    public function ContentVoicable()
    {
        return $this->morphTo();
    }
}
