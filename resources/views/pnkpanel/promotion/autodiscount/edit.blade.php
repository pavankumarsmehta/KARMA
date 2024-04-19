@extends('pnkpanel.layouts.app')
@section('content')
@php

if($autodiscount->start_date != '0000-00-00' && $autodiscount->start_date != '') {
	$start_date = \Carbon\Carbon::parse($autodiscount->start_date)->format('m/d/Y');
} else {
	$start_date = '';
}
if($autodiscount->end_date != '0000-00-00' && $autodiscount->end_date != '') {
	$end_date = \Carbon\Carbon::parse($autodiscount->end_date)->format('m/d/Y');
} else {
	$end_date = '';
}

@endphp
<form action="{{ route('pnkpanel.autodiscount.update') }}" method="post" name="frmAutoDiscount" id="frmAutoDiscount"  class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="id" value="{{ $autodiscount->auto_discount_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $autodiscount->auto_discount_id > 0 ? 'update' : 'add' }}">
	@csrf
        
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Auto Discount Common Settings</h2>
				</header>
				<div class="card-body">
					
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="order_amount">Order Amount<span class="required">*</span></label>
							<div class="col-lg-6" id="orders_field_0">
								<input type="text" name="order_amount" id="order_amount" value="{{ old('order_amount', $autodiscount->order_amount) }}" size="50" class="form-control form-control-modern @error('order_amount') error @enderror""  />
								@error('order_amount')
									<label class="error" for="order_amount" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="auto_discount_amount">Discount Amount<span class="required">*</span></label>
							<div class="col-lg-3 col-xl-3">
								<input type="text" name="auto_discount_amount" id="auto_discount_amount" value="{{ old('auto_discount_amount', $autodiscount->auto_discount_amount) }}" size="50" class="form-control form-control-modern @error('auto_discount_amount') error @enderror" />
								@error('auto_discount_amount')
									<label class="error" for="auto_discount_amount" role="alert">{{ $message }}</label>
								@enderror
								@error('auto_discount_between')
									<label class="error" for="auto_discount_between" role="alert">{{ $message }}</label>
								@enderror
							</div>
							<div class="col-lg-3 col-xl-3">
			                    <select name="type" id="type" class="form-control form-control-modern">
			                      <option value='1' {{ (old('type', $autodiscount->type)=='1')? "selected" : "" }}>Percent</option>
			                      <option value='0' {{ (old('type', $autodiscount->type)=='0')? "selected" : "" }}>Amount</option>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="shipping_mode_id">Start Date to <br />End Date<span class="required">*</span></label>
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
									<textarea class="form-control" rows="3" id="detail" name="detail">{{ old('detail', $autodiscount->detail) }}</textarea>
								</div>
						</div>

						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="status">Status</label>
							<div class="col-lg-7 col-xl-6">
								<select name="status" id="status" class="form-control form-control-modern">
									<option value="1" {{ old('status', $autodiscount->status) == '1' ? 'selected' : '' }}>
										Active</option>
									<option value="0" {{ old('status', $autodiscount->status) == '0' ? 'selected' : '' }}>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Auto Discount </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($autodiscount->auto_discount_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
				<a href="javascript:void(0);" data-id="{{ $autodiscount->auto_discount_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete Auto Discount </a> 
		   </div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.autodiscount.list') }}";
let url_edit = "{{ route('pnkpanel.autodiscount.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.autodiscount.update') }}";
let url_delete = "{{ route('pnkpanel.autodiscount.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/autodiscount_edit.js') }}"></script>
@endpush



