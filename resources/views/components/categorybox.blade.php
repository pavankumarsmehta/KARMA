<ul>
@foreach($catData  as $key =>  $cat)
<li>
<a href="{{$cat['category_url']}}" class="ctbox-item" title="{{$cat['category_name']}}" tabindex="0">
			<picture><img src="{{$cat['thumb_image']}}" alt="{{$cat['category_name']}}" width="238" height="238" loading="lazy" title="{{$cat['category_name']}}"/></picture>
			<span>{{$cat['category_name']}}</span>
</a>
</li>
@endforeach
</ul>