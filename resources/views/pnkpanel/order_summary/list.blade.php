@extends('pnkpanel.layouts.app')
@push('styles')
<style>
.ui-autocomplete {
    max-height: 300px;
    overflow-y: auto;   /* prevent horizontal scrollbar */
    overflow-x: hidden; /* add padding to account for vertical scrollbar */
    z-index:1000 !important;
}
</style>
@endpush

@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="row rowtest">

					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-3">
					  <label class="mb-1" for="category_id">By Customer Name</label>
					  <input type="text" name="customer_ID" id="customer_ID" class="form-control" value="{{ $customer_name }}" autocomplete="off" />
					  <input type="hidden" id="filterCustomer" name="filterCustomer" value="{{ request()->get('filterCustomer') }}">
					</div>
					<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
						<label class="mb-1" for="filterStartDate">Order Date Range</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"placeholder": "mm/dd/yyyy", "orientation": "bottom", "format": "mm/dd/yyyy", "autoclose": "true", "endDate": "today", "multidate": true, "translation": { 1: {pattern: /[0-3*]/}, 2: {pattern: /[0-9*]/}, 3: {pattern: /[0-1*]/}, 4: {pattern: /[0-9*]/}, 5: {pattern: /[1*]/}, 6: {pattern: /[9*]/}, 7: {pattern: /[0-9*]/}, 8: {pattern: /[0-9*]/}, }}'>
							<span class="input-group-prepend">
							  <span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							  </span>
							</span>
							<input type="text" class="form-control" name="filterStartDate" id="filterStartDate" placeholder="mm/dd/yyyy" value="{{ $filterStartDate }}" size="10" data-plugin-masked-input data-input-mask="99/99/9999" data-plugin-options='{"placeholder": "mm/dd/yyyy", "format": "mm/dd/yyyy", "translation": { 1: {pattern: /[0-3*]/}, 2: {pattern: /[0-9*]/}, 3: {pattern: /[0-1*]/}, 4: {pattern: /[0-9*]/}, 5: {pattern: /[1*]/}, 6: {pattern: /[9*]/}, 7: {pattern: /[0-9*]/}, 8: {pattern: /[0-9*]/}, }}' />
							<span class="input-group-text border-left-0 border-right-0 rounded-0">to</span>
							<input type="text" class="form-control" name="filterEndDate" id="filterEndDate" placeholder="mm/dd/yyyy" value="{{ $filterEndDate }}" size="10" data-plugin-masked-input data-input-mask="99/99/9999" data-plugin-options='{"placeholder": "mm/dd/yyyy", "format": "mm/dd/yyyy", "translation": { 1: {pattern: /[0-3*]/}, 2: {pattern: /[0-9*]/}, 3: {pattern: /[0-1*]/}, 4: {pattern: /[0-9*]/}, 5: {pattern: /[1*]/}, 6: {pattern: /[9*]/}, 7: {pattern: /[0-9*]/}, 8: {pattern: /[0-9*]/}, }}' />
					  </div>
					</div>
					<div class="col-xl-5 col-lg-3 col-md-12 col-sm-12 col-12 mb-2">
						<div class="mt-xl-4 mt-lg-4 mt-md-4 mt-sm-0">
							<a href="javascript:void(0);" id="btnSerach" class="btn btn-primary btn-md">Search</a> &nbsp;&nbsp; 
							<a href="javascript:void(0);" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 1100px;">
						<thead>
							<tr>
								<th width="8%">Date</th>
								<th width="8%">Total Order</th>
								<th width="10%">Order ~ Payment(s) Pending</th>
								<th width="10%">Order ~ Payment(s) Collected</th>
								<th width="10%">Order ~ Payment(s) Cancelled</th>
								<th width="10%">Order ~ Payment(s) Declined</th>
								<th width="10%">Total Amount</th>
								<th width="8%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="10" class="dataTables_empty"></td></tr>
						</tbody>
					</table>
					
					<hr class="solid mt-5 opacity-4">
					
					@include('pnkpanel.component.datatable_footer')
					
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
				</div>
				<h2 class="card-title">Print Orders & Packaging Slip</h2>
			</header>
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<form action="{{ route('pnkpanel.order.order_slip') }}" method="post" name="frmPrintOrders" id="frmPrintOrders" target="_blank">
						@csrf
							<div class="align-top d-inline-block mt-2">
							Print a range of orders (Order Id) From : &nbsp;
							</div>
							<div class="col-sm-1 px-0 align-top d-inline-block">
								<input type="text" name="start_id" class="form-control" value="" size="5">
							</div>
							<div class="align-top d-inline-block mt-2">
							&nbsp; To : &nbsp;
							</div>
							<div class="col-sm-1 px-0 align-top d-inline-block">
								<input type="text" name="end_id" class="form-control" value="" size="5">
							</div>
							<div class="align-top d-inline-block mt-3 mt-sm-0">
							&nbsp;
							<button type="button" class="btn btn-primary btn-md btnSubmitPrintOrders">Print Orders</button>
							</div>
					  </form>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<form action="{{ route('pnkpanel.order.packing_slip') }}" method="post" name="frmPrintPackagingSlip" id="frmPrintPackagingSlip" target="_blank">
						@csrf
							<div class="align-top d-inline-block mt-2">
							Print a range of orders (Order Id) From : &nbsp;
							</div>
							<div class="col-sm-1 px-0 d-inline-block">
								<input type="text" name="start_id" class="form-control" value="" size="5">
							</div>
							<div class="align-top d-inline-block mt-2">
							&nbsp; To : &nbsp;
							</div>
							<div class="col-sm-1 px-0 d-inline-block">
								<input type="text" name="end_id" class="form-control" value="" size="5">
							</div>
							<div class="align-top d-inline-block mt-3 mt-sm-0">
							&nbsp;
							<button type="button" class="btn btn-primary btn-md btnSubmitPrintPackagingSlip">Print Packaging Slip</button>
							</div>
					  </form>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.order-summary') }}";
let url_auto_suggest_customer_name = "{{ route('pnkpanel.customer.auto_suggest_customer_name') }}";
let url_ajax_loader = "{{ asset('/pnkpanel/images/ajax-loader-small.gif') }}";

</script>
<script src="{{ asset('pnkpanel/js/order_summary.js') }}"></script>
@endpush
