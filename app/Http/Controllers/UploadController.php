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
    public function upload(Request $request){
        $q1 = 0;
        $q2 = 0;
        $q3 = 0;
        $q4 = 0;
        $q5 = 0;
        $q6 = 0;
        $q7 = 0;
        $q8 = 0;
        $q9 = 0;
        $q10 = 0;
        if($request->hasFile('uploadFile')){
            $file = $request->file('uploadFile');
            $array = Excel::toCollection([], $file);
            $template = Storage::disk('public')->path('LAF Final Template.docx');        
            $ctr = 0;
            $folder = uniqid();
            File::makeDirectory(Storage::disk('public')->path($folder));
            foreach($array[0] as $key => $value){
                
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
                    if($value[8] == "Owned"){
                        $owned = 'X';
                    }

                    if($value[8] == "Rented"){
                        $rented = 'X';
                    }

                    $templateProcessor->setValue('house_owned', $owned);
                    $templateProcessor->setValue('house_rented', $rented);

                    $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[9]));
                    
                    $templateProcessor->setValue('birthday', $date->toDateString());
                    $templateProcessor->setValue('age', $date->age);

                    $male= '';
                    $female= '';
                    if($value[10] == "Male"){
                        $male = 'X';
                    }

                    if($value[10] == "Female"){
                        $female = 'X';
                    }
                    
                    $templateProcessor->setValue('is_male', $male);
                    $templateProcessor->setValue('is_female', $female);
                    
                    $templateProcessor->setValue('birthplace', ucwords($value[11]));
                    
                    $single= '';
                    $married= '';
                    $widowed= '';
                    $separated= '';
                    if($value[12] == "Single"){
                        $single = 'X';
                    }

                    if($value[12] == "Married"){
                        $married = 'X';
                    }

                    if($value[12] == "Widow"){
                        $widowed = 'X';
                    }

                    if($value[12] == "Separated"){
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
                    if($value[13] == "Post Graduate"){
                        $post_grad = 'X';
                    }

                    if($value[13] == "College"){
                        $college = 'X';
                    }

                    if($value[13] == "High School"){
                        $highschool = 'X';
                    }

                    if($value[13] == "Elementary"){
                        $elementary = 'X';
                    }

                    if($value[13] != "Post Graduate" && $value[12] != "College" && $value[12] != "High School" && $value[12] != "Elementary"){
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

                    $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[30]));
                    $templateProcessor->setValue('spouse_birthday', $date->toDateString());

                    $templateProcessor->setValue('spouse_age', $date->age);

                    $templateProcessor->setValue('dependents', $value[24]);

                    $templateProcessor->setValue('household', $value[25]);

                    $templateProcessor->setValue('mother_name', $value[26]);
                    

                // Progress Poverty Index


                if ($value[57] == "Walo o Higit pa") {
                    $q1 = 0;
                }
                if ($value[57] == "Pito") {
                    $q1 = 2;
                }
                if ($value[57] == "Anim") {
                    $q1 = 6;
                }
                if ($value[57] == "Lima") {
                    $q1 = 11;
                }
                 if ($value[57] == "Apat") {
                    $q1 = 15;
                }
                if ($value[57] == "Tatlo") {
                    $q1 = 21;
                }
                if ($value[57] == "Isa o Dalawa") {
                    $q1 = 30;
                }

                // Question 2

                if ($value[58] == "Hindi") {
                    $q2 = 0;
                }

                if ($value[58] == "Oo") {
                    $q2 = 1;
                }

                if ($value[58] == "Walang may edad 6-17") {
                    $q2 = 2;
                }

                // Question 3

                if ($value[59] == "Wala") {
                    $q3 = 0;
                }
                if ($value[59] == "Isa") {
                    $q3 = 2;
                }

                if ($value[59] == "Dalawa") {
                    $q3 = 7;
                }

                if ($value[59] == "Tatlo o higit pa") {
                    $q3 = 12;
                }


                // Question 4

                if ($value[60] == "Tatlo o higit pa") {
                    $q4 = 0;
                }

                if ($value[60] == "Dalawa") {
                    $q4 = 4;
                }

                if ($value[60] == "Isa") {
                    $q4 = 8;
                }

                if ($value[60] == "Wala") {
                    $q4 = 12;
                }

                // Question 5

                if ($value[61] == "Elementary o No Grade Completed") {
                    $q5 = 0;
                }

                if ($value[61] == "Walang babaeng puno ng pamilya") {
                    $q5 = 2;
                }

                if ($value[61] == "Elementary or HS undergrad") {
                    $q5 = 2;
                }
                
                if ($value[61] == "High School Graduate") {
                    $q5 = 4;
                }

                if ($value[61] == "College undergrad o higit pa") {
                    $q5 = 7;
                }

                // Question 6

                if ($value[62] == "Light Materials (LM) (cogon/nipa/anahaw) or mixed but more LM") {
                    $q6 = 0;
                }
                if ($value[62] == "Mixed but predominantly strong materials") {
                    $q6 = 2;
                }

                if ($value[62] == "Strong materials (galvanized iron, aluminum, tile, concrete, brick, stone, wood, plywood, asbestos)") {
                    $q6 = 3;
                }
                
                // Question 7

                if ($value[63] == "Hindi (Walang pagmamay-ari)") {
                    $q7 = 0;
                }

                if ($value[63] == "Oo (Mayroong pagmamay-ari)") {
                    $q7 = 3;
                }

                // Question 8
                if ($value[64] == "Wala (Walang pagmamay-ari)") {
                    $q8 = 0;
                }

                if ($value[64] == "1 sa nabanggit, pero hindi pareho") {
                    $q8 = 6;
                }

                if ($value[64] == "Parehong may pagmamay-ari") {
                    $q8 = 12;
                }

                // Question 9

                if ($value[65] == "Hindi/ Wala") {
                    $q9 = 0;
                }

                if ($value[65] == "TV lamang") {
                    $q9 = 4;
                }
                if ($value[65] == "TV/ VCD/DVD player") {
                    $q9 = 7;
                }

                // Question 10

                if ($value[66] == "Wala") {
                    $q10 = 0;
                }

                if ($value[66] == "Isa") {
                    $q10 = 4;
                }

                if ($value[66] == "Dalawa") {
                    $q10 = 7;
                }
                if ($value[66] == "Tatlo o Higit pa") {
                    $q10 = 12;
                }   
                $qts = $q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10;     
                $templateProcessor->setValue('q1', $q1);
                $templateProcessor->setValue('q2', $q2);
                $templateProcessor->setValue('q3', $q3);
                $templateProcessor->setValue('q4', $q4);
                $templateProcessor->setValue('q5', $q5);
                $templateProcessor->setValue('q6', $q6);
                $templateProcessor->setValue('q7', $q7);
                $templateProcessor->setValue('q8', $q8);
                $templateProcessor->setValue('q9', $q9);
                $templateProcessor->setValue('q10', $q10);
                $templateProcessor->setValue('qts', $qts);


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
                $ctr++;
                
            }
            
            $zip = new ZipArchive();
            $zipFileName = storage_path('app/public/'.$folder).'.zip';

            $files = Storage::disk('public')->files($folder);
            

            $path = storage_path('app/public/'.$folder);

    

            $files = File::allfiles($path);
            if($zip->open($zipFileName, ZIPARCHIVE::CREATE) === false)
            {
                return 'Something went wrong';
            }
            foreach($files as $file) {
                $zip->addFile($file->getPathname(),$file->getFilename());
               }
            $zip->close();
            File::deleteDirectory(storage_path('app/public/'.$folder));
            return response()->download($zipFileName)->deleteFileAfterSend(true);

            return 'meron';
        }
        return 'hey';
        // return view('upload');
    }
}
