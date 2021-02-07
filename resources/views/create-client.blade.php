@extends('layouts.app')

@section('content')

<div class="container">
	 <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            	<div class="card-body">
	            	<div class="form-container">

						@if(session()->has('message'))
							
	                        <div class="alert alert-success">
	                            <h1>{{ session()->get('message') }}</h1>
	                        </div>
                    	@endif
	            		<div>
	            			
						</div>
						
	            		<h1 class="h1 ml-3 mt-3">Personal Information</h1>
		            	<form method="POST">
		            		@csrf()
		            		<div class="form-wrapper">
		            			<div class="row pb-4">
		            				<div class="col-md-6">
		            					<label for="office_id" class="title">Branch</label>
		            					<select name="office_id" id="office_id" value="{{ old('office_id') }}" class="form-control">
		            						@if(auth()->user()->is_admin)
		                                    <option> Please Select </option>
			                                    @foreach ($offices as $office)
			                                    <option value="{{$office->id}}">{{ $office->name }}</option>
			                                    @endforeach
		                                    @endif
			                                    
			                                <option value="{{ Auth::user()->office->first()->id }}">{{ Auth::user()->office->first()->name }}</option>
		            					</select>
		            					@error('office_id')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
		            				</div>
		            				<div class="col-md-6">
		            					<label for="loan_officer" class="title">Loan Officer</label>
		            					<input type="text" name="loan_officer" id="loan_officer" value="{{ old('loan_officer') }}" class="form-control">
		            					@error('loan_officer')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
		            				</div>
		            			</div>
		            			<div class="row">
		            				<div class="col-md-3">
				            			<label class="title" for="first_name">First Name</label>
				            			<input class="form-control" type="text" id="first_name" value="{{ old('first_name') }}" name="first_name">
				            			@error('first_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="middle_name">Middle Name</label>
				            			<input class="form-control" value="{{ old('middle_name') }}" type="text" id="middle_name" name="middle_name">
				            			@error('middle_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="last_name">Last Name</label>
				            			<input class="form-control" type="text" value="{{ old('last_name') }}" id="last_name" name="last_name">
				            			@error('last_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="nickname">Nickname</label>
				            			<input class="form-control" type="text" value="{{ old('nickname') }}" id="nickname" name="nickname">
				            			
				            		</div>
		            			</div>
			            		
			            		<div class="row">
			            			<div class="col-md-10 np">
			            				<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="street_address">Street Address</label>
						            			<input class="form-control" value="{{ old('street_address') }}" type="text" id="street_address" name="street_address">
						            			@error('street_address')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="barangay">Barangay</label>
						            			<input class="form-control" value="{{ old('barangay') }}" type="text" id="barangay" name="barangay">
						            			@error('barangay')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="city">City</label>
						            			<input class="form-control" value="{{ old('city') }}" type="text" id="city" name="city">
						            			@error('city')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
					            		<div class="row">
						            		<div class="form-group col-md-6">
						            			<label class="title" for="province">Province</label>
						            			<input class="form-control" value="{{ old('province') }}" type="text" id="province" name="province">
						            			@error('province')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="form-group col-md-6">
						            			<label class="title" for="zip_code">Zip Code</label>
						            			<input class="form-control" value="{{ old('zip_code') }}" type="number" id="zip_code" name="zip_code">
						            			@error('zip_code')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
				            		</div>

				            		<div class="col-md-2">
				            			<div class="row form-group">
					            			<label class="title" for="years_of_stay">Years of Stay</label>
					            			<input class="form-control" value="{{ old('years_of_stay') }}" type="number" id="years_of_stay" name="years_of_stay">
					            			@error('years_of_stay')
											    <strong class="invalid-danger">{{ $message }}</strong>
											@enderror
					            		</div>
				            		</div>
			            		</div>

			            		<div class="row">
				            		<h1 class="h1 pl-3">Business Information</h1>

				            		<div class="col-md-10 np">
				            			<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_street_address">Business / Farm Street Address</label>
						            			<input class="form-control" value="{{ old('business_farm_street_address') }}" type="text" id="business_farm_street_address" name="business_farm_street_address">
						            			@error('business_farm_street_address')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="business_barangay">Business / Farm Barangay</label>
						            			<input class="form-control" value="{{ old('business_barangay') }}" type="text" id="business_barangay" name="business_barangay">
						            			@error('business_barangay')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="business_farm_city">Business / Farm City</label>
						            			<input class="form-control" value="{{ old('business_farm_city') }}" type="text" id="business_farm_city" name="business_farm_city">
						            			@error('business_farm_city')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
					            		<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_province">Business / Farm Province</label>
						            			<input class="form-control" type="text" value="{{ old('business_farm_province') }}" id="business_farm_province" name="business_farm_province">
						            			@error('business_farm_province')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_zip_code">Business / Farm Zip Code</label>
						            			<input class="form-control" value="{{ old('business_farm_zip_code') }}" type="text" id="business_farm_zip_code" name="business_farm_zip_code">
						            			@error('business_farm_zip_code')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
				            		</div>
				            		<div class="col-md-2">
				            			<label for="house">House</label>
				            			<select value="{{ old('house') }}" name="house" id="house" class="form-control">
				            				<option value="">Select Options</option>
				            				<option value="RENTED">RENTED</option>
				            				<option value="OWNED">OWNED</option>
				            			</select>
				            			@error('house')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
				            	<hr>
				            	<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="birthday">Birthday:</label>
				            			<input class="form-control" value="{{ old('birthday') }}" type="date" id="birthday" name="birthday">
				            			@error('birthday')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="gender">Gender:</label>
				            			<select name="gender" value="{{ old('gender') }}" id="gender" class="form-control">
				            				<option value="">Please Select Options</option>
				            				<option value="MALE">MALE</option>
				            				<option value="FEMALE">FEMALE</option>
				            			</select>
				            			@error('gender')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="birthplace">Birthplace:</label>
				            			<input class="form-control" value="{{ old('birthplace') }}" type="text" id="birthplace" name="birthplace">
				            			@error('birthplace')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="tin_id">TIN ID:</label>
				            			<input class="form-control" value="{{ old('tin_id') }}" type="number" id="tin_id" name="tin_id">
				            		</div>
			            		</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
			            			<label class="title" for="civil_status">Civil Status:</label>
				            			<select name="civil_status" value="{{ old('civil_status') }}" id="civil_status" class="form-control" style="max-width: 605px;">
				            				<option value="">Please Select Options</option>
				            				<option value="SINGLE">SINGLE</option>
				            				<option value="SEPARATED">SEPARATED</option>
				            				<option value="WIDOW">WIDOW</option>
				            				<option value="MARRIED">MARRIED</option>
				            			</select>
				            			@error('civil_status')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="other_ids">Other ID's</label>
				            			<input class="form-control" type="number" id="other_ids  `" name="other_ids">
				            		</div>
			            		</div>
			            		
			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="education">Education:</label>
				            			<select name="education" value="{{ old('education') }}" id="education" class="form-control" style="max-width: 610px;">
				            				<option value="">Please Select Options</option>
				            				<option value="POST GRADUATE">POST GRADUATE</option>
				            				<option value="COLLEGE">COLLEGE</option>
				            				<option value="HIGH SCHOOL">HIGH SCHOOL</option>
				            				<option value="ELEMENTARY">ELEMENTARY</option>
				            				<option value="OTHERS">OTHERS</option>
				            			</select>
				            			@error('education')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		
			            		</div>

			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="mobile_number">Mobile Number:</label>
				            			<input class="form-control" value="{{ old('mobile_number') }}" type="number" id="mobile_number" name="mobile_number">
				            			@error('mobile_number')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="facebook_account_link">Facebook Link:</label>
				            			<input class="form-control" value="{{ old('facebook_account_link') }}" type="text" id="facebook_account_link" name="facebook_account_link">
				            			@error('facebook_account_link')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>

			            		<div class="row">
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_first_name">Spouse First Name</label>
				            			<input type="text" value="{{ old('spouse_first_name') }}" id="spouse_first_name" name="spouse_first_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_middle_name">Spouse Middle Name</label>
				            			<input type="text" value="{{ old('spouse_middle_name') }}" name="spouse_middle_name" id="spouse_middle_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_last_name">Spouse Last Name</label>
				            			<input type="text" value="{{ old('spouse_last_name') }}" id="spouse_last_name" name="spouse_last_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_mobile_number">Spouse Mobile Number</label>
				            			<input type="number" value="{{ old('spouse_mobile_number') }}" id="spouse_mobile_number" name="spouse_mobile_number" class="form-control">
				            			@error('spouse_mobile_number')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="spouse_birthday">Spouse Birthday</label>
				            			<input type="date" id="spouse_birthday" name="spouse_birthday" value="{{ old('spouse_birthday') }}" style="max-width: 550px;" class="d-inline-block form-control">
										@error('spouse_birthday')
												<strong class="invalid-danger">{{ $message }}</strong>
										@enderror
									</div>
									
				            		<div class="col-md-6">
				            			<label class="title" for="mothers_maiden_name">Mothers Maiden Name:</label>
			            				<input class="form-control" value="{{ old('mothers_maiden_name') }}" type="text" id="mothers_maiden_name" name="mothers_maiden_name">
			            				@error('mothers_maiden_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="household_size">Household Size:</label>
				            			<input class="form-control" value="{{ old('household_size') }}" type="number" id="household_size" name="household_size">
				            			@error('household_size')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="number_of_dependents">Number of Dependents:</label>
				            			<input class="form-control" value="{{ old('number_of_dependents') }}" type="number" id="number_of_dependents" name="number_of_dependents">
				            			@error('number_of_dependents')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>
			            	</div>

			            	<div class="form-wrapper">
			            		<h1 class="h1 ml-3 mt-5">Personal Reference</h1>

			            		<div class="row">
			            			<div class="col-md-4">
				            			<label class="title" for="person_1_name">Person 1 Full Name</label>
				            			<input type="text" value="{{ old('person_1_name') }}" id="person_1_name" name="person_1_name" class="form-control">
				            			@error('person_1_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="person_1_contact_number">Person 1 Contact number</label>
				            			<input type="number" id="person_1_contact_number" name="person_1_contact_number" value="{{ old('person_1_contact_number') }}" class="form-control">
				            			@error('person_1_contact_number')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-5">
				            			<label class="title" for="person_1_whole_address">Person 1 Whole Address</label>
				            			<input type="text" id="person_1_whole_address" value="{{ old('person_1_whole_address') }}" name="person_1_whole_address" class="form-control">
				            			@error('person_1_whole_address')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>

			            		<div class="row">
			            			<div class="col-md-4">
				            			<label class="title" for="person_2_name">Person 2 Full Name</label>
				            			<input type="text" value="{{ old('person_2_name') }}" name="person_2_name" id="person_2_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="person_2_contact_number">Person 2 Contact number</label>
				            			<input type="number" name="person_2_contact_number" id="person_2_contact_number" value="{{ old('person_2_contact_number') }}" class="form-control">
				            		</div>
				            		<div class="col-md-5">
				            			<label class="title" for="person_2_whole_address">Person 2 Whole Address</label>
				            			<input type="text" name="person_2_whole_address" value="{{ old('person_2_whole_address') }}" id="person_2_whole_address" class="form-control">
				            		</div>
			            		</div>

			            		
			            	</div>

			            	<div class="form-wrapper">
			            		<div class="row">
				            		<div class="col-md-4 form-group">
				            			<input type="checkbox" class="d-inline-block" value="1" id="self_employed" name="self_employed" value="1"
				            			{{ old('self_employed') == '1' ? 'checked' : '' }}>
				            			<label class="d-inline-block" for="self_employed">Self Employed</label>
				            			@error('self_employed')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>

				            		<div class="col-md-4">
				            			<label for="business_type">Business type</label>
				            			<select name="business_type" id="business_type" value="{{ old('business_type') }}" class="form-control">
				            				<option value="">Please Select Option</option>
				            				<option value="TRADING/MERCHANDISING">TRADING/MERCHANDISING</option>
				            				<option value="MANUFACTURING">MANUFACTURING</option>
				            				<option value="SERVICE">SERVICE</option>
				            				<option value="AGRICULTURE">AGRICULTURE</option>
				            			</select>
				            			@error('business_type')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>

				            		<div class="col-md-4 form-group">
				            			<label class="title" for="estimated_monthly_income_for_business">Estimated Monthly Income for Business</label>
				            			<input type="number" value="{{ old('estimated_monthly_income_for_business') }}" class="form-control" name="estimated_monthly_income_for_business" id="estimated_monthly_income_for_business">
				            			@error('estimated_monthly_income_for_business')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>

			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="other_income">Other Income</label>
				            			<input type="text" class="form-control" value="{{ old('other_income') }}" name="other_income" id="other_income">
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="other_income_monthly_estimated_earnings">Other Income Estimated Earnings</label>
				            			<input type="text" class="form-control" name="other_income_monthly_estimated_earnings" id="other_income_monthly_estimated_earnings"
				            			value="{{ old('other_income_monthly_estimated_earnings') }}">
				            		</div>
			            		</div>
		            		</div>

			            	<div class="form-wrapper">
			            		<h1 class="h1 ml-3 mt-5">Spouse Employment Information</h1>
			            		<div class="row">
				            		<div class="col-md-4 form-group">
				            			<input type="checkbox" class="d-inline-block" id="spouse_self_employed" name="spouse_self_employed" value="1"
				            			{{ old('spouse_self_employed') == '1' ? 'checked' : '' }}>
					            			<label class="d-inline-block" for="spouse_self_employed">Spouse Self Employed</label>
				            		</div>


				            		<div class="col-md-4">
				            			<label for="spouse_business_type">Spouse Business Type</label>
				            			<select value="{{ old('spouse_business_type') }}"
				            			 name="spouse_business_type" id="spouse_business_type" class="form-control">
				            				<option value="">Please Select Option</option>
				            				<option value="TRADING / MERCHANDISING">TRADING / MERCHANDISING</option>
				            				<option value="MANUFACTURING">MANUFACTURING</option>
				            				<option value="SERVICE">SERVICE</option>
				            				<option value="AGRICULTURE">AGRICULTURE</option>
				            			</select>
				            			@error('spouse_business_type')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>

				            		<div class="col-md-4 form-group">
				            			<label class="title" for="monthly_income_for_spouse_business">Monthly Income for Spouse Business</label>
				            			<input value="{{ old('monthly_income_for_spouse_business') }}" type="number" class="form-control" id="monthly_income_for_spouse_business" name="monthly_income_for_spouse_business">
				            			@error('monthly_income_for_spouse_business')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>


			            		<div class="row">
				            		<div class="col-md-3">
				            			<input type="checkbox" class="d-inline-block" id="spouse_employed" name="spouse_employed" value="1" {{ old('spouse_employed') == '1' ? 'checked' : '' }}>
					            		<label class="d-inline-block" for="spouse_employed">Spouse Employed</label>
				            		</div>



				            		<div class="col-md-3">
				            			<label class="title"  for="position">Spouse Position</label>
				            			<input type="text" class="form-control" name="position" id="position">
										@error('position')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror				            			
				            		</div>

				            		<div class="col-md-3">
				            			<label class="title" for="company_name">Spouse Company Name</label>
				            			<input type="text" value="{{ old('company_name') }}" class="form-control" name="company_name" id="company_name">
				            			@error('company_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_monthly_gross_income_at_work">Spouse Monthly Gross Income</label>
				            			<input value="{{ old('spouse_monthly_gross_income_at_work') }}" type="text" class="form-control" name="spouse_monthly_gross_income_at_work" id="spouse_monthly_gross_income_at_work">
				            			@error('spouse_monthly_gross_income_at_work')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
			            		
			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="spouse_other_income">Spouse Other Income</label>
				            			<input type="text" value="{{ old('spouse_other_income') }}" class="form-control" name="spouse_other_income" id="spouse_other_income">
				            		</div>

				            		<div class="col-md-6">
				            			<label class="title" for="spouse_other_income_monthly_estimated_earnings">Spouse Other Income Monthly Estimated Earnings</label>
				            			<input type="text" class="form-control" name="spouse_other_income_monthly_estimated_earnings" id="spouse_other_income_monthly_estimated_earnings"
				            			value="{{ old('spouse_other_income_monthly_estimated_earnings') }}">
				            		</div>
			            		</div>
			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="pension">Pension</label>
				            			<input type="number" class="form-control" name="pension" id="pension" 
				            			value="{{ old('pension') }}">
				            		</div>

				            		<div class="col-md-6">
				            			<label class="title" for="remittance">Remittance</label>
				            			<input type="number" class="form-control" name="remittance" id="remittance"
				            			value="{{ old('remittance') }}">
				            		</div>
			            		</div>

			            		<input type="submit" class="btn btn-success btn-lg mt-4 ml-3">
			            	</div>
		            	</form>
	            	</div>
            	</div>
            </div>
        </div>
    </div>        	
</div>


@endsection


@section('scripts')
<script defer>
    window.addEventListener('DOMContentLoaded', function() {
           
                
             @if(old('office_id') != '')
				 $('#office_id').val("{{old('office_id')}}")
			 @endif

             @if(old('house') != '')
				 $('#house').val("{{old('house')}}")
			@endif
             @if(old('gender') != '')
				 $('#gender').val("{{old('gender')}}")
			@endif
             @if(old('civil_status') != '')
				 $('#civil_status').val("{{old('civil_status')}}")
			@endif
             @if(old('education') != '')
				 $('#education').val("{{old('education')}}")
			@endif
             @if(old('business_type') != '')
				 $('#business_type').val("{{old('business_type')}}")
			@endif
             @if(old('spouse_business_type') != '')
				 $('#spouse_business_type').val("{{old('spouse_business_type')}}")
			@endif

            });
        
    </script>
@endsection