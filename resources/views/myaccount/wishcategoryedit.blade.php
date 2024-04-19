@extends('layouts.app')
@section('content')
<div class="container">
	@include('myaccount.breadcrumbs')
	<div class="myact mt-lg-0 mt-4 pb-sm-5 pb-0 mb-5">
		<div class="mb-3 hidden-md-up">
			<h2 tabindex="0">My Wishlist</h2>
		</div>
		@include('myaccount.myaccountmenu')
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
				<div class="pb-3 hidden-sm-down">
					<h2 tabindex="0">My Wishlist</h2>
				</div>
				@if (Session::has('success'))
				<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => Session::get('success')]" />
				@endif
				<form id="frmWishCategoryEdit" method="post" action="">
					<input type="hidden" name="action" value="EditCat" />
					<input type="hidden" name="wishcatid" value="{{$WishCat->wishlist_category_id}}" class="input" />
					@csrf
					<div class="pb-2">
						<label for="description" class="form-label">Category Name</label>

						<input type="text" id="name" name="name" placeholder="Category Name" class="form-control" value="{{ old('name') ? old('name') : $WishCat->name }}" />
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_name']"
						/>
						@if ($errors->has('name'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('name')]"
							/>
						@endif
						@error('name')
						<div class="invalid-feedback">{{$message}}</div>
						@enderror

					</div>

					<div class="pb-2">

						<label for="description" class="form-label">Description</label>
						<textarea id="description" name="description" placeholder="Description" class="form-control">{{ old('description') ? old('description') : $WishCat->description }}</textarea>
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_description']"
						/>
						@if ($errors->has('description'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('description')]"
							/>
						@endif
						@error('description')
						<div class="invalid-feedback">{{$message}}</div>
						@enderror
					</div>
					<div class="order-row pt-3 item-mid dfflex">
						<button type="button"  id="btnEditWishCategory" class="btn btn-primary btn-xs-block mb-2 mb-sm-0" title="Submit" aria-label="Submit">Submit</button>
						<a href="{{config('const.SITE_URL').'/wish-category.html'}}" class="text_c2" tabindex="0" title="Back" aria-label="Back">
									<svg class="svg_arrow_right me-1" aria-hidden="true" role="img" width="7" height="14">
										<use href="#svg_arrow_right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_right"></use>
									</svg>
									Back
								</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection