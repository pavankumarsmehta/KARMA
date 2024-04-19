@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">

							<div class="col-12 col-lg-auto ml-auto mb-3 mb-lg-0">
								<div class="d-flex align-items-lg-center flex-column flex-lg-row">
									<label class="ws-nowrap mr-3 mb-0">Search By:</label>
									<select class="form-control select-style-1 search-by" name="search-by">
										<option value="1">Sourcing Product</option>
									</select>
								</div>
							</div>
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
								<div class="search search-style-1 search-style-1-lg mx-lg-auto">
									<div class="input-group">
										<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Quotation">
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
								<th width="20%">Sourcing Product</th>
								<th width="20%">Quantity</th>
								<th width="20%">Unit</th>
								<th width="15%">Status</th>
								<th width="23%">Action</th>
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
let url_list = "{{ route('pnkpanel.manage-quotations.list') }}";
let url_edit = "{{ route('pnkpanel.manage-quotations.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.manage-quotations.update') }}";
let url_delete = "{{ route('pnkpanel.manage-quotations.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.manage-quotations.bulk_action') }}";
</script>
<script src="{{ asset('pnkpanel/js/quotations_list.js') }}"></script>
@endpush

