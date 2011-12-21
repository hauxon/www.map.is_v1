<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta content="text/html; charset=utf-8" http-equiv="Content-Type">   
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
<script lang="javascript">
    var dloadOK = false;
    function formOK()
    {
        if( $("#tkAgreeConditionsBox:checked").val() != undefined )
        {
            if( $("#tkEmail").val().length != 0 )
            {
                if ( isValidEmailAddress( $("#tkEmail").val() ) )
                {
                    $("#tkSumbitBtn").removeAttr("disabled");
                    dloadOK = true;
                }
                else
                {
                    $("#tkSumbitBtn").attr("disabled", "true");
                    dloadOK = false;
                }
            }           
            else
            {
                $("#tkSumbitBtn").attr("disabled", "true");
                dloadOK = false;
            }
        }else
        {
            $("#tkSumbitBtn").attr("disabled", "true");
            dloadOK = false;
        }
    }
  
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        return pattern.test(emailAddress);
    }
    
    function getPDF()
    {
        if(dloadOK)
        {
            var doctype = $('input[name=doctype]:checked').val();
            
            var url = "http://www.map.is/tk-kort/tk250Confirm.php?email=" + $('#tkEmail').val() + "&name=" + $('#tkName').val() + "&ipaddress=<?php print($_SERVER['REMOTE_ADDR']); ?>&doctype=" + doctype;
                       
            sendSyncAJAXRequest( url );
            //alert(url);
            var doctype = $('input[name=doctype]:checked').val();
            
            if(doctype == "pdf")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk250/TK-250.pdf', '_blank');
            
            if(doctype == "geotiff")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk250/TK-250_geotiff.zip', '_blank');
        }
    }
    
    function initAjax()
    {

            try
            {
                    // Firefox, Opera 8.0+, Safari
                    xmlHttp=new XMLHttpRequest();
            }
            catch(e)
            {
                    // Internet Explorer
                    try
                    {
                            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch(e2)
                    {
                            try
                            {
                                    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            catch(e3)
                            {
                                    alert("Your browser does not support AJAX!");
                            }
                    }

            }
    }
    
    function sendSyncAJAXRequest(url)
    {
            initAjax();
            //xmlHttp.onreadystatechange = callback;
            xmlHttp.open("GET",url,false);
            xmlHttp.send(null);
            return xmlHttp.responseText;
            //alert(xmlHttp.responseText);
    } 

</script>
</head>
<body>
<div id="tk250main" style="width:565px;font-family: Verdana, Arial,Helvetica;font-size: 8pt;">

<br/>
TK-250 er stafrænt Íslandskort Loftmynda ehf. (LM) í mælikvarðanum 1:250.000 sem einstaklingar, fyrirtæki og stofnunum er heimilt að nota gjaldfrjálst (sjá nánar notkunarskilmála). 
<br/><br/>
<div align="center"><img src="http://kortasja.loftmyndir.is/img/kort/tk250/tk250preview565.jpg"></img></div>
<br/>
<form id="tkForm" action="javascript:getPDF();">
    Netfang:<br/>
    <input type="text" id="tkEmail" size="30" onkeyup="javascript:formOK();" /><br/>
    Nafn:<br/>
    <input type="text" id="tkName" size="30" /><br/><br/>
    <input type="checkbox" id="tkAgreeConditionsBox" onclick="javascript:formOK();" /> Ég hef lesið notkunarskilmálana og samþykki þá. <br/><br/>
    <input type="radio" name="doctype" id="pdf" value="pdf" checked>PDF (21Mb)</input><br/>
    <input type="radio" name="doctype" id="geotiff" value="geotiff">GeoTIFF (37Mb ZIP skrá)</input>
    <br/><br/>
    <input type="submit" id="tkSumbitBtn" value="Sækja kort" disabled="true" />
    
</form>
<br/>
<div id="tk250Skilmalar" style="font-size:7pt;background-color:#EFEFEF; padding:5px; border: 1px #CCC solid;">
<b>NOTKUNARSKILMÁLAR:</b>
<br/><br/>
Einstaklingum, fyrirtækjum og stofnunum er heimilt að nota TK-250 gjaldfrjálst í eigin tölvukerfum,
GPS tækjum og birta á vefsíðum án sérstaks leyfis LM. Kortið eða hluta þess má prenta út í allt að
100 eintökum án sérstaks leyfis LM. Kortið má birta í sjónvarpi og dagblöðum gjaldfrjálst án sérstaks
leyfis LM. Óheimilt að breyta útliti eða endurgera kortið að hluta til eða öllu leiti. Óheimilt er að
innheimta gjald fyrir birtingu eða dreifingu kortsins. Kortið má ekki nota á kortasjám (vefsjá) eða
í vefþjónustum nema með leyfi LM. Með vefsjá er átt við veflausn þar sem t.d. er hægt að þysja inn
í kortið (zoom in).
<br/><br/>
Kortið er eign LM sem á höfundarrétt á kortinu skv. höfundalögum nr. 73/1972 og með þeim
breytingum sem gerðar hafa verið á þeim lögum síðar.
<br/><br/>
Í öllum tilfellum þegar TK-250 er birt skal textinn "Loftmyndir ehf" ásamt firmamerki LM sýnt með.
</div>
</div>
</body>
</html>
<?php

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
