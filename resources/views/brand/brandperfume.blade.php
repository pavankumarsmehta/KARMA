@extends('layouts.app')
@section('content')
<style>

</style>
<div class="static-page brandpage">
  <div class="container">
    <div class="breadcrumb">
      <a href="#" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
        <use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
        </svg> 
      </a> 
      <span class="active" tabindex="0">Brands</span>
    </div>
    <div class="static-hd"><h1 tabindex="0" class="h2">Shop all brands</h1></div>
    <div class="static-con">
      
      <div class="brand-hd">
       {{--  <h4>All brands</h4>--}}
        <div class="header_search">
          {{-- <div class="search_btn">
            <svg class="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
              <use href="#svg_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_search"></use>
            </svg> 
          </div>
          <input type="text" list="browsers" class="form-control" placeholder="Search Products" id="search_brands_list"  aria-label="Search.." aria-describedby="button-addon2">
          <datalist id="browsers">
            @foreach($BrandsList as $key => $BrandAlfa)   
            @foreach($BrandAlfa as $Brand)
            <option value='{{$Brand["Name"]}}'></option>
            @endforeach
            @endforeach
          </datalist>--}}
        </div>
      </div>
      <div id="alp-sticky-anchor"></div>
      <div id="alphabets_brands" class="alphabets1">
        @if( isset($BrandsList) && !empty($BrandsList))
        <div class="alphalist-inner">
          @foreach (range('A', 'Z') as $alphabet)
          <a class="scroll" tabindex="0" title="{{ $alphabet }}" aria-label="{{ $alphabet }}" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('{{ $alphabet }}')">{{ $alphabet }}</a>
          @endforeach
          <a class="scroll" tabindex="0" title="#" aria-label="#" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('ot')">#</a>
          <div class="clearfix"></div>
        </div>
      </div>
      @foreach($BrandsList as $key => $BrandAlfa)   
      <div class="alpha-bot-box">
        <div class="alpha-type tac"  id="topbrands{{$key}}">
          <a href="#" tabindex="0" title="{{$key}}" aria-label="{{$key}}"  id="@if($key != '#'){{strtolower($key)}}@else{{'ot'}}@endif">{{$key}}</a>
        </div>
        <div class="alpha-cat" id="topbrands_scoller{{$key}}">
          <ul>
            @foreach($BrandAlfa as $Brand)
            <li><a href="{{$Brand['Link']}}" tabindex="0" title="{{$Brand['Name']}}" aria-label="{{$Brand['Name']}}">{{$Brand['Name']}}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      @endforeach 
      @else
      <div class="errmsg  pb-5 text-center" tabindex="0" id="noprod">
				Sorry, there are not any shop by brands available right now
				
			</div>
      @endif 
    </div>
  </div>
</div>
@endsection

