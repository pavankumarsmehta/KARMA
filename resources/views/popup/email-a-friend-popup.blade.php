
<div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable1 common-popup" id="email-a-friend">
	<div class="modal-content">
		<div class="modal-body">
		<h4 class="modal-title">TELL A FRIEND</h4>
    <p id="email_friend_popup_message" style="color:var(--tealish-green); display:none; font-weight:400; font-size:15px;"></p>
			<svg class="btn-close svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
				<use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
			</svg>
			{!! NoCaptcha::renderJs() !!}
		<form name="frmFriend" id="frmFriend" method="post" class="mb-0">
			@csrf
			<input type="hidden" name="isAction" value="">
			<input type="hidden" name="products_id" value="{{ $products_id }}">	
			
				<div class="pb-4"><input type="text" class="form-control" id="friend_email1" name="friend_email1" placeholder="Friend's Email Address 1" required /></div>
				<div class="pb-4"><input type="text" class="form-control" id="friend_email2" name="friend_email2" placeholder="Friend's Email Address 2" /></div>
				<div class="pb-4"><textarea class="form-control" id="message" name="message" rows="3" placeholder="Your Message"></textarea></div>
				<div class="pb-4"><input type="text" class="form-control" id="your_email" name="your_email" placeholder="Your Email" required /></div>
				<div class="pb-4"><input type="text" class="form-control" id="your_name" name="your_name" placeholder="Your Name"/></div>
				<div class="pb-4 border_bottom">
					<div class="pb-2 error-selector sachin">
					{!! NoCaptcha::display() !!}
					@error('g-recaptcha-response')
						<div class="invalid-feedback">{{$message}}</div>
					@enderror
					<div id="g_recaptcha_error_div" class="error" style="display: none">
					</div>
					</div>
					<!-- <label class="form-label dblock">Type the characters you see in the picture below.</label>
					<div class="row">
						<div class="col-sm-4"><img id="characters" src="https://via.placeholder.com/140x50" alt="Captcha"> </div>
						<div class="col-xs-8"><input type="text" class="form-control" id="Verificationcode" value="" placeholder="Verification code" required /></div>
					</div> -->
				</div>
			
				<button type="submit" id="submitform" class="btn btn-success btn-block">Submit</button>
			</form>
		</div>
		
	</div>
</div>

<script src="{{ asset('public/js/front/email_a_friend_validate.js') }}"></script>
 