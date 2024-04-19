@extends('layouts.app')
@section('content')
<div class="container">
	<div class="myact mt-lg-0 mt-4 pb-sm-5 pb-0 mb-5">
		<div class="row row10">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				<div class="pb-3 pt-3 hidden-sm-down">
					<h2>Change Password</h2>
				</div>
				<div class="pb-3"><strong class="red-color">Note:</strong> Password must contain at least 1 upper case letter (A-Z), 1 number (0-9), min. 8 characters.</div>
				<form class="gy-3 needs-validation" name="frmResetPassword" id="frmResetPassword" novalidate="" method="POST" action="{{ route('reset-password',['token'=>$token]) }}">
					<input type="hidden" value="{{$token}}" name="token">
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
						<label for="email" class="form-label">New Password <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="password" class="form-control" id="new_pass" name="new_pass" aria-describedby="inputGroupPrepend" placeholder="New Password" required>
						</div>
						@error('new_pass')
							<div class="invalid-feedback">{{ $message}}</div>
						@enderror
					</div>
					<div class="pb-2">
						<label for="email" class="form-label">Re-Type Password <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="password" class="form-control" id="confirm_pass" name="confirm_pass"aria-describedby="inputGroupPrepend" placeholder="Re-Type Password" required>
						</div>
						@error('confirm_pass')
							<div class="invalid-feedback">{{ $message}}</div>
						@enderror
					</div>
					<div class="pb-2">
						<div class="input-group has-validation">
							<?php /*{!! NoCaptcha::display() !!}*/?>
						</div>
						@error('g-recaptcha-response')
						<div class="invalid-feedback">{{$message}}</div>
						@enderror
						<label id="g-recaptcha-response-error" class="error w-100" for="g-recaptcha-response" style="display: none;"></label>
					</div>
					<div class="mt-2">
						<div class="row row10">
							<div class="col-md-6 col-sm-7 col-xs-12 pb-2 pb-sm-0">
								<button type="submit" class="btn btn-block btn-success">Change My Password</button>
							</div>
							<div class="col-md-6 col-sm-5 col-xs-12 tac tar_sm pt-sm-1 pt-0">
								<a href="{{ route('myaccount') }}" class="text_c2 dflex aic fr_sm">
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
</div>
@endsection
<?php /*
@push('scripts')
	{!! NoCaptcha::renderJs() !!}
@endpush */?>
<script src="{{ asset('js/front/resetpassword.js') }}"></script>