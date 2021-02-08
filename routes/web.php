<?php
date_default_timezone_set('Asia/Singapore');

use App\Http\Middleware\PasswordChanged;
use App\User;
use Illuminate\Http\Request;
use App\Mail\MailUserCredentials;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/structure',function(){
        seedPilotUsers();
        // Artisan::call('migrate:fresh --seed');

        // $offices = \App\Office::where('level','branch')->orderBy('name','asc')->get();
        // return view('test',compact('offices'));
});
Route::group(['middleware' => ['auth']], function () {
        
        Route::get('/', function(){
                return response()->redirectTo('/home');
        });

        Route::group(['middleware'=>['password.changed','account.enabled']], function () {
                Route::get('/usr/branches','UserController@branches');
                Route::get('/home', 'ClientController@index')->name('home');
                
                Route::post('/download','ClientController@download')->name('download.list');
                
                Route::get('/export/{id}', 'ClientController@exportClient');     
                
                Route::get('/create/client','ClientController@createClient');
                Route::post('/create/client','ClientController@store')->name('create.client');
                Route::get('/user/reset/{id}','UserController@reset')->name('user.reset');
                Route::get('/user/disable/{id}','UserController@disable')->name('user.disable');
                Route::get('/user/enable/{id}','UserController@enable')->name('user.enable');
                Route::get('/users','UserController@list');
        
        });
   
        Route::get('/changepass', function(Request $request){
                return view('changepass');
        })->name('change.password')->middleware('auth');
        Route::post('/changepass', 'UserController@changePassword')->name('change.password')->middleware('auth');
        
});


Auth::routes();

