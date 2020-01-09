<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $primaryKey='user_id';
    protected $guarded = [];
    protected $table = 'user';
    public $timestamps = false;
}
