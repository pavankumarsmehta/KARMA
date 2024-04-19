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
$gen_required_fields = array('sku');


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

	'is_sale' =>  array(
		'import_field'		=> 	'is_sale',
		'export_field'		=> 	'is_sale',
		'product_field'		=>	'is_sale',
		'import_header_val'	=>	'Is Sale',
		'export_header_val'	=> 	'Is Sale'
	),

	'badge' =>  array(
		'import_field'		=> 	'badge',
		'export_field'		=> 	'badge',
		'product_field'		=>	'badge',
		'import_header_val'	=>	'Badge',
		'export_header_val'	=> 	'Badge'
	)
);
