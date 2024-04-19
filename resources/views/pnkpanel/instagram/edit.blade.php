@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.instagram-settings.update') }}" method="post" name="frmGlobalSettings" id="frmGlobalSettings" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="update">
	@csrf
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Instagram Settings</h2>
				</header>
				<div class="card-body">
				
						@if(count($instagram_settings) > 0)
						@foreach($instagram_settings as $is_key => $is_value)
						<div class="form-group row">
						@if($is_value['var_name']=='INSTAGRAM_STATUS')
							<label class="col-lg-3 control-label text-lg-right pt-2" for="1">{{$is_value['title']}}</label>
							<div class="col-lg-6 col-xl-6">
							<select name="{{$is_value['instagram_settings_id']}}" class="form-control form-control-modern">
											<option {{($is_value['setting'] == 'Enable' ? 'selected' : '')}} value="Enable">Enable</option>
											<option {{($is_value['setting'] == 'Disable' ? 'selected' : '')}} value="Disable">Disable</option>
							</select>
							</div>
						@elseif($is_value['var_name']=='INSTAGRAM_FETCH_BY')
						<label class="col-lg-3 control-label text-lg-right pt-2" for="1">{{$is_value['title']}}</label>
						<div class="col-lg-6 col-xl-6">
						<select name="{{$is_value['instagram_settings_id']}}" class="form-control form-control-modern">
											<option {{($is_value['setting'] == 'My User name' ? 'selected' : '')}} value="My User name">My User name</option>
							</select>
							</div>
						@elseif($is_value['var_name']=='IS_SHOW_HOME_PAGE')
						<label class="col-lg-3 control-label text-lg-right pt-2" for="1">{{$is_value['title']}}</label>
						<div class="col-lg-6 col-xl-6">
						<select name="{{$is_value['instagram_settings_id']}}" class="form-control form-control-modern">
							<option {{($is_value['setting'] == 'Yes' ? 'selected' : '')}} value="Yes">Yes</option>
							<option {{($is_value['setting'] == 'No' ? 'selected' : '')}} value="No">No</option>
						</select>
						</div>
						@else
						<label class="col-lg-3 control-label text-lg-right pt-2" for="1">{{$is_value['title']}}</label>
						<div class="col-lg-6 col-xl-6">
						<input type="text" name="{{$is_value['instagram_settings_id']}}" value="{{$is_value['setting']}}" size="45" maxlength="255"  class="form-control">
						</div>
						@endif
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
let url_edit = "{{ route('pnkpanel.instagram-settings.edit') }}";
let url_update = "{{ route('pnkpanel.instagram-settings.update') }}";

//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 Start
var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
if (err_msg1_for_cache != ""){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontcacheinstasettings',
		success: function() {
			console.log('Instagram Settings cache clear sucessfully');

		}
	});
}
//Added as per 'Shoppe Edits' basecamp point as on 27-04-2023 End		
</script>
@endpush
