<?php
session_start();
include_once("config_setting.php");
set_time_limit(0);

if (isset($_POST['upload'])) {
    $xlsxFileName = $_FILES['csv_file']['name'];
    ## Check if a CSV file was uploaded
    if ($_FILES['csv_file']['error'] == 0 && $_FILES['csv_file']['type'] == 'text/csv') {
        $csvFile = $_FILES['csv_file']['tmp_name'];

        ## Open and read the CSV file
        $handle = fopen($csvFile, "r");

        $firstRow = true; ## A flag to indicate if it's the first row
        $directoryPath = 'imagesWithSKUName';
        $tablename = "hba_image_rename_status";
        $obj->sql_query("TRUNCATE TABLE $tablename");

        if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true); // The third parameter "true" creates parent directories if they don't exist
            }

        if ($handle !== false) {
            $_SESSION['messages'] = array();
            ## Iterate through the CSV rows
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                 if ($firstRow) {
                        $firstRow = false; ## Skip the first row
                        continue;
                    }
                ## Assuming the first column contains SKU and the second column contains image file name
                $sku = $data[0];
                $imageName = $data[1];
                if (strpos($imageName, '%20') !== false) {
                        $imageName = str_replace('%20', ' ', $imageName);
                    }

                ## Check if the image file exists in the "images" directory
                $imagePath = 'large/' . $imageName;

                $insert_imp_arr['product_sku'] = $sku;
                $insert_imp_arr['image_name'] = $imageName;
                $insert_imp_arr['image_new_name'] = NULL;
                ## Use pathinfo to get the file extension
                $file_info = pathinfo($imagePath);
                    if(isset($file_info['extension'])){
                        if (file_exists($imagePath)) {
                            ## Rename the image file with SKU
                            $newImagePath = $directoryPath.'/'.$sku.'.'.$file_info['extension']; ## Change the extension if needed
                            ## rename($imagePath, $newImagePath);
                            copy($imagePath, $newImagePath);
                            $insert_imp_arr['image_new_name'] = $sku.'.'.$file_info['extension'];
                            $insert_imp_arr['flag'] = 1;

                            $message = '<p style="font-size: 18px; color: #336699; font-weight: bold; text-align: center;">' 
                                        .$imageName. ' to '. $sku. '.'.$file_info['extension'].'</p>';

                            // echo "Renamed: $imageName to $sku.". $file_info['extension'] . "<br>";
                    } else {
                         $insert_imp_arr['flag'] = 2;
                         $message ='<p style="font-size: 18px; color: #FF0000; font-weight: bold; text-align: center;">  Image not found : '. $imageName.'. </p>';
                    }

                $result = $obj->insert($tablename, $insert_imp_arr);
                array_push($_SESSION['messages'], $message);
                }
            }

            fclose($handle);
        } else {
            $_SESSION['message'] = '<p style="font-size: 18px; color: #FF0000; font-weight: bold; text-align: center;">  Error opening the CSV file.</p>';
            /*echo "Error opening the CSV file.";*/
        }
    } else {
        $_SESSION['message'] = '<p style="font-size: 18px; color: #FF0000; font-weight: bold; text-align: center;">  Please upload a valid CSV file.</p>';
        /*echo "Please upload a valid CSV file.";*/
    }

    $redirect_url = 'index.php';
    header('Location: ' . $redirect_url);
    exit;
}
?>