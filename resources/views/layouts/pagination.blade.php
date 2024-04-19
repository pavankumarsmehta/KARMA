@if ($paginator->hasPages())
	<div class="pagination">
		{{-- Previous Page Link --}}
		@if ($paginator->onFirstPage())
			<a href="javascript:void(0);" class="previous disabled" onclick="return false;">
				<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none">
					<use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use>
				</svg>
			</a>
		@else
			<a href="{{ $paginator->previousPageUrl() }}"  class="previous active">
				<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none">
					<use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use>
				</svg>
			</a>
		@endif
		{{-- Pagination Elements --}}
		@foreach ($elements as $element)
			{{-- "Three Dots" Separator --}}
			@if (is_string($element))
			<a href="javascript:void(0)" class="disabled">{{ $element }}</a>
			@endif
			{{-- Array Of Links --}}
			@if (is_array($element))
				@foreach ($element as $page => $url)
					@if ($page == $paginator->currentPage())
					<a href="javascript:void(0)" class="active">{{ $page }}</a>
					@else
					<a href="{{ $url }}">{{ $page }}</a>
					@endif
				@endforeach
			@endif
		@endforeach
		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
			<a href="{{ $paginator->nextPageUrl() }}" class="next active">
				<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none">
					<use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use>
				</svg>
			</a>
			
		
		@else
			<a href="javascript:void(0);" class="next disabled">
				<svg class="svg_pag_arrow" width="13px" height="19px" aria-hidden="true" role="img" fill="none">
					<use href="#svg_pag_arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_pag_arrow"></use>
				</svg>
			</a>
		@endif
	</div>
@endif