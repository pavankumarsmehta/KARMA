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
		'export_header_val'	=> 	'SKU',
		'display_rank'		=> 1
	),
	'current_stock' =>  array(
		'import_field'		=> 	'current_stock',
		'export_field'		=> 	'current_stock',
		'product_field'		=>	'current_stock',
		'import_header_val'	=>	'Current Stock',
		'export_header_val'	=> 	'Current Stock',
		'display_rank'		=> 2
	),
	'retail_price' =>  array(
		'import_field'		=> 	'retail_price',
		'export_field'		=> 	'retail_price',
		'product_field'		=>	'retail_price',
		'import_header_val'	=>	'Retail Price ',
		'export_header_val'	=> 	'Retail Price ',
		'display_rank'		=> 3
	),

	'our_price' =>  array(
		'import_field'		=> 	'our_price',
		'export_field'		=> 	'our_price',
		'product_field'		=>	'our_price',
		'import_header_val'	=>	'Our Price',
		'export_header_val'	=> 	'Our Price',
		'display_rank'		=> 4
	),
	'sale_price' =>  array(
		'import_field'		=> 	'sale_price',
		'export_field'		=> 	'sale_price',
		'product_field'		=>	'sale_price',
		'import_header_val'	=>	'Sale Price',
		'export_header_val'	=> 	'Sale Price',
		'display_rank'		=> 5
	),
	
);