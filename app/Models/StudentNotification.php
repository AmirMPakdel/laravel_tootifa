<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'expiration_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
