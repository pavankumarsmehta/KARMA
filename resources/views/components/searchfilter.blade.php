@if(isset($filterAttr['title']) && !empty($filterAttr['title'])&& !empty($filterData))
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">{{$filterAttr['title']}}</div>
        <div class="filter-options-content" >
            <div class="foc_pd">
                <div class="filter-options-scrollbar filter_checkbox-js" id="{{$filterAttr['id']}}" data-filter-type="{{$filterAttr['title']}}" data-filter-controller="{{$CurrentController}}">
					@foreach($filterData as $key => $fData)
					<div class="form-check" tabindex="0">
						<input class="form-check-input form-check-input-js" data-id="{{$key}}" type="checkbox" value="{{$key}}" id="{{$filterAttr['id']}}_{{ $loop->index }}"  @if(in_array($key,$filterSelected)) checked @endif @if(in_array($key,$filterSelected) && $filterAttr["title"] == 'Brands' && $CurrentController == 'BrandController') disabled @endif />
						<label tabindex="-1" class="form-check-label form-check-label-js" data-id="{{$key}}" for="{{$filterAttr['id']}}_{{ $loop->index }}">{{ucwords(strtolower(trim($fData)))}}</label>
					</div>
					@endforeach
                </div>
            </div>
        </div>
    </div>
    @endif 

	