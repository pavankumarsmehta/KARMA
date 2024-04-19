@php 
$pyment_mode = false;
$pyment_mode = Pnkpanel::payment_method_production_mode();
@endphp
@if(!empty(Pnkpanel::payment_method_production_mode()))
	<div class="fixed-top  {{(Pnkpanel::payment_method_production_mode()=='production') ? 'bg-success' : 'bg-danger' }} text-white center">Payment Gateway on {{(Pnkpanel::payment_method_production_mode()=='production') ? ' Live Mode' : 'Test Mode' }}</div>
@endif
<style>
	.header{margin-top:32px;}
	.inner-wrapper{padding-top:92px;}
	.fixed-top{padding:4px;}
</style>
<header class="header">
	<div class="logo-container">
		<a href="{{route('pnkpanel.dashboard')}}" class="logo"><img src="{{asset('pnkpanel/images/logo.png')}}" alt="{{ config('Settings.SITE_TITLE') }}" width="60"></a>
		<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened"><i class="fas fa-bars" aria-label="Toggle sidebar"></i></div>
	</div>
	<div class="header-right">
		<span class="separator"></span>
		<div id="userbox" class="userbox">
			<a href="#" data-toggle="dropdown">
				 <figure class="profile-picture"><i class="fa fa-user-circle fa-2x"></i></figure>
				 {{--<!--<figure class="profile-picture"><img src="https://via.placeholder.com/585x585" alt="" class="rounded-circle" data-lock-picture="https://via.placeholder.com/585x585" /></figure>-->--}}
				<div class="profile-info">
					<span class="name">{{Pnkpanel::user()->email}}</span>
					<span class="role">{{Pnkpanel::user()->admin_type}}</span>
				</div>
				<i class="fa custom-caret"></i>
			</a>
			<div class="dropdown-menu">
				<ul class="list-unstyled mb-2">
					<li class="divider"></li>
					<li> <a role="menuitem" tabindex="-1" href="{{route('pnkpanel.admin.edit', Pnkpanel::user()->admin_id)}}"><i class="bx bx-user-circle"></i> My Profile</a> </li>
					<li> <a role="menuitem" tabindex="-1" href="{{ route('pnkpanel.lockscreen') }}"><i class="bx bx-lock"></i> Lock Screen</a> </li>
					<li> <a role="menuitem" tabindex="-1" href="{{route('pnkpanel.logout')}}"><i class="bx bx-power-off"></i> Logout</a> </li>
				</ul>
			</div>
		</div>
	</div>
</header>
