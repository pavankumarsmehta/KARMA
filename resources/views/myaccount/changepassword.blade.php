@extends('layouts.app')
@section('content')
<div class="container myact">	
	@include('myaccount.breadcrumbs')
	<h1 class="hidden-md-up h2" tabindex="0">Change Password</h1>
	@include('myaccount.myaccountmenu')
	<div class="container-650">
		<p><strong>Note:</strong> Password must contain at least 1 upper case letter (A-Z), 1 number (0-9), min. 8 characters.</p>
		<form class="needs-validation" name="frmChangePassword" id="frmChangePassword" novalidate="">
				@csrf
				@if (Session::has('success'))
				<x-message :attr="[
							'classname' => 'frmsuccess', 
							'message' => Session::get('success')]"
					/>
				@endif
				@if ($errors->has('wrong_password'))
					<x-message :attr="[
								'classname' => 'frmerror frmerror_shw', 
								'message' => $errors->first('wrong_password')]"
					/>
				@endif
				<div class="pb-2">
					<label for="old_pass" class="form-label">Old Password <span class="red-color">*</span></label>
					<div class="input-group has-validation">
						<input type="password" class="form-control" name="old_pass" id="old_pass"  aria-describedby="inputGroupPrepend" placeholder="Old Password" required>
						<span class="input-group-text">
							<svg class="svg_eye dnone" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye"></use>
							</svg>
							<svg class="svg_eye_slash" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
								<use href="#svg_eye_slash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye_slash"></use>
							</svg>
						</span>
					</div>
					@error('old_pass')
						<!-- <div id="old_pass-error" class="error w-100">{{ $message}}</div> -->
						<label id="old_pass-error" class="error w-100" for="old_pass">{{ $message}}</label>
					@enderror
				</div>
				<div class="pb-2">
					<label for="new_pass" class="form-label">New Password <span class="red-color">*</span></label>
					<div class="has-validation">
						<input type="password" class="form-control" id="new_pass" name="new_pass" aria-describedby="inputGroupPrepend" placeholder="New Password" required>
					</div>
					@error('new_pass')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>
				<div class="pb-3">
					<label for="confirm_pass" class="form-label">Re-Type Password <span class="red-color">*</span></label>
					<div class="brand_banner_image has-validation">
						<input type="password" class="form-control" id="confirm_pass" name="confirm_pass"aria-describedby="inputGroupPrepend" placeholder="Re-Type Password" required>
					</div>
					@error('confirm_pass')
						<div class="invalid-feedback">{{ $message}}</div>
					@enderror
				</div>				
				<div class="myact-btn">
					<button type="submit" class="btn" title="Change My Password" aria-label="Change My Password">Change My Password</button>
					<a href="{{ route('myaccount') }}" class="myact-back" tabindex="0" title="Back" aria-label="Back">
						<svg class="svg_arrow_right" aria-hidden="true" role="img" width="7" height="14"><use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use></svg>Back
					</a>
				</dv>
			</form>
	</div>
</div>
@endsection