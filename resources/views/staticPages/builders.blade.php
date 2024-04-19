@extends('layouts.app')
@section('content')
<div class="static-page">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active" tabindex="0">Professional Builder Price Comparison</span> 
		</div>
		<div class="static-hd"><h2 tabindex="0">Professional Builder Price Comparison</h2></div>
		<div class="static-con">
			<h1 class="sub-hd1 red-color" tabindex="0">Professional Builder Price Comparison</h1>
			<p tabindex="0">Carpet Express has served the building industry for over 20 years by shipping floor covering direct from our distribution center in Dalton, GA, to job sites and trucking/shipping terminals across the United States.</p>
			<p class="mb-5" tabindex="0">Our experienced sales staff is commited to customer service and allows builders to buy from us with confidence, knowing their entire order will be handled right.</p>

			<h3 class="sub-hd2" tabindex="0">Sample Request</h3>
			<p tabindex="0">Please call {{config('Settings.TOLL_FREE_NO')}} and let us design a sample package that meets your individual needs. At Carpet Express we offer the most trusted brands of residential and commercial flooring at the lowest possible price.</p>
			<p class="mb-5" tabindex="0">Your personal Carpet Express sales representative will work hard to earn and keep your business. Our southern hospitality and dependable service makes shopping a pleasure at Carpet Express. <strong class="black-color">Great Prices Are Only The Beginning!</strong></p>

			<h3 class="sub-hd2" tabindex="0">Price Comparison</h3>
			<p class="mb-5" tabindex="0">Please complete our "Price Comparison Form" for a quick price check on your regularly purchased flooring products. Just list the manufacturer name, style name, average order size, and price of the Carpet,Vinyl,Hardwood, Laminate or Pad you currently use. </p>
			<hr>
			<div class="text-center mt-5 mb-5">
				<h3 class="sub-hd2" tabindex="0">Price Comparison Form</h3>
			</div>
			<form class="row gy-3 needs-validation" action="{{ route('builders') }}" method="post" name="BuildersForm" id="BuildersForm" novalidate="novalidate">
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
				<div class="col-md-12 error-selector">
					<div class="pb-md-3 text-end black-color">
						<span class="red-color">*</span>Required Fields
					</div>
				</div>
				<div class="col-md-6 error-selector">
					<label for="email" class="form-label">Email <span class="red-color">*</span></label>
					<div class="input-group has-validation">
						<input type="email" class="form-control" id="email" name="email" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required="">
						<span class="input-group-text" id="inputGroupPrepend">@</span>					
						<div class="form-text">We'll never share your email with anyone else.</div>
						@error('email')
						<div class="invalid-feedback">{{ $message}}</div>					
						@enderror
					</div>
				</div>
				<div class="col-md-6 error-selector">
					<label for="your_name" class="form-label">Your Name <span class="red-color">*</span></label>
					<input type="text" class="form-control" id="your_name" name="your_name" placeholder="Enter First Name" required="">
					@error('your_name')
					<div class="invalid-feedback">{{ $message}}</div>					
					@enderror
				</div>
				<div class="col-md-6">
					<label for="phone" class="form-label">Phone Number</label>
					<input class="form-control phone" id="phone" name="phone" type="tel">
				</div>
				<div class="col-md-6">
					<label for="prior" class="form-label d-block">Have you dealt with us before?</label>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="prior" id="prior" value="Yes">
						<label class="form-check-label" for="prior">Yes</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="prior" id="prior" value="No">
						<label class="form-check-label" for="prior">No</label>
					</div>				
				</div>
				@for($i=0; $i<5; $i++)
				<div class="col-md-3">
					<label for="mfgName{{$i}}" class="form-label">Manufacturer Name:</label>
					<input class="form-control" id="mfgName{{$i}}" name="mfgName{{$i}}" type="text">
				</div>
				<div class="col-md-3">
					<label for="styleName{{$i}}" class="form-label">Style Name:</label>
					<input class="form-control" id="styleName{{$i}}" name="styleName{{$i}}" type="text">
				</div>
				<div class="col-md-3">
					<label for="avgOrder{{$i}}" class="form-label">Average Order:</label>
					<input class="form-control" id="avgOrder{{$i}}" name="avgOrder{{$i}}" type="number">
				</div>
				<div class="col-md-3">
					<label for="Price{{$i}}" class="form-label">Current Price:</label>
					<input class="form-control" id="Price{{$i}}" name="Price{{$i}}" type="number">				
				</div>
				@endfor	
				<div class="col-md-12">
					<label for="comments" class="form-label">Additional Comments:</label>
					<textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Additional Comments" spellcheck="false"></textarea>
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

           
@endsection