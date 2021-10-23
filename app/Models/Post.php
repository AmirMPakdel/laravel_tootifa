<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Includes\Constant;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
        'cover',
        'introduction_video',
        'short_desc',
        'long_desc',
        'duration',
        'score',
        'validation_status',
        'validation_status_message',
        'content_hierarchy',
        'suggested_courses',
        'suggested_posts',
        'visits_count',
        'is_comments_open',
        'all_comments_valid',
    ];

    public function scopeValid($query){
        return $query->where('validation_status', '=', Constant::$VALIDATION_STATUS_VALID);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function writers()
    {
        return $this->belongsToMany(Writer::class);
    }

    public function post_contents()
    {
        return $this->hasMany(PostContent::class);
    }

    public function post_forms()
    {
        return $this->hasMany(PostForm::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function scores()
    {
        return $this->morphMany(Score::class, 'scorable');
    }

    public function level_one_group(){
        return $this->belongsTo(LevelOneGroup::class);
    }

    public function level_two_group(){
        return $this->belongsTo(LevelTwoGroup::class);
    }

    public function level_three_group(){
        return $this->belongsTo(LevelThreeGroup::class);
    }

}
