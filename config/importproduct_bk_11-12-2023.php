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
$gen_required_fields = array('sku', 'product_name');


/*
	This array contains all the fields which are valid in the csv
	If product csv contains the coulmn(s) which are not in the product sample csv then its shows the invalid csv format
*/

// return array(

// 	'sku' =>  array(
// 		'import_field'		=> 	'sku',
// 		'export_field'		=> 	'sku',
// 		'product_field'		=>	'sku',
// 		'import_header_val'	=>	'SKU',
// 		'export_header_val'	=> 	'SKU'
// 	),
// 	'product_group_code' =>  array(
// 		'import_field'		=> 	'product_group_code',
// 		'export_field'		=> 	'product_group_code',
// 		'product_field'		=>	'product_group_code',
// 		'import_header_val'	=>	'Product Group Code',
// 		'export_header_val'	=> 	'Product Group Code'
// 	),
// 	'parent_sku' =>  array(
// 		'import_field'	=> 	'parent_sku',
// 		'export_field'		=> 	'parent_sku',
// 		'product_field'		=>	'parent_sku',
// 		'import_header_val'	=>	'Parent_Sku',
// 		'export_header_val'	=> 	'Parent_Sku'
// 	),
// 	'related_sku' =>  array(
// 		'import_field'	=> 	'related_sku',
// 		'export_field'		=> 	'related_sku',
// 		'product_field'		=>	'related_sku',
// 		'import_header_val'	=>	'Related SKU',
// 		'export_header_val'	=> 	'Related SKU'
// 	),
// 	'color' =>  array(
// 		'import_field'	=> 	'color',
// 		'export_field'		=> 	'color',
// 		'product_field'		=>	'color',
// 		'import_header_val'	=>	'Color',
// 		'export_header_val'	=> 	'Color'
// 	),
// 	'upc' =>  array(
// 		'import_field'		=> 	'upc',
// 		'export_field'		=> 	'upc',
// 		'product_field'		=>	'upc',
// 		'import_header_val'	=>	'UPC',
// 		'export_header_val'	=> 	'UPC'
// 	),
// 	'product_name' 	=>  array(
// 		'import_field'		=> 	'product_name',
// 		'export_field'		=> 	'product_name',
// 		'product_field'		=>	'product_name',
// 		'import_header_val'	=>	'Product Name',
// 		'export_header_val'	=> 	'Product Name'
// 	),
// 	'short_description' =>  array(
// 		'import_field'		=> 	'short_description',
// 		'export_field'		=> 	'short_description',
// 		'product_field'		=>	'short_description',
// 		'import_header_val'	=>	'Short Description',
// 		'export_header_val'	=> 	'Short Description'
// 	),
// 	'product_description' =>  array(
// 		'import_field'		=> 	'product_description',
// 		'export_field'		=> 	'product_description',
// 		'product_field'		=>	'product_description',
// 		'import_header_val'	=>	'Product Description',
// 		'export_header_val'	=> 	'Product Description'
// 	),
// 	'category' 	=>  array(
// 		'import_field'		=> 	'category',
// 		'export_field'		=> 	'category',
// 		'product_field'		=>	'category',
// 		'import_header_val'	=>	'Category',
// 		'export_header_val'	=> 	'Category'
// 	),
// 	'general_information' 	=>  array(
// 		'import_field'		=> 	'general_information',
// 		'export_field'		=> 	'general_information',
// 		'product_field'		=>	'general_information',
// 		'import_header_val'	=>	'General Information',
// 		'export_header_val'	=> 	'General Information'
// 	),
// 	'gender' 	=>  array(
// 		'import_field'		=> 	'gender',
// 		'export_field'		=> 	'gender',
// 		'product_field'		=>	'gender',
// 		'import_header_val'	=>	'Gender',
// 		'export_header_val'	=> 	'Gender'
// 	),
// 	'manufacturer' 	=>  array(
// 		'import_field'		=> 	'manufacturer',
// 		'export_field'		=> 	'manufacturer',
// 		'product_field'		=>	'manufacturer',
// 		'import_header_val'	=>	'Manufacturer',
// 		'export_header_val'	=> 	'Manufacturer'
// 	),
// 	'brand' 	=>  array(
// 		'import_field'		=> 	'brand',
// 		'export_field'		=> 	'brand',
// 		'product_field'		=>	'brand',
// 		'import_header_val'	=>	'Brand',
// 		'export_header_val'	=> 	'Brand'
// 	),
// 	'product_type' =>  array(
// 		'import_field'	=> 	'product_type',
// 		'export_field'		=> 	'product_type',
// 		'product_field'		=>	'product_type',
// 		'import_header_val'	=>	'Product Type',
// 		'export_header_val'	=> 	'Product Type'
// 	),
// 	'skin_type' =>  array(
// 		'import_field'	=> 	'skin_type',
// 		'export_field'		=> 	'skin_type',
// 		'product_field'		=>	'skin_type',
// 		'import_header_val'	=>	'Skin Type',
// 		'export_header_val'	=> 	'Skin Type'
// 	),
// 	'retail_price' =>  array(
// 		'import_field'		=> 	'retail_price',
// 		'export_field'		=> 	'retail_price',
// 		'product_field'		=>	'retail_price',
// 		'import_header_val'	=>	'Retail Price ',
// 		'export_header_val'	=> 	'Retail Price '
// 	),

// 	'our_price' =>  array(
// 		'import_field'		=> 	'our_price',
// 		'export_field'		=> 	'our_price',
// 		'product_field'		=>	'our_price',
// 		'import_header_val'	=>	'Our Price',
// 		'export_header_val'	=> 	'Our Price'
// 	),

// 	'sale_price' =>  array(
// 		'import_field'		=> 	'sale_price',
// 		'export_field'		=> 	'sale_price',
// 		'product_field'		=>	'sale_price',
// 		'import_header_val'	=>	'Sale Price',
// 		'export_header_val'	=> 	'Sale Price'
// 	),

// 	'on_sale' =>  array(
// 		'import_field'		=> 	'on_sale',
// 		'export_field'		=> 	'on_sale',
// 		'product_field'		=>	'on_sale',
// 		'import_header_val'	=>	'On Sale',
// 		'export_header_val'	=> 	'On Sale'
// 	),

// 	'wholesale_price' =>  array(
// 		'import_field'		=> 	'wholesale_price',
// 		'export_field'		=> 	'wholesale_price',
// 		'product_field'		=>	'wholesale_price',
// 		'import_header_val'	=>	'Wholesale Price',
// 		'export_header_val'	=> 	'Wholesale Price'
// 	),

//    'wholesale_markup_percent' =>  array(
// 		'import_field'		=> 	'wholesale_markup_percent',
// 		'export_field'		=> 	'wholesale_markup_percent',
// 		'product_field'		=>	'wholesale_markup_percent',
// 		'import_header_val'	=>	'Wholesale Markup Percent',
// 		'export_header_val'	=> 	'Wholesale Markup Percent'
// 	),

//    'our_cost' =>  array(
// 		'import_field'		=> 	'our_cost',
// 		'export_field'		=> 	'our_cost',
// 		'product_field'		=>	'our_cost',
// 		'import_header_val'	=>	'Our Cost',
// 		'export_header_val'	=> 	'Our Cost'
// 	),

//    	'video_url' =>  array(
// 		'import_field'		=> 	'video_url',
// 		'export_field'		=> 	'video_url',
// 		'product_field'		=>	'video_url',
// 		'import_header_val'	=>	'Video URL',
// 		'export_header_val'	=> 	'Video URL'
// 	),
// 	'current_stock' =>  array(
// 		'import_field'		=> 	'current_stock',
// 		'export_field'		=> 	'current_stock',
// 		'product_field'		=>	'current_stock',
// 		'import_header_val'	=>	'Current Stock',
// 		'export_header_val'	=> 	'Current Stock'
// 	),
// 	'meta_title' =>  array(
// 		'import_field'		=> 	'meta_title',
// 		'export_field'		=> 	'meta_title',
// 		'product_field'		=>	'meta_title',
// 		'import_header_val'	=>	'Meta Title',
// 		'export_header_val'	=> 	'Meta Title'
// 	),
// 	'meta_keyword' =>  array(
// 		'import_field'		=> 	'meta_keyword',
// 		'export_field'		=> 	'meta_keyword',
// 		'product_field'		=>	'meta_keyword',
// 		'import_header_val'	=>	'Meta Keyword',
// 		'export_header_val'	=> 	'Meta Keyword'
// 	),
// 	'meta_description' =>  array(
// 		'import_field'		=> 	'meta_description',
// 		'export_field'		=> 	'meta_description',
// 		'product_field'		=>	'meta_description',
// 		'import_header_val'	=>	'Meta Description',
// 		'export_header_val'	=> 	'Meta Description'
// 	),
// 	'clearance' =>  array(
// 		'import_field'		=> 	'clearance',
// 		'export_field'		=> 	'clearance',
// 		'product_field'		=>	'clearance',
// 		'import_header_val'	=>	'Clearance',
// 		'export_header_val'	=> 	'Clearance'
// 	),
// 	'best_seller' =>  array(
// 		'import_field'		=> 	'best_seller',
// 		'export_field'		=> 	'best_seller',
// 		'product_field'		=>	'best_seller',
// 		'import_header_val'	=>	'Best Seller',
// 		'export_header_val'	=> 	'Best Seller'
// 	),
// 	'new_arrival' =>  array(
// 		'import_field'		=> 	'new_arrival',
// 		'export_field'		=> 	'new_arrival',
// 		'product_field'		=>	'new_arrival',
// 		'import_header_val'	=>	'New Arrival',
// 		'export_header_val'	=> 	'New Arrival'
// 	),
// 	// 'featured' =>  array(
// 	// 	'import_field'		=> 	'featured',
// 	// 	'export_field'		=> 	'featured',
// 	// 	'product_field'		=>	'featured',
// 	// 	'import_header_val'	=>	'Featured',
// 	// 	'export_header_val'	=> 	'Featured'
// 	// ),
// 	'display_rank' =>  array(
// 		'import_field'		=> 	'display_rank',
// 		'export_field'		=> 	'display_rank',
// 		'product_field'		=>	'display_rank',
// 		'import_header_val'	=>	'Display Rank',
// 		'export_header_val'	=> 	'Display Rank'
// 	),
// 	'is_atomizer' =>  array(
// 		'import_field'		=> 	'is_atomizer',
// 		'export_field'		=> 	'is_atomizer',
// 		'product_field'		=>	'is_atomizer',
// 		'import_header_val'	=>	'Is Atomizer',
// 		'export_header_val'	=> 	'Is Atomizer'
// 	),
// 	'status' =>  array(
// 		'import_field'		=> 	'status',
// 		'export_field'		=> 	'status',
// 		'product_field'		=>	'status',
// 		'import_header_val'	=>	'Status',
// 		'export_header_val'	=> 	'Status'
// 	),
// 	'product_availability' =>  array(
// 		'import_field'		=> 	'product_availability',
// 		'export_field'		=> 	'product_availability',
// 		'product_field'		=>	'product_availability',
// 		'import_header_val'	=>	'Product Availability',
// 		'export_header_val'	=> 	'Product Availability'
// 	),
// 	'ingredients' =>  array(
// 		'import_field'		=> 	'ingredients',
// 		'export_field'		=> 	'ingredients',
// 		'product_field'		=>	'ingredients',
// 		'import_header_val'	=>	'Ingredients',
// 		'export_header_val'	=> 	'Ingredients'
// 	),
// 	'ingredients_pdf' =>  array(
// 		'import_field'		=> 	'ingredients_pdf',
// 		'export_field'		=> 	'ingredients_pdf',
// 		'product_field'		=>	'ingredients_pdf',
// 		'import_header_val'	=>	'Ingredients PDF',
// 		'export_header_val'	=> 	'Ingredients PDF'
// 	),
// 	'uses' =>  array(
// 		'import_field'		=> 	'uses',
// 		'export_field'		=> 	'uses',
// 		'product_field'		=>	'uses',
// 		'import_header_val'	=>	'Uses',
// 		'export_header_val'	=> 	'Uses'
// 	),
// 	'key_features' =>  array(
// 		'import_field'		=> 	'key_features',
// 		'export_field'		=> 	'key_features',
// 		'product_field'		=>	'key_features',
// 		'import_header_val'	=>	'Key Features',
// 		'export_header_val'	=> 	'Key Features'
// 	),
// 	'size' =>  array(
// 		'import_field'		=> 	'size',
// 		'export_field'		=> 	'size',
// 		'product_field'		=>	'size',
// 		'import_header_val'	=>	'Size',
// 		'export_header_val'	=> 	'Size'
// 	),
// 	'pack_size' =>  array(
// 		'import_field'		=> 	'pack_size',
// 		'export_field'		=> 	'pack_size',
// 		'product_field'		=>	'pack_size',
// 		'import_header_val'	=>	'Pack Size',
// 		'export_header_val'	=> 	'Pack Size'
// 	),
// 	'flavour' =>  array(
// 		'import_field'		=> 	'flavour',
// 		'export_field'		=> 	'flavour',
// 		'product_field'		=>	'flavour',
// 		'import_header_val'	=>	'Flavour',
// 		'export_header_val'	=> 	'Flavour'
// 	),
// 	'metric_size' =>  array(
// 		'import_field'		=> 	'metric_size',
// 		'export_field'		=> 	'metric_size',
// 		'product_field'		=>	'metric_size',
// 		'import_header_val'	=>	'Metric Size',
// 		'export_header_val'	=> 	'Metric Size'
// 	),
// 	'product_weight' =>  array(
// 		'import_field'		=> 	'product_weight',
// 		'export_field'		=> 	'product_weight',
// 		'product_field'		=>	'product_weight',
// 		'import_header_val'	=>	'Product_Weight',
// 		'export_header_val'	=> 	'Product_Weight'
// 	),
// 	'product_length' =>  array(
// 		'import_field'		=> 	'product_length',
// 		'export_field'		=> 	'product_length',
// 		'product_field'		=>	'product_length',
// 		'import_header_val'	=>	'Product_Length',
// 		'export_header_val'	=> 	'Product_Length'
// 	),
// 	'product_width' =>  array(
// 		'import_field'		=> 	'product_width',
// 		'export_field'		=> 	'product_width',
// 		'product_field'		=>	'product_width',
// 		'import_header_val'	=>	'Product_Width',
// 		'export_header_val'	=> 	'Product_Width'
// 	),
// 	'product_height' =>  array(
// 		'import_field'		=> 	'product_height',
// 		'export_field'		=> 	'product_height',
// 		'product_field'		=>	'product_height',
// 		'import_header_val'	=>	'Product_Height',
// 		'export_header_val'	=> 	'Product_Height'
// 	),
// 	'shipping_weight' =>  array(
// 		'import_field'		=> 	'shipping_weight',
// 		'export_field'		=> 	'shipping_weight',
// 		'product_field'		=>	'shipping_weight',
// 		'import_header_val'	=>	'Shipping_Weight',
// 		'export_header_val'	=> 	'Shipping_Weight'
// 	),
// 	'shipping_length' =>  array(
// 		'import_field'		=> 	'shipping_length',
// 		'export_field'		=> 	'shipping_length',
// 		'product_field'		=>	'shipping_length',
// 		'import_header_val'	=>	'Shipping_Length',
// 		'export_header_val'	=> 	'Shipping_Length'
// 	),
// 	'shipping_width' =>  array(
// 		'import_field'		=> 	'shipping_width',
// 		'export_field'		=> 	'shipping_width',
// 		'product_field'		=>	'shipping_width',
// 		'import_header_val'	=>	'Shipping_Width',
// 		'export_header_val'	=> 	'Shipping_Width'
// 	),
// 	'shipping_height' =>  array(
// 		'import_field'		=> 	'shipping_height',
// 		'export_field'		=> 	'shipping_height',
// 		'product_field'		=>	'shipping_height',
// 		'import_header_val'	=>	'Shipping_Height',
// 		'export_header_val'	=> 	'Shipping_Height'
// 	),
// 	'country_of_origin' =>  array(
// 		'import_field'		=> 	'country_of_origin',
// 		'export_field'		=> 	'country_of_origin',
// 		'product_field'		=>	'country_of_origin',
// 		'import_header_val'	=>	'Country_of_origin',
// 		'export_header_val'	=> 	'Country_of_origin'
// 	),
// 	'is_hazmat' =>  array(
// 		'import_field'		=> 	'is_hazmat',
// 		'export_field'		=> 	'is_hazmat',
// 		'product_field'		=>	'is_hazmat',
// 		'import_header_val'	=>	'Is_Hazmat',
// 		'export_header_val'	=> 	'Is_Hazmat'
// 	),
// 	'is_multipack' =>  array(
// 		'import_field'		=> 	'is_multipack',
// 		'export_field'		=> 	'is_multipack',
// 		'product_field'		=>	'is_multipack',
// 		'import_header_val'	=>	'Is_Multipack',
// 		'export_header_val'	=> 	'Is_Multipack'
// 	),
// 	'is_set' =>  array(
// 		'import_field'		=> 	'is_set',
// 		'export_field'		=> 	'is_set',
// 		'product_field'		=>	'is_set',
// 		'import_header_val'	=>	'Is_Set',
// 		'export_header_val'	=> 	'Is_Set'
// 	),
// 	'variant' =>  array(
// 		'import_field'		=> 	'variant',
// 		'export_field'		=> 	'variant',
// 		'product_field'		=>	'variant',
// 		'import_header_val'	=>	'Variant',
// 		'export_header_val'	=> 	'Variant'
// 	),
// 	'age_group' =>  array(
// 		'import_field'		=> 	'age_group',
// 		'export_field'		=> 	'age_group',
// 		'product_field'		=>	'age_group',
// 		'import_header_val'	=>	'Age_Group',
// 		'export_header_val'	=> 	'Age_Group'
// 	),
// 	'multi_pack_sku' =>  array(
// 		'import_field'		=> 	'multi_pack_sku',
// 		'export_field'		=> 	'multi_pack_sku',
// 		'product_field'		=>	'multi_pack_sku',
// 		'import_header_val'	=>	'Multi Pack Sku',
// 		'export_header_val'	=> 	'Multi Pack Sku'
// 	),
// 	'temp' =>  array(
// 		'import_field'		=> 	'temp',
// 		'export_field'		=> 	'temp',
// 		'product_field'		=>	'temp',
// 		'import_header_val'	=>	'Temp',
// 		'export_header_val'	=> 	'Temp'
// 	),
// 	'nioxin_system' =>  array(
// 		'import_field'		=> 	'nioxin_system',
// 		'export_field'		=> 	'nioxin_system',
// 		'product_field'		=>	'nioxin_system',
// 		'import_header_val'	=>	'NIOXIN_System',
// 		'export_header_val'	=> 	'NIOXIN_System'
// 	),
// 	'nioxin_size' =>  array(
// 		'import_field'		=> 	'nioxin_size',
// 		'export_field'		=> 	'nioxin_size',
// 		'product_field'		=>	'nioxin_size',
// 		'import_header_val'	=>	'NIOXIN_Size',
// 		'export_header_val'	=> 	'NIOXIN_Size'
// 	),
// 	'nioxin_type' =>  array(
// 		'import_field'		=> 	'nioxin_type',
// 		'export_field'		=> 	'nioxin_type',
// 		'product_field'		=>	'nioxin_type',
// 		'import_header_val'	=>	'NIOXIN_Type',
// 		'export_header_val'	=> 	'NIOXIN_Type'
// 	),
// 	'ship_international' =>  array(
// 		'import_field'		=> 	'ship_international',
// 		'export_field'		=> 	'ship_international',
// 		'product_field'		=>	'ship_international',
// 		'import_header_val'	=>	'Ship International',
// 		'export_header_val'	=> 	'Ship International'
// 	),
// 	'free_text_1' =>  array(
// 		'import_field'		=> 	'free_text_1',
// 		'export_field'		=> 	'free_text_1',
// 		'product_field'		=>	'free_text_1',
// 		'import_header_val'	=>	'Free Text_1',
// 		'export_header_val'	=> 	'Free Text_1'
// 	),
// 	'free_text_2' =>  array(
// 		'import_field'		=> 	'free_text_2',
// 		'export_field'		=> 	'free_text_2',
// 		'product_field'		=>	'free_text_2',
// 		'import_header_val'	=>	'Free Text_2',
// 		'export_header_val'	=> 	'Free Text_2'
// 	),
	
// );
return array(

	'sku' =>  array(
		'import_field'		=> 	'sku',
		'export_field'		=> 	'sku',
		'product_field'		=>	'sku',
		'import_header_val'	=>	'SKU',
		'export_header_val'	=> 	'SKU',
		'display_rank'		=> 1
	),
	'parent_sku' =>  array(
		'import_field'	=> 	'parent_sku',
		'export_field'		=> 	'parent_sku',
		'product_field'		=>	'parent_sku',
		'import_header_val'	=>	'Parent SKU',
		'export_header_val'	=> 	'Parent SKU',
		'display_rank'		=> 3
	),
	'product_group_code' =>  array(
		'import_field'		=> 	'product_group_code',
		'export_field'		=> 	'product_group_code',
		'product_field'		=>	'product_group_code',
		'import_header_val'	=>	'Product Group Code',
		'export_header_val'	=> 	'Product Group Code',
		'display_rank'		=> 2
	),
	
	'related_sku' =>  array(
		'import_field'	=> 	'related_sku',
		'export_field'		=> 	'related_sku',
		'product_field'		=>	'related_sku',
		'import_header_val'	=>	'Related SKU',
		'export_header_val'	=> 	'Related SKU',
		'display_rank'		=> 4
	),
	'color' =>  array(
		'import_field'	=> 	'color',
		'export_field'		=> 	'color',
		'product_field'		=>	'color',
		'import_header_val'	=>	'Color',
		'export_header_val'	=> 	'Color',
		'display_rank'		=> 5
	),
	'upc' =>  array(
		'import_field'		=> 	'upc',
		'export_field'		=> 	'upc',
		'product_field'		=>	'upc',
		'import_header_val'	=>	'UPC',
		'export_header_val'	=> 	'UPC',
		'display_rank'		=> 19
	),
	'product_name' 	=>  array(
		'import_field'		=> 	'product_name',
		'export_field'		=> 	'product_name',
		'product_field'		=>	'product_name',
		'import_header_val'	=>	'Product Name',
		'export_header_val'	=> 	'Product Name',
		'display_rank'		=> 6
	),
	'short_description' =>  array(
		'import_field'		=> 	'short_description',
		'export_field'		=> 	'short_description',
		'product_field'		=>	'short_description',
		'import_header_val'	=>	'Short Description',
		'export_header_val'	=> 	'Short Description',
		'display_rank'		=> 7
	),
	'product_description' =>  array(
		'import_field'		=> 	'product_description',
		'export_field'		=> 	'product_description',
		'product_field'		=>	'product_description',
		'import_header_val'	=>	'Product Description',
		'export_header_val'	=> 	'Product Description',
		'display_rank'		=> 12
	),
	'category' 	=>  array(
		'import_field'		=> 	'category',
		'export_field'		=> 	'category',
		'product_field'		=>	'category',
		'import_header_val'	=>	'Category',
		'export_header_val'	=> 	'Category',
		'display_rank'		=> 8
	),
	'general_information' 	=>  array(
		'import_field'		=> 	'general_information',
		'export_field'		=> 	'general_information',
		'product_field'		=>	'general_information',
		'import_header_val'	=>	'General Information',
		'export_header_val'	=> 	'General Information',
		'display_rank'		=> 9
	),
	'gender' 	=>  array(
		'import_field'		=> 	'gender',
		'export_field'		=> 	'gender',
		'product_field'		=>	'gender',
		'import_header_val'	=>	'Gender',
		'export_header_val'	=> 	'Gender',
		'display_rank'		=> 59
	),
	'manufacturer' 	=>  array(
		'import_field'		=> 	'manufacturer',
		'export_field'		=> 	'manufacturer',
		'product_field'		=>	'manufacturer',
		'import_header_val'	=>	'Manufacturer',
		'export_header_val'	=> 	'Manufacturer',
		'display_rank'		=> 10
	),
	'brand' 	=>  array(
		'import_field'		=> 	'brand',
		'export_field'		=> 	'brand',
		'product_field'		=>	'brand',
		'import_header_val'	=>	'Brand',
		'export_header_val'	=> 	'Brand',
		'display_rank'		=> 11
	),
	'product_type' =>  array(
		'import_field'	=> 	'product_type',
		'export_field'		=> 	'product_type',
		'product_field'		=>	'product_type',
		'import_header_val'	=>	'Product Type',
		'export_header_val'	=> 	'Product Type',
		'display_rank'		=> 58
	),
	'skin_type' =>  array(
		'import_field'	=> 	'skin_type',
		'export_field'		=> 	'skin_type',
		'product_field'		=>	'skin_type',
		'import_header_val'	=>	'Skin Type',
		'export_header_val'	=> 	'Skin Type',
		'display_rank'		=> 60
	),
	'retail_price' =>  array(
		'import_field'		=> 	'retail_price',
		'export_field'		=> 	'retail_price',
		'product_field'		=>	'retail_price',
		'import_header_val'	=>	'Retail Price ',
		'export_header_val'	=> 	'Retail Price ',
		'display_rank'		=> 22
	),

	'our_price' =>  array(
		'import_field'		=> 	'our_price',
		'export_field'		=> 	'our_price',
		'product_field'		=>	'our_price',
		'import_header_val'	=>	'Our Price',
		'export_header_val'	=> 	'Our Price',
		'display_rank'		=> 23
	),

	'sale_price' =>  array(
		'import_field'		=> 	'sale_price',
		'export_field'		=> 	'sale_price',
		'product_field'		=>	'sale_price',
		'import_header_val'	=>	'Sale Price',
		'export_header_val'	=> 	'Sale Price',
		'display_rank'		=> 24
	),

	'on_sale' =>  array(
		'import_field'		=> 	'on_sale',
		'export_field'		=> 	'on_sale',
		'product_field'		=>	'on_sale',
		'import_header_val'	=>	'On Sale',
		'export_header_val'	=> 	'On Sale',
		'display_rank'		=> 25
	),

	'wholesale_price' =>  array(
		'import_field'		=> 	'wholesale_price',
		'export_field'		=> 	'wholesale_price',
		'product_field'		=>	'wholesale_price',
		'import_header_val'	=>	'Wholesale Price',
		'export_header_val'	=> 	'Wholesale Price',
		'display_rank'		=> 26
	),

   'wholesale_markup_percent' =>  array(
		'import_field'		=> 	'wholesale_markup_percent',
		'export_field'		=> 	'wholesale_markup_percent',
		'product_field'		=>	'wholesale_markup_percent',
		'import_header_val'	=>	'Wholesale Markup Percent',
		'export_header_val'	=> 	'Wholesale Markup Percent',
		'display_rank'		=> 27
	),

   'our_cost' =>  array(
		'import_field'		=> 	'our_cost',
		'export_field'		=> 	'our_cost',
		'product_field'		=>	'our_cost',
		'import_header_val'	=>	'Our Cost',
		'export_header_val'	=> 	'Our Cost',
		'display_rank'		=> 28
	),

   	'video_url' =>  array(
		'import_field'		=> 	'video_url',
		'export_field'		=> 	'video_url',
		'product_field'		=>	'video_url',
		'import_header_val'	=>	'Video URL',
		'export_header_val'	=> 	'Video URL',
		'display_rank'		=> 14
	),
	'current_stock' =>  array(
		'import_field'		=> 	'current_stock',
		'export_field'		=> 	'current_stock',
		'product_field'		=>	'current_stock',
		'import_header_val'	=>	'Current Stock',
		'export_header_val'	=> 	'Current Stock',
		'display_rank'		=> 29
	),
	'meta_title' =>  array(
		'import_field'		=> 	'meta_title',
		'export_field'		=> 	'meta_title',
		'product_field'		=>	'meta_title',
		'import_header_val'	=>	'Meta Title',
		'export_header_val'	=> 	'Meta Title',
		'display_rank'		=> 16
	),
	'meta_keyword' =>  array(
		'import_field'		=> 	'meta_keyword',
		'export_field'		=> 	'meta_keyword',
		'product_field'		=>	'meta_keyword',
		'import_header_val'	=>	'Meta Keyword',
		'export_header_val'	=> 	'Meta Keyword',
		'display_rank'		=> 17
	),
	'meta_description' =>  array(
		'import_field'		=> 	'meta_description',
		'export_field'		=> 	'meta_description',
		'product_field'		=>	'meta_description',
		'import_header_val'	=>	'Meta Description',
		'export_header_val'	=> 	'Meta Description',
		'display_rank'		=> 18
	),
	'clearance' =>  array(
		'import_field'		=> 	'clearance',
		'export_field'		=> 	'clearance',
		'product_field'		=>	'clearance',
		'import_header_val'	=>	'Clearance',
		'export_header_val'	=> 	'Clearance',
		'display_rank'		=> 30
	),
	'best_seller' =>  array(
		'import_field'		=> 	'best_seller',
		'export_field'		=> 	'best_seller',
		'product_field'		=>	'best_seller',
		'import_header_val'	=>	'Best Seller',
		'export_header_val'	=> 	'Best Seller',
		'display_rank'		=> 31
	),
	'new_arrival' =>  array(
		'import_field'		=> 	'new_arrival',
		'export_field'		=> 	'new_arrival',
		'product_field'		=>	'new_arrival',
		'import_header_val'	=>	'New Arrival',
		'export_header_val'	=> 	'New Arrival',
		'display_rank'		=> 32
	),
	'featured' =>  array(
		'import_field'		=> 	'featured',
		'export_field'		=> 	'featured',
		'product_field'		=>	'featured',
		'import_header_val'	=>	'Featured',
		'export_header_val'	=> 	'Featured',
		'display_rank'		=> 33
	),
	'display_rank' =>  array(
		'import_field'		=> 	'display_rank',
		'export_field'		=> 	'display_rank',
		'product_field'		=>	'display_rank',
		'import_header_val'	=>	'Display Rank',
		'export_header_val'	=> 	'Display Rank',
		'display_rank'		=> 15
	),
	'is_atomizer' =>  array(
		'import_field'		=> 	'is_atomizer',
		'export_field'		=> 	'is_atomizer',
		'product_field'		=>	'is_atomizer',
		'import_header_val'	=>	'Is Atomizer',
		'export_header_val'	=> 	'Is Atomizer',
		'display_rank'		=> 34
	),
	'status' =>  array(
		'import_field'		=> 	'status',
		'export_field'		=> 	'status',
		'product_field'		=>	'status',
		'import_header_val'	=>	'Status',
		'export_header_val'	=> 	'Status',
		'display_rank'		=> 21
	),
	'product_availability' =>  array(
		'import_field'		=> 	'product_availability',
		'export_field'		=> 	'product_availability',
		'product_field'		=>	'product_availability',
		'import_header_val'	=>	'Product Availability',
		'export_header_val'	=> 	'Product Availability',
		'display_rank'		=> 35
	),
	'ingredients' =>  array(
		'import_field'		=> 	'ingredients',
		'export_field'		=> 	'ingredients',
		'product_field'		=>	'ingredients',
		'import_header_val'	=>	'Ingredients',
		'export_header_val'	=> 	'Ingredients',
		'display_rank'		=> 36
	),
	'ingredients_pdf' =>  array(
		'import_field'		=> 	'ingredients_pdf',
		'export_field'		=> 	'ingredients_pdf',
		'product_field'		=>	'ingredients_pdf',
		'import_header_val'	=>	'Ingredients PDF',
		'export_header_val'	=> 	'Ingredients PDF',
		'display_rank'		=> 37
	),
	'uses' =>  array(
		'import_field'		=> 	'uses',
		'export_field'		=> 	'uses',
		'product_field'		=>	'uses',
		'import_header_val'	=>	'Uses',
		'export_header_val'	=> 	'Uses',
		'display_rank'		=> 38
	),
	'key_features' =>  array(
		'import_field'		=> 	'key_features',
		'export_field'		=> 	'key_features',
		'product_field'		=>	'key_features',
		'import_header_val'	=>	'Key Features',
		'export_header_val'	=> 	'Key Features',
		'display_rank'		=> 39
	),
	'size' =>  array(
		'import_field'		=> 	'size',
		'export_field'		=> 	'size',
		'product_field'		=>	'size',
		'import_header_val'	=>	'Size',
		'export_header_val'	=> 	'Size',
		'display_rank'		=> 41
	),
	'pack_size' =>  array(
		'import_field'		=> 	'pack_size',
		'export_field'		=> 	'pack_size',
		'product_field'		=>	'pack_size',
		'import_header_val'	=>	'Pack Size',
		'export_header_val'	=> 	'Pack Size',
		'display_rank'		=> 42
	),
	'flavour' =>  array(
		'import_field'		=> 	'flavour',
		'export_field'		=> 	'flavour',
		'product_field'		=>	'flavour',
		'import_header_val'	=>	'Flavour',
		'export_header_val'	=> 	'Flavour',
		'display_rank'		=> 43
	),
	'metric_size' =>  array(
		'import_field'		=> 	'metric_size',
		'export_field'		=> 	'metric_size',
		'product_field'		=>	'metric_size',
		'import_header_val'	=>	'Metric Size',
		'export_header_val'	=> 	'Metric Size',
		'display_rank'		=> 40
	),
	'product_weight' =>  array(
		'import_field'		=> 	'product_weight',
		'export_field'		=> 	'product_weight',
		'product_field'		=>	'product_weight',
		'import_header_val'	=>	'Product Weight',
		'export_header_val'	=> 	'Product Weight',
		'display_rank'		=> 44
	),
	'product_length' =>  array(
		'import_field'		=> 	'product_length',
		'export_field'		=> 	'product_length',
		'product_field'		=>	'product_length',
		'import_header_val'	=>	'Product Length',
		'export_header_val'	=> 	'Product Length',
		'display_rank'		=> 45
	),
	'product_width' =>  array(
		'import_field'		=> 	'product_width',
		'export_field'		=> 	'product_width',
		'product_field'		=>	'product_width',
		'import_header_val'	=>	'Product Width',
		'export_header_val'	=> 	'Product Width',
		'display_rank'		=> 	46
	),
	'product_height' =>  array(
		'import_field'		=> 	'product_height',
		'export_field'		=> 	'product_height',
		'product_field'		=>	'product_height',
		'import_header_val'	=>	'Product Height',
		'export_header_val'	=> 	'Product Height',
		'display_rank'		=> 47
	),
	'shipping_weight' =>  array(
		'import_field'		=> 	'shipping_weight',
		'export_field'		=> 	'shipping_weight',
		'product_field'		=>	'shipping_weight',
		'import_header_val'	=>	'Shipping Weight',
		'export_header_val'	=> 	'Shipping Weight',
		'display_rank'		=> 48
	),
	'shipping_length' =>  array(
		'import_field'		=> 	'shipping_length',
		'export_field'		=> 	'shipping_length',
		'product_field'		=>	'shipping_length',
		'import_header_val'	=>	'Shipping Length',
		'export_header_val'	=> 	'Shipping Length',
		'display_rank'		=> 49
	),
	'shipping_width' =>  array(
		'import_field'		=> 	'shipping_width',
		'export_field'		=> 	'shipping_width',
		'product_field'		=>	'shipping_width',
		'import_header_val'	=>	'Shipping Width',
		'export_header_val'	=> 	'Shipping Width',
		'display_rank'		=> 50
	),
	'shipping_height' =>  array(
		'import_field'		=> 	'shipping_height',
		'export_field'		=> 	'shipping_height',
		'product_field'		=>	'shipping_height',
		'import_header_val'	=>	'Shipping Height',
		'export_header_val'	=> 	'Shipping Height',
		'display_rank'		=> 51
	),
	'country_of_origin' =>  array(
		'import_field'		=> 	'country_of_origin',
		'export_field'		=> 	'country_of_origin',
		'product_field'		=>	'country_of_origin',
		'import_header_val'	=>	'Country Of Origin',
		'export_header_val'	=> 	'Country Of Origin',
		'display_rank'		=> 52
	),
	'is_hazmat' =>  array(
		'import_field'		=> 	'is_hazmat',
		'export_field'		=> 	'is_hazmat',
		'product_field'		=>	'is_hazmat',
		'import_header_val'	=>	'Is Hazmat',
		'export_header_val'	=> 	'Is Hazmat',
		'display_rank'		=> 53
	),
	'is_multipack' =>  array(
		'import_field'		=> 	'is_multipack',
		'export_field'		=> 	'is_multipack',
		'product_field'		=>	'is_multipack',
		'import_header_val'	=>	'Is Multipack',
		'export_header_val'	=> 	'Is Multipack',
		'display_rank'		=> 54
	),
	'is_set' =>  array(
		'import_field'		=> 	'is_set',
		'export_field'		=> 	'is_set',
		'product_field'		=>	'is_set',
		'import_header_val'	=>	'Is Set',
		'export_header_val'	=> 	'Is Set',
		'display_rank'		=> 55
	),
	'variant' =>  array(
		'import_field'		=> 	'variant',
		'export_field'		=> 	'variant',
		'product_field'		=>	'variant',
		'import_header_val'	=>	'Variant',
		'export_header_val'	=> 	'Variant',
		'display_rank'		=> 56
	),
	'age_group' =>  array(
		'import_field'		=> 	'age_group',
		'export_field'		=> 	'age_group',
		'product_field'		=>	'age_group',
		'import_header_val'	=>	'Age Group',
		'export_header_val'	=> 	'Age Group',
		'display_rank'		=> 57
	),
	'multi_pack_sku' =>  array(
		'import_field'		=> 	'multi_pack_sku',
		'export_field'		=> 	'multi_pack_sku',
		'product_field'		=>	'multi_pack_sku',
		'import_header_val'	=>	'Multi Pack SKU',
		'export_header_val'	=> 	'Multi Pack SKU',
		'display_rank'		=> 61
	),
	'temp' =>  array(
		'import_field'		=> 	'temp',
		'export_field'		=> 	'temp',
		'product_field'		=>	'temp',
		'import_header_val'	=>	'Temp',
		'export_header_val'	=> 	'Temp',
		'display_rank'		=> 62
	),
	'nioxin_system' =>  array(
		'import_field'		=> 	'nioxin_system',
		'export_field'		=> 	'nioxin_system',
		'product_field'		=>	'nioxin_system',
		'import_header_val'	=>	'NIOXIN System',
		'export_header_val'	=> 	'NIOXIN System',
		'display_rank'		=> 63
	),
	'nioxin_size' =>  array(
		'import_field'		=> 	'nioxin_size',
		'export_field'		=> 	'nioxin_size',
		'product_field'		=>	'nioxin_size',
		'import_header_val'	=>	'NIOXIN Size',
		'export_header_val'	=> 	'NIOXIN Size',
		'display_rank'		=> 64
	),
	'nioxin_type' =>  array(
		'import_field'		=> 	'nioxin_type',
		'export_field'		=> 	'nioxin_type',
		'product_field'		=>	'nioxin_type',
		'import_header_val'	=>	'NIOXIN Type',
		'export_header_val'	=> 	'NIOXIN Type',
		'display_rank'		=> 65
	),
	'ship_international' =>  array(
		'import_field'		=> 	'ship_international',
		'export_field'		=> 	'ship_international',
		'product_field'		=>	'ship_international',
		'import_header_val'	=>	'Ship International',
		'export_header_val'	=> 	'Ship International',
		'display_rank'		=> 66
	),
	'free_text_1' =>  array(
		'import_field'		=> 	'free_text_1',
		'export_field'		=> 	'free_text_1',
		'product_field'		=>	'free_text_1',
		'import_header_val'	=>	'Free Text 1',
		'export_header_val'	=> 	'Free Text 1',
		'display_rank'		=> 67
	),
	'free_text_2' =>  array(
		'import_field'		=> 	'free_text_2',
		'export_field'		=> 	'free_text_2',
		'product_field'		=>	'free_text_2',
		'import_header_val'	=>	'Free Text 2',
		'export_header_val'	=> 	'Free Text 2',
		'display_rank'		=> 68
	),
	
);