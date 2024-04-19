<article class="menu" tabindex="0" title="Menu" aria-label="Menu">Menu</article>
@guest
	<div style="float:right;">
		<a href="{{route('login')}}" tabindex="0" title="My Account" aria-label="Login">Login</a> 
		<a href="{{route('register')}}" tabindex="0" title="My Account" aria-label="Register">Register</a>
	</div>
@endguest
@auth
	<div style="float:right;"><a href="{{config('const.SITE_URL')}}/myaccount.html" tabindex="0" title="My Account" aria-label="My Account">My Account</a>&nbsp;&nbsp;<a href="{{route('logout')}}" tabindex="0" title="Logout" aria-label="Logout">Logout</a></div>
@endauth