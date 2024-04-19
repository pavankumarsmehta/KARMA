@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.home-page-banner.update') }}" method="post" name="frmHomePageBanner" id="frmHomePageBanner" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="image_id" value="{{ $home_image->image_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $home_image->image_id > 0 ? 'update' : 'add' }}">
	@csrf
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Home Page Banner</h2>
				</header>
				<div class="card-body">
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="title">Title </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('title') error @enderror" id="title" name="title" value="{{ old('title', $home_image->title) }}">
									{{--
									@error('title')
								<label class="error" for="title" role="alert">{{ $message }}</label>
								@enderror
									--}}
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="image_alt_text">Image Alt Text </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('image_alt_text') error @enderror" id="image_alt_text" name="image_alt_text" value="{{ old('image_alt_text', $home_image->image_alt_text) }}">
								@error('image_alt_text')
								<label class="error" for="image_alt_text" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="banner_text">Banner Text </label>
							<div class="col-lg-9">
								<textarea name="banner_text" id="banner_text" class="form-control mceEditor" cols="80" rows="5">{{ stripslashes(old('banner_text', $home_image->banner_text)) }}</textarea>
								@error('banner_text')
								<label class="error" for="category_name" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="banner_position">Banner Position</label>
							<div class="col-lg-7 col-xl-6">
								<select name="banner_position" id="banner_position" class="form-control form-control-modern" onchange="getval(this);">
									<option value="HOME_MAIN" {{ old('bannerposition', $home_image->banner_position) == 'HOME_MAIN' ? 'selected' : '' }}>HOME MAIN SLIDER</option>
									<option value="HOME_MIDDLE" {{ old('status', $home_image->banner_position) == 'HOME_MIDDLE' ? 'selected' : '' }}>
									HOME PROMOTION</option>
									<option value="HOME_MIDDLE_WHOLESALER" {{ old('status', $home_image->banner_position) == 'HOME_MIDDLE_WHOLESALER' ? 'selected' : '' }}>HOME WHOLESALER</option>
									<option value="HOME_BOTTOM" {{ old('status', $home_image->banner_position) == 'HOME_BOTTOM' ? 'selected' : '' }}>HOME BOTTOM BEAUTY</option>
								</select>
							</div>
						</div>
						<div class="hide_extra">
							@if($home_image->banner_position != 'HOME_MIDDLE' && $home_image->banner_position != 'HOME_BOTTOM')
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
									for="display_position">Banner Text Display Position</label>
								<div class="col-lg-7 col-xl-6">
									<select name="display_position" id="display_position" class="form-control form-control-modern">
										<option value="LEFT_TOP" {{ old('displayposition', $home_image->display_position) == 'LEFT_TOP' ? 'selected' : '' }}>LEFT TOP</option>
										<option value="LEFT_BOTTOM" {{ old('status', $home_image->display_position) == 'LEFT_BOTTOM' ? 'selected' : '' }}>LEFT BOTTOM</option>
										<option value="RIGHT_TOP" {{ old('status', $home_image->display_position) == 'RIGHT_TOP' ? 'selected' : '' }}>RIGHT TOP</option>
										<option value="RIGHT_BOTTOM" {{ old('displayposition', $home_image->display_position) == 'RIGHT_BOTTOM' ? 'selected' : '' }}>RIGHT BOTTOM</option>
									</select>
								</div>
							</div>
							@endif
						</div>	
						<input type="hidden" name="home_image_old" id="home_image_old" value="{{$home_image->home_image}}">
						<div class="form-group row hide_extra_add pt-3">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="home_image">Image{{-- <span class="required">*</span> --}}</label>
							<div class="col-lg-6">
								<div class="fileupload @if (!empty($home_image->home_image) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
									<div class="input-append">
										<div class="uneditable-input">
											<i class="fas fa-file fileupload-exists"></i>
											<span class="fileupload-preview" id="home_image_file_preview">@if (!empty($home_image->home_image) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image))  {{ $home_image->home_image }} @endif</span>
										</div>
										<span class="btn btn-default btn-file">
											<span class="fileupload-exists">Change</span>
											<span class="fileupload-new">Select file</span>
											<input type="file" name="home_image" id="home_image" >
										</span>
										@if (!empty($home_image->home_image) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image))
										<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="home_image" data-subtype="home_image" data-id="{{ $home_image->image_id }}" data-src="{{ config('const.HOME_IMAGE_URL').$home_image->home_image }}" data-caption="Large Image">View</a>
										@endif
										<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="home_image" data-subtype="home_image" data-id="{{ $home_image->image_id }}" data-image-name="{{ $home_image->home_image }}" data-dismiss="fileupload">Remove</a>
									</div>
									
									<span class="help-block"><b>Note:</b> Please Select Width: <span class="desktop_image_width"></span> X Height: <span class="desktop_image_height"></span> size of image.</span>
									
									@error('home_image')
										<label class="error" for="home_image" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
						</div>
						<div class="hide_extra">
							<input type="hidden" name="home_image_mobile_old" id="home_image_mobile_old" value="{{$home_image->home_image_mobile}}">
							<div class="form-group row">
								<label class="col-lg-3 control-label text-lg-right pt-2" for="home_image_mobile">Mobile Image{{-- <span class="required">*</span> --}}</label>
								<div class="col-lg-6">
									<div class="fileupload @if (!empty($home_image->home_image_mobile) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image_mobile)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
										<div class="input-append">
											<div class="uneditable-input">
												<i class="fas fa-file fileupload-exists"></i>
												<span class="fileupload-preview" id="home_image_mobile_file_preview">@if (!empty($home_image->home_image_mobile) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image_mobile))  {{ $home_image->home_image_mobile }} @endif</span>
											</div>
											<span class="btn btn-default btn-file">
												<span class="fileupload-exists">Change</span>
												<span class="fileupload-new">Select file</span>
												<input type="file" name="home_image_mobile" id="home_image_mobile" >
											</span>
											@if (!empty($home_image->home_image_mobile) && File::exists(config('const.HOME_IMAGE_PATH').$home_image->home_image_mobile))
											<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="home_image_mobile" data-subtype="home_image_mobile" data-id="{{ $home_image->image_id }}" data-src="{{ config('const.HOME_IMAGE_URL').$home_image->home_image_mobile }}" data-caption="Large Image">View</a>
											@endif
											<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="home_image_mobile" data-subtype="home_image_mobile" data-id="{{ $home_image->image_id }}" data-image-name="{{ $home_image->home_image_mobile }}" data-dismiss="fileupload">Remove</a>
										</div>
										
										<span class="help-block"><b>Note:</b> Please Select Width: <span class="mobile_image_width"></span> X Height: <span class="mobile_image_height"></span> size of image.</span>
							
		
		
										{{-- <span class="help-block"><b>Note:</b> Please Select Width: {{ config('const.HOME_PAGE_MOBILE_BANNER_WIDTH')}} X Height: {{ config('const.HOME_PAGE_MOBILE_BANNER_HEIGHT')}} size of image.</span> --}} 
										{{--<span class="help-block">(Note: This field is only for home page main banner & site wide banner.)</span>--}}
										@error('home_image_mobile')
											<label class="error" for="home_image_mobile" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
							</div>
						</div>
						
						{{--
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="youtube_video">YouTube Embed Video URL </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('youtube_video') error @enderror" id="youtube_video" name="youtube_video" value="{{ old('youtube_video', $home_image->youtube_video) }}">
								<span class="help-block">(Ex.: https://www.youtube.com/embed/mF2XBqPzSns)</span>
							</div>
						</div>
						--}}
						@if($home_image->banner_position != 'HOME_BOTTOM')
						<div class="hide_extra">	
							<div class="form-group row">
								<label class="col-lg-3 control-label text-lg-right pt-2" for="video_url">Video URL </label>
								<div class="col-lg-6">
									<input type="text" class="form-control @error('video_url') error @enderror" id="video_url" name="video_url" value="{{ old('video_url', $home_image->video_url) }}">
								</div>
							</div>
						
							<div class="form-group row">
								<label class="col-lg-3 control-label text-lg-right pt-2" for="video_url_mobile">Video Mobile URL </label>
								<div class="col-lg-6">
									<input type="text" class="form-control @error('video_url_mobile') error @enderror" id="video_url_mobile" name="video_url_mobile" value="{{ old('video_url_mobile', $home_image->video_url_mobile) }}">
								</div>
							</div>
						</div>
						
						<div class="form-group row hide_extra_add pt-3">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="link">Link URL </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('link') error @enderror" id="link" name="link" value="{{ old('link', $home_image->link) }}">
							</div>
						</div>
						@endif
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="position">Display Rank</label>
							<div class="col-lg-6">
								<input type="number"  name="position" id="position" value="{{ old('position', $home_image->position) }}" class="form-control @error('position') error @enderror">
								@error('position')
								<label class="error" for="position" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="status">Status</label>
							<div class="col-lg-7 col-xl-6">
								<select name="status" id="status" class="form-control form-control-modern">
									<option value="1" {{ old('status', $home_image->status) == '1' ? 'selected' : '' }}>
										Active</option>
									<option value="0" {{ old('status', $home_image->status) == '0' ? 'selected' : '' }}>
										Inactive</option>
								</select>
							</div>
						</div>

						<div class="form-group row" style="display:none;">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="name">Short Page Name (for
                                    URL)</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('name') error @enderror" id="name"
                                        name="name" value="{{ old('email', $home_image->name) }}">
                                    @error('name')
                                        <label class="error" for="name" role="alert">{{ $message }}</label>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Image </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($home_image->image_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
				<a href="javascript:void(0);" data-id="{{ $home_image->image_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Image </a> 
		   </div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.home-page-banner.list') }}";
let url_edit = "{{ route('pnkpanel.home-page-banner.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.home-page-banner.update') }}";
let url_delete = "{{ route('pnkpanel.home-page-banner.delete', ':id') }}";
let url_delete_image  = "{{ route('pnkpanel.home-page-banner.delete_image') }}";

let home_page_desktop_width = "{{ config('const.HOME_PAGE_DESKTOP_BANNER_WIDTH') }}";
let home_page_desktop_height = "{{ config('const.HOME_PAGE_DESKTOP_BANNER_HEIGHT') }}";

let home_page_mobile_width = "{{ config('const.HOME_PAGE_MOBILE_BANNER_WIDTH') }}";
let home_page_mobile_height = "{{ config('const.HOME_PAGE_MOBILE_BANNER_HEIGHT') }}";

let promotion_desktop_width = "{{ config('const.HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_WIDTH') }}";
let promotion_desktop_height = "{{ config('const.HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_HEIGHT') }}";

let promotion_mobile_width = "{{ config('const.HOME_PAGE_MIDDLE_MOBILE_PROMOTION_WIDTH') }}";
let promotion_mobile_height = "{{ config('const.HOME_PAGE_MIDDLE_MOBILE_PROMOTION_HEIGHT') }}";

let wholesaler_desktop_width = "{{ config('const.HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_WIDTH') }}";
let wholesaler_desktop_height = "{{ config('const.HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_HEIGHT') }}";

let wholesaler_mobile_width = "{{ config('const.HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_WIDTH') }}";
let wholesaler_mobile_height = "{{ config('const.HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_HEIGHT') }}";

let beauty_desktop_width = "{{ config('const.HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_WIDTH') }}";
let beauty_desktop_height = "{{ config('const.HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_HEIGHT') }}";

let beauty_mobile_width = "{{ config('const.HOME_PAGE_BOTTOM_MOBILE_BEAUTY_WIDTH') }}";
let beauty_mobile_height = "{{ config('const.HOME_PAGE_BOTTOM_MOBILE_BEAUTY_HEIGHT') }}";

$('.desktop_image_width').text(home_page_desktop_width);
$('.desktop_image_height').text(home_page_desktop_height);

$('.mobile_image_width').text(home_page_mobile_width);
$('.mobile_image_height').text(home_page_mobile_height);


$('select#banner_position').on('change', function() {
	
	var banner_position = $( "#banner_position option:selected" ).val();
	banner_position = banner_position.trim();
	
	if(banner_position=='HOME_MAIN'){		
		$('.desktop_image_width').text(home_page_desktop_width);
		$('.desktop_image_height').text(home_page_desktop_height);
		
		$('.mobile_image_width').text(home_page_mobile_width);
		$('.mobile_image_height').text(home_page_mobile_height);		
	}	
	else if (banner_position == 'HOME_MIDDLE') {
		$('.desktop_image_width').text(promotion_desktop_width);
		$('.desktop_image_height').text(promotion_desktop_height);
		
		$('.mobile_image_width').text(promotion_mobile_width);
		$('.mobile_image_height').text(promotion_mobile_height);
	}
	else if (banner_position == 'HOME_MIDDLE_WHOLESALER') {
		$('.desktop_image_width').text(wholesaler_desktop_width);
		$('.desktop_image_height').text(wholesaler_desktop_height);
		
		$('.mobile_image_width').text(wholesaler_mobile_width);
		$('.mobile_image_height').text(wholesaler_mobile_height);
	}
	else if (banner_position == 'HOME_BOTTOM') {
		$('.desktop_image_width').text(beauty_desktop_width);
		$('.desktop_image_height').text(beauty_desktop_height);
		
		$('.mobile_image_width').text(beauty_mobile_width);
		$('.mobile_image_height').text(beauty_mobile_height);
	}	

});

$( document ).ready(function() {
	var banner_position = $("#banner_position").val();
	banner_position = banner_position.trim();
	
	if(banner_position=='HOME_MAIN'){		
		$('.desktop_image_width').text(home_page_desktop_width);
		$('.desktop_image_height').text(home_page_desktop_height);
		
		$('.mobile_image_width').text(home_page_mobile_width);
		$('.mobile_image_height').text(home_page_mobile_height);
	}	
	else if (banner_position == 'HOME_MIDDLE') {
		$('.desktop_image_width').text(promotion_desktop_width);
		$('.desktop_image_height').text(promotion_desktop_height);
		
		$('.mobile_image_width').text(promotion_mobile_width);
		$('.mobile_image_height').text(promotion_mobile_height);
	}
	else if (banner_position == 'HOME_MIDDLE_WHOLESALER') {
		$('.desktop_image_width').text(wholesaler_desktop_width);
		$('.desktop_image_height').text(wholesaler_desktop_height);
		
		$('.mobile_image_width').text(wholesaler_mobile_width);
		$('.mobile_image_height').text(wholesaler_mobile_height);
	}
	else if (banner_position == 'HOME_BOTTOM') {
		$('.desktop_image_width').text(beauty_desktop_width);
		$('.desktop_image_height').text(beauty_desktop_height);
		
		$('.mobile_image_width').text(beauty_mobile_width);
		$('.mobile_image_height').text(beauty_mobile_height);
	}
	
    getvalLoad(banner_position);
});

function getvalLoad(banner_position) {
    var value = banner_position;
	if(value == 'HOME_MAIN')
	{
		$('.hide_extra').show();
		$('.hide_extra_add').addClass('pt-3');
	}
	else if(value == 'HOME_BOTTOM')
	{
		$('.hide_extra').hide();
		$('.hide_extra_add').removeClass('pt-3');
	}
	else if(value == 'HOME_BOTTOM')
	{
		
	}
}
</script>
<script src="{{ asset('pnkpanel/js/home_page_banner_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
<script type="text/javascript">
	var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
	//alert(err_msg1_for_cache);
	
		if (err_msg1_for_cache != ""){
			$.ajax({
				type: 'POST',
				url: site_url + '/clearfrontcachehomepagebanner',
				success: function(data) {
					console.log('product homepagebanner cache clear sucessfully');

				}
			});
		}
</script>
@endpush
