@php
    $countrycombo 		= displaycountry(old('country', $customer->country), $countryArray);	
	$readonly = ($customer->customer_id > 0) ? 'readonly' : 'add';
	$statecombo 		= displaystate(old('state', $customer->state), $stateArray);	

	$state = $customer->state;
	$otherstate = '';
	if($customer->country != "US")
	{
		$otherstate = $state;
		$state = "";
	}

@endphp
@extends('admin.layouts.app')
@section('content')
<form action="{{ route('admin.customer.update') }}" method="post" name="frmCustomer" id="frmCustomer" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $customer->customer_id > 0 ? 'update' : 'add' }}">
	@csrf
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Customer Information</h2>
				</header>
				<div class="card-body">
						
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="email">Email <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('email') error @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" {{ $readonly }}>
								@error('email')
								<label class="error" for="email" role="alert">{{ $message }}</label>
								@enderror
								@error('email_exists')
								<label class="error" for="email" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<input type="hidden" name="old_email" id="old_email" value="{{$customer->email}}">
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="password">Password </label>
							<div class="col-lg-6">
								<input type="password" class="form-control @error('password') error @enderror" id="password" name="password" value="">
								<span class="help-block">(Note: Please Enter password in order to change it.)</span>
								@error('password')
								<label class="error" for="password" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="confirm_password">Confirm Password </label>
							<div class="col-lg-6">
								<input type="password" class="form-control @error('confirm_password') error @enderror" id="confirm_password" name="confirm_password" value="">
								@error('confirm_password')
								<label class="error" for="confirm_password" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="first_name">First Name <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('first_name') error @enderror" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name) }}">
								@error('first_name')
								<label class="error" for="first_name" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="last_name">Last Name <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('last_name') error @enderror" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name) }}">
								@error('last_name')
								<label class="error" for="last_name" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="address1">Address1 <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('address1') error @enderror" id="address1" name="address1" value="{{ old('address1', $customer->address1) }}">
								@error('address1')
								<label class="error" for="address1" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="address1">Address2</label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('address2') error @enderror" id="address2" name="address2" value="{{ old('address2', $customer->address2) }}">
								@error('address2')
								<label class="error" for="address2" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="city">City <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('city') error @enderror" id="city" name="city" value="{{ old('city', $customer->city) }}">
								@error('city')
								<label class="error" for="city" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="country">Country <span class="required">*</span></label>
							<div class="col-lg-6">
								<select name="country" id="country" class="form-control form-control-modern">
									{!! $countrycombo !!}
								</select>
								@error('country')
								<label class="error" for="country" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row" id="divstate">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="state">State <span class="required">*</span></label>
							<div class="col-lg-6">
								<select name="state" id="state" class="form-control form-control-modern">
									{!! $statecombo !!}
								</select>
								@error('state')
								<label class="error" for="state" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row" id="divotherstate" style="display: none;">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="other_state">Other State <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('other_state') error @enderror" id="other_state" name="other_state" value="{{ old('other_state', $customer->other_state) }}">
								@error('other_state')
								<label class="error" for="other_state" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="zip">Zip <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('zip') error @enderror" id="zip" name="zip" value="{{ old('zip', $customer->zip) }}">
								@error('zip')
								<label class="error" for="zip" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="phone">Phone <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('phone') error @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
								@error('phone')
								<label class="error" for="phone" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						{{--<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="representative_id">Sales Representative </label>
							<div class="col-lg-6">
								<select name="representative_id" id="representative_id" class="form-control form-control-modern">
                     				<option value="">Select Person</option>
									{!! $representative_str !!}
								</select>
								@error('representative_id')
								<label class="error" for="representative_id" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>--}}
						
						<div class="form-group row align-items-center">
							<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
								for="status">Status</label>
							<div class="col-lg-7 col-xl-6">
								<select name="status" id="status" class="form-control form-control-modern">
									<option value="1" {{ old('status', $customer->status) == '1' ? 'selected' : '' }}>
										Active</option>
									<option value="0" {{ old('status', $customer->status) == '0' ? 'selected' : '' }}>
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
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($customer->customer_id > 0)
			<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
				<a href="javascript:void(0);" data-id="{{ $customer->customer_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a> 
		   </div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script language="javascript" type="text/javascript">
var jsuser_type = '<?=strtolower($customer->registration_type)?>';
var actType = '<?= $customer->customer_id > 0 ? "update" : "add" ?>';
</script>
<script>
let url_list = "{{ route('admin.customer.list') }}";
let url_edit = "{{ route('admin.customer.edit', ':id') }}";
let url_update = "{{ route('admin.customer.update') }}";
let url_delete = "{{ route('admin.customer.delete', ':id') }}";
</script>
<script src="{{ asset('admin/js/customer_edit.js') }}"></script>
@endpush
