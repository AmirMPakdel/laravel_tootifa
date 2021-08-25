<?php

namespace App\Models;

use App\Includes\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
        'cover',
        'score',
        'price',
        'duration',
        'discount',
        'has_discount',
        'holding_status',
        'validation_status',
        'validation_status_message',
        'release_date',
        'subjects',
        'short_desc',
        'long_desc',
        'requirements',
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

    public function course_introduction()
    {
        return $this->hasOne(CourseIntroduction::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot("access");
    }

    public function educators()
    {
        return $this->belongsToMany(Educator::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function course_headings(){
        return $this->hasMany(CourseHeading::class);
    }

    public function course_contents(){
        return $this->hasMany(CourseContent::class);
    }

    public function qas(){
        return $this->hasMany(Qa::class);
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

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function scores()
    {
        return $this->morphMany(Score::class, 'scorable');
    }


}
