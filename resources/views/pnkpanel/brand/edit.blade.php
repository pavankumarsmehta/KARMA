@extends('pnkpanel.layouts.app')
@section('content')

<form action="{{ route('pnkpanel.brand.update') }}" method="post" name="frmBrand" id="frmBrand" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="brand_id" value="{{ $brand->brand_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $brand->brand_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" id="is_delete" name="is_delete" value="no">
	@csrf

	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Brand Information</h2>
				</header>
				<div class="card-body">


					<div class="form-group row">
						<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="brand_name">Brand Name <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('brand_name') error @enderror" id="brand_name" name="brand_name" value="{{ old('brand_name', $brand->brand_name) }}">
							@error('brand_name')
							<label class="error" for="brand_name" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="brand_description">Brand Description </label>
						<div class="col-lg-9">
							<textarea name="brand_description" id="brand_description" class="mceEditor" cols="80" rows="5">{{ stripslashes(old('brand_description', $brand->brand_description)) }}</textarea>
							@error('brand_description')
							<label class="error" for="brand_description" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="brand_logo_image">Brand Logo Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($brand->brand_logo_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_logo_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($brand->brand_logo_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_logo_image)) {{ $brand->brand_logo_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="brand_logo_image" id="brand_logo_image">
									</span>
									@if (!empty($brand->brand_logo_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_logo_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="brand_logo_image" data-subtype="brand_logo_image" data-id="{{ $brand->brand_id }}" data-src="{{ config('const.BRAND_IMAGE_URL').$brand->brand_logo_image }}" data-caption="Brand Logo Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="brand_logo_image" data-subtype="brand_logo_image" data-id="{{ $brand->brand_id }}" data-image-name="{{ $brand->brand_logo_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.BRAND_IMAGE_THUMB_WIDTH')}} X {{ config('const.BRAND_IMAGE_THUMB_HEIGHT')}})</span>
								@error('brand_logo_image')
								<label class="error" for="brand_logo_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="brand_banner_image">Brand Banner Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($brand->brand_banner_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_banner_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($brand->brand_banner_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_banner_image)) {{ $brand->brand_banner_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="brand_banner_image" id="brand_banner_image">
									</span>
									@if (!empty($brand->brand_banner_image) && File::exists(config('const.BRAND_IMAGE_PATH').$brand->brand_banner_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="brand_banner_image" data-subtype="brand_banner_image" data-id="{{ $brand->brand_id }}" data-src="{{ config('const.BRAND_IMAGE_URL').$brand->brand_banner_image }}" data-caption="Banner Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="brand_banner_image" data-subtype="brand_banner_image" data-id="{{ $brand->brand_id }}" data-image-name="{{ $brand->brand_banner_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.BRAND_BANNER_WIDTH')}} X {{ config('const.BRAND_BANNER_HEIGHT')}})</span>
								@error('brand_banner_image')
								<label class="error" for="brand_banner_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="display_position">Display Position <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('display_position') error @enderror" id="display_position" name="display_position" value="{{ old('display_position', $brand->display_position) }}">
							@error('display_position')
							<label class="error" for="display_position" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
						<div class="col-lg-7 col-xl-6">
							<select name="status" id="status" class="form-control form-control-modern">
								<option value="1" {{ old('status', $brand->status) == '1' ? 'selected' : '' }}>
									Active</option>
								<option value="0" {{ old('status', $brand->status) == '0' ? 'selected' : '' }}>
									Inactive</option>
							</select>
						</div>
					</div>

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_home">Display On Home</label>
						<div class="col-lg-7 col-xl-6">
							<select name="display_on_home" id="display_on_home" class="form-control form-control-modern">
								<option value="Yes" {{ old('display_on_home', $brand->display_on_home) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('display_on_home', $brand->display_on_home) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div>
					
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="display_on_category">Display On Category</label>
						<div class="col-lg-7 col-xl-6">
							<select name="display_on_category" id="display_on_category" class="form-control form-control-modern">
								<option value="Yes" {{ old('display_on_category', $brand->display_on_category) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('display_on_category', $brand->display_on_category) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div>
	
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="is_popular">Popular Brand for Menu</label>
						<div class="col-lg-7 col-xl-6">
							<select name="is_popular" id="is_popular" class="form-control form-control-modern">
								<option value="Yes" {{ old('is_popular', $brand->is_popular) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('is_popular', $brand->is_popular) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
						</div>
					</div>
	
					<div class="form-group row align-items-center">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_title">Meta Title </label>
						<div class="col-lg-6">
							<textarea name="meta_title" id="meta_title" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_title', $brand->meta_title)) }}</textarea>
							@error('meta_title')
							<label class="error" for="meta_title" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_keywords">Meta Keywords </label>
						<div class="col-lg-6">
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_keywords', $brand->meta_keywords)) }}</textarea>
							@error('meta_keywords')
							<label class="error" for="meta_keywords" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="meta_description">Meta Description </label>
						<div class="col-lg-6">
							<textarea name="meta_description" id="meta_description" class="form-control" cols="80" rows="3">{{ stripslashes(old('meta_description', $brand->meta_description)) }}</textarea>
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
		@if($brand->brand_id > 0)
		<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
			<a href="javascript:void(0);" data-id="{{ $brand->brand_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a>
		</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
	let url_list = "{{ route('pnkpanel.brand.list') }}";
	let url_edit = "{{ route('pnkpanel.brand.edit', ':id') }}";
	let url_update = "{{ route('pnkpanel.brand.update') }}";
	let url_delete = "{{ route('pnkpanel.brand.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.brand.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/brand_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
<script type="text/javascript">
	var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
		if (err_msg1_for_cache != ""){
			$.ajax({
				type: 'POST',
				data: {
		        "_token": "{{ csrf_token() }}",
		       "brand_id": "{{ $brand->brand_id }}"
		        },
				url: site_url + '/clearfrontcachebrandmenu',
				success: function(data) {
					console.log('Brand menu  cache clear sucessfully');

				}
			});
		}
</script>
@endpush