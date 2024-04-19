@extends('layouts.app')
@section('content')
<div class="container">
	<div class="breadcrumb">
		<a href="{{ route('home') }}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="5px" height="8px" aria-hidden="true" role="img"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
		<span class="active" tabindex="0">Track Your Order</span> 
	</div>


	<div class="myact mt-lg-0 mt-4 pb-sm-5 pb-0 mb-5">
		<div class="mb-3 hidden-md-up">
			<h1 tabindex="0" class="h2">Track Your Order</h2>
		</div>
		<div class="row row10">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				@if ($errors->has('Failed'))
					<x-message :attr="[
								'classname' => 'frmerror frmerror_shw', 
								'message' => $errors->first('Failed')]"
					/>
				@endif
				<div class="tar mb-3 f16" tabindex="0"><span class="red-color">*</span>Required Fields</div>
				<form id="formOrderTrack" class="needs-validation" novalidate="" method="post" name="formOrderTrack">
					@csrf 
					<input type="hidden" name="action" value="ordertrack"/>
					<div class="pb-2">
						<label for="ordernumber" class="form-label" tabindex="0">Order Number <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="number" class="form-control ordernumber" id="ordernumber" name="ordernumber" placeholder="Order Number" required>
							@error('ordernumber')
								<div class="invalid-feedback">{{ $message}}</div>
							@enderror
						</div>
					</div>
					
					<div class="pb-2">
						<label for="orderbillingemail" class="form-label" tabindex="0">Order Billing Email <span class="red-color">*</span></label>
						<div class="input-group has-validation">
							<input type="email" class="form-control orderbillingemail" id="orderbillingemail" name="orderbillingemail" aria-describedby="inputGroupPrepend" placeholder="Enter Your Email Id" required>
							@error('orderbillingemail')
								<div class="invalid-feedback">{{ $message}}</div>
							@enderror
						</div>
					</div>
					<div class="mt-2">
						<div class="row row10">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<button type="submit" class="btn btn-block btn-success">Submit</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>		
</div>
<div class="container">
	<div class="static-hd mb-3"><h2 tabindex="0">{{ $TrackContent['title'] }}</h2></div>
	<div class="static-con">
		<div class="accordion_title"></div>
		<div class="accordion_content">{!! $TrackContent['content'] !!}</div>
	</div>
</div>
@endsection