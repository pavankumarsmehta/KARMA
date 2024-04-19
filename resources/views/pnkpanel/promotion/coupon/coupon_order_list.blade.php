@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					<div class="datatable-header">
						<div class="row align-items-center mb-3">
							<div class="col-12 col-lg-auto mb-3 mb-lg-0"> 
								@foreach($coupan_name as $plaza)
									<h3><b>{{ $plaza->coupon_number }}</b> coupon applied for below order lists</h3>
								@endforeach
							</div>
							
						</div>
					</div>
					<input type="hidden" name="coupon_id" id="coupon_id" value="{{ $coupon_id }}">
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 1100px;">
						<thead>
							<tr>
								<th width="8%">Order No.</th>
								<th width="10%">Order DateTime</th>
								{{--<!--<th width="5%">Date</th>
								<th width="5%">Time</th>-->--}}
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
let url_list = "{{ route('pnkpanel.coupon.coupon_order_list', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/coupon_order_list.js') }}"></script>
@endpush
