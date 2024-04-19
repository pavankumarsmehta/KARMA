<div class="checkout-login checkout-acd">
	<div class="login-content">
		<div class="fwidth">
			<form class="row row10 needs-validation" id="formLogin" method="post" name="formLogin" novalidate="">
				@csrf
				<input type="hidden" name="action" value="signin">
				<h2>Sign In <span>Returning Customers</span></h2>
				<div class="py-2 py-md-3">
					<label for="email" class="form-label">Email Address *</label>
					<input type="text" class="form-control" id="email" name="email" required>
					@error('email')
					<div class="invalid-feedback">{{ $message}} frmerror_shw</div>
					@enderror
				</div>
				<div class="py-2 py-md-3">
					<label for="password" class="form-label">Password *</label>
					<input type="password" class="form-control" name="password" id="password" required>
					@error('password')
						<div class="invalid-feedback frmerror_shw">{{ $message}}</div>
					@enderror
					<span class="ex-label" style="float:right;">
					<a href="{{route('forgot-password')}}" title="Forgot Password">Forgot Password?</a>
					</span>
				</div>
				<div class="pt-5">
					<button type="submit" class="btn btn-success btn-block" title="Sign In" aria-label="Sign In">Sign In</button>
				</div>
			</form>
		</div>
		
	</div>
	<div class="login-content">
		<div class="fwidth">
			<h2>Create an Account <span>Lorem ipsum dolor sit amet consectetur. Proin mi nunc et morbi nullam in amet a. </span></h2>
			<div class="fr-info">
				<div class="fr-info-inr">
					<svg class="svg_delivery2" aria-hidden="true" role="img" width="28" height="28" loading="lazy">&nbsp;<use href="#svg_delivery2" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_delivery2"></use>
					</svg>
					<span>Free Shipping</span>
				</div>
				<div class="fr-info-inr">
					<svg class="svg_return2" aria-hidden="true" role="img" width="22" height="22" loading="lazy">&nbsp;<use href="#svg_return2" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_return2"></use>
					</svg>
					<span>Easy Returns</span>
				</div>
			</div>
			
			<div class="pb-1">
				<a javascript:void(0); onclick="return show_user_register_popup();" class="btn btn-border btn-twilight-border btn-block">Create Account</a>
			</div>
			<div class="dividerr_or no-brd"><span class="ttu">or</span></div>
			<div class="pt-1">
				<button class="btn btn-border btn-twilight-border btn-block" type="button" onclick="checkout_as_guest();" title="Continue as Guest" aria-label="Continue as Guest">Continue as Guest</button>				
			</div>
		</div>
	</div>
</div>