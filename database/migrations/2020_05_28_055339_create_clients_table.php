<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('street_address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('years_of_stay')->nullable();
            $table->string('business_farm_street_address')->nullable();
            $table->string('business_barangay')->nullable();
            $table->string('business_farm_city')->nullable();
            $table->string('business_farm_province')->nullable();
            $table->string('business_farm_zip_code')->nullable();
            $table->string('house')->nullable();
            $table->string('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('tin_id')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('other_ids')->nullable();
            $table->string('education')->nullable();
            $table->string('facebook_account_link')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_mobile_number')->nullable();
            $table->string('spouse_birthday')->nullable();
            $table->string('spouse_age')->nullable();
            $table->string('number_of_dependents')->nullable();
            $table->string('household_size')->nullable();
            $table->string('mothers_maiden_name')->nullable();
            $table->string('person_1_name')->nullable();
            $table->string('person_1_contact_number')->nullable();
            $table->string('person_1_whole_address')->nullable();
            
            $table->string('person_2_name')->nullable();
            $table->string('person_2_contact_number')->nullable();
            $table->string('person_2_whole_address')->nullable();

            $table->string('self_employed')->nullable();
            $table->string('business_type')->nullable();
            $table->string('estimated_monthly_income_for_business')->nullable();
            $table->string('other_income')->nullable();
            $table->string('other_income_monthly_estimated_earnings')->nullable();
            $table->string('spouse_self_employed')->nullable();
            $table->string('spouse_business_type')->nullable();
            $table->string('monthly_income_for_spouse_business')->nullable();
            $table->string('spouse_employed')->nullable();
            $table->string('position')->nullable();
            $table->string('company_name')->nullable();
            $table->string('spouse_monthly_gross_income_at_work')->nullable();
            $table->string('spouse_other_income')->nullable();
            $table->string('spouse_other_income_monthly_estimated_earnings')->nullable();
            $table->string('pension')->nullable();
            $table->string('remittance')->nullable();
     
            


            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
