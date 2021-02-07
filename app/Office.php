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


        
    public function children(){
        return $this->hasMany(static::class, 'parent_id');
    }   

    public function getLowerOfficeIDS($insert_self = true){
        $id = $this->id;
        $child_ids = $this->getAllChildrenIDS();
        if ($insert_self) {
            return array_merge($child_ids, [$id]);
        }
        return $child_ids;
    }
    public function getAllChildren($insert_self = true){
        $children = $this->children;
        $ids = [];

        if ($insert_self) {
            array_push($ids,$this);
            return array_merge($ids, $this->getAllChildren(false));
        }
        foreach ($children as $child) {
            array_push($ids,$child);
            $ids = array_merge($ids, $child->getAllChildren(false));
        }
        return $ids;
    }


    public function getAllChildrenIDS(){
        $children = $this->children;
        $ids = [];
            foreach ($children as $child) {
                array_push($ids,$child->id);
                $ids = array_merge($ids, $child->getAllChildrenIDs());
            }
        return $ids;
    }
    public static function canBeAccessedBy($office_id, $user_id){
        return in_array($office_id, User::find($user_id)->office->first()->getLowerOfficeIDS());
    }
}
