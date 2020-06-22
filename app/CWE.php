<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CWE extends Model
{
	protected $table = 'cwe';
	protected $fillable = [
		'client_id',
		'loan_type',
		'branch',
		'cluster',
		'loan_purpose',
		'type_of_loan',
		'loan_cycle',
		'date_of_membership',
		'prefered_loan_amount',
		'terms_in_months',
		'business_1_business_income',
		'business_2_business_income',
		'business_3_business_income',
		'business_1_labor_expense',
		'business_1_rent_expense',
		'business_1_utility_expense',
		'business_1_transportation_expense',
		'business_1_other_expense',
		'business_2_labor_expense',
		'business_2_rent_expense',
		'business_2_utility_expense',
		'business_2_transportation_expense',
		'business_2_other_expense',
		'business_3_labor_expense',
		'business_3_rent_expense',
		'business_3_utility_expense',
		'business_3_transportation_expense',
		'business_3_other_expense',
		'mfi_1',
		'mfi_1_loan_amount',
		'mfi_1_number_of_weeks_installment',
		'mfi_2',
		'mfi_2_loan_amount',
		'mfi_2_number_of_weeks_installment',
		'appliances_equipment',
		'appliances_equipment_loan_amount',
		'appliances_equipment_monthly_installment',
		'salaries_wages_net',
		'remittances',
		'hh_other_income',
		'food',
		'hh_education',
		'transportation',
		'house_rent',
		'clothing',
		'water_bill',
		'electricity_bill',
		'others',
		'character',
		'capacity',
		'capital',
		'collateral',
		'condition',
		'response'
	];    
	public function client(){
    	return $this->belongsTo(Client::class);
    }
}
