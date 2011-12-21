<?php
/* 
 * Leitar proxy
 */

// Fyrir íslenzku stafina
//header('Content-type: text/xml; charset=iso-8859-1');

header('Content-type: text/plain; charset=iso-8859-1');

/*header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");*/


$sKey = $_GET['sKey'];
$sValue =  $_GET['sValue'];

//$sKey = $_POST['sKey'];
//$sValue =  $_POST['sValue'];

$nCounty = ""; // LAGA SEINNA $_GET['nCounty'];
$theDB = "";
$theAction = "";
$errorstring = getSQL($sKey, urldecode($sValue),$nCounty );


// Lykilorð sem er notað til að læsa/opna MD5 lyklinum
//$strMD5Hash = md5($errorstring ."MamiyaRZ67ProIIULD");
$strMD5Hash = md5("MamiyaRZ67ProIIULD");

$url = "http://geoserver.loftmyndir.is/kortasja/errors.php?error=" . urlencode($errorstring) . "&md5=" . $strMD5Hash . $theDB;

//echo $url;

$fp = fopen($url, 'r');
$response = '';
while ($l = fread($fp, 1024)) $response .= $l;
fclose($fp);
echo $response;

function getSQL($key, $searchValue,$sveitarfelag)
{
	// Usage:
	// sqlStr is the SQL string for the database
	// theDB is postgre database name.  Needs to be set if not using default database (LM_postgis)

	$sqlStr = "";

        switch ($key) {
        case "checkAddressAndZipUnique":
            
            // searchValue holds two values. Pipe is delimeter (|)
            $searchValArr = explode("|",$searchValue);
            $sqlStr = "SELECT ST_AsGeoJson(the_geom) AS the_geom, postnumer, sveitarfel, tettbyliss FROM postfong WHERE heimilisfa ILIKE '" . $searchValArr[0] ."' AND (postnumer ILIKE '" . $searchValArr[1] ."' OR sveitarfel ILIKE '" . $searchValArr[1] ."' OR tettbyliss ILIKE '" . $searchValArr[1] ."')";
            $theDB = "&DB=routing";
            break;
        case "checkUniqueAddress":
            $sqlStr = "SELECT ST_AsGeoJson(the_geom) AS the_geom, postnumer, sveitarfel, tettbyliss FROM postfong WHERE heimilisfa ILIKE '" . $searchValue ."' AND postfang ILIKE 'Y' ORDER BY postnumer";
            $theDB = "&DB=routing";
            break;
        case "errors":
            $sqlStr = $searchValue;
            $theDB = "&DB=LM_postgis";
			$theAction = "&action=insert";
            break;
        case "errorslog":
            $sqlStr = $searchValue;
            $theDB = "&DB=LM_postgis";
			$theAction = "&action=select";
            break;			
        }

        
        return $sqlStr;
}
?>
