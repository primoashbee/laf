<?php

namespace App\Http\Controllers;

use DB;
use App\CWE;
use App\PPI;
use App\Client;
use App\Office;
use App\Rules\Gender;
use App\Rules\SpouseSelfEmployed;
use App\Rules\Branch;
use App\Rules\CivilStatus;
use App\Rules\HouseType;
use App\Rules\BusinessType;
use App\Rules\Education;
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
    
    public function index(Request $request){

        // dd($request->branch);
        $branch = auth()->user()->office->first()->name;
        $q = $request->search;
        $clients = Client::paginate(25);

        if(auth()->user()->is_admin){
            
            if($request->branch != null){
                $clients = Client::where('branch',$request->branch)->paginate(25);
            }
            if($request->has('search')){
                $clients = Client::where('first_name','LIKE','%'.$q.'%')->orWhere('last_name','LIKE','%'.$q.'%')->orWhere('middle_name','LIKE','%'.$q.'%')->paginate(25);
            }
            if($request->has('from_date')){
                try {
                   $date_valid = $request->validate([
                    'from_date' => 'before_or_equal:today'
                    ]);   
                } catch (Exception $e) {
                    return redirect()->back()->with($e);
                }
                $current_date = Carbon::now();

                if($request->branch != null){
                    $clients = Client::where('branch', $request->branch)->whereBetween('created_at', [$request->from_date, $current_date])->paginate(25);
                    }else{
                    $clients = Client::whereBetween('created_at', [$request->from_date, $current_date])->paginate(25);
                }
            }
        }else{
            if($request->has('search')){
                $clients = Client::where('branch',$branch)->where('first_name','LIKE','%'.$q.'%')->orWhere('last_name','LIKE','%'.$q.'%')->orWhere('middle_name','LIKE','%'.$q.'%')->paginate(25);
            }else{
                $clients = Client::where('branch',$branch)->paginate(25);
            }
            if($request->has('from_date')){
                try {
                   $date_valid = $request->validate([
                    'from_date' => 'before_or_equal:today'
                    ]);   
                } catch (Exception $e) {
                    return redirect()->back()->with($e);
                }
                $current_date = Carbon::now();
                $clients = Client::where('branch', $branch)->whereBetween('created_at', [$request->from_date, $current_date])->paginate(25);
            }
            
        }
        $clients->appends(['q' => $q]); 

        $offices = Office::where('level','branch')->orderBy('name','asc')->get();
        return view('home',compact('clients','offices'));

    }

    public function createClient(){
        $offices = Office::where('level','branch')->get();
        return view('create-client', compact('offices'));
    }

    // public function printed(Request $request){

    //     $branch = auth()->user()->office->first()->name;
        
    //     $clients = Client::where('received',true)->paginate(25);

    //     if(auth()->user()->is_admin){
    //         if($request->has('branch')){
    //             $clients = Client::where('received',true)->where('branch',$request->branch)->paginate(1);
    //         }
            
    //     }else{
    //         if($request->has('branch')){
    //             if($request->branch != $branch){
    //                 abort(404);
    //             }
    //             $clients = Client::where('received',true)->where('branch',$request->branch)->paginate(25);
    //         }

    //         $clients = Client::where('received',true)->where('branch',$branch)->paginate(25);
    //     }
              
    //     $offices = Office::where('level','branch')->orderBy('name','asc')->get();
    //     return view('client-list',compact('clients','offices'));
    // }


    public function store(Request $request){
        
        try {
            $request->validate
                (
                    [
                        'first_name' => 'required',
                        'middle_name' => 'sometimes',
                        'last_name' => 'required',
                        'street_address' => 'required',
                        'barangay' => 'required',
                        'city' => 'required',
                        'zip_code' => 'required|integer',
                        'province' => 'required',
                        'years_of_stay' => 'required|integer',
                        'business_farm_street_address' => 'required',
                        'business_farm_city' => 'required',
                        'business_barangay' => 'required',
                        'business_farm_province' => 'required',
                        'business_farm_zip_code' => 'required',
                        'birthday' => 'required|date',
                        'birthplace' => 'required',
                        'mobile_number' => 'required|alpha_num',
                        'facebook_account_link' => 'required',
                        'spouse_mobile_number' => 'sometimes|alpha_num',
                        'mothers_maiden_name' => 'required',
                        'spouse_birthday' => 'sometimes|date',
                        'household_size' => 'required|integer',
                        'number_of_dependents' => 'required|integer',
                        'person_1_name' => 'required',
                        'person_1_whole_address' => 'required',
                        'person_1_contact_number' => 'required',
                        'estimated_monthly_income_for_business' => 'required',
                        'gender' => ['required', new Gender],
                        'civil_status' => ['required', new CivilStatus],
                        'education' => ['required', new Education],
                        'house' => ['required', new HouseType],
                        'business_type' => ['required', new BusinessType],
                        'branch' => ['required', new Branch],
                        'loan_officer' => 'required',
                        'estimated_monthly_income_for_business' => 'required|integer',
                        'self_employed' => 'required',
                        'spouse_business_type' => ['required_if:spouse_self_employed,1'],
                        'monthly_income_for_spouse_business' => 'required_if:spouse_self_employed,1',
                        'spouse_monthly_gross_income_at_work' => 'required_if:spouse_employed,1',
                        'company_name' => 'required_if:spouse_employed,1',
                        'position' => 'required_if:spouse_employed,1',
                    ]
                );

            
        } catch (Exception $e) {
            return redirect()->back()->with($e);
        }
        
        Client::create($request->all());
        
        return redirect()->back()->with('message', 'Client Successfully Created.');
        
    }

    
    
    public function printList($clients){
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

        $template = public_path('LAF Final Template.docx');

        $folder = uniqid();
        File::makeDirectory(Storage::disk('public')->path($folder));
        $ctr= 0;
        
        
        
        

        foreach ($clients as $client) {
            
            $templateProcessor = new TemplateProcessor($template);
            
            $name = ucwords($client->last_name.', '.$client->first_name.' '.$client->middle_name);
            $date = Carbon::parse($client->created_at)->format('d/m/Y');

            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('date', $date);
            $templateProcessor->setValue('nickname', ucwords($client->nickname));
            $present_address = ucwords($client->street_address.' '.'Brgy.'.$client->barangay.' '.$client->city.', '.$client->province);
            $templateProcessor->setValue('present_home_address', $present_address);
            $templateProcessor->setValue('years_of_stay', $client->years_of_stay);
            $business_address = ucwords($client->business_farm_street_address.' '.'Brgy.'.$client->business_barangay.' '.$client->business_farm_city.', '.$client->business_farm_province);
            $templateProcessor->setValue('business_address', $business_address);

            $owned= '';
            $rented= '';
            if($client->house == "OWNED"){
                $owned = 'X';
            }

            if($client->house == "RENTED"){
                $rented = 'X';
            }

            $templateProcessor->setValue('ho', $owned);
            $templateProcessor->setValue('hr', $rented);

            $templateProcessor->setValue('birthday', $client->birthday);
            $templateProcessor->setValue('age', Carbon::parse($client->birthday)->age);

            $male= '';
            $female= '';
            if($client->gender == "MALE"){
                $male = 'X';
            }

            if($client->gender == "FEMALE"){
                $female = 'X';
            }

            $templateProcessor->setValue('m', $male);
            $templateProcessor->setValue('f', $female);
             
            $templateProcessor->setValue('birthplace', $client->birthplace);
            $templateProcessor->setValue('tin', $client->tin_id);

            $single= '';
            $married= '';
            $widowed= '';
            $separated= '';
            if($client->civil_status == "SINGLE"){
                $single = 'X';
            }

            if($client->civil_status == "MARRIED"){
                $married = 'X';
            }

            if($client->civil_status == "WIDOW"){
                $widowed = 'X';
            }

            if($client->civil_status == "SEPARATED"){
                $separated = 'X';
            }

            $templateProcessor->setValue('cs', $single);
            $templateProcessor->setValue('cm', $married);
            $templateProcessor->setValue('cw', $widowed);
            $templateProcessor->setValue('cse', $separated);
            $templateProcessor->setValue('umid', $client->other_ids);


            $post_grad= '';
            $college = '';
            $highschool= '';
            $elementary= '';
            $others= '';
            if($client->education == "POST GRADUATE"){
                $post_grad = 'X';
            }

            if($client->education == "COLLEGE"){
                $college = 'X';
            }

            if($client->education == "HIGH SCHOOL"){
                $highschool = 'X';
            }

            if($client->education == "ELEMENTARY"){
                $elementary = 'X';
            }

            if($client->education == "OTHERS"){
                $others = 'X';
            }

            $templateProcessor->setValue('ep', $post_grad);
            $templateProcessor->setValue('ec', $college);
            $templateProcessor->setValue('eh', $highschool);
            $templateProcessor->setValue('ee', $elementary);
            $templateProcessor->setValue('eo', $others);

            $templateProcessor->setValue('fb_account', $client->facebook_account_link);
            $templateProcessor->setValue('contact', $client->mobile_number);


            // Spouse Information

            $spouse_name = $client->spouse_first_name.' '.$client->spouse_middle_name.' '.$client->spouse_last_name;
            $templateProcessor->setValue('s_name', $spouse_name);
            $templateProcessor->setValue('s_birthday', $client->spouse_birthday);
            $templateProcessor->setValue('s_age', Carbon::parse($client->spouse_birthday)->age);
            $templateProcessor->setValue('s_contact', $client->spouse_mobile_number);
            $templateProcessor->setValue('dependents', $client->number_of_dependents);
            $templateProcessor->setValue('household', $client->household_size);
            $templateProcessor->setValue('mother_name', $client->mothers_maiden_name);

            $templateProcessor->setValue('pr1_name', $client->person_1_name);
            $templateProcessor->setValue('pr1_contact', $client->person_1_contact_number);
            $templateProcessor->setValue('p1r_address', $client->person_1_whole_address);

            $templateProcessor->setValue('pr2_name', $client->person_2_name);
            $templateProcessor->setValue('pr2_contact', $client->person_2_contact_number);
            $templateProcessor->setValue('p2r_address', $client->person_2_whole_address);


            $other_income='';
            $spouse_other_income='';
            if ($client->self_employed == true) {
                $self_employed = 'X';
            }
            if (!empty($client->other_income)) {
                $other_income = 'X';
            }

            $templateProcessor->setValue('e', $self_employed);
            $templateProcessor->setValue('service_type', $client->business_type);
            $templateProcessor->setValue('b_mgi', $this->currency($client->estimated_monthly_income_for_business));
            $templateProcessor->setValue('o', $other_income);
            $templateProcessor->setValue('o_name', $client->other_income);
            $templateProcessor->setValue('o_mgi', $this->currency($client->other_income_monthly_estimated_earnings));

            $spouse_total_income=0;
            $spouse_self_employed='';
            $spouse_employed='';    

            if ($spouse_name) {
                if ($client->spouse_self_employed == true) {
                    $spouse_self_employed = 'X';
                }
                $templateProcessor->setValue('se', $spouse_self_employed);
                $templateProcessor->setValue('s_service_type', $client->spouse_business_type);

                 if ($client->monthly_income_for_spouse_business>0) {
                    $templateProcessor->setValue('se_mgi', $this->currency($client->monthly_income_for_spouse_business));
                }else{
                    $templateProcessor->setValue('se_mgi', '');
                }


                if ($client->spouse_employed == true) {
                    $spouse_employed = 'X';
                }

                $templateProcessor->setValue('sep', $spouse_employed);
                $templateProcessor->setValue('position', $client->position);
                $templateProcessor->setValue('company', $client->company);

                if ($client->spouse_monthly_gross_income_at_work>0) {
                    $templateProcessor->setValue('sep_mgi', $this->currency($client->spouse_monthly_gross_income_at_work));
                }else{
                    $templateProcessor->setValue('sep_mgi', '');
                }

                if (!empty($client->spouse_other_income)) {
                    $spouse_other_income = 'X';
                }

                $templateProcessor->setValue('so', $spouse_other_income);
                $templateProcessor->setValue('so_name', $client->spouse_other_income);
                if ($client->spouse_other_income_monthly_estimated_earnings>0) {
                    $templateProcessor->setValue('so_mgi', $this->currency($client->spouse_other_income_monthly_estimated_earnings));
                }else{
                    $templateProcessor->setValue('so_mgi', '');
                }

                $spouse_total_income = $client->monthly_income_for_spouse_business + $client->spouse_monthly_gross_income_at_work + $client->spouse_other_income_monthly_estimated_earnings;

            }else{
                $templateProcessor->setValue('se', $spouse_self_employed);
                $templateProcessor->setValue('s_service_type', '');
                $templateProcessor->setValue('se_mgi', '');
                $templateProcessor->setValue('sep_mgi', '');
                $templateProcessor->setValue('sep', $spouse_employed);
                $templateProcessor->setValue('position', '');
                $templateProcessor->setValue('company', '');
                $templateProcessor->setValue('so', $spouse_other_income);
                $templateProcessor->setValue('so_name', '');
                $templateProcessor->setValue('so_mgi', '');
            }

                $pension ='';
                $remittance ='';
                $total_others=$this->cn($client->pension)+$this->cn($client->remittance);

                if (!empty($client->pension)) {
                    $pension = 'X';
                }
                if (!empty($client->remittance)) {
                    $remittance = 'X';
                }

                $templateProcessor->setValue('rem', $remittance);
                $templateProcessor->setValue('pen', $pension);
                if ($total_others>0) {
                    $templateProcessor->setValue('total_others', $this->currency($total_others));
                }else{
                    $templateProcessor->setValue('total_others', '');
                }
                
                $other_income_monthly_estimated_earnings = $this->cn($client->other_income_monthly_estimated_earnings);
                $estimated_monthly_income_for_business = $this->cn($client->estimated_monthly_income_for_business);

                $total_household_income= $this->currency($estimated_monthly_income_for_business+$other_income_monthly_estimated_earnings+$spouse_total_income+$client->pension+
                    $client->remittance);
                
                $templateProcessor->setValue('total_hh', $total_household_income);
                $templateProcessor->setValue('branch', $client->branch);

            // Progress Poverty Index


            //QUESTION 1 
            // if ($client->ppi->ppi_q_1 == "Walo o Higit pa") {
            //         $q1 = 0;
            //     }
            //     if ($client->ppi->ppi_q_1 == "Pito") {
            //         $q1 = 2;
            //     }
            //     if ($client->ppi->ppi_q_1 == "Anim") {
            //         $q1 = 6;
            //     }
            //     if ($client->ppi->ppi_q_1 == "Lima") {
            //         $q1 = 11;
            //     }
            //      if ($client->ppi->ppi_q_1 == "Apat") {
            //         $q1 = 15;
            //     }
            //     if ($client->ppi->ppi_q_1 == "Tatlo") {
            //         $q1 = 21;
            //     }
            //     if ($client->ppi->ppi_q_1 == "Isa o Dalawa") {
            //         $q1 = 30;
            //     }    

            // Question 2

                // if ($client->ppi->ppi_q_2 == "Hindi") {
                //     $q2 = 0;
                // }

                // if ($client->ppi->ppi_q_2 == "Oo") {
                //     $q2 = 1;
                // }

                // if ($client->ppi->ppi_q_2 == "Walang may edad 6-17") {
                //     $q2 = 2;
                // }    

            // Question 3

                // if ($client->ppi->ppi_q_3 == "Wala") {
                //     $q3 = 0;
                // }
                // if ($client->ppi->ppi_q_3 == "Isa") {
                //     $q3 = 2;
                // }

                // if ($client->ppi->ppi_q_3 == "Dalawa") {
                //     $q3 = 7;
                // }

                // if ($client->ppi->ppi_q_3 == "Tatlo o higit pa") {
                //     $q3 = 12;
                // }   

            // Question 4

                // if ($client->ppi->ppi_q_4 == "Tatlo o higit pa") {
                //     $q4 = 0;
                // }

                // if ($client->ppi->ppi_q_4 == "Dalawa") {
                //     $q4 = 4;
                // }

                // if ($client->ppi->ppi_q_4 == "Isa") {
                //     $q4 = 8;
                // }

                // if ($client->ppi->ppi_q_4 == "Wala") {
                //     $q4 = 12;
                // } 

            // Question 5

                // if ($client->ppi->ppi_q_5 == "Elementary o No Grade Completed") {
                //     $q5 = 0;
                // }

                // if ($client->ppi->ppi_q_5 == "Walang babaeng puno ng pamilya") {
                //     $q5 = 2;
                // }

                // if ($client->ppi->ppi_q_5 == "Elementary or HS undergrad") {
                //     $q5 = 2;
                // }
                
                // if ($client->ppi->ppi_q_5 == "High School Graduate") {
                //     $q5 = 4;
                // }

                // if ($client->ppi->ppi_q_5 == "College undergrad o higit pa") {
                //     $q5 = 7;
                // }

            // Question 6

                // if ($client->ppi->ppi_q_6 == "Light Materials (LM) (cogon/nipa/anahaw) or mixed but more LM") {
                //     $q6 = 0;
                // }
                // if ($client->ppi->ppi_q_6 == "Mixed but predominantly strong materials") {
                //     $q6 = 2;
                // }

                // if ($client->ppi->ppi_q_6 == "Strong materials (galvanized iron, aluminum, tile, concrete, brick, stone, wood, plywood, asbestos)") {
                //     $q6 = 3;
                // }

            // Question 7

                // if ($client->ppi_ppi_q_7 == "Hindi (Walang pagmamay-ari)") {
                //     $q7 = 0;
                // }

                // if ($client->ppi_ppi_q_7 == "Oo (Mayroong pagmamay-ari)") {
                //     $q7 = 3;
                // }

             // Question 8
                // if ($client->ppi->ppi_q_8 == "Wala (Walang pagmamay-ari)") {
                //     $q8 = 0;
                // }

                // if ($client->ppi->ppi_q_8 == "1 sa nabanggit, pero hindi pareho") {
                //     $q8 = 6;
                // }

                // if ($client->ppi->ppi_q_8 == "Parehong may pagmamay-ari") {
                //     $q8 = 12;
                // }

            // Question 9

                // if ($client->ppi->ppi_q_9 == "Hindi/ Wala") {
                //     $q9 = 0;
                // }

                // if ($client->ppi->ppi_q_9 == "TV lamang") {
                //     $q9 = 4;
                // }
                // if ($client->ppi->ppi_q_9 == "TV/ VCD/DVD player") {
                //     $q9 = 7;
                // }   

            // Question 10

                // if ($client->ppi->ppi_q_10 == "Wala") {
                //     $q10 = 0;
                // }

                // if ($client->ppi->ppi_q_10 == "Isa") {
                //     $q10 = 4;
                // }

                // if ($client->ppi->ppi_q_10 == "Dalawa") {
                //     $q10 = 7;
                // }
                // if ($client->ppi->ppi_q_10 == "Tatlo o Higit pa") {
                //     $q10 = 12;
                // }         

                // $qts = $q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10;     
                // $templateProcessor->setValue('q1', $q1);
                // $templateProcessor->setValue('q2', $q2);
                // $templateProcessor->setValue('q3', $q3);
                // $templateProcessor->setValue('q4', $q4);
                // $templateProcessor->setValue('q5', $q5);
                // $templateProcessor->setValue('q6', $q6);
                // $templateProcessor->setValue('q7', $q7);
                // $templateProcessor->setValue('q8', $q8);
                // $templateProcessor->setValue('q9', $q9);
                // $templateProcessor->setValue('q10', $q10);
                // $templateProcessor->setValue('qts', $qts);   


                // Credit Worthiness Evaluation

                // $new_loan='';
                // $reloan='';

                // if ($client->cwe->loan_type == 'New Loan') {
                //     $new_loan = 'X';
                // }
                // if ($client->cwe->loan_type == 'Reloan') {
                //     $reloan = 'X';
                // }

                // $templateProcessor->setValue('nl', $new_loan);
                // $templateProcessor->setValue('rl', $reloan);

                // $templateProcessor->setValue('branch', $client->cwe->branch);
                // $templateProcessor->setValue('cluster', $client->cwe->cluster);
                // $templateProcessor->setValue('lo', $client->loan_officer);
                /*$templateProcessor->setValue('loan_purpose', $client->cwe->loan_purpose);


                $mpl = '[   ]MPL';
                $gml = '[   ]GML';
                $llp = '[   ]LLP';
                $agl = '[   ]AGL';

                if ($client->cwe->type_of_loan == 'MPL') {
                    $mpl = '[ X ]MPL';
                }
                if ($client->cwe->type_of_loan == 'GML') {
                    $gml = '[ X ]GML';
                }

                if ($client->cwe->type_of_loan == 'LLP') {
                    $llp = '[ X ]LLP';
                }

                if ($client->cwe->type_of_loan == 'AGL') {
                    $agl = '[ X ]AGL';
                }

                $templateProcessor->setValue('mpl', $mpl); 
                $templateProcessor->setValue('gml', $gml); 
                $templateProcessor->setValue('llp', $llp); 
                $templateProcessor->setValue('agl', $agl); 

                $templateProcessor->setValue('lc', $client->cwe->loan_cycle); 
                $templateProcessor->setValue('dom', $client->cwe->date_of_membership);
                $templateProcessor->setValue('pref_loan', $this->currency($client->cwe->prefered_loan_amount));
                $templateProcessor->setValue('terms_in_months', $client->cwe->terms_in_months);

                $total_income = $client->cwe->business_1_business_income/4
                                 + $client->business_3_business_income/4 + $client->business_2_business_income/4;

                $templateProcessor->setValue('bi_1', $this->currency($client->cwe->business_1_business_income, true));
                $templateProcessor->setValue('bi_2', $this->currency($client->cwe->business_2_business_income, true));
                $templateProcessor->setValue('bi_3', $this->currency($client->cwe->business_3_business_income, true));
                $templateProcessor->setValue('bus_ti', $this->currency($total_income));
               
                if ($client->cwe->business_1_business_income>0) {
                    $templateProcessor->setValue('b1_labor', $this->currency($client->cwe->business_1_labor_expense, true));
                    $templateProcessor->setValue('b1_rent', $this->currency($client->cwe->business_1_rent_expense, true));
                    $templateProcessor->setValue('b1_uti', $this->currency($client->cwe->business_1_utility_expense, true));
                    $templateProcessor->setValue('b1_transpo', $this->currency($client->cwe->business_1_transportation_expense, true));
                    $templateProcessor->setValue('b1_others', $this->currency($client->cwe->business_1_other_expense, true));
                }else{
                    $templateProcessor->setValue('b1_labor', '');
                    $templateProcessor->setValue('b1_rent', '');
                    $templateProcessor->setValue('b1_uti', '');
                    $templateProcessor->setValue('b1_transpo', '');
                    $templateProcessor->setValue('b1_others', '');
                }

                if ($client->cwe->business_2_business_income>0) {
                    $templateProcessor->setValue('b2_labor', $this->currency($client->cwe->business_2_labor_expense, true));
                    $templateProcessor->setValue('b2_rent', $this->currency($client->cwe->business_2_rent_expense, true));
                    $templateProcessor->setValue('b2_uti', $this->currency($client->cwe->business_2_utility_expense, true));
                    $templateProcessor->setValue('b2_transpo', $this->currency($client->cwe->business_2_transportation_expense, true));
                    $templateProcessor->setValue('b2_others', $this->currency($client->cwe->business_2_other_expense, true));
                }else{
                    $templateProcessor->setValue('b2_labor', '');
                    $templateProcessor->setValue('b2_rent', '');
                    $templateProcessor->setValue('b2_uti', '');
                    $templateProcessor->setValue('b2_transpo', '');
                    $templateProcessor->setValue('b2_others', '');
                }

                if ($client->cwe->business_3_business_income>0) {
                    $templateProcessor->setValue('b3_labor', $this->currency($client->cwe->business_3_labor_expense, true));
                    $templateProcessor->setValue('b3_rent', $this->currency($client->cwe->business_3_rent_expense, true));
                    $templateProcessor->setValue('b3_uti', $this->currency($client->cwe->business_3_utility_expense, true));
                    $templateProcessor->setValue('b3_transpo', $this->currency($client->cwe->business_3_transportation_expense, true));
                    $templateProcessor->setValue('b3_others', $this->currency($client->cwe->business_3_other_expense, true));
                }else{
                    $templateProcessor->setValue('b3_labor', '');
                    $templateProcessor->setValue('b3_rent', '');
                    $templateProcessor->setValue('b3_uti', '');
                    $templateProcessor->setValue('b3_transpo', '');
                    $templateProcessor->setValue('b3_others', '');
                }
                
                $total_labor = $this->cn($client->cwe->business_1_labor_expense)+
                               $this->cn($client->cwe->business_2_labor_expense)+
                               $this->cn($client->cwe->business_3_labor_expense);

                $total_rent = $this->cn($client->cwe->business_1_rent_expense)+
                              $this->cn($client->cwe->business_2_rent_expense)+
                              $this->cn($client->cwe->business_3_rent_expense);

                $total_utilities = $this->cn($client->cwe->business_1_utility_expense)+ 
                                   $this->cn($client->cwe->business_2_utility_expense)+
                                   $this->cn($client->cwe->business_3_utility_expense);

                $total_transpo = $this->cn($client->cwe->business_1_transportation_expense)+
                                 $this->cn($client->cwe->business_2_transportation_expense)+
                                 $this->cn($client->cwe->business_3_transportation_expense);

                $total_others = $this->cn($client->cwe->business_1_other_expense)+
                                $this->cn($client->cwe->business_2_other_expense)+
                                $this->cn($client->cwe->business_3_other_expense);

                $templateProcessor->setValue('t_labor', $this->currency($total_labor,true));
                $templateProcessor->setValue('t_rent', $this->currency($total_rent,true));
                $templateProcessor->setValue('t_uti', $this->currency($total_utilities,true));
                $templateProcessor->setValue('t_transpo', $this->currency($total_transpo,true));
                $templateProcessor->setValue('t_others', $this->currency($total_others,true));

                $weekly_omfi1 = '';
                if ($client->cwe->mfi_1_loan_amount > 0 && $client->cwe->mfi_1_number_of_weeks_installment > 0) {
                    $weekly_omfi1 = $this->cn($client->cwe->mfi_1_loan_amount) / $this->cn($client->cwe->mfi_1_number_of_weeks_installment); 
                }
                

                $templateProcessor->setValue('omfi_1', $client->cwe->mfi_1);
                $templateProcessor->setValue('a1_omfi', $this->currency($client->cwe->mfi_1_loan_amount));
                $templateProcessor->setValue('w1_omfi', $this->currency($weekly_omfi1));


                $weekly_omfi2 = '';
                if ($client->cwe->mfi_2_loan_amount > 0 && $client->cwe->mfi_2_number_of_weeks_installment > 0) {
                   $weekly_omfi2 = $this->cn($client->cwe->mfi_2_loan_amount) / $this->cn($client->cwe->mfi_2_number_of_weeks_installment); 
                }
                

                $templateProcessor->setValue('omfi_2', $client->cwe->mfi_1);
                $templateProcessor->setValue('a2_omfi', $this->currency($client->cwe->mfi_2_loan_amount));
                $templateProcessor->setValue('w2_omfi', $this->currency($weekly_omfi2));

                $weekly_ape = '';
                $number_of_weeks = $this->cn($client->cwe->appliances_equipment_monthly_installment) * 4;

                if (!empty($client->cwe->appliances_equipment_loan_amount) && !empty($client->cwe->appliances_equipment_monthly_installment)) {
                    $weekly_ape = $client->cwe->appliances_equipment_loan_amount / $number_of_weeks;
                }

                $templateProcessor->setValue('ape', $client->cwe->appliances_equipment);
                $templateProcessor->setValue('a_ape', $this->currency($client->cwe->appliances_equipment_loan_amount));
                $templateProcessor->setValue('m_ape', $this->currency($weekly_ape));

                $total_hh_income = $this->cn($client->cwe->salaries_wages_net)/4+
                                   $this->cn($client->cwe->remittance)/4+
                                   $this->cn($client->cwe->salary)/4;

                

                $templateProcessor->setValue('salary', $this->currency($client->cwe->salaries_wages_net, true));
                $templateProcessor->setValue('remittance', $this->currency($client->cwe->remittance, true));
                $templateProcessor->setValue('hi_oi', $this->currency($client->cwe->hh_other_income, true));
                $templateProcessor->setValue('hi_ti', $this->currency($total_hh_income));

                $total_household_expense = 
                $client->cwe->food/4 + 
                $client->cwe->hh_education/4 + 
                $client->cwe->transportation/4 + 
                $client->cwe->house_rent/4 + 
                $client->cwe->clothing/4 + 
                $client->cwe->water_bill/4 +
                $client->cwe->electricity_bill/4 +
                $client->cwe->others/4;

                $templateProcessor->setValue('food', $this->currency($client->cwe->food,true));
                $templateProcessor->setValue('educ', $this->currency($client->cwe->hh_education,true));
                $templateProcessor->setValue('transpo', $this->currency($client->cwe->transportation,true));
                $templateProcessor->setValue('rent', $this->currency($client->cwe->house_rent,true));
                $templateProcessor->setValue('clothing', $this->currency($client->cwe->clothing,true));
                $templateProcessor->setValue('water', $this->currency($client->cwe->water_bill,true));
                $templateProcessor->setValue('elec', $this->currency($client->cwe->electricity_bill,true));
                $templateProcessor->setValue('he_others', $this->currency($client->cwe->others,true));
                $templateProcessor->setValue('he_te', $this->currency($total_household_expense));


                $e1 = $total_labor/4 + $total_rent/4 + $total_transpo/4 + $total_utilities/4 + $total_others/4;

                $e2 = $this->cn($weekly_ape) + $this->cn($weekly_omfi1) + $this->cn($weekly_omfi2);
                $bndi = $total_income - ($e1+$e2);

                $twndi = $bndi + ($total_hh_income - $total_household_expense);
                $pccp = $twndi * .7;
                $ltw = $client->cwe->terms_in_months*4;
                $credit_limit = $pccp * $ltw;
                $cla = $credit_limit * .82;

                $templateProcessor->setValue('ltw', $ltw); 
                $templateProcessor->setValue('bndi', '₱ '.number_format($bndi,2,".",",")); 
                $templateProcessor->setValue('twndi', '₱ '.number_format($twndi,2,".",","));   
                $templateProcessor->setValue('pccp', '₱ '.number_format($pccp,2,".",","));
                $templateProcessor->setValue('cl', '₱ '.number_format($credit_limit,2,".",","));
                $templateProcessor->setValue('cla', '₱ '.number_format($cla,2,".",","));

                $n_p ='';
                $p_p ='';

                if ($pccp > 0) {
                    $p_p ='X';
                }else{
                    $n_p ='X';
                }
                $templateProcessor->setValue('p_p', $p_p);
                $templateProcessor->setValue('n_p', $n_p);*/

                if ($client->received == false) {
                    $client->update(['received' => true]);    
                }
            
            $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.'('.$ctr.') '.$name.'.docx');
            
            $templateProcessor->saveAs($newFile);
            $ctr++;
        }

            $zip = new ZipArchive();
                
            $zipFileName = storage_path('app/public/'.$folder).'.zip';

            $files = Storage::disk('public')->files($folder);
        
            // dd($folder);
            $path = storage_path('app/public/'.$folder);
            // $path = storage_path('app/public/5f05496e13ace');

            $files = File::allfiles($path);

          
            // if (empty($files)) {
            //     return redirect()->back()->with('message', 'Clients Not Found');
            // }

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
        
        $current_date = Carbon::now();
        $branch = auth()->user()->office->first()->name;
        $clients = Client::all();
        if(auth()->user()->is_admin){
            if($request->has('from_date')){

                try {
                   $date_valid = $request->validate([
                    'from_date' => 'before_or_equal:today'
                    ]);   
                } catch (Exception $e) {
                    return redirect()->back()->with($e);
                }


                $current_date = Carbon::now();


                if($request->branch != null){
                    $clients = Client::where('branch', $request->branch)->whereBetween('created_at', [$request->from_date, $current_date])->get();
                    }else{
                    $clients = Client::whereBetween('created_at', [$request->from_date, $current_date])->get();
                }
            }else{
                if($request->has('branch')){
                    $clients = Client::where('branch',$request->branch)->get();
                }
            }
        }else{
            if($request->has('from_date')){
                try {
                   $date_valid = $request->validate([
                    'from_date' => 'before_or_equal:today'
                    ]);   
                } catch (Exception $e) {
                    return redirect()->back()->with($e);
                }
                $clients = Client::where('branch', $branch)->whereBetween('created_at', [$request->from_date, $current_date])->get();
            }else{
                $clients = Client::where('branch',$branch)->get();    
            }
               
        }

        if($clients->count() == 0){
            return redirect()->back()->with('message', 'No available clients for export.');
        }

        $file = $this->printList($clients);
        
        session()->flash('download',$file);

        return response()->download($file)->deleteFileAfterSend(true);
    }

    

    public function currency($value, $weekly=false){
        if ($weekly == true && $value > 0) {
            return '₱ '.number_format($value/4,2,".",",");
        }
        if ($value>0) {
            return '₱ '.number_format($value,2,".",",");
        }
            $value = '';
            return $value;
    }

    public function cn($value){
        if ($value == "") {
            $value = 0;
            return $value;
        }
        return $value;

    }


    public function exportClient($id){


        $user = auth()->user()->office->first();
        $client = Client::find($id)->load('ppi','cwe');
        if(!auth()->user()->is_admin){
            if($client->branch != auth()->user()->office->first()->name){
                abort(403);
            }
        }
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

        
        $template = public_path('LAF Final Template.docx');

        $folder = uniqid();
        File::makeDirectory(Storage::disk('public')->path($folder));


        
        
        $templateProcessor = new TemplateProcessor($template);

        $name = ucwords($client->last_name.' ,'.$client->first_name.' '.$client->middle_name);
        $date = Carbon::parse($client->created_at)->format('d/m/Y');
        

        $templateProcessor->setValue('name', $name);
        $templateProcessor->setValue('date', $date);
        $templateProcessor->setValue('nickname', ucwords($client->nickname));
        $present_address = ucwords($client->street_address.' '.'Brgy.'.$client->barangay.' '.$client->city.', '.$client->province);
        $templateProcessor->setValue('present_home_address', $present_address);
        $templateProcessor->setValue('years_of_stay', $client->years_of_stay);
        $business_address = ucwords($client->business_farm_street_address.' '.'Brgy.'.$client->business_barangay.' '.$client->business_farm_city.', '.$client->business_farm_province);
        $templateProcessor->setValue('business_address', $business_address);

        $owned= '';
        $rented= '';
        if($client->house == "OWNED"){
            $owned = 'X';
        }

        if($client->house == "RENTED"){
            $rented = 'X';
        }
    
        $client->spouse_age = Carbon::parse($client->spouse_birthday)->age;
        $templateProcessor->setValue('ho', $owned);
        $templateProcessor->setValue('hr', $rented);

        $templateProcessor->setValue('birthday', $client->birthday);
        $templateProcessor->setValue('age', Carbon::parse($client->birthday)->age);

        $male= '';
        $female= '';
        if($client->gender == "MALE"){
            $male = 'X';
        }

        if($client->gender == "FEMALE"){
            $female = 'X';
        }

        $templateProcessor->setValue('m', $male);
        $templateProcessor->setValue('f', $female);
         
        $templateProcessor->setValue('birthplace', $client->birthplace);
        $templateProcessor->setValue('tin', $client->tin_id);

        $single= '';
        $married= '';
        $widowed= '';
        $separated= '';
        if($client->civil_status == "SINGLE"){
            $single = 'X';
        }

        if($client->civil_status == "MARRIED"){
            $married = 'X';
        }

        if($client->civil_status == "WIDOW"){
            $widowed = 'X';
        }

        if($client->civil_status == "SEPARATED"){
            $separated = 'X';
        }

        $templateProcessor->setValue('cs', $single);
        $templateProcessor->setValue('cm', $married);
        $templateProcessor->setValue('cw', $widowed);
        $templateProcessor->setValue('cse', $separated);
        $templateProcessor->setValue('umid', $client->other_ids);


        $post_grad= '';
        $college = '';
        $highschool= '';
        $elementary= '';
        $others= '';
        if($client->education == "POST GRADUATE"){
            $post_grad = 'X';
        }

        if($client->education == "COLLEGE"){
            $college = 'X';
        }

        if($client->education == "HIGH SCHOOL"){
            $highschool = 'X';
        }

        if($client->education == "ELEMENTARY"){
            $elementary = 'X';
        }

        if($client->education == "OTHERS"){
            $others = 'X';
        }

        $templateProcessor->setValue('ep', $post_grad);
        $templateProcessor->setValue('ec', $college);
        $templateProcessor->setValue('eh', $highschool);
        $templateProcessor->setValue('ee', $elementary);
        $templateProcessor->setValue('eo', $others);

        $templateProcessor->setValue('fb_account', $client->facebook_account_link);
        $templateProcessor->setValue('contact', $client->mobile_number);


        // Spouse Information

        $spouse_name = $client->spouse_last_name.' '.$client->spouse_first_name.' '.$client->spouse_middle_name;
        $templateProcessor->setValue('s_name', $spouse_name);
        $templateProcessor->setValue('s_birthday', $client->spouse_birthday);
        $templateProcessor->setValue('s_age', Carbon::parse($client->spouse_birthday)->age);
        $templateProcessor->setValue('s_contact', $client->spouse_mobile_number);
        $templateProcessor->setValue('dependents', $client->number_of_dependents);
        $templateProcessor->setValue('household', $client->household_size);
        $templateProcessor->setValue('mother_name', $client->mothers_maiden_name);

        $templateProcessor->setValue('pr1_name', $client->person_1_name);
        $templateProcessor->setValue('pr1_contact', $client->person_1_contact_number);
        $templateProcessor->setValue('p1r_address', $client->person_1_whole_address);

        $templateProcessor->setValue('pr2_name', $client->person_2_name);
        $templateProcessor->setValue('pr2_contact', $client->person_2_contact_number);
        $templateProcessor->setValue('p2r_address', $client->person_2_whole_address);


        $other_income='';
        $spouse_other_income='';
        if ($client->self_employed == true) {
            $self_employed = 'X';
        }
        if (!empty($client->other_income)) {
            $other_income = 'X';
        }

        $templateProcessor->setValue('e', $self_employed);
        $templateProcessor->setValue('service_type', $client->business_type);
        $templateProcessor->setValue('b_mgi', $this->currency($client->estimated_monthly_income_for_business));
        $templateProcessor->setValue('o', $other_income);
        $templateProcessor->setValue('o_name', $client->other_income);
        $templateProcessor->setValue('o_mgi', $this->currency($client->other_income_monthly_estimated_earnings));

        $spouse_total_income=0;
        $spouse_self_employed='';
        $spouse_employed='';    

        if ($spouse_name) {
            if ($client->spouse_self_employed == true) {
                $spouse_self_employed = 'X';
            }
            $templateProcessor->setValue('se', $spouse_self_employed);
            $templateProcessor->setValue('s_service_type', $client->spouse_business_type);

             if ($client->monthly_income_for_spouse_business>0) {
                $templateProcessor->setValue('se_mgi', $this->currency($client->monthly_income_for_spouse_business));
            }else{
                $templateProcessor->setValue('se_mgi', '');
            }


            if ($client->spouse_employed == true) {
                $spouse_employed = 'X';
            }

            $templateProcessor->setValue('sep', $spouse_employed);
            $templateProcessor->setValue('position', $client->position);
            $templateProcessor->setValue('company', $client->company_name);

            if ($client->spouse_monthly_gross_income_at_work>0) {
                $templateProcessor->setValue('sep_mgi', $this->currency($client->spouse_monthly_gross_income_at_work));
            }else{
                $templateProcessor->setValue('sep_mgi', '');
            }

            if (!empty($client->spouse_other_income)) {
                $spouse_other_income = 'X';
            }

            $templateProcessor->setValue('so', $spouse_other_income);
            $templateProcessor->setValue('so_name', $client->spouse_other_income);
            if ($client->spouse_other_income_monthly_estimated_earnings>0) {
                $templateProcessor->setValue('so_mgi', $this->currency($client->spouse_other_income_monthly_estimated_earnings));
            }else{
                $templateProcessor->setValue('so_mgi', '');
            }

            $spouse_total_income = $client->monthly_income_for_spouse_business + $client->spouse_monthly_gross_income_at_work + $client->spouse_other_income_monthly_estimated_earnings;

        }else{
            $templateProcessor->setValue('se', $spouse_self_employed);
            $templateProcessor->setValue('s_service_type', '');
            $templateProcessor->setValue('se_mgi', '');
            $templateProcessor->setValue('sep_mgi', '');
            $templateProcessor->setValue('sep', $spouse_employed);
            $templateProcessor->setValue('position', '');
            $templateProcessor->setValue('company', '');
            $templateProcessor->setValue('so', $spouse_other_income);
            $templateProcessor->setValue('so_name', '');
            $templateProcessor->setValue('so_mgi', '');
        }

            $pension ='';
            $remittance ='';
            $total_others=$this->cn($client->pension)+$this->cn($client->remittance);

            if (!empty($client->pension)) {
                $pension = 'X';
            }
            if (!empty($client->remittance)) {
                $remittance = 'X';
            }

            $templateProcessor->setValue('rem', $remittance);
            $templateProcessor->setValue('pen', $pension);
            if ($total_others>0) {
                $templateProcessor->setValue('total_others', $this->currency($total_others));
            }else{
                $templateProcessor->setValue('total_others', '');
            }
            
            $other_income_monthly_estimated_earnings = $this->cn($client->other_income_monthly_estimated_earnings);
            $estimated_monthly_income_for_business = $this->cn($client->estimated_monthly_income_for_business);

            $total_household_income= $this->currency($estimated_monthly_income_for_business+$other_income_monthly_estimated_earnings+$spouse_total_income+$client->pension+
                $client->remittance);
            // dd($spouse_total_income);
            $templateProcessor->setValue('total_hh', $total_household_income);
            $templateProcessor->setValue('branch', $client->branch);
        // Progress Poverty Index


        //QUESTION 1 
        // if ($client->ppi->ppi_q_1 == "Walo o Higit pa") {
        //         $q1 = 0;
        //     }
        //     if ($client->ppi->ppi_q_1 == "Pito") {
        //         $q1 = 2;
        //     }
        //     if ($client->ppi->ppi_q_1 == "Anim") {
        //         $q1 = 6;
        //     }
        //     if ($client->ppi->ppi_q_1 == "Lima") {
        //         $q1 = 11;
        //     }
        //      if ($client->ppi->ppi_q_1 == "Apat") {
        //         $q1 = 15;
        //     }
        //     if ($client->ppi->ppi_q_1 == "Tatlo") {
        //         $q1 = 21;
        //     }
        //     if ($client->ppi->ppi_q_1 == "Isa o Dalawa") {
        //         $q1 = 30;
        //     }    

        // Question 2

            // if ($client->ppi->ppi_q_2 == "Hindi") {
            //     $q2 = 0;
            // }

            // if ($client->ppi->ppi_q_2 == "Oo") {
            //     $q2 = 1;
            // }

            // if ($client->ppi->ppi_q_2 == "Walang may edad 6-17") {
            //     $q2 = 2;
            // }    

        // Question 3

            // if ($client->ppi->ppi_q_3 == "Wala") {
            //     $q3 = 0;
            // }
            // if ($client->ppi->ppi_q_3 == "Isa") {
            //     $q3 = 2;
            // }

            // if ($client->ppi->ppi_q_3 == "Dalawa") {
            //     $q3 = 7;
            // }

            // if ($client->ppi->ppi_q_3 == "Tatlo o higit pa") {
            //     $q3 = 12;
            // }   

        // Question 4

            // if ($client->ppi->ppi_q_4 == "Tatlo o higit pa") {
            //     $q4 = 0;
            // }

            // if ($client->ppi->ppi_q_4 == "Dalawa") {
            //     $q4 = 4;
            // }

            // if ($client->ppi->ppi_q_4 == "Isa") {
            //     $q4 = 8;
            // }

            // if ($client->ppi->ppi_q_4 == "Wala") {
            //     $q4 = 12;
            // } 

        // Question 5

            // if ($client->ppi->ppi_q_5 == "Elementary o No Grade Completed") {
            //     $q5 = 0;
            // }

            // if ($client->ppi->ppi_q_5 == "Walang babaeng puno ng pamilya") {
            //     $q5 = 2;
            // }

            // if ($client->ppi->ppi_q_5 == "Elementary or HS undergrad") {
            //     $q5 = 2;
            // }
            
            // if ($client->ppi->ppi_q_5 == "High School Graduate") {
            //     $q5 = 4;
            // }

            // if ($client->ppi->ppi_q_5 == "College undergrad o higit pa") {
            //     $q5 = 7;
            // }

        // Question 6

            // if ($client->ppi->ppi_q_6 == "Light Materials (LM) (cogon/nipa/anahaw) or mixed but more LM") {
            //     $q6 = 0;
            // }
            // if ($client->ppi->ppi_q_6 == "Mixed but predominantly strong materials") {
            //     $q6 = 2;
            // }

            // if ($client->ppi->ppi_q_6 == "Strong materials (galvanized iron, aluminum, tile, concrete, brick, stone, wood, plywood, asbestos)") {
            //     $q6 = 3;
            // }

        // Question 7

            // if ($client->ppi_ppi_q_7 == "Hindi (Walang pagmamay-ari)") {
            //     $q7 = 0;
            // }

            // if ($client->ppi_ppi_q_7 == "Oo (Mayroong pagmamay-ari)") {
            //     $q7 = 3;
            // }

         // Question 8
            // if ($client->ppi->ppi_q_8 == "Wala (Walang pagmamay-ari)") {
            //     $q8 = 0;
            // }

            // if ($client->ppi->ppi_q_8 == "1 sa nabanggit, pero hindi pareho") {
            //     $q8 = 6;
            // }

            // if ($client->ppi->ppi_q_8 == "Parehong may pagmamay-ari") {
            //     $q8 = 12;
            // }

        // Question 9

            // if ($client->ppi->ppi_q_9 == "Hindi/ Wala") {
            //     $q9 = 0;
            // }

            // if ($client->ppi->ppi_q_9 == "TV lamang") {
            //     $q9 = 4;
            // }
            // if ($client->ppi->ppi_q_9 == "TV/ VCD/DVD player") {
            //     $q9 = 7;
            // }   

        // Question 10

            // if ($client->ppi->ppi_q_10 == "Wala") {
            //     $q10 = 0;
            // }

            // if ($client->ppi->ppi_q_10 == "Isa") {
            //     $q10 = 4;
            // }

            // if ($client->ppi->ppi_q_10 == "Dalawa") {
            //     $q10 = 7;
            // }
            // if ($client->ppi->ppi_q_10 == "Tatlo o Higit pa") {
            //     $q10 = 12;
            // }         

            // $qts = $q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10;     
            // $templateProcessor->setValue('q1', $q1);
            // $templateProcessor->setValue('q2', $q2);
            // $templateProcessor->setValue('q3', $q3);
            // $templateProcessor->setValue('q4', $q4);
            // $templateProcessor->setValue('q5', $q5);
            // $templateProcessor->setValue('q6', $q6);
            // $templateProcessor->setValue('q7', $q7);
            // $templateProcessor->setValue('q8', $q8);
            // $templateProcessor->setValue('q9', $q9);
            // $templateProcessor->setValue('q10', $q10);
            // $templateProcessor->setValue('qts', $qts);   


            // Credit Worthiness Evaluation

            // $new_loan='';
            // $reloan='';

            // if ($client->cwe->loan_type == 'New Loan') {
            //     $new_loan = 'X';
            // }
            // if ($client->cwe->loan_type == 'Reloan') {
            //     $reloan = 'X';
            // }

            // $templateProcessor->setValue('nl', $new_loan);
            // $templateProcessor->setValue('rl', $reloan);

            // $templateProcessor->setValue('branch', $client->cwe->branch);
            // $templateProcessor->setValue('cluster', $client->cwe->cluster);
            // $templateProcessor->setValue('lo', $client->loan_officer." | ");

            /*$templateProcessor->setValue('loan_purpose', $client->cwe->loan_purpose);
            $mpl = '[   ]MPL';
            $gml = '[   ]GML';
            $llp = '[   ]LLP';
            $agl = '[   ]AGL';

            if ($client->cwe->type_of_loan == 'MPL') {
                $mpl = '[ X ]MPL';
            }
            if ($client->cwe->type_of_loan == 'GML') {
                $gml = '[ X ]GML';
            }

            if ($client->cwe->type_of_loan == 'LLP') {
                $llp = '[ X ]LLP';
            }

            if ($client->cwe->type_of_loan == 'AGL') {
                $agl = '[ X ]AGL';
            }

            $templateProcessor->setValue('mpl', $mpl); 
            $templateProcessor->setValue('gml', $gml); 
            $templateProcessor->setValue('llp', $llp); 
            $templateProcessor->setValue('agl', $agl); 

            $templateProcessor->setValue('lc', $client->cwe->loan_cycle); 
            $templateProcessor->setValue('dom', $client->cwe->date_of_membership);
            $templateProcessor->setValue('pref_loan', $this->currency($client->cwe->prefered_loan_amount));
            $templateProcessor->setValue('terms_in_months', $client->cwe->terms_in_months);

            $total_income = $client->cwe->business_1_business_income/4
                             + $client->business_3_business_income/4 + $client->business_2_business_income/4;

            $templateProcessor->setValue('bi_1', $this->currency($client->cwe->business_1_business_income, true));
            $templateProcessor->setValue('bi_2', $this->currency($client->cwe->business_2_business_income, true));
            $templateProcessor->setValue('bi_3', $this->currency($client->cwe->business_3_business_income, true));
            $templateProcessor->setValue('bus_ti', $this->currency($total_income));
           
            if ($client->cwe->business_1_business_income>0) {
                $templateProcessor->setValue('b1_labor', $this->currency($client->cwe->business_1_labor_expense, true));
                $templateProcessor->setValue('b1_rent', $this->currency($client->cwe->business_1_rent_expense, true));
                $templateProcessor->setValue('b1_uti', $this->currency($client->cwe->business_1_utility_expense, true));
                $templateProcessor->setValue('b1_transpo', $this->currency($client->cwe->business_1_transportation_expense, true));
                $templateProcessor->setValue('b1_others', $this->currency($client->cwe->business_1_other_expense, true));
            }else{
                $templateProcessor->setValue('b1_labor', '');
                $templateProcessor->setValue('b1_rent', '');
                $templateProcessor->setValue('b1_uti', '');
                $templateProcessor->setValue('b1_transpo', '');
                $templateProcessor->setValue('b1_others', '');
            }

            if ($client->cwe->business_2_business_income>0) {
                $templateProcessor->setValue('b2_labor', $this->currency($client->cwe->business_2_labor_expense, true));
                $templateProcessor->setValue('b2_rent', $this->currency($client->cwe->business_2_rent_expense, true));
                $templateProcessor->setValue('b2_uti', $this->currency($client->cwe->business_2_utility_expense, true));
                $templateProcessor->setValue('b2_transpo', $this->currency($client->cwe->business_2_transportation_expense, true));
                $templateProcessor->setValue('b2_others', $this->currency($client->cwe->business_2_other_expense, true));
            }else{
                $templateProcessor->setValue('b2_labor', '');
                $templateProcessor->setValue('b2_rent', '');
                $templateProcessor->setValue('b2_uti', '');
                $templateProcessor->setValue('b2_transpo', '');
                $templateProcessor->setValue('b2_others', '');
            }

            if ($client->cwe->business_3_business_income>0) {
                $templateProcessor->setValue('b3_labor', $this->currency($client->cwe->business_3_labor_expense, true));
                $templateProcessor->setValue('b3_rent', $this->currency($client->cwe->business_3_rent_expense, true));
                $templateProcessor->setValue('b3_uti', $this->currency($client->cwe->business_3_utility_expense, true));
                $templateProcessor->setValue('b3_transpo', $this->currency($client->cwe->business_3_transportation_expense, true));
                $templateProcessor->setValue('b3_others', $this->currency($client->cwe->business_3_other_expense, true));
            }else{
                $templateProcessor->setValue('b3_labor', '');
                $templateProcessor->setValue('b3_rent', '');
                $templateProcessor->setValue('b3_uti', '');
                $templateProcessor->setValue('b3_transpo', '');
                $templateProcessor->setValue('b3_others', '');
            }
            
            $total_labor = $this->cn($client->cwe->business_1_labor_expense)+
                           $this->cn($client->cwe->business_2_labor_expense)+
                           $this->cn($client->cwe->business_3_labor_expense);

            $total_rent = $this->cn($client->cwe->business_1_rent_expense)+
                          $this->cn($client->cwe->business_2_rent_expense)+
                          $this->cn($client->cwe->business_3_rent_expense);

            $total_utilities = $this->cn($client->cwe->business_1_utility_expense)+ 
                               $this->cn($client->cwe->business_2_utility_expense)+
                               $this->cn($client->cwe->business_3_utility_expense);

            $total_transpo = $this->cn($client->cwe->business_1_transportation_expense)+
                             $this->cn($client->cwe->business_2_transportation_expense)+
                             $this->cn($client->cwe->business_3_transportation_expense);

            $total_others = $this->cn($client->cwe->business_1_other_expense)+
                            $this->cn($client->cwe->business_2_other_expense)+
                            $this->cn($client->cwe->business_3_other_expense);

            $templateProcessor->setValue('t_labor', $this->currency($total_labor,true));
            $templateProcessor->setValue('t_rent', $this->currency($total_rent,true));
            $templateProcessor->setValue('t_uti', $this->currency($total_utilities,true));
            $templateProcessor->setValue('t_transpo', $this->currency($total_transpo,true));
            $templateProcessor->setValue('t_others', $this->currency($total_others,true));

            $weekly_omfi1 = '';
            if ($client->cwe->mfi_1_loan_amount > 0 && $client->cwe->mfi_1_number_of_weeks_installment > 0) {
                $weekly_omfi1 = $this->cn($client->cwe->mfi_1_loan_amount) / $this->cn($client->cwe->mfi_1_number_of_weeks_installment); 
            }
            

            $templateProcessor->setValue('omfi_1', $client->cwe->mfi_1);
            $templateProcessor->setValue('a1_omfi', $this->currency($client->cwe->mfi_1_loan_amount));
            $templateProcessor->setValue('w1_omfi', $this->currency($weekly_omfi1));


            $weekly_omfi2 = '';
            if ($client->cwe->mfi_2_loan_amount > 0 && $client->cwe->mfi_2_number_of_weeks_installment > 0) {
               $weekly_omfi2 = $this->cn($client->cwe->mfi_2_loan_amount) / $this->cn($client->cwe->mfi_2_number_of_weeks_installment); 
            }
            

            $templateProcessor->setValue('omfi_2', $client->cwe->mfi_1);
            $templateProcessor->setValue('a2_omfi', $this->currency($client->cwe->mfi_2_loan_amount));
            $templateProcessor->setValue('w2_omfi', $this->currency($weekly_omfi2));

            $weekly_ape = '';
            $number_of_weeks = $this->cn($client->cwe->appliances_equipment_monthly_installment) * 4;

            if (!empty($client->cwe->appliances_equipment_loan_amount) && !empty($client->cwe->appliances_equipment_monthly_installment)) {
                $weekly_ape = $client->cwe->appliances_equipment_loan_amount / $number_of_weeks;
            }

            $templateProcessor->setValue('ape', $client->cwe->appliances_equipment);
            $templateProcessor->setValue('a_ape', $this->currency($client->cwe->appliances_equipment_loan_amount));
            $templateProcessor->setValue('m_ape', $this->currency($weekly_ape));

            $total_hh_income = $this->cn($client->cwe->salaries_wages_net)/4+
                               $this->cn($client->cwe->remittance)/4+
                               $this->cn($client->cwe->salary)/4;

            

            $templateProcessor->setValue('salary', $this->currency($client->cwe->salaries_wages_net, true));
            $templateProcessor->setValue('remittance', $this->currency($client->cwe->remittance, true));
            $templateProcessor->setValue('hi_oi', $this->currency($client->cwe->hh_other_income, true));
            $templateProcessor->setValue('hi_ti', $this->currency($total_hh_income));

            $total_household_expense = 
            $client->cwe->food/4 + 
            $client->cwe->hh_education/4 + 
            $client->cwe->transportation/4 + 
            $client->cwe->house_rent/4 + 
            $client->cwe->clothing/4 + 
            $client->cwe->water_bill/4 +
            $client->cwe->electricity_bill/4 +
            $client->cwe->others/4;

            $templateProcessor->setValue('food', $this->currency($client->cwe->food,true));
            $templateProcessor->setValue('educ', $this->currency($client->cwe->hh_education,true));
            $templateProcessor->setValue('transpo', $this->currency($client->cwe->transportation,true));
            $templateProcessor->setValue('rent', $this->currency($client->cwe->house_rent,true));
            $templateProcessor->setValue('clothing', $this->currency($client->cwe->clothing,true));
            $templateProcessor->setValue('water', $this->currency($client->cwe->water_bill,true));
            $templateProcessor->setValue('elec', $this->currency($client->cwe->electricity_bill,true));
            $templateProcessor->setValue('he_others', $this->currency($client->cwe->others,true));
            $templateProcessor->setValue('he_te', $this->currency($total_household_expense));


            $e1 = $total_labor/4 + $total_rent/4 + $total_transpo/4 + $total_utilities/4 + $total_others/4;

            $e2 = $this->cn($weekly_ape) + $this->cn($weekly_omfi1) + $this->cn($weekly_omfi2);
            $bndi = $total_income - ($e1+$e2);

            $twndi = $bndi + ($total_hh_income - $total_household_expense);
            $pccp = $twndi * .7;
            $ltw = $client->cwe->terms_in_months*4;
            $credit_limit = $pccp * $ltw;
            $cla = $credit_limit * .82;

            $templateProcessor->setValue('ltw', $ltw); 
            $templateProcessor->setValue('bndi', '₱ '.number_format($bndi,2,".",",")); 
            $templateProcessor->setValue('twndi', '₱ '.number_format($twndi,2,".",","));   
            $templateProcessor->setValue('pccp', '₱ '.number_format($pccp,2,".",","));
            $templateProcessor->setValue('cl', '₱ '.number_format($credit_limit,2,".",","));
            $templateProcessor->setValue('cla', '₱ '.number_format($cla,2,".",","));

            $n_p ='';
            $p_p ='';

            if ($pccp > 0) {
                $p_p ='X';
            }else{
                $n_p ='X';
            }
            $templateProcessor->setValue('p_p', $p_p);
            $templateProcessor->setValue('n_p', $n_p);*/

            if ($client->received == false) {
                $client->update(['received' => true]);    
            }
            
            $newFile = Storage::disk('public')->path('LAF Record - '.$name.'.docx');
            $templateProcessor->saveAs($newFile);
            
            return response()->download($newFile)->deleteFileAfterSend(true);
    }


}
