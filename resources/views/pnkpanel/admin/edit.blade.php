@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.admin.update') }}" method="post" name="frmAdmin" id="frmAdmin" class="ecommerce-form action-buttons-fixed">
<input type="hidden" name="admin_id" value="{{$admin->admin_id}}">
<input type="hidden" name="actType" id="actType" value="{{ $admin->admin_id > 0 ? 'update' : 'add' }}">
@csrf
	<div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2-5 col-xl-1-5">
							<i class="card-big-info-icon bx bx-user-circle"></i>
							<h2 class="card-big-info-title">Account Info</h2>
							<p class="card-big-info-desc">Add here the admin account info with all details and necessary information.</p>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
							<div class="form-group row align-items-center">
								<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
							</div>
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="email">E-mail
									@unless ($admin->admin_id > 0)
									<span class="required">*</span>
									@endunless
								</label>
								<div class="col-lg-7 col-xl-6">
									<input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" size="255" class="form-control form-control-modern @error('email') error @enderror" @if ($admin->admin_id > 0) readonly  @endif />
									@error('email')
										<label class="error" for="email" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="password">Password
									@unless ($admin->admin_id > 0)
									<span class="required">*</span>
									@endunless
								</label>
								<div class="col-lg-7 col-xl-6">
									<input type="password" name="password" id="password" value="" size="50" class="form-control form-control-modern @error('password') error @enderror" />
									@error('password')
										<label class="error" for="email" role="alert">{{ $message }}</label>
									@enderror
									<span class="help-block text-info">Note: Please enter password in order to change it.</span>
								</div>
							</div>
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="password_confirmation">Confirm Password
									@unless ($admin->admin_id > 0)
									 <span class="required">*</span>
									 @endunless
								</label>
								<div class="col-lg-7 col-xl-6">
									<input type="password" name="password_confirmation" id="password_confirmation" value="" size="50" class="form-control form-control-modern @error('password_confirmation') error @enderror"  />
									@error('password_confirmation')
										<label class="error" for="email" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
								<div class="col-lg-7 col-xl-6">
									<select name="status" id="status" class="form-control form-control-modern">
										<option value="1" {{ (old('status', $admin->status) == '1' ? 'selected' : '') }}>Active</option>
										<option value="0" {{ (old('status', $admin->status) == '0' ? 'selected' : '') }}>Inactive</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	<div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2-5 col-xl-1-5">
							<i class="card-big-info-icon bx bx-lock"></i>
							<h2 class="card-big-info-title">Account Permissions</h2>
							<p class="card-big-info-desc">Define here the admin account permissions.</p>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
							
							<div class="form-group row align-items-center">
								<label class="col-lg-3 control-label text-lg-right pt-2"  for="admin_type">Admin Type</label>
								<div class="col-lg-6">
									<div class="radio-custom radio-inline radio-primary">
										<input name="admin_type" id="super_admin" type="radio" value="super admin" {{ (old('admin_type', $admin->admin_type) == "super admin" || $admin->admin_type == "") ? "checked" :  "" }} onclick="check_permision();" />
										<label for="super_admin"> Super Admin</label>
									</div>
									<div class="radio-custom radio-inline radio-primary mb-2 ml-3">
										<input name="admin_type" id="sub_admin" type="radio" value="sub admin" {{ (old('admin_type', $admin->admin_type) == "sub admin") ? "checked" :  "" }} onclick="check_permision();" />
										<label for="sub_admin"> Sub Admin</label>
									</div>
									<label class="error" for="admin_type"></label>
								</div>
							</div>
							
							
							
							@php 
								$rights_array = array_flip(explode(',',$admin->rights));
							@endphp

							<div id="checkboxes_for_super_admin" {!! (old('admin_type', $admin->admin_type) == "super admin" || $admin->admin_type == "") ? "style='display:block;'" :  "style='display:none;'" !!} >
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right"></label>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<label class="text-info">Super admin have all the privileges.</label>
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="all_permision" value="all" class="disabled" {{ (old('admin_type', $admin->admin_type) == "super admin" || $admin->admin_type == "") ? " checked " :  " disabled " }} onClick="return false;">
													<label for="all">All Privileges</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="checkboxes_for_sub_admin" {!! (old('admin_type', $admin->admin_type) == "sub admin") ? "style='display:block;'" :  "style='display:none;'" !!}>
							
								<div class="border-top my-3"></div>
								<div class="form-group row mb-0">
									<div class="col-lg-12 text-center">
										<input type="hidden" name="check_subadmin_rights" id="check_subadmin_rights" value="" class="mb-2" />
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right"></label>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-12 mb-2">
												<div class="custom-checkbox">
													<input type="checkbox" id="masterCheck" class="custom-control-input">
													<label class="custom-control-label ml-4" for="masterCheck">Select / Unselect All</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Admin Section</label>

									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="admin" value="admin" {{  (is_array(old('rights')) ? (in_array('admin', old('rights')) ? 'checked' : '' ) : (isset($rights_array['admin']) ? 'checked' : '')) }}>
													<label for="admin">Manage Admin</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="country" value="country-state" {{  (is_array(old('rights')) ? (in_array('country-state', old('rights')) ? 'checked' : '' ) : (isset($rights_array['country-state']) ? 'checked' : '')) }}>
													<label for="country">Country/State</label>
												</div>
											</div>
											
										</div>
									</div>
								  </div>
								
								<hr/>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Customer Section</label>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="customer" value="customer" {{  (is_array(old('rights')) ? (in_array('customer', old('rights')) ? 'checked' : '' ) : (isset($rights_array['customer']) ? 'checked' : '')) }}>
													<label for="customer">Customer</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<hr/>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Store Inventory Section</label>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="category" value="category" {{  (is_array(old('rights')) ? (in_array('category', old('rights')) ? 'checked' : '' ) : (isset($rights_array['category']) ? 'checked' : '')) }}>
													<label for="category">Manage Categories</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="product" value="product" {{  (is_array(old('rights')) ? (in_array('product', old('rights')) ? 'checked' : '' ) : (isset($rights_array['product']) ? 'checked' : '')) }}>
													<label for="product">Manage Products</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="import" value="import" {{  (is_array(old('rights')) ? (in_array('import', old('rights')) ? 'checked' : '' ) : (isset($rights_array['import']) ? 'checked' : '')) }}>
													<label for="import">Import Products</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="export" value="export" {{  (is_array(old('rights')) ? (in_array('export', old('rights')) ? 'checked' : '' ) : (isset($rights_array['export']) ? 'checked' : '')) }}>
													<label for="export">Export Products</label>
												</div>
											</div>
										</div>
									</div>
								  </div>
								
								<hr/>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Store Settings Section</label>
									
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="global" value="global" {{  (is_array(old('rights')) ? (in_array('global', old('rights')) ? 'checked' : '' ) : (isset($rights_array['global']) ? 'checked' : '')) }}>
													<label for="global">Site Global Settings</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="bottom" value="bottom" {{  (is_array(old('rights')) ? (in_array('bottom', old('rights')) ? 'checked' : '' ) : (isset($rights_array['bottom']) ? 'checked' : '')) }}>
													<label for="bottom">Site Html</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="mail" value="mail" {{  (is_array(old('rights')) ? (in_array('mail', old('rights')) ? 'checked' : '' ) : (isset($rights_array['mail']) ? 'checked' : '')) }}>
													<label for="mail">Mail Templates</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="meta" value="meta" {{  (is_array(old('rights')) ? (in_array('meta', old('rights')) ? 'checked' : '' ) : (isset($rights_array['meta']) ? 'checked' : '')) }}>
													<label for="meta">Meta Settings</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="static" value="static" {{  (is_array(old('rights')) ? (in_array('static', old('rights')) ? 'checked' : '' ) : (isset($rights_array['static']) ? 'checked' : '')) }}>
													<label for="static">Static Pages</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="payment" value="payment" {{  (is_array(old('rights')) ? (in_array('payment', old('rights')) ? 'checked' : '' ) : (isset($rights_array['payment']) ? 'checked' : '')) }}>
													<label for="payment">Payment Methods</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="shipping" value="shipping" {{  (is_array(old('rights')) ? (in_array('shipping', old('rights')) ? 'checked' : '' ) : (isset($rights_array['shipping']) ? 'checked' : '')) }}>
													<label for="shipping">Shipping Methods</label>
												</div>
											</div>
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="tax" value="tax" {{  (is_array(old('rights')) ? (in_array('tax', old('rights')) ? 'checked' : '' ) : (isset($rights_array['tax']) ? 'checked' : '')) }}>
													<label for="tax">Tax Area & Rate</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="home_products" value="home_products" {{  (is_array(old('rights')) ? (in_array('home_products', old('rights')) ? 'checked' : '' ) : (isset($rights_array['home_products']) ? 'checked' : '')) }}>
													<label for="home_products">Home Page Setting</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<hr/>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Order Management Section</label>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="order" value="order" {{  (is_array(old('rights')) ? (in_array('order', old('rights')) ? 'checked' : '' ) : (isset($rights_array['order']) ? 'checked' : '')) }}>
													<label for="order">Order Summary</label>
												</div>
											</div>

											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">													
													<input type="checkbox" name="rights[]" id="order_list" value="order_list" {{  (is_array(old('rights')) ? (in_array('order_list', old('rights')) ? 'checked' : '' ) : (isset($rights_array['order_list']) ? 'checked' : '')) }}>
													<label for="order">Order List</label>
												</div>
											</div>

										</div>
									</div>
								  </div>

								<hr/>
								
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right">Promotions Section</label>
									
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="coupon" value="coupon" {{  (is_array(old('rights')) ? (in_array('coupon', old('rights')) ? 'checked' : '' ) : (isset($rights_array['coupon']) ? 'checked' : '')) }}>
													<label for="coupon">Discount Coupons</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="auto_discount" value="auto_discount" {{  (is_array(old('rights')) ? (in_array('auto_discount', old('rights')) ? 'checked' : '' ) : (isset($rights_array['auto_discount']) ? 'checked' : '')) }}>
													<label for="auto_discount">Auto Discount</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="qty_discount" value="qty_discount" {{  (is_array(old('rights')) ? (in_array('qty_discount', old('rights')) ? 'checked' : '' ) : (isset($rights_array['qty_discount']) ? 'checked' : '')) }}>
													<label for="qty_discount">Quantity Discount</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="bulk_mail" value="bulk_mail" {{  (is_array(old('rights')) ? (in_array('bulk_mail', old('rights')) ? 'checked' : '' ) : (isset($rights_array['bulk_mail']) ? 'checked' : '')) }}>
													<label for="bulk_mail">Bulk Mail</label>
												</div>
											</div>
											
											<div class="col-lg-6 mb-2">
												<div class="checkbox-custom checkbox-default">
													<input type="checkbox" name="rights[]" id="newsletter" value="newsletter" {{  (is_array(old('rights')) ? (in_array('newsletter', old('rights')) ? 'checked' : '' ) : (isset($rights_array['newsletter']) ? 'checked' : '')) }}>
													<label for="newsletter">Newsletter</label>
												</div>
											</div>
											
										</div>
									</div>
								</div>
								
							</div>
						
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save </button>
		</div>
        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"  class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
        @if ($admin->admin_id > 0 && Pnkpanel::user()->admin_id != $admin->admin_id)
        <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);" data-id="{{ $admin->admin_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a> </div>
        @endif
      </div>
 </form> 
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.admin.list') }}";
let url_edit = "{{ route('pnkpanel.admin.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.admin.update') }}";
let url_delete = "{{ route('pnkpanel.admin.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/admin_edit.js') }}"></script>
@endpush
