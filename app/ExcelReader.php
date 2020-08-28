<?php

namespace App;
use Carbon\Carbon;
class ExcelReader
{
	protected $data;
	public $client;
	public $cwe;
	public $ppi;
	public $batch_id;
	public function __construct($array,$batch_id)
	{
			$this->batch_id =  $batch_id;
			$date = Carbon::parse($array[0])->format('d-F-Y');
			$birthday = Carbon::parse($array[17])->format('d-F-Y');
			$spouse_birthday= Carbon::parse($array[30])->format('d-F-Y');
			$this->client = array(
				'timestamp' => $date,				
				'first_name' => $array[1],
				'middle_name' => $array[2],
				'last_name' => $array[3],
				'nickname' => $array[4],
				'street_address' => $array[5],
				'barangay' => $array[6],
				'city' => $array[7],
				'province' => $array[8],
				'zip_code' => $array[9],
				'years_of_stay' => $array[10],
				'business_farm_street_address' => $array[11],
				'business_barangay' => $array[12],
				'business_farm_city' => $array[13],
				'business_farm_province' => $array[14],
				'business_farm_zip_code' => $array[15],
				'house' => $array[16],
				'birthday' => $birthday,
				'gender' => $array[18],
				'birthplace' => $array[19],
				'tin_id' => $array[20],
				'civil_status' => $array[21],
				'other_ids' => $array[22],
				'education' => $array[23],
				'facebook_account_link' => $array[24],
				'mobile_number' => $array[25],
				'spouse_first_name' => $array[26],
				'spouse_middle_name' => $array[27],
				'spouse_last_name' => $array[28],
				'spouse_mobile_number' => $array[29],
				'spouse_birthday' => $spouse_birthday,
				'spouse_age' => $array[31],
				'number_of_dependents' => $array[32],
				'household_size' => $array[33],
				'mothers_maiden_name' => $array[34],
				'person_1_name' => $array[35],
				'person_1_contact_number' => $array[36],
				'person_1_whole_address' => $array[37],
				'person_2_name' => $array[38],
				'person_2_contact_number' => $array[39],
				'person_2_whole_address' => $array[40],
				'self_employed' => $array[41],
				'business_type' => $array[42],
				'estimated_monthly_income_for_business' => $array[43],
				'other_income' => $array[44],
				'other_income_monthly_estimated_earnings' => $array[45],
				'spouse_self_employed' => $array[46],
				'spouse_business_type' => $array[47],
				'monthly_income_for_spouse_business' => $array[48],
				'spouse_employed' => $array[49],
				'position' => $array[50],
				'company_name' => $array[51],
				'spouse_monthly_gross_income_at_work' => $array[52],
				'spouse_other_income' => $array[53],
				'spouse_other_income_monthly_estimated_earnings' => $array[54],
				'pension' => $array[55],
				'remittance' => $array[56],
				'branch' => $array[69],
				'loan_officer' => $array[71],
				'received' => false,
				'batch_id' => $this->batch_id
			);
			$this->ppi = array(
				'ppi_q_1' => $array[57],
				'ppi_q_2' => $array[58],
				'ppi_q_3' => $array[59],
				'ppi_q_4' => $array[60],
				'ppi_q_5' => $array[61],
				'ppi_q_6' => $array[62],
				'ppi_q_7' => $array[63],
				'ppi_q_8' => $array[64],
				'ppi_q_9' => $array[65],
				'ppi_q_10' => $array[66],
			);

			// $date_of_membership = Carbon::parse($array[73])->format('d-F-Y');

			$this->cwe = array(
			 	'loan_type'	=> $array[68],
			 	'branch'	=> $array[69],
			 	'cluster'	=> $array[70],
			 /*	'loan_purpose'	=> $array[70],
			 	'type_of_loan'	=> $array[71],
			 	'loan_cycle'	=> $array[72],
			 	'date_of_membership'	=> $date_of_membership,
			 	'prefered_loan_amount'	=> $array[74],
			 	'terms_in_months'	=> $array[75],
			 	'business_1_business_income'	=> $array[76],
			 	'business_2_business_income'	=> $array[77],
			 	'business_3_business_income'	=> $array[78],
			 	'business_1_labor_expense'	=> $array[79],
			 	'business_1_rent_expense'	=> $array[80],
			 	'business_1_utility_expense'	=> $array[81],
			 	'business_1_transportation_expense'	=> $array[82],
			 	'business_1_other_expense'	=> $array[83],
			 	'business_2_labor_expense'	=> $array[84],
			 	'business_2_rent_expense'	=> $array[85],
			 	'business_2_utility_expense'	=> $array[86],
			 	'business_2_transportation_expense'	=> $array[87],
			 	'business_2_other_expense'	=> $array[88],
			 	'business_3_labor_expense'	=> $array[89],
			 	'business_3_rent_expense'	=> $array[90],
			 	'business_3_utility_expense'	=> $array[91],
			 	'business_3_transportation_expense'	=> $array[92],
			 	'business_3_other_expense'	=> $array[93],

			 	'mfi_1' => $array[94],
				'mfi_1_loan_amount' => $array[95],
				'mfi_1_number_of_weeks_installment' => $array[96],
				'mfi_2' => $array[97],
				'mfi_2_loan_amount' => $array[98],
				'mfi_2_number_of_weeks_installment' => $array[99],
				'appliances_equipment' => $array[100],
				'appliances_equipment_loan_amount' => $array[101],
				'appliances_equipment_monthly_installment' => $array[102],
				'salaries_wages_net' => $array[103],
				'remittances' => $array[104],
				'hh_other_income' => $array[105],
				'food' => $array[106],
				'hh_education' => $array[107],
				'transportation' => $array[108],
				'house_rent' => $array[109],
				'clothing' => $array[110],
				'water_bill' => $array[111],
				'electricity_bill' => $array[112],
				'others' => $array[113],*/
			);
	}

}

