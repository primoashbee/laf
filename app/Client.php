<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $appends = ['age'];
	protected $casts = ['self_employed' => 'boolean','spouse_employed' => 'boolean','spouse_self_employed' => 'boolean'];
    protected $fillable = [
    	'first_name',
    	'middle_name',
    	'last_name',
    	'nickname',
    	'street_address',
    	'barangay',
    	'city',
    	'province',
    	'zip_code',
    	'years_of_stay',
    	'business_farm_street_address',
    	'business_barangay',
    	'business_farm_city',
    	'business_farm_province',
    	'business_farm_zip_code',
    	'house',
    	'birthday',
    	'gender',
		'birthplace',
		'tin_id',
		'civil_status',
		'other_ids',
		'education',
		'facebook_account_link',
		'mobile_number',
		'spouse_first_name',
		'spouse_middle_name',
		'spouse_last_name',
		'spouse_mobile_number',
		'spouse_birthday',
		'spouse_age',
		'number_of_dependents',
		'household_size',
		'mothers_maiden_name',
		'person_1_name',
		'person_1_contact_number',
		'person_1_whole_address',
		'person_2_name',
		'person_2_contact_number',
		'person_2_whole_address',
		'self_employed',
		'business_type',
		'estimated_monthly_income_for_business',
		'other_income',
		'other_income_monthly_estimated_earnings',
		'spouse_self_employed',
		'spouse_business_type',
		'monthly_income_for_spouse_business',
		'spouse_employed',
		'position',
		'company_name',
		'spouse_monthly_gross_income_at_work',
		'spouse_other_income',
		'spouse_other_income_monthly_estimated_earnings',
		'pension',
		'remittance',
    	'office_id',
		'received',
		'batch_id',
		'loan_officer',
		'timestamp',
		'created_by'
	];
		public function user(){
			return $this->hasOne(User::class,'id','created_by');
		}
		public function ppi(){
			return $this->hasOne(PPI::class);
		}

		public function cwe(){
			return $this->hasOne(CWE::class);
		}

		public function pulledAt(){
			return $this->where('batch_id',$this->batch_id)->orderBy('created_at','desc')->limit(1)->get()->first()->created_at;
		}
		public function office(){
			return $this->hasOne(Office::class,'id','office_id');
		}

		public static function batches(){
            if (auth()->user()->level!="MANAGER") {
				$unit = auth()->user()->level;
                return collect(
					DB::select(
						'SELECT x.* from (Select date(timestamp) as created_at, count(id) as total, RIGHT(loan_officer,1) AS unit, loan_officer, branch 
						from clients 
						group by date(timestamp), branch, loan_officer) X where x.unit = :unit',
						['unit'=>$unit]
					)
				);
				
			}
			return collect(DB::select('Select date(timestamp) as created_at, count(id) as total, RIGHT(loan_officer,1) AS unit, loan_officer, branch from clients group by date(timestamp), branch, loan_officer'));
			// if (auth()->user()->level!="MANAGER") {
			// 	$unit = auth()->user()->level;
            //     return collect(
			// 		DB::select(
			// 			"SELECT x.* from (Select STR_TO_DATE(timestamp, '%m/%d/%Y') as timestamp, count(id) as total, RIGHT(loan_officer,1) AS unit, branch 
			// 			from clients 
			// 			group by timestamp, branch, loan_officer) X where x.unit = :unit",
			// 			['unit'=>$unit]
			// 		)
			// 	);
			// }
			// return collect(DB::select("Select STR_TO_DATE(timestamp, '%m/%d/%Y') as timestamp, count(id) as total, branch from clients group by timestamp, branch"));
			
		}

		public static function batchesByLoanOfficer(){
			return collect(DB::select('Select date(created_at) as created_at, count(id) as total, branch, loan_officer from clients group by date(created_at), branch, loan_officer'));
		}


		public function getAgeAttribute(){
			return Carbon::parse($this->birthday)->age;
		}
		public function getSpouseAgeAttribute(){
			return Carbon::parse($this->spouse_birthday)->age;
		}

		public function getName(){
			return $this->last_name. ', '.$this->first_name.', ' . $this->middle_name;
		}
		public function personalInformation(){
			$c = $this;
			
			$house_type="Rented";
			if($c->house == "Owned"){
				$house_type = 'Owned';
			}
	
			$info = [
				'name'=>$c->getName(),
				'nickname'=>$c->nickname,
				'present_home_address'=>ucwords($c->street_address.' '.'Brgy.'.$c->barangay.' '.$c->city.', '.$c->province),
				'years_of_stay'=>$c->years_of_stay,
				'busines_farm_address'=>ucwords($c->business_farm_street_address.' '.'Brgy.'.$c->business_barangay.' '.$c->business_farm_city.', '.$c->business_farm_province),
				'house_type'=> $house_type,
				'birthday'=>$c->birthday,
				'age'=>$c->age,
				'gender'=>$c->gender,
				'birthplace'=>$c->birthplace,
				'civil_status'=>$c->civil_status,
				'tin'=>$c->tin_id,
				'other_ids'=>$c->other_ids,
				'education'=>$c->education,
				'fb_account'=>$c->facebook_account_link,
				'mobile_number'=>$c->mobile_number,
				'spouse_name'=>$c->spouse_first_name.' '.$c->spouse_middle_name.' '.$c->spouse_last_name,
				'spouse_mobile'=>$c->spouse_mobile_number,
				'spouse_birthday'=>$c->spouse_birthday,
				'spouse_age'=>$c->spouse_age,
				'mother_maiden_name'=>$c->mothers_maiden_name,
				'number_of_dependents'=>$c->number_of_dependents,
				'household_size'=>$c->household_size
			];
			return (object) $info;
		}

		public function personalPreferences(){
			$c = $this;
			$info = [
				'person_1_name'=>$c->person_1_name,
				'person_1_contact'=>$c->person_1_contact_number,
				'person_1_address'=>$c->person_1_whole_address,
				'person_2_name'=>$c->person_2_name,
				'person_2_contact'=>$c->person_2_contact_number,
				'person_2_address'=>$c->person_2_whole_address,
			];
		
			return (object) $info;
		}

		public function ppiSummary(){
			$c = $this;
			$info = [
				'q1'=> (object) [
					'answer'=>$c->ppi->ppi_q_1,
					'score'=>$this->ppiAnswerScore(1,$c->ppi->ppi_q_1)
				],
				'q2'=>(object) [
					'answer'=>$c->ppi->ppi_q_2,
					'score'=>$this->ppiAnswerScore(2,$c->ppi->ppi_q_2)
				],
				'q3'=>(object) [
					'answer'=>$c->ppi->ppi_q_3,
					'score'=>$this->ppiAnswerScore(3,$c->ppi->ppi_q_3)
				],
				'q4'=>(object) [
					'answer'=>$c->ppi->ppi_q_4,
					'score'=>$this->ppiAnswerScore(4,$c->ppi->ppi_q_4)
				],
				'q5'=>(object) [
					'answer'=>$c->ppi->ppi_q_5,
					'score'=>$this->ppiAnswerScore(5,$c->ppi->ppi_q_5)
				],
				'q6'=>(object) [
					'answer'=>$c->ppi->ppi_q_6,
					'score'=>$this->ppiAnswerScore(6,$c->ppi->ppi_q_6)
				],
				'q7'=>(object) [
					'answer'=>$c->ppi->ppi_q_7,
					'score'=>$this->ppiAnswerScore(7,$c->ppi->ppi_q_7)
				],
				'q8'=>(object) [
					'answer'=>$c->ppi->ppi_q_8,
					'score'=>$this->ppiAnswerScore(8,$c->ppi->ppi_q_8)
				],
				'q9'=>(object) [
					'answer'=>$c->ppi->ppi_q_9,
					'score'=>$this->ppiAnswerScore(9,$c->ppi->ppi_q_9)
				],
				'q10'=>(object) [
					'answer'=>$c->ppi->ppi_q_10,
					'score'=>$this->ppiAnswerScore(10,$c->ppi->ppi_q_10)
				],
			];

			$score = 0;
			foreach($info as $i){
				$score = $score + $i->score;
			}
			$info['total'] = $score;
			return (object) $info;
		}

		public function ppiAnswerScore($qn,$answer){
			if($qn==1){
				if ($answer == "Walo o Higit pa") {
					return  0;
				}
				if ($answer == "Pito") {
					return  2;
				}
				if ($answer == "Anim") {
					return  6;
				}
				if ($answer == "Lima") {
					return  11;
				}
				 if ($answer == "Apat") {
					return 15;
				}
				if ($answer == "Tatlo") {
					return  21;
				}
				if ($answer == "Dalawa o Isa") {
					return  30;
				}    
			}
			if($qn==2){
				if ($answer== "Hindi") {
					return  0;
				}
	
				if ($answer== "Oo") {
					return  1;
				}
	
				if ($answer== "Walang may edad 6-17") {
					return  2;
				} 
			}
			if($qn==3){
				if ($answer == "Wala") {
					return 0;
				}
				if ($answer == "Isa") {
					return 2;
				}
				
				if ($answer == "Dalawa") {
					return 7;
				}
				
				if ($answer == "Tatlo o higit pa") {
					return 12;
				} 
			}

			if($qn==4){
				if ($answer == "Tatlo o higit pa") {
					return 0;
				}
				
				if ($answer == "Dalawa") {
					return 4;
				}
				
				if ($answer == "Isa") {
					return 8;
				}
				
				if ($answer == "Wala") {
					return 12;
				}
			}

			if($qn==5){
				if ($answer == "Elementary o No Grade Completed") {
					return 0;
				}
				
				if ($answer == "Walang babaeng puno ng pamilya") {
					return 2;
				}
				
				if ($answer == "Elementary or HS undergrad") {
					return 2;
				}
				
				if ($answer == "High School Graduate") {
					return 4;
				}
				
				if ($answer == "College undergrad o higit pa") {
					return 7;
				}
			}
			if($qn==6){
				if ($answer == "Light Materials (LM) (cogon/nipa/anahaw) or mixed but more LM") {
					return 0;
				}
				if ($answer == "Mixed but predominantly strong materials") {
					return 2;
				}
				
				if ($answer == "Strong materials (galvanized iron, aluminum, tile, concrete, brick, stone, wood, plywood, asbestos)") {
					return 3;
				}
			}
			if($qn==7){
				if ($answer == "Hindi (Walang pagmamay-ari)") {
					return 0;
				}
	
				if ($answer == "Oo (Mayroong pagmamay-ari)") {
					return 3;
				}
			}
			if($qn==8){
				if ($answer == "Hindi (Walang pagmamay-ari)") {
					return 0;
				}
	
				if ($answer == "1 sa nabanggit, pero hindi pareho") {
					return 6;
				}
	
				if ($answer == "Parehong may pagmamay-ari") {
					return 12;
				}
			}
			if($qn==9){
				if ($answer == "Hindi/ Wala") {
					return 0;
				}
	
				if ($answer == "TV lamang") {
					return 4;
				}
				if ($answer == "TV/ VCD/DVD player") {
					return 7;
				}   
			}
			if($qn==10){
				if ($answer == "Wala") {
					return 0;
				}
	
				if ($answer == "Isa") {
					return 4;
				}
	
				if ($answer == "Dalawa") {
					return 7;
				}
				if ($answer == "Tatlo o Higit pa") {
					return 12;
				}
			}
			


		}

		public function householdIncomeSummary(){
			$c = $this;
			$client = (object) [
				'self_employed'=>$c->self_employed,
				'self_employed_business_type'=>$c->business_type,
				'self_employed_gross_income'=>$c->estimated_monthly_income_for_business,
				'other_income'=>$c->other_income,
				'other_income_amount'=>$c->other_income_monthly_estimated_earnings,
				'total_income'=>(float)$c->estimated_monthly_income_for_business + (float) $c->other_income_monthly_estimated_earnings
			];
			$spouse = (object) [
				'self_employed'=>$c->spouse_self_employed,
				'self_employed_business_type'=>$c->spouse_business_type,
				'self_employed_gross_income'=>$c->monthly_income_for_spouse_business,
				'employed'=>$c->spouse_employed,
				'employed_position'=>$c->position,
				'employed_company'=>$c->company,
				'employed_gross_income'=>$c->spouse_monthly_gross_income_at_work,
				'other_income'=>$c->spouse_other_income,
				'other_income_amount'=>$c->spouse_other_income_monthly_estimated_earnings,
				'total_income'=>(float)$c->monthly_income_for_spouse_business + (float)$c->spouse_monthly_gross_income_at_work + (float)$c->spouse_other_income_monthly_estimated_earnings

			];
			$other = (object) [
				'remittances'=>$c->remittance,
				'pension'=>$c->pension,
				'total_income'=>(float) $c->remittance + (float) $c->pension
			];
			$total = 0;
		
			$income = ['client'=>$client,'spouse'=>$spouse,'other'=>$other];
			foreach($income as $i){
				$total = $total + (float) $i->total_income;
			}
			$income['total'] = $total;
			return (object) $income;
		}

		public function cweSummary(){
			$c = $this;
			$info = [
				'loan_type'=>$c->cwe->loan_type,
				'branch'=>$c->cwe->branch,
				'cluster'=>$c->cwe->cluster,
				'lo'=>$c->loan_officer
			];
			return (object) $info;
		}
		public function getTimestampAttribute($value){
			return Carbon::parse($value);
		}
		public function getSelfEmployedAttribute($value){
			if(is_null($value)){
				return false;
			}
			return $value;
		}



		public function canBeExportedBy($user_id){
			$client_office_id = $this->office->id;
			$list = User::find($user_id)->office->first()->getLowerOfficeIDS();
			if(in_array($client_office_id,$list)){
				return true;
			}
			return false;
		}

}


