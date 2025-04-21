<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $fillable = ['name_admin', 'email', 'password'];
    protected $hidden = ['password'];
    
}
