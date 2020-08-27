<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['name','code','parent_id','level'];

    public function parent(){
        return $this->belongsTo(static::class, 'parent_id');
    }
    
    public function getParent(){
        return $this->parent;
    }
    function getTopOffice($level="main_office"){
        
        if($this->level==$level){
            return $this;
        }
        $parent = $this->getParent();
        if($parent == null){
            return $parent;
        }
        if($parent->level == $level){
         return $parent;
        }else{
            return $parent->getTopOffice($level);
        }
    }
}
