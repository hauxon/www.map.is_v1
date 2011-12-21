<?php
/* 
 * Query proxy
 */
/** parametrar */
$data_type = querystring("type"); 
//$the_timestamp = querystring("timestamp");  
$value = querystring("value");  
$value2 = "aasdf";
$value2 = ((isset($_GET["value2"]))?querystring("value2"):"asdf");  
$select = "";
//$the_timestamp = iconv("UTF-8","ISO-8859-1", querystring("timestamp"));
//header('Content-type: text/html; charset=UTF-8');
//header('Content-type: text/x-json; charset=UTF-8');
header('Content-type: application/json; charset=UTF-8');  // this should be the right mimi type - works well with jQuery
//header('Content-type: text/javascript; charset=UTF-8');

// Lykilorð sem er notað til að læsa/opna MD5 lyklinum
//$strMD5Hash = md5($errorstring ."MamiyaRZ67ProIIULD");
$strMD5Hash = md5("MamiyaRZ67ProIIULD");

/*$theDB = "";
$sqlStr = "";*/

$returnVal = getSQL($data_type, $value);

//$url = "http://geoserver.loftmyndir.is/kortasja/query_db.php?type=" . $data_type . "&timestamp=" . $the_timestamp . "&MD5=" . $strMD5Hash;
//$url = "http://geoserver.loftmyndir.is/kortasja/query_db.php?type=" . $data_type . "&sql=" . $returnVal["sqlStr"] . "&MD5=" . $strMD5Hash . "&db=" . $returnVal["theDB"];
$url = "http://geoserver.loftmyndir.is/kortasja/query_db.php?type=" . $data_type . "&value=" . urlencode($value) . "&value2=" . $value2 . "&MD5=" . $strMD5Hash . "&db=" . $returnVal["theDB"];

/*echo $url;
exit;*/

$fp = fopen($url, 'r');
$response = '';
while ($l = fread($fp, 1024)) $response .= $l;
fclose($fp);
echo $response;

function querystring($parameter){
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

function getSQL($key, $searchValue)
{
	// Usage:
	// sqlStr is the SQL string for the database
	// theDB is postgre database name.  Needs to be set if not using default database (LM_postgis)

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
        case "skains":
            $sqlStr = $searchValue;
            $theDB = "&DB=LM_postgis";
			$theAction = "&action=insert";
            break;
        case "skasel":
            $sqlStr = $searchValue;
            $theDB = "&DB=LM_postgis";
			$theAction = "&action=select";
            break;			
        case "fridun":
            $valuesarr["theDB"] = "LM_postgis";
            break;	
        case "prev":
            $valuesarr["theDB"] = "LM_postgis";
            break;
        case "next":
            $valuesarr["theDB"] = "LM_postgis";
            break;        
        case "vefmyndavelar":
            $valuesarr["theDB"] = "LM_postgis";
            break;			
    }
    return $valuesarr;
}
?>
