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
let url_list = "{{ route('pnkpanel.order.return_order') }}";
let url_bulk_action = "{{ route('pnkpanel.order.return_order.bulk_action') }}";
let url_ajax_loader = "{{ asset('/pnkpanel/images/ajax-loader-small.gif') }}";
let sampleOrder_list = "{{ route('pnkpanel.sampleOrder.export') }}";

</script>
<script src="{{ asset('pnkpanel/js/return_order_list.js') }}"></script>
@endpush
