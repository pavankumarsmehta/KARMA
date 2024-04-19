@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.shipping-method.update') }}" method="post" name="frmHomePageBanner" id="frmHomePageBanner" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="{{ $shipping_mode->shipping_mode_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" name="shipping_mode_id" value="{{$shipping_mode->shipping_mode_id}}">
	@csrf
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Shipping Method</h2>
				</header>
				<div class="card-body">
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_title">Shipping Title </label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('shipping_title') error @enderror" id="shipping_title" name="shipping_title" value="{{ old('shipping_title', $shipping_mode->shipping_title) }}">
								@error('shipping_title')
									<label class="error" for="shipping_title" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="detail">Detail </label>
							<div class="col-lg-9">
								<textarea name="detail" id="detail" class="form-control" cols="80" rows="5">{{ stripslashes(old('detail', $shipping_mode->detail)) }}</textarea>
								@error('detail')
								<label class="error" for="detail" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="display_position">Display Rank</label>
							<div class="col-lg-6">
								<input type="number"  name="display_position" id="display_position" value="{{ old('display_position', $shipping_mode->display_position) }}" class="form-control @error('display_position') error @enderror">
								@error('display_position')
								<label class="error" for="display_position" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
								<div class="col-lg-7 col-xl-6">
									<select name="status" id="status" class="form-control form-control-modern">
										<option value="1" {{ (old('status', $shipping_mode->status) == '1' ? 'selected' : '') }}>Active</option>
										<option value="0" {{ (old('status', $shipping_mode->status) == '0' ? 'selected' : '') }}>Inactive</option>
									</select>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> {{ $shipping_mode->shipping_mode_id > 0 ? 'Update' : 'Add' }} Shipping Charge </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($shipping_mode->shipping_mode_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
					data-id="{{ $shipping_mode->shipping_mode_id }}"
					class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
					<i class="bx bx-trash text-4 mr-2"></i> Delete Shipping Charge </a> </div>
			</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.shipping-method.list',$id) }}";
        let url_edit = "{{ route('pnkpanel.shipping-method.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.shipping-method.update') }}";
        let url_delete = "{{ route('pnkpanel.shipping-method.delete', ':id') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/shipping_method_edit.js') }}"></script>
@endpush
