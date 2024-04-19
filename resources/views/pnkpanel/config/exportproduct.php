<?php
/*
	-------------------------------------------
	Instructions :
	-------------------------------------------
	Array : gen_csv_fields_arr

	Key : key name must be same as the csv header rows column values, spaces are replaced with underscore.

	import_field 	  : contains the filed name exists in the import products table
	export_field 	  : contains the filed name exists in the export products table
	product_field 	  : contains the filed name exists in the products table
	import_header_val : csv header row column name
	export_header_val : csv header row column name
	product_category  : Header row column name keep same for all the projects
*/

/* This array contains the columns name which are required in the uploaded product csv */
$gen_required_fields = array('sku', 'name');


/*
	This array contains all the fields which are valid in the csv
	If product csv contains the coulmn(s) which are not in the product sample csv then its shows the invalid csv format
*/

return array(

	'sku' =>  array(
		'import_field'		=> 	'sku',
		'export_field'		=> 	'sku',
		'product_field'		=>	'sku',
		'import_header_val'	=>	'SKU',
		'export_header_val'	=> 	'SKU'
	),

	'parent_sku' =>  array(
		'import_field'	=> 	'parent_sku',
		'export_field'		=> 	'parent_sku',
		'product_field'		=>	'parent_sku',
		'import_header_val'	=>	'Parent SKU',
		'export_header_val'	=> 	'Parent SKU'
	),
	'name' 	=>  array(
		'import_field'		=> 	'product_name',
		'export_field'		=> 	'product_name',
		'product_field'		=>	'product_name',
		'import_header_val'	=>	'Name',
		'export_header_val'	=> 	'Name'
	),

	'category' 	=>  array(
		'import_field'		=> 	'category',
		'export_field'		=> 	'category',
		'product_field'		=>	'category',
		'import_header_val'	=>	'Category',
		'export_header_val'	=> 	'Category'
	),

	'collection' 	=>  array(
		'import_field'		=> 	'collection',
		'export_field'		=> 	'collection',
		'product_field'		=>	'collection',
		'import_header_val'	=>	'Collection',
		'export_header_val'	=> 	'Collection'
	),

	'weave' 	=>  array(
		'import_field'		=> 	'weave',
		'export_field'		=> 	'weave',
		'product_field'		=>	'weave',
		'import_header_val'	=>	'Weave',
		'export_header_val'	=> 	'Weave'
	),

	'construction' 	=>  array(
		'import_field'		=> 	'construction',
		'export_field'		=> 	'construction',
		'product_field'		=>	'construction',
		'import_header_val'	=>	'Construction',
		'export_header_val'	=> 	'Construction'
	),

	'content' 	=>  array(
		'import_field'		=> 	'content',
		'export_field'		=> 	'content',
		'product_field'		=>	'content',
		'import_header_val'	=>	'Content',
		'export_header_val'	=> 	'Content'
	),

	'content2' =>  array(
		'import_field'		=> 	'content2',
		'export_field'		=> 	'content2',
		'product_field'		=>	'content2',
		'import_header_val'	=>	'Content2',
		'export_header_val'	=> 	'Content2'
	),

	'shape' 		=>  array(
		'import_field'		=> 	'shape',
		'export_field'		=> 	'shape',
		'product_field'		=>	'shape',
		'import_header_val'	=>	'Shape',
		'export_header_val'	=> 	'Shape'
	),

	'pile_height' 		=>  array(
		'import_field'		=> 	'pile_height',
		'export_field'		=> 	'pile_height',
		'product_field'		=>	'pile_height',
		'import_header_val'	=>	'Pile Height',
		'export_header_val'	=> 	'Pile Height'
	),

	'thickness' 		=>  array(
		'import_field'		=> 	'thickness',
		'export_field'		=> 	'thickness',
		'product_field'		=>	'thickness',
		'import_header_val'	=>	'Thickness',
		'export_header_val'	=> 	'Thickness'
	),

	'backing' 			=>  array(
		'import_field'		=> 	'backing',
		'export_field'		=> 	'backing',
		'product_field'		=>	'backing',
		'import_header_val'	=>	'Backing',
		'export_header_val'	=> 	'Backing'
	),

	'brand' 	=>  array(
		'import_field'		=> 	'brand',
		'export_field'		=> 	'brand',
		'product_field'		=>	'brand',
		'import_header_val'	=>	'Brand',
		'export_header_val'	=> 	'Brand'
	),

	'description' =>  array(
		'import_field'		=> 	'description',
		'export_field'		=> 	'description',
		'product_field'		=>	'description',
		'import_header_val'	=>	'Description',
		'export_header_val'	=> 	'Description'
	),

	'specification' =>  array(
		'import_field'		=> 	'specification',
		'export_field'		=> 	'specification',
		'product_field'		=>	'specification',
		'import_header_val'	=>	'Specification',
		'export_header_val'	=> 	'Specification'
	),

	// 'seat_specification' =>  array(
	// 	'import_field'		=> 	'seat_specification',
	// 	'export_field'		=> 	'seat_dimension',
	// 	'product_field'		=>	'seat_specification',
	// 	'import_header_val'	=>	'Seat Specification',
	// 	'export_header_val'	=> 	'Seat Dimension'
	// ),

	'color_family' 	=>  array(
		'import_field'		=> 	'color_family',
		'export_field'		=> 	'color_family',
		'product_field'		=>	'color_family',
		'import_header_val'	=>	'Color Family',
		'export_header_val'	=> 	'Color Family'
	),

	'color' 	=>  array(
		'import_field'		=> 	'color',
		'export_field'		=> 	'color',
		'product_field'		=>	'color',
		'import_header_val'	=>	'Color',
		'export_header_val'	=> 	'Color'
	),

	'size_dimension' 	=>  array(
		'import_field'		=> 	'size_dimension',
		'export_field'		=> 	'size_dimension',
		'product_field'		=>	'size_dimension',
		'import_header_val'	=>	'Size Dimension',
		'export_header_val'	=> 	'Size Dimension'
	),

	'size_family' 	=>  array(
		'import_field'		=> 	'size_family',
		'export_field'		=> 	'size_family',
		'product_field'		=>	'size_family',
		'import_header_val'	=>	'Size Family',
		'export_header_val'	=> 	'Size Family'
	),

	'weight' =>  array(
		'import_field'		=> 	'weight',
		'export_field'		=> 	'weight',
		'product_field'		=>	'weight',
		'import_header_val'	=>	'Weight',
		'export_header_val'	=> 	'Weight'
	),

	'metal_color' =>  array(
		'import_field'		=> 	'metal_color',
		'export_field'		=> 	'metal_color',
		'product_field'		=>	'metal_color',
		'import_header_val'	=>	'Metal Color',
		'export_header_val'	=> 	'Metal Color'
	),

	'metal_type' =>  array(
		'import_field'		=> 	'metal_type',
		'export_field'		=> 	'metal_type',
		'product_field'		=>	'metal_type',
		'import_header_val'	=>	'Metal Type',
		'export_header_val'	=> 	'Metal Type'
	),

	'width' =>  array(
		'import_field'		=> 	'width',
		'export_field'		=> 	'width',
		'product_field'		=>	'width',
		'import_header_val'	=>	'Width',
		'export_header_val'	=> 	'Width'
	),

	'depth' =>  array(
		'import_field'		=> 	'depth',
		'export_field'		=> 	'depth',
		'product_field'		=>	'depth',
		'import_header_val'	=>	'Depth',
		'export_header_val'	=> 	'Depth'
	),

	'height' =>  array(
		'import_field'		=> 	'height',
		'export_field'		=> 	'height',
		'product_field'		=>	'height',
		'import_header_val'	=>	'Height',
		'export_header_val'	=> 	'Height'
	),

	'length' =>  array(
		'import_field'		=> 	'length',
		'export_field'		=> 	'length',
		'product_field'		=>	'length',
		'import_header_val'	=>	'Length',
		'export_header_val'	=> 	'Length'
	),

	'size_unit' =>  array(
		'import_field'		=> 	'size_unit',
		'export_field'		=> 	'size_unit',
		'product_field'		=>	'size_unit',
		'import_header_val'	=>	'Size Unit',
		'export_header_val'	=> 	'Size Unit'
	),

	'feature' =>  array(
		'import_field'		=> 	'feature',
		'export_field'		=> 	'feature',
		'product_field'		=>	'feature',
		'import_header_val'	=>	'Feature',
		'export_header_val'	=> 	'Feature'
	),

	'match' =>  array(
		'import_field'		=> 	'match',
		'export_field'		=> 	'match',
		'product_field'		=>	'match',
		'import_header_val'	=>	'Match',
		'export_header_val'	=> 	'Match'
	),

	'room' =>  array(
		'import_field'		=> 	'room',
		'export_field'		=> 	'room',
		'product_field'		=>	'room',
		'import_header_val'	=>	'Room',
		'export_header_val'	=> 	'Room'
	),

	'style_1' =>  array(
		'import_field'		=> 	'style_1',
		'export_field'		=> 	'style_1',
		'product_field'		=>	'style_1',
		'import_header_val'	=>	'Style 1',
		'export_header_val'	=> 	'Style 1'
	),

	'country' =>  array(
		'import_field'		=> 	'country',
		'export_field'		=> 	'country',
		'product_field'		=>	'country',
		'import_header_val'	=>	'Country',
		'export_header_val'	=> 	'Country'
	),

	'care' =>  array(
		'import_field'		=> 	'care',
		'export_field'		=> 	'care',
		'product_field'		=>	'care',
		'import_header_val'	=>	'Care',
		'export_header_val'	=> 	'Care'
	),

	'accessories' =>  array(
		'import_field'		=> 	'accessories',
		'export_field'		=> 	'accessories',
		'product_field'		=>	'accessories',
		'import_header_val'	=>	'Accessories',
		'export_header_val'	=> 	'Accessories'
	),

	'swatch_image' =>  array(
		'import_field'		=> 	'swatch_image',
		'export_field'		=> 	'swatch_image',
		'product_field'		=>	'swatch_image',
		'import_header_val'	=>	'Swatch Image Zoom',
		'export_header_val'	=> 	'Swatch Image Zoom'
	),
	'swatch_image_thumb' =>  array(
		'import_field'		=> 	'swatch_image_thumb',
		'export_field'		=> 	'swatch_image_thumb',
		'product_field'		=>	'swatch_image_thumb',
		'import_header_val'	=>	'Swatch Image Thumb',
		'export_header_val'	=> 	'Swatch Image Thumb'
	),
	'swatch_image_small' =>  array(
		'import_field'		=> 	'swatch_image_small',
		'export_field'		=> 	'swatch_image_small',
		'product_field'		=>	'swatch_image_small',
		'import_header_val'	=>	'Swatch Image Small',
		'export_header_val'	=> 	'Swatch Image Small'
	),
	'swatch_image_large' =>  array(
		'import_field'		=> 	'swatch_image_large',
		'export_field'		=> 	'swatch_image_large',
		'product_field'		=>	'swatch_image_large',
		'import_header_val'	=>	'Swatch Image Large',
		'export_header_val'	=> 	'Swatch Image Large'
	),
	'main_image' =>  array(
		'import_field'		=> 	'main_image',
		'export_field'		=> 	'main_image',
		'product_field'		=>	'main_image',
		'import_header_val'	=>	'Main Image Zoom',
		'export_header_val'	=> 	'Main Image Zoom'
	),
	'main_image_thumb' =>  array(
		'import_field'		=> 	'main_image_thumb',
		'export_field'		=> 	'main_image_thumb',
		'product_field'		=>	'main_image_thumb',
		'import_header_val'	=>	'Main Image Thumb',
		'export_header_val'	=> 	'Main Image Thumb'
	),
	'main_image_small' =>  array(
		'import_field'		=> 	'main_image_small',
		'export_field'		=> 	'main_image_small',
		'product_field'		=>	'main_image_small',
		'import_header_val'	=>	'Main Image Small',
		'export_header_val'	=> 	'Main Image Small'
	),
	'main_image_large' =>  array(
		'import_field'		=> 	'main_image_large',
		'export_field'		=> 	'main_image_large',
		'product_field'		=>	'main_image_large',
		'import_header_val'	=>	'Main Image Large',
		'export_header_val'	=> 	'Main Image Large'
	),
	'rollover_image' =>  array(
		'import_field'		=> 	'rollover_image',
		'export_field'		=> 	'rollover_image',
		'product_field'		=>	'rollover_image',
		'import_header_val'	=>	'Rollover Image Zoom',
		'export_header_val'	=> 	'Rollover Image Zoom'
	),
	'rollover_image_thumb' =>  array(
		'import_field'		=> 	'rollover_image_thumb',
		'export_field'		=> 	'rollover_image_thumb',
		'product_field'		=>	'rollover_image_thumb',
		'import_header_val'	=>	'Rollover Image Thumb',
		'export_header_val'	=> 	'Rollover Image Thumb'
	),
	'rollover_image_small' =>  array(
		'import_field'		=> 	'rollover_image_small',
		'export_field'		=> 	'rollover_image_small',
		'product_field'		=>	'rollover_image_small',
		'import_header_val'	=>	'Rollover Image Small',
		'export_header_val'	=> 	'Rollover Image Small'
	),
	'rollover_image_large' =>  array(
		'import_field'		=> 	'rollover_image_large',
		'export_field'		=> 	'rollover_image_large',
		'product_field'		=>	'rollover_image_large',
		'import_header_val'	=>	'Rollover Image Large',
		'export_header_val'	=> 	'Rollover Image Large'
	),
	'additional_images' =>  array(
		'import_field'		=> 	'additional_images',
		'export_field'		=> 	'additional_images',
		'product_field'		=>	'additional_images',
		'import_header_val'	=>	'Additional Images Zoom',
		'export_header_val'	=> 	'Additional Images Zoom'
	),
	'additional_images_thumb' =>  array(
		'import_field'		=> 	'additional_images_thumb',
		'export_field'		=> 	'additional_images_thumb',
		'product_field'		=>	'additional_images_thumb',
		'import_header_val'	=>	'Additional Images Thumb',
		'export_header_val'	=> 	'Additional Images Thumb'
	),
	'additional_images_small' =>  array(
		'import_field'		=> 	'additional_images_small',
		'export_field'		=> 	'additional_images_small',
		'product_field'		=>	'additional_images_small',
		'import_header_val'	=>	'Additional Images Small',
		'export_header_val'	=> 	'Additional Images Small'
	),
	'additional_images_large' =>  array(
		'import_field'		=> 	'additional_images_large',
		'export_field'		=> 	'additional_images_large',
		'product_field'		=>	'additional_images_large',
		'import_header_val'	=>	'Additional Images Large',
		'export_header_val'	=> 	'Additional Images Large'
	),
	'video_url' =>  array(
		'import_field'		=> 	'video_url',
		'export_field'		=> 	'video_url',
		'product_field'		=>	'video_url',
		'import_header_val'	=>	'Video URL',
		'export_header_val'	=> 	'Video URL'
	),
	'assembly' =>  array(
		'import_field'		=> 	'assembly',
		'export_field'		=> 	'assembly',
		'product_field'		=>	'assembly',
		'import_header_val'	=>	'Assembly',
		'export_header_val'	=> 	'Assembly'
	),
	'badge' =>  array(
		'import_field'		=> 	'badge',
		'export_field'		=> 	'badge',
		'product_field'		=>	'badge',
		'import_header_val'	=>	'Badge',
		'export_header_val'	=> 	'Badge'
	),
	'display_rank' =>  array(
		'import_field'		=> 	'display_rank',
		'export_field'		=> 	'display_rank',
		'product_field'		=>	'display_rank',
		'import_header_val'	=>	'Display Rank',
		'export_header_val'	=> 	'Display Rank'
	),
	'group_rank' =>  array(
		'import_field'		=> 	'group_rank',
		'export_field'		=> 	'group_rank',
		'product_field'		=>	'group_rank',
		'import_header_val'	=>	'Group Rank',
		'export_header_val'	=> 	'Group Rank'
	),
	'meta_title' =>  array(
		'import_field'		=> 	'meta_title',
		'export_field'		=> 	'meta_title',
		'product_field'		=>	'meta_title',
		'import_header_val'	=>	'Meta Title',
		'export_header_val'	=> 	'Meta Title'
	),
	'meta_keywords' =>  array(
		'import_field'		=> 	'meta_keywords',
		'export_field'		=> 	'meta_keywords',
		'product_field'		=>	'meta_keywords',
		'import_header_val'	=>	'Meta Keywords',
		'export_header_val'	=> 	'Meta Keywords'
	),
	'meta_description' =>  array(
		'import_field'		=> 	'meta_description',
		'export_field'		=> 	'meta_description',
		'product_field'		=>	'meta_description',
		'import_header_val'	=>	'Meta Description',
		'export_header_val'	=> 	'Meta Description'
	),
	'upc' =>  array(
		'import_field'		=> 	'upc',
		'export_field'		=> 	'upc',
		'product_field'		=>	'upc',
		'import_header_val'	=>	'UPC',
		'export_header_val'	=> 	'UPC'
	),
	'quantity' =>  array(
		'import_field'		=> 	'quantity',
		'export_field'		=> 	'quantity',
		'product_field'		=>	'quantity',
		'import_header_val'	=>	'Quantity',
		'export_header_val'	=> 	'Quantity'
	),

	'sales_unit' =>  array(
		'import_field'		=> 	'sales_unit',
		'export_field'		=> 	'sales_unit',
		'product_field'		=>	'sales_unit',
		'import_header_val'	=>	'Sales Unit',
		'export_header_val'	=> 	'Sales Unit'
	),
	'status' =>  array(
		'import_field'		=> 	'status',
		'export_field'		=> 	'status',
		'product_field'		=>	'status',
		'import_header_val'	=>	'Status',
		'export_header_val'	=> 	'Status'
	),


	'lamp_color' =>  array(
		'import_field'		=> 	'lamp_color',
		'export_field'		=> 	'lamp_color',
		'product_field'		=>	'lamp_color',
		'import_header_val'	=>	'Lamp Color',
		'export_header_val'	=> 	'Lamp Color'
	),

	'shade_color' =>  array(
		'import_field'		=> 	'shade_color',
		'export_field'		=> 	'shade_color',
		'product_field'		=>	'shade_color',
		'import_header_val'	=>	'Shade Color',
		'export_header_val'	=> 	'Shade Color'
	),

	'base_type' =>  array(
		'import_field'		=> 	'base_type',
		'export_field'		=> 	'base_type',
		'product_field'		=>	'base_type',
		'import_header_val'	=>	'Base Type',
		'export_header_val'	=> 	'Base Type'
	),

	'body_material' =>  array(
		'import_field'		=> 	'body_material',
		'export_field'		=> 	'body_material',
		'product_field'		=>	'body_material',
		'import_header_val'	=>	'Body Material',
		'export_header_val'	=> 	'Body Material'
	),

	'shade_dimensions' =>  array(
		'import_field'		=> 	'shade_dimensions',
		'export_field'		=> 	'shade_dimensions',
		'product_field'		=>	'shade_dimensions',
		'import_header_val'	=>	'Shade Dimensions',
		'export_header_val'	=> 	'Shade Dimensions'
	),

	'canopy_dimensions' =>  array(
		'import_field'		=> 	'canopy_dimensions',
		'export_field'		=> 	'canopy_dimensions',
		'product_field'		=>	'canopy_dimensions',
		'import_header_val'	=>	'Canopy Dimensions',
		'export_header_val'	=> 	'Canopy Dimensions'
	),

	'light_bulb_bass' =>  array(
		'import_field'		=> 	'light_bulb_bass',
		'export_field'		=> 	'light_bulb_bass',
		'product_field'		=>	'light_bulb_bass',
		'import_header_val'	=>	'Light Bulb Bass',
		'export_header_val'	=> 	'Light Bulb Bass'
	),

	'cord_length' =>  array(
		'import_field'		=> 	'cord_length',
		'export_field'		=> 	'cord_length',
		'product_field'		=>	'cord_length',
		'import_header_val'	=>	'Cord Length',
		'export_header_val'	=> 	'Cord Length'
	),

	'chain_rod_length' =>  array(
		'import_field'		=> 	'chain_rod_length',
		'export_field'		=> 	'chain_rod_length',
		'product_field'		=>	'chain_rod_length',
		'import_header_val'	=>	'Chain Rod Length',
		'export_header_val'	=> 	'Chain Rod Length'
	),

	'maximum_hanging_length' =>  array(
		'import_field'		=> 	'maximum_hanging_length',
		'export_field'		=> 	'maximum_hanging_length',
		'product_field'		=>	'maximum_hanging_length',
		'import_header_val'	=>	'Maximum Hanging Length',
		'export_header_val'	=> 	'Maximum Hanging Length'
	),

	'minimum_hanging_length' =>  array(
		'import_field'		=> 	'minimum_hanging_length',
		'export_field'		=> 	'minimum_hanging_length',
		'product_field'		=>	'minimum_hanging_length',
		'import_header_val'	=>	'Minimum Hanging Length',
		'export_header_val'	=> 	'Minimum Hanging Length'
	),

	'bulb_type' =>  array(
		'import_field'		=> 	'bulb_type',
		'export_field'		=> 	'bulb_type',
		'product_field'		=>	'bulb_type',
		'import_header_val'	=>	'Bulb Type',
		'export_header_val'	=> 	'Bulb Type'
	),

	'watts' =>  array(
		'import_field'		=> 	'watts',
		'export_field'		=> 	'watts',
		'product_field'		=>	'watts',
		'import_header_val'	=>	'Watts',
		'export_header_val'	=> 	'Watts'
	),

	'special_functions' =>  array(
		'import_field'		=> 	'special_functions',
		'export_field'		=> 	'special_functions',
		'product_field'		=>	'special_functions',
		'import_header_val'	=>	'Special Functions',
		'export_header_val'	=> 	'Special Functions'
	),

	'wood_color' =>  array(
		'import_field'		=> 	'wood_color',
		'export_field'		=> 	'wood_color',
		'product_field'		=>	'wood_color',
		'import_header_val'	=>	'Wood Color',
		'export_header_val'	=> 	'Wood Color'
	),

	'wood_content' =>  array(
		'import_field'		=> 	'wood_content',
		'export_field'		=> 	'wood_content',
		'product_field'		=>	'wood_content',
		'import_header_val'	=>	'Wood Content',
		'export_header_val'	=> 	'Wood Content'
	),

	'finish_surface_treatment' =>  array(
		'import_field'		=> 	'finish_surface_treatment',
		'export_field'		=> 	'finish_surface_treatment',
		'product_field'		=>	'finish_surface_treatment',
		'import_header_val'	=>	'Finish Surface Treatment',
		'export_header_val'	=> 	'Finish Surface Treatment'
	),

	'fabric_color' =>  array(
		'import_field'		=> 	'fabric_color',
		'export_field'		=> 	'fabric_color',
		'product_field'		=>	'fabric_color',
		'import_header_val'	=>	'Fabric Color',
		'export_header_val'	=> 	'Fabric Color'
	),

	'fabric_material' =>  array(
		'import_field'		=> 	'fabric_material',
		'export_field'		=> 	'fabric_material',
		'product_field'		=>	'fabric_material',
		'import_header_val'	=>	'Fabric Material',
		'export_header_val'	=> 	'Fabric Material'
	),

	'fill_material' =>  array(
		'import_field'		=> 	'fill_material',
		'export_field'		=> 	'fill_material',
		'product_field'		=>	'fill_material',
		'import_header_val'	=>	'Fill Material',
		'export_header_val'	=> 	'Fill Material'
	),

	'seat_dimension' =>  array(
		'import_field'		=> 	'seat_dimension',
		'export_field'		=> 	'seat_dimension',
		'product_field'		=>	'seat_dimension',
		'import_header_val'	=>	'Seat Dimension',
		'export_header_val'	=> 	'Seat Dimension'
	),

	'table_dimension' =>  array(
		'import_field'		=> 	'table_dimension',
		'export_field'		=> 	'table_dimension',
		'product_field'		=>	'table_dimension',
		'import_header_val'	=>	'Table Dimension',
		'export_header_val'	=> 	'Table Dimension'
	),

	'bench_dimension' =>  array(
		'import_field'		=> 	'bench_dimension',
		'export_field'		=> 	'bench_dimension',
		'product_field'		=>	'bench_dimension',
		'import_header_val'	=>	'Bench Dimension',
		'export_header_val'	=> 	'Bench Dimension'
	),

	'chair_dimensions' =>  array(
		'import_field'		=> 	'chair_dimensions',
		'export_field'		=> 	'chair_dimensions',
		'product_field'		=>	'chair_dimensions',
		'import_header_val'	=>	'Chair Dimensions',
		'export_header_val'	=> 	'Chair Dimensions'
	),

	'weight_capacity' =>  array(
		'import_field'		=> 	'weight_capacity',
		'export_field'		=> 	'weight_capacity',
		'product_field'		=>	'weight_capacity',
		'import_header_val'	=>	'Weight Capacity',
		'export_header_val'	=> 	'Weight Capacity'
	),

	'no_of_shelves' =>  array(
		'import_field'		=> 	'no_of_shelves',
		'export_field'		=> 	'no_of_shelves',
		'product_field'		=>	'no_of_shelves',
		'import_header_val'	=>	'No_Of_Shelves',
		'export_header_val'	=> 	'No_Of_Shelves'
	),

	'shelf_dimensions' =>  array(
		'import_field'		=> 	'shelf_dimensions',
		'export_field'		=> 	'shelf_dimensions',
		'product_field'		=>	'shelf_dimensions',
		'import_header_val'	=>	'Shelf Dimensions',
		'export_header_val'	=> 	'Shelf Dimensions'
	),

	'no_of_drawers' =>  array(
		'import_field'		=> 	'no_of_drawers',
		'export_field'		=> 	'no_of_drawers',
		'product_field'		=>	'no_of_drawers',
		'import_header_val'	=>	'No_Of_Drawers',
		'export_header_val'	=> 	'No_Of_Drawers'
	),

	'drawer_dimensions' =>  array(
		'import_field'		=> 	'drawer_dimensions',
		'export_field'		=> 	'drawer_dimensions',
		'product_field'		=>	'drawer_dimensions',
		'import_header_val'	=>	'Drawer Dimensions',
		'export_header_val'	=> 	'Drawer Dimensions'
	),

	'metal_finish' =>  array(
		'import_field'		=> 	'metal_finish',
		'export_field'		=> 	'metal_finish',
		'product_field'		=>	'metal_finish',
		'import_header_val'	=>	'Metal Finish',
		'export_header_val'	=> 	'Metal Finish'
	),

	'back_cover' =>  array(
		'import_field'		=> 	'back_cover',
		'export_field'		=> 	'back_cover',
		'product_field'		=>	'back_cover',
		'import_header_val'	=>	'Back Cover',
		'export_header_val'	=> 	'Back Cover'
	),

	'closure' =>  array(
		'import_field'		=> 	'closure',
		'export_field'		=> 	'closure',
		'product_field'		=>	'closure',
		'import_header_val'	=>	'Closure',
		'export_header_val'	=> 	'Closure'
	),

	'insert' =>  array(
		'import_field'		=> 	'insert',
		'export_field'		=> 	'insert',
		'product_field'		=>	'insert',
		'import_header_val'	=>	'Insert',
		'export_header_val'	=> 	'Insert'
	),

	'glass_thickness' =>  array(
		'import_field'		=> 	'glass_thickness',
		'export_field'		=> 	'glass_thickness',
		'product_field'		=>	'glass_thickness',
		'import_header_val'	=>	'Glass Thickness',
		'export_header_val'	=> 	'Glass Thickness'
	),

	'mirror_dimensions' =>  array(
		'import_field'		=> 	'mirror_dimensions',
		'export_field'		=> 	'mirror_dimensions',
		'product_field'		=>	'mirror_dimensions',
		'import_header_val'	=>	'Mirror Dimensions',
		'export_header_val'	=> 	'Mirror Dimensions'
	),

	'retail_price' =>  array(
		'import_field'		=> 	'retail_price',
		'export_field'		=> 	'retail_price',
		'product_field'		=>	'retail_price',
		'import_header_val'	=>	'Retail Price ',
		'export_header_val'	=> 	'Retail Price '
	),

	'price' =>  array(
		'import_field'		=> 	'price',
		'export_field'		=> 	'price',
		'product_field'		=>	'price',
		'import_header_val'	=>	'Price',
		'export_header_val'	=> 	'Price'
	),

	'sale_price' =>  array(
		'import_field'		=> 	'sale_price',
		'export_field'		=> 	'sale_price',
		'product_field'		=>	'sale_price',
		'import_header_val'	=>	'Sale Price',
		'export_header_val'	=> 	'Sale Price'
	),

	'sale_start_date' =>  array(
		'import_field'		=> 	'sale_start_date',
		'export_field'		=> 	'sale_start_date',
		'product_field'		=>	'sale_start_date',
		'import_header_val'	=>	'Sale Start Date',
		'export_header_val'	=> 	'Sale Start Date'
	),

	'sale_end_date' =>  array(
		'import_field'		=> 	'sale_end_date',
		'export_field'		=> 	'sale_end_date',
		'product_field'		=>	'sale_end_date',
		'import_header_val'	=>	'Sale End Date',
		'export_header_val'	=> 	'Sale End Date'
	),

	'shipping' =>  array(
		'import_field'		=> 	'shipping',
		'export_field'		=> 	'shipping',
		'product_field'		=>	'shipping',
		'import_header_val'	=>	'Shipping',
		'export_header_val'	=> 	'Shipping'
	),

	'shipping_days' =>  array(
		'import_field'		=> 	'shipping_days',
		'export_field'		=> 	'shipping_days',
		'product_field'		=>	'shipping_days',
		'import_header_val'	=>	'Shipping Days',
		'export_header_val'	=> 	'Shipping Days'
	),

	'is_sale' =>  array(
		'import_field'		=> 	'is_sale',
		'export_field'		=> 	'is_sale',
		'product_field'		=>	'is_sale',
		'import_header_val'	=>	'Is_Sale',
		'export_header_val'	=> 	'Is_Sale'
	),
	'related_sku' =>  array(
		'import_field'	=> 	'related_sku',
		'export_field'		=> 	'related_sku',
		'product_field'		=>	'related_sku',
		'import_header_val'	=>	'Related SKU',
		'export_header_val'	=> 	'Related SKU'
	),
	'product_url' =>  array(
		'import_field'		=> 	'product_url',
		'export_field'		=> 	'product_url',
		'product_field'		=>	'product_url',
		'import_header_val'	=>	'product_url',
		'export_header_val'	=> 	'product_url'
	)

);
