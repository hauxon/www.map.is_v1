<?php
header('Content-Type: text/plain; charset=UTF-8');

//$data_sql = querystring("sql");

// include required files
include 'XML/Query2XML.php';
include 'MDB2.php';

$data_sql = "INSERT INTO lm_tk250_email (email, ip_address, name, doctype) VALUES ('".querystring('email')."', '".querystring('ipaddress')."', '".querystring('name')."', '".querystring('doctype')."');";

try 
{	
    // initialize Query2XML object
    // LIVE - $mdb2 = MDB2::factory('pgsql://kalli:kaffi+kort@geoserver.loftmyndir.is/LM_postgis');
    $mdb2 = MDB2::factory('pgsql://kalli:kaffi+kort@geoserver.loftmyndir.is/LM_sukiyaki');
    $mdb2->query("SET NAMES 'iso-8859-1'");    // Poland douze points
    $q2x = XML_Query2XML::factory($mdb2);
    header('Content-Type: text/xml; charset=utf-8');
    $xml = $q2x->getFlatXML( stripslashes($data_sql) );
        
    // send output to browser
    $xml->formatOutput = true;	
    echo $xml->saveXML();
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}

/** querystring wrapper*/
// Checks whether requested parameter is correct.
function querystring($parameter){
	if($temp = $_GET[$parameter])
	{
		return $temp;
	}
	else
	{	
		echo "Error, missing parameter.";
		exit;
	}	 
}

function parseChars($strPar1)
{
	$strPar1 = str_ireplace ("Ã¦","æ",$strPar1);
	$strPar1 = str_ireplace ("Ã†","Æ",$strPar1);
	$strPar1 = str_ireplace ("Ã¶","ö",$strPar1);
	$strPar1 = str_ireplace ("Ã–","Ö",$strPar1);
	$strPar1 = str_ireplace ("Ã¾","þ",$strPar1);
	$strPar1 = str_ireplace ("Ãž","Þ",$strPar1);
	$strPar1 = str_ireplace ("Ã½","ý",$strPar1);
	$strPar1 = str_ireplace ("Ã","Ý",$strPar1);
	$strPar1 = str_ireplace ("Ã­","í",$strPar1);
	$strPar1 = str_ireplace ("Ã","Í",$strPar1);
	$strPar1 = str_ireplace ("Ã³","ó",$strPar1);
	$strPar1 = str_ireplace ("Ã“","Ó",$strPar1);
	$strPar1 = str_ireplace ("Ã¡","á",$strPar1);
	$strPar1 = str_ireplace ("Ã","Á",$strPar1);
	$strPar1 = str_ireplace ("Ã©","é",$strPar1);
	$strPar1 = str_ireplace ("Ã‰","É",$strPar1);
	$strPar1 = str_ireplace ("Ã°","ð",$strPar1);
	$strPar1 = str_ireplace ("Ã","Ð",$strPar1);
	$strPar1 = str_ireplace ("Ãº","ú",$strPar1);
	$strPar1 = str_ireplace ("Ãš","Ú",$strPar1);
	return $strPar1;
}
?>
