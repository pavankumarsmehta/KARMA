@php
	
	if($metainfo->type == "HO")
		$type_text = "Home Page";
	elseif($metainfo->type == "NR")
		$type_text = "Normal Page";
	elseif($metainfo->type == "PD")
		$type_text = "Product Detail Page";
	elseif($metainfo->type == "CT")
		$type_text = "Category Page";
	elseif($metainfo->type == "BR")
		$type_text = "Brand Page";
	elseif($metainfo->type == "TS")
		$type_text = "Trade Show Page";
	
@endphp

@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.meta-info.update') }}" method="post" name="frmMetaInfo" id="frmMetaInfo" class="ecommerce-form action-buttons-fixed" >
	<input type="hidden" name="type" id="type" value="HO">
	<input type="hidden" name="actType" id="actType" value="{{ $metainfo->type != '' ? 'update' : 'add' }}">
	@csrf
	<div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="tabs-modern row" style="min-height: 490px;" id="meta-info">
						<div class="col-lg-2-5 col-xl-1-5">
							<div class="nav flex-column" id="tabs" role="tablist" aria-orientation="vertical">

								<a class="nav-link active" id="home-page-tab" data-toggle="pill" href="#home-page" role="tab" aria-controls="home-page" aria-selected="false" data-type="HO">Home Page</a>

								<a class="nav-link" id="normal-page-tab" data-toggle="pill" href="#normal-page" role="tab" aria-controls="normal-page" aria-selected="false" data-type="NR">Normal Page</a>

								<a class="nav-link" id="product-detail-page-tab" data-toggle="pill" href="#product-detail-page" role="tab" aria-controls="product-detail-page" aria-selected="false" data-type="PD">Product Detail Page</a>

								<a class="nav-link" id="category-page-tab" data-toggle="pill" href="#category-page" role="tab" aria-controls="category-page" data-type="CT">Category Page</a>

								<a class="nav-link" id="brand-page-tab" data-toggle="pill" href="#brand-page" role="tab" aria-controls="brand-page" data-type="BR">Brand Page</a>
								<a class="nav-link" id="tradeshow-page-tab" data-toggle="pill" href="#tradeshow-page" role="tab" aria-controls="tradeshow-page" data-type="TS">Tradeshow Page</a>

	
							</div>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
							<div class="tab-content" id="tabContent">
								<div class="tab-pane fade show active" id="home-page" role="tabpanel" aria-labelledby="home-page-tab">
									<!--<div class="form-group row align-items-center">
										<label class="col-lg-12 control-label text-left mb-0"><strong>{{ $type_text }}</strong></label>
									</div>-->
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_title">Meta Title</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_title" id="meta_title" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_title', $metainfo->meta_title)) }}</textarea>
											@error('meta_title')
											<label class="error" for="meta_title" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_keywords">Meta Keywords</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_keywords" id="meta_keywords" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_keywords', $metainfo->meta_keywords)) }}</textarea>
											@error('meta_keywords')
											<label class="error" for="meta_keywords" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_description">Meta Description</label>
										<div class="col-lg-7 col-xl-9">
											<textarea name="meta_description" id="meta_description" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_description', $metainfo->meta_description)) }}</textarea>
											@error('meta_description')
											<label class="error" for="meta_description" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="normal-page" role="tabpanel" aria-labelledby="normal-page-tab">
								</div>
								<div class="tab-pane fade" id="product-detail-page" role="tabpanel" aria-labelledby="product-detail-page-tab">
								</div>
								<div class="tab-pane fade" id="category-page" role="tabpanel" aria-labelledby="category-page-tab">
								</div>
								
								<div class="tab-pane fade" id="brand-page" role="tabpanel" aria-labelledby="brand-page-tab">
								</div>
								
								<div class="tab-pane fade" id="sale-page" role="tabpanel" aria-labelledby="sale-page-tab">
								</div>
								<div class="tab-pane fade" id="tradeshow-page" role="tabpanel" aria-labelledby="tradeshow-page-tab">
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
			<button type="submit"
				class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Changes </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
	</div>

 </form> 
@endsection

@push('scripts')
<script>
var site_url = '<?= config("const.SITE_URL") ?>';
let url_edit = "{{ route('pnkpanel.meta-info.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.meta-info.update') }}";
let url_get_html = "{{ route('pnkpanel.meta-info.get-html') }}";

var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
if (err_msg1_for_cache != ""){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontmetainfocache',
		success: function(data) {
			console.log('metainfo cache clear sucessfully');
		}
	});
}
</script>
<script src="{{ asset('pnkpanel/js/meta_info.js') }}"></script>
@endpush
