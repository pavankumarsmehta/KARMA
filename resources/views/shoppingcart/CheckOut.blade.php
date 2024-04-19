@extends('layouts.app')
@section('content')
<div class="checkout">
	<div class="container">
		<div class="breadcrumb">
			<a href="#">
				Home
				<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
					<use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
				</svg>
			</a>
			<span class="active">Checkout</span>
		</div>
		<div class="checkout_title">
			<h2>Checkout</h2>
			@guest
			<p>Already have an account? Click here to <a href="javascript:void(0);" class="text_c1 tdu loginpopup">Sign in</a> Or <a href="{{config('const.SITE_URL')}}/register.html" class="text_c1 tdu">Create an Account</a></p>
			@endguest
		</div>
		
		<form id="frmCheckOut" name="frmCheckOut" action="{{config('const.SITE_URL')}}checkout-actiononcart" method="POST">
			{{ csrf_field() }}			
			<input type="hidden" name="action" value="">
			<input type="hidden" name="avalara_valid_cnt" id="avalara_valid_cnt" value="1">
			<input type="hidden" name="paypalec" id="paypalec" value="@if(isset($paypalec)){{ $paypalec }}@endif">	
			<input type="hidden" name="is_customer_login" id="is_customer_login" value="{{$is_customer_login}}">
			<input type="hidden" name="is_user_confirm_guest_checkout" id="is_user_confirm_guest_checkout" value="0">	
			<div class="checkout_row">
				<div class="checkout_left">
					<div class="checkout-form">
						<div class="checkout-shipping">
							<div class="ck-leftsec step1" id="c-step-1">
								<div class="row row10">
									<div class="col-sm-7">
										<h2>Contact Information</h2>
									</div>
									@guest
									<div class="col-sm-3">
										<p class="ck-editlink d-none" id="contact-edit-link"><a href="javascript:void(0);" onclick="edit_checkout_step('contact');" class="prime-link">Edit</a></p>
									</div>
									@endguest
									<div class="col-sm-3">
										<p class="ck-editlink">&nbsp;</p>
									</div>
									<div id="contact-form-section">
										<div class="col-xs-12 col-sm-12 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_sh_email" name="bl_sh_email" value="{{$Shipping['email']}}" placeholder="e.g. name@email.com" required />
											<div class="error-cls" id="error_bl_sh_email"></div>
											<div class="valid-feedback" id="success_bottom_email"></div>
										</div>
										{{--<div class="col-sm-8 py-2 py-md-3"><span class="f14"><input type="checkbox" name="newsletter" id="newsletter" checked="checked"> Keep me up to date on news and exclusive offers through email.</span></div>--}}
										<div class="col-sm-8 py-2 py-md-3">
											<a href="javascript:void(0);" class="btn btn-success" onclick="valid_contact_detail();">Save & Continue</a>
										</div>
									</div>
									<div class="left-sec-infodtl d-none" id="contact-info-section">
										<div class="left-infodtl-inner" id="contact-filled-section"></div>
									</div>
								</div>
							</div>
							<div class="ck-leftsec step2" id="c-step-2">
								<div class="row row10">
									<div class="col-sm-7">
										<h2>Shipping Address</h2>
									</div>
									<em class="errmsg mt-1 mt-md-0 d-none" id="shipping-mandatory-text">* Fields are mandatory</em>
									<div class="col-sm-5 tar tar_sm"><span class="f14">(<span class="red-color">*</span> Required field) <div class="ck-editlink d-none" id="shipping-edit-link"><a href="javascript:void(0);" onclick="edit_checkout_step('shipping');" class="prime-link">Edit</a></div> </span></div>
									
								</div>
								<div class="d-none" id="shipping-form-section">
									<div class="pb-1 errmsg" id="av_tax_error"></div>
									<div class="row row10 form-row">
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_fname" name="sh_fname" value="{{ $Shipping['first_name'] }}" placeholder="First Name *" required />
											<div class="error-cls" id="error_sh_fname"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_lname" name="sh_lname" value="{{ $Shipping['last_name'] }}" placeholder="Last Name *" required />
											<div class="error-cls" id="error_sh_lname"></div>
										</div>
										<div class="col-xs-12 col-sm-12 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_company" name="sh_company" placeholder="Company" required />
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_Addr1" name="sh_Addr1" value="{{ $Shipping['address1'] }}" placeholder="Address 1 *" required />
											<div class="error-cls" id="error_sh_Addr1"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_Addr2" name="sh_Addr2" value="{{ $Shipping['address2'] }}" placeholder="Address 2" />
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_city" name="sh_city" value="{{ $Shipping['city'] }}" placeholder="City *" required />
											<div class="error-cls" id="error_sh_city"></div>
										</div>
										
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<select class="form-select" id="sh_country" name="sh_country" placeholder="Select Country" required>
												<option selected>Select Country</option>
												@foreach($aCountry as $countryShort => $countryLong)
												<option value="{{ $countryShort }}" @if($Shipping["country"] == $countryShort) selected @endif>{{ $countryLong }}</option>
												@endforeach
											</select>
											<div class="error-cls" id="error_sh_country"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<select class="form-select" id="sh_state" name="sh_state" placeholder="Select State" required>
												<option selected>Select State</option>
												@foreach($aState as $stateShort => $stateLong)
													<option value="{{ $stateShort }}" @if($Shipping["state"] == $stateShort) selected @endif >{{ $stateLong }}</option>
												@endforeach
											</select>
											<div class="error-cls" id="error_sh_state"></div>						
											<input type="text" name="sh_otherstate" id="sh_otherstate" value="{{ $Shipping['state'] }}" placeholder="State" class="form-control" />
											<div class="error-cls" id="error_sh_otherstate"></div>
										</div>
										<div class="col-xs-4 col-sm-2 py-2 py-md-3">
											<input type="text" class="form-control" id="sh_zip" name="sh_zip" value="{{ $Shipping['zip'] }}" placeholder="Zip Code *" required />
											<div class="error-cls" id="error_sh_zip"></div>
										</div>
										<div class="col-xs-8 col-sm-4 py-2 py-md-3">
											<div class="input-group"> {{--<span class="input-group-text">+1</span>--}}
												<input type="text" class="form-control" id="sh_phone" name="sh_phone" value="{{ $Shipping['phone'] }}" placeholder="Phone Number" />
												<div class="error-cls" id="error_sh_phone"></div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 py-2 py-md-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="sa-reoffers" required>
												<label class="form-check-label" for="sa-reoffers"> Receive email and other exclusive offers</label>
											</div>
										</div>
										@guest
										<div class="col-xs-12 col-sm-12 py-2 py-md-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="sa-csg" required>
												<label class="form-check-label" for="sa-csg"> Continue as Guest</label>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 py-2 py-md-3">
											<label class="form-label f600 f14">Create My hbasales Shoppe'er Account</label>
											<div class="row row10">
												<div class="col-xs-12 col-sm-6 py-2 py-md-1">
													<input type="text" class="form-control" id="sa-choose-password" placeholder="Choose a password" required />
												</div>
												<div class="col-xs-12 col-sm-6 py-2 py-md-1">
													<input type="text" class="form-control" id="sa-rernt-password" placeholder="Re-Enter Password" required />
												</div>
											</div>
										</div>
										@endguest
										
										<div class="row row10">
											<div class="col-sm-12">
												<h2>Shipping Method</h2>
											</div>
											<div class="col-sm-5 tar tar_sm">
												<div class="error-cls" id="error_shippingMethod"></div>
												<div class="shipping-method" id="shipping-methods">
													<input type="radio" name="shippingModeId" id="shippingModeId0" value="1" checked="" onclick="Ajax_GetOrder_Summery();" data-charge="0" data-shipname="FedEx 2Day">FedEx 2Day<br>
													<input type="radio" name="shippingModeId" id="shippingModeId1" value="2" onclick="Ajax_GetOrder_Summery();" data-charge="10.00" data-shipname="FedEx Priority Overnight">FedEx Priority Overnight
												</div>
												<input type="hidden" name="count_shipmethod" id="count_shipmethod" value="2">
											</div>
										</div>
									</div>
									<a href="javascript:void(0);" onclick="valid_shipping_detail();" class="btn btn-success">SAVE & CONTINUE</a>
								</div>
								<div class="left-sec-infodtl d-none" id="shipping-info-section">
									<div class="left-infodtl-inner pt-4 mt-1" id="shipping-address-filled-section"></div>
									<div class="d-flex align-items-center justify-content-between mt-4 pt-3">
										<h3 class="sub-hd1">Shipping Method</h3>
									</div>
									<div class="left-infodtl-inner pt-4 mt-1" id="shipping-method-filled-section">
									</div>
								</div>		
							</div>
						</div>
						<hr />
						<div class="ck-leftsec step3" id="c-step-3">
							<div class="checkout-billing py-3">
								<h2>Billing Address</h2>
								<div class="col-sm-3">
									<p class="ck-editlink d-none" id="billing-edit-link"><a href="javascript:void(0);" onclick="edit_checkout_step('billing');" class="prime-link">Edit</a></p>
								</div>
								<div class="form-check mt-2 d-none">
									<input class="form-check-input" type="checkbox" id="same_asbill" name="same_asbill" @if($IsBillingAsShipping != 'No') checked @endif onclick="show_billing_address();">
									<label class="form-check-label" for="ba-csg"> Same as Shipping address</label>
								</div>
								<div class="" id="billing-form-section">
									<div class="row row10 form-row">
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_fname" name="bl_fname" value="{{ $Billing['first_name'] }}" placeholder="First Name *" required />
											<div class="error-cls" id="error_bl_fname"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_lname" name="bl_lname" value="{{ $Billing['last_name'] }}" placeholder="Last Name *" required />
											<div class="error-cls" id="error_bl_lname"></div>
										</div>
										<div class="col-xs-12 col-sm-12 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_company" name="bl_company" placeholder="Company"  />
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_Addr1" name="bl_Addr1" value="{{ $Billing['address1'] }}" placeholder="Address 1 *" required />
											<div class="error-cls" id="error_bl_Addr1"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_Addr2" name="bl_Addr2" value="{{ $Billing['address2'] }}" placeholder="Address 2" />
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_city" name="bl_city" value="{{ $Billing['city'] }}" placeholder="City *" required />
											<div class="error-cls" id="error_bl_city"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<select class="form-select" id="bl_country" name="bl_country" placeholder="Select Country" required>
												<option selected>Select Country</option>
												@foreach($aCountry as $countryShort => $countryLong)
												<option value="{{ $countryShort }}" @if($Shipping["country"] == $countryShort) selected @endif>{{ $countryLong }}</option>
												@endforeach
											</select>
											<div class="error-cls" id="error_bl_country"></div>
										</div>
										<div class="col-xs-12 col-sm-6 py-2 py-md-3">
											<select class="form-select" id="bl_state" name="bl_state" placeholder="Select State" required>
												<option selected>Select State</option>
												@foreach($aState as $stateShort => $stateLong)
													<option value="{{ $stateShort }}" @if($Shipping["state"] == $stateShort) selected @endif >{{ $stateLong }}</option>
												@endforeach
											</select>
											<div class="error-cls" id="error_bl_state"></div>						
											<input type="text" name="bl_otherstate" id="bl_otherstate" value="{{ $Billing['state'] }}" placeholder="State" class="form-control" />
											<div class="error-cls" id="error_bl_otherstate"></div>
										</div>
										<div class="col-xs-4 col-sm-2 py-2 py-md-3">
											<input type="text" class="form-control" id="bl_zip" name="bl_zip" value="{{ $Billing['zip'] }}" placeholder="Zip Code *" required />
											<div class="error-cls" id="error_bl_zip"></div>
										</div>
										<div class="col-xs-8 col-sm-4 py-2 py-md-3">
											<div class="input-group"> {{--<span class="input-group-text">+1</span>--}}
												<input type="text" class="form-control" id="bl_phone" name="bl_phone" value="{{ $Billing['phone'] }}" placeholder="Phone Number" />
												<div class="error-cls" id="error_bl_phone"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>	
						<hr />
						<div class="checkout-payment py-3">
							<h2>
								Payment Method 
								<a href="#" class="text_c1 ms-2">
									<svg class="svg_lock" width="15px" height="17px" aria-hidden="true" fill="none" role="img">
										<use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use>
									</svg>
									Secure and Encrypted
								</a>
							</h2>
							<div class="d-none" id="payment-form-section">
								<div class="checkout-acd active">
								   <label class="title">
										<input class="check-input-rd" name="rd-checkout" type="radio"  checked="checked"/> 
										<span class="pm-logo"><img src="images/check_cod.png" alt="cod" /></span>
										Cash On Delivery 
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
								   </label>
									<!--<div class="title">
										<input class="check-input-rd" name="rd-checkout" type="radio"  checked="checked"/> 
										<div class="pm-logo"><img src="images/check_affirm-inc.png" alt="Affirm" /></div>
										Cash On Delivery 
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
									</div>-->
									<div class="content">
										<div class="inner">test</div>
									</div>
								</div>
								<div class="checkout-acd">
									<label class="title"><input class="check-input-rd" name="rd-checkout" type="radio" /> 
										<span class="pm-logo"><img src="images/check_credit_card.png" alt="Credit Card" /></span>
										Credit Card 
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
									</label>
									<div class="content">
										<div class="inner">
											<form method="post">
												<div class="pb-3">
													<label class="form-label f14" for="cardnumber">We accepts all major credit and debit cards.</label>
													<div class="row row10 form-row aic">
														<div class="col-xs-12 col-sm-8 py-1">
															<input type="text" id="cardnumber" class="form-control" placeholder="Card Number *" required />
														</div>
														<div class="col-xs-12 col-sm-4 py-1"><img src="images/card_icon.png" alt="" /></div>
													</div>
												</div>
												<div class="pb-3">
													<label class="form-label f14">Expiration Date</label>
													<div class="row row10">
														<div class="col-xs-12 col-sm-8">
															<div class="row row10">
																<div class="col-xs-6 col-sm-4 py-1">
																	<select class="form-select" id="pm-month" required="">
																		<option value=''>Month</option>
																		@for($i=1;$i<=12;$i++)
																		<option value="{{str_pad($i, 2, '0', STR_PAD_LEFT)}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>
																		@endfor
																	</select>
																</div>
																<div class="col-xs-6 col-sm-4 py-1">
																	<select class="form-select" id="pm-year" required="">
																		<option value=''>Year</option>
																		@for($i=date('Y');$i<=(date('Y'))+15;$i++)
																		<option value="{{$i}}">{{$i}}</option>
																		@endfor
																	</select>
																</div>
																<div class="col-xs-6 col-sm-4 py-1">
																	<div class="check_csc">
																		<input type="text" class="form-control" placeholder="CSC *" value="CSC *" required>
																		<img src="images/cart_csc.png" alt="" /> 
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" id="pm-scc" required>
													<label class="form-check-label" for="pm-scc"> Save credit card <u>Learn More</u></label>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="checkout-acd">
									<label class="title"><input class="check-input-rd" name="rd-checkout" type="radio" /> 
										<span class="pm-logo"><img src="images/check_affirm-inc.png" alt="Affirm" /></span>
										Affirm 
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
									</label>
									<div class="content">
										<div class="inner">test</div>
									</div>
								</div>
								<div class="checkout-acd">
									<label class="title"><input class="check-input-rd" name="rd-checkout" type="radio" /> 
										<span class="pm-logo"><img src="images/check_paypal.png" alt="Paypal" /></span>
										Paypal 
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
									</label>
									<div class="content">
										<div class="inner">test</div>
									</div>
								</div>
							</div>
						</div>
						<hr />
						<div class="checkout-item pt-3">
							<h2>YOUR ITEMS</h2>
							<div class="cart_table">
								<?php //dd($cart_data); ?>
								@if(count($cart_data) > 0)	
									@foreach($cart_data as $key => $val)
										<div class="loop">
											<div class="cart_row">
												<div class="thumb">
													<a href="{{$val['product_url']}}" class="img-wrapper"><img src="{{ $val['Image'] }}" alt="" width="130" height="130" /></a>
													<div class="hidden-sm-up qty">
														<div class="qty-text pt-2">Qty: {{ $val['Qty'] }}</div>
													</div>
												</div>
												<div class="info">
													<div class="mb-3"><a href="{{$val['product_url']}}" class="name text_c1">{{ $val['ProductName'] }}</a></div>
													<div class="pb-2">SKU#<span class="ps-2">{{ $val['SKU'] }}</span></div>
													@if(isset($val['size_dimension']) && $val['size_dimension'] != "")
													<div class="pb-2">Size<span class="ps-2">{{$val['size_dimension']}}</span></div>
													@endif
													@if(isset($val['shipping_text']) && $val['shipping_text'] != "")
													<div>{{$val['shipping_text']}}</div>
													@endif
													<div class="hidden-sm-up">
														<div class="cart_price">
															<div class="price"> <span class="special-price">{{Make_Price($val['TotPrice'],true) }}</span> <span class="old-price">{{Make_Price($val['oldTotPrice'],true) }}</span> </div>
														</div>
													</div>
												</div>
												<div class="qty hidden-xs-down">
													<div class="qty-text">Qty: {{ $val['Qty'] }}</div>
												</div>
												<div class="cart_price hidden-xs-down">
													<div class="price"> <span class="special-price">{{Make_Price($val['TotPrice'],true) }}</span> <span class="old-price">{{Make_Price($val['oldTotPrice'],true) }}</span> </div>
												</div>
											</div>
										</div>
									@endforeach
								@endif
							</div>
							<div class="row row5">
								<div class="col-sm-6 col-sm-push-6 py-1 py-sm-0 tar"><a href="#" class="btn btn-success f700 btn-xs-block">CONTINUE & REVIEW ORDER</a></div>
								<div class="col-sm-6 col-sm-pull-6 py-1 py-sm-0">
									<a href="#" class="btn btn-border btn-xs-block backtoshoppe-btn">
										<svg class="svg_left_arrow" aria-hidden="true" role="img" width="9" height="14">
											<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
										</svg>
										Back to Shoppe
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout_right">
					<div class="order_summary">
						<h2 class="tac">Order Summary</h2>
						<table class="ord_sum">
							<tbody>
								<tr>
									<td>Subtotal:</td>
									<td>${{$SubTotal}}</td>
								</tr>
								<tr>
									<td>Shipping:</td>
									<td>Free</td>
								</tr>
								@if($SalesTax > 0)
								<tr>
									<td>Sales Tax</td>
									<td>${{$SalesTax}}</td>
								</tr>
								@endif
								{{-- @if($auto_quantity_discount > 0)
								<tr>
									<td>Discount</td>
									<td>-${{$auto_quantity_discount}}</td>
								</tr>
								@endif --}}
								@if($AutoDiscount > 0)
								<tr>
									<td>Auto Discount</td>
									<td>-${{$AutoDiscount}}</td>
								</tr>
								@endif
								@if($QuantityDiscount > 0)
								<tr>
									<td>Quantity Discount</td>
									<td>-${{$QuantityDiscount}}</td>
								</tr>
								@endif
								@if($CouponDiscount > 0)
								<tr class="savings">
									<td>Coupon Discount:</td>
									<!--<td>-$150.00</td>-->
									<td>-${{$CouponDiscount}}</td>
								</tr>
								@endif
							</tbody>
							@if($Total_Amount > 0)
							<tfoot>
								<tr class="ord_total">
									<td>Total Amount:</td>
									<td>${{$Total_Amount}}</td>
								</tr>
							</tfoot>
							@endif
						</table>
					</div>
					@include('shoppingcart.needAssistance')
				
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
	</div>
</div>
@endsection