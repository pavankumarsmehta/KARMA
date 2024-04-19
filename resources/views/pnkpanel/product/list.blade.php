@extends('pnkpanel.layouts.app')
@section('content')

<div class="row">
	<div class="col">
	  <div class="card card-modern">
		<div class="card-body">
		  <div class="row rowtest">

			<div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 col-12 mb-3">
			  <label class="mb-1" for="category_id">Search By Category</label>
			  <select name="category_id" id="category_id" class="form-control">
				<option value="" selected>----- Select Category -----</option>
				@php
					$records = App\Models\Category::where('parent_id', '=', '0')->orderBy('category_name', 'asc')->with(['childrenRecursive' => function ($query) {
						$query->orderBy('category_name', 'asc');
					}])->get();
					echo implode(App\Http\Controllers\pnkpanel\CategoryController::drawCategoryTreeDropdown($records, 0));
				@endphp
			  </select>
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
							
							<div class="col-auto mb-3 mb-lg-0"> <a href="{{ route('pnkpanel.product.edit' ) }}" class="btn btn-primary btn-md"><i class="bx bx-plus"></i> Add New </a> </div>
							
							<div class="col-12 col-lg-auto ml-auto mb-3 mb-lg-0">
								<div class="d-flex align-items-lg-center flex-column flex-lg-row">
									<label class="ws-nowrap mr-3 mb-0">Search By:</label>
									<select class="form-control select-style-1 search-by" name="search-by">
										<option value="1">Product SKU</option>
										<option value="2">Product Name</option>
										<option value="9">Product Status (1 / 0)</option>
									</select>
								</div>
							</div>
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
								<div class="search search-style-1 search-style-1-lg mx-lg-auto">
									<div class="input-group">
										<input type="text" class="search-term form-control" name="search-term" id="search-term" placeholder="Search Product">
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
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 1100px;">
						<thead>
							<tr>
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th width="15%">Product SKU</th>
								<th width="15%">Product Name</th>
								<th width="12%">Retail Price</th>
								<th width="12%">Our Price</th>
								<th width="12%">Sale Price</th>
								<th width="10%">Display Rank</th>
								{{-- <th width="10%">Group Rank</th>
								<th width="8%">Sale Rank</th> --}}
								<th width="10%">Status</th>
								<th width="12%">Action</th>
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
let url_list = "{{ route('pnkpanel.product.list') }}";
let url_edit = "{{ route('pnkpanel.product.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.product.update') }}";
let url_delete = "{{ route('pnkpanel.product.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.product.bulk_action') }}";
let url_bulk_action_update_rank = "{{ route('pnkpanel.product.bulk_action_update_rank') }}";
let url_bulk_action_update_group_rank = "{{ route('pnkpanel.product.bulk_action_update_group_rank') }}";
let url_bulk_action_update_sale_rank = "{{ route('pnkpanel.product.bulk_action_update_sale_rank') }}";
let url_bulk_action_create_clone = "{{ route('pnkpanel.product.bulk_action_create_clone') }}";
</script>
<script src="{{ asset('pnkpanel/js/product_list.js') }}"></script>
@endpush
