@php
   $sectionName = (isset($ProductListingType) && !empty($ProductListingType)) ? $ProductListingType : 'ProductListPage';
@endphp
@if(count($Products) > 0)
	<x-productbox :prodData="$Products" :sectionName="$sectionName" />
@endif