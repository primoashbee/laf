<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OfficeUser extends Model
{
    protected $fillable = ['user_id','office_id'];
    protected $table = 'office_user';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
