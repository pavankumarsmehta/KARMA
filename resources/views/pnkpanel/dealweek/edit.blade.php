@extends('pnkpanel.layouts.app')
@section('content')
@php

if($dealweek->start_date != '0000-00-00' && $dealweek->start_date != '') {
	$start_date = \Carbon\Carbon::parse($dealweek->start_date)->format('m/d/Y');
} else {
	$start_date = '';
}
if($dealweek->end_date != '0000-00-00' && $dealweek->end_date != '') {
	$end_date = \Carbon\Carbon::parse($dealweek->end_date)->format('m/d/Y');
} else {
	$end_date = '';
}

@endphp

<form action="{{ route('pnkpanel.dealweek.update') }}" method="post" name="frmDealWeek" id="frmDealWeek"  class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="id" value="{{ $dealweek->dealofweek_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $dealweek->dealofweek_id > 0 ? 'update' : 'add' }}">
	@csrf
        
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Deal of the week</h2>
				</header>
				<div class="card-body">
					
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="product_sku">Product SKU<span class="required">*</span></label>
							<div class="col-lg-6" id="orders_field_0">
								<input type="text" name="product_sku" id="product_sku" value="{{ old('product_sku', $dealweek->product_sku) }}" size="50" class="form-control form-control-modern @error('product_sku') error @enderror"  />
								@error('product_sku')
									<label class="error" for="product_sku" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="deal_price">Discount Amount<span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" name="deal_price" id="deal_price" value="{{ old('deal_price', $dealweek->deal_price) }}" size="50" class="form-control form-control-modern @error('deal_price
								') error @enderror" />
								@error('deal_price')
									<label class="error" for="deal_price" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="start_date">Start Date to <br />End Date<span class="required">*</span></label>
							<div class="col-lg-6">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="start_date" id="d_start_date" value="{{ old('start_date', $start_date) }}">
									@error('start_date')
										<label class="error" for="start_date" role="alert">{{ $message }}</label>
									@enderror
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to
									</span>
									<input type="text" class="form-control" name="end_date" id="d_end_date" value="{{ old('end_date', $end_date) }}">
									@error('end_date')
										<label class="error" for="end_date" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_mode_id">Details</label>
								<div class="col-lg-6 col-xl-6 ordersF" id="orders_field_1">
									<textarea class="form-control" rows="3" id="description" name="description">{{ old('description', $dealweek->description) }}</textarea>
								</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="display_rank">Display Rank<span class="required">*</span></label>
							<div class="col-lg-6" id="orders_field_0">
								<input type="number" name="display_rank" id="display_rank" value="{{ old('display_rank', $dealweek->display_rank) }}" size="50" class="form-control form-control-modern @error('display_rank') error @enderror""  />
								@error('display_rank')
									<label class="error" for="display_rank" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="display_on_home ">Display on Home </label>
							<div class="col-lg-7 col-xl-6">
								<select name="display_on_home" id="display_on_home" class="form-control form-control-modern">
									<option value="Yes" {{ old('display_on_home ', $dealweek->display_on_home ) == 'Yes' ? 'selected' : '' }}>
										Yes</option>
									<option value="No" {{ old('display_on_home ', $dealweek->display_on_home ) == 'No' ? 'selected' : '' }}>
										No</option>
								</select>
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="deal_type">Deal Type </label>
							<div class="col-lg-7 col-xl-6">
								<select name="deal_type" id="deal_type" class="form-control form-control-modern">
									<option value="1" {{ old('deal_type ', $dealweek->deal_type ) == 'weekly' ? 'selected' : '' }}>
										Weekly</option>
									<option value="0" {{ old('deal_type ', $dealweek->deal_type ) == 'daily' ? 'selected' : '' }}>
										Daily</option>
								</select>
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="status">Status</label>
							<div class="col-lg-7 col-xl-6">
								<select name="status" id="status" class="form-control form-control-modern">
									<option value="1" {{ old('status', $dealweek->status) == '1' ? 'selected' : '' }}>
										Active</option>
									<option value="0" {{ old('status', $dealweek->status) == '0' ? 'selected' : '' }}>
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
			<button type="submit"
				class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Deal</button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($dealweek->dealofweek_id  > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
				<a href="javascript:void(0);" data-id="{{ $dealweek->dealofweek_id  }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Deal </a> 
		   </div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.dealweek.list') }}";
let url_edit = "{{ route('pnkpanel.dealweek.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.dealweek.update') }}";
let url_delete = "{{ route('pnkpanel.dealweek.delete', ':id') }}";
var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
if (err_msg1_for_cache != ""){
	$.ajax({
		type: 'POST',
		url: site_url + '/clearfrontdealoftheweekscache',
		data: {
			parent_sku: '',
		},
		success: function(data) {
			console.log('deal of the week chache cache clear sucessfully');

		}
	});
}
</script>
<script src="{{ asset('pnkpanel/js/dealweek_edit.js') }}"></script>
@endpush



