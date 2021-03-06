<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Client;
use App\Office;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
	$gender = $faker->randomElement(['MALE', 'FEMALE']);
    $civil_status = $faker->randomElement(['SINGLE', 'MARRIED','DIVORCED']);
    $education = $faker->randomElement(['ELEMENTARY', 'HIGH SCHOOL','COLLEGE','VOCATIONAL']);
    $barangay = $faker->randomElement(['San Jose', 'Sta. Rita','Gordon Heights','Pag-asa']);
    $province = $faker->randomElement(['Zambales', 'Pampanga','Bataan']);
    $type = $faker->randomElement(['TRADING / MERCHANDISING', 'MANUFACTURING','SERVICE','AGRICULTURE']);
	$contact = "09190700608";
    $dependents = rand(1,5);
	$id_type = $faker->randomElement(['School ID','PASSPORT','PRC ID','GSIS','SSS','UMID','DRIVERS LICENSE','PHILHEALTH','TIN','VOTERS ID','DIGITIZED POSTAL ID','SOCIAL AMELIORATION PROGRAM','SENIOR CITIZENS ID','EMPLOYEES ID','PAGIBIG MEMBER ID','SOLO PARENT ID','DSWD ID','4PS ID','BARANGAY ID','AFP DEPENDEDNT ID']);
    $house_type = $faker->randomElement(['RENTED','OWNED']);
    $lo = $faker->randomElement([
		'LO1A',
		'LO2A',
		'LO3A',
		'LO4A',
		'LO5A',
		'LO6A',
		'LO7A',
		'LO8A',
		'LO9A',
		'L10A',
		'L01B',
		'L02B',
		'L03B',
		'L04B',
		'L05B',
		'L06B',
		'L07B',
		'L08B',
		'L09B',
		'L10B',
		]);
    return [
        'first_name' => $faker->firstName,
    	'middle_name' =>$faker->firstName,
    	'last_name'=>$faker->lastName,
    	'nickname' =>$faker->firstName,
    	'street_address'=>$faker->firstName,
    	'barangay'=>$barangay,
    	'city'=>$faker->city,
    	'province'=>$province,
    	'zip_code'=>$faker->firstName,
    	'years_of_stay'=>rand(0,9),
    	'business_farm_street_address'=>$faker->firstName,
    	'business_barangay'=>$faker->firstName,
    	'business_farm_city'=>$faker->firstName,
    	'business_farm_province'=>$faker->firstName,
    	'business_farm_zip_code'=>$faker->firstName,
    	'house'=>$house_type,
    	'birthday'=>$faker->date,
    	'gender'=>$gender,
		'birthplace'=>$faker->firstName,
		'tin_id'=>$faker->firstName,
		'civil_status'=>$civil_status,
		'other_id_type'=>$id_type,
		'other_id_number'=>rand(11111111,999999),
		'education'=>$education,
		'facebook_account_link'=>$faker->firstName,
		'mobile_number'=>$contact,
		'spouse_first_name'=>$faker->firstName,
		'spouse_middle_name'=>$faker->firstName,
		'spouse_last_name'=>$faker->firstName,
		'spouse_mobile_number'=>$contact,
		'spouse_birthday'=>$faker->date,
		'spouse_age'=>rand(0,9),
		'number_of_dependents'=>rand(0,2),
		'household_size'=>rand(0,2),
		'mothers_maiden_name'=>$faker->firstName,
		'person_1_name'=>$faker->firstName,
		'person_1_contact_number'=>$contact,
		'person_1_whole_address'=>$faker->firstName,
		'person_2_name'=>$faker->firstName,
		'person_2_contact_number'=>$contact,
		'person_2_whole_address'=>$faker->firstName,
		'self_employed'=>rand(0,1),
		'business_type'=>$type,
		'estimated_monthly_income_for_business'=>rand(1000,99999),
		'other_income'=>$faker->firstName,
		'other_income_monthly_estimated_earnings'=>rand(1000,99999),
		'spouse_self_employed'=>rand(0,1),
		'spouse_business_type'=>$type,
		'monthly_income_for_spouse_business'=>rand(1000,99999),
		'spouse_employed'=>rand(0,1),
		'position'=>$faker->firstName,
		'company_name'=>$faker->firstName,
		'spouse_monthly_gross_income_at_work'=>rand(1000,99999),
		'spouse_other_income'=>$faker->firstName,
		'spouse_other_income_monthly_estimated_earnings'=>rand(1000,99999),
		'pension'=>rand(0,1),
		'remittance'=>rand(0,1),
    	'office_id'=> Office::where('level','branch')->inRandomOrder()->first()->id,
		'received'=>rand(0,1),
		
		'loan_officer'=>$lo,
		'created_by'=>1
    ];
});
