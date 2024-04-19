@extends('layouts.app')
@section('content')
<div class="static-page">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active" tabindex="0">Contact Us</span> 
		</div>
		<div class="static-hd"><h1 tabindex="0" class="h2">Contact Us</h1></div>
		<div class="static-con contact_us">
			<div class="row">
				<div class="col-sm-6">{!! $left_content !!}</div>
				<div class="col-sm-6">
					{!! NoCaptcha::renderJs() !!}
					<form  action="{{ route('contact-us') }}" class="needs-validation" method="post" name="ContactUsForm" id="ContactUsForm" novalidate="novalidate">
					@csrf
					@if(Session::has('error_msg'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">  
								{!! Session::get('error_msg') !!}									
							</div>
						@elseif(Session::has('success_msg'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">  
								{!! Session::get('success_msg') !!}									
							</div>
					@endif
					<div class="row row10">
						<div class="col-md-6 col-sm-6 pb-2 error-selector">
						<label for="fname" class="form-label">Your Name <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="text" class="form-control" id="fname" name="fname" placeholder="Your Name" required>
							@error('fname')
								<div class="invalid-feedback">{{ $message}}</div>					
							@enderror
						</div>
						</div>
						<div class="col-md-6 col-sm-6 pb-2 error-selector">
						<label for="lname" class="form-label">Last name <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
							@error('lname')
								<div class="invalid-feedback">{{ $message}}</div>					
							@enderror
						</div>
						</div>
					</div>
					<div class="row row10">
						<div class="col-md-6 col-sm-6 pb-2 error-selector">
						<label for="email" class="form-label">Email <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="email" class="form-control" id="email" name="email"  aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
							<span class="input-group-text" id="inputGroupPrepend">@</span>
							@error('email')
								<div class="invalid-feedback">{{ $message}}</div>					
							@enderror
						</div>
						</div>
						
						<div class="col-md-6 col-sm-6 pb-2 error-selector">
						<label for="customer_phone" class="form-label">Phone <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="number" class="form-control" id="customer_phone" name="customer_phone"  aria-describedby="inputGroupPrepend" placeholder="Enter Your Phone number" required>
							@error('customer_phone')
								<div class="invalid-feedback">{{ $message}}</div>					
							@enderror
						</div>
						</div>
						
						
					</div>
					<div class="pb-2 error-selector">
						<label for="subject" class="form-label">Subject</label>
						<div class="input-group has-validation">
							<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
							@error('subject')
								<div class="invalid-feedback">{{ $message}}</div>					
							@enderror
						</div>
					</div>
					<div class="pb-2 error-selector">
						<label for="note" class="form-label">Note <span class="red-color">*</span></label>
						<textarea class="form-control" id="note" name="note" rows="4" placeholder="Note" required spellcheck="false"></textarea>
						@error('note')
						<div class="invalid-feedback">Please enter note</div>					
						@enderror
						</div>
					<!-- <div class="col-md-12 col-sm-12 pb-2">
						<label for="questions" class="form-label">Type the characters you see in the picture below.</label>
										<div class="pb-2">
							<img id="characters" src="https://via.placeholder.com/200x80" alt="Captcha">
						</div>
											<input id="" type="text" placeholder="Type the characters you see in the picture below." class="form-control">
						</div> -->
						<?php //dd(NoCaptcha::display()); ?>
						<div class="pb-2 error-selector sachin">
							{!! NoCaptcha::display() !!}
							@error('g-recaptcha-response')
								<div class="invalid-feedback">{{$message}}</div>
							@enderror
							<div id="g_recaptcha_error_div" class="error" style="display: none">
							</div>
						</div>
					<div class="mt-2">
						<button class="btn btn-success btn-xs-block" type="submit">Submit</button>	
						</div>				
					</form>			  
				</div>
			</div>
			<!--<div class="map_con">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2381.831876473348!2d-6.261628284280947!3d53.34626568241861!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48670e84c63bf2ab%3A0x11e40c300ba81e4!2s8%209!5e0!3m2!1sen!2sin!4v1640172562192!5m2!1sen!2sin" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
		  </div>-->
		</div>
	</div>    
</div>
@endsection
