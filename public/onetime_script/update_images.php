<?php
include_once("config_setting.php");
set_time_limit(0);

$fileselect = 'SampleFile.csv';
$tablename = "hba_products";

if(is_file($fileselect) and !empty($fileselect)) 	
{
	$file_path = $fileselect;
	if(file_exists($file_path))	
	{	
		$handle = fopen($file_path, "rb");
		$rec_counter = 0;
					
		$getfilesizevar = filesize($file_path);

		$arrayCsv = array();
		while($data = fgetcsv($handle, $getfilesizevar, ",")) 
		{ 							
			 array_push($arrayCsv,$data);				 				
		}
		
		
		//for($k=0;$k<count($arrayCsv);$k++)
		for($k=1;$k<count($arrayCsv);$k++)
		{
			//echo $arrayCsv[$k][1]; exit;
			if($k > count($arrayCsv))
				break;
			
			$sku = ltrim($arrayCsv[$k][0],"H");
			$db_query = "SELECT sku FROM hba_products WHERE sku = '".$sku."'";
			$db_recs = $obj->select($db_query); 
			
			$Updateimages = array(
					'image_name' => $arrayCsv[$k][1],
					'extra_images' => $arrayCsv[$k][2]
			);
			
			//echo "<pre>"; print_r($Updateimages); exit;
			
			if(count($db_recs) > 0)
			{
				$result = $obj->update('hba_products', $Updateimages, "sku ='".$sku."'") ;
				$rec_counter++;
			}
			
		}	
	} 
}

$rec_counter." Records are update." ;

$email_headers = "From: gequaldev@gmail.com\n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";	
$email_subject =  "Image data updated successfully.";
$email_body = "There are total " . $rec_counter . " records updated.<br>";
$xyz = @mail("sachin.qualdev@gmail.com", $email_subject, $email_body, $email_headers);
?>
