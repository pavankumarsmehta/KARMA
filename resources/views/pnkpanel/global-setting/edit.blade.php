@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.global-setting.update') }}" method="post" name="frmGlobalSettings" id="frmGlobalSettings" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="update">
	@csrf
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Global Settings</h2>
				</header>
				<div class="card-body">
						
						@if(count($global_setting) > 0)
							@foreach($global_setting as $gs_key => $gs_value)
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="{{$gs_value['site_settings_id']}}">{{$gs_value["title"]}}</label>
										<div class="col-lg-6 col-xl-6">
								@if($gs_value['html_element'] == "Select") 
									@php
									 	$SelAry = explode("#", $gs_value['html_element_value']);
									@endphp
											<select name="{{$gs_value['site_settings_id']}}" id="{{$gs_value['site_settings_id']}}" class="form-control form-control-modern">
												@if(count($SelAry) > 0)
													@foreach($SelAry as $select_key => $select_value)
														@php
															$selected = ($select_value == $gs_value['setting']) ? 'selected' : '';
														@endphp
														<option value="{{ $select_value}}" {{ $selected }}>{{ $select_value}}</option>
													@endforeach
												@endif
											</select>
								@elseif($gs_value['html_element'] == "Textarea") 
											<textarea name="{{$gs_value['site_settings_id']}}" id="{{$gs_value['site_settings_id']}}" class="form-control" cols="80" rows="5">{{$gs_value['setting']}}</textarea>
								@elseif($gs_value['html_element'] == "File") 
											<div class="fileupload @if ( File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$gs_value['site_settings_id']) ) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$gs_value['site_settings_id'])) {{$gs_value['site_settings_id']}} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="{{$gs_value['site_settings_id']}}" id="{{$gs_value['site_settings_id']}}" >
													</span>
													@if (!empty($gs_value['site_settings_id']) && File::exists(config('const.PRESS_LARGE_IMAGE_PATH').$gs_value['site_settings_id']))
													<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="{{$gs_value['site_settings_id']}}" data-subtype="{{$gs_value['site_settings_id']}}" data-id="{{ $gs_value['site_settings_id'] }}" data-src="{{ config('const.PRESS_LARGE_IMAGE_URL').$gs_value['site_settings_id'] }}" data-caption="Large Image">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="{{$gs_value['site_settings_id']}}" data-subtype="{{$gs_value['site_settings_id']}}" data-id="{{ $gs_value['site_settings_id'] }}" data-image-name="{{$gs_value['site_settings_id']}}" data-dismiss="fileupload">Remove</a>
												</div>
												<span class="help-block">(Note: Upload only large images (.jpg) and minimum image size should be {{ config('const.PRESS_LARGE_MAX_WIDTH_SIZE')}})</span>
											</div>
								@else
											<input type="text" name="{{$gs_value['site_settings_id']}}" class="form-control" id="{{$gs_value['site_settings_id']}}" value="{{$gs_value['setting']}}" size="45" class="admin-input">
								@endif
											<span class="help-block">{{$gs_value["description"]}}</span>
										</div>
									</div>
							@endforeach
						@endif

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
		<!-- <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnResetForm" onclick="resetform('frmBulkMail');">Reset</a>
		</div> -->
	</div>

</form>
@endsection

@push('scripts')
<script>
var site_url = '<?= config("const.SITE_URL") ?>';
let url_edit = "{{ route('pnkpanel.global-setting.index') }}";
let url_update = "{{ route('pnkpanel.global-setting.update') }}";

//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 Start
var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
if (err_msg1_for_cache != ""){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcacheglobalsettings',
		success: function() {
			console.log('Global Settings cache clear sucessfully');

		}
	});
}
//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 End		
</script>
@endpush
