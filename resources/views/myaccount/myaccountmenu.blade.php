<div class="act_link">
	<div class="al_user">
		<div class="udinner">
			<div class="uname me-3">LI</div>
			<div class="user-ndtl">
			<div class="h5 mb-1" tabindex="0">Hi, {!! ucfirst(Session::get('sess_first_name')) !!}</div>
				<a href="{{route('logout')}}" class="btn-link" rel="nofollow" tabindex="0" title="Log Out" aria-label="Log Out">
					<svg class="svg_logout me-2" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_logout"></use></svg><span>Log Out</span>
				</a>
			</div>
		</div>
		<div class="myacc-toggle hidden-md-up">
			<svg class="svg_dots" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_dots" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_dots"></use></svg>
		</div>
	</div>
	<ul class="alu_inner">
		<li class="{{($active=='editprofile')?'active':''}}">
			<a href="{{route('editprofile')}}" tabindex="0" title="Edit Profile" aria-label="Edit Profile" rel="nofollow">
				<span>
					<svg class="svg_edit_profile" aria-hidden="true" role="img" width="30" height="30"><use href="#svg_edit_profile" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_edit_profile"></use></svg>Edit Profile
				</span>
				<svg class="svg_arrow_point me-0" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_arrow_point" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_point"></use></svg>
			</a>
		</li>
		<li class="{{($active=='orderhistory')?'active':''}}">
			<a href="{{route('order-history')}}" tabindex="0" title="Order History" aria-label="Order History" rel="nofollow">
				<span>
					<svg class="svg_history" aria-hidden="true" role="img" width="30" height="30"><use href="#svg_history" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_history"></use></svg>Order History
				</span>
				<svg class="svg_arrow_point me-0" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_arrow_point" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_point"></use></svg>
			</a>
		</li>
		<li class="{{($active=='changepassword')?'active':''}}">
			<a href="{{route('changepassword')}}" tabindex="0" title="Change Password" aria-label="Change Password" rel="nofollow">
				<span>
					<svg class="svg_change_pass" aria-hidden="true" role="img" width="30" height="30"><use href="#svg_change_pass" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_change_pass"></use></svg>Change Password
				</span>
				<svg class="svg_arrow_point me-0" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_arrow_point" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_point"></use></svg>
			</a>
		</li>
		<li class="{{($active=='wishlist')?'active':''}}">
			<a href="{{config('const.SITE_URL').'/wish-category.html'}}" tabindex="0" title="My Wishlist" aria-label="My Wishlist" rel="nofollow">
				<span>
					<svg class="svg_myproject" aria-hidden="true" role="img" width="30" height="30"><use href="#svg_myproject" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_myproject"></use></svg>My Wishlist
				</span>
				<svg class="svg_arrow_point me-0" aria-hidden="true" role="img" width="20" height="20"><use href="#svg_arrow_point" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_arrow_point"></use></svg>
			</a>
		</li>
	</ul>
</div>