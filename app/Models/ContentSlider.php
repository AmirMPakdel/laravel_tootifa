<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function content_sliderable()
    {
        return $this->morphTo();
    }

    public function content_videos(){
        return $this->morphOne(ContentVideo::class, 'content_videoable');
    }

    public function content_images(){
        return $this->morphMany(ContentImage::class, 'content_imagable');
    }
}
