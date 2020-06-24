<?php 

use App\Office;
use App\User;
use App\OfficeUser;
use Carbon\Carbon;
use App\Imports\OfficeImport;
    function generateStucture(){
        $structure = Excel::toCollection(new OfficeImport, "public/OFFICE STRUCTURE.xlsx");
        $data = array();
        $ctr = 0;

        foreach($structure[0] as $level){
            if ($ctr>0) {
                $data[] = array(
                'id'=>$level[0],
                'parent_id'=>$level[2],
                'level'=>$level[4],
                'name'=>$level[3],
                'code'=>$level[1],
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now(),
                );
                // echo $level[3].' : '.$level[4].'<br>';
            }
            $ctr++;
        }
        
        Office::insert($data);
    }

    function createAdminAccount(){
        $user = User::create([
            'name' => 'Nelson Abilgos Tan',
            'email' => 'nelson.tan@light.org.ph',
            'is_admin' => true,
            'password' => Hash::make('tannelsona')
        ]);

        OfficeUser::create([
            'user_id'=>$user->id,
            'office_id'=>1
        ]);   
        $user = User::create([
            'name' => 'Ashbee Morgado',
            'email' => 'ashbee.morgado@icloud.com',
            'is_admin' => true,
            'password' => Hash::make('sv9h4pld')
        ]);

        OfficeUser::create([
            'user_id'=>$user->id,
            'office_id'=>1
        ]);   
    }

?>