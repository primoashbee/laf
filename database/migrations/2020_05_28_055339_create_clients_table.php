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
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nickname')->nullable();
            $table->string('street_address');
            $table->string('barangay');
            $table->string('city');
            $table->string('province');
            $table->string('zip_code');
            $table->string('years_of_stay');
            $table->string('business_farm_street_address');
            $table->string('business_barangay');
            $table->string('business_farm_city');
            $table->string('business_farm_province');
            $table->string('business_farm_zip_code');
            $table->string('house');
            $table->date('birthday');
            $table->string('gender');
            $table->string('birthplace');
            $table->string('tin_id')->nullable();
            $table->string('civil_status');
            $table->string('other_id_type')->nullable();
            $table->string('other_id_number')->nullable();
            $table->string('education');
            $table->string('facebook_account_link')->nullable();
            $table->string('mobile_number');
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_mobile_number')->nullable();
            $table->string('spouse_birthday')->nullable();
            $table->integer('spouse_age')->nullable();
            $table->string('number_of_dependents');
            $table->string('household_size');
            $table->string('mothers_maiden_name');
            $table->string('person_1_name');
            $table->string('person_1_contact_number');
            $table->string('person_1_whole_address');
            
            $table->string('person_2_name')->nullable();
            $table->string('person_2_contact_number')->nullable();
            $table->string('person_2_whole_address')->nullable();

            $table->boolean('self_employed')->nullable();
            $table->string('business_type');
            $table->integer('estimated_monthly_income_for_business');
            $table->string('other_income')->nullable();
            $table->integer('other_income_monthly_estimated_earnings')->nullable();
            $table->boolean('spouse_self_employed')->nullable();
            $table->string('spouse_business_type')->nullable();
            $table->string('monthly_income_for_spouse_business')->nullable();
            $table->boolean('spouse_employed')->nullable();
            $table->string('position')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('spouse_monthly_gross_income_at_work')->nullable();
            $table->string('spouse_other_income')->nullable();
            $table->integer('spouse_other_income_monthly_estimated_earnings')->nullable();
            $table->integer('pension')->nullable();
            $table->integer('remittance')->nullable();
            $table->unsignedInteger('office_id');
            $table->string('loan_officer');
            $table->boolean('received')->default(false);
            $table->unsignedInteger('created_by');
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
