@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0"> <a href="{{ route('pnkpanel.quantitydiscount.edit' ) }}" class="btn btn-primary btn-md font-weight-semibold btn-py-2 px-4"><i class="bx bx-plus"></i> Add New Quantity Discount</a> </div>
							
							<div class="col-12 col-lg-auto pl-lg-1 text-center ml-auto">
								<button type="button" class="mb-1 mt-1 mr-1 btn btn-default" onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i> Refresh</button>
							</div>
						
						</div>
					</div>
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th width="10%">Quantity</th>
								<th width="10%">Discount</th>
								<th width="16%">Start To End Date</th>
								<th width="10%">Status</th>
								<th width="12%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="6" class="dataTables_empty"></td></tr>
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
let url_list = "{{ route('pnkpanel.quantitydiscount.list') }}";
let url_edit = "{{ route('pnkpanel.quantitydiscount.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.quantitydiscount.update') }}";
let url_delete = "{{ route('pnkpanel.quantitydiscount.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.quantitydiscount.bulk_action') }}";
</script>
<script src="{{ asset('pnkpanel/js/quantitydiscount_list.js') }}"></script>
@endpush
