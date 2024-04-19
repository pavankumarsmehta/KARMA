<?php
include_once("config_setting.php");
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once($physical_path."classes/GlobalClassConstruct.cls.php");
include_once($physical_path."classes/general.cls.php");

if(!isset($generalobj))
{
    $generalobj = new General($obj,$smarty);
}

$sql = "SELECT * FROM ".TABLE_PREFIX."instagram_settings";
$res_total = $obj->select($sql);

// get access token
$instagram_access_token = "SELECT * FROM ".TABLE_PREFIX."instagram_access_token"; 
$result = $obj->select($instagram_access_token);

if(isset($result) && count($result) > 0)
{
	if($result[0]['instagram_access_token'] != ''){
		$new_token_update_date = date('Y-m-d', strtotime($result[0]['last_updated_date']. ' + 59 days'));	
		
		if($new_token_update_date <= date('Y-m-d')){
			$accessToken = $result[0]['instagram_access_token'];
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $accessToken);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
			$refreshedToken = json_decode($output, true)['access_token'];

			if($refreshedToken != ''){

				// update access token in instagram_access_token table
				$UpdateData = array(
					'instagram_access_token' => $refreshedToken,
					'last_updated_date' => date('Y-m-d')
				);
				$update = $obj->update('hba_instagram_access_token', $UpdateData, "instagram_access_token_id ='".$result[0]['instagram_access_token_id']."'");

				// update access token in instagram_settings table
				$UpdateInsatgramSettingData = array(
					'setting' => $refreshedToken
				);
				$UpdateInsatgramSetting = $obj->update('hba_instagram_settings', $UpdateInsatgramSettingData, "var_name = 'INSTAGRAM_ACCESS_TOKEN'");

				echo "Access token updated successfully";
			}else{
				echo "Something went wrong while updating access token";
			}
	
		}else{
			echo 'Access token is not expired yet. Expire date is: '.$new_token_update_date.' and current date is: '.date('Y-m-d').'';
		}
	}else{
		echo 'Access token field is empty in database';
	}
}else{
	echo "Access token not found";
}
?>