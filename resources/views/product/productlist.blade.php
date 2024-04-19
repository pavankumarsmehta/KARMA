@php
   $advertisementArr = (isset($advertisementCategoryArr)  && !empty($advertisementCategoryArr)) ? $advertisementCategoryArr[0] : '';
   $sectionName = (isset($ProductListingType) && !empty($ProductListingType)) ? $ProductListingType : 'ProductListPage';
@endphp
@if(count($Products) > 0)
	<x-productbox :prodData="$Products" :advertisementData="$advertisementArr" :sectionName="$sectionName" />
@endif