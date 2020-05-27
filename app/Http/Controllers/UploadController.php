<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\ClientImport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class UploadController extends Controller
{
    //
    public function index(){
        return view('upload');
    }
    public function upload1(){
        $template = Storage::disk('public')->path('LAF Final Template.docx');
        $templateProcessor = new TemplateProcessor($template);
        $templateProcessor->setValue('e', 'X');
        $templateProcessor->setValue('name', 'Morgado, John Ashbee, A.');
        $row = 'pito';
        $score = 0;
        
        if($row ==''){
            $score =2;
            
        }
        if($row =='Anim'){
            $score = 6;
        }

        $templateProcessor->setValue('s1', $score);
        
       
        
        $templateProcessor->setValue('s1', $score);
        $folder = uniqid();
        File::makeDirectory(Storage::disk('public')->path($folder));
        $newFile = Storage::disk('public')->path($folder.'/LAF Record - TEST.docx');
        $templateProcessor->saveAs($newFile);

        
        

    }
    public function upload(Request $request){
        // dd(auth()->user()->office()->first()->name);
        if($request->hasFile('uploadFile')){
            $file = $request->file('uploadFile');
            $array = Excel::toCollection([], $file);
            $template = Storage::disk('public')->path('LAF Template.docx');
            $ctr = 0;
            
            $folder = uniqid();
            File::makeDirectory(Storage::disk('public')->path($folder));
            foreach($array[0] as $key => $value){
                //68 is branch
                
                if (strtoupper(auth()->user()->office()->first()->name) === strtoupper($value[68])) {
                    if ($ctr > 0) {
                        $templateProcessor = new TemplateProcessor($template);
                        $name = ucwords($value[1]). ' '. ucwords($value[2]). ' '. ucwords($value[3]);
                        $templateProcessor->setValue('name', $name);
                        $nickname = ucwords($value[4]);
                        $templateProcessor->setValue('nickname', $nickname);
                        $present_address = ucwords($value[5]);
                        $templateProcessor->setValue('present_address', $present_address);
                        $templateProcessor->setValue('years_of_stay', $value[6]);
                        $templateProcessor->setValue('business_address', $value[7]);
                        $owned= '';
                        $rented= '';
                        if ($value[8] == "Owned") {
                            $owned = 'X';
                        }

                        if ($value[8] == "Rented") {
                            $rented = 'X';
                        }

                        $templateProcessor->setValue('house_owned', $owned);
                        $templateProcessor->setValue('house_rented', $rented);

                        $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[9]));
                        
                        $templateProcessor->setValue('birthday', $date->toDateString());
                        $templateProcessor->setValue('age', $date->age);

                        $male= '';
                        $female= '';
                        if ($value[10] == "Male") {
                            $male = 'X';
                        }

                        if ($value[10] == "Female") {
                            $female = 'X';
                        }
                        
                        $templateProcessor->setValue('is_male', $male);
                        $templateProcessor->setValue('is_female', $female);
                        
                        $templateProcessor->setValue('birthplace', ucwords($value[11]));
                        
                        $single= '';
                        $married= '';
                        $widowed= '';
                        $separated= '';
                        if ($value[12] == "Single") {
                            $single = 'X';
                        }

                        if ($value[12] == "Married") {
                            $married = 'X';
                        }

                        if ($value[12] == "Widow") {
                            $widowed = 'X';
                        }

                        if ($value[12] == "Separated") {
                            $separated = 'X';
                        }

                        $templateProcessor->setValue('is_single', $single);
                        $templateProcessor->setValue('is_married', $married);
                        $templateProcessor->setValue('is_widowed', $widowed);
                        $templateProcessor->setValue('is_separated', $separated);
                        

                        
                        $post_grad= '';
                        $college = '';
                        $highschool= '';
                        $elementary= '';
                        $others= '';
                        if ($value[13] == "Post Graduate") {
                            $post_grad = 'X';
                        }

                        if ($value[13] == "College") {
                            $college = 'X';
                        }

                        if ($value[13] == "High School") {
                            $highschool = 'X';
                        }

                        if ($value[13] == "Elementary") {
                            $elementary = 'X';
                        }

                        if ($value[13] != "Post Graduate" && $value[12] != "College" && $value[12] != "High School" && $value[12] != "Elementary") {
                            $others = $value[13];
                        }

                        $templateProcessor->setValue('is_post_graduate', $post_grad);
                        $templateProcessor->setValue('is_college', $college);
                        $templateProcessor->setValue('is_highschool', $highschool);
                        $templateProcessor->setValue('is_elementary', $elementary);
                        $templateProcessor->setValue('is_others', $others);

                        $templateProcessor->setValue('fb_account', $value[14]);
                        $templateProcessor->setValue('contact', $value[15]);

                        $templateProcessor->setValue('tin', $value[16]);

                        $templateProcessor->setValue('other_ids', $value[17]);
                        
                        $spouse_name = $value[20].' '.$value[18].' '.$value[19];

                        $templateProcessor->setValue('spouse_name', $spouse_name);

                        $templateProcessor->setValue('spouse_contact', $value[21]);

                        $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[22]));
                        $templateProcessor->setValue('spouse_birthday', $date->toDateString());

                        $templateProcessor->setValue('spouse_age', $date->age);

                        $templateProcessor->setValue('dependents', $value[24]);

                        $templateProcessor->setValue('household', $value[25]);

                        $templateProcessor->setValue('mother_name', $value[26]);
                        

                        // $filename = $value[61];
                        // $tempImage = tempnam(sys_get_temp_dir(), $filename);
                        // copy($value[61], $tempImage);

                        // $aImgs = array(
                        //     array(
                        //         'img' => 'phpword/Examples/_earth.JPG',
                        //         'size' => array(200, 150),
                        //         'dataImg' => 'Esta es el pie de imagen para _earth.JPG'
                        //     )
                        // );
                        // $templateProcessor->setImg($filename,);
                        // $document->replaceStrToImg( 'profile', $templateProcessor);

                        $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.$name.'.docx');
                        $templateProcessor->saveAs($newFile);
                    }
                }
                $ctr++;
                
            }
            
            if ($ctr > 1) {
                $zip = new ZipArchive();
                
                $zipFileName = storage_path('app/public/'.$folder).'.zip';

                $files = Storage::disk('public')->files($folder);
            

                $path = storage_path('app/public/'.$folder);

    

                $files = File::allfiles($path);
              
                if ($zip->open($zipFileName, ZIPARCHIVE::CREATE) === false) {
                    return 'Something went wrong';
                }
                foreach ($files as $file) {
                    $zip->addFile($file->getPathname(), $file->getFilename());
                }
                $zip->close();
               
                File::deleteDirectory(storage_path('app/public/'.$folder));
                return response()->download($zipFileName)->deleteFileAfterSend(true);

                return 'meron';
            }
        }
        return 'No records found yet.';
        // return view('upload');
    }
}
