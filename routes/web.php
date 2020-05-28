<?php

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
Route::get('/link','UploadController@get');
Route::group(['middleware' => ['auth']], function () {
        Route::get('/home', 'UploadController@index');
        Route::post('/home', 'UploadController@upload')->name('form.upload');
        Route::get('/', function(){
                return response()->redirectTo('/home');
        });
        Route::get('/test', 'UploadController@upload1');

        
});
Route::get('/dl',function(){
        $str = '1AyIUrmAeT_kZfu7xRJKRqC03zH9TnP03';
        $contents = collect(Storage::disk('google')->listContents('/',false));
        $dl =  $contents
        ->where('type', '=', 'file')
        ->where('basename','=',$str)
        ->first();
        $rawData = Storage::disk('google')->get($dl['path']);
        $name = $dl['name'];        
        return response($rawData, 200)
        ->header('ContentType', $dl['mimetype'])
        ->header('Content-Disposition', "attachment; filename=$name");
        
        // return 'hey';
        // $file = $contents
        // ->where('type', '=', 'file')
        // ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        // ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        // ->first(); 
        
        
    
//     $disk = Storage::disk('google');

//     $path = 'https://drive.google.com/open?id=1Yl6_0_f70llyCXZdFen-okGkVcaRQeq_';

//    $filename = 'temp-image.jpg';
//    $tempImage = tempnam(sys_get_temp_dir(), $filename);
//    copy($path, $tempImage);

//    return response()->download($tempImage, $filename);


});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
