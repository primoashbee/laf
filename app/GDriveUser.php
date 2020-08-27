<?php

namespace App;

use Illuminate\Support\Facades\Hash;

class GDriveUser{
    public $items;
    
    public function __construct($array){
        $this->items = $array;
    }
    public function createUsers(){
        
        $items = $this->items;

        $to_insert=[];
        $ctr = 0;
        foreach($items as $item){
            if ($ctr>0) {
                $user = User::create([
                    'name'=>$item[1],
                    'email'=>$item[0],
                    'password'=>Hash::make($item[2]),
                    'level'=>strtoupper($item[3]),
                ]);

                $user->offices()->create([
                    'office_id'=>$item[4]
                ]);
            }
            $ctr++;
        }
    }
}