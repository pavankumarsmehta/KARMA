@php
$countrycombo = displaycountry(old('country'), $countryArray);
$statecombo = displaystate(old('country'), $stateArray);
@endphp
@extends('layouts.app')
@section('content')

<div class="container myact">
	<div class="breadcrumb">
		<a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
      <use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
      </svg> 
		</a> 
		<span class="active" tabindex="0">Register Your Account</span> 
	</div>
	<div class="container-650">
		<h2 tabindex="0" class="tac pb-3 hidden-sm-down">Register Your Account</h2>
		<h3 tabindex="0" class="tac pb-3 hidden-md-up">Register Your Account</h3>
		{{--<div class="pb-2 pt-2 f16"><strong class="red-color">Note:</strong> Password must contain at least 1 upper case letter (A-Z), 1 number (0-9), min. 8 characters.</div>--}}

		@if (count($errors) > 0)
		<div class="error tac">
			<?php //echo "<pre>"; print_r(count($errors));  echo "</pre>";
			?>
			@if($errors->has('existing_email'))
			{{ $errors->first('existing_email') }}
			@endif
		</div>
		@endif
		<form class="needs-validation" novalidate="" method="post" id="frmRegister">
			<input type="hidden" name="action" value="signup" />
			@csrf
			<div class="pb-2">
				<label for="email" class="form-label" tabindex="0">Email <span class="red-color">*</span></label>
				<div class="input-group has-validation">
					<input type="email" class="form-control" id="email" name="email" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
					<span class="input-group-text" id="inputGroupPrepend_email">@</span>
					@error('email')
					<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
			</div>
			<div class="pb-2">
				<label for="phone" class="form-label" tabindex="0">Phone Number <span class="red-color">*</span></label>
				<input type="number" class="form-control phone" id="phone" name="phone" placeholder="Enter Phone Number" required>
				@error('phone')
				<div class="invalid-feedback">{{ $message}}</div>
				@enderror
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="pass_log_id" class="form-label" tabindex="0">Password <span class="red-color">*</span></label>
					<div class="input-group has-validation">
						<input type="password" class="form-control password-input" id="password" name="password" aria-describedby="inputGroupPrepend" placeholder="Enter Password" required minlength="8" passwordCheck="passwordCheck">
						<span class="input-group-text pass-visible" style="cursor:pointer;" id="inputGroupPrepend_password">
							<svg class="svg_eye dnone" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye"></use>
							</svg>
							<svg class="svg_eye_slash" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye_slash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye_slash"></use>
							</svg>
						</span>
					</div>
					@error('password')
					<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="pass_log_id1" class="form-label" tabindex="0">Confirm Password <span class="red-color">*</span></label>
					<div class="input-group has-validation">
						<input type="password" class="form-control password-input" name="confirmpassword" id="confirmpassword" aria-describedby="inputGroupPrepend" placeholder="Enter Password" required>
						<span class="input-group-text pass-visible" style="cursor:pointer;" id="inputGroupPrepend_confirmpassword">
							<svg class="svg_eye svg_eyer dnone" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye"></use>
							</svg>
							<svg class="svg_eye_slash svg_eyes" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye_slash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye_slash"></use>
							</svg>
						</span>
					</div>
					@error('confirmpassword')
					<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
			</div>
			<div class="pb-3 f12" tabindex="0"><strong class="red-color">Note:</strong> Password must contain at least 1 upper case letter (A-Z), 1 number (0-9), min. 8 characters.</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="first-name" class="form-label" tabindex="0">First name <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required>
					@error('first_name')
					<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="first-name" class="form-label" tabindex="0">Last name <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" required>
					@error('last_name')
					<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
			</div>
			<div class="pb-2">
				<label for="address" class="form-label" tabindex="0">Street Address <span class="red-color">*</span></label>
				<textarea class="form-control" id="address1" name="address1" rows="4" placeholder="Enter Street Address" required spellcheck="false"></textarea>
				@error('address1')
					<div class="invalid-feedback">{{$message}}</div>
				@enderror
			</div>
			<div class="pb-2">
				<label for="address1" class="form-label" tabindex="0">Street Address 2</label>
				<textarea class="form-control" id="address2" name="address2" rows="4" placeholder="Enter Street Address" spellcheck="false"></textarea>
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="city" class="form-label" tabindex="0">City <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="city" name="city" placeholder="Enter City Name" required>
					@error('city')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="state" class="form-label" tabindex="0">State <span class="red-color">*</span></label>
					<select class="form-select" id="state" name="state" required>
						{!! $statecombo !!}
					</select>
					@error('state')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
			</div>
			<div class="row row10">
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="zip" class="form-label" tabindex="0">Zip <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code" required>
					@error('zip')
					<div class="invalid-feedback">{{$message}}</div>
					@enderror
				</div>
				<div class="col-md-6 col-sm-6 pb-2">
					<label for="country" class="form-label" tabindex="0">Country <span class="red-color">*</span></label>
					<select class="form-select" id="country" name="country">
						{!! $countrycombo !!}
					</select>
					@error('country')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
			</div>
			<div class="pt-2">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="" id="agree" name="agree" required>
					<label class="form-check-label" for="agree">I agree with the <a href="{{ config('const.SITE_URL') }}/pages/terms-and-condition" target=”_blank” class="linksbb" title="Terms of Service">Terms of Service</a> & <a href="{{ config('const.SITE_URL') }}/pages/privacy-policy" target=”_blank” class="linksbb" title="Privacy Policy">Privacy Policy</a></label>
				</div>
				@error('agree')
				<div class="invalid-feedback frmerror_shw">{{$message}}</div>
				@enderror
			</div>
			<div class="mb-4">
				<div class="form-check mb-0 mt-2">
					<input class="form-check-input" type="checkbox" value="" id="subscribe" name="subscribe">
					<label class="form-check-label" for="subscribe">Subscribe to our Newsletter</label>
				</div>
			</div>
			<div class="mb-4">
			{{--{!! NoCaptcha::display() !!}--}}
				@error('g-recaptcha-response')
				<div class="invalid-feedback">{{$message}}</div>
				@enderror
				<label id="g-recaptcha-response-error" class="error" for="g-recaptcha-response" style="display: none;"></label>
			</div>
			<div class="pt-3"><button type="submit" class="btn btn-block btn-success" title="Create Account">Create Account</button></div>
			<div class="diblock pt-3">
				<a href="{{route('login')}}" class="text_c2 text_c2 dflex aic" title="Back">
					<svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
						<use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
					</svg>
					Back
				</a>
			</div>
		</form>
	</div>
</div>
@endsection
{{--
@push('scripts')
	{!! NoCaptcha::renderJs() !!}
@endpush
--}}