<?php

namespace App;

use App\Jobs\MailUserCredentialsJob;
use App\Office;
use App\Mail\MailUserCredentials;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','level','password_changed','is_admin','send_to','pstring'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function office(){
        return $this->belongsToMany(Office::class)->orderBy('office_id');
    }
    public function offices(){
        return $this->hasMany(OfficeUser::class);
    }
    public function logs(){
        return $this->hasMany(UserLog::class);
    }

    public function lastLogin(){
        return $this->logs->sortByDesc('id')->first();
    }

    public function reset(){
        $this->password = Hash::make('lightmfi123');
        $this->password_changed = false;
        return $this->save();
    }
    public function disable(){
        $this->disabled = true;
        return $this->save();
    }
    public function enable(){
        $this->disabled = false;
        return $this->save();
    }
    public function sendToEmail(){
        dispatch(new MailUserCredentialsJob($this));
        return true;
    }

    public static function sendBulkUserCredentials(){
        $users = User::all();

        foreach($users as $user){
            $user->sendToEmail();
        }
    }
}
