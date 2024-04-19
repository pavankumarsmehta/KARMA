<?php
set_time_limit(0);
ini_set("memory_limit", -1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
include_once("config_setting.php");
set_time_limit(0);


// include_once("../../config_setting.php");
// require_once($physical_path."classes/GlobalClassConstruct.cls.php");
// include_once($physical_path."classes/general.cls.php");

try
{
    $physical_path = '/home/hbasales/public_html/hbastore/public/images/';
    $zoomImagePath = $physical_path .'productimages/zoom/';
    $startLimit = (isset($_REQUEST['startLimit'])) ? $_REQUEST['startLimit'] : 0;
    $totalRecords = (isset($_REQUEST['totalRecords'])) ? $_REQUEST['totalRecords'] : '';

    if ($startLimit == 0)
    {
        $tblProductsTotalRecordsSql = "SELECT count(*) AS tot FROM ".TABLE_PREFIX."products  WHERE img_main_executed_var!= '1' ";
        $tblProductsTotalRecordsRes = $obj->select($tblProductsTotalRecordsSql);
        $totalRecords = $tblProductsTotalRecordsRes[0]["tot"];
        
    }
 
    if ($startLimit <= $totalRecords)
    {
        $endLimit = 40;

        echo "<div align='center' style='color:#FF0000;'><h1>Please wait while we are processing.</h1></div>";
        echo "<div align='center' style='color:#FF0000;'><h1>Processing records from : ".$startLimit." To ".($startLimit+$endLimit)."</h1></div>";

        $tblProductsSql = "SELECT p.product_id,p.sku,p.image_name,p.product_url FROM ".TABLE_PREFIX."products AS p  WHERE   p.img_main_executed_var != '1'  order by p.product_id asc limit $startLimit, $endLimit";
        $tblProductsRes = $obj->select($tblProductsSql);
        $tblProductsTotalRecordsCount = count($tblProductsRes);

        for ($i=0; $i<=$tblProductsTotalRecordsCount; $i++)
        {
            $mainImagename = $tblProductsRes[$i]['image_name'];
            $mainImageUrl = $tblProductsRes[$i]['image_name'];
            $mainImageUrl = $zoomImagePath.$mainImageUrl;

            if (file_exists($mainImageUrl) && !empty($mainImagename))
            {

      
                $tblProductsUpdateAIFSql = "UPDATE ".TABLE_PREFIX."products SET img_main_executed_var = '1',img_main_missing_var = '0' WHERE product_id = ".$tblProductsRes[$i]['product_id'];
                $tblProductsUpdateAIFRes = $obj->sql_query($tblProductsUpdateAIFSql);
            }
            else
            {
                $tblProductsUpdateMIFSql = "UPDATE ".TABLE_PREFIX."products SET img_main_missing_var = '1', img_main_executed_var = '0' WHERE product_id = ".$tblProductsRes[$i]['product_id'];
                $tblProductsUpdateMIFRes = $obj->sql_query($tblProductsUpdateMIFSql);
            }
        }
        //exit;
        $startLimit = $startLimit + 40;
        ?>
        <html>
        <body>
        <form method="post" action="<?php echo $Site_URL; ?>feed_cron/chk_main_image_large.php" name="checkImageBatchForm">
            <input type="hidden" name="startLimit" value="<?php echo $startLimit; ?>" />
            <input type="hidden" name="totalRecords" value="<?php echo $totalRecords; ?>" />
        </form>
        <SCRIPT language="javascript">
            document.checkImageBatchForm.submit();
        </SCRIPT>
        </body>
        </html>
        <?php
    }
    else
    {
        echo "<div align='center' style='color:#FF0000;'><h1>All images checked successfully!</h1></div>";
        exit;
    }
}
catch (Exception $exception)
{
    echo "<div align='center' style='color:#FF0000;'><h1>Something went wrong!</h1></div>";
    exit;
}
?>