@extends('layouts.app')
@section('content')
<style>
	.static-con{padding-top: 20px !important;font-family:'Rubik', sans-serif;font-size: 14px;font-weight:300 !important;line-height:1.55 !important;letter-spacing:0.15px !important;color:var(--black) !important;}
.static-con p{margin-top:0px !important;margin-bottom:15px !important;font-weight:300 !important;}
.static-con span{font-weight:300 !important;}
.static-con ol li{font-weight:300 !important;}
.static-con h1{ font-size:18px !important; line-height:18px !important; margin-bottom:15px !important; margin-top:25px !important;}

	</style>
<div class="static-page">
	<div class="container">
		<div class="breadcrumb">
	      <a href="{{config('const.SITE_URL')}}" tabindex="0" title="Home" aria-label="Home">Home<svg class="svg_barrow" width="272px" height="74px" aria-hidden="true" role="img">
	        <use href="#svg_barrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_barrow"></use>
	        </svg> 
	      </a> 
	      <span class="active" tabindex="0">{!! $breadcrumb !!}</span>
	    </div>
		<div class="static-hd"><h1 tabindex="0" class="h2">{{$StaticPage->title}}</h1></div>
		<div class="static-con">
		 @if($StaticPage->name=="faqs" && !empty($pageContent))
		 <div class="accordion faq-accordion" id="accordion">
			 @foreach($pageContent as $member)
			 @if(!empty($member['shop_question']))
			 <div class="accordion_title">{{ $member['shop_question'] }}</div>
			 <div class="accordion_content" style="display: none;" >{{ $member['shop_answer'] }}</div>
			 @endif
			 @endforeach
			</div>
		 @elseif($StaticPage->name!="faqs")
		 {!! $pageContent !!}
		 
		@endif
		</div>
	</div>    
</div>    
@endsection