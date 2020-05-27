<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeUser extends Model
{
    protected $fillable = ['user_id','office_id'];
    protected $table = 'office_user';
}
