@extends('pnkpanel.layouts.app')
@section('content')

@php

	$start_date = \Carbon\Carbon::now()->subDays(7)->format('m/d/Y');
	$end_date = \Carbon\Carbon::now()->format('m/d/Y');

@endphp
<div class="row">
	<div class="col">
	  <div class="card card-modern">
		<div class="card-body">
		  <div class="row rowtest">


			<label class="col-lg-3 control-label text-lg-right pt-2" for="date">Date From - To</label>
			<div class="col-lg-6">
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
								<th width="33%">Tax Amount</th>
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
let url_list = "{{ route('pnkpanel.salestax-report.list') }}";
</script>
<script src="{{ asset('pnkpanel/js/salestax_report_list.js') }}"></script>
@endpush
