<?php 

use App\User;
use App\Office;
use Carbon\Carbon;
use App\OfficeUser;
use App\Imports\OfficeImport;
use Illuminate\Support\Facades\Hash;
    function generateStucture(){
        $structure = Excel::toCollection(new OfficeImport, public_path("OFFICE STRUCTURE.xlsx"));
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

    function seedPilotUsers(){
        $users = Excel::toCollection(new OfficeImport, public_path("Users.xlsx"))[0];
        
        $ctr = 0;
        
        \DB::beginTransaction();

        try {
            foreach($users as $row){
                if($ctr>0){
                    
                    $data = array(
                        'name'=>$row[0],
                        'email'=>$row[1],
                        'password'=> Hash::make('lightmfi123'),
                        'is_admin'=>false,
                        'disabled'=>false,
                    );
                    $office_id = $row[3];
                    $user = User::create($data);
                    $user->office()->attach([$office_id]);
                }
                $ctr++;
            }
            \DB::commit();
        }catch(Exception $e){
            $e->getMessage();
        }
        
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