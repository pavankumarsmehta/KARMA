@extends('layouts.app')
@section('content')
<div class="container">
   <div class="breadcrumb"><a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
      <use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
      </svg> </a> <span class="active" tabindex="0">Login</span> </div>
    <div class="login-page pb-sm-5 pb-0 mb-5">
      <div class="tac pb-3">
        <h1 tabindex="0" class="h2">Welcome To <span class="text_c3">{{config('const.SITE_NAME')}}</span></h1>
		@if (Session::has('success'))
			<x-message :attr="['classname' => 'frmsuccess', 'message' => Session::get('success')]"/>
		@endif
		@if ($errors->has('Failed'))
			<x-message :attr="['classname' => 'frmerror_shw', 'message' => $errors->first('Failed')]"/>
		@endif
      </div>
      <form class="row row10 needs-validation" id="formLogin" method="post" name="formLogin" novalidate="">
		@csrf
		<input type="hidden" name="action" value="signin">
        <div class="col-md-12 col-sm-12 pb-4 pb-sm-0">
          <h3 tabindex="0">Login</h3>
          <div class="login_min_h">
            <div class="pb-2 pt-2 f16" tabindex="0">Welcome back! Please login to your account.</div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <div class="pb-2">
                  <label for="email" class="form-label" tabindex="0">Email <span class="red-color">*</span></label>
                  <div class="input-group has-validation">
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
                    <span class="input-group-text" id="inputGroupPrepend_email">@</span>
    				@error('email')
    					<div class="invalid-feedback frmerror_shw">{{ $message}}</div>
    				@enderror
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6">
                <div class="pb-2">
                  <label for="pass_log_id" class="form-label" tabindex="0">Password <span class="red-color">*</span></label>
                  <div class="input-group has-validation">
                    <input type="password" class="form-control password-input" name="password" id="password" aria-describedby="inputGroupPrepend" placeholder="Enter Password" required>
                    <span class="input-group-text pass-visible" style="cursor:pointer;" id="inputGroupPrepend">
                      <svg class="svg_eye dnone" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
                      <use href="#svg_eye" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye"></use>
                      </svg>
                      <svg class="svg_eye_slash" aria-hidden="true" role="img" width="20" height="20" loading="lazy">
                      <use href="#svg_eye_slash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_eye_slash"></use>
                      </svg>
                    </span>
                  </div>
                  @error('password')
                    <div class="invalid-feedback frmerror_shw">{{ $message}}</div>
                  @enderror
                </div>
              </div>
            </div>


            
            <div class="dflex jcb pb-2">
              <div class="form-check">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
                <input class="form-check-input" type="checkbox" value="" id="rememberMe" name="rememberMe" aria-label="Remember me">
              </div>
              <a href="{{route('forgot-password')}}" title="Forgot Password" class="f16 text_c1 tdu">Forgot Password?</a> </div>
          </div>
		  <button type="submit" class="btn btn-block" title="Login" aria-label="Login">Login</button>
		  </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <h3 tabindex="0">Register</h3>
          <div class="login_min_h">
            <div class="pb-2 pt-2 f16" tabindex="0">Creating an account is simple and fast. Just click the button below. Once it's been set up, you can take advantage of all the benefits of HBASales.</div>
          </div>
          <button type="button" onclick="window.location='{{route('register')}}'" class="btn btn-border ttu btn-block" title="Register Now" area-label="Register Now">Register Now</button> </div>
      </form>
    </div>
    </div>
	
@endsection