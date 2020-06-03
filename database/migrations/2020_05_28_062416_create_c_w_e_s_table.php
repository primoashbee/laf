<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCWESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cwe', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->string('loan_type')->nullable();
            $table->string('branch')->nullable();
            $table->string('cluster')->nullable();
            $table->string('loan_purpose')->nullable();
            $table->string('type_of_loan')->nullable();
            $table->string('loan_cycle')->nullable();
            $table->string('date_of_membership')->nullable();
            $table->string('prefered_loan_amount')->nullable();
            $table->string('terms_in_months')->nullable();  
            $table->string('business_1_business_income')->nullable();
            $table->string('business_2_business_income')->nullable();
            $table->string('business_3_business_income')->nullable();

            $table->string('business_1_labor_expense')->nullable();
            $table->string('business_1_rent_expense')->nullable();
            $table->string('business_1_utility_expense')->nullable();
            $table->string('business_1_transportation_expense')->nullable();
            $table->string('business_1_other_expense')->nullable();

            $table->string('business_2_labor_expense')->nullable();
            $table->string('business_2_rent_expense')->nullable();
            $table->string('business_2_utility_expense')->nullable();
            $table->string('business_2_transportation_expense')->nullable();
            $table->string('business_2_other_expense')->nullable();

            $table->string('business_3_labor_expense')->nullable();
            $table->string('business_3_rent_expense')->nullable();
            $table->string('business_3_utility_expense')->nullable();
            $table->string('business_3_transportation_expense')->nullable();
            $table->string('business_3_other_expense')->nullable();

            $table->string('mfi_1')->nullable();
            $table->string('mfi_1_loan_amount')->nullable();
            $table->string('mfi_1_number_of_weeks_installment')->nullable();
            $table->string('mfi_2')->nullable();
            $table->string('mfi_2_loan_amount')->nullable();
            $table->string('mfi_2_number_of_weeks_installment')->nullable();
            $table->string('appliances_equipment')->nullable();
            $table->string('appliances_equipment_loan_amount')->nullable();
            $table->string('appliances_equipment_monthly_installment')->nullable();
            $table->string('salaries_wages_net')->nullable();
            $table->string('remittances')->nullable();
            $table->string('hh_other_income')->nullable();
            $table->string('food')->nullable();
            $table->string('hh_education')->nullable();
            $table->string('transportation')->nullable();
            $table->string('house_rent')->nullable();
            $table->string('clothing')->nullable();
            $table->string('water_bill')->nullable();
            $table->string('electricity_bill')->nullable();
            $table->string('others')->nullable();
            $table->string('character')->nullable();
            $table->string('capacity')->nullable();
            $table->string('capital')->nullable();
            $table->string('collateral')->nullable();
            $table->string('condition')->nullable();
            $table->string('response')->nullable();
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
        Schema::dropIfExists('c_w_e_s');
    }
}
