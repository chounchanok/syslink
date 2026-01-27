<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable;
    protected $table = 'admins';
    protected $fillable = [
        'name','username','password','role','remember_token'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getAuthPassword()
    {
      return $this->password;
    }
}
