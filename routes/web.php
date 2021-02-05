<?php
date_default_timezone_set('Asia/Singapore');

use App\User;
use Illuminate\Http\Request;
use App\Mail\MailUserCredentials;
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => ['auth']], function () {
        
        Route::get('/home', 'UploadController@index')->name('home');
        Route::get('/', function(){
                return response()->redirectTo('/home');
        });
        Route::get('/printed','UploadController@printed')->name('forms.printed');
        Route::get('/unprinted','UploadController@unPrinted')->name('forms.unprinted');
        // Route::get('/batch/{batch_id}','UploadController@byBatch')->name('forms.by.batch');
        
        // Route::get('/date')
        Route::get('/download','UploadController@download')->name('download.list');
        // Route::post('/downloadFile', function(Request $request){
        //         return response()->download($file)->deleteFileAfterSend(true);
        // })->name('download.file');
        Route::get('/export/{id}', 'UploadController@exportClient');     
        // Route::get('/export', 'UploadController@getClients');        
        // Route::get('/admin', 'UploadController@admin')->middleware('is.admin');

        Route::get('/create/client','UploadController@createClient');
        Route::post('/create/client','UploadController@store')->name('create.client');
        Route::get('/user/reset/{id}','UserController@reset')->name('user.reset');
        Route::get('/user/disable/{id}','UserController@disable')->name('user.disable');
        Route::get('/user/enable/{id}','UserController@enable')->name('user.enable');
        Route::get('/users','UserController@list');
       
});

Route::get('/changepass', function(Request $request){
        return view('changepass');
})->name('change.password')->middleware('auth');
Route::post('/changepass', 'UserController@changePassword')->name('change.password')->middleware('auth');

Auth::routes();

