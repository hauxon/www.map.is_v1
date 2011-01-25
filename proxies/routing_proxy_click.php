<?php

$url = "http://geoserver.loftmyndir.is/kortasja/leit/routing_service_click.php?";


if ( request.querystring("debug") != "")
{
		echo("<br>". $url . $_SERVER['QUERY_STRING'] . "<br>");
		exit;
}

?>


<%
Response.Buffer = False
response.Charset="iso-8859-1"

url = "http://geoserver.loftmyndir.is/kortasja/leit/routing_service_click.php?"


if not request.querystring("debug") = "" then
		out("<br>"&url&request.querystring&"<br>")
		response.end
end if


response.ContentType="text/plain"
    sub out(str)
		response.write(str)
	end sub

    set xmlhttp = CreateObject("MSXML2.ServerXMLHTTP")


	xmlhttp.open "GET", url&request.querystring, false
    xmlhttp.send ""
    resp = xmlhttp.responseText
	'out url&request.querystring
	out resp

    set xmlhttp = nothing
}
%>