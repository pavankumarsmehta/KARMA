@extends('pnkpanel.layouts.app')
@section('content')

@php
	$start_date = \Carbon\Carbon::now()->subDays(7)->format('m/d/Y');
	$end_date = \Carbon\Carbon::now()->format('m/d/Y');
	$countrycombo 		= displaycountry($filterByCountry,$countryArray);
	$statecombo 		= displaystate($filterByState,$stateArray);	
@endphp
<div class="row">
	<div class="col">
	  <div class="card card-modern">
		<div class="card-body">
		  <div class="row rowtest">

			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">By Amount Spent</label>
				<select name="filterByAmountSpent" id="filterByAmountSpent" class="form-control">
                  <option value="">--- Select ---</option>
                  <option value="1-499" <?=$filterByAmountSpent=='1-499'?'selected':''?>>$1 - $499</option>
                  <option value="500-999" <?=$filterByAmountSpent=='500-999'?'selected':''?>>$500 - $999</option>
                  <option value="1000-1499" <?=$filterByAmountSpent=='1000-1499'?'selected':''?>>$1000 - $1499</option>
                  <option value="1500-1999" <?=$filterByAmountSpent=='1500-1999'?'selected':''?>>$1500 - $1999</option>
                  <option value="2000-2999" <?=$filterByAmountSpent=='2000-2999'?'selected':''?>>$2000 - $2999</option>
                  <option value="3000-3999" <?=$filterByAmountSpent=='3000-3999'?'selected':''?>>$3000 - $3999</option>
                  <option value="4000-4999" <?=$filterByAmountSpent=='4000-4999'?'selected':''?>>$4000 - $4999</option>
                  <option value="5000-7499" <?=$filterByAmountSpent=='5000-7499'?'selected':''?>>$5000 - $7499</option>
                  <option value="7500-10000" <?=$filterByAmountSpent=='7500-10000'?'selected':''?>>$7500 - $10000</option>
                  <option value="10000-0" <?=$filterByAmountSpent=='10000-0'?'selected':''?>>More than $10000</option>
                </select>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">By No. Of Orders</label>
				<select name="filterByNoOrders" id="filterByNoOrders" class="form-control">
                  <option value="">--- Select ---</option>
                  <option value="1" <?=$filterByNoOrders=='1'?'selected':''?>>1</option>
                  <option value="2" <?=$filterByNoOrders=='2'?'selected':''?>>2</option>
                  <option value="3" <?=$filterByNoOrders=='3'?'selected':''?>>3</option>
                  <option value="4" <?=$filterByNoOrders>='4'?'selected':''?>>4 & Up</option>
                </select>
			</div>

			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">Order Date From - To</label>
				<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
					<span class="input-group-prepend">
						<span class="input-group-text">
							<i class="fas fa-calendar-alt"></i>
						</span>
					</span>
					<input type="text" class="form-control" name="start_date" id="d_start_date" value="{{$start_date}}">
					<span class="input-group-text border-left-0 border-right-0 rounded-0">
						to
					</span>
					<input type="text" class="form-control" name="end_date" id="d_end_date" value="{{$end_date}}">
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">By Category</label>
				<select name="filterByCategory" id="filterByCategory" class="form-control">
                  <option value="">--- Select Category ---</option>
					@php
						$records = App\Models\Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
							$query->orderBy('category_name', 'asc');
						}])->get();
						echo implode(App\Http\Controllers\pnkpanel\CategoryController::drawCategoryTreeDropdown($records, 0));
					@endphp
                </select>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">By Country</label>
				<select name="filterByCountry" id="filterByCountry" class="form-control">
                  <option value="">--- Select Country ---</option>
                  {!! $countrycombo !!}
                </select>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
				<label class="mb-1">By State</label>
				<select name="filterByState" id="filterByState" class="form-control">
                  <option value="">--- Select State ---</option>
                  {!! $statecombo !!}
                </select>
			</div>

		  </div>

			<div>
				<a href="javascript:void(0);" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</a>
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
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							<!-- <div class="col-12 col-lg-auto pl-lg-1 text-center">
								<button type="button" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</button>
							</div> -->
						</div>
					</div>
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="34%">Customer Name</th>
								<th width="33%">Number Of Orders</th>
								<th width="33%">Shipping Amount</th>
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
let url_list = "{{ route('pnkpanel.customerorder-report.list') }}";
</script>
<script src="{{ asset('pnkpanel/js/customerorder_report_list.js') }}"></script>
@endpush
