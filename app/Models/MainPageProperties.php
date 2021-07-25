<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainPageProperties extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_banner_on',
        'banner_link',
        'banner_cover',
        'banner_text',
        'page_title',
        'page_logo',
        'page_cover',
        'content_hierarchy',
        'footer_links',
    ];
}
