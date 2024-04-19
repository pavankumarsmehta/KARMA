
<div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable1 common-popup" id="return-order-item">
	<div class="modal-content">
		<div class="modal-body">
		<h4 class="modal-title">Return Item</h4>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
				<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
			</svg>
		<form name="frmReturnItem" id="frmReturnItem" method="post" class="mb-0">
			@csrf
			<input type="hidden" name="isAction" value="">
			<input type="hidden" name="order_detail_id" value="{{ $order_detail_id }}">	
			
				<div class="pb-4">
					<select  class="form-select return_request_quantity" name="return_request_quantity" id="return_request_quantity">
						@for($i=1; $i<=$orderItemQuantity; $i++)
							<option value="{{$i}}">{{$i}}</option>
						@endfor
					</select>
				</div>
				<div class="pb-4"><textarea class="form-control" id="message" name="message" rows="3" placeholder="Your Message" required></textarea></div>
			
				<button type="submit" id="submitform" class="btn btn-success btn-block">Submit</button>
			</form>
		</div>
		
	</div>
</div>

<script src="{{ asset('public/js/front/return_order_item_validate.js') }}"></script>
 