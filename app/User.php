<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Soft Deletes
    use SoftDeletes;

    // Soft Deletes
    protected $dates = ['deleted_at'];

    // Images
    public function images() {
        // One-to-Many
        return $this->hasMany('App\Images');
    }

    protected $fillable = [
        'name', 'email', 'password', 'profile'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
