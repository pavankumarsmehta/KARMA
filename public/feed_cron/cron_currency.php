<?php 
include_once("config_setting.php");
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once($physical_path."classes/GlobalClassConstruct.cls.php");
include_once($physical_path."classes/general.cls.php");

/*
 * Code added on 09-09-2022 :: Start
 * The following code will update the currency rate.
 * */
try
{
   
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.apilayer.com/fixer/fluctuation?base=USD",
		CURLOPT_HTTPHEADER => array(
		"Content-Type: text/plain",
		"apikey: n5Hx7o99wuPsW1TOPa7WJZGzZw3XWSoO"
		),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 100,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET"
	));

    $response = curl_exec($curl);
	
    curl_close($curl);
    
    $currencyRes = json_decode($response);
	//echo "<pre>"; print_r($currencyRes); exit;
    if (!empty($currencyRes) && $currencyRes->success == true)
    {
        foreach ($currencyRes->rates as $key => $rates)
        {
            $currencySelectSql = "SELECT * FROM `hba_currencies` WHERE `code` = '".$key."' ORDER BY `currencies_id` DESC LIMIT 1";
            $currencyResultRes = $obj->select($currencySelectSql);

            if (!empty($currencyResultRes))
            {
                $cRateValue = floatval($rates->start_rate);
                $cRateValue = round($cRateValue,4);
                $currentDate = date("Y-m-d H:i:s", time());
                $currencyUpdateSql = "UPDATE `hba_currencies` SET `value` = '".$cRateValue."', `last_updated` = '".$currentDate."' WHERE `currencies_id` = '".$currencyResultRes[0]['currencies_id']."' AND `code` = '".$currencyResultRes[0]['code']."'";
                $cUResultRes = $obj->sql_query($currencyUpdateSql);
            }
        }
    }
}
catch (Exception $exception)
{
    echo 'Something went wrong!';
    exit;
}

echo $headers  = "From: HBAStore Currency Cron <gequaldev@gmail.com>\n"; 
echo $headers .= "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";
echo $message  = "<BR><strong><U>HBAStore Currency Data</U></strong>";
echo $message .= "<BR><BR>Currency Cron Job executed successfully";
@mail("HBAStore Cron Job executed",$message,'gequaldev@gmail.com','sachin.qualdev@gmail.com');
################# send the mail regarding the cron executed############
sleep(1);
//$obj->close();
echo 'Success';
exit;
?>