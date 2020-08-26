<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
}
