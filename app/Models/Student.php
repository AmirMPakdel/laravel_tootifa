<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'national_code',
        'phone_number',
        'phone_verified_at',
        'email_verified_at',
        'verification_code',
        'password',
        'token',
        'state',
        'city',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot("access");
    }

    public function student_notifications()
    {
        return $this->hasMany(StudentNotification::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
