<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Users extends Authenticatable
{
     protected $table="users";
     public $timestamps = false;
}
