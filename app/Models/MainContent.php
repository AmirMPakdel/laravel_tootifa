<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'type'
    ];

    public function content_video(){
        return $this->morphOne(ContentVideo::class, 'content_videoable');
    }

    public function content_image(){
        return $this->morphOne(ContentImage::class, 'content_imagable');
    }

    public function content_voice(){
        return $this->morphOne(ContentVoice::class, 'content_voicable');
    }

    public function content_text(){
        return $this->morphOne(ContentText::class, 'content_textable');
    }

    public function content_slider(){
        return $this->morphOne(ContentSlider::class, 'content_sliderable');
    }

    public function scopeVisible($query){
        return $query->where('visible', '=', 1);
    }
}
