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
      <span class="active" tabindex="0">Other Categories</span>
    </div>
    <div class="static-hd"><h1 tabindex="0" class="h2">Shop all Categories</h1></div>
    <div class="static-con">
      
    @if( isset($AllCategory) && !empty($AllCategory))

      @if($AllCategory)   
      <div class="alpha-bot-box">
        <div class="alpha-type tac" >
        </div>
        <div class="alpha-cat">
          <ul>
            @foreach($AllCategory as $key => $category)
            <li><a href="{{$category['category_full_url']}}" tabindex="0" title="{{$category['category_name']}}" aria-label="{{$category['category_name']}}">{{$category['category_name']}}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      @endif 
      @else
      <div class="errmsg  pb-5 text-center" tabindex="0" id="noprod">
				Sorry, there are not any Shop all Categories available right now
				
			</div>
      @endif 
    </div>
  </div>
</div>
@endsection

