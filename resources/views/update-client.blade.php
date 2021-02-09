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
		            	<form action ="{{route('client.update.post',$client->id)}}" method="POST">
		            		@csrf()
		            		<div class="form-wrapper">
		            			<div class="row pb-4">
		            				<div class="col-md-6">
		            					<label for="office_id" class="title">Branch</label>

                                        <?php 
										
                                        $value = $client->office->officeSelectValue();
										
										?>
										
                                        <office-list  default_value="{{$value}}"  style="width:360px" ></office-list>
		            					@error('office_id')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
		            				</div>
		            				<div class="col-md-6">
		            					<label for="loan_officer" class="title">Loan Officer</label>
										
		            					<input type="text" name="loan_officer" id="loan_officer" value="{{ $client->loan_officer}}" class="form-control">
		            					
										@error('loan_officer')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
		            				</div>
		            			</div>
		            			<div class="row">
		            				<div class="col-md-3">
				            			<label class="title" for="first_name">First Name</label>
				            			<input class="form-control" type="text" id="first_name" value="{{ $client->first_name }}" name="first_name">
				            			@error('first_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="middle_name">Middle Name</label>
				            			<input class="form-control" value="{{ $client->middle_name }}" type="text" id="middle_name" name="middle_name">
				            			@error('middle_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="last_name">Last Name</label>
				            			<input class="form-control" type="text" value="{{ $client->last_name}}" id="last_name" name="last_name">
				            			@error('last_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="nickname">Nickname</label>
				            			<input class="form-control" type="text" value="{{ $client->nickname}}" id="nickname" name="nickname">
				            			
				            		</div>
		            			</div>
			            		
			            		<div class="row">
			            			<div class="col-md-12 np">
			            				<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="street_address">Street Address</label>
						            			<input class="form-control" value="{{ $client->street_address}}" type="text" id="street_address" name="street_address">
						            			@error('street_address')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="barangay">Barangay</label>
						            			<input class="form-control" value="{{ $client->barangay}}" type="text" id="barangay" name="barangay">
						            			@error('barangay')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="city">City</label>
						            			<input class="form-control" value="{{ $client->city}}" type="text" id="city" name="city">
						            			@error('city')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
					            		<div class="row">
						            		<div class="form-group col-md-4">
						            			<label class="title" for="province">Province</label>
						            			<input class="form-control" value="{{ $client->province }}" type="text" id="province" name="province">
						            			@error('province')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="form-group col-md-2">
						            			<label class="title" for="zip_code">Zip Code</label>
						            			<input class="form-control" value="{{ $client->zip_code}}" type="number" id="zip_code" name="zip_code">
						            			@error('zip_code')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
											<div class="form-group col-md-2">
													<label class="title" for="years_of_stay">Years of Stay</label>
													<input class="form-control" value="{{ $client->years_of_stay}}" type="number" id="years_of_stay" name="years_of_stay">
													@error('years_of_stay')
														<strong class="invalid-danger">{{ $message }}</strong>
													@enderror
											</div>
											<div class="form-group col-md-4">
												<label for="house" class="title">House</label>
												<select value="{{ $client->house}}" name="house" id="house" class="form-control">
													<option value="">Select Options</option>
													<option value="RENTED">RENTED</option>
													<option value="OWNED">OWNED</option>
												</select>
												@error('house')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
					            		</div>
										<div class="row">
											<div class="col-md-6">
												<label class="title" for="birthday">Birthday:</label>
												<input class="form-control" value="{{ $client->birthday }}" type="date" id="birthday" name="birthday">
												@error('birthday')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
											<div class="col-md-6">
												<label class="title" for="gender">Gender:</label>
												<select name="gender" value="{{ $client->gender }}" id="gender" class="form-control">
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
												<input class="form-control" value="{{ $client->birthplace }}" type="text" id="birthplace" name="birthplace">
												@error('birthplace')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
											<div class="col-md-6">
												<label class="title" for="tin_id">TIN ID:</label>
												<input class="form-control" value="{{ $client->tin_id }}" type="text" id="tin_id" name="tin_id">
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-4">
											<label class="title" for="civil_status">Civil Status:</label>
												<select name="civil_status" value="{{ $client->civil_status}}" id="civil_status" class="form-control" style="max-width: 605px;">
													<option value="">Please Select Options</option>
													<option value="SINGLE">SINGLE</option>
													<option value="SEPARATED">SEPARATED</option>
													<option value="WIDOW">WIDOW</option>
													<option value="MARRIED">MARRIED</option>
													<option value="DIVORCED">DIVORCED</option>
												</select>
												@error('civil_status')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
											<div class="col-md-4">
												<label class="title" for="other_ids">Other ID (ID Number)</label>
												<input class="form-control" type="text" id="other_ids" name="other_ids">
											</div>
											<div class="col-md-4">
												<label class="title" for="education">Education:</label>
												<select name="education" value="{{ $client->education }}" id="education" class="form-control" style="max-width: 610px;">
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

											
										</div>
		
										<div class="row">
											<div class="col-md-6">
												<label class="title" for="mobile_number">Mobile Number:</label>
												<input class="form-control" value="{{ $client->mobile_number }}" type="number" id="mobile_number" name="mobile_number">
												@error('mobile_number')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
											<div class="col-md-6">
												<label class="title" for="facebook_account_link">Facebook Link:</label>
												<input class="form-control" value="{{ $client->facebook_account_link }}" type="text" id="facebook_account_link" name="facebook_account_link">
												@error('facebook_account_link')
													<strong class="invalid-danger">{{ $message }}</strong>
												@enderror
											</div>
										</div>
				            		</div>

									



			            		</div>
								<hr>
			            		<div class="row">
				            		<h1 class="h1 pl-3">Business Information</h1>

				            		<div class="col-md-12 np">
				            			<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_street_address">Business / Farm Street Address</label>
						            			<input class="form-control" value="{{ $client->business_farm_street_address }}" type="text" id="business_farm_street_address" name="business_farm_street_address">
						            			@error('business_farm_street_address')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="business_barangay">Business / Farm Barangay</label>
						            			<input class="form-control" value="{{ $client->business_barangay }}" type="text" id="business_barangay" name="business_barangay">
						            			@error('business_barangay')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-3">
						            			<label class="title" for="business_farm_city">Business / Farm City</label>
						            			<input class="form-control" value="{{ $client->business_farm_city }}" type="text" id="business_farm_city" name="business_farm_city">
						            			@error('business_farm_city')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
					            		<div class="row">
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_province">Business / Farm Province</label>
						            			<input class="form-control" type="text" value="{{ $client->business_farm_province }}" id="business_farm_province" name="business_farm_province">
						            			@error('business_farm_province')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
						            		<div class="col-md-6">
						            			<label class="title" for="business_farm_zip_code">Business / Farm Zip Code</label>
						            			<input class="form-control" value="{{ $client->business_farm_zip_code }}" type="text" id="business_farm_zip_code" name="business_farm_zip_code">
						            			@error('business_farm_zip_code')
												    <strong class="invalid-danger">{{ $message }}</strong>
												@enderror
						            		</div>
					            		</div>
				            		</div>

				            	</div>
				            	<hr>
								<h1 class="h1 pl-3">Other Information</h1>
			            		<div class="row">
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_first_name">Spouse First Name</label>
				            			<input type="text" value="{{ $client->spouse_first_name}}" id="spouse_first_name" name="spouse_first_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_middle_name">Spouse Middle Name</label>
				            			<input type="text" value="{{ $client->spouse_middle_name }}" name="spouse_middle_name" id="spouse_middle_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_last_name">Spouse Last Name</label>
				            			<input type="text" value="{{ $client->spouse_last_name }}" id="spouse_last_name" name="spouse_last_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_mobile_number">Spouse Mobile Number</label>
				            			<input type="number" value="{{ $client->spouse_mobile_number }}" id="spouse_mobile_number" name="spouse_mobile_number" class="form-control">
				            			@error('spouse_mobile_number')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="spouse_birthday">Spouse Birthday</label>
				            			<input type="date" id="spouse_birthday" name="spouse_birthday" value="{{ $client->spouse_birthday }}" style="max-width: 550px;" class="d-inline-block form-control">
										@error('spouse_birthday')
												<strong class="invalid-danger">{{ $message }}</strong>
										@enderror
									</div>
									
				            		<div class="col-md-6">
				            			<label class="title" for="mothers_maiden_name">Mothers Maiden Name:</label>
			            				<input class="form-control" value="{{ $client->mothers_maiden_name }}" type="text" id="mothers_maiden_name" name="mothers_maiden_name">
			            				@error('mothers_maiden_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>
			            		
			            		<div class="row">
			            			<div class="col-md-6">
				            			<label class="title" for="household_size">Household Size:</label>
				            			<input class="form-control" value="{{ $client->household_size }}" type="number" id="household_size" name="household_size">
				            			@error('household_size')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="number_of_dependents">Number of Dependents:</label>
				            			<input class="form-control" value="{{ $client->number_of_dependents }}" type="number" id="number_of_dependents" name="number_of_dependents">
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
				            			<input type="text" value="{{ $client->person_1_name }}" id="person_1_name" name="person_1_name" class="form-control">
				            			@error('person_1_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="person_1_contact_number">Person 1 Contact number</label>
				            			<input type="number" id="person_1_contact_number" name="person_1_contact_number" value="{{ $client->person_1_contact_number }}" class="form-control">
				            			@error('person_1_contact_number')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            		<div class="col-md-5">
				            			<label class="title" for="person_1_whole_address">Person 1 Whole Address</label>
				            			<input type="text" id="person_1_whole_address" value="{{ $client->person_1_whole_address }}" name="person_1_whole_address" class="form-control">
				            			@error('person_1_whole_address')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>

			            		<div class="row">
			            			<div class="col-md-4">
				            			<label class="title" for="person_2_name">Person 2 Full Name</label>
				            			<input type="text" value="{{ $client->person_2_name }}" name="person_2_name" id="person_2_name" class="form-control">
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="person_2_contact_number">Person 2 Contact number</label>
				            			<input type="number" name="person_2_contact_number" id="person_2_contact_number" value="{{ $client->person_2_contact_number }}" class="form-control">
				            		</div>
				            		<div class="col-md-5">
				            			<label class="title" for="person_2_whole_address">Person 2 Whole Address</label>
				            			<input type="text" name="person_2_whole_address" value="{{ $client->person_2_whole_address }}" id="person_2_whole_address" class="form-control">
				            		</div>
			            		</div>

			            		
			            	</div>

			            	<div class="form-wrapper">
			            		<div class="row">
				            		<div class="col-md-2 form-group">
				            			<input type="checkbox" class="d-inline-block" value="1" {{$client->self_employed == 1 ? 'checked': ''}} id="self_employed" name="self_employed" >
				            			<label class="d-inline-block" for="self_employed">Self Employed</label>
				            			@error('self_employed')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>

				            		<div class="col-md-4">
				            			<label for="business_type">Business type</label>
				            			<select name="business_type" id="business_type"  class="form-control">
				            				<option value="">Please Select Option</option>
				            				<option value="TRADING / MERCHANDISING">TRADING / MERCHANDISING</option>
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
				            			<input type="number" value="{{ $client->estimated_monthly_income_for_business }}" class="form-control" name="estimated_monthly_income_for_business" id="estimated_monthly_income_for_business">
				            			@error('estimated_monthly_income_for_business')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>

			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="other_income">Other Income</label>
				            			<input type="text" class="form-control" value="{{ $client->other_income }}" name="other_income" id="other_income">
				            		</div>
				            		<div class="col-md-6">
				            			<label class="title" for="other_income_monthly_estimated_earnings">Other Income Estimated Earnings</label>
				            			<input type="text" class="form-control" name="other_income_monthly_estimated_earnings" id="other_income_monthly_estimated_earnings"
				            			value="{{ $client->other_income_monthly_estimated_earnings }}">
				            		</div>
			            		</div>
		            		</div>

			            	<div class="form-wrapper">
			            		<h1 class="h1 ml-3 mt-5">Spouse Employment Information</h1>
			            		<div class="row">
				            		<div class="col-md-2 form-group">
				            			
                                        <input type="checkbox" class="d-inline-block"  value ="1" {{$client->spouse_self_employed == 1 ? 'checked' : ''}} id="spouse_self_employed" name="spouse_self_employed" >
					            		<label class="d-inline-block" for="spouse_self_employed">Spouse Self Employed</label>
				            		</div>


				            		<div class="col-md-4">
				            			<label for="spouse_business_type">Spouse Business Type</label>
				            			<select value="{{ $client->spouse_business_type }}"
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

				            		<div class="col-md-6 form-group">
				            			<label class="title" for="monthly_income_for_spouse_business">Monthly Income for Spouse Business</label>
				            			<input value="{{ $client->monthly_income_for_spouse_business }}" type="number" class="form-control" id="monthly_income_for_spouse_business" name="monthly_income_for_spouse_business">
				            			@error('monthly_income_for_spouse_business')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
			            		</div>


			            		<div class="row">
				            		<div class="col-md-2">
				            			<input type="checkbox" class="d-inline-block" id="spouse_employed" name="spouse_employed" value="1" {{$client->spouse_employed ==1 ? 'checked' : ''}}>
					            		<label class="d-inline-block" for="spouse_employed">Spouse Employed</label>
				            		</div>



				            		<div class="col-md-3">
				            			<label class="title"  for="position">Spouse Position</label>
				            			<input type="text" class="form-control" name="position" id="position" value="{{$client->position}}">
										@error('position')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror				            			
				            		</div>

				            		<div class="col-md-4">
				            			<label class="title" for="company_name">Spouse Company Name</label>
				            			<input type="text" value="{{ $client->company_name }}" class="form-control" name="company_name" id="company_name">
				            			@error('company_name')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror	
				            		</div>
				            		<div class="col-md-3">
				            			<label class="title" for="spouse_monthly_gross_income_at_work">Spouse Monthly Gross Income</label>
				            			<input value="{{ $client->spouse_monthly_gross_income_at_work }}" type="text" class="form-control" name="spouse_monthly_gross_income_at_work" id="spouse_monthly_gross_income_at_work">
				            			@error('spouse_monthly_gross_income_at_work')
										    <strong class="invalid-danger">{{ $message }}</strong>
										@enderror
				            		</div>
				            	</div>
			            		
			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="spouse_other_income">Spouse Other Income</label>
				            			<input type="text" value="{{ $client->spouse_other_income }}" class="form-control" name="spouse_other_income" id="spouse_other_income">
				            		</div>

				            		<div class="col-md-6">
				            			<label class="title" for="spouse_other_income_monthly_estimated_earnings">Spouse Other Income Monthly Estimated Earnings</label>
				            			<input type="text" class="form-control" name="spouse_other_income_monthly_estimated_earnings" id="spouse_other_income_monthly_estimated_earnings"
				            			value="{{ $client->spouse_other_income_monthly_estimated_earnings }}">
				            		</div>
			            		</div>
			            		<div class="row">
				            		<div class="col-md-6">
				            			<label class="title" for="pension">Pension</label>
				            			<input type="number" class="form-control" name="pension" id="pension" 
				            			value="{{ $client->pension }}">
				            		</div>

				            		<div class="col-md-6">
				            			<label class="title" for="remittance">Remittance</label>
				            			<input type="number" class="form-control" name="remittance" id="remittance"
				            			value="{{ $client->remittance }}">
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
           
            $('#house').val("{{$client->house}}")
            $('#gender').val("{{$client->gender}}")
            $('#civil_status').val("{{$client->civil_status}}")
            $('#education').val("{{$client->education}}")
            $('#business_type').val("{{$client->business_type}}")
            $('#spouse_business_type').val("{{$client->spouse_business_type}}")

            });
        
    </script>
@endsection