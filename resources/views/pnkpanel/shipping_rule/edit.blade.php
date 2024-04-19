@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.shipping-rule.update') }}" method="post" name="frmshippingrule" id="frmshippingrule" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="{{ $shipping_rule->shipping_rule_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" name="shipping_rule_id" value="{{$shipping_rule->shipping_rule_id}}">
	@csrf
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Shipping Rule</h2>
				</header>
				<div class="card-body">
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						<div class="form-group row align-items-center">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="state">Shipping Method</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="shipping_method" id="shipping_method" class="form-control form-control-modern"   >
                                
                                @foreach ($shipping_method as $shipping_method)
                                    <option value="{{ $shipping_method->shipping_mode_id }}"
                                        {{ old('shipping_method', $shipping_rule->shipping_mode_id) == $shipping_method->shipping_mode_id ? 'selected' : '' }} >
                                        {{ $shipping_method->shipping_title }}</option>
                                @endforeach
                            </select>
                            @error('shipping_method')
                                <label class="error" for="shipping_method" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
						<div class="form-group row align-items-center">
	                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="country">Country</label>
	                        <div class="col-lg-7 col-xl-6">
	                            <select name="country[]" id="country" class="form-control form-control-modern" multiple> 
	                                @foreach ($country as $country)
	                                    <option value="{{ $country->countries_iso_code_2 }}" @if (in_array($country->countries_iso_code_2, $shipping_country)) selected @endif
	                                       >
	                                        {{ $country->countries_iso_code_2 . ' ' . $country->countries_name }}</option>
	                                @endforeach
	                            </select>
	                            @error('country')
	                                <label class="error" for="country" role="alert">{{ $message }}</label>
	                            @enderror
	                        </div>
                      </div>
                       
                       <div class="form-group row align-items-center" id="divstate">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="state">States</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="state[]" id="state" class="form-control form-control-modern" multiple>
                                @foreach ($state as $state)
                                    <option value="{{ $state->code }}" @if (in_array($state->code, $shipping_state)) selected @endif >
                                        {{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <label class="error" for="state" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                      </div>
                       <div class="form-group row" id="divotherstate">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="state">State</label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('otherstate') error @enderror" id="otherstate" name="otherstate" value="{{ old('otherstate', $shipping_rule->state) }}">
								@error('state')
									<label class="error" for="otherstate" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
                      <div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="zipcode_from">Zipcode From</label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('zipcode_from') error @enderror" id="zipcode_from" name="zipcode_from" value="{{ old('zipcode_from', $shipping_rule->zipcode_from) }}">
								@error('zipcode_from')
									<label class="error" for="zipcode_from" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="zipcode_to">Zipcode To </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('zipcode_to') error @enderror" id="zipcode_to" name="zipcode_to" value="{{ old('zipcode_to', $shipping_rule->zipcode_to) }}">
								@error('zipcode_to')
									<label class="error" for="zipcode" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="rule_type">Charge Basis </label>
							<div class="col-lg-6">
								<input type="radio" class="@error('rule_type') error @enderror" id="no1" name="rule_type" value="0" onclick="radio_click_number()" checked {{ (old('rule_type', $shipping_rule->rule_type) == '0')?" checked ":"" }} > Charge Basis On Number Of Item
								@error('rule_type')
									<label class="error" for="rule_type" role="alert">{{ $message }}</label>
								@enderror
								<div class="row">
									<div class="col-lg-12">
									@for ($cb = 0; $cb < 30; $cb++)
								     Number Of Item <input type="text" class="form-control @error('order_amount') error @enderror" id="item<?=$cb;?>"  class="order_amount" name="order_amount[]" value="@if($cnt_shipping_rate >= $cb && $shipping_rule->rule_type == '0') {{ $shipping_rate[$cb]['order_amount']}}  @endif" style="width:100px;display:inline;margin:5px"> Charge is <input type="text" class="form-control @error('charge') error @enderror" id="chargeno<?=$cb;?>" class="charge" name="charge[]" value="@if($cnt_shipping_rate >= $cb && $shipping_rule->rule_type == '0') {{ $shipping_rate[$cb]['charge']}} @endif" style="width:100px;display:inline;margin:5px"><br>
									 @endfor
									</div>
								</div>
								@error('order_amount')
									<label class="error" for="order_amount" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="prop_item">Proportional Shipping Charge </label>
							<div class="col-lg-6">
								From<input type="text" class="form-control @error('prop_item') error @enderror" id="prop_item" name="prop_item" value="{{ old('prop_item', $shipping_rule->prop_item) }}" style="width:100px;display:inline;margin:5px">Item(s) <input type="text" class="form-control @error('prop_charge') error @enderror" id="prop_charge" name="prop_charge" value="{{ old('prop_charge', $shipping_rule->prop_charge) }}" style="width:100px;display:inline;margin:5px"> Charge is For each item
								@error('zipcode')
									<label class="error" for="zipcode" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="free_ship_amt">Free Shipping Starting Amount </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('free_ship_amt') error @enderror" id="free_ship_amt" name="free_ship_amt" value="{{ old('free_ship_amt', $shipping_rule->free_ship_amt) }}">
								@error('free_ship_amt')
									<label class="error" for="free_ship_amt" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="rule_type"></label>
							<div class="col-lg-6">
								<input type="radio" class="@error('rule_type') error @enderror" id="or1" name="rule_type" value="1" onclick="radio_click_number()" {{ (old('rule_type', $shipping_rule->rule_type) == '1')?" checked ":"" }}> Charge Basis On Order Amount
								@error('rule_type')
									<label class="error" for="rule_type" role="alert">{{ $message }}</label>
								@enderror
								<div class="row">
									<div class="col-lg-12">
									@for ($cb = 0; $cb < 30; $cb++)
								     Order Amount From<input type="text" class="form-control @error('order_amount') error @enderror" id="order<?=$cb;?>" name="order_amount[]" class="order_amount" value="@if($cnt_shipping_rate >= $cb && $shipping_rule->rule_type == '1') {{ $shipping_rate[$cb]['order_amount']}}  @endif" style="width:100px;display:inline;margin:5px"> Charge is <input type="text" class="form-control @error('charge') error @enderror" id="chargeor<?=$cb;?>" name="charge[]" class="charge" value="@if($cnt_shipping_rate >= $cb && $shipping_rule->rule_type =='1') {{ $shipping_rate[$cb]['charge']}}  @endif" style="width:100px;display:inline;margin:5px"><br>
									 @endfor
									</div>
								</div>
								@error('order_amount')
									<label class="error" for="order_amount" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						
				</div>
			</section>
		</div>
	</div>
	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="submit"
				class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> {{ $shipping_rule->shipping_rule_id > 0 ? 'Update' : 'Add' }} Shipping Rule </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($shipping_rule->shipping_rule_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
					data-id="{{ $shipping_rule->shipping_rule_id }}"
					class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
					<i class="bx bx-trash text-4 mr-2"></i> Delete Shipping Rule </a> </div>
			</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.shipping-rule.list',$id) }}";
        let url_edit = "{{ route('pnkpanel.shipping-rule.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.shipping-rule.update') }}";
        let url_delete = "{{ route('pnkpanel.shipping-rule.delete', ':id') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/shipping_rule_edit.js') }}"></script>
    <script>
        	$(window).on('load', function (e) {
			    	
				    var selectedCountry = $("#country").val();
				   	if(selectedCountry != 'US') {
						$("#divstate").hide();
				    	$("#divotherstate").show();
				    } else {
						$("#divstate").show();
				    	$("#divotherstate").hide();
					}
					});
    	$(document).ready(function() {
    			if (document.frmshippingrule.no1.checked==true)
					{ 
						for(texti=0;texti<30;texti++)
						{
							orderx = eval("document.frmshippingrule.order"+texti);
							orderx.disabled = true;
							numx = eval("document.frmshippingrule.item"+texti);
							numx.disabled = false;

							orderc = eval("document.frmshippingrule.chargeor"+texti);
							orderc.disabled = true;
							numc = eval("document.frmshippingrule.chargeno"+texti);
							numc.disabled = false;
						}
					}
					if (document.frmshippingrule.or1.checked==true)
					{
						for(texti=0;texti<30;texti++)
						{
							orderx = eval("document.frmshippingrule.order"+texti);
							orderx.disabled = false;
							numx = eval("document.frmshippingrule.item"+texti);
							numx.disabled = true;

							orderc = eval("document.frmshippingrule.chargeor"+texti);
							orderc.disabled = false;
							numc = eval("document.frmshippingrule.chargeno"+texti);
							numc.disabled = true;
						}
					}
				
					$('#country').on('change', function (e) {
						

					    var selectedCountry = $(this).val();
						//
						arrSelectedCountry = Array.from(selectedCountry);
						console.log(jQuery.inArray("US", arrSelectedCountry)!== -1);
						if(arrSelectedCountry.length > 1){
							if(jQuery.inArray("US", arrSelectedCountry)!== -1) {
								var wanted_option = $('#country option[value="US"]');
  								wanted_option.prop('selected', false);
							}	
						}
						var selectedCountry = $(this).val();
						arrSelectedCountry = Array.from(selectedCountry);
						if(jQuery.inArray("US", arrSelectedCountry)== -1) {
							$("#divstate").hide();
					    	$("#divotherstate").show();
					    } else {
							$("#divstate").show();
					    	$("#divotherstate").hide();
						}
					});
				});
    	function radio_click_number()
		{
			if (document.frmshippingrule.no1.checked==true)
			{
				for(texti=0;texti<30;texti++)
				{
					orderx = eval("document.frmshippingrule.order"+texti);
					orderx.disabled = true;
					numx = eval("document.frmshippingrule.item"+texti);
					numx.disabled = false;

					orderc = eval("document.frmshippingrule.chargeor"+texti);
					orderc.disabled = true;
					numc = eval("document.frmshippingrule.chargeno"+texti);
					numc.disabled = false;
				}
			}
			if (document.frmshippingrule.or1.checked==true)
			{
				for(texti=0;texti<30;texti++)
				{
					orderx = eval("document.frmshippingrule.order"+texti);
					orderx.disabled = false;
					numx = eval("document.frmshippingrule.item"+texti);
					numx.disabled = true;

					orderc = eval("document.frmshippingrule.chargeor"+texti);
					orderc.disabled = false;
					numc = eval("document.frmshippingrule.chargeno"+texti);
					numc.disabled = true;
				}

			}
		}
    </script>
@endpush
