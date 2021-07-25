<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'is_free',
    ];

    public function content_video(){
        return $this->morphOne(ContentVideo::class, 'content_videoable');
    }

    public function content_voice(){
        return $this->morphOne(ContentVoice::class, 'content_voicable');
    }

    public function content_document(){
        return $this->morphOne(ContentDocument::class, 'content_documentable');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
