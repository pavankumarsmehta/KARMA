@foreach($popularBrandData  as $key =>  $popularBrand)
<a href="{{$popularBrand['brand_url']}}"  title="{{$popularBrand['brand_name']}}" tabindex="0">
				<picture><img src="{{$popularBrand['brand_logo_image_url']}}" alt="{{$popularBrand['brand_name']}}" width="165" height="53" loading="lazy" title="{{$popularBrand['brand_name']}}"/></picture>
			</a>
@endforeach