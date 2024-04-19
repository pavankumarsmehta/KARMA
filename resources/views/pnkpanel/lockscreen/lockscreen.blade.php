@extends('pnkpanel.layouts.app')
@section('content')
<section class="body-sign body-locked">
	<div class="center-sign">
		<div class="panel card-sign pt-0">
			<div class="card-body">
				<form action="{{ route('pnkpanel.lockscreen') }}" name="frmLockScreen" id="frmLockScreen" method="post">
					@csrf
					
					<div class="text-center">
						<i class="fa fa-user-circle fa-10x"></i>
						<h3 class="user-name text-dark m-0 my-4" style="word-break: break-all;">{{ Pnkpanel::user()->email }}</h3>
					</div>
					
					{{--<!--<div class="current-user text-center">
						<img src="{{ asset('pnkpanel/images/admin_profile.jpg') }}" alt="John Doe" class="rounded-circle user-image" />
						<h2 class="user-name text-dark m-0">John Doe</h2>
						<p class="user-email m-0">{{ Pnkpanel::user()->email }}</p>
					</div>-->--}}
					
					@if(Session::has('site_common_msg'))
					<div class="alert alert-success alert-dismissible fade show">  
						<button type="button" class="close" data-dismiss="alert">×</button>  
						{!! Session::get('site_common_msg') !!}
					</div>
					@endif
					
					@if(Session::has('site_common_msg_err'))
					<div class="alert alert-danger alert-dismissible fade show">  
						<button type="button" class="close" data-dismiss="alert">×</button>  
						{!! Session::get('site_common_msg_err') !!}
					</div>
					@endif

					@if(Session::has('success'))
					<div class="alert alert-success alert-dismissible fade show">  
						<button type="button" class="close" data-dismiss="alert">×</button>  
						{!! Session::get('success') !!}
					</div>
					@endif
					
					@if(Session::has('error'))
					<div class="alert alert-danger alert-dismissible fade show">  
						<button type="button" class="close" data-dismiss="alert">×</button>  
						{!! Session::get('error') !!}
					</div>
					@endif
					
					<div class="form-group mb-3">
						<div class="input-group">
							<input type="password" name="password" id="password" class="form-control form-control-lg @error('password') error @enderror" placeholder="Password" />
							<span class="input-group-append">
								<span class="input-group-text">
									<i class="fas fa-lock"></i>
								</span>
							</span>
						</div>
						@error('password')
							<label class="error" for="password" role="alert">{{ $message }}</label>
						@enderror
					</div>

					<div class="row">
						<div class="col-6">
							<button type="button" class="btn btn-secondary pull-left" onClick="window.location.href='{{ route('pnkpanel.logout') }}';"><i class="fa fa-power-off fa-sm"></i>&nbsp; Logout</button>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-unlock fa-sm"></i>&nbsp; Unlock</button>
						</div>
					</div>
					{{--<!--<div class="row">
						<div class="col-12 text-center">
							<button type="submit" class="btn btn-primary px-4 py-2">Unlock</button>
						</div>
					</div>-->--}}
				</form>
			</div>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('pnkpanel/js/lockscreen.js') }}"></script>
@endpush
