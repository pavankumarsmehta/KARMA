@php
	$countrycombo 		= displaycountry($Userdata[0]->country, $countryArray);	
	$statecombo 		= displaystate($Userdata[0]->state, $stateArray);	

	$state = $Userdata[0]->state;
	$otherstate = '';
	if($Userdata[0]->country != "US")
	{
		$otherstate = $state;
		$state = "";
	}

@endphp
@extends('layouts.app')
@section('content')
<div class="container myact">
	@include('myaccount.breadcrumbs')
	<h1 class="hidden-md-up h2" tabindex="0">Edit Profile</h1>
	@include('myaccount.myaccountmenu')	
	<div class="container-870">
		<h1 class="pb-3 hidden-sm-down h2" tabindex="0">Edit Profile</h1>
		<form class="needs-validation" id="frmEditProfile" name="frmEditProfile" novalidate="">
			<input type="hidden" name="action" value="update"/>
			@if (Session::has('success'))
				<x-message :attr="[
							'classname' => 'frmsuccess', 
							'message' => Session::get('success')]"
				/>
			@endif
			@csrf
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="first-name" class="form-label">First name <span class="red-color">*</span></label>
					<div class="has-validation">
						<input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name')?old('first_name'):$Userdata[0]->first_name}}" aria-describedby="inputGroupPrepend" placeholder="Enter Your First Name" required>
						@error('first_name')
							<div class="invalid-feedback">{{$message}}</div>
						@enderror
					</div>
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="last-name" class="form-label">Last name <span class="red-color">*</span></label>
					<div class="has-validation">
						<input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name')?old('last_name'):$Userdata[0]->last_name}}" placeholder="Last name" required>
					</div>
					@error('last_name')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="email" class="form-label">Email <span class="red-color">*</span></label>
					<div class="input-group has-validation">
							<input type="email" class="form-control" id="email" name="email" value="{{ old('email')?old('email'):$Userdata[0]->email}}" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
							<span class="input-group-text" id="inputGroupPrepend">@</span>
					</div>
					@error('email')
							<div class="invalid-feedback">{{$message}}</div>
						@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="phone" class="form-label">Phone Number <span class="red-color">*</span></label>
					<input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone')?old('phone'):$Userdata[0]->phone}}" placeholder="+00 000 000 00 00" required>
					@error('phone')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="address1" class="form-label">Street Address <span class="red-color">*</span></label>
					<textarea class="form-control" name="address1" id="address1" rows="4" placeholder="Enter Street Address" required spellcheck="false">{{ old('address1')?old('address1'):$Userdata[0]->address1}}</textarea>
					@error('address1')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="address1" class="form-label">Street Address <span class="red-color">*</span></label>
					<textarea class="form-control" id="address2" name="address2"  rows="4" placeholder="Enter Street Address" required spellcheck="false">{{ old('address2')?old('address2'):$Userdata[0]->address2}}</textarea>
					@error('address2')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="city" class="form-label">City <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="city" name="city" value="{{ old('city')?old('city'):$Userdata[0]->city}}" placeholder="Enter City Name" required>
					@error('city')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="country" class="form-label">Country <span class="red-color">*</span></label>
					<select class="form-select" id="country" name="country">
						{!! $countrycombo !!}
					</select>
					@error('country')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
			</div>
			<div class="row row10 pb-1">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="state" class="form-label">State <span class="red-color">*</span></label>
					<select class="form-select" id="state" name="state">
							{!! $statecombo !!}
					</select>
					@error('state')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="zip" class="form-label">Zip <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code" value="{{ old('zip')?old('zip'):$Userdata[0]->zip}}" required>
				</div>
			</div>
			<div class="myact-btn">
				<button type="submit" class="btn" title="Save" aria-label="Save">Save</button>
				<a href="{{ route('myaccount') }}" class="myact-back" tabindex="0" title="Back" aria-label="Back">
					<svg class="svg_arrow_right" aria-hidden="true" role="img" width="7" height="14"><use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use></svg>Back
				</a>
			</dv>

			
		</form>
	</div>
</div>
@endsection