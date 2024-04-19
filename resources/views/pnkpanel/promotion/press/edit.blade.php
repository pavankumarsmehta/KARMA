
@extends('admin.layouts.app')
@section('content')
<form action="{{ route('admin.press.update') }}" method="post" name="frmPress" id="frmPress" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="ipress_id" value="{{ $press->ipress_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $press->ipress_id > 0 ? 'update' : 'add' }}">
	@csrf
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Press Item Information</h2>
				</header>
				<div class="card-body">
						
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="vpress_name">Press Title <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('vpress_name') error @enderror" id="vpress_name" name="vpress_name" value="{{ old('vpress_name', $press->vpress_name) }}">
								@error('vpress_name')
								<label class="error" for="vpress_name" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="vpress_small_desc">Short Description </label>
							<div class="col-lg-9">
								<textarea name="vpress_small_desc" id="vpress_small_desc" class="form-control" cols="80" rows="5">{{ stripslashes(old('vpress_small_desc', $press->vpress_small_desc)) }}</textarea>
									<span class="help-block">(Note: small description about press)</span>
								@error('vpress_small_desc')
								<label class="error" for="category_name" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="vpress_image">Large Image</label>
							<div class="col-lg-6">
								<div class="fileupload @if (!empty($press->vpress_image) && File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$press->vpress_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
									<div class="input-append">
										<div class="uneditable-input">
											<i class="fas fa-file fileupload-exists"></i>
											<span class="fileupload-preview">@if (!empty($press->vpress_image) && File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$press->vpress_image))  {{ $press->vpress_image }} @endif</span>
										</div>
										<span class="btn btn-default btn-file">
											<span class="fileupload-exists">Change</span>
											<span class="fileupload-new">Select file</span>
											<input type="file" name="vpress_image" id="vpress_image" >
										</span>
										@if (!empty($press->vpress_image) && File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$press->vpress_image))
										<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="vpress_image" data-subtype="vpress_image" data-id="{{ $press->ipress_id }}" data-src="{{ config('const.PRESS_LARGE_IMAGE_URL').$press->vpress_image }}" data-caption="Large Image">View</a>
										@endif
										<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="vpress_image" data-subtype="vpress_image" data-id="{{ $press->ipress_id }}" data-image-name="{{ $press->vpress_image }}" data-dismiss="fileupload">Remove</a>
									</div>
									<span class="help-block">(Note: Upload only large images (.jpg) and minimum image size should be {{ config('const.PRESS_LARGE_MAX_WIDTH_SIZE')}})</span>
									@error('vpress_image')
										<label class="error" for="vpress_image" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="external_pdf">PDF File</label>
							<div class="col-lg-6">
								<div class="fileupload @if (!empty($press->pdf_file) && File::exists(config('const.PDF_FILE_PATH').$press->pdf_file)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
									<div class="input-append">
										<div class="uneditable-input">
											<i class="fas fa-file fileupload-exists"></i>
											<span class="fileupload-preview">@if (!empty($press->pdf_file) && File::exists(config('const.PDF_FILE_PATH').$press->pdf_file)) {{ $press->pdf_file }} @endif</span>
										</div>
										<span class="btn btn-default btn-file">
											<span class="fileupload-exists">Change</span>
											<span class="fileupload-new">Select file</span>
											<input type="file" name="external_pdf" id="external_pdf" >
										</span>
										@if (!empty($press->pdf_file) && File::exists(config('const.PDF_FILE_PATH').$press->pdf_file))
										<a href="{{config('const.PDF_FILE_URL').$press->pdf_file}}" target="_blank" class="btn btn-default fileupload-view" data-type="pdf_file" data-subtype="pdf_file" data-id="{{ $press->ipress_id }}" data-src="{{ config('const.PDF_FILE_URL').$press->pdf_file }}" data-caption="Banner Image">View</a>
										@endif
										<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="pdf_file" data-subtype="pdf_file" data-id="{{ $press->ipress_id }}" data-image-name="{{ $press->pdf_file }}" data-dismiss="fileupload">Remove</a>
									</div>
									@error('external_pdf')
										<label class="error" for="external_pdf" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right">&nbsp;</label>
							<div class="col-lg-6">OR</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="external_link">External Link </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('external_link') error @enderror" id="external_link" name="external_link" value="{{ old('external_link', $press->external_link) }}">
							</div>
						</div>

						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="display_position">Display Rank</label>
							<div class="col-lg-6">
								<input type="number"  name="display_position" id="display_position" value="{{ old('display_position', $press->display_position) }}" class="form-control @error('display_position') error @enderror">
								@error('display_position')
								<label class="error" for="display_position" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="status">Status</label>
							<div class="col-lg-7 col-xl-6">
								<select name="status" id="status" class="form-control form-control-modern">
									<option value="1" {{ old('status', $press->status) == '1' ? 'selected' : '' }}>
										Active</option>
									<option value="0" {{ old('status', $press->status) == '0' ? 'selected' : '' }}>
										Inactive</option>
								</select>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Press </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($press->ipress_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
				<a href="javascript:void(0);" data-id="{{ $press->ipress_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Press </a> 
		   </div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('admin.press.list') }}";
let url_edit = "{{ route('admin.press.edit', ':id') }}";
let url_update = "{{ route('admin.press.update') }}";
let url_delete = "{{ route('admin.press.delete', ':id') }}";
let url_delete_image  = "{{ route('admin.press.delete_image') }}";
</script>
<script src="{{ asset('admin/js/press_edit.js') }}"></script>
@endpush
