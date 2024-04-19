@if (isset($sliderData) and (count($sliderData) >0))
  {{ $section_start }}
    {{ $product_start_section }}
      {{ $product_content }} 
    {{ $product_end_section }}
  {{ $section_end }}
@endif
