<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
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
    	'branch',
		'received',
		'batch_id'
	];
		

		public function ppi(){
			return $this->hasOne(PPI::class);
		}

		public function cwe(){
			return $this->hasOne(CWE::class);
		}

		public function pulledAt(){
			return $this->where('batch_id',$this->batch_id)->orderBy('created_at','desc')->limit(1)->get()->first()->created_at;
		}

}


