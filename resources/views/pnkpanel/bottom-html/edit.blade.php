@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.bottom-html.update') }}" method="post" name="frmSiteHtml" id="frmSiteHtml" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="Update_Text">
	@csrf
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Site HTML</h2>
				</header>
				<div class="card-body">
						
					<div class="form-group row">
						<label class="col-lg-12 control-label text-left mb-0" for="email"><strong>Top Html Settings</strong></label>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="home_top_html_text">Text</label>
						<div class="col-lg-9">
							<textarea name="home_top_html_text" id="home_top_html_text" class="form-control mceEditor" cols="80" rows="5">{{ stripslashes($top->home_html_text) }}</textarea>
							@error('home_top_html_text')
							<label class="error" for="home_top_html_text" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-12 control-label text-left mb-0" for="email"><strong>Bottom Html Settings</strong></label>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="home_html_text">Text</label>
						<div class="col-lg-9">
							<textarea name="home_html_text" id="home_html_text" class="form-control mceEditor" cols="80" rows="5">{{ stripslashes($bottom->home_html_text) }}</textarea>
							@error('home_html_text')
							<label class="error" for="home_html_text" role="alert">{{ $message }}</label>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Update </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_update = "{{ route('pnkpanel.bottom-html.update') }}";
</script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
@endpush
