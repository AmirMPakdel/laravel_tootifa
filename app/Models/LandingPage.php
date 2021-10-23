<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    public function content_video(){
        return $this->morphOne(ContentVideo::class, 'content_videoable');
    }

    public function content_image(){
        return $this->morphOne(ContentImage::class, 'content_imagable');
    }

}
