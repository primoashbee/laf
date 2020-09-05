<?php

namespace App\Http\Controllers;

use DB;
use App\CWE;
use App\PPI;
use App\Client;
use App\Office;
use ZipArchive;
use Carbon\Carbon;
use App\ExcelReader;
use App\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ClientImport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Database\Eloquent\Builder;
use Revolution\Google\Sheets\Facades\Sheets;

class UploadController extends Controller
{
    //
    public function index(Request $request){
        $branch = auth()->user()->office->first()->name;
        
        $clients = Client::batches();
        if(auth()->user()->is_admin){
            if($request->has('branch')){
                // $clients = Client::where('received',true)->where('branch',$request->branch)->get();
                $clients = Client::batches()->where('branch',$request->branch);
            }
            // dd('dito');
        }else{
            
            if($request->has('branch')){
                
                if($request->branch != $branch){
                    abort(404);
                }
                $clients = Client::batches()->where('branch',$request->branch);
            }
            
            $clients = Client::batches()->where('branch', $branch);

            if (auth()->user()->level!="MANAGER") {
                $branch = auth()->user()->office->first()->getTopOffice('branch')->name;
                
                $clients = Client::batches()->where('branch', $branch);
                
                
            }
            
            
        }
        $offices = Office::where('level','branch')->orderBy('name','asc')->get();

        return view('home',compact('clients','offices'));
    }


    public function batchByNameAndDate(Request $request){

        $offices = Office::where('level','branch')->orderBy('name','asc')->get();
        $clients = Client::where('branch',$request->branch)->where(DB::raw('date(timestamp)'),$request->date);
        $client_list = Client::where('branch',$request->branch)->where(DB::raw('date(timestamp)'),$request->date)->get();
        
        if(auth()->user()->level!="MANAGER"){
            
            $client_list = Client::where('branch',$request->branch)
            ->where(DB::raw('RIGHT(loan_officer,1)'),auth()->user()->level)
            ->where(DB::raw('date(timestamp)'),$request->date)
            ->get();
        }
        
        return view('client-list',compact('offices','clients','client_list'));
    }

    
    public function printList($clients){
        $template = public_path('LAF Final Template.docx');

        $folder = uniqid();
        File::makeDirectory(Storage::disk('public')->path($folder));
        
        $ctr= 0;
        
        foreach ($clients as $client) {
            $name = $client->personalInformation()->name;
            $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.'('.$ctr.') '.$name.'.docx');
            
            $this->printClient($client,$folder);
            $ctr++;
        }

        
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
            return $zipFileName;
            return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    public function download(Request $request){
        $branch = auth()->user()->office->first()->name;
        $printed = false;
        $clients = Client::where('branch', $request->branch)->where(DB::raw('date(timestamp)'), $request->date)->get();

        if(auth()->user()->level!="MANAGER"){
            $clients = Client::where('branch', $request->branch)
                    ->where(DB::raw('date(timestamp)'), $request->date)
                    ->where(DB::raw('RIGHT(loan_officer,1)'),auth()->user()->level)
                    ->get();
        }
        $file = $this->printList($clients);
        
        session()->flash('download',$file);

        return response()->download($file)->deleteFileAfterSend(true);
        
    }

    public function exportClient($id){
        $user = auth()->user()->office->first();
        $client = Client::find($id)->load('ppi','cwe');
        if(!auth()->user()->is_admin){
            if($client->branch != auth()->user()->office->first()->name){
                abort(403);
            }
        }
        $folder = uniqid();
        File::makeDirectory(Storage::disk('public')->path($folder));
        $newFile = $this->printClient($client,$folder);
        
        return response()->download($newFile)->deleteFileAfterSend(true);
    }

    public function printClient($client,$folder){

        $template = public_path('LAF Final Template.docx');
        
        $name = $client->personalInformation()->name;
        

        $templateProcessor = new TemplateProcessor($template);
        
        $templateProcessor->setValue('date',$client->timestamp->format('F d, Y'));
        $templateProcessor->setValue('name',$client->personalInformation()->name);
        $templateProcessor->setValue('nickname',$client->personalInformation()->nickname);
        $templateProcessor->setValue('present_home_address',Str::limit($client->personalInformation()->present_home_address,57,'...'));
        $templateProcessor->setValue('years_of_stay',$client->personalInformation()->years_of_stay);
        $templateProcessor->setValue('business_address',Str::limit($client->personalInformation()->busines_farm_address,57,'...'));
        
        if($client->personalInformation()->house_type =="Rented"){
            $templateProcessor->setValue('hr','X');
            $templateProcessor->setValue('ho','');
        }else{
            $templateProcessor->setValue('ho','X');
            $templateProcessor->setValue('hr','');
        }
        $templateProcessor->setValue('birthplace',$client->personalInformation()->birthplace);
        $templateProcessor->setValue('birthday',$client->personalInformation()->birthday);
        $templateProcessor->setValue('age',$client->personalInformation()->age);

        if($client->personalInformation()->gender =="Female"){
            $templateProcessor->setValue('f','X');
            $templateProcessor->setValue('m','');
        }else{
            $templateProcessor->setValue('m','X');
            $templateProcessor->setValue('f','');
        }
        $templateProcessor->setValue('age',$client->personalInformation()->age);
        $templateProcessor->setValue('tin',$client->personalInformation()->tin);

        if ($client->personalInformation()->civil_status =="Single") {
            $templateProcessor->setValue('cs', 'X');
            $templateProcessor->setValue('cm', '');
            $templateProcessor->setValue('cw', '');
            $templateProcessor->setValue('cse', '');
        }else if($client->personalInformation()->civil_status =="Married"){
            $templateProcessor->setValue('cs', '');
            $templateProcessor->setValue('cm', 'X');
            $templateProcessor->setValue('cw', '');
            $templateProcessor->setValue('cse', '');
        }else if($client->personalInformation()->civil_status =="Widow"){
            $templateProcessor->setValue('cs', '');
            $templateProcessor->setValue('cm', '');
            $templateProcessor->setValue('cw', 'X');
            $templateProcessor->setValue('cse', '');
        }else if($client->personalInformation()->civil_status =="Separated"){
            $templateProcessor->setValue('cs', '');
            $templateProcessor->setValue('cm', '');
            $templateProcessor->setValue('cw', '');
            $templateProcessor->setValue('cse', 'X');
        }else{
            $templateProcessor->setValue('cs', '');
            $templateProcessor->setValue('cm', '');
            $templateProcessor->setValue('cw', '');
            $templateProcessor->setValue('cse', '');
        }

        $templateProcessor->setValue('umid', $client->personalInformation()->other_ids);

        if ($client->personalInformation()->education=="Post Graduate") {
            $templateProcessor->setValue('ep', 'X');
            $templateProcessor->setValue('ec', '');
            $templateProcessor->setValue('eh', '');
            $templateProcessor->setValue('ee', '');
            $templateProcessor->setValue('eo', '');
        }else if($client->personalInformation()->education=="College"){
            $templateProcessor->setValue('ep', '');
            $templateProcessor->setValue('ec', 'X');
            $templateProcessor->setValue('eh', '');
            $templateProcessor->setValue('ee', '');
            $templateProcessor->setValue('eo', '');
        }else if($client->personalInformation()->education=="High School"){
            $templateProcessor->setValue('ep', '');
            $templateProcessor->setValue('ec', '');
            $templateProcessor->setValue('eh', 'X');
            $templateProcessor->setValue('ee', '');
            $templateProcessor->setValue('eo', '');
        }else if($client->personalInformation()->education=="Elementary"){
            $templateProcessor->setValue('ep', '');
            $templateProcessor->setValue('ec', '');
            $templateProcessor->setValue('eh', '');
            $templateProcessor->setValue('ee', 'X');
            $templateProcessor->setValue('eo', '');
        }else if($client->personalInformation()->education=="Elementary"){
            $templateProcessor->setValue('ep', '');
            $templateProcessor->setValue('ec', '');
            $templateProcessor->setValue('eh', '');
            $templateProcessor->setValue('ee', '');
            $templateProcessor->setValue('eo', 'X');
        }else{
            $templateProcessor->setValue('ep', '');
            $templateProcessor->setValue('ec', '');
            $templateProcessor->setValue('eh', '');
            $templateProcessor->setValue('ee', '');
            $templateProcessor->setValue('eo', '');
        }
        $templateProcessor->setValue('fb_account', $client->personalInformation()->fb_account);
        $templateProcessor->setValue('contact', $client->personalInformation()->mobile_number);
        $templateProcessor->setValue('s_name', $client->personalInformation()->spouse_name);
        $templateProcessor->setValue('s_contact', $client->personalInformation()->spouse_mobile);
        $templateProcessor->setValue('s_birthday', $client->personalInformation()->spouse_birthday);
        $templateProcessor->setValue('s_age', $client->personalInformation()->spouse_age);
        $templateProcessor->setValue('mother_name', $client->personalInformation()->mother_maiden_name);
        $templateProcessor->setValue('dependents', $client->personalInformation()->number_of_dependents);
        $templateProcessor->setValue('household', $client->personalInformation()->household_size);

        //personal preferences
        $templateProcessor->setValue('pr1_name', $client->personalPreferences()->person_1_name);
        $templateProcessor->setValue('pr1_contact', $client->personalPreferences()->person_1_contact);
        $templateProcessor->setValue('pr1_address', Str::limit($client->personalPreferences()->person_1_address,30,'...'));
        $templateProcessor->setValue('pr2_name', $client->personalPreferences()->person_2_name);
        $templateProcessor->setValue('pr2_contact', $client->personalPreferences()->person_2_contact);
        $templateProcessor->setValue('pr2_address', Str::limit($client->personalPreferences()->person_2_address,30,'...'));
        
        
        //ppi
        $templateProcessor->setValue('q1', $client->ppiSummary()->q1->score);
        $templateProcessor->setValue('q2', $client->ppiSummary()->q2->score);
        $templateProcessor->setValue('q3', $client->ppiSummary()->q3->score);
        $templateProcessor->setValue('q4', $client->ppiSummary()->q4->score);
        $templateProcessor->setValue('q5', $client->ppiSummary()->q5->score);
        $templateProcessor->setValue('q6', $client->ppiSummary()->q6->score);
        $templateProcessor->setValue('q7', $client->ppiSummary()->q7->score);
        $templateProcessor->setValue('q8', $client->ppiSummary()->q8->score);
        $templateProcessor->setValue('q9', $client->ppiSummary()->q9->score);
        $templateProcessor->setValue('q10', $client->ppiSummary()->q10->score);
        $templateProcessor->setValue('qts', $client->ppiSummary()->total);

        //householdincome
        if ($client->householdIncomeSummary()->client->self_employed=='Yes') {
            $templateProcessor->setValue('e', 'X');
        }else{
            $templateProcessor->setValue('e', '');
        }
        $templateProcessor->setValue('service_type', $client->householdIncomeSummary()->client->self_employed_business_type);
        $templateProcessor->setValue('b_mgi', $client->householdIncomeSummary()->client->self_employed_gross_income);
        if($client->householdIncomeSummary()->client->other_income !=""){
            $templateProcessor->setValue('o','X');
            $templateProcessor->setValue('o',$client->householdIncomeSummary()->client->other_income);
            $templateProcessor->setValue('o_mgi',$client->householdIncomeSummary()->client->other_income_amount);
        }else{
            $templateProcessor->setValue('o','');
            $templateProcessor->setValue('o_name','');
            $templateProcessor->setValue('o_mgi','');
        }
        if ($client->householdIncomeSummary()->spouse->self_employed=='Yes') {
            $templateProcessor->setValue('se', 'X');
        }else{
            $templateProcessor->setValue('se', '');
        }
        $templateProcessor->setValue('s_service_type', $client->householdIncomeSummary()->spouse->self_employed_business_type);
        $templateProcessor->setValue('se_mgi', $client->householdIncomeSummary()->spouse->self_employed_gross_income);
        if($client->householdIncomeSummary()->spouse->employed=='Yes'){
            $templateProcessor->setValue('sep','X');
            $templateProcessor->setValue('position',$client->householdIncomeSummary()->spouse->employed_position);
            $templateProcessor->setValue('company',$client->householdIncomeSummary()->spouse->employed_company);
            $templateProcessor->setValue('sep_mgi',$client->householdIncomeSummary()->spouse->employed_gross_income);
        }else{
            $templateProcessor->setValue('sep','');
            $templateProcessor->setValue('position','');
            $templateProcessor->setValue('company','');
            $templateProcessor->setValue('sep_mgi','');
        }

        if($client->householdIncomeSummary()->spouse->other_income !=""){
            $templateProcessor->setValue('o','X');
            $templateProcessor->setValue('so',$client->householdIncomeSummary()->spouse->other_income);
            $templateProcessor->setValue('so_mgi',$client->householdIncomeSummary()->spouse->other_income_amount);
        }else{
            $templateProcessor->setValue('so','');
            $templateProcessor->setValue('so_name','');
            $templateProcessor->setValue('so_mgi','');
        }
        if($client->householdIncomeSummary()->other->remittances > 0){
            $templateProcessor->setValue('rem','X');
        }else{
            $templateProcessor->setValue('rem','');
        }
        if($client->householdIncomeSummary()->other->pension > 0){
            $templateProcessor->setValue('pen','X');
        }else{
            $templateProcessor->setValue('pen','');
        }
        $templateProcessor->setValue('total_others',$client->householdIncomeSummary()->other->total_income);
        $templateProcessor->setValue('total_hh',$client->householdIncomeSummary()->total);
        $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.$name.'.docx');
        $templateProcessor->saveAs($newFile);
        return $newFile;
    }


}
