@extends('layouts.app')
@section('content')
<?php
	//dd(Session::get()); exit;
	//dd(Session::get('ShoppingCart'));
	if(Session::has('sess_useremail') && (Session::get('sess_useremail') == "gequaldev@gmail.com" || Session::get('sess_useremail') == "qualdevcsteam@gmail.com" || Session::get('sess_useremail') == "qatesting@mailinator.com")){
		$checking = "no";
	}else{
		$checking = "yes";
	}
	//dd($Shipping);
?>
@php 
	$APP_URLS = config('const.APP_URLS');
@endphp
<div class="checkout">
	<div class="container">
		<div class="checkout_hd">
			<h1 class="h2">Secure Checkout</h1>
		</div>		
		<div class="ck-step">
			<a href="{{config('const.SITE_URL')}}/cart" title="Cart">Cart<svg class="svg_barrow" width="23" height="23" aria-hidden="true" role="img" loading="lazy"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<a href="javascript:void(0);#" title="Shipping/Billing Information">Shipping/Billing Information<svg class="svg_barrow" width="23" height="23" aria-hidden="true" role="img" loading="lazy"><use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use></svg></a> 
			<span class="active">Payment</span>
		</div>
		<div class="checkout_row">
			<div class="checkout_left">
				<div class="checkout-form">
				{{--
				@guest
					@include('shoppingcart.CheckOut_Login')
				@endguest
				--}}
				
				
				
				@guest
				<p class="f16">Already have an account? Click here to <a href="javascript:void(0);" class="tdu loginpopup" title="Sign in">Sign in</a> Or <a href="javascript:void(0);" onclick="return show_user_register_popup();" class="tdu" title="Create an Account">Create an Account</a></p>
				@endguest
					
					@if(Session::has('error_msg'))
						<div class="alert alert-danger border-left-danger alert-dismissible fade show mb-5" role="alert">
							<i class="mdi mdi-block-helper me-2"></i>
							{!! Session::get('error_msg') !!}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" title="Close">
								<span aria-hidden="true"></span>
							</button>
						</div>
					@endif
					@if(isset($msg) && $msg != "")
					<div class="alert alert-danger border-left-danger alert-dismissible fade show mb-5" role="alert">
						<i class="mdi mdi-block-helper me-2"></i>
						<div class="text-left errmsg pb-3">{{ $msg }}</div>
					</div>
					@endif
					<form id="frmCheckOut" name="frmCheckOut" action="{{config('const.SITE_URL')}}/checkout-actiononcart" method="POST"novalidate="novalidate">
					{{ csrf_field() }}			
					<input type="hidden" name="action" value="">
					<input type="hidden" name="CartLength" value="<?php echo $CartLength; ?>">
					<input type="hidden" name="checking" id="checking" value="<?php echo $checking; ?>">
					<input type="hidden" name="avalara_valid_cnt" id="avalara_valid_cnt" value="1">
					<input type="hidden" name="paypalec" id="paypalec" value="@if(isset($paypalec)){{ $paypalec }}@endif">	
					<input type="hidden" name="payment_method" id="payment_method" value="">	
					<input type="hidden" name="is_customer_login" id="is_customer_login" value="{{$is_customer_login}}">
					<input type="hidden" name="is_user_confirm_guest_checkout" id="is_user_confirm_guest_checkout" value="0">
					<input type="hidden" name="bt_payment_method_nonce" id="bt_payment_method_nonce" value="{{$bt_express_payment_method_nonce ?? ""}}" /> 
					<input type="hidden" name="is_bt_express_checkout" id="is_bt_express_checkout" value="{{$is_bt_express_checkout ?? ""}}" />
					<input type="hidden" name="sam_as_shipp_add" id="sam_as_shipp_add" value="<?php echo $IsBillingAsShipping; ?>">	
					<input type="hidden" name="ga_order_total_amount" id="ga_order_total_amount" value="{{$Total_Amount}}">
					@if($Shipping['email'] == 'gequaldev@gmail.com' || $Shipping['email'] == 'qqualdev@gmail.com' || $Shipping['email'] == 'qualdev.support@gmail.com' || $Shipping['email'] == 'qatesting@mailinator.com' || $Shipping['email'] == 'hitumc_1348752712_per@gmail.com')
						<input type="hidden" name="BRAINTREE_TOKENIZATION_KEY" id="BRAINTREE_TOKENIZATION_KEY" value="sandbox_6md674h5_j5vyzpwpfzvfk597" />
					@else
						<input type="hidden" name="BRAINTREE_TOKENIZATION_KEY" id="BRAINTREE_TOKENIZATION_KEY" value="{{$bt_api_details->BRAINTREE_TOKENIZATION_KEY ?? ""}}" />
					@endif
					<input type="hidden" name="BRAINTREE_GOOGLE_MERCHANT_ID" id="BRAINTREE_GOOGLE_MERCHANT_ID" value="{{$bt_api_details->BRAINTREE_GOOGLE_MERCHANT_ID ?? ""}}" /> 
						<div class="checkout-acd active" id="shipping-acd">
							<div class="title">
								<h3 aria-hidden="true">Shipping Address</h3>
								<svg class="svg_left_arrow shipping_arrow" id="shipping_edit_link" aria-hidden="true" role="img" width="13" height="19" loading="lazy" onclick="edit_checkout_step('shipping');">
									<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
								</svg>
							</div>							
							<p class="mb-0 mt-2">Where should we ship it?</p>
							<div id="shipping_form_section">
								<div class="pb-1 errmsg" id="av_tax_error"></div>
								<div class="pt-2">
									<label for="bl_sh_email" class="form-label">Email Address <span class="red-color">*</span></label>
									<input type="text" class="form-control" id="bl_sh_email" name="bl_sh_email" value="{{$Shipping['email']}}" placeholder="Email *" required @auth readonly @endauth>
									<div class="error error-cls" id="error_bl_sh_email"></div>
								</div>
								<div class="row row10">
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_fname" class="form-label">First Name <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="sh_fname" name="sh_fname" value="{{ $Shipping['first_name'] }}" placeholder="First Name *" required />
										<div class="error error-cls" id="error_sh_fname"></div>
									</div>
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_lname" class="form-label">Last Name <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="sh_lname" name="sh_lname" value="{{ $Shipping['last_name'] }}" placeholder="Last Name *" required />
										<div class="error error-cls" id="error_sh_lname"></div>
									</div>
								</div>
								<div class="row row10">
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_Addr1" class="form-label">Address 1 <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="sh_Addr1" name="sh_Addr1" value="{{ $Shipping['address1'] }}" placeholder="Address 1 *" required />
										<div class="error error-cls" id="error_sh_Addr1"></div>
									</div>
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_Addr2" class="form-label">Address 2</label>
										<input type="text" class="form-control" id="sh_Addr2" name="sh_Addr2" value="{{ $Shipping['address2'] }}" placeholder="Address 2" />
									</div>
								</div>
								<div class="row row10">
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_city" class="form-label">City <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="sh_city" name="sh_city" value="{{ $Shipping['city'] }}" placeholder="City *" required />
										<div class="error error-cls" id="error_sh_city"></div>
									</div>
									<div class="col-xs-12 col-sm-6 pt-3">
										<label for="sh_country" class="form-label">Select Country <span class="red-color">*</span></label>
										<select class="form-select" id="sh_country" name="sh_country" placeholder="Select Country" onchange="ship_statecheck(); Ajax_GetShipMethod();" required>
											<option value="">Select Country</option>
											@foreach($aCountry as $countryShort => $countryLong)
											<option value="{{ $countryShort }}" @if($Shipping["country"] == $countryShort) selected @endif>{{ $countryLong }}</option>
											@endforeach
										</select>
										<div class="error error-cls" id="error_sh_country"></div>
									</div>
								</div>
								<div class="row row10">
									<div class="col-xs-12 col-sm-4 pt-3">
										<label for="sh_state" class="form-label">State <span class="red-color">*</span></label>
										<select class="form-select" id="sh_state" name="sh_state" placeholder="Select State" onchange="Ajax_GetShipMethod();" required>
										<option value="" selected>Select State</option>
											@foreach($aState as $stateShort => $stateLong)
												<option value="{{ $stateShort }}" @if($Shipping["state"] == $stateShort) selected @endif >{{ $stateLong }}</option>
											@endforeach
										</select>
										<div class="error error-cls" id="error_sh_state"></div>
										<input type="text" name="sh_otherstate" id="sh_otherstate" value="{{ $Shipping['state'] }}" placeholder="State" class="form-control" />
										<div class="error error-cls" id="error_sh_otherstate"></div>
									</div>
									<div class="col-xs-12 col-sm-4 pt-3">
										<label for="sh_zip" class="form-label">Zip Code <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="sh_zip" name="sh_zip" value="{{ $Shipping['zip'] }}" placeholder="Zip Code *" onkeypress="Ajax_GetShipMethod()" required />
										<div class="error error-cls" id="error_sh_zip"></div>
									</div>
									<div class="col-xs-12 col-sm-4 pt-3">
										<label for="sh_phone" class="form-label">Phone Number <span class="red-color">*</span></label>
										<div class="input-group-nouseclass"> {{-- <span class="input-group-text">+1</span> --}}
											<input type="number" class="form-control" id="sh_phone" name="sh_phone"  value="{{ $Shipping['phone'] }}" placeholder="Phone Number" />
										</div>
										<div class="error error-cls" id="error_sh_phone"></div>
										<span class="ex-label">Used for order notifications</span>
									</div>
								</div>
								<div class="switchbox mt-3">
									<label class="flipswitch" for="is_newsletter" aria-hidden="true">
										<input tabindex="-1" type="checkbox" class="flipswitch-input" id="is_newsletter" name="is_newsletter"/> 
										<span class="flipswitch-slider"></span>
									</label>  Sign up for emails now for our latest sales, new arrivals & special offers.
								</div>
								<a href="javascript:void(0);" class="btn ctbtn" aria-label="Save & Continue" title="Save & Continue" onClick="valid_shipping_detail();">Save & Continue</a>
							</div>
							<div id="shipping_info_section" class="dnone">
								<div id="shipping_info_disp" class="pt-4"></div>
								<div id="shipping-method-filled-section"></div>
							</div>							
						</div>					
						<div class="checkout-acd" id="billing-acd">
							<div class="title">
								<h3 aria-hidden="true">Billing Address</h3>
								<svg class="svg_left_arrow billing_arrow" aria-hidden="true" role="img" width="13" height="19" loading="lazy" onclick="edit_checkout_step('billing');">
										<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
								</svg>
							</div>
							<div id="billing_form_section" class="dnone">
								<div id="billing_adddress_box" style="display:none;">
									<div class="switchbox mt-4">
										<label class="flipswitch"> <input type="checkbox" class="flipswitch-input" name="same_asship" id="same_asship" <?php if($IsBillingAsShipping == 'yes'){ ?> checked <?php } ?>  onclick="show_billing_address();" /> <span class="flipswitch-slider"></span> </label> Billing address is the same as the shipping address
									</div>
									<div class="row row10">
										<div class="col-xs-12 col-sm-6 pt-3">
											<label for="bl_fname" class="form-label">First Name <span class="red-color">*</span></label>
											<input type="text" class="form-control" id="bl_fname" name="bl_fname" value="{{ $Billing['first_name'] }}" placeholder="First Name *" required />
											<div class="error" id="error_bl_fname"></div>
										</div>
										<div class="col-xs-12 col-sm-6 pt-3">
											<label for="bl_lname" class="form-label">Last Name <span class="red-color">*</span></label>
											<input type="text" class="form-control" id="bl_lname" name="bl_lname" value="{{ $Billing['last_name'] }}" placeholder="Last Name *" required />
											<div class="error" id="error_bl_lname"></div>
										</div>
									</div>
									<div class="pt-3">
										<label for="bl_Addr1" class="form-label">Address 1 <span class="red-color">*</span></label>
										<input type="text" class="form-control" id="bl_Addr1" name="bl_Addr1" value="{{ $Billing['address1'] }}" placeholder="Address 1 *" required />
										<div class="error" id="error_bl_Addr1"></div>
									</div>
									<div class="pt-3">
										<label for="bl_Addr2" class="form-label">Address 2</label>
										<input type="text" class="form-control" name="bl_Addr2" id="bl_Addr2" value="{{ $Billing['address2'] }}" placeholder="Apt/Suite #" />
									</div>
									<div class="row row10">
										<div class="col-xs-12 col-sm-6 pt-3">
											<label for="bl_city" class="form-label">City <span class="red-color">*</span></label>
											<input type="text" class="form-control" id="bl_city" name="bl_city" value="{{ $Billing['city'] }}" placeholder="City *" required />
											<div class="error" id="error_bl_city"></div>
										</div>
										<div class="col-xs-12 col-sm-6 pt-3">
											<label for="bl_country" class="form-label">Select Country <span class="red-color">*</span></label>
											<select class="form-select" id="bl_country" name="bl_country" placeholder="Select Country"  onchange="Ajax_GetShipMethod(); bill_statecheck();" required>
												<option value="">Select Country</option>
												@foreach($aCountry as $countryShort => $countryLong)
												<option value="{{ $countryShort }}" @if($Billing["country"] == $countryShort) selected @endif>{{ $countryLong }}</option>
												@endforeach
											</select>
											<div class="error" id="error_bl_country"></div>
										</div>
									</div>
									<div class="row row10">
										<div class="col-xs-12 col-sm-4 pt-3">
											<label for="bl_state" class="form-label">State <span class="red-color">*</span></label>
											<select class="form-select" id="bl_state" name="bl_state" placeholder="Select State" onchange="Ajax_GetShipMethod();" required>
												<option value="" selected>Select State</option>
												@foreach($aState as $stateShort => $stateLong)
													<option value="{{ $stateShort }}" @if($Billing["state"] == $stateShort) selected @endif >{{ $stateLong }}</option>
												@endforeach
											</select>
											<input type="text" name="bl_otherstate" id="bl_otherstate" value="{{ $Billing['state'] }}" placeholder="State" class="form-control" />
											<div class="error" id="error_bl_state"></div>
											<div class="error" id="error_bl_otherstate"></div>
										</div>
										<div class="col-xs-12 col-sm-4 pt-3">
											<label for="bl_zip" class="form-label">Zip Code <span class="red-color">*</span></label>
											<input type="text" class="form-control" id="bl_zip" name="bl_zip" value="{{ $Billing['zip'] }}" placeholder="Zip Code *" onkeypress="Ajax_GetShipMethod()" required />
											<div class="error" id="error_bl_zip"></div>
										</div>
										<div class="col-xs-12 col-sm-4 pt-3">
											<label for="bl_phone" class="form-label">Phone Number <span class="red-color">*</span></label>
											<div class="input-group"> {{-- <span class="input-group-text">+1</span> --}}
												<input type="number" class="form-control" id="bl_phone" name="bl_phone" value="{{ $Billing['phone'] }}" placeholder="Phone Number" />
												<div class="error" id="error_bl_phone"></div>
											</div>
											<span class="ex-label">Used for order notifications</span>
										</div>
									</div>
									<a href="javascript:void(0);" class="btn ctbtn" aria-label="Save & Continue" title="Save & Continue" onclick="valid_billing_detail();">Save & Continue</a>
									
								</div>
							</div>
							<div <?php if($IsBillingAsShipping == 'yes'){ ?> <?php }else{ ?> class="dnone" <?php } ?> id="billing_info_section">
								<div id="billing_info_disp" class="pt-4"></div>
							</div>
						</div>
						
						<div class="shipping-method checkout-acd">

                                <div class="title">
									<h3 aria-hidden="true">Shipping Method <svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19" loading="lazy" onClick="edit_checkout_step('shipping-method');">
                                            <use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
                                        </svg></h3>
                                   
                                    <div class="securetxt">
                                        <svg class="svg_method" aria-hidden="true" role="img" width="16" height="17" loading="lazy" onClick="edit_checkout_step('shipping-method');">                     <use href="#svg_method" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_method"></use></svg> Shipping Information</div>
                                </div>
                                
                                <div class="f14 pt-4 dnone shipping-expected-delivery">
                                        <span class="dblock"><strong>Expected Delivery</strong></span>
										<p class="f12 f400" id="shipping_method_disp"></p>
                                    </div>
                                
                                <div class="payment-acd dnone shipping-method-content-js">
                                    <div class="content">
                                        <div class="inner">
                                            <div class="ship-method">
											<div class="error-cls" id="error_shippingMethod"></div>
											
											<div class="shipping-method" id="shipping-methods"></div>
                                                <!-- <div class="ship-time">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked="">
                                                        <label class="form-check-label" for="gridRadios1"> <strong>Free 2nd Day Air</strong></label>
                                                    </div>
                                                </div>
                                                <div class="ship-time">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                                                        <label class="form-check-label" for="gridRadios1"> $25 - Next Day Air</label>
                                                    </div>
                                                </div>
                                                <div class="ship-time">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                                                        <label class="form-check-label" for="gridRadios1"> $40 - Saturday Delivery</label>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="pt-5">
										<a href="javascript:void(0);" class="btn btn-success ctbtn" aria-label="Continue" title="Continue" onclick="valid_shipping_method();">Continue</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


						<div class="checkout-acd" id="payment_method">
							<div class="title">
								<h3 aria-hidden="true">Payment Method</h3>
								<div class="f12"><svg class="svg_lock me-1" width="12px" height="14px" aria-hidden="true" fill="none" role="img" loading="lazy"><use href="#svg_lock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_lock"></use></svg>Secure and Encrypted</div>
								<svg class="svg_left_arrow" aria-hidden="true" role="img" width="13" height="19" loading="lazy" onClick="edit_checkout_step('payment-method');"><use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use></svg>
							</div>
							<div class="error-cls" id="error_paymentMethod"></div>
							<div class="checkout-pmact dnone" id="payment-form-section">
							{!! $paymentMethodHtml !!}
								{{-- <div class="checkout-pmact-loop" data-paymnet-id="1"  data-paymnet-method="">
									<div class="checkout-pmact-hd">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="payment_method" data-paymnet-id="1" id="CREDIT_CART" value="CREDIT_CART" checked="checked" onClick="change_payment_method(this)">
											<label class="form-check-label" for="credit_cart"> Credit Card</label>
										</div>
										<picture><img src="images/card_icon.png" alt="Credit Card" title="Credit Card" width="150" height="24" loading="lazy"/></picture>
									</div>
									<div class="checkout-pmact-cont">
										<form method="post">
											<div class="">
												<label for="Card-Number" class="form-label">Card Number <span class="red-color">*</span></label>
												<input type="text" class="form-control" id="Card-Number" value="" required="">
												<div class="invalid-feedback">Please provide a valid card number.</div>
											</div>
											<div class="pt-3">
												<label for="Card-Name" class="form-label">Name On Card <span class="red-color">*</span></label>
												<input type="text" class="form-control" id="Card-Name" value="" required="">
												<div class="invalid-feedback">Please provide a valid name on card.</div>
											</div>
											<div class="row row10">
												<div class="col-xs-12 col-sm-6 pt-3">
													<label for="pm-month" class="form-label">Expiration Date (MM/YY)</label>
														<select class="form-select" id="pm-month" required="">
															<option value=''>Month</option>
															<option value='1'>Janaury</option>
															<option value='2'>February</option>
															<option value='3'>March</option>
															<option value='4'>April</option>
															<option value='5'>May</option>
															<option value='6'>June</option>
															<option value='7'>July</option>
															<option value='8'>August</option>
															<option value='9'>September</option>
															<option value='10'>October</option>
															<option value='11'>November</option>
															<option value='12'>December</option>
														</select>													
												</div>
												<div class="col-xs-12 col-sm-6 pt-3">									
													<div class="check_csc">
														<label for="Security Code" class="form-label">Security Code</label>
														<input type="text" class="form-control" id="Security Code" value="" required="">
														<div class="invalid-feedback">Please provide a valid security code.</div>
														<picture><img src="images/cart_csc.png" alt="" width="33" loading="lazy"/></picture>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="checkout-pmact-loop" data-paymnet-id="2" data-paymnet-method="">
									<div class="checkout-pmact-hd">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="payment_method" id="PAYPAL" data-paymnet-id="2" value="PAYPAL" onClick="change_payment_method(this)">
											<label class="form-check-label" for="PAYPAL"> Paypal</label>
										</div>
										<picture><img src="images/check_paypal.png" alt="Paypal" title="Paypal" width="74" height="20" loading="lazy" /></picture>
									</div>
									<div class="checkout-pmact-cont">test</div>
								</div>
								<div class="checkout-pmact-loop" data-paymnet-id="3" data-paymnet-method="">
									<div class="checkout-pmact-hd">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="payment_method" id="AFFIRM" data-paymnet-id="3" value="AFFIRM" onClick="change_payment_method(this)">
											<label class="form-check-label" for="AFFIRM"> Affirm</label>
										</div>
										<picture><img src="images/affirm.png" alt="Affirm" title="Affirm" width="65" height="26" loading="lazy" /></picture>
									</div>
									<div class="checkout-pmact-cont">test</div>
								</div> 
								<button class="btn ctbtn" type="submit" title="Place Order">Place Order</button> --}}
							</div>
							
						</div>
					</form>
				</div>
				<div class="ch-contact">
					<div class="ch-contact-inr">
						<svg class="svg-email" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
							<use href="#svg-email" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-email"></use>
						</svg>
						<a href="mailto:{{ config('Settings.CONTACT_MAIL') }}" title="Email Us" class="linksbb">Email Us</a>
					</div>
					<div class="ch-contact-inr">
						<svg class="svg-phone" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
							<use href="#svg-phone" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-phone"></use>
						</svg>
						<a href="tel:{{ config('Settings.TOLL_FREE_NO') }}" title="Call US" class="linksbb">{{ config('Settings.TOLL_FREE_NO') }}</a>
					</div>
					<div class="ch-contact-inr">
						<svg class="svg-chat" aria-hidden="true" role="img" width="27" height="27" loading="lazy">
							<use href="#svg-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chat"></use>
						</svg>
						<a href="javascript:void(0);" onclick="openWidget()" title="Live Chart" class="linksbb">Live Chat</a>
					</div>
				</div>
			</div>
			
			<div class="checkout_right">
				@include('shoppingcart.Free_Shipping')
				<div class="order_summary">
					<div class="h2">Order Summary</div>

					<div class="yr-item">
						<div class="title active">
							<a href="javascript:void(0);" title="Your {{$TotalItemInCart}} Items" class="arrow_down">Your {{$TotalItemInCart}} Items <svg class="svg_left_arrow" aria-hidden="true" role="img" width="10" height="15" loading="lazy">
									<use href="#svg_left_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_left_arrow"></use>
								</svg>
							</a>	
						</div>
						@if($CartLength > 0)
							<div id="all-product-remove">
								<ul class="Cart-items-remove">
								@foreach($Cart as $key => $val)
									<li>
										<a href="{{$APP_URLS.$val['product_url']}}" title="{{ $val['ProductName'] }}">
											<picture><img src="{{ $val['Image'] }}" alt="{{ $val['ProductName'] }}" title="{{ $val['ProductName'] }}" width="60" height="60" loading="lazy"></picture>
										</a>
									</li>
									@endforeach
								</ul>
							</div>						
						@endif
						@if($CartLength > 0)
						<div class="dnone" id="all-product-add">
							<ol class="Cart-items">
								@foreach($Cart as $key => $val)
								<input type="checkbox" name="ch_{{ $loop->index }}" value="{{ $loop->index }}" style="display:none;">								
								<li>
								<div class="product">
									<a href="{{$APP_URLS.$val['product_url']}}" class="product_thumb" title="{{ $val['ProductName'] }}"><picture><img src="{{ $val['Image'] }}" alt="{{ $val['ProductName'] }}" title="{{ $val['ProductName'] }}" width="90" height="90" loading="lazy"></picture></a>
									<div class="product_name"><a href="{{$APP_URLS.$val['product_url']}}" class="name text_c1" title="{{ $val['ProductName'] }}">{{ $val['ProductName'] }}</a></div>
									<div class="product_sku pt-1"><strong class="pe-2">SKU:</strong>{{ $val['SKU'] }}</div>
									
									@if (isset($val['flavour']) && $val['flavour'] != "")
										<div class="product_qty">@if(isset($val['attribute_3']) && $val['attribute_3'] != "") <b>{{$val['attribute_3']}}:</b> @endif {{$val['flavour']}}</div>
									@endif
									
									@if (isset($val['size_dimension']) && $val['size_dimension'] != "")
										<div class="product_qty">@if(isset($val['attribute_1']) && $val['attribute_1'] != "") <b>{{$val['attribute_1']}}:</b> @endif {{$val['size_dimension']}}</div>
									@endif
									
									@if (isset($val['pack_size']) && $val['pack_size'] != "")
										<div class="product_qty">@if(isset($val['attribute_2']) && $val['attribute_2'] != "") <b>{{$val['attribute_2']}}:</b> @endif {{$val['pack_size']}}</div>
									@endif
									
									@if(isset($val['shipping_text']) && $val['shipping_text'] != "")
									<div class="mb-3">{{$val['shipping_text']}}</div>
									@endif
									{{--<a href="javascript:void(0);" class="f12 text_c1 tdu itemremove pt-2" data-index="{{ $loop->index }}" title="Remove"> Remove </a>--}}
									<div class="product_price"><span class="special-price">{{Make_Price($val['TotPrice'],true) }}</span></div>
								</div>
								</li>
								@endforeach
							</ol>
						</div>
						@endif
					</div>
					<hr>
					<div id="OrderSummaryDiv">
						@include('shoppingcart.checkout-order-summary')
					</div>	
					<hr>
					<div class="proced-msec hidden-md-up">
						<div class="row row10">
							<div class="col-xs-4"> <span>{{$TotalItemInCart}} items</span> </div>
							<div class="col-xs-8 tar"> <strong>Subtotal: {{$CurencySymbol}}{{$SubTotal}}</strong> </div>
						</div>
					</div>
					<div class="coupon-code coupon-code-checkout">
						<div class="h4">coupon code</div>
						<div class="coupon-content"> 
							<div class="coupon-inner">
								<form action="" method="post" onsubmit="applyCoupon();return false;">
								@csrf
									<label for="coupon" class="dnone">Coupon code</label>
									<div class="input-group">
										<input type="text" aria-label="Coupon code" class="form-control" id="coupon" @if($CouponCode!='' || Session::get('ShoppingCart.CouponCode') != "") value="{{Session::get('ShoppingCart.CouponCode')}}" readonly @endif  name="coupon">
										<button class="btn btn-border ttu" type="button" title="Remove" id="coupon_remove" onclick="removeCoupon();" @if($CouponCode=='') style="display: none;" @endif >Remove</button>
										<button class="btn btn-border ttu" type="button" title="Apply" id="coupon_apply" onclick="applyCoupon();" @if($CouponCode!='') style="display: none;" @endif>Apply</button>
									</div>
									<div class="frmerror error" role="alert" id="coupon_alert" style="display: none;">Pleae enter valid coupon code</div>
								</form>
							</div>
						</div>
					</div>
					<p class="f14 tac mt-5">By placing an order, you agree to the {{ config('const.SITE_NAME')}} <a href="{{ config('const.SITE_URL') }}/pages/terms-and-condition" class="tdu" title="Terms & Conditions">Terms & Conditions</a> and <a href="{{ config('const.SITE_URL') }}/pages/privacy-policy" class="tdu" title="Privacy Policy">Privacy Policy</a></p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<script src="https://js.braintreegateway.com/web/3.86.0/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.86.0/js/hosted-fields.min.js"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.33.4/js/dropin.min.js"></script>



@if(isset($GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA ) && $GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA != "")
<script>
  window.dataLayer = window.dataLayer || [];
  {!! $GA4_GOOGLE_BEGIN_CHECKOUT_EVENT_DATA !!}
</script>	
@endif

<style>
.coupon-code .cc
{
    color: var(--black);
    cursor: pointer;
    font-size: 14px;
    text-transform: capitalize;
    padding: 25px;
    position: relative;
    font-weight: normal;
}
</style>
@endsection