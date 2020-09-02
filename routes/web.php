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

Route::get('/mail',function(){
        Mail::raw('From new gmail' , function ($message) {
                $message->to('ashbee.morgado@icloud.com')->subject('Waddduppp mah nigga');
        });
});
Route::group(['middleware' => ['auth','password.changed','account.enabled']], function () {
        Route::get('/import','UploadController@import');
        Route::get('/home', 'UploadController@index')->name('home');
        Route::post('/home', 'UploadController@upload')->name('form.upload');
        Route::get('/', function(){
                return response()->redirectTo('/home');
        });
        Route::get('/printed','UploadController@printed')->name('forms.printed');
        Route::get('/unprinted','UploadController@unPrinted')->name('forms.unprinted');
        // Route::get('/batch/{batch_id}','UploadController@byBatch')->name('forms.by.batch');
        Route::get('/batch/{branch}/{date}','UploadController@batchByNameAndDate')->name('forms.by.batch.and.date');
        Route::get('/print/list','UploadController@download')->name('print.list');
        Route::get('/download','UploadController@download')->name('download.list');
        // Route::post('/downloadFile', function(Request $request){
        //         return response()->download($file)->deleteFileAfterSend(true);
        // })->name('download.file');
        Route::get('/export/{id}', 'UploadController@exportClient');     
        Route::get('/export', 'UploadController@getClients');        
        Route::get('/admin', 'UploadController@admin')->middleware('is.admin');

        Route::get('/users','UserController@list');
        // Route::get('/gdrive/users','UserController@pullAccountsFromGoogleDrive');

        Route::get('/user/reset/{id}','UserController@reset')->name('user.reset');
        Route::get('/user/disable/{id}','UserController@disable')->name('user.disable');
        Route::get('/user/enable/{id}','UserController@enable')->name('user.enable');
       
});

Route::get('/changepass', function(Request $request){
        return view('changepass');
})->name('change.password')->middleware('auth');
Route::post('/changepass', 'UserController@changePassword')->name('change.password')->middleware('auth');
// Route::get('/dl',function(){
//         $str = '1AyIUrmAeT_kZfu7xRJKRqC03zH9TnP03';
//         $contents = collect(Storage::disk('google')->listContents('/',false));
//         $dl =  $contents
//         ->where('type', '=', 'file')
//         ->where('basename','=',$str)
//         ->first();
//         $rawData = Storage::disk('google')->get($dl['path']);
//         $name = $dl['name'];        
//         return response($rawData, 200)
//         ->header('ContentType', $dl['mimetype'])
//         ->header('Content-Disposition', "attachment; filename=$name");
        
//         // return 'hey';
//         // $file = $contents
//         // ->where('type', '=', 'file')
//         // ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
//         // ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
//         // ->first(); 
        
        
    
// //     $disk = Storage::disk('google');

// //     $path = 'https://drive.google.com/open?id=1Yl6_0_f70llyCXZdFen-okGkVcaRQeq_';

// //    $filename = 'temp-image.jpg';
// //    $tempImage = tempnam(sys_get_temp_dir(), $filename);
// //    copy($path, $tempImage);

// //    return response()->download($tempImage, $filename);


// });

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
