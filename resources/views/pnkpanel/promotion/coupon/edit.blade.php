@extends('pnkpanel.layouts.app')
@section('content')
@php
	if($coupon->start_date != '0000-00-00' && $coupon->start_date != '') {
		$start_date = \Carbon\Carbon::parse($coupon->start_date)->format('m/d/Y');
	} else {
		$start_date = '';
	}
	if($coupon->end_date != '0000-00-00' && $coupon->end_date != '') {
		$end_date = \Carbon\Carbon::parse($coupon->end_date)->format('m/d/Y');
	} else {
		$end_date = '';
	}
	$sku_arr = array();
	if($coupon->sku != ''){
		$sku_arr = explode(",",$coupon->sku);
	}
	

	$orders = $coupon->orders;

	// $categories = [
	// 		'1' => 'Test Data',
	// 		'96' => 'Accessories',
	// 		'108' => 'Adhesive',
	// 		'298' => 'Hardwood',
	// 		'241' => 'LVT',
	// 		'239' => 'Multi-Puspose',
	// 		'240' => 'Vinyl Flooring',
	// 		'103' => 'Hardwood',
	// 		'104' => 'Combination Base',
	// 		'109' => 'Quarter Round',
	// 		'110' => 'Reducer'
	// 	];

	// $arr_category_id = explode(",",$coupon->sku);
	//$categories = array();
	$arr_category_id = array();
	$brands = [
		'1' => 'Test Data',
		'39' => 'Accessories',
		'93' => 'American Olean',
		'98' => 'Anderson',
		'127' => 'Armstrong',
		'71' => 'Armstrong Commercial',
		'34' => 'Armstrong Hardwood',
		'35' => 'Armstrong Laminate',
		'36' => 'Armstrong Lvt',
		'64' => 'Armstrong Vct',
		'37' => 'Armstrong Vinyl'
	];

	$arr_brand_id = explode(",",$coupon->sku);

	$shippingMethods = [
		'1' => 'Home Delivery',
		'2' => 'Terminal Pick-up',
		'3' => 'Ground Shipping',
		'4' => 'Home Delivery',
		'5' => 'Terminal Pick-up',
		'6' => 'Home Delivery',
		'7' => 'Terminal Pick-up',
		'8' => 'Home Delivery',
		'9' => 'Terminal Pick-up'
	];
@endphp
<form action="{{ route('pnkpanel.coupon.update') }}" method="post" name="frmCoupon" id="frmCoupon" class="ecommerce-form action-buttons-fixed">
<input type="hidden" name="id" value="{{$coupon->coupon_id}}">
<input type="hidden" name="actType" id="actType" value="{{ $coupon->coupon_id > 0 ? 'update' : 'add' }}">
@csrf
	<div class="row">
		<div class="col">
			<section class="card">

				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Discount Coupon Settings</h2>
				</header>
				
				<div class="card-body">
					
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="coupon_title">Coupon Title</label>
							<div class="col-lg-6">
								<input type="text" name="coupon_title" id="coupon_title" value="{{ old('coupon_title', $coupon->coupon_title) }}" size="255" class="form-control form-control-modern" />
								@error('first_name')
								<label class="error" for="parent_id" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="coupon_number">Coupon Code <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" name="coupon_number" id="coupon_number" value="{{ old('coupon_number', $coupon->coupon_number) }}" size="50" class="form-control form-control-modern @error('coupon_number') error @enderror" />
								@error('coupon_number')
									<label class="error" for="coupon_number" role="alert">{{ $message }}</label>
								@enderror
								<span class="help-block" id="gcn"><a href="javascript:void(0);">Generate Coupon Code</a></span>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="discount">Coupon Discount<span class="required">*</span></label>
							<div class="col-lg-3 col-xl-3">
								<input type="text" name="discount" id="discount" value="{{ old('discount', $coupon->discount) }}" size="50" class="form-control form-control-modern @error('discount') error @enderror" {{ (old('orders', $coupon->orders) == "4") ? "disabled" :  "" }} />
								@error('discount')
									<label class="error" for="discount" role="alert">{{ $message }}</label>
								@enderror
							</div>
							<div class="col-lg-3 col-xl-3">
			                    <select name="type" class="form-control form-control-modern">
			                      <option value='1' {{ (old('type', $coupon->type)=='1')? "selected" : "" }}>Percent</option>
			                      <option value='0' {{ (old('type', $coupon->type)=='0')? "selected" : "" }}>Amount</option>
								</select>
							</div>
						</div>

						<div class="form-group row">							
							<div class="col-lg-3 col-xl-3 text-lg-right">
								<label class="control-label pt-2" for="order_amount">Order Amount<span class="required">*</span></label>
								<div class="radio-custom radio-inline radio-primary mb-2">
									<input name="orders" id="orders0" type="radio" value="0" {{ (old('orders', $coupon->orders) == "0" || $coupon->orders == "") ? "checked" :  "" }} />
									<label for="super_admin"></label>
								</div>
							</div>
							<div class="col-lg-4 col-xl-4 ordersF" id="orders_field_0">
								<input type="text" name="order_amount" id="order_amount" value="{{ ($coupon->orders == '0') ? old('order_amount', $coupon->order_amount) : '' }}" size="50" class="form-control form-control-modern" {{ (old('orders', $coupon->orders) == "0" || $coupon->orders == "") ? "" :  "disabled" }} />
								@error('order_amount')
									<label class="error" for="order_amount" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							
							<div class="col-lg-3 col-xl-3 text-lg-right">
								<label class="control-label pt-2" for="category_id">Category<span class="required">*</span></label>
								<div class="radio-custom radio-inline radio-primary mb-2">
									<input name="orders" id="orders3" type="radio" value="3" {{ (old('orders', $coupon->orders) == "3") ? "checked" :  "" }}  />
									<label for="sub_admin"></label>
								</div>
							</div>
							<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_3">
								<select name="category_id[]" id="category_id" class="form-control form-control-modern" multiple="multiple" {{ (old('orders', $coupon->orders) == "3") ? "" :  "disabled" }}>
									<option value="">Select Category</option>
									@foreach($categories as $key_category => $value_category)
										<option value="{{$value_category['category_id']}}" {{(in_array($value_category['category_id'],$sku_arr) ? 'selected' : '')}} >{{$value_category['category_name']}}</option>
									@endforeach
									{{-- @foreach($categories as $key_category => $value_category)
										<option value="{{ $key_category }}" {{ ( (old('category_id', $coupon->category_id) == $key_category || in_array($key_category,$arr_category_id)) && ($coupon->orders==3) ? 'selected' : '') }}>{{ $value_category }}</option>
									@endforeach --}}
								</select>
								@error('category_id')
									<label class="error" for="category_id" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row" style="display: none;">
							
							<div class="col-lg-3 col-xl-3 text-lg-right">
								<label class="control-label pt-2" for="brand_id">Manufacture<span class="required">*</span></label>
								<div class="radio-custom radio-inline radio-primary mb-2">
									<input name="orders" id="orders5" type="radio" value="5" {{ (old('orders', $coupon->orders) == "5") ? "checked" :  "" }}  />
									<label for="sub_admin"></label>
								</div>
							</div>
							<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_5">
								<select name="brand_id[]" id="brand_id" class="form-control form-control-modern" multiple="multiple" {{ (old('orders', $coupon->orders) == "5") ? "" :  "disabled" }} >
									<option value="">Select Manufacture</option>
									@foreach($brands as $key_brand => $value_brand)
										<option value="{{ $key_brand }}" {{ ( (old('brand_id', $coupon->brand_id) == $key_brand || in_array($key_brand,$arr_brand_id)) && ($coupon->orders==5) ? 'selected' : '') }}>{{ $value_brand }}</option>
									@endforeach
								</select>
								@error('brand_id')
									<label class="error" for="brand_id" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							
							<div class="col-lg-3 col-xl-3 text-lg-right">
								<label class="control-label pt-2" for="sku">Products SKU<span class="required">*</span></label>
								<div class="radio-custom radio-inline radio-primary mb-2">
									<input name="orders" id="orders1" type="radio" value="1" {{ (old('orders', $coupon->orders) == "1") ? "checked" :  "" }}  />
									<label for="sub_admin"></label>
								</div>
							</div>
							<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_1">
								<textarea class="form-control" rows="3" id="sku" name="sku" {{ (old('orders', $coupon->orders) == "1") ? "" :  "disabled" }}><? if($orders=="1") { echo $coupon->sku; } ?></textarea>
								@error('sku')
									<label class="error" for="sku" role="alert">{{ $message }}</label>
								@enderror
								<span class="help-block">Note : Multiple Products SKU can be filled using comma(,) separated.</span>
							</div>
						</div>

						<div class="form-group row">
							
							<div class="col-lg-3 col-xl-3 text-lg-right">
								<label class="control-label pt-2" for="shipping_mode_id">Free Shipping<span class="required">*</span></label>
								<div class="radio-custom radio-inline radio-primary mb-2">
									<input name="orders" id="orders4" type="radio" value="4" {{ (old('orders', $coupon->orders) == "4") ? "checked" :  "" }}  />
									<label for="sub_admin"></label>
								</div>
							</div>
							<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_4">
								<select name="shipping_mode_id" id="shipping_mode_id" class="form-control form-control-modern" {{ (old('orders', $coupon->orders) == "4") ? "" :  "disabled" }}>
									<option value="">Select Shipping Method</option>
									@foreach($shippingMethods as $key_shipping => $value_shipping)
										<option value="{{ $key_shipping }}" {{ (old('shipping_mode_id', $coupon->sku) == $key_shipping && ($coupon->orders==4) ? 'selected' : '') }}>{{ $value_shipping }}</option>
									@endforeach
								</select>
								@error('shipping_mode_id')
									<label class="error" for="shipping_mode_id" role="alert">{{ $message }}</label>
								@enderror
								<span class="help-block">Note : Use only for free shipping</span>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_mode_id">Coupon Start Date to <br />Coupon End Date<span class="required">*</span></label>
							<div class="col-lg-6 col-xl-6">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="start_date" id="d_start_date" value="{{ old('start_date', $start_date) }}">
									@error('start_date')
										<label class="error" for="start_date" role="alert">{{ $message }}</label>
									@enderror
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to
									</span>
									<input type="text" class="form-control" name="end_date" id="d_end_date" value="{{ old('end_date', $end_date) }}">
									@error('end_date')
										<label class="error" for="end_date" role="alert">{{ $message }}</label>
									@enderror
								</div>
								<!-- <div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control dtp" name="start_date" id="d_start_date" value="{{ old('start_date', $start_date) }}" data-plugin-datepicker data-plugin-options='{"format": "yyyy-mm-dd"}'>
								</div> -->
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_mode_id">Coupon Details</label>
								<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_1">
									<textarea class="form-control" rows="3" id="detail" name="detail">{{ old('detail', $coupon->detail) }}</textarea>
								</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_mode_id">Use Limit</label>
							<div class="col-lg-6">
								<select name="is_once" id="is_once" class="form-control form-control-modern">
									<option value="0" {{ (old('is_once', $coupon->is_once) == '0' ? 'selected' : '') }}>Unlimited</option>
									<option value="1" {{ (old('is_once', $coupon->is_once) == '1' ? 'selected' : '') }}>Once</option>
									<option value="2" {{ (old('is_once', $coupon->is_once) == '2' ? 'selected' : '') }}>Once per Customer</option>
								</select>
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
								<div class="col-lg-7 col-xl-6">
									<select name="status" id="status" class="form-control form-control-modern">
										<option value="1" {{ (old('status', $coupon->status) == '1' ? 'selected' : '') }}>Active</option>
										<option value="0" {{ (old('status', $coupon->status) == '0' ? 'selected' : '') }}>Inactive</option>
									</select>
								</div>
						</div>
					
				</div>
			</section>

			
		</div>
	</div>
	
	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Coupon </button>
		</div>
        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"  class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
        @if ($coupon->coupon_id > 0)
        <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);" data-id="{{ $coupon->coupon_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Coupon </a> </div>
        @endif
      </div>
 </form> 
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.coupon.list') }}";
let url_edit = "{{ route('pnkpanel.coupon.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.coupon.update') }}";
let url_delete = "{{ route('pnkpanel.coupon.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/coupon_edit.js') }}"></script>
@endpush
