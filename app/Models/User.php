<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'first_name',
    //     'last_name',
    //     'email',
    //     'national_code',
    //     'phone_number',
    //     'phone_verified_at',
    //     'email_verified_at',
    //     'verification_code',
    //     'tenant_id',
    //     'password',
    //     'token',
    //     'u_profile_id',
    //     'unicode'
    // ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function u_profile()
    {
        return $this->hasOne(UProfile::class);
    }


}
