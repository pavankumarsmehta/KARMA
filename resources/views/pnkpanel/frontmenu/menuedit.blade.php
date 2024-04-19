@extends('pnkpanel.layouts.app')
@section('content')

@if($create_subcat=='Yes')
	@php
		$parentcat=App\Models\Frontmenu::where('menu_id','=',$parent_id)->first();
		$parentcat_title=$parentcat->menu_title;
	@endphp	
@else
	@php
	  	$parentcat_title='';
	@endphp	
@endif	
<form action="{{ route('pnkpanel.frontmenu.update') }}" method="post" name="frmBrand" id="frmBrand" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="menu_id" value="{{ $frontmenu->menu_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $frontmenu->menu_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" id="is_delete" name="is_delete" value="no">
	@csrf

	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Front menu Information</h2>
				</header>
				<div class="card-body">


					<div class="form-group row">
						<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="parent_id">Parent Category <span class="required">*</span></label>
						<div class="col-lg-6">
							<select name="parent_id" id="parent_id" class="form-control form-control-modern">
								<option value="0" selected>&raquo;&nbsp;Add as a Parent Category</option>
								
								 @if($create_subcat=='Yes')
								 @php
								 	$records = App\Models\Frontmenu::where('parent_id', '=', '0')->orderBy('menu_title', 'asc')->with(['childrenRecursive' => function ($query) {
									$query->orderBy('menu_title', 'asc');
									}])->get();
									echo implode(App\Http\Controllers\Pnkpanel\MenuListController::drawCategoryTreeDropdown($records, 0, old('parent_id', $parent_id)));
								@endphp	
								@elseif($create_subcat=='No')
								@php
									$records = App\Models\Frontmenu::where('parent_id', '=', '0')->orderBy('menu_title', 'asc')->with(['childrenRecursive' => function ($query) {
									$query->orderBy('menu_title', 'asc');
									}])->get();
									
									echo implode(App\Http\Controllers\Pnkpanel\MenuListController::drawCategoryTreeDropdown($records, 0, old('parent_id', $frontmenu->parent_id)));

								@endphp	
								@endif	
								
							</select>

							@error('parent_id')
							<label class="error" for="parent_id" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="menu_title">Menu Title <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('menu_title') error @enderror" id="menu_title" name="menu_title" value="{{ old('menu_title', $frontmenu->menu_title) }}">
							@error('menu_title')
							<label class="error" for="menu_title" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">

					<label class="col-lg-3 control-label text-lg-right pt-2" for="menu_title">Select Category</label>
						<div class="col-lg-6">

							<select name="category_id" id="category_id" class="form-control">
								<option value="" selected>----- Select Category -----</option>
								@php
									$records = App\Models\Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
										$query->orderBy('category_name', 'asc');
									}])->get();
									echo implode(App\Http\Controllers\pnkpanel\CategoryController::drawCategoryTreeDropdownWithLink($records, 0,$frontmenu->category_id));
								@endphp
							</select>
						</div>
						
						
					</div>
					<div class="form-group row">

					<label class="col-lg-3 control-label text-lg-right pt-2" for="menu_title">Select Brand</label>
						<div class="col-lg-6">
							<select name="brand_id" id="brand_id" class="form-control">
								<option value="" selected>----- Select Brand -----</option>
								@php 
								$BrandsList = BrandsList();
								@endphp
								@if(isset($BrandsList) && !empty($BrandsList))	
									@foreach($BrandsList as $key => $BrandAlfa)   
										@foreach($BrandAlfa as $Brand)
											<option data-url='{{$Brand["Link"]}}'{{ ($Brand["Brand_id"]== $frontmenu->brand_id) ? 'selected' : '' }}  value='{{$Brand["Brand_id"]}}'>{{$Brand["Name"]}}</option>
										@endforeach
									@endforeach
								@endif
							</select>
						</div>
						
						
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="menu_link">Menu Link </label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('menu_link') error @enderror" id="menu_link" name="menu_link" value="{{ old('menu_link', $frontmenu->menu_link) }}">
							@error('menu_link')
							<label class="error" for="menu_link" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="rank">Rank <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('rank') error @enderror" id="rank" name="rank" value="{{ old('rank', $frontmenu->rank) }}">
							@error('rank')
							<label class="error" for="rank" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="is_label">Is lable?</label>
						<div class="col-lg-7 col-xl-6">
							<select name="is_label" id="is_label" class="form-control form-control-modern">
								<option value="0" {{ old('is_label', $frontmenu->is_label) == '0' ? 'selected' : '' }}> No</option>
								<option value="1" {{ old('is_label', $frontmenu->is_label) == '1' ? 'selected' : '' }}> Yes</option>
							</select>
							<span>Display as bold label</span>
							@error('is_label')
							<label class="error" for="is_label" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
						<div class="col-lg-7 col-xl-6">
							<select name="status" id="status" class="form-control form-control-modern">
								<option value="1" {{ old('status', $frontmenu->status) == '1' ? 'selected' : '' }}>
									Active</option>
								<option value="0" {{ old('status', $frontmenu->status) == '0' ? 'selected' : '' }}>
									Inactive</option>
							</select>
							@error('status')
							<label class="error" for="status" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="is_banner">Is Banner ?</label>
						<div class="col-lg-7 col-xl-6">
							<select name="is_banner" id="is_banner" class="form-control form-control-modern">
								<option value="Yes" {{ old('is_banner', $frontmenu->is_banner) == 'Yes' ? 'selected' : '' }}>
									Yes</option>
								<option value="No" {{ old('is_banner', $frontmenu->is_banner) == 'No' ? 'selected' : '' }}>
									No</option>
							</select>
							@error('is_banner')
							<label class="error" for="is_banner" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					 @if($parentcat_title=='Custom Tag Link - Banner Section' OR $frontmenu->is_banner=='Yes')
					<div class="form-group row">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2" for="menu_image">Menu Image</label>
						<div class="col-lg-7 col-xl-6">
							<div class="fileupload @if (!empty($frontmenu->menu_image) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($frontmenu->menu_image) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image)) {{ $frontmenu->menu_image }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="menu_image" id="menu_image">
									</span>
									@if (!empty($frontmenu->menu_image) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="menu_image" data-subtype="menu_image" data-id="{{ $frontmenu->menu_id }}" data-src="{{ config('const.MENUIMAGE_URL').$frontmenu->menu_image }}" data-caption="Menu Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="menu_image" data-subtype="menu_image" data-id="{{ $frontmenu->menu_id }}" data-image-name="{{ $frontmenu->menu_image }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.MENUIMAGE_WIDTH')}} X {{ config('const.MENUIMAGE_HEIGHT')}})</span>
								@error('menu_image')
								<label class="error" for="menu_image" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2" for="menu_image1">Menu Image1</label>
						<div class="col-lg-7 col-xl-6">
							<div class="fileupload @if (!empty($frontmenu->menu_image1) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image1)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($frontmenu->menu_image1) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image1)) {{ $frontmenu->menu_image1 }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="menu_image1" id="menu_image1">
									</span>
									@if (!empty($frontmenu->menu_image1) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image1))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="menu_image1" data-subtype="menu_image1" data-id="{{ $frontmenu->menu_id }}" data-src="{{ config('const.MENUIMAGE_URL').$frontmenu->menu_image1 }}" data-caption="Menu Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="menu_image1" data-subtype="menu_image1" data-id="{{ $frontmenu->menu_id }}" data-image-name="{{ $frontmenu->menu_image1 }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.MENUIMAGE_WIDTH')}} X {{ config('const.MENUIMAGE_HEIGHT')}})</span>
								@error('menu_image1')
								<label class="error" for="menu_image1" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2" for="menu_image2">Menu Image2</label>
						<div class="col-lg-7 col-xl-6">
							<div class="fileupload @if (!empty($frontmenu->menu_image2) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image2)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($frontmenu->menu_image2) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image2)) {{ $frontmenu->menu_image2 }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="menu_image2" id="menu_image2">
									</span>
									@if (!empty($frontmenu->menu_image2) && File::exists(config('const.MENUIMAGE_PATH').$frontmenu->menu_image2))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="menu_image2" data-subtype="menu_image2" data-id="{{ $frontmenu->menu_id }}" data-src="{{ config('const.MENUIMAGE_URL').$frontmenu->menu_image2 }}" data-caption="Menu Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="menu_image2" data-subtype="menu_image2" data-id="{{ $frontmenu->menu_id }}" data-image-name="{{ $frontmenu->menu_image2 }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.MENUIMAGE_WIDTH')}} X {{ config('const.MENUIMAGE_HEIGHT')}})</span>
								@error('menu_image2')
								<label class="error" for="menu_image2" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-lg-4 col-xl-4">
							<label for="menu_label">Menu Label : </label>
							<input type="text" class="form-control @error('menu_label') error @enderror" id="menu_label" name="menu_label" value="{{ old('menu_label', $frontmenu->menu_label) }}">
							@error('rank')
							<label class="error" for="menu_label" role="alert">{{ $message }}</label>
							@enderror
						</div>
						<div class="col-lg-4 col-xl-4">
							<label  for="menu_label1">Menu Label1 : </label>
							<input type="text" class="form-control @error('menu_label1') error @enderror" id="menu_label1" name="menu_label1" value="{{ old('menu_label1', $frontmenu->menu_label1) }}">
							@error('rank')
							<label class="error" for="menu_label1" role="alert">{{ $message }}</label>
							@enderror
						</div>
						<div class="col-lg-4 col-xl-4">
							<label for="menu_label2">Menu Label2 : </label>
							<input type="text" class="form-control @error('menu_label2') error @enderror" id="menu_label2" name="menu_label2" value="{{ old('menu_label2', $frontmenu->menu_label2) }}">
							@error('rank')
							<label class="error" for="menu_label2" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<div class="col-lg-4 col-xl-4">
							<label for="menu_custom_link">Menu link : </label>
							<input type="text" class="form-control @error('menu_custom_link') error @enderror" id="menu_custom_link" name="menu_custom_link" value="{{ old('menu_custom_link', $frontmenu->menu_custom_link) }}">
							@error('rank')
							<label class="error" for="menu_custom_link" role="alert">{{ $message }}</label>
							@enderror
						</div>
						
						<div class="col-lg-4 col-xl-4">
							<label for="menu_custom_link1">Menu link1 : </label>
							<input type="text" class="form-control @error('menu_custom_link1') error @enderror" id="menu_custom_link1" name="menu_custom_link1" value="{{ old('menu_custom_link1', $frontmenu->menu_custom_link1) }}">
							@error('rank')
							<label class="error" for="menu_custom_link1" role="alert">{{ $message }}</label>
							@enderror
						</div>
						<div class="col-lg-4 col-xl-4">
							<label for="menu_custom_link2">Menu link2 : </label>
							<input type="text" class="form-control @error('menu_custom_link2') error @enderror" id="menu_custom_link2" name="menu_custom_link2" value="{{ old('menu_custom_link2', $frontmenu->menu_custom_link2) }}">
							@error('rank')
							<label class="error" for="menu_custom_link2" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					@endif	
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
		@if($frontmenu->brand_id > 0)
		<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
			<a href="javascript:void(0);" data-id="{{ $frontmenu->brand_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a>
		</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
	let url_list = "{{ route('pnkpanel.frontmenu.menulist') }}";
	let url_edit = "{{ route('pnkpanel.frontmenu.menuedit', ':id',':parent_id') }}";
	let url_update = "{{ route('pnkpanel.frontmenu.update') }}";
	let url_delete = "{{ route('pnkpanel.frontmenu.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.frontmenu.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/frontmenu_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
<script type="text/javascript">
	var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
		if (err_msg1_for_cache != ""){
			$.ajax({
				type: 'POST',
				data: {
		        "_token": "{{ csrf_token() }}",
		       
		        },
				url: site_url + '/clearfrontcachemenu',
				success: function(data) {
					console.log('menu  cache clear sucessfully');

				}
			});
		}
</script>
@endpush