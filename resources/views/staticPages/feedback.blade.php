@extends('layouts.app')
@section('content')
<div class="container">
	<div class="breadcrumb">
		<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
		<span class="active" tabindex="0">Ask the Experts</span> 
	</div>

	
	<div class="static-content">
		<div class="myaccount-main mt-4">
			<div class="login-page-main">
				<div class="login-detail">
					<div class="login-detail-inner">
						<div class="text-center mb-5">
							<h1 class="sub-hd1 red-color" tabindex="0">Ask the Experts!</h1>
							<p>Ask the Experts at Carpet Express for reliable advice on flooring products and installation. Let our experience help you choose the best flooring products to fit your individual needs or answer any questions pertaining to carpet, wood, resilient, and other types of flooring.</p>
						</div>
						<div class="text-end mb-3"><span class="red-color">*</span>Required Fields</div>
						<form class="row gy-3 needs-validation" action="{{ route('feedback') }}" method="post" name="FeedBackForm" id="FeedBackForm" novalidate="novalidate">
							@csrf
							@if(Session::has('error_msg'))
							<div class="col-md-12">
								<div class="alert alert-danger alert-dismissible fade show" role="alert">  
									{!! Session::get('error_msg') !!}									
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							</div>
							@elseif(Session::has('success_msg'))
							<div class="col-md-12">
								<div class="alert alert-success alert-dismissible fade show" role="alert">  
									{!! Session::get('success_msg') !!}									
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							</div>
							@endif
							<div class="col-md-6 error-selector">
								<label for="first-name" class="form-label">First name <span class="red-color">*</span></label>
								<input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required="">
								@error('first_name')
								<div class="invalid-feedback">{{ $message}}</div>					
								@enderror
							</div>
							<div class="col-md-6 error-selector">
								<label for="first-name" class="form-label">Last name <span class="red-color">*</span></label>
								<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" required="">
								@error('last_name')
								<div class="invalid-feedback">{{ $message}}</div>					
								@enderror
							</div>
							<div class="col-md-12 error-selector">
								<label for="address" class="form-label">Street Address</label>
								<textarea class="form-control" id="street_address" name="street_address" rows="4" placeholder="Enter Street Address" spellcheck="false"></textarea>
							</div>
							<div class="col-md-6 error-selector">
								<label for="city" class="form-label">City</label>
								<input type="text" class="form-control" id="city" name="city" placeholder="Enter City Name">					
							</div>
							<div class="col-md-6 error-selector">
								<label for="country" class="form-label">Country </label>								
								<select class="form-select" id="country" name="country">
									@foreach($CountryArr as $countryCode => $countryName)
										<option value="{{ $countryCode }}">{{ $countryName }}</option>
									@endforeach																					
								</select>
							</div>
							<div class="col-md-6 error-selector">
								<label for="state" class="form-label">State </label>
								<select class="form-select" id="state" name="state">
									@foreach($StateArr as $stateCode => $stateName)
										<option value="{{ $stateCode }}">{{ $stateName }}</option>
									@endforeach	
								</select>
								<input type="text" class="form-control displaynone" id="otherState" name="otherState" placeholder="Enter State Name">		
							</div>
							<div class="col-md-6 error-selector">
								<label for="zip" class="form-label">Zip </label>
								<input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code">					
							</div>
							<div class="col-md-6 error-selector">
								<label for="email" class="form-label">Email <span class="red-color">*</span></label>
								<div class="input-group has-validation">
									<input type="email" class="form-control" id="email" name="email" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required="">
									<span class="input-group-text" id="inputGroupPrepend">@</span>									
								</div>
								@error('email')
								<div class="invalid-feedback">{{ $message}}</div>					
								@enderror
							</div>
							<div class="col-md-6 error-selector">
								<label for="number" class="form-label">Phone Number </label>
								<div class="input-group has-validation">
									<input type="number" class="form-control phone" id="phone_number" name="phone_number" aria-describedby="inputGroupPrepend" >
								</div>
							</div>
							<div class="col-md-12 error-selector">
								<label for="address1" class="form-label">Leave your comments/questions here <span class="red-color">*</span></label>
								<textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Enter Street Address" required="" spellcheck="false"></textarea>
								@error('comments')
								<div class="invalid-feedback">{{ $message}}</div>					
								@enderror
							</div>
							<div class="col-md-12 error-selector">						
								<div class="input-group has-validation">
									@include('googlecaptcha')
								</div>
								@error('gtext')
								<div class="invalid-feedback">{{ $message}}</div>					
								@enderror
							</div>
							<div class="col-md-12 mt-4">
								<div class="row align-items-center">
									<div class="col-md-6 col-sm-7 mb-3 mb-sm-0">
										<button class="btn" type="submit">Submit</button>
										<button class="btn btn-outline" type="reset">Reset</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection