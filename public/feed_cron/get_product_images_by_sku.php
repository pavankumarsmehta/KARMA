<?php
ob_start();
set_time_limit(0);
ini_set("display_errors",1);
error_reporting(1);
include_once("config_setting.php");
set_time_limit(0);

require_once('image.php');
$physical_path = '/home/hbasales/public_html/hbastore/public/images/';
// Function to resize and save an image in different folders
function resizeAndSaveImage($sourcePath, $imageName) {
   global  $physical_path;
	
  
    $largeImagePath = $physical_path .'productimages/large/'.$imageName;
    $mediumImagePath = $physical_path .'productimages/medium/'.$imageName;
	$smallImagePath = $physical_path .'productimages/small/'.$imageName;
	$thumbImagePath = $physical_path .'productimages/thumb/'.$imageName;

	//resizeImage($sourcePath, $zoomImagePath, 1200, 1200);
	resizeImage($sourcePath, $largeImagePath, 900, 900);
    resizeImage($sourcePath, $mediumImagePath, 600, 600);
    resizeImage($sourcePath, $smallImagePath, 300, 300);
    resizeImage($sourcePath, $thumbImagePath, 50, 50);

	return true;
   
}
// Function to resize and save an image
function resizeImage($sourcePath, $targetPath, $width, $height) {

    
    list($originalWidth, $originalHeight) = getimagesize($sourcePath);
	
    $aspectRatio = $originalWidth / $originalHeight;

    if ($width / $height > $aspectRatio) {
        $width = $height * $aspectRatio;
    } else {
        $height = $width / $aspectRatio;
    }
    $resizedImage = imagecreatetruecolor($width, $height);

    $sourceImage = imagecreatefromjpeg($sourcePath);

    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    imagejpeg($resizedImage, $targetPath);
    return true;
    
    // Free up memory
    // imagedestroy($sourceImage);
    // imagedestroy($resizedImage);
}

$WIDTH = 900;
$HEIGHT = 900;

$SOURCE = $physical_path.'productimages/original/';
$DEST = $physical_path.'productimages/convertimages/';
$FAILDEST = $physical_path.'productimages/failconvertimage/';
$zoomImagePath = $physical_path .'productimages/zoom/';

$image_array = glob($SOURCE."*.*");
$rec_counter = 0;
$fail_rec_counter = 0;
foreach ($image_array as $file_name) 
{  

	$info = pathinfo($file_name);
	
	$extension = strtolower($info['extension']);
	$new_file_name = basename($info['basename']);
	$old_file_name = basename($info['basename']);
	$sku = str_replace(".$extension","",$new_file_name);
	$sku_split = explode("_",$sku);

	if(count($sku_split) > 1){
		$sku = $sku_split[0];
	}else{
		$sku = $sku_split[0];
	}
	if($extension == 'jpg' or $extension == 'jpeg') 
	{
		if(!file_exists($DEST.$new_file_name))
		{
			/* (*$obj = new Image($file_name);
			$obj->resize($WIDTH,$HEIGHT);
			//echo $DEST.$new_file_name; exit;
			$obj->save($DEST.$new_file_name,1) */ 


			$db_query = "SELECT sku,extra_images FROM hba_products WHERE sku = '".$sku."'";
			$db_recs = $obj->select($db_query); 
		
			if(count($db_recs) > 0)
			{
				$extra_images = $db_recs[0]['extra_images'];
				if(count($sku_split) > 1){
				
					if(!empty($extra_images)){
						
						$split_extra_image = explode("#",$extra_images);
							$extra_image_count = 0 ;
							if(count($split_extra_image) > 5){
								copy($file_name, "$FAILDEST/$old_file_name");	
								unlink($file_name);
								$fail_rec_counter++;
								continue;
							}
							$extra_image_count = count($split_extra_image)+1;
							$new_file_name = $sku.'_'.$extra_image_count.'.'.$extension;
							$extra_images.= "#".$new_file_name;
					}else{
						$extra_images = '';
						$new_file_name = $sku.'_1.'.$extension;
						$extra_images.= $new_file_name;
					}
				}

				if(resizeAndSaveImage($file_name, $new_file_name)){
					if(count($sku_split) > 1){
						$Updateimages = array(
							'extra_images' => $extra_images,
							'product_img_update_track' => '1'
						);
					}else{
						$Updateimages = array(
							'image_name' => $new_file_name,
							'product_img_update_track' => '1'
						);
					}		
					$result = $obj->update('hba_products', $Updateimages, "sku ='".$sku."'") ;

					copy($file_name, "$zoomImagePath/$new_file_name");
					copy($file_name, "$DEST/$old_file_name");
					unlink($file_name);
					$rec_counter++;
			 	}
			}else{
				copy($file_name, "$FAILDEST/$old_file_name");	
				unlink($file_name);
				$fail_rec_counter++;
			}

		}else{
			copy($file_name, "$FAILDEST/$old_file_name");
			unlink($file_name);
			$fail_rec_counter++;
		}
	} 
	else 
	{
		copy($file_name, "$FAILDEST/$old_file_name");
		unlink($file_name);
		$fail_rec_counter++;
	}
}
echo "Total Image Updated : $rec_counter and Total Image Fail Updated : $fail_rec_counter";
exit;