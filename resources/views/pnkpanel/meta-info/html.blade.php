@php
	if($get_data->type == "HO")
		$type_text = "Home Page";
	elseif($get_data->type == "NR")
		$type_text = "Normal Page";
	elseif($get_data->type == "PD")
		$type_text = "Product Detail Page";
	elseif($get_data->type == "CT")
		$type_text = "Category Page";
	elseif($get_data->type == "BR")
		$type_text = "Brand Page";
	elseif($get_data->type == "TS")
		$type_text = "Trade Show Page";
	
@endphp
<!--<div class="form-group row align-items-center">
	<label class="col-lg-12 control-label text-left mb-0"><strong>{{ $type_text }}</strong></label>
</div>-->
<div class="form-group row">
	<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_title">Meta Title</label>
	<div class="col-lg-7 col-xl-9">
		<textarea name="meta_title" id="meta_title" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_title', $get_data->meta_title)) }}</textarea>
		@error('meta_title')
		<label class="error" for="meta_title" role="alert">{{ $message }}</label>
		@enderror
	</div>
</div>
<div class="form-group row">
	<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_keywords">Meta Keywords</label>
	<div class="col-lg-7 col-xl-9">
		<textarea name="meta_keywords" id="meta_keywords" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_keywords', $get_data->meta_keywords)) }}</textarea>
		@error('meta_keywords')
		<label class="error" for="meta_keywords" role="alert">{{ $message }}</label>
		@enderror
	</div>
</div>
<div class="form-group row">
	<label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="meta_description">Meta Description</label>
	<div class="col-lg-7 col-xl-9">
		<textarea name="meta_description" id="meta_description" class="form-control form-control-modern" cols="80" rows="3">{{ stripslashes(old('meta_description', $get_data->meta_description)) }}</textarea>
		@error('meta_description')
		<label class="error" for="meta_description" role="alert">{{ $message }}</label>
		@enderror
	</div>
</div>