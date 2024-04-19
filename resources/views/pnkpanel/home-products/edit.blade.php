@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.home-products.update') }}" method="post" name="frmPress" id="frmPress" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="Update_Text">
	@csrf
	
	<div class="row">
		<div class="col">
				{{-- <header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Home Page Section</h2>
				</header> --}}
				<?php //echo "<pre>"; print_r($result); exit; ?>
				@if(count($result) > 0)
					@foreach($result as $key => $value)
						@if($key == 0)
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
									</div>
									<h2 class="card-title">FREE SHIPPING TEXT</h2>
								</header>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Description </label>
										<div class="col-lg-6">
											<textarea name="text{{$key}}" id="text{{$key}}" class="form-control @if($key != 10) mceEditor @endif" cols="80" rows="5">{{ stripslashes($value['text']) }}</textarea>
											@error('text{{$key}}')
											<label class="error" for="text{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
							</section>	
						@endif
						@if($key == 1)
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
									</div>
									<h2 class="card-title">QUIZ</h2>
								</header>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Title </label>
										<div class="col-lg-6">
											<input type="text" class="form-control" id="title{{$key}}" name="title{{$key}}" value="{{ $value['title'] }}">
											@error('title{{$key}}')
											<label class="error" for="title{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Description </label>
										<div class="col-lg-6">
											<textarea name="text{{$key}}" id="text{{$key}}" class="form-control @if($key != 10) mceEditor @endif" cols="80" rows="5">{{ stripslashes($value['text']) }}</textarea>
											@error('text{{$key}}')
											<label class="error" for="text{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="link{{$key}}">Button Name </label>
										<div class="col-lg-6">
											<input type="text" class="form-control" id="button_name{{$key}}" name="button_name{{$key}}" value="{{ $value['button_name'] }}">
											@error('button_name{{$key}}')
											<label class="error" for="button_name{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
							</section>	
						@endif
						
						
						
						@if($key == 2)
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
									</div>
									<h2 class="card-title">ABOUT HBA</h2>
								</header>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Title </label>
										<div class="col-lg-6">
											<input type="text" class="form-control" id="title{{$key}}" name="title{{$key}}" value="{{ $value['title'] }}">
											@error('title{{$key}}')
											<label class="error" for="title{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Description </label>
										<div class="col-lg-6">
											<textarea name="text{{$key}}" id="text{{$key}}" class="form-control @if($key != 10) mceEditor @endif" cols="80" rows="5">{{ stripslashes($value['text']) }}</textarea>
											@error('text{{$key}}')
											<label class="error" for="text{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="link{{$key}}">Button Name </label>
										<div class="col-lg-6">
											<input type="text" class="form-control" id="button_name{{$key}}" name="button_name{{$key}}" value="{{ $value['button_name'] }}">
											@error('button_name{{$key}}')
											<label class="error" for="button_name{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="link{{$key}}">Link </label>
										<div class="col-lg-6">
											<input type="text" class="form-control" id="link{{$key}}" name="link{{$key}}" value="{{ $value['link'] }}">
											@error('link{{$key}}')
											<label class="error" for="link{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
									<input type="hidden" name="image_name_old{{$key}}" id="image_name_old{{$key}}" value="{{$value['image_name']}}">
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="image_name{{$key}}">Image </label>
										<div class="col-lg-6">
											<div class="fileupload @if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name'])) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name'])) {{ $value['image_name'] ?? '' }} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="image_name{{$key}}" id="image_name{{$key}}" >
													</span>
													@if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name']))
													<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="cat_img" data-subtype="cat_img" data-id="{{ $value['home_title_id'] }}" data-src="{{ config('const.SITE_IMAGES_URL').'homeimg/'.$value['image_name'] }}" data-caption="Main Image">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="cat_img" data-subtype="cat_img" data-id="{{ $value['home_title_id'] }}" data-image-name="{{ $value['image_name'] }}" data-dismiss="fileupload">Remove</a>
													<span class="help-block"><b>Note:</b> Please Select Width: 623 X Height: 762 size of image</span>
												</div>
												@error('image_name{{$key}}')
												<label class="error" for="image_name{{$key}}" role="alert">{{ $message }}</label>
												@enderror
											</div>
										</div>
									</div>
								</div>
							</section>	
						@endif
					@endforeach
				@endif	
				
				{{-- <section class="card">
				<div class="card-body">
				<?php //dd($result);?>
					@if(count($result) > 0)
						@foreach($result as $key => $value)
							@if($value['home_flag'] == 'FREESHIPPING')
								@if($key != 0 && $key != 10)
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">Title {{$key + 1}} </label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="title{{$key}}" name="title{{$key}}" value="{{ $value['title'] }}">
										@error('title{{$key}}')
										<label class="error" for="title{{$key}}" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								@endif
							@endif
							<div class="form-group row">
								<label class="col-lg-3 control-label text-lg-right pt-2" for="title{{$key}}">@if($key == 10) Shop the Look @else Description @endif @if($key != 10) {{$key + 1}} @endif </label>
								<div class="col-lg-6">
									<textarea name="text{{$key}}" id="text{{$key}}" class="form-control @if($key != 10) mceEditor @endif" cols="80" rows="5">{{ stripslashes($value['text']) }}</textarea>
									@error('text{{$key}}')
									<label class="error" for="text{{$key}}" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							@if($key != 0 && $key != 1 && $key != 2 && $key != 10)
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2" for="link{{$key}}">Link {{$key + 1}} </label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="link{{$key}}" name="link{{$key}}" value="{{ $value['link'] }}">
										@error('link{{$key}}')
										<label class="error" for="link{{$key}}" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2" for="link{{$key}}">Button Name {{$key + 1}} </label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="button_name{{$key}}" name="button_name{{$key}}" value="{{ $value['button_name'] }}">
										@error('button_name{{$key}}')
										<label class="error" for="button_name{{$key}}" role="alert">{{ $message }}</label>
										@enderror
									</div>
								</div>
								@if($key != 6 && $key != 10) 
								<input type="hidden" name="image_name_old{{$key}}" id="image_name_old{{$key}}" value="{{$value['image_name']}}">
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2" for="image_name{{$key}}">Image {{$key + 1}} </label>
									<div class="col-lg-6">
										<div class="fileupload @if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name'])) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input">
													<i class="fas fa-file fileupload-exists"></i>
													<span class="fileupload-preview">@if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name'])) {{ $value['image_name'] ?? '' }} @endif</span>
												</div>
												<span class="btn btn-default btn-file">
													<span class="fileupload-exists">Change</span>
													<span class="fileupload-new">Select file</span>
													<input type="file" name="image_name{{$key}}" id="image_name{{$key}}" >
												</span>
												@if (!empty($value['image_name']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name']))
												<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="cat_img" data-subtype="cat_img" data-id="{{ $value['home_title_id'] }}" data-src="{{ config('const.SITE_IMAGES_URL').'homeimg/'.$value['image_name'] }}" data-caption="Main Image">View</a>
												@endif
												<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="cat_img" data-subtype="cat_img" data-id="{{ $value['home_title_id'] }}" data-image-name="{{ $value['image_name'] }}" data-dismiss="fileupload">Remove</a>
											</div>
											@error('image_name{{$key}}')
											<label class="error" for="image_name{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
								@endif
								
								@if($key != 4 && $key != 6 && $key != 7 && $key != 8 && $key != 9) 
								<input type="hidden" name="image_name_mob_old{{$key}}" id="image_name_old{{$key}}" value="{{$value['image_name_mob']}}">
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2" for="image_name_mob{{$key}}">Image Mobile {{$key + 1}} </label>
									<div class="col-lg-6">
										<div class="fileupload @if (!empty($value['image_name_mob']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name_mob'])) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input">
													<i class="fas fa-file fileupload-exists"></i>
													<span class="fileupload-preview">@if (!empty($value['image_name_mob']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name_mob'])) {{ $value['image_name_mob'] ?? '' }} @endif</span>
												</div>
												<span class="btn btn-default btn-file">
													<span class="fileupload-exists">Change</span>
													<span class="fileupload-new">Select file</span>
													<input type="file" name="image_name_mob{{$key}}" id="image_name_mob{{$key}}" >
												</span>
												@if (!empty($value['image_name_mob']) && File::exists(config('const.SITE_IMAGES_PATH').'homeimg/'.$value['image_name_mob']))
												<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="cat_img" data-subtype="cat_img" data-id="{{ $value['home_title_id'] }}" data-src="{{ config('const.SITE_IMAGES_URL').'homeimg/'.$value['image_name_mob'] }}" data-caption="Main Image">View</a>
												@endif
												<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="cat_img_mob" data-subtype="cat_img_mob" data-id="{{ $value['home_title_id'] }}" data-image-name="{{ $value['image_name_mob'] }}" data-dismiss="fileupload">Remove</a>
											</div>
											@error('image_name_mob{{$key}}')
											<label class="error" for="image_name_mob{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								</div>
								@endif
								@if($key == 6) 
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2" for="sku{{$key}}">Item SKU {{$key + 1}} </label>
										<div class="col-lg-6">
											<textarea name="sku{{$key}}" id="sku{{$key}}" class="form-control" cols="80" rows="5">{{ stripslashes($value['sku']) }}</textarea>
											<span class="help-block">(Note: Add comma seperated sku like MIR3002C,PAT2508B,CHA4003A)</span>
											@error('sku{{$key}}')
											<label class="error" for="sku{{$key}}" role="alert">{{ $message }}</label>
											@enderror
										</div>
									</div>
								@endif
							@endif
						@endforeach
					@endif
				</div>
				</section> --}}
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
let url_list = "{{ route('pnkpanel.home-products.index') }}";
let url_update = "{{ route('pnkpanel.home-products.update') }}";
let url_delete_image  = "{{ route('pnkpanel.home-products.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/manage_article_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
@endpush
