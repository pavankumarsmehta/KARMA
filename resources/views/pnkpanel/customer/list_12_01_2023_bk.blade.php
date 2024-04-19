@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							<div class="col-auto mb-3 mb-lg-0"> <a href="{{ route('admin.customer.edit' ) }}" class="btn btn-primary btn-md"><i class="bx bx-plus"></i> Add New </a> </div>
							<!-- <div class="col-12 col-lg-auto mb-3 mb-lg-0"> <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="emailForgotPassword"> Email Forgot Password</a> </div> -->
                        	<div class="col-auto mb-3 mb-lg-0"> <a href="{{ route('admin.customer.export' ) }}" class="btn btn-primary btn-md">Export to CSV</a> </div>
						</div>	
						<div class="row align-items-center mb-3">
							<div class="col-12 col-lg-auto ml-auto mb-3 mb-lg-0">
								<div class="d-flex align-items-lg-center flex-column flex-lg-row">
									<label class="ws-nowrap mr-3 mb-0">Search By:</label>
									<select class="form-control select-style-1 search-by" name="search-by">
										<option value="1">First Name</option>
										<option value="2">Last Name</option>
										<option value="5">City</option>
										<option value="6">State</option>
										<option value="7">Country</option>
										<option value="4">Email</option>
									</select>
								</div>
							</div>
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
								<div class="search search-style-1 search-style-1-lg mx-lg-auto">
									<div class="input-group">
										<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Customer">
										<span class="input-group-append">
											<button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
										</span>
									</div>
								</div>
							</div>
							
							<div class="col-12 col-lg-auto pl-lg-1 text-center">
								<button type="button" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</button>
							</div>
						
						</div>
					</div>
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th class="d-none">First Name</th>
								<th class="d-none">Last Name</th>
								<th width="10%">Name</th>
								<th width="10%">Email</th>
								
								<th width="12%">Registered<br/>Date</th>
								<th width="10%">Customer<br/>Type</th>
								<th width="10%">Status</th>
								<th width="9%">View<br/>Orders</th>
								<th width="7%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="10" class="dataTables_empty"></td></tr>
						</tbody>
					</table>
					
					<hr class="solid mt-5 opacity-4">
					
					@include('admin.component.datatable_footer')
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('admin.customer.list') }}";
let url_edit = "{{ route('admin.customer.edit', ':id') }}";
let url_update = "{{ route('admin.customer.update') }}";
let url_delete = "{{ route('admin.customer.delete', ':id') }}";
let url_bulk_action = "{{ route('admin.customer.bulk_action') }}";
let url_email_forgot_password = "{{ route('admin.customer.email_forgot_password' ) }}";
</script>
<script src="{{ asset('admin/js/customer_list.js') }}"></script>
@endpush
