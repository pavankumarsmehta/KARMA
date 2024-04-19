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
    <div class="filter-options-content">
        <div class="foc_pd">
            <div class="filter-options-scrollbar">
                <ul class="filter-cat-list">
                    @foreach($Categories as $Main => $SubCats)
                         @foreach($SubCats as $Scat => $SubCat)
                                    @if($SubCat['Level'] == '0')
                                        <li class="maincat">{{$SubCat['category_name']}}</li>
                                    @endif
                                                        
                                    @if($SubCat['Level'] == 1)
                                    @if(isset($SubCat['hasChild']) && $SubCat['hasChild'] == 'Yes')
                                        <li class="maincat" style="padding-left:5px;">- {{$SubCat['category_name']}}</li>
                                    @else
                                        <li class="subcat" style="padding-left:5px;"><a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">- {{$SubCat['category_name']}}</a></li>
                                    @endif
                                @endif
                               @if($SubCat['Level'] == 2)
                                 <li class="subcat" style="padding-left:15px;"><a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}"data-id="{{$SubCat['category_id']}}" title="{{$SubCat['category_name']}}">- {{$SubCat['category_name']}}</a></li>
                                @endif
                       @endforeach 
                    @endforeach
                </ul>
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
                        @endif  @endif 
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Color</div>
        <div class="filter-options-content color-option">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_gray_silver" />
                        <label class="form-check-label" for="c_gray_silver">Gray / Silver</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_blue" />
                        <label class="form-check-label" for="c_blue">Blue</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_ivory_cream" />
                        <label class="form-check-label" for="c_ivory_cream">Ivory / Cream</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_brown_tan" />
                        <label class="form-check-label" for="c_brown_tan">Brown / Tan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" value="" id="c_black" />
                        <label class="form-check-label" for="c_black">Black</label>
                    </div>
                </div>
                <a href="javascript:void(0)" class="linksbb mt-2" title="See More">See More</a>
            </div>
        </div>
    </div>
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Brands</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="faux_leather" />
                        <label class="form-check-label" for="faux_leather">Faux Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="genuine_leather" />
                        <label class="form-check-label" for="genuine_leather">Genuine Leather</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="leather_match" />
                        <label class="form-check-label" for="leather_match">Leather Match</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Price Range</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_under75" />
                        <label class="form-check-label" for="c_under75">Under $75</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_75_125" />
                        <label class="form-check-label" for="c_75_125">$75 to $125</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_125_175" />
                        <label class="form-check-label" for="c_125_175">$125 to $175</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_175_250" />
                        <label class="form-check-label" for="c_175_250">$175 to $250</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_250_400" />
                        <label class="form-check-label" for="c_250_400">$250 to $400</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_400_above" />
                        <label class="form-check-label" for="c_400_above">$400 & Above</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Type</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_modern" />
                        <label class="form-check-label" for="c_modern">Modern</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_traditional" />
                        <label class="form-check-label" for="c_traditional">Traditional</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_mid_century_modern" />
                        <label class="form-check-label" for="c_mid_century_modern">Mid-Century Modern</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Size</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_modern" />
                        <label class="form-check-label" for="c_modern">Modern</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_traditional" />
                        <label class="form-check-label" for="c_traditional">Traditional</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_mid_century_modern" />
                        <label class="form-check-label" for="c_mid_century_modern">Mid-Century Modern</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-options active">
        <div class="filter-options-title" tabindex="0">Age Range</div>
        <div class="filter-options-content">
            <div class="foc_pd">
                <div class="filter-options-scrollbar">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_bedroom" />
                        <label class="form-check-label" for="c_bedroom">Bedroom</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_entry_hallway" />
                        <label class="form-check-label" for="c_entry_hallway">Entry & Hallway</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="c_kitchen_dining_room" />
                        <label class="form-check-label" for="c_kitchen_dining_room">Kitchen & Dining Room</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>