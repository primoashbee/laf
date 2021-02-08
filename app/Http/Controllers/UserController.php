<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Office;
use App\GDriveUser;
use App\GDriverUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function branches(Request $request){
        return auth()->user()->scopesBranch($request->level);
    }
    public function changePassword(Request $request){
        
        Validator::make($request->all(), [
            'password' => 'required|min:8|max:255|confirmed',
        ])->validate();

        // dd(Hash::make($request->password));
        auth()->user()->update([
            'password'=>Hash::make($request->password),
            'password_changed'=>true
        ]);

        return redirect()->route('home');
    }
    public function list(Request $request){
        $users = User::all();

        return view('users',compact('users'));
    }

    public function reset($id){
        User::find($id)->reset();
        return redirect('/users');
    }
    public function enable($id){
        User::find($id)->enable();
        return redirect('/users');
    }
    public function disable($id){
        User::find($id)->disable();
        return redirect('/users');
    }
    public function pullAccountsFromGoogleDrive(){


        
        $offices = Office::where('level','unit')->orderBy('name','asc')->get();
        $client = new \Google_Client();
        $client->setApplicationName('My PHP App');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        // $jsonAuth = public_path('credentials.json');
        $jsonAuth = public_path('credentials-v2.json');
        $client->setAuthConfig($jsonAuth, true);

        $sheets = new \Google_Service_Sheets($client);



        // The range of A2:H will get columns A through H and all rows starting from row 2
        $spreadsheetId = '1ymdMHX48AeXIhmnSrLiG0nYQ5ZmtosZC52tjtPqkSOM';
        $range = 'A1:F';
        
        
        $rows = collect($sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']));
        
        
        $gdrive_users = new GDriveUser($rows);
        dd($gdrive_users->createUsers());

    }
}
