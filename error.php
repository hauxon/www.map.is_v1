<?php
/* 
 loggar villur
 */
// Fyrir íslenzku stafina
header('Content-type: text/plain; charset=iso-8859-1');

$error =  $_GET['error'];
$site = "not_set";
$site = ((isset($_GET["site"]))?querystring("site"):"not_set");

$theDB = "";
$theAction = "";
$errorstring = urldecode($error);


// Lykilorð sem er notað til að læsa/opna MD5 lyklinum
//$strMD5Hash = md5($errorstring ."MamiyaRZ67ProIIULD");
$strMD5Hash = md5("MamiyaRZ67ProIIULD");

$url = "http://geoserver.loftmyndir.is/kortasja/errors_map_is.php?error=" . urlencode($errorstring) . "&site=" . $site . "&page=not_set&md5=" . $strMD5Hash . "&DB=LM_postgis";

$fp = fopen($url, 'r');
$response = '';
while ($l = fread($fp, 1024)) $response .= $l;
fclose($fp);
echo $response;

function querystring($parameter)
{
    if($temp = $_GET[$parameter])
    {
        return $temp;
    }
    else
    {	
        debug( "Error, missing parameter ".$parameter.".");
        echo 3;
        exit;
    }	 
}
?>
