
<div class="filter-title hidden-lg-up">Filter
    <a href="javascript:void(0)" tabindex="0" title="Filter Close" aria-label="Filter Close" class="filter-close">
        <svg class="fl svg_close" width="25px" height="25px" aria-hidden="true" role="img" data-dismiss="modal">
            <use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use>
        </svg>
    </a>
</div>
<div class="filter-content">
    @if(isset($Categories))
    @if(count($Categories) > 0)
       
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Category</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div data-cat="@if(isset($Category->category_id)) {{$Category->category_id}} @endif">
                    <div class="filter-options-scrollbar filter_checkbox-js">
                        <ul id="categories" class="filter-cat-list" style="margin-top: 10px;">
                        @foreach($Categories as $Main => $SubCats)
                            @foreach($SubCats as $Scat => $SubCat)
                                        @if($SubCat['Level'] == '0')
                                            <li class="maincat" tabindex="-1"><a href="{{$SubCat['product_link']}}" tabindex="0" class="@if(isset($CategoryId) && $SubCat['category_id']==$CategoryId)active @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">{{$SubCat['category_name']}}</a></li>
                                        @endif
                                                            
                                        @if($SubCat['Level'] == 1)
                                        @if(isset($SubCat['hasChild']) && $SubCat['hasChild'] == 'Yes')
                                            <li class="maincat" tabindex="-1" style="padding-left:5px;"><a href="{{$SubCat['product_link']}}" tabindex="0" class="@if(isset($CategoryId) && $SubCat['category_id']==$CategoryId)active @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">{{$SubCat['category_name']}}</a></li>
                                        @else
                                            <li class="subcat" tabindex="-1" style="padding-left:5px;"><a href="{{$SubCat['product_link']}}" tabindex="0" class="@if(isset($CategoryId) && $SubCat['category_id']==$CategoryId)active @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">{{$SubCat['category_name']}}</a></li>
                                        @endif
                                    @endif
                                @if($SubCat['Level'] == 2)
                                    <li class="subcat" tabindex="-1" style="padding-left:15px;"><a href="{{$SubCat['product_link']}}" tabindex="0" class="@if(isset($CategoryId) && $SubCat['category_id']==$CategoryId)active @endif" id="cat-{{$SubCat['category_id']}}"data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">{{$SubCat['category_name']}}</a></li>
                                    @endif
                        @endforeach 
                        @endforeach
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
     @endif
      @if(isset($Filters))
        @if(count($Filters) > 0)
            @foreach($Filters as $FilterArray)
                @foreach($FilterArray as $Filter)
                    <x-filter :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']"/>           
                @endforeach
            @endforeach
        @endif  
    @endif 
    @php 
$main_catgory = app('request')->main_catgory;
$priceArrayFilter = getPriceCategoryWise($main_catgory);
@endphp
@php 
			$filter_price = [];
			@endphp		
			@foreach($Filters as $FilterArray)
            @if(isset($FilterArray['price']))    
			@foreach($FilterArray as $Filter)
			@php 
					$filter_price = $FilterArray['price']['Selected'];
                 
			@endphp		
				@endforeach
			@endif
            @endforeach
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Price</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar filter_checkbox-js" id="price" data-filter-type="Price">
                    @foreach($priceArrayFilter as $key => $value)
              <div class="form-check" tabindex="0">
                <input class="form-check-input form-check-input-js" data-id="{{$key}}" @if(in_array($key,$filter_price)) checked @endif value="{{ $key }}" type="checkbox" value="{{ $key }}" id="{{ $key }}" />
                <label class="form-check-label form-check-label-js" data-id="{{$key}}" data-id="{{$key}}" for="{{ $key }}">{{$value}}</label>
              </div>
              @endforeach
                </div>
            </div>
        </div>
    </div>
</div>