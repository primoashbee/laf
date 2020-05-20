<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ClientImport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class UploadController extends Controller
{
    //
    public function index(){
        return view('upload');
    }
    public function upload(Request $request){
        if($request->hasFile('uploadFile')){
            $file = $request->file('uploadFile');
            $array = Excel::toCollection([], $file);

            

            $template = Storage::disk('public')->path('LAF Template.docx');
            // $templateProcessor = new TemplateProcessor($template);
            $ctr = 0;
            $folder = uniqid();
            File::makeDirectory(Storage::disk('public')->path($folder));
            foreach($array[0] as $key => $value){
                
                if ($ctr > 0) {
                    $templateProcessor = new TemplateProcessor($template);
                    $templateProcessor->setValue('name', $value[0]);
                    $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.$value[0].'.docx');
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
            return response()->download($zipFileName);

            return 'meron';
        }
        return 'hey';
        // return view('upload');
    }
}
