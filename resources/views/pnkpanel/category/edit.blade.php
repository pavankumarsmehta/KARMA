@extends('pnkpanel.layouts.app')
@section('content')
<style>
.table thead th{border-bottom:inherit !important; padding:.75rem 0px 5px 22px; }
.table th, .table td{border-bottom:inherit !important;}
.fileupload .uneditable-input .fileupload-preview{padding:0 0 0 35px;}
</style>
<?php
if ($category->category_beauty_json != "") {
	$category_beauty_json = json_decode($category->category_beauty_json, true);
	$category_beauty_json_count = count($category_beauty_json);
	$key_values = array_column($category_beauty_json, 'display_position');
	array_multisort($key_values, SORT_ASC, $category_beauty_json);
} else {
	$category_beauty_json_count = 0;
}

?>
<form action="{{ route('pnkpanel.category.update') }}" method="post" name="frmCategory" id="frmCategory" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="category_id" value="{{ $category->category_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $category->category_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" id="shop_actType" name="actType" value="shop_section">
	<input type="hidden" name="type" value="shop">
	<input type="hidden" name="id" value="">
	<input type="hidden" id="is_delete" name="is_delete" value="no">
	<input type="hidden" id="shop_count" name="shop_count" value="<?php echo $category_beauty_json_count; ?>">
	<input type="hidden" id="shop_count_org" name="shop_count_org" value="<?php echo $category_beauty_json_count; ?>">
	<input type="hidden" id="del_chk" name="del_chk" value="">
	@csrf

	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Category Information</h2>
				</header>
				<div class="card-body">


					<div class="form-group row">
						<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
					</div>
					@if($category->category_id > 0)
					<div class="form-group row">
						<div class="col-lg-12" style="text-align:right;">
							<a title="download whole product data csv file" target="_blank" href="{{$catviewpage}}" class="btn btn-primary">View Front Page</a>
						</div>
					</div>
					@endif
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="parent_id">Parent Category <span class="required">*</span></label>
						<div class="col-lg-6">
							<select name="parent_id" id="parent_id" class="form-control form-control-modern">
								<option value="0" selected>&raquo;&nbsp;Add as a Parent Category</option>
								@php
								$records = App\Models\Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
								$query->orderBy('category_name', 'asc');
								}])->get();
								echo implode(App\Http\Controllers\Pnkpanel\CategoryController::drawCategoryTreeDropdown($records, 0, old('parent_id', $category->parent_id)));
								@endphp
							</select>
							@error('parent_id')
							<label class="error" for="parent_id" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="category_name">Category Name <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('category_name') error @enderror" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name) }}">
							@error('category_name')
							<label class="error" for="category_name" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="category_description">Category Description </label>
						<div class="col-lg-9">
							<textarea name="category_description" id="category_description" class="mceEditor" cols="80" rows="5">{{ stripslashes(old('category_description', $category->category_description)) }}</textarea>
							@error('category_description')
							<label class="error" for="category_description" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="thumb_image">Category Thumb Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($category->thumb_image) && File::exists(config('const.CAT_IMAGE_PATH').$category->thumb_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($category->thumb_image) && File::exists(config('const.CAT_IMAGE_PATH').$category->thumb_image)) {{ $category->thumb_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="thumb_image" id="thumb_image">
									</span>
									@if (!empty($category->thumb_image) && File::exists(config('const.CAT_IMAGE_PATH').$category->thumb_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="category_image" data-subtype="thumb_image" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_IMAGE_URL').$category->thumb_image }}" data-caption="Thumb Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="category_image" data-subtype="thumb_image" data-id="{{ $category->category_id }}" data-image-name="{{ $category->thumb_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_IMAGE_THUMB_WIDTH')}} X {{ config('const.CAT_IMAGE_THUMB_HEIGHT')}})</span>
								@error('thumb_image')
								<label class="error" for="thumb_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="banner_image">Category Banner Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($category->banner_image) && File::exists(config('const.CAT_BANNER_PATH').$category->banner_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($category->banner_image) && File::exists(config('const.CAT_BANNER_PATH').$category->banner_image)) {{ $category->banner_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="banner_image" id="banner_image">
									</span>
									@if (!empty($category->banner_image) && File::exists(config('const.CAT_BANNER_PATH').$category->banner_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="category_banner" data-subtype="banner_image" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_BANNER_URL').$category->banner_image }}" data-caption="Banner Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="category_banner" data-subtype="banner_image" data-id="{{ $category->category_id }}" data-image-name="{{ $category->banner_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_BANNER_THUMB_WIDTH')}} X {{ config('const.CAT_BANNER_THUMB_HEIGHT')}})</span>
								@error('banner_image')
								<label class="error" for="banner_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="banner_position">Category Banner Position</label>
						<div class="col-lg-7 col-xl-6">
							<select name="banner_position" id="banner_position" class="form-control form-control-modern">
								<option value="FULL" {{ old('banner_position', $category->banner_position) == 'FULL' ? 'selected' : '' }}>
									Full Banner</option>
								<option value="LEFT" {{ old('banner_position', $category->banner_position) == 'LEFT' ? 'selected' : '' }}>
									Banner with Text and Title on Left Side</option>
								<option value="RIGHT" {{ old('banner_position', $category->banner_position) == 'RIGHT' ? 'selected' : '' }}>
									Banner with Text and Title on Right Side</option>	
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="display_menu_position">Category Banner Link</label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('banner_image_link') error @enderror" id="banner_image_link" name="banner_image_link" value="{{ old('banner_image_link', $category->banner_image_link) }}">
							@error('banner_image_link')
							<label class="error" for="banner_image_link" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="promotion_banner_image">Category Promotion Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($category->promotion_banner_image) && File::exists(config('const.CAT_PROMOTION_PATH').$category->promotion_banner_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($category->promotion_banner_image) && File::exists(config('const.CAT_PROMOTION_PATH').$category->promotion_banner_image)) {{ $category->promotion_banner_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="promotion_banner_image" id="promotion_banner_image">
									</span>
									@if (!empty($category->promotion_banner_image) && File::exists(config('const.CAT_PROMOTION_PATH').$category->promotion_banner_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="category_banner" data-subtype="promotion_banner_image" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_PROMOTION_URL').$category->promotion_banner_image }}" data-caption="Banner Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="promotion_banner_image" data-subtype="promotion_banner_image" data-id="{{ $category->category_id }}" data-image-name="{{ $category->promotion_banner_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_PROMOTION_THUMB_WIDTH')}} X {{ config('const.CAT_PROMOTION_THUMB_HEIGHT')}})</span>
								@error('promotion_banner_image')
								<label class="error" for="promotion_banner_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="display_menu_position">Category Promotion Link</label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('promotion_image_link') error @enderror" id="promotion_image_link" name="promotion_image_link" value="{{ old('promotion_image_link', $category->promotion_image_link) }}">
							@error('promotion_image_link')
							<label class="error" for="promotion_image_link" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="promotion_title">Category Promotion Title</label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('promotion_title') error @enderror" id="promotion_title" name="promotion_title" value="{{ old('promotion_title', $category->promotion_title) }}">
							@error('promotion_title')
							<label class="error" for="promotion_title" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="promotion_text">Category Promotion Text</label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('promotion_text') error @enderror" id="promotion_text" name="promotion_text" value="{{ old('promotion_text', $category->promotion_text) }}">
							@error('promotion_text')
							<label class="error" for="promotion_text" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					{{-- <div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_category">Display on Category</label>
						<div class="col-lg-7 col-xl-6">
							<select name="display_on_category" id="display_on_category" class="form-control form-control-modern">
								<option value="Yes" {{ old('display_on_category', $category->display_on_category) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('display_on_category', $category->display_on_category) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div> --}}
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_other_category">Display on other Category & Menu</label>
						<div class="col-lg-7 col-xl-6">
							<select name="display_on_other_category" id="display_on_other_category" class="form-control form-control-modern">
								<option value="Yes" {{ old('display_on_other_category', $category->display_on_other_category) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('display_on_other_category', $category->display_on_other_category) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div>
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_home">Display on Home</label>
						<div class="col-lg-7 col-xl-6">
							<select name="display_on_home" id="display_on_home" class="form-control form-control-modern">
								<option value="Yes" {{ old('display_on_home', $category->display_on_home) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('display_on_home', $category->display_on_home) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div>
					{{--
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_home">Display On Top Menu</label>
						<div class="col-lg-7 col-xl-6">
							<select name="is_topmenu" id="is_topmenu" class="form-control form-control-modern">
								<option value="No" {{ old('is_topmenu', $category->is_topmenu) == 'No' ? 'selected' : '' }}>No</option>
								<option value="Yes" {{ old('is_topmenu', $category->is_topmenu) == 'Yes' ? 'selected' : '' }}>Yes</option>
							</select>
							<span class="help-block"></span>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="display_menu_position">Display Top Menu Position</label>
						<div class="col-lg-6">
							<input type="number" name="display_menu_position" id="display_menu_position" value="{{ old('display_menu_position', $category->display_menu_position) }}" class="form-control @error('display_menu_position') error @enderror">
							<span class="help-block">(Note: Position for every category and subcategory)</span>
							@error('display_menu_position')
							<label class="error" for="display_menu_position" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					--}}

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="template_page">Template Page</label>
						<div class="col-lg-7 col-xl-6">
							<select name="template_page" id="template_page" class="form-control form-control-modern">
								<option value="category_list" {{ old('template_page', $category->template_page) == 'category_list' ? 'selected' : '' }}>
									Category List</option>
								<option value="product_list" {{ old('template_page', $category->template_page) == 'product_list' ? 'selected' : '' }}>
									Product List</option>
							</select>
						</div>
					</div>
					
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
						<div class="col-lg-7 col-xl-6">
							<select name="status" id="status" class="form-control form-control-modern">
								<option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>
									Active</option>
								<option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>
									Inactive</option>
							</select>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Category Advertisement</h2>
				</header>
				
				<div class="card-body">
					    <div class="form-group row">
							<table class="table">
								<thead class="">
									<tr>
										<th>Advertisement Title</th>
										<th>Advertisement Link</th>
										<th>Advertisement Image</th>
									</tr>
								</thead>		   
							    <tbody>
									<tr>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<input type="text" class="form-control" id="advertisement_title1" name="advertisement_title1" value="{{ old('advertisement_title1', $category->advertisement_title1) }}" placeholder="Advertisement Title 1">
											</div>
										</td>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<input type="text" class="form-control" id="advertisement_link1" name="advertisement_link1" value="{{ old('advertisement_link1', $category->advertisement_link1) }}" placeholder="Advertisement Link 1">
											</div>
										</td>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<div class="fileupload @if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
													<div class="input-append">
														<div class="uneditable-input">
															<i class="fas fa-file fileupload-exists"></i>
															<span class="fileupload-preview">@if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1)) {{ $category->advertisement_image1 }} @endif</span>
														</div>
														<span class="btn btn-default btn-file">
															<span class="fileupload-exists">Change</span>
															<span class="fileupload-new">Select file</span>
															<input type="file" name="advertisement_image1" id="advertisement_image1">
														</span>
														@if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1))
														<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="advertisement_image1" data-subtype="advertisement_image1" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_ADS_IMAGE_URL').$category->advertisement_image1 }}" data-caption="Menu Image">View</a>
														@endif
														<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="advertisement_image1" data-subtype="advertisement_image1" data-id="{{ $category->category_id }}" data-image-name="{{ $category->advertisement_image1 }}" data-dismiss="fileupload">Remove</a>
													</div>
													<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_ADS1_BANNER_WIDTH')}} X {{ config('const.CAT_ADS_BANNER_HEIGHT')}})</span>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<input type="text" class="form-control" id="advertisement_title2" name="advertisement_title2" value="{{ old('advertisement_title2', $category->advertisement_title2) }}" placeholder="Advertisement Title 2">
											</div>
										</td>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<input type="text" class="form-control" id="advertisement_link2" name="advertisement_link2" value="{{ old('advertisement_link2', $category->advertisement_link2) }}" placeholder="Advertisement Link 2">
											</div>
										</td>
										<td>
											<div class="col-lg-12" style="padding:0px;">
												<div class="fileupload @if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
													<div class="input-append">
														<div class="uneditable-input">
															<i class="fas fa-file fileupload-exists"></i>
															<span class="fileupload-preview">@if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2)) {{ $category->advertisement_image2 }} @endif</span>
														</div>
														<span class="btn btn-default btn-file">
															<span class="fileupload-exists">Change</span>
															<span class="fileupload-new">Select file</span>
															<input type="file" name="advertisement_image2" id="advertisement_image2">
														</span>
														@if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2))
														<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="advertisement_image2" data-subtype="advertisement_image2" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_ADS_IMAGE_URL').$category->advertisement_image2 }}" data-caption="Menu Image">View</a>
														@endif
														<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="advertisement_image2" data-subtype="advertisement_image2" data-id="{{ $category->category_id }}" data-image-name="{{ $category->advertisement_image2 }}" data-dismiss="fileupload">Remove</a>
													</div>
													<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_ADS1_BANNER_WIDTH')}} X {{ config('const.CAT_ADS_BANNER_HEIGHT')}})</span>
												</div>
											</div>
										</td>
									</tr>
							    </tbody>    
							</table>
						</div>
					</div>
					
					
					
					
					
					
					{{-- <div class="card-body">
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_title1">Advertisement Title 1</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="advertisement_title1" name="advertisement_title1" value="{{ old('advertisement_title1', $category->advertisement_title1) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_link1">Advertisement Link 1</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="advertisement_link1" name="advertisement_link1" value="{{ old('advertisement_link1', $category->advertisement_link1) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_image1">Advertisement Image 1 </label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1)) {{ $category->advertisement_image1 }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="advertisement_image1" id="advertisement_image1">
									</span>
									@if (!empty($category->advertisement_image1) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image1))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="advertisement_image1" data-subtype="advertisement_image1" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_ADS_IMAGE_URL').$category->advertisement_image1 }}" data-caption="Menu Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="advertisement_image1" data-subtype="advertisement_image1" data-id="{{ $category->category_id }}" data-image-name="{{ $category->advertisement_image1 }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_ADS1_BANNER_WIDTH')}} X {{ config('const.CAT_ADS_BANNER_HEIGHT')}})</span>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_title2">Advertisement Title 2</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="advertisement_title2" name="advertisement_title2" value="{{ old('advertisement_title2', $category->advertisement_title2) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_link2">Advertisement Link 2</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="advertisement_link2" name="advertisement_link2" value="{{ old('advertisement_link2', $category->advertisement_link2) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="advertisement_image2">Advertisement Image 2 </label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2)) {{ $category->advertisement_image2 }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="advertisement_image2" id="advertisement_image2">
									</span>
									@if (!empty($category->advertisement_image2) && File::exists(config('const.CAT_ADS_IMAGE_PATH').$category->advertisement_image2))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="advertisement_image2" data-subtype="advertisement_image2" data-id="{{ $category->category_id }}" data-src="{{ config('const.CAT_ADS_IMAGE_URL').$category->advertisement_image2 }}" data-caption="Menu Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="advertisement_image2" data-subtype="advertisement_image2" data-id="{{ $category->category_id }}" data-image-name="{{ $category->advertisement_image2 }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.CAT_ADS2_BANNER_WIDTH')}} X {{ config('const.CAT_ADS_BANNER_HEIGHT')}})</span>
							</div>
						</div>
					</div>
				</div>--}}
			</section>
		</div>
	</div>
	{{-- 
	<!--Added code for Category Beauty Images as on 06-10-2023 Start
	Hide as on 26-02-2024 Do not delete below code Start -->
	<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Category Beauty Images</h2>
				</header>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-ecommerce-simple table-striped mb-0 dataTable no-footer">
							<tbody>
								<?php for ($i = 0; $i < $category_beauty_json_count; $i++) {
									if ($i % 2 == 0) $rclass = "even";
									else $rclass = "odd"; ?>
									<tr role="row" class="<?php echo $rclass; ?>" id="shop_row<?= ($i + 1); ?>">
										<td>
											<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2" id="shop_chkbox<?= $i; ?>" name="shop_chkbox<?= $i; ?>" value="<?php echo $i; ?>">
										</td>
										<td>
											Title <?= ($i + 1); ?>: <input type="text" class="form-control " id="shop_title<?= $i; ?>" name="shop_title<?= $i; ?>" value="<?= $category_beauty_json[$i]['category_name']; ?>">
										</td>
										<td>
											Image <?= ($i + 1); ?>:
											<input type="hidden" name="old_shop_image<?= $i; ?>" value="<?php echo $category_beauty_json[$i]['category_beauty_image']; ?>">
											<input type="file" name="shop_image<?= $i; ?>">
											<? if (file_exists(config('const.CAT_BEAUTY_IMAGE_PATH') . $category_beauty_json[$i]['category_beauty_image']) && $category_beauty_json[$i]['category_beauty_image'] != "") { ?>

												<a href="javascript:void(0);" class="btn btn-default fileupload-view btnViewImage" data-type="category_image" data-subtype="thumb_image" data-src="<?php echo config('const.CAT_BEAUTY_IMAGE_URL') . $category_beauty_json[$i]['category_beauty_image']; ?>" data-caption="<?php echo $category_beauty_json[$i]['category_beauty_image']; ?>">View</a>

											<? } ?>
										</td>
										<td>Beauty Url <?= ($i + 1); ?>: <input type="text" class="form-control " name="shop_link<?= $i; ?>" value="<?= $category_beauty_json[$i]['category_link']; ?>"></td>
										<td>
											Rank: <input type="number" class="form-control shop_row" name="shop_rank<?= $i; ?>" value="<?= $category_beauty_json[$i]['display_position']; ?>">

										</td>
										<td>Status: <select name="shop_status<?= $i; ?>" id="shop_status<?= $i; ?>" class="form-control form-control-modern">
												<option value="">Select Status</option>
												<option value="1" <?php if ($category_beauty_json[$i]['category_status'] == 1) { ?> selected <?php } ?>>Active</option>
												<option value="0" <?php if ($category_beauty_json[$i]['category_status'] == 0) { ?> selected <?php } ?>>Inactive</option>
											</select>
										</td>
									</tr>
								<?php } ?>
								<tr role="row" class="even" id="shop_row"></tr>
							</tbody>
						</table>
					</div>

					<div class="table-responsive">
						<table class="table table-ecommerce-simple table-striped mb-0 dataTable no-footer">
							<tbody>
								<tr>
									<td>
										<a href="javascript:void(0);" onclick="add_shop_row('shop');" class="submit-button btn btn-primary" style="text-decoration:none;">Add New Row</a>
										<a href="javascript:void(0);" onclick="delete_shop_row('shop');" class="delete-button btn btn-danger" style="text-decoration:none;">Delete Selected</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!--Hide as on 26-02-2024 Do not delete below code End 
	Added code for Category Tile Beauty as on 06-10-2023 End-->
	--}}

	<div class="row">
		<div class="col">
			<section class="card card-collapsed">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Category Meta Information</h2>
				</header>
				<div class="card-body">

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_title">Meta Title </label>
						<div class="col-lg-6">
							<textarea name="meta_title" id="meta_title" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_title', $category->meta_title)) }}</textarea>
							@error('meta_title')
							<label class="error" for="meta_title" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_keywords">Meta Keywords </label>
						<div class="col-lg-6">
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_keywords', $category->meta_keywords)) }}</textarea>
							@error('meta_keywords')
							<label class="error" for="meta_keywords" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_description">Meta Description </label>
						<div class="col-lg-6">
							<textarea name="meta_description" id="meta_description" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_description', $category->meta_description)) }}</textarea>
							@error('meta_description')
							<label class="error" for="meta_description" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

				</div>
			</section>
		</div>
	</div>

	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="button" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($category->category_id > 0)
		<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
			<a href="javascript:void(0);" data-id="{{ $category->category_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a>
		</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
	let url_list = "{{ route('pnkpanel.category.list') }}";
	let url_edit = "{{ route('pnkpanel.category.edit', ':id') }}";
	let url_update = "{{ route('pnkpanel.category.update') }}";
	let url_delete = "{{ route('pnkpanel.category.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.category.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/category_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
<script type="text/javascript">
	var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
	if (err_msg1_for_cache != ""){
		$.ajax({
			type: 'POST',
			data: {
			"_token": "{{ csrf_token() }}",
		   "category_id": "{{ $category->category_id }}"
			},
			url: site_url + '/clearfrontcachecategory',
			success: function(data) {
				console.log('Category cache clear sucessfully');

			}
		});
	}
	function add_shop_row(type) {
		var beauty_rows = parseInt($("#" + type + "_count").val());

		if (beauty_rows > 0) {
			var index = beauty_rows + 1;
		} else {
			var index = 1;
		}

		var rclass = 'odd';
		if (beauty_rows % 2 == 0) {
			rclass = 'even';
		}

		var shop_html = '<tr role="row" class="' + rclass + '" id="' + type + '_row' + index + '"><td><input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2" id="' + type + '_chkbox' + beauty_rows + '" name="' + type + '_chkbox' + beauty_rows + '" value="" checked></td><td>Title ' + index + ': <input type="text" class="form-control " id="' + type + '_title' + beauty_rows + '" name="' + type + '_title' + beauty_rows + '" value=""></td><td>Image ' + index + ': <input type="file" name="' + type + '_image' + beauty_rows + '"></td><td>Beauty Url ' + index + ': <input type="text" class="form-control " name="' + type + '_link' + beauty_rows + '" value=""></td><td>Rank: <input type="number" class="form-control shop_row" name="' + type + '_rank' + beauty_rows + '" value="' + index + '"></td><td>Status: <select name="' + type + '_status' + beauty_rows + '" id="' + type + '_status' + beauty_rows + '" class="form-control form-control-modern"><option value="">Select Status</option><option value="1" selected>Active</option><option value="2">Inactive</option></select></td><tr>';

		if (beauty_rows > 0) {
			$("#" + type + "_row" + beauty_rows).after(shop_html);
		} else {
			$("#" + type + "_row").after(shop_html);
		}

		$("#" + type + "_count").val(index);
	}


	function delete_shop_row(type) {
		var beauty_rows = parseInt($("#" + type + "_count").val());
		var org_beauty_rows = parseInt($("#" + type + "_count_org").val());
		var delete_row = 0;
		var chbox_sel = 'no';
		var del_flag = "false";

		var del_chk = $('.list_checkbox:checked').map(function() {
			return this.value;
		}).get();

		for (var d = 0; d < beauty_rows; d++) {
			if ($("#" + type + "_chkbox" + d).prop('checked') == true) {
				chbox_sel = 'true';
				if (d >= org_beauty_rows) {
					$("#" + type + "_row" + (d + 1)).remove();
					delete_row = delete_row + 1;
				} else {
					del_flag = "true";
				}
			}
		}

		if (del_flag == "true") {
			var ans = window.confirm("Are you sure you want to delete?");
			if (ans) {
				$("#is_delete").val("yes");
				$("#del_chk").val(del_chk);
				$(".btnSaveRecord").trigger('click');
			}
		}

		if (chbox_sel == 'no') {
			alert('Please select checkbox to delete.');
			return false;
		}
		if (delete_row > 0) {
			$("#" + type + "_count").val((beauty_rows - delete_row));
		}
	}
</script>
@endpush