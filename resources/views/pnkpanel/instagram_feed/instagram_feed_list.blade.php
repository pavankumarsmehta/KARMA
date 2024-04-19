@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							
							<div class="col-12 col-lg-auto mb-3 mb-lg-0"> <a href="{{ route('pnkpanel.instagram-feeds.fetch' ) }}" class="btn btn-primary btn-md font-weight-semibold btn-py-2 px-4"><i class="bx bx-plus"></i> Fetch Instagram Feeds</a> </div>
						
						</div>
					</div>
					
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="2%"><input type="checkbox" id="mainChk" name="select-all" class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
								<th width="10%">Image</th>
								<th width="10%">Username</th>
								<th width="20%">View On Instagram</th>
								<th width="10%">Status</th>
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
let url_list = "{{ route('pnkpanel.instagram-feeds.list') }}";
let url_bulk_action = "{{ route('pnkpanel.instagram-feeds.bulk_action') }}";
let url_fetch = "{{ route('pnkpanel.instagram-feeds.fetch') }}";
let url_accept = "{{ route('pnkpanel.instagram-feeds.accept', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/instagram_feed_list.js') }}"></script>
@endpush
