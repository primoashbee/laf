<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    //
    protected $fillable = ['user_id','ip_address'];

    public function user(){
       return $this->belongsTo(User::class);
    }
}
