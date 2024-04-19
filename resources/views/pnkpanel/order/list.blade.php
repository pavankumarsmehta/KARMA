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

					<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
					  <label class="mb-1" for="category_id">By Customer Name</label>
					  {{--<!--<input type="text" name="customer_ID" id="customer_ID" class="form-control" value="{{ $customer_name }}" autocomplete="off" onkeyup="Show_SearchTerms();" />-->--}}
					  <input type="text" name="customer_ID" id="customer_ID" class="form-control" value="{{ $customer_name }}" autocomplete="off" />
					  <input type="hidden" id="filterCustomer" name="filterCustomer" value="{{ request()->get('filterCustomer') }}">
					  {{--<!--<ul id="SearchTerms_List" class="termsStyle list-unstyled"></ul>-->--}}
					</div>
					
					<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
						<label class="mb-1" for="filterStatus">Order Status</label>
						<select name="filterStatus" id="filterStatus" class="form-control">
							<option value="">Any</option>
							@foreach($allOptions = ['Pending', 'Completed', 'Canceled','Declined','Refunded','Partial Refund','Admin Review'] as $option)
							<option value="{{ $option }}" {{ request()->get('filterStatus') == $option ? 'selected' : '' }}>{{ $option }}</option>
							@endforeach
						</select>
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
							{{--<!--<input type="text" class="form-control" name="filterEndDate" id="filterEndDate" value="{{ $filterEndDate }}" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "mm/dd/yyyy", "autoclose": "true", "endDate": "today"}' size="10" data-plugin-masked-input data-input-mask="99/99/9999" placeholder="__/__/____" />-->--}}
					  </div>
					  
						{{--<!--<input type="text" class="form-control" name="filterStartDate" id="filterStartDate" value="{{ $filterStartDate }}"  data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "mm/dd/yyyy", "autoclose": "true", "endDate": "today"}' readonly="true" size="10" />-->--}}
						
					</div>
					{{--<!--<div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6 mb-3">
						<label class="mb-1" for="filterEndDate">To</label>
						<input type="text" class="form-control" name="filterEndDate" id="filterEndDate" value="{{ $filterEndDate }}" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "mm/dd/yyyy", "autoclose": "true", "endDate": "today"}' readonly="true" size="10" />
					</div>-->--}}
					<div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
						<label class="mb-1" for="filterEndDate">Search By</label>
						<select class="form-control select-style search-by" name="search-by">
							<option value="1">Order No.</option>
							<option value="5">Billing Email</option>
						</select>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12 mb-3">
						<div class="mx-lg-auto mt-md-4 mt-sm-0">
							<div class="input-group">
								<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Order">
							</div>
						</div>
					</div>
					
					{{--<!--<div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 mb-3">
						<div class="mt-xl-4 mt-lg-0 mt-md-4 mt-sm-0">
						<a href="javascript:void(0);" id="btnSerach" class="btn btn-primary btn-md">Search</a> &nbsp;&nbsp; 
						<a href="javascript:void(0);" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</a>
						</div>
					</div>-->--}}
				</div>
				{{--<!--<div class="row align-items-center mb-3">
					<div class="col-12 col-lg-auto mb-3 mb-lg-0">
						<div class="d-flex align-items-lg-center flex-column flex-lg-row">
							<label class="ws-nowrap mr-3 mb-0">Search By:</label>
							<select class="form-control select-style-1 search-by" name="search-by">
								<option value="1">Order No.</option>
								<option value="4">Billing Email</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
						<div class="search search-style-1 search-style-1-lg mx-lg-auto">
							<div class="input-group">
								<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Order">
								<span class="input-group-append">
									<button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>-->--}}
				<div>
					<a href="javascript:void(0);" id="btnSerach" class="btn btn-primary btn-md">Search</a> &nbsp;&nbsp; 
					<a href="javascript:void(0);" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</a>
					&nbsp; &nbsp; 
					<a href="javascript:void(0);" id="exportOrder" class="btn btn-primary btn-md">Export to CSV</a>
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
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th width="8%">Order No.</th>
								<th width="10%">Order DateTime</th>
								<th width="15%">Customer</th>
								<th class="d-none">Billing Email</th>
								<th width="8%">Subtotal</th>
								<th width="8%">Tax</th>
								<th width="8%">Shipping</th>
								<th width="8%">Order Total</th>
								<th width="8%">Order Status</th>
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
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.order.list') }}";
let url_bulk_action = "{{ route('pnkpanel.order.bulk_action') }}";
let url_auto_suggest_customer_name = "{{ route('pnkpanel.customer.auto_suggest_customer_name') }}";
let url_ajax_loader = "{{ asset('/pnkpanel/images/ajax-loader-small.gif') }}";
let sampleOrder_list = "{{ route('pnkpanel.sampleOrder.export') }}";

</script>
<script src="{{ asset('pnkpanel/js/order_list.js') }}"></script>
@endpush
