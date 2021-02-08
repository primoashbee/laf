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
        'name', 'email', 'password','password_changed','is_admin','send_to','pstring'
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

    public function scopes(){
       
        $offices = $this->office;

        $scopes = [];
        foreach ($offices as $office) {
            array_push($scopes, $office);
            $scopes = array_merge($scopes, $office->getChild());
        }
        return $scopes;
    
    
}

public function scopesBranch($level = null){
    $office_level = $level;
    
    $collection = collect($this->scopes());
    if ($office_level==null) {
        $branches = [];
        $clusters = [];
        $officers = [];
        $units = [];
        $lo = [];

        
        $branches = $collection->filter(function($item){
            return $item->level=="branch";
        })->values();
        
        $branches = $branches->map(function($item){
            $branch['id'] = $item->id;
            $branch['name'] = $item->name;
            return $branch;
        });
        

        $clusters = $collection->filter(function($item){
            return $item->level=="cluster";
        })->values();

        $clusters = $clusters->map(function($item){
            $cluster['id'] = $item->id;
            $cluster['name'] = $item->name;
            return $cluster;
        });
        
        $officers = $collection->filter(function($item){
            return $item->level=="account_officer";
        })->values();

        $lo = $collection->filter(function($item){
            return $item->level=="loan_officer";
        })->values();
        

        $officers = $officers->map(function($item){
            $officer['id'] = $item->id;
            $officer['name'] = $item->name;
            return $officer;
        });

        $units = $collection->filter(function($item){
            return $item->level=="unit";
        })->values();
        

        $units = $units->map(function($item){
            $unit['id'] = $item->id;
            $unit['name'] = $item->name;
            return $unit;
        });

        $los = $lo->map(function($item){
            $unit['id'] = $item->id;
            $unit['name'] = $item->name;
            return $unit;
        });
        
        $filtered = [
            ['level' => 'Branches', 'data' => collect($branches)->sortBy('name')->unique()->values()], 
            ['level' => 'Clusters', 'data' => collect($clusters)->sortBy('name')->unique()->values()], 
            ['level' => 'Officers', 'data' => collect($officers)->sortBy('name')->unique()->values()],
            ['level' => 'Units', 'data' => collect($units)->sortBy('name')->unique()->values()],
            ['level' => 'Loan Officers', 'data' => collect($los)->sortBy('name')->unique()->values()]
        ];
        return $filtered;
    }

    $list = $collection->filter(function($item) use($office_level){
        return $item->level == $office_level;
    })->values();
    
    $lists = $list->map(function($item) use ($office_level){
        $branch['id'] = $item->id;
        $branch['name'] = $item->name;
        
        if($office_level=="main_office"){
            //make region
            $branch['prefix'] = pad(Office::levelCount('region')+1,'3');
        }elseif($office_level=="region"){
            //make area
            $branch['prefix'] = pad(Office::levelCount('area')+1,'2');
        }elseif($office_level=="area"){
            
            $branch['prefix'] = pad(Office::levelCount('branch')+1,'3');
        }
        
        
        if (Office::isChildOf('branch', $item->level) || Office::isChildOf('branch',$item->level == "branch")) {
            $branch['code'] = $item->getTopOffice('branch')->code;
            $branch['prefix'] = $item->getTopOffice('branch')->code;
        }
        return $branch;
    });
     
    $filtered = [
        [
            'level' => ucwords($office_level), 
            'data' => collect($lists)->sortBy('name')->unique()->values()
        ], 
    ];
    return $filtered;
}
}
