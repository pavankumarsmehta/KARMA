@extends('admin.layouts.app')
@section('content')
<form action="{{ route('admin.product.bulk_image_upload') }}" method="post" name="frmProductBulkImageUpload" id="frmProductBulkImageUpload" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
<input type="hidden" name="actType" id="actType" value="ImageUpload">
@csrf
	<div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2-5 col-xl-1-5">
							<i class="card-big-info-icon bx bxs-file-archive"></i>
							<h2 class="card-big-info-title">Product Bulk Image Upload</h2>
							<p class="card-big-info-desc">Browse products large images ZIP file.</p>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
							<div class="form-group row">
								{{--<!--<label class="col-lg-3 control-label text-lg-right pt-2" for="zipfile">Browse Products Large Images ZIP File</label>-->--}}
								<div class="col-lg-6">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="input-append">
											<div class="uneditable-input">
												<i class="fas fa-file fileupload-exists"></i>
												<span class="fileupload-preview"></span>
											</div>
											<span class="btn btn-default btn-file">
												<span class="fileupload-exists">Change</span>
												<span class="fileupload-new">Select file</span>
												<input type="file" name="zipfile" id="zipfile" accept="application/zip, application/x-zip, application/x-zip-compressed, multipart/x-zip, application/octet-stream">
											</span>
											<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
										@error('zipfile')
											<label class="error" for="thumb_image" role="alert">{{ $message }}</label>
										@enderror
										 <span class="help-block"><u><strong>Note :</strong></u></span>
										<ul class="help-block ml-n3">
											<li>Upload Only Large products images through <u><strong>ZIP file</strong></u></li>
											<li>Product Image name must be <strong>.jpg</strong> formats</li>
											<li>Product Image size should be <strong>{{ config('const.PRD_LARGE_MAX_WIDTH')}}px X {{ config('const.PRD_LARGE_MAX_WIDTH')}}px</strong></li>
										</ul>
										
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
			<button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-upload text-4 mr-2"></i> Upload File </button>
		</div>
        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"  class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
      </div>
 </form> 
@endsection

@push('scripts')
<script>
let url_list = "{{ route('admin.dashboard') }}";
</script>
<script src="{{ asset('admin/js/product_bulk_image_upload.js') }}"></script>
@endpush
