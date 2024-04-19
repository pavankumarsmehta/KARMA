<header class="page-header">
	<h2>{!! $page_title ?? '' !!}</h2>
    <div class="right-wrapper text-right">
		<ol class="breadcrumbs mr-3">
			@if(Route::currentRouteName() != 'pnkpanel.dashboard')
			<li> <a href="{{route('pnkpanel.dashboard')}}"> <i class="bx bx-home-alt"></i> </a> </li>
			@endif
			@if (isset($breadcrumbs) && count($breadcrumbs))
				@foreach ($breadcrumbs as $breadcrumb)
					@if ($breadcrumb['url'] && !$loop->last)
						<li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
					@else
						<li class="breadcrumb-item active"><span>{{ $breadcrumb['title'] }}</span></li>
					@endif
				@endforeach
			@endif
    </ol>
	</div>
</header>
