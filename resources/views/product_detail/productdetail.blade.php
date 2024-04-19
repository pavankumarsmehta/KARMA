@extends('layouts.app')
@section('content')
<div class="container">
	{!! $bredCrumb_Detail !!}

	@include('product_detail.productdetail_leftimage_section')

	@include('product_detail.productdetail_right_desc_section')


	<div class="clearfix"></div>

	@include('product_detail.productdetail_compare_section')

	@include('product_detail.productdetail_relateditem_section')

	@include('product_detail.productdetail_review_section')




	@include('product_detail.productdetail_recentitem_section')



</div>

@endsection
@section('modal')
@endsection