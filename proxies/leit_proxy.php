<?php
/* 
 * Leitar proxy
 */

// Fyrir íslenzku stafina

//header('Content-type: text/xml; charset=UTF-8');


$sKey = $_GET['sKey'];
$sValue =  $_GET['sValue'];
//$sValue =  iconv("UTF-8","ISO-8859-1", $sValue);
//$sValue = iconv("Windows-1252","Windows-1252", $_GET['sValue']);
$nCounty = ""; // LAGA SEINNA $_GET['nCounty'];
$theDB = "";
$sqlstring = getSQL($sKey, urldecode($sValue),$nCounty );


// Lykilorð sem er notað til að læsa/opna MD5 lyklinum
$strMD5Hash = md5($sqlstring ."MamiyaRZ67ProIIULD");

$url = "http://geoserver.loftmyndir.is/kortasja/leit/leitproxy.php?sql=" . urlencode($sqlstring) . "&md5=" . $strMD5Hash . $theDB;

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
        }

        //echo $sqlStr;
        return $sqlStr;
}
?>