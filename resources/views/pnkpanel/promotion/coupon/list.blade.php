@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							<div class="col-12 col-lg-auto mb-3 mb-lg-0"> <a href="{{ route('pnkpanel.coupon.edit' ) }}" class="btn btn-primary btn-sm"><i class="bx bx-plus"></i> Add New Discount Coupon</a> </div>
							<div class="col-12 col-lg-auto pl-lg-1 ml-auto">
								<button type="button" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</button>
							</div>
						</div>
					</div>
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th width="10%">Coupon Title</th>
								<th width="10%">Coupon Code</th>
								<th width="10%">Coupon For</th>
								<th width="10%">Order Amount</th>
								<th width="10%">Discount</th>
								<th width="16%">Start To End Date</th>
								<th width="10%">Total Sales</th>
								<th width="10%">Total Discount</th>
								<th width="10%">Status</th>
								<th width="12%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty"></td></tr>
						</tbody>
					</table>
					
					<hr class="solid mt-5 opacity-4">
					
					@include('pnkpanel.component.datatable_footer')
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.coupon.list') }}";
let url_edit = "{{ route('pnkpanel.coupon.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.coupon.update') }}";
let url_delete = "{{ route('pnkpanel.coupon.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.coupon.bulk_action') }}";
</script>
<script src="{{ asset('pnkpanel/js/coupon_list.js') }}"></script>
@endpush
