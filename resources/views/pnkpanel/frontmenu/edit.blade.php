@extends('pnkpanel.layouts.app')
@section('content')

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
								@php
								$records = App\Models\Frontmenu::where('parent_id', '=', '0')->orderBy('menu_title', 'asc')->with(['childrenRecursive' => function ($query) {
								$query->orderBy('menu_title', 'asc');
								}])->get();
								echo implode(App\Http\Controllers\Pnkpanel\FrontmenuController::drawCategoryTreeDropdown($records, 0, old('parent_id', $frontmenu->parent_id)));
								@endphp
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
						<label class="col-lg-3 control-label text-lg-right pt-2" for="menu_link">Menu Link </label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('menu_link') error @enderror" id="menu_link" name="menu_link" value="{{ old('menu_link', $frontmenu->menu_link) }}">
							@error('menu_link')
							<label class="error" for="menu_link" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="rank">Rank </label>
						<div class="col-lg-6">
							<input type="text" class="form-control @error('rank') error @enderror" id="rank" name="rank" value="{{ old('rank', $frontmenu->rank) }}">
							@error('rank')
							<label class="error" for="rank" role="alert">{{ $message }}</label>
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
						</div>
					</div>
	

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
	let url_list = "{{ route('pnkpanel.brand.list') }}";
	let url_edit = "{{ route('pnkpanel.brand.edit', ':id') }}";
	let url_update = "{{ route('pnkpanel.brand.update') }}";
	let url_delete = "{{ route('pnkpanel.brand.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.brand.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/brand_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
@endpush