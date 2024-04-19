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
$gen_required_fields = array('zipcode','taxregionname','estimatedcombinedrate');


/*
	This array contains all the fields which are valid in the csv
	If product csv contains the coulmn(s) which are not in the product sample csv then its shows the invalid csv format
*/

return array ( 
		
    'state' 	=>  array (
        'import_field'		=> 	'State',
        'import_header_val'	=>	'State',
        'export_header_val'	=> 	'State' ),
        
    'zipcode' =>  array (
                'import_field'		=> 	'ZipCode',
                'import_header_val'	=>	'ZipCode',
                'export_header_val'	=> 	'ZipCode' ),								

    'taxregionname' =>  array (
                'import_field'		=> 	'TaxRegionName',
                'import_header_val'	=>	'TaxRegionName',
                'export_header_val'	=> 	'TaxRegionName'),	
                            
    'staterate' 		=>  array (
            'import_field'		=> 	'StateRate',
            'import_header_val'	=>	'StateRate',
            'export_header_val'	=> 	'StateRate'),
            
    'estimatedcombinedrate' 	=>  array (
        'import_field'		=> 	'EstimatedCombinedRate',
        'import_header_val'	=>	'EstimatedCombinedRate',
        'export_header_val'	=> 	'EstimatedCombinedRate'),

    'estimatedcountyrate' 			=>  array (
            'import_field'		=> 	'EstimatedCountyRate',
            'import_header_val'	=>	'EstimatedCountyRate',
            'export_header_val'	=> 	'EstimatedCountyRate'),							
            
    'estimatedcityrate' 	=>  array (
            'import_field'		=> 	'EstimatedCityRate',
            'import_header_val'	=>	'EstimatedCityRate',
            'export_header_val'	=> 	'EstimatedCityRate'),


    'estimatedspecialrate' =>  array (
        'import_field'		=> 	'EstimatedSpecialRate',
        'import_header_val'	=>	'EstimatedSpecialRate',
        'export_header_val'	=> 	'EstimatedSpecialRate'),
                        
    'risklevel' =>  array (
        'import_field'		=> 	'RiskLevel',
        'import_header_val'	=>	'RiskLevel',
        'export_header_val'	=> 	'RiskLevel')
);
