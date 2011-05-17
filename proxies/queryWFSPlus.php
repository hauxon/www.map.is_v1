<?php
/* 
 * Leitar proxy
 */

// Fyrir íslenzku stafina
//header('charset=UTF-8');
header('Content-type: application/json; charset=UTF-8');

//$url = "http://geoserver.loftmyndir.is/geoserver/ows?service=wfs&";	
$url = "http://geoserver.loftmyndir.is/geoserver/ows?";	

//$debug = $_GET['debug'];
//if ( $debug == "")
//{
//    echo("<br>". $url . $_SERVER['QUERY_STRING'] . "<br>");
//    exit;
//}

$QueryString = $_SERVER['QUERY_STRING'];
$pos_col = strpos($QueryString, "COL");
// The !== operator can also be used.  Using != would not work as expected
// because the position of 'COL' is 0. The statement (0 != false) evaluates 
// to false. If $pos !== false then $pos returns position of 'COL'
if ( $pos_col !== false )
{

}
$pos_json = strpos($QueryString, "outputFormat");
if ( $pos_json !== false )
{
	//header('Content-type: application/json; charset=UTF-8');
}
//echo $url.$QueryString;
//exit;


$sendUrl = $url.$QueryString;

//echo $sendUrl;
//exit;

header('Content-type: text/xml; charset=UTF-8');

$fp = fopen($sendUrl, 'r');
$response = '';
while ($l = fread($fp, 1024)) $response .= $l;
fclose($fp);
echo $response;
exit;
?>
