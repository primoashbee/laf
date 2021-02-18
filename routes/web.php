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

// Route::get('/structure',function(){
//         // seedPilotUsers();
//         // Artisan::call('migrate:fresh --seed');

//         $users = Users::all();
//         return view('test',compact('users'));
// });

Route::group(['middleware' => ['auth']], function () {
        
        Route::get('/', function(){
                return response()->redirectTo('/home');
        });

        Route::group(['middleware'=>['password.changed','account.enabled']], function () {
                Route::get('/usr/branches','UserController@branches');
                Route::get('/home', 'ClientController@index')->name('home');
                
                Route::post('/download','ClientController@download')->name('download.list');
                
                Route::get('/export/{id}', 'ClientController@exportClient')->name("client.export") ;
                Route::get('/delete/client/{id}','ClientController@delete')->name('client.delete');
                
                Route::get('/create/client','ClientController@createClient');
                Route::post('/create/client','ClientController@store')->name('client.create');
                Route::get('/update/client/{id}','ClientController@update')->name('client.update');
                Route::post('/update/client/{Client}','ClientController@updateClient')->name('client.update.post');
                Route::group(['middleware'=>['is.admin']], function () {
                        Route::get('/user/disable/{id}','UserController@disable')->name('user.disable');
                        Route::get('/user/enable/{id}','UserController@enable')->name('user.enable');
                        Route::get('/users','UserController@list')->name('users');
                        Route::get('/user/reset/{id}','UserController@reset')->name('user.reset');
                });
                
                
        
        });
   
        Route::get('/changepass', function(Request $request){
                return view('changepass');
        })->name('change.password')->middleware('auth');
        Route::post('/changepass', 'UserController@changePassword')->name('change.password')->middleware('auth');
        
});


Auth::routes();

