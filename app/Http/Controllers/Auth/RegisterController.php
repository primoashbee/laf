<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\OfficeUser;
use App\Rules\OfficeID;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Rules\UserLevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'level' => ['required', 'string', new UserLevel],
            'branch_id' => ['required', new OfficeID],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'branch_id.required'=>'Branch must be selected'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $is_admin = false;
        if($data['branch_id']=="1"){
            $is_admin = true;
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'level'=>$data['level'],
            'is_admin'=>$is_admin,
            'password' => Hash::make($data['password']),
        ]);
        OfficeUser::create([
            'user_id'=>$user->id,
            'office_id'=>$data['branch_id'],
        ]);
        return $user;
    }
}
