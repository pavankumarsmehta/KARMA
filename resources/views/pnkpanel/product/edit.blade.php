@extends('pnkpanel.layouts.app')
@section('content')

<?php
if(isset($product) && !empty($product)){
	if ($product->additional_images_thumb != "") {
		$additional_img_thumb=explode('#',$product->additional_images_thumb);
		$additional_images_thumb=count($additional_img_thumb);
	}else{
		$additional_images_thumb = 0;
	}
}else{
	$additional_images_thumb = 0;
}
?>


<form action="{{ route('pnkpanel.product.update') }}" method="post" name="frmProduct" id="frmProduct" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="product_id" value="{{ $product->product_id > 0 ? $product->product_id : ''  }}">
	<input type="hidden" name="actType" id="actType" value="{{ $product->product_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" id="shop_count" name="shop_count" value="<?php echo $additional_images_thumb; ?>">

	@csrf
	<div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="tabs-modern row" style="min-height: 490px;">
						<div class="col-lg-3-7 col-xl-1-7">
							<div class="nav flex-row" id="tabs" role="tablist" aria-orientation="vertical">
								<a class="nav-link @if(!$errors->any() || $errors->has('arr_category_id')){{ 'active' }}@endif" id="product-category-tab" data-toggle="pill" href="#product-category" role="tab" aria-controls="product-category" aria-selected="@if(!$errors->any() || $errors->has('arr_category_id')){{ 'true' }}@else{{ 'fasle' }}@endif">Product Category</a>

								<a class="nav-link @if($errors->has('product_name') || $errors->has('sku') || $errors->has('retail_price') || $errors->has('price') || $errors->has('sale_price')){{ 'active' }}@endif" id="product-information-tab" data-toggle="pill" href="#product-information" role="tab" aria-controls="product-information" aria-selected="@if($errors->has('product_name') || $errors->has('sku') || $errors->has('retail_price') || $errors->has('price') || $errors->has('sale_price')){{ 'true' }}@else{{ 'false' }}@endif">Product Information</a>

								<a class="nav-link" id="product-images-tab" data-toggle="pill" href="#product-images-video" role="tab" aria-controls="product-images" aria-selected="false">Product Images & Video</a>

								<a class="nav-link" id="meta-information-tab" data-toggle="pill" href="#meta-information" role="tab" aria-controls="meta-information">Product Meta Information</a>

							</div>
						</div>
						<div class="col-lg-12 col-xl-12">
							<div class="tab-content" id="tabContent">
								<div class="tab-pane fade @if(!$errors->any() || $errors->has('arr_category_id')){{ 'show active' }}@endif" id="product-category" role="tabpanel" aria-labelledby="product-category-tab">
									<div class="form-group row align-items-center">
										<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="category_id">Product Category <span class="required">*</span></label>
										<div class="col-lg-7 col-xl-6">
											<select name="arr_category_id[]" id="category_id" class="form-control form-control-modern @error('arr_category_id') error @enderror" multiple="multiple" size="15">
												<option value="" disabled="disabled" style="font-weight:bold; color:#000000;">Select Product categories</option>
												@php
												$records = App\Models\Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
												$query->orderBy('category_name', 'asc');
												}])->get();
												$selectedCategoryIdArr = [];
												// echo var_dump($product->productsCategory); exit;

												if(isset($product) && !empty($product)){
													foreach($product->productsCategory as $productsCategory)
													{
													$selectedCategoryIdArr[] = $productsCategory->category_id;
													}
											    }
												echo implode(App\Http\Controllers\Pnkpanel\ProductController::drawCategoryTreeDropdown($records, 0, old('parent_id', $selectedCategoryIdArr)));
												@endphp
											</select>
											@error('arr_category_id')
											<label class="error" for="arr_category_id[]" role="alert">{{ $message }}</label>
											@enderror
											@error('arr_category_id.*')
											<label class="error" for="arr_category_id[]" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									
							
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="brand_id">Brand</label>
										<div class="col-lg-7 col-xl-6" id="brand_container">
											<select class="form-control form-control-modern" name="brand_id" id="brand_id">
												<option value="">Select Brand</option>
												@foreach($brands as $brand)
												<option value="{{ $brand->brand_id }}" {{ ($brand->brand_id == old('brand_id', $product->brand_id) ? 'selected' : '') }}>{{ $brand->brand_name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-7 col-xl-6 text-center" style="line-height: 3.5;">
											<img src="{{ asset('pnkpanel/images/ajax-loader-small.gif') }}" alt="loading..." id="brand_loader" class="d-none" />
										</div>
									</div>

									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="manufacturer_id">Manufaturer</label>
										<div class="col-lg-7 col-xl-6" id="manufaturer_container">
											<select class="form-control form-control-modern" name="manufacturer_id" id="manufacturer_id">
												<option value="">Select Manufaturer</option>
												@foreach($manufacturer as $manufacturer)
												<option value="{{ $manufacturer->manufacturer_id }}" {{ ($manufacturer->manufacturer_id == old('manufacturer_id', $product->manufacturer_id) ? 'selected' : '') }}>{{ $manufacturer->manufacturer_name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-7 col-xl-6 text-center" style="line-height: 3.5;">
											<img src="{{ asset('pnkpanel/images/ajax-loader-small.gif') }}" alt="loading..." id="brand_loader" class="d-none" />
										</div>
									</div>
								</div>
								<div class="tab-pane fade @if($errors->has('product_name') || $errors->has('sku') || $errors->has('retail_price') || $errors->has('price') || $errors->has('sale_price')){{ 'show active' }}@endif" id="product-information" role="tabpanel" aria-labelledby="product-information-tab">
									
									<div class="form-group row align-items-center">
										<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="product_name">Product Name <span class="required">*</span></label>
										<div class="col-lg-7 col-xl-6">
											<input type="text" class="form-control form-control-modern @error('product_name') error @enderror" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" size="85">
											@error('product_name')
											<label class="error" for="product_name" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="product_description">Short Description</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="short_description" id="short_description" class="form-control form-control-modern" cols="40" rows="3">{{ stripslashes(old('short_description', $product->short_description)) }}</textarea>
											@error('short_description')
											<label class="error" for="short_description" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>	
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="sku">Product SKU <span class="required">*</span></label>
										<div class="col-lg-7 col-xl-6">
											<input type="text" class="form-control form-control-modern @error('sku') error @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" size="20">
											@error('sku')
											<label class="error" for="sku" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="product_group_code">Product Group Code <span class="required"></span></label>
										<div class="col-lg-7 col-xl-6">
											<input type="text" class="form-control form-control-modern @error('product_group_code') error @enderror" id="product_group_code" name="product_group_code" value="{{ old('product_group_code', $product->product_group_code) }}" size="20">
											@error('product_group_code')
											<label class="error" for="product_group_code" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="product_type">Product Type</label>
										<div class="col-lg-7 col-xl-6">
											<input type="text" class="form-control form-control-modern @error('product_type') error @enderror" id="product_type" name="product_type" value="{{ old('product_type', $product->product_type) }}" size="20">
											@error('product_type')
											<label class="error" for="product_type" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="related_sku">Related SKU <span class="required"></span></label>
										<div class="col-lg-7 col-xl-6">
											<textarea name="related_sku" id="related_sku" class="form-control form-control-modern" cols="40" rows="3">{{ stripslashes(old('related_sku', $product->related_sku)) }}</textarea>
											<span class="help-block"><b>Note:</b> 5010724527375#865479000555#865479000669</span>
											@error('related_sku')
											<label class="error" for="related_sku" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="product_description">Product Description</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="product_description" id="product_description" class="form-control form-control-modern mceEditor" cols="80" rows="5">{{ stripslashes(old('product_description', $product->product_description)) }}</textarea>
											<span class="help-block"><b>Note:</b> Detailed Description About Product</span>
											@error('product_description')
											<label class="error" for="product_description" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="general_information">General Information</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="general_information" id="general_information" class="form-control form-control-modern" cols="80" rows="5">{{ stripslashes(old('general_information', $product->general_information)) }}</textarea>
											<span class="help-block"><b>Note:</b> General Information About Product</span>
											@error('general_information')
											<label class="error" for="general_information" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="parent_sku">Parent SKU <span class="required"></span></label>
										<div class="col-lg-7 col-xl-6">
											<input type="text" class="form-control form-control-modern @error('parent_sku') error @enderror" id="parent_sku" name="parent_sku" value="{{ old('parent_sku', $product->parent_sku) }}" size="20">
											@error('parent_sku')
											<label class="error" for="parent_sku" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>				
									<div class="form-group row align-items-center">
										
										<div class="col-lg-7 col-xl-3">
											<label  for="retail_price">Retail Price <span class="required">*</span></label>
											<input type="text" class="form-control form-control-modern @error('retail_price') error @enderror" id="retail_price" name="retail_price" value="{{ old('retail_price', $product->retail_price) ?? 0 }}" size="20">
											@error('retail_price')
											<label class="error" for="retail_price" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="our_price">Our Price <span class="required">*</span></label>
										
											<input type="text" class="form-control form-control-modern @error('our_price') error @enderror" id="our_price" name="our_price" value="{{ old('our_price', $product->our_price) ?? 0}}" size="20">
											@error('our_price')
											<label class="error" for="our_price" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="sale_price">Sale Price</label>
										    <input type="text" class="form-control form-control-modern @error('sale_price') error @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" size="20">
											@error('sale_price')
											<label class="error" for="sale_price" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="on_sale">On Sale</label>
											<select class="form-control form-control-modern" name="on_sale" id="on_sale">
												<option value="1" {{ (old('on_sale', $product->on_sale) == '1')?" selected ":"" }}>Yes</option>
												<option value="0" {{ (old('on_sale', $product->on_sale) == '0' || old('on_sale', $product->on_sale) == '')?" selected ":"" }}>No</option>
											</select>
											@error('on_sale')
											<label class="error" for="on_sale" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
									
										<div class="col-lg-7 col-xl-3">
											<label  for="wholesale_price">Wholesale Price </label>
										
											<input type="text" class="form-control form-control-modern @error('wholesale_price') error @enderror" id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price) ?? 0}}" size="20">
											@error('wholesale_price')
											<label class="error" for="wholesale_price" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="wholesale_markup_percent">Wholesale Markup </label>
										
											<input type="text" class="form-control form-control-modern @error('wholesale_markup_percent') error @enderror" id="wholesale_markup_percent" name="wholesale_markup_percent" value="{{ old('wholesale_markup_percent', $product->wholesale_markup_percent) ?? 0}}" size="20">
											@error('wholesale_markup_percent')
											<label class="error" for="wholesale_markup_percent" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="our_cost">Our Cost</label>
										
											<input type="text" class="form-control form-control-modern @error('our_cost') error @enderror" id="our_cost" name="our_cost" value="{{ old('our_cost', $product->our_cost) ?? 0}}" size="20">
											@error('our_cost')
											<label class="error" for="our_cost" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="our_cost">color</label>
											<input type="text" class="form-control form-control-modern @error('color') error @enderror" id="color" name="color" value="{{ old('color', $product->color) }}" size="20">
											@error('color')
											<label class="error" for="color" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="shipping_text">Shipping</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="shipping_text" id="shipping_text" class="form-control form-control-modern" cols="80" rows="5">{{ stripslashes(old('shipping_text', $product->shipping_text)) }}</textarea>
											<span class="help-block"></span>
											@error('shipping_text')
											<label class="error" for="shipping_text" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label for="shipping_days">Shipping Days</label>
										
											<input type="numbber" class="form-control form-control-modern @error('shipping_days') error @enderror" id="shipping_days" name="shipping_days" value="{{ old('shipping_days', $product->shipping_days) }}">
											<span class="help-block"></span>
											@error('shipping_days')
											<label class="error" for="shipping_days" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="upc">UPC</label>
											<input type="text" class="form-control form-control-modern @error('upc') error @enderror" id="upc" name="upc" value="{{ old('upc', $product->upc) }}">
											<span class="help-block"></span>
											@error('upc')
											<label class="error" for="upc" role="alert">{{ $message }}</label>
											@enderror
										</div>
										
										<div class="col-lg-7 col-xl-3">
											<label  for="rank">Display Rank</label>
											<input type="text" class="form-control form-control-modern @error('rank') error @enderror" id="display_rank" name="display_rank" value="{{ old('display_rank', $product->display_rank) }}" size="20">
										</div>
										
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label  for="clearance">Clearance</label>
										
											<select class="form-control form-control-modern" name="clearance" id="clearance">
												<option value="Yes" {{ (old('clearance', $product->clearance) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('clearance', $product->clearance) == 'No' || old('clearance', $product->clearance) == '')?" selected ":"" }}>No</option>
											</select>
											@error('clearance')
											<label class="error" for="clearance" role="alert">{{ $message }}</label>
											@enderror
										</div>
										
										<div class="col-lg-7 col-xl-3">
											<label  for="best_seller">Best Seller</label>
											<select class="form-control form-control-modern" name="best_seller" id="best_seller">
												<option value="Yes" {{ (old('best_seller', $product->best_seller) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('best_seller', $product->best_seller) == 'No' || old('best_seller', $product->best_seller) == '')?" selected ":"" }}>No</option>
											</select>
											@error('best_seller')
											<label class="error" for="best_seller" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="best_seller">Display Deal OF Week</label>
											<select class="form-control form-control-modern" name="display_deal_of_week" id="display_deal_of_week">
												<option value="Yes" {{ (old('display_deal_of_week', $product->display_deal_of_week) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('display_deal_of_week', $product->display_deal_of_week) == 'No' || old('display_deal_of_week', $product->display_deal_of_week) == '')?" selected ":"" }}>No</option>
											</select>
											@error('best_seller')
											<label class="error" for="best_seller" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="new_arrival">New Arrival</label>
											<select class="form-control form-control-modern" name="new_arrival" id="new_arrival">
												<option value="Yes" {{ (old('new_arrival', $product->new_arrival) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('new_arrival', $product->new_arrival) == 'No' || old('new_arrival', $product->new_arrival) == '')?" selected ":"" }}>No</option>
											</select>
											@error('new_arrival')
											<label class="error" for="new_arrival" role="alert">{{ $message }}</label>
											@enderror
										</div>
										 <div class="col-lg-7 col-xl-3">
										    <label  for="featured">Featured</label>
										    <select class="form-control form-control-modern" name="featured" id="featured">
												<option value="Yes" {{ (old('featured', $product->featured) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('featured', $product->featured) == 'No' || old('featured', $product->featured) == '')?" selected ":"" }}>No</option>
											</select>
											@error('featured')
											<label class="error" for="featured" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
										    <label  for="seasonal_specials">Seasonal Specials</label>
										    <select class="form-control form-control-modern" name="seasonal_specials" id="seasonal_specials">
												<option value="Yes" {{ (old('seasonal_specials', $product->seasonal_specials) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('seasonal_specials', $product->seasonal_specials) == 'No' || old('seasonal_specials', $product->seasonal_specials) == '')?" selected ":"" }}>No</option>
											</select>
											@error('featured')
											<label class="error" for="featured" role="alert">{{ $message }}</label>
											@enderror
										</div>
										
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label  for="product_availability">Product Availability</label>
									         <select class="form-control form-control-modern" name="product_availability" id="product_availability">
									         	<option value="" {{ (old('product_availability', $product->product_availability) == '')?" selected ":"" }}>Select</option>
												<option value="Both" {{ (old('product_availability', $product->product_availability) == 'Both')?" selected ":"" }}>Both</option>
												<option value="B2C" {{ (old('product_availability', $product->product_availability) == 'B2C')?" selected ":"" }}>B2C</option>
												<option value="B2B" {{ (old('product_availability', $product->product_availability) == 'B2B')?" selected ":"" }}>B2B</option>
												
											</select>
											@error('product_availability')
											<label class="error" for="product_availability" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
												<label  for="is_atomizer">Is Atomizer</label>
									
											<select class="form-control form-control-modern" name="is_atomizer" id="is_atomizer">
												<option value="Yes" {{ (old('is_atomizer', $product->is_atomizer) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('is_atomizer', $product->is_atomizer) == 'No' || old('is_atomizer', $product->is_atomizer) == '')?" selected ":"" }}>No</option>
											</select>
											@error('is_atomizer')
											<label class="error" for="is_atomizer" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label  for="is_atomizer">Gender</label>
												<select class="form-control form-control-modern" name="gender" id="gender">
												<option value="" {{ (old('gender', $product->gender) == '')?" selected ":"" }}>Select</option>
												<option value="Male" {{ (old('gender', $product->gender) == 'Male')?" selected ":"" }}>Male</option>
												<option value="Female" {{ (old('gender', $product->gender) == 'Female')?" selected ":"" }}>Female</option>
												<option value="Unisex" {{ (old('gender', $product->gender) == 'Unisex')?" selected ":"" }}>Unisex</option>
											</select>
											@error('gift_wrapping')
											<label class="error" for="gift_wrapping" role="alert">{{ $message }}</label>
											@enderror
										</div>
										 <div class="col-lg-7 col-xl-3">
											<label  for="status">Status</label>
										
											<select class="form-control form-control-modern" name="status" id="status">
												<option value="0" {{ (old('status', $product->status) == '0')?" selected ":"" }}>Inactive</option>
												<option value="1" {{ (old('status', $product->status) == '1' || old('status', $product->status) == '')?" selected ":"" }}>Active</option>
											</select>
											@error('status')
											<label class="error" for="status" role="alert">{{ $message }}</label>
											@enderror
										</div>

									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-4">
											<label for="metric_size">Metric Size</label>
										    <input type="text" class="form-control form-control-modern @error('metric_size') error @enderror" id="metric_size" name="metric_size" value="{{ old('metric_size', $product->metric_size) }}" size="20">
										    @error('metric_size')
											<label class="error" for="metric_size" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="ingredients_pdf">Ingredients PDF</label>
										<div class="col-lg-9">
											<div class="fileupload @if (!empty($product->ingredients_pdf) && File::exists(config('const.PRD_INGREDIENTS_PDF_PATH').$product->ingredients_pdf)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (!empty($product->ingredients_pdf) && File::exists(config('const.PRD_INGREDIENTS_PDF_PATH').$product->ingredients_pdf)) {{ $product->ingredients_pdf }} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="ingredients_pdf" id="ingredients_pdf" accept="application/pdf">
													</span>
													@if (!empty($product->ingredients_pdf) && File::exists(config('const.PRD_INGREDIENTS_PDF_PATH').$product->ingredients_pdf))
													<a href="{{ config('const.PRD_INGREDIENTS_PDF_URL').$product->ingredients_pdf }}" class="btn btn-default fileupload-exists btnViewPdf"  data-id="{{ $product->product_id }}" data-src="{{ config('const.PRD_INGREDIENTS_PDF_URL').$product->ingredients_pdf }}" data-caption="INGREDIENTS PDF" target="_blank">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeletePdf" data-type="image_pdf" data-subtype="ingredients_pdf" data-id="{{ $product->product_id }}" data-image-name="{{ $product->ingredients_pdf }}" data-dismiss="fileupload">Remove</a>
												</div>
												
												@error('ingredients_pdf')
												<label class="error" for="ingredients_pdf" role="alert">{{ $message }}</label>
												@enderror
											</div>
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="ingredients">Ingredients</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="ingredients" id="ingredients" class="form-control form-control-modern mceEditor" cols="80" rows="5">{{ stripslashes(old('ingredients', $product->ingredients)) }}</textarea>
											<span class="help-block"></span>
											@error('ingredients')
											<label class="error" for="ingredients" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="uses">Uses</label>		
										<div class="col-lg-7 col-xl-9">
											<textarea name="uses" id="uses" class="form-control form-control-modern mceEditor" cols="80" rows="5">{{ stripslashes(old('uses', $product->uses)) }}</textarea>
											<span class="help-block"></span>
											@error('uses')
											<label class="error" for="uses" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="key_features">Key Features</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="key_features" id="key_features" class="form-control form-control-modern mceEditor" cols="80" rows="5">{{ stripslashes(old('key_features', $product->key_features)) }}</textarea>
											@error('key_features')
											<label class="error" for="key_features" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label for="product_weight">Product Weight</label>
											<input type="text" class="form-control form-control-modern @error('product_weight') error @enderror" id="product_weight" name="product_weight" value="{{ old('product_weight', $product->product_weight) }}">
											<span class="help-block"></span>
											@error('product_weight')
											<label class="error" for="product_weight" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="product_length">Product Length</label>
											<input type="text" class="form-control form-control-modern @error('product_length') error @enderror" id="product_length" name="product_length" value="{{ old('product_length', $product->product_length) }}">
											<span class="help-block"></span>
											@error('product_length')
											<label class="error" for="product_length" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="product_width">Product Width</label>
											<input type="text" class="form-control form-control-modern @error('product_width') error @enderror" id="product_width" name="product_width" value="{{ old('product_width', $product->product_width) }}">
											<span class="help-block"></span>
											@error('product_width')
											<label class="error" for="product_width" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="product_height">Product Height</label>
											<input type="text" class="form-control form-control-modern @error('product_height') error @enderror" id="product_height" name="product_height" value="{{ old('product_height', $product->product_height) }}">
											<span class="help-block"></span>
											@error('product_height')
											<label class="error" for="product_height" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										
										<div class="col-lg-7 col-xl-3">
											<label for="shipping_weight">Shipping Weight</label>
											<input type="text" class="form-control form-control-modern @error('shipping_weight') error @enderror" id="shipping_weight" name="shipping_weight" value="{{ old('shipping_weight', $product->shipping_weight) }}">
											<span class="help-block"></span>
											@error('shipping_weight')
											<label class="error" for="shipping_weight" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="shipping_length">Shipping Length</label>
											<input type="text" class="form-control form-control-modern @error('shipping_length') error @enderror" id="shipping_length" name="shipping_length" value="{{ old('shipping_length', $product->shipping_length) }}">
											<span class="help-block"></span>
											@error('shipping_length')
											<label class="error" for="shipping_length" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="shipping_width">Shipping Width</label>
											<input type="text" class="form-control form-control-modern @error('shipping_width') error @enderror" id="shipping_width" name="shipping_width" value="{{ old('shipping_width', $product->shipping_width) }}">
											<span class="help-block"></span>
											@error('shipping_width')
											<label class="error" for="shipping_width" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="shipping_height">Shipping Height</label>
											<input type="text" class="form-control form-control-modern @error('shipping_height') error @enderror" id="shipping_height" name="shipping_height" value="{{ old('shipping_height', $product->shipping_height) }}">
											<span class="help-block"></span>
											@error('shipping_height')
											<label class="error" for="shipping_height" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										
										<div class="col-lg-7 col-xl-3">
											<label for="country_of_origin">Country Of Origin</label>
											<input type="text" class="form-control form-control-modern @error('country_of_origin') error @enderror" id="country_of_origin" name="country_of_origin" value="{{ old('country_of_origin', $product->country_of_origin) }}">
											<span class="help-block"></span>
											@error('country_of_origin')
											<label class="error" for="country_of_origin" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="is_hazmat">Is Hazmat ? </label>
											<select class="form-control form-control-modern" name="is_hazmat" id="is_hazmat">
												<option value="Yes" {{ (old('is_hazmat', $product->is_hazmat) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('is_hazmat', $product->is_hazmat) == 'No' || old('is_hazmat', $product->is_hazmat) == '')?" selected ":"" }}>No</option>
											</select>
											@error('is_hazmat')
											<label class="error" for="status" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="is_set">Is Set ? </label>
											<select class="form-control form-control-modern" name="is_set" id="is_set">
												<option value="Yes" {{ (old('is_set', $product->is_set) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('is_set', $product->is_set) == 'No' || old('is_set', $product->is_set) == '')?" selected ":"" }}>No</option>
											</select>
											@error('is_set')
											<label class="error" for="status" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="is_multipack">Is Multipack ? </label>
											<select class="form-control form-control-modern" name="is_multipack" id="is_multipack">
												<option value="Yes" {{ (old('is_multipack', $product->is_multipack) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('is_multipack', $product->is_multipack) == 'No' || old('is_multipack', $product->is_multipack) == '')?" selected ":"" }}>No</option>
											</select>
											@error('is_multipack')
											<label class="error" for="status" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label for="age_group">Age Group</label>
											<input type="text" class="form-control form-control-modern @error('age_group') error @enderror" id="age_group" name="age_group" value="{{ old('age_group', $product->age_group) }}">
											<span class="help-block"></span>
											@error('age_group')
											<label class="error" for="age_group" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="variant">Variant</label>
											<input type="text" class="form-control form-control-modern @error('variant') error @enderror" id="variant" name="variant" value="{{ old('variant', $product->variant) }}">
											<span class="help-block"></span>
											@error('variant')
											<label class="error" for="variant" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="current_stock">Current Stock</label>
											<input type="text" class="form-control form-control-modern @error('current_stock') error @enderror" id="current_stock" name="current_stock" value="{{ old('current_stock', $product->current_stock) }}">
											<span class="help-block"></span>
											@error('current_stock')
											<label class="error" for="current_stock" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="size">Size</label>
											<input type="text" class="form-control form-control-modern @error('size') error @enderror" id="size" name="size" value="{{ old('size', $product->size) }}">
											<span class="help-block"></span>
											@error('size')
											<label class="error" for="size" role="alert">{{ $message }}</label>
											@enderror
										</div>

										<div class="col-lg-7 col-xl-3">
											<label for="pack_size">Pack Size</label>
											<input type="text" class="form-control form-control-modern @error('pack_size') error @enderror" id="pack_size" name="pack_size" value="{{ old('pack_size', $product->pack_size) }}">
											<span class="help-block"></span>
											@error('pack_size')
											<label class="error" for="pack_size" role="alert">{{ $message }}</label>
											@enderror
										</div>

										<div class="col-lg-7 col-xl-3">
											<label for="flavour">Flavour</label>
											<input type="text" class="form-control form-control-modern @error('flavour') error @enderror" id="flavour" name="flavour" value="{{ old('flavour', $product->flavour) }}">
											<span class="help-block"></span>
											@error('flavour')
											<label class="error" for="flavour" role="alert">{{ $message }}</label>
											@enderror
										</div>
										
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label for="skin_type">Skin Type</label>
											<input type="text" class="form-control form-control-modern @error('skin_type') error @enderror" id="skin_type" name="skin_type" value="{{ old('skin_type', $product->skin_type) }}">
											<span class="help-block"></span>
											@error('skin_type')
											<label class="error" for="skin_type" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="multi_pack_sku">Multi Pack Sku</label>
											<input type="text" class="form-control form-control-modern @error('multi_pack_sku') error @enderror" id="multi_pack_sku" name="multi_pack_sku" value="{{ old('multi_pack_sku', $product->multi_pack_sku) }}">
											<span class="help-block"></span>
											@error('multi_pack_sku')
											<label class="error" for="multi_pack_sku" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="temp">Temp</label>
											<input type="text" class="form-control form-control-modern @error('temp') error @enderror" id="temp" name="temp" value="{{ old('temp', $product->temp) }}">
											<span class="help-block"></span>
											@error('temp')
											<label class="error" for="temp" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="nioxin_system">NIOXIN System</label>
											<input type="text" class="form-control form-control-modern @error('nioxin_system') error @enderror" id="nioxin_system" name="nioxin_system" value="{{ old('nioxin_system', $product->nioxin_system) }}">
											<span class="help-block"></span>
											@error('nioxin_system')
											<label class="error" for="nioxin_system" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row align-items-center">
										 
										<div class="col-lg-7 col-xl-3">
											<label for="nioxin_size">NIOXIN Size</label>
											<input type="text" class="form-control form-control-modern @error('nioxin_size') error @enderror" id="nioxin_size" name="nioxin_size" value="{{ old('nioxin_size', $product->nioxin_size) }}">
											<span class="help-block"></span>
											@error('nioxin_size')
											<label class="error" for="nioxin_size" role="alert">{{ $message }}</label>
											@enderror
										</div>
										<div class="col-lg-7 col-xl-3">
											<label for="nioxin_type">NIOXIN Type</label>
											<input type="text" class="form-control form-control-modern @error('nioxin_type') error @enderror" id="nioxin_type" name="nioxin_type" value="{{ old('nioxin_type', $product->nioxin_type) }}">
											<span class="help-block"></span>
											@error('nioxin_type')
											<label class="error" for="nioxin_type" role="alert">{{ $message }}</label>
											@enderror
										</div>

										<div class="col-lg-7 col-xl-3">
											<label for="ship_international">Ship International </label>
											<select class="form-control form-control-modern" name="ship_international" id="ship_international">
												<option value="Yes" {{ (old('ship_international', $product->ship_international) == 'Yes')?" selected ":"" }}>Yes</option>
												<option value="No" {{ (old('ship_international', $product->ship_international) == 'No' || old('ship_international', $product->ship_international) == '')?" selected ":"" }}>No</option>
											</select>
											@error('ship_international')
											<label class="error" for="status" role="alert">{{ $message }}</label>
											@enderror
										</div>

										<div class="col-lg-7 col-xl-3">
											<label for="free_text_1">Free Text_1</label>
											<input type="text" class="form-control form-control-modern @error('free_text_1') error @enderror" id="free_text_1" name="free_text_1" value="{{ old('free_text_1', $product->free_text_1) }}">
											<span class="help-block"></span>
											@error('free_text_1')
											<label class="error" for="free_text_1" role="alert">{{ $message }}</label>
											@enderror
										</div>

										
									</div>
									<div class="form-group row align-items-center">
										<div class="col-lg-7 col-xl-3">
											<label for="free_text_2">Free Text_2</label>
											<input type="text" class="form-control form-control-modern @error('free_text_2') error @enderror" id="free_text_2" name="free_text_2" value="{{ old('free_text_2', $product->free_text_2) }}">
											<span class="help-block"></span>
											@error('free_text_2')
											<label class="error" for="free_text_2" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="product-images-video" role="tabpanel" aria-labelledby="product-images-tab">
								
								
									@php

									$total_extra_image = 5;

									if($product->product_id > 0 && isset($product->extra_images) && $product->extra_images != '') {
										$arr_extra_images_new = array();
										$arr_extra_images = explode("#", $product->extra_images);
										for($j=0; $j < $total_extra_image; $j++) {
											if(isset($arr_extra_images[$j]) && $arr_extra_images[$j] != '') {
												$total_extra_image_exp = explode(".", $arr_extra_images[$j]);
												$total_extra_image_exp = explode("_", $total_extra_image_exp[0]);
												if(isset($arr_extra_images[$j]) && $arr_extra_images[$j] !='') {
													$arr_extra_images_new[$j+1] = $arr_extra_images[$j];	
												}

											}
										}
									}

									@endphp
									<input type="hidden" name="total_extra_image" value="{{ $total_extra_image }}" />
									<input type="hidden" name="total_extra_image_count" value="{{ (isset($arr_extra_images)) ? count( $arr_extra_images) : 0 }}" />
									<div class="form-group row align-items-center">
									<label class="col-lg-3 col-xl-3 control-label text-lg-right mb-0" for="status">Main Image</label>
										<div class="col-lg-9 col-xl-9">
											<div class="fileupload @if (!empty($product->image_name) && File::exists(config('const.PRD_ZOOM_IMG_PATH').$product->image_name)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (!empty($product->image_name) && File::exists(config('const.PRD_ZOOM_IMG_PATH').$product->image_name)) {{ $product->image_name }} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="image_name" id="image_name">
													</span>
													@if (!empty($product->image_name) && File::exists(config('const.PRD_ZOOM_IMG_PATH').$product->image_name))
													<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="image_name" data-subtype="image_name" data-id="{{ $product->product_id }}" data-src="{{ config('const.PRD_ZOOM_IMG_URL').$product->image_name }}" data-caption="Product Zoom Image">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="image_name" data-subtype="image_name" data-id="{{ $product->product_id }}" data-image-name="{{ $product->image_name }}" data-dismiss="fileupload">Remove</a>
												</div>
												<span class="help-block">(<b>Note:</b> Recommended Image size should be {{ config('const.PRD_IMG_WIDTH')}} X {{ config('const.PRD_IMG_HEIGHT')}})</span>
												@error('image_name')
												<label class="error" for="image_name" role="alert">{{ $message }}</label>
												@enderror
											</div>
										</div>
									</div>
									@php
															
									for($p=0; $p < $total_extra_image; $p++)
									{	
										$extra_image_name = '';
										
										if($product->product_id > 0 && isset($arr_extra_images_new[$p+1]) && $arr_extra_images_new[$p+1] != '') {
											$extra_image_name = $arr_extra_images_new[$p+1];
										}
										//dd(config('const.PRD_LARGE_IMG_PATH').$extra_image_name);
									@endphp

									<div class="form-group row align-items-center">
										<label class="col-lg-3 col-xl-3 control-label text-lg-right mb-0" for="extra_image{{ $p }}">Extra Image {{$p+1}}</label>
										<div class="col-lg-9 col-xl-9">
											<div class="fileupload @if (!empty($extra_image_name) && File::exists(config('const.PRD_LARGE_IMG_PATH').$extra_image_name)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (!empty($extra_image_name) && File::exists(config('const.PRD_LARGE_IMG_PATH').$extra_image_name)) {{ $extra_image_name ?? '' }} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="extra_image{{$p}}" id="extra_image{{$p}}" >
													</span>
													@if (!empty($extra_image_name) && File::exists(config('const.PRD_LARGE_IMG_PATH').$extra_image_name))
													<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="product_image" data-subtype="extra_image" data-id="{{ $product->product_id }}" data-src="{{ config('const.PRD_LARGE_IMG_URL').$extra_image_name }}" data-caption="Extra Image {{$p+1}}">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="product_image" data-subtype="extra_image" data-id="{{ $product->product_id }}" data-image-name="{{ $extra_image_name }}" data-dismiss="fileupload">Remove</a>
												</div>
												@error('extra_image'.$p)
													<label class="error" for="extra_image{{$p}}" role="alert">{{ $message }}</label>
												@enderror
											</div>
										</div>
									</div>

									@php
									}
									@endphp


									<div class="form-group row align-items-center">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="video_url">Video URL</label>
										<div class="col-lg-9 col-xl-7" id="swatch_container">
											<input type="text" class="form-control form-control-modern" id="video_url" name="video_url" value="{{ $product->video_url }}" size="100">
											<span class="help-block">(Ex.: https://player.vimeo.com/video/<b>347119375</b>)</span>
											<span class="help-block">(Ex.: https://www.youtube.com/embed/<b>EngW7tLk6R8</b>)</span>
											@error('video_url')
											<label class="error" for="video_url" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="meta-information" role="tabpanel" aria-labelledby="meta-information-tab">
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_title">Meta Title</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_title" id="meta_title" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_title', $product->meta_title)) }}</textarea>
											@error('meta_title')
											<label class="error" for="meta_title" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_keywords">Meta Keywords</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_keywords" id="meta_keywords" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_keywords', $product->meta_keywords)) }}</textarea>
											@error('meta_keywords')
											<label class="error" for="meta_keywords" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_desc">Meta Description</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_desc" id="meta_desc" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_desc', $product->meta_description)) }}</textarea>
											@error('meta_desc')
											<label class="error" for="meta_desc" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>


	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($product->product_id > 0)
		<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
			<a href="javascript:void(0);" data-id="{{ $product->product_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a>
		</div>
		<div class="col-12 col-md-auto">
			<a href="{{$product->product_url}}?preview=1" target="_blank" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"> <i class="bx bx-save text-4 mr-2"></i> Preview Product Display On the Site After Save </a>
		</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
	var site_url = '<?= config("const.SITE_URL") ?>';
	let url_list = "{{ route('pnkpanel.product.list') }}";
	let url_edit = "{{ route('pnkpanel.product.edit', ':id') }}";
	let url_update = "{{ route('pnkpanel.product.update') }}";
	let url_delete = "{{ route('pnkpanel.product.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.product.delete_image') }}";
	let url_delete_pdf = "{{ route('pnkpanel.product.delete_pdf') }}";
	let url_get_brands = "{{ route('pnkpanel.product.getbrands', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/product_edit.js') }}"></script>

<script type="text/javascript">

// gets the selected html rows checkbox's
        function getSelectedRows() {
            var selectedRows = []
            $('input[type=checkbox]').each(function () {
                if ($(this).is(":checked")) {
                    selectedRows.push($(this));
                }
            });
            return selectedRows;
        };

        // deleting selected multiple html table rows
        function delete_shop_row() {
            var selectedRows = getSelectedRowsgetSelectedRows();
            for (var i = 0; i < selectedRows.length; i++) {
                $(selectedRows[i]).parent().parent().remove();
            }
        }
		var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
		if (err_msg1_for_cache != ""){
			$.ajax({
				type: 'POST',
				url: site_url + '/clearfrontcachebyparentsku',
				data: {
					parent_sku: '<?= $parent_sku ?>',
					product_id: '<?= $product->product_id > 0 ? $product->product_id : '' ?>'
				},
				success: function(data) {
					console.log('product parentsku cache clear sucessfully');

				}
			});
		}

		/*var catid = '<?= isset($selectedCategoryIdArr)?implode(',',$selectedCategoryIdArr):"" ?>';
		if (catid != ""){
			$.ajax({
				type: 'POST',
				url: site_url + '/clearfrontplpcache',
				data: {
					category_id: catid,
				},
				success: function(data) {
					console.log('product parentsku cache clear sucessfully');

				}
			});
		}*/
</script>
@endpush