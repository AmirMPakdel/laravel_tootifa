<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseIntroduction extends Model
{
    use HasFactory;

    public function content_video(){
        return $this->morphOne(ContentVideo::class, 'content_videoable');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
