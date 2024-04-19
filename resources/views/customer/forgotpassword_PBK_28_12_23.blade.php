@extends('layouts.app')
@section('content')
<div class="container">
	<div class="breadcrumb">
		<a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
      <use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
      </svg> 
		</a> 
		<span class="active" tabindex="0">Forgot Password</span> 
	</div>
	
	<div class="row row10">
		<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 pb-sm-5 pb-0 mb-5">
			<div class="tac pb-3">
				<h2 tabindex="0">Forgot Password</h2>
			</div>
			<div class="pb-2 pt-2 f16" tabindex="0">Please enter the e-mail address you use when creating your account, we’ll send you instruction to reset your password.</div>
			@if (count($errors) > 0)
			<div class="error tac">
				<?php //echo "<pre>"; print_r(count($errors));  echo "</pre>";
				?>
				@if($errors->has('not_exist_email'))
				{{ $errors->first('not_exist_email') }}
				@endif
			</div>
			@endif
			<form method="post" id="formForgotPassword" name="formForgotPassword" class="needs-validation ">
				@csrf
				<input type="hidden" name="action" value="forgot_password"/>
				<div class="pb-4">
					<label for="email" class="form-label" tabindex="0">Email <span class="red-color">*</span></label>
					<div class="input-group has-validation">
						<input type="email" class="form-control" id="email" name="email" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
						<span class="input-group-text" id="inputGroupPrepend">@</span>
						@error('email')
							<div class="invalid-feedback">{{ $message}}</div>
						@enderror
						@if ($errors->has('email'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('email')]"
							/>
						@endif
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
					</div>
				</div>
				<div class="pb-4">
					<div class="input-group has-validation">
					{{-- {!! NoCaptcha::display() !!} --}}
						@error('g-recaptcha-response')
							<div class="invalid-feedback">{{ $message}}</div>
						@enderror
					</div>
					<label id="g-recaptcha-response-error" class="error w-100" for="g-recaptcha-response" style="display: none;"></label>
				</div>
				<div class="order-row row row10">
					<div class="col-md-6 col-sm-7 col-xs-12 pb-2 pb-sm-0">
						<button type="submit" class="btn btn-block btn-success" title="Submit" aria-label="Submit">Submit</button>
					</div>
					<div class="col-md-6 col-sm-5 col-xs-12 tac tar_sm pt-sm-1 pt-0">
						<div class="diblock">
							<a href="{{route('login')}}" class="text_c2 text_c2 dflex aic" tabindex="0" title="Back" aria-label="Back">
								<svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
									<use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
								</svg>
								Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
{{--
@push('scripts')
	{!! NoCaptcha::renderJs() !!}
@endpush
--}}