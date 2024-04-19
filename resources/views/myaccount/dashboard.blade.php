@extends('layouts.app')
@section('content')
<div class="container">
	<div class="breadcrumb">
		<a href="{{config('const.SITE_URL')}}">Home</a> > <span class="active">My Account</span>
	</div>
	<div class="myact mt-4 mb-4">
		@include('myaccount.myaccountmenu')
		<div class="ac_dashboard mb-5 por">
			<div class="bgbanner hidden-sm-down">
				<img src="https://via.placeholder.com/1500x766" width="1500" height="766" class="d-none hidden-xs-down" alt="placeholder">
				<img src="https://via.placeholder.com/767x500" width="767" height="480" class="hidden-sm-up" alt="placeholder">
			</div>
			<div class="over_cont tac">
				<h1 class="mb-1 tal_sm tac h2">Welcome to your Account</h1>
				<div class="sales_box jcc">
					<div class="thumb mb-sm-0 mb-4 tal_sm tac">
						<img src="{{config('const.SITE_URL')}}/images/sales-image.jpg" width="129" height="129" alt="sales-image">
					</div>
					<div class="sales_det tal_sm tac">
						<p>Hello, {!! ucfirst(Session::get('sess_first_name')) !!}!</p>
						<p>Thank you for choosing {{config('Settings.SITE_NAME')}}!</p>
						<p class="mb-0">Best Regards,<br>{{config('Settings.SITE_NAME')}} Team</p>
						<div class="mb-1">
							<a href="mailto:{{config('Settings.CONTACT_MAIL')}}">
								<svg class="svg-email me-2" aria-hidden="true" role="img" width="20" height="20"><use href="#svg-email" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-email"></use></svg>{{config('Settings.CONTACT_MAIL')}}
							</a>
						</div>
						<div class="mb-1">
							<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">
								<svg class="svg-phone me-2" aria-hidden="true" role="img" width="20" height="20"><use href="#svg-phone" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use></svg>{{config('Settings.TOLL_FREE_NO')}}
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>	    
	</div>
</div>
@endsection