@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0"> <a href="{{ route('pnkpanel.tax-area.edit' ) }}" class="btn btn-primary btn-md font-weight-semibold btn-py-2 px-4"><i class="bx bx-plus"></i> Add Tax Area</a> </div>

							<div class="col-12 col-lg-auto ml-auto mb-3 mb-lg-0">
								<div class="d-flex align-items-lg-center flex-column flex-lg-row">
									<label class="ws-nowrap mr-3 mb-0">Search By:</label>
									<select class="form-control select-style-1 search-by" name="search-by">
										<option value="2">State</option>
                                        {{--<option value="4">Zip From</option>
										<option value="5">Zip To</option>--}}
									</select>
								</div>
							</div>
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
								<div class="search search-style-1 search-style-1-lg mx-lg-auto">
									<div class="input-group">
										<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Tax Area">
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
								<th width="5%">Country</th>
								<th width="5%">States</th>
								<th width="20%">Tax Region Name</th>
								{{--<th width="8%">ZIP from</th>
                                <th width="8%">ZIP to</th>--}}
								<th width="8%">Status</th>
								<th width="20%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="8" class="dataTables_empty"></td></tr>
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
let url_list = "{{ route('pnkpanel.tax-area.list') }}";
let url_edit = "{{ route('pnkpanel.tax-area.edit', ':id') }}";
let url_edit_tax_area_rate = "{{ route('pnkpanel.tax-area.tax_area_rate_edit', ':id') }}";
let url_update = "{{ route('pnkpanel.tax-area.update') }}";
let url_delete = "{{ route('pnkpanel.tax-area.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.tax-area.bulk_action') }}";
</script>
<script src="{{ asset('pnkpanel/js/tax_area_list.js') }}"></script>
@endpush
