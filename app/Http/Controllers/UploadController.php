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
use Illuminate\Support\Str;

class UploadController extends Controller
{
    //
    public function index(){
        
        return view('upload');
    }

    public function get(){
        
        $token = [
            'access_token'  => env('ACCESS_TOKEN'),
            'refresh_token' => env('REFRESH_TOKEN'),
            'expires_in'    => env('EXPIRES_IN'),
            'created'       => env('CREATED'),
        ];
        
        $values = Sheets::setAccessToken($token)->spreadsheet('1qAkyBUKgkN7ISvHFlOoOAN5QaXTga2A5QFIVNEk7pug')->sheet('Form Responses 1')->all();
        return $values;
    }


    
    public function upload1(){
        $template = Storage::disk('public')->path('LAF Final Template.docx');
        $templateProcessor = new TemplateProcessor($template);
        $templateProcessor->setValue('e', 'X');
        $templateProcessor->setValue('name', 'Morgado, John Ashbee, A.');
        $row = 'pito';
        $score = 0;
        
        if ($row =='') {
            $score =2;
        }
        if ($row =='Anim') {
            $score = 6;
        }
    }

    public function currency($value){
        return '₱ '.number_format($value,2,".",",");
    }

    public function check_5c($value, $string){
        $cb = '☐';
        if (Str::contains($value, $string)) {
            $cb = '☒';
        }
        return $cb;
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
                    $entry_date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[0]));
                    $templateProcessor->setValue('date', $entry_date->toDateString());
                    $name = ucwords($value[1]). ' '. ucwords($value[2]). ' '. ucwords($value[3]);
                    $templateProcessor->setValue('name', $name);
                    $nickname = ucwords($value[4]);
                    $templateProcessor->setValue('nickname', $nickname);
                    $present_address = ucwords($value[5]). ' Brgy. '. ucwords($value[6]). ' '. ucwords($value[7]). ' '. ucwords($value[8]);
                    $templateProcessor->setValue('present_home_address', $present_address);
                    $templateProcessor->setValue('years_of_stay', $value[10]);
                    $business_address = ucwords($value[11]). ' Brgy. '. ucwords($value[12]). ' '. ucwords($value[13]). ' '. ucwords($value[14]);
                    $templateProcessor->setValue('business_address', $business_address);
                    $owned= '';
                    $rented= '';
                    if($value[16] == "Owned"){
                        $owned = 'X';
                    }

                    if($value[16] == "Rented"){
                        $rented = 'X';
                    }

                    $templateProcessor->setValue('ho', $owned);
                    $templateProcessor->setValue('hr', $rented);

                    $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[17]));
                    
                    $templateProcessor->setValue('birthday', $date->toDateString());
                    $templateProcessor->setValue('age', $date->age);

                    $male= '';
                    $female= '';
                    if($value[18] == "Male"){
                        $male = 'X';
                    }

                    if($value[18] == "Female"){
                        $female = 'X';
                    }
                    
                    $templateProcessor->setValue('m', $male);
                    $templateProcessor->setValue('f', $female);
                     
                    $templateProcessor->setValue('birthplace', ucwords($value[19]));
                    $templateProcessor->setValue('tin', $value[20]);
                    $single= '';
                    $married= '';
                    $widowed= '';
                    $separated= '';
                    if($value[21] == "Single"){
                        $single = 'X';
                    }

                    if($value[21] == "Married"){
                        $married = 'X';
                    }

                    if($value[21] == "Widow"){
                        $widowed = 'X';
                    }

                    if($value[21] == "Separated"){
                        $separated = 'X';
                    }

                    $templateProcessor->setValue('cs', $single);
                    $templateProcessor->setValue('cm', $married);
                    $templateProcessor->setValue('cw', $widowed);
                    $templateProcessor->setValue('cse', $separated);
                    $templateProcessor->setValue('umid', $value[22]);

                      
                    $post_grad= '';
                    $college = '';
                    $highschool= '';
                    $elementary= '';
                    $others= '';
                    if($value[23] == "Post Graduate"){
                        $post_grad = 'X';
                    }

                    if($value[23] == "College"){
                        $college = 'X';
                    }

                    if($value[23] == "High School"){
                        $highschool = 'X';
                    }

                    if($value[23] == "Elementary"){
                        $elementary = 'X';
                    }

                    if($value[23] != "Post Graduate" && $value[23] != "College" && $value[23] != "High School" && $value[23] != "Elementary"){
                        $others = $value[23];
                    }

                    $templateProcessor->setValue('ep', $post_grad);
                    $templateProcessor->setValue('ec', $college);
                    $templateProcessor->setValue('eh', $highschool);
                    $templateProcessor->setValue('ee', $elementary);
                    $templateProcessor->setValue('eo', $others);

                    $templateProcessor->setValue('fb_account', $value[24]);
                    $templateProcessor->setValue('contact', $value[25]);
                    
                    $spouse_name = $value[28].' '.$value[27].' '.$value[26];

                    $templateProcessor->setValue('s_name', $spouse_name);

                    $templateProcessor->setValue('s_contact', $value[29]);

                    $date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[30]));
                    $templateProcessor->setValue('s_birthday', $date->toDateString());

                    $templateProcessor->setValue('s_age', $date->age);

                    $templateProcessor->setValue('dependents', $value[32]);

                    $templateProcessor->setValue('household', $value[33]);

                    $templateProcessor->setValue('mother_name', $value[34]);

                    // Personal References

                    $templateProcessor->setValue('pr1_name', $value[35]);
                    $templateProcessor->setValue('pr1_contact', $value[36]);
                    $templateProcessor->setValue('p1r_address', $value[37]);

                    $templateProcessor->setValue('pr2_name', $value[38]);
                    $templateProcessor->setValue('pr2_contact', $value[39]);
                    $templateProcessor->setValue('p2r_address', $value[40]);
            

                    // Household Income

                    $self_employed='';
                    $other_income='';
                    $spouse_other_income='';
                    if ($value[41] == 'Yes') {
                        $self_employed = 'X';
                    }
                    if (!empty($value[44])) {
                        $other_income = 'X';
                    }
                    
                    $templateProcessor->setValue('e', $self_employed);
                    $templateProcessor->setValue('service_type', $value[42]);
                    
                    $templateProcessor->setValue('b_mgi', $this->currency($value[43]));

                    $templateProcessor->setValue('o', $other_income);
                    $templateProcessor->setValue('o_name', $value[44]);
                    
                    $templateProcessor->setValue('o_mgi', $this->currency($value[45]));

                    // Spouse Employment Information

                    $spouse_self_employed='';
                    $spouse_employed='';
                    if ($value[46] == 'Yes') {
                        $spouse_self_employed = 'X';
                    }

                    $templateProcessor->setValue('se', $spouse_self_employed);
                    $templateProcessor->setValue('s_service_type', $value[47]);
                    
                    $templateProcessor->setValue('se_mgi', $this->currency($value[48]));



                    if ($value[49] == 'Yes') {
                        $spouse_employed = 'X';
                    }
                    $templateProcessor->setValue('sep', $spouse_employed);
                    $templateProcessor->setValue('position', $value[50]);
                    $templateProcessor->setValue('company', $value[51]);
                    
                    $templateProcessor->setValue('sep_mgi', $this->currency($value[52]));

                    if (!empty($value[53])) {
                        $spouse_other_income = 'X';
                    }

                    $templateProcessor->setValue('so', $spouse_other_income);
                    $templateProcessor->setValue('so_name', $value[53]);
                    $templateProcessor->setValue('so_mgi', $value[54]);

                    $pension ='';
                    $remittance ='';
                    $total_others=$value[55]+$value[56];

                    if (!empty($value[55])) {
                        $pension = 'X';
                    }
                    if (!empty($value[56])) {
                        $remittance = 'X';
                    }

                    $templateProcessor->setValue('rem', $remittance);
                    $templateProcessor->setValue('pen', $pension);
                    $templateProcessor->setValue('total_others', $total_others);

                    $total_household_income= $this->currency($value[43]+$value[45]+$value[48]+$value[52]+$value[54]+$value[56]);
                    
                    $templateProcessor->setValue('total_hh', $total_household_income);

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

                $new_loan='';
                $reloan='';

                if ($value[67] == 'New Loan') {
                    $new_loan = 'X';
                }
                if ($value[67] == 'Reloan') {
                    $new_loan = 'X';
                }
                $templateProcessor->setValue('nl', $new_loan);
                $templateProcessor->setValue('rl', $reloan);
                $templateProcessor->setValue('branch', $value[68]);
                $templateProcessor->setValue('cluster', $value[69]);
                $templateProcessor->setValue('loan_purpose', $value[70]);

                $mpl = '[   ]MPL';
                $gml = '[   ]GML';
                $llp = '[   ]LLP';
                $agl = '[   ]AGL';

                if ($value[71] == 'MPL') {
                    $mpl = '[ X ]MPL';
                }
                if ($value[71] == 'GML') {
                    $gml = '[ X ]GML';
                }

                if ($value[71] == 'LLP') {
                    $llp = '[ X ]LLP';
                }

                if ($value[71] == 'AGL') {
                    $agl = '[ X ]AGL';
                }

                $templateProcessor->setValue('mpl', $mpl); 
                $templateProcessor->setValue('gml', $gml); 
                $templateProcessor->setValue('llp', $llp); 
                $templateProcessor->setValue('agl', $agl); 
                
                $templateProcessor->setValue('lc', $value[72]); 
                $date_of_membership = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[73]));       

                $templateProcessor->setValue('dom', $date_of_membership->toDateString());  
                $templateProcessor->setValue('pref_loan', $this->currency($value[74]));
                $templateProcessor->setValue('terms_in_months', $value[75]);


                $total_income = $value[76]+$value[77]+$value[78];

                $templateProcessor->setValue('bi_1', $this->currency($value[76]));
                $templateProcessor->setValue('bi_2', $this->currency($value[77]));
                $templateProcessor->setValue('bi_3', $this->currency($value[78]));
                $templateProcessor->setValue('bus_ti', $this->currency($total_income));


                $templateProcessor->setValue('b1_labor', $this->currency($value[79]));
                $templateProcessor->setValue('b1_rent', $this->currency($value[80]));
                $templateProcessor->setValue('b1_uti', $this->currency($value[81]));
                $templateProcessor->setValue('b1_transpo', $this->currency($value[82]));
                $templateProcessor->setValue('b1_others', $this->currency($value[83]));


                $templateProcessor->setValue('b2_labor', $this->currency($value[84]));
                $templateProcessor->setValue('b2_rent', $this->currency($value[85]));
                $templateProcessor->setValue('b2_uti', $this->currency($value[86]));
                $templateProcessor->setValue('b2_transpo', $this->currency($value[87]));
                $templateProcessor->setValue('b2_others', $this->currency($value[88]));

                $templateProcessor->setValue('b3_labor', $this->currency($value[89]));
                $templateProcessor->setValue('b3_rent', $this->currency($value[90]));
                $templateProcessor->setValue('b3_uti', $this->currency($value[91]));
                $templateProcessor->setValue('b3_transpo', $this->currency($value[92]));
                $templateProcessor->setValue('b3_others', $this->currency($value[93]));

                $templateProcessor->setValue('omfi_1', $value[94]);
                $templateProcessor->setValue('a1_omfi', $this->currency($value[95]));
                $templateProcessor->setValue('w1_omfi', $this->currency($value[96]));

                $templateProcessor->setValue('omfi_2', $value[97]);
                $templateProcessor->setValue('a2_omfi', $this->currency($value[98]));
                $templateProcessor->setValue('w2_omfi', $this->currency($value[99]));

                $templateProcessor->setValue('ape', $value[100]);
                $templateProcessor->setValue('a_ape', $this->currency($value[101]));
                $templateProcessor->setValue('m_ape', $this->currency($value[102]));

                $total_labor = $this->currency($value[79]+$value[84]+$value[89]);
                $total_rent = $this->currency($value[80]+$value[85]+$value[90]);
                $total_utilities = $this->currency($value[81]+$value[86]+$value[91]);
                $total_transpo = $this->currency($value[82]+$value[87]+$value[92]);
                $total_others = $this->currency($value[83]+$value[88]+$value[93]);

                $templateProcessor->setValue('t_labor', $total_labor);
                $templateProcessor->setValue('t_rent', $total_rent);
                $templateProcessor->setValue('t_uti', $total_utilities);
                $templateProcessor->setValue('t_transpo', $total_transpo);
                $templateProcessor->setValue('t_others', $total_others);


                $total_hh_income = $this->currency($value[103]+$value[104]+$value[105]);
                $templateProcessor->setValue('salary', $this->currency($value[103]));
                $templateProcessor->setValue('remittance', $this->currency($value[104]));
                $templateProcessor->setValue('hi_oi', $this->currency($value[105]));
                $templateProcessor->setValue('hi_ti', $total_hh_income);


                $total_household_expense = $this->currency($value[106]+$value[107]+$value[108]+$value[109]+$value[110]+$value[111]+$value[112]+$value[113]);

                $templateProcessor->setValue('food', $this->currency($value[106]));
                $templateProcessor->setValue('educ', $this->currency($value[107]));
                $templateProcessor->setValue('transpo', $this->currency($value[108]));
                $templateProcessor->setValue('rent', $this->currency($value[109]));
                $templateProcessor->setValue('clothing', $this->currency($value[110]));
                $templateProcessor->setValue('water', $this->currency($value[111]));
                $templateProcessor->setValue('elec', $this->currency($value[112]));
                $templateProcessor->setValue('he_others', $this->currency($value[113]));
                $templateProcessor->setValue('he_te', $total_household_expense);

                $cb1= $this->check_5c($value[114], "Shows honesty and integrity");
                $cb2= $this->check_5c($value[114], "Reputation in the community is good, no cases in the barangay");
                $cb3= $this->check_5c($value[114], "Good repayment behavior from other MFI (if applies");
                $cb4= $this->check_5c($value[114], "Family supports loan application of the Partner Client");
                $cb5= $this->check_5c($value[114], "Family members shows support to each other");

                $cb6= $this->check_5c($value[115], "HH income is greater than HH expenses");
                $cb7= $this->check_5c($value[115], "Current business inventory is higher than the applied loan.");

                $cb8= $this->check_5c($value[116], "Family including PC invested additional money in the business aside from loan with LIGHT MFI");

                $cb9= $this->check_5c($value[117], "Total business assets are greater than the loan applied");
                $cb10= $this->check_5c($value[117], "Co-maker has other stable source of income");
                $cb11= $this->check_5c($value[117], "With Savings");

                $cb12= $this->check_5c($value[118], "Has regular supplier");
                $cb13= $this->check_5c($value[118], "Business is not seasonal");
                $cb14= $this->check_5c($value[118], "Business exist at least 1 year");
                $cb15= $this->check_5c($value[118], "Has adequate and stable market to sustain the business");

                $total_score_5c = 0;
                for ($i=0; $i < 15 ; $i++) { 
                    $cbs=[$cb1,$cb2,$cb3,$cb4,$cb5,$cb6,$cb7,$cb8,$cb9,$cb10,$cb11,$cb12,$cb13,$cb14,$cb15];
                    if ($cbs[$i] == '☒') {
                        $total_score_5c += 1;
                    }

                }


                $templateProcessor->setValue('cb1', $cb1);
                $templateProcessor->setValue('cb2', $cb2);
                $templateProcessor->setValue('cb3', $cb3);
                $templateProcessor->setValue('cb4', $cb4);
                $templateProcessor->setValue('cb5', $cb5);
                $templateProcessor->setValue('cb6', $cb6);
                $templateProcessor->setValue('cb7', $cb7);
                $templateProcessor->setValue('cb8', $cb8);
                $templateProcessor->setValue('cb9', $cb9);
                $templateProcessor->setValue('cb10', $cb10);
                $templateProcessor->setValue('cb11', $cb11);
                $templateProcessor->setValue('cb12', $cb12);
                $templateProcessor->setValue('cb13', $cb13);
                $templateProcessor->setValue('cb14', $cb14);
                $templateProcessor->setValue('cb15', $cb15);
                $templateProcessor->setValue('t5c', $total_score_5c);
                $newFile = Storage::disk('public')->path($folder.'/LAF Record - '.$name.'.docx');
                $templateProcessor->saveAs($newFile);
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
