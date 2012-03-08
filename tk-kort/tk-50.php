<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta content="text/html; charset=utf-8" http-equiv="Content-Type">   
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
<script lang="javascript">
    var dloadOK = false;
    function formOK()
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
    }
  
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        return pattern.test(emailAddress);
    }
    
    function getDoc()
    {
        if(dloadOK)
        {
            var doctype = $('input[name=doctype]:checked').val();
            
            var url = "http://www.map.is/tk-kort/tk50Confirm.php?email=" + $('#tkEmail').val() + "&name=" + $('#tkName').val() + "&ipaddress=<?php print($_SERVER['REMOTE_ADDR']); ?>&doctype=" + doctype;
                       
            sendSyncAJAXRequest( url );
            debugger;
            //alert(url);
            var doctype = $('input[name=doctype]:checked').val();
            
             if(doctype == "pdf")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk50/TK-50_utlit.pdf', '_blank');
            
            if(doctype == "shp")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk50/prufusvaedi_TK50_shp.zip', '_blank');
            
            if(doctype == "dgn")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk50/prufusvaedi_TK50_dgn.zip', '_blank');
            
            if(doctype == "afnot")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk50/Afnotareglur.pdf', '_blank');
            
            if(doctype == "gagnalysing")
                window.open('http://kortasja.loftmyndir.is/img/kort/tk50/TK-50_gagnalysing.pdf', '_blank');
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
TK-50 er stafrænn landfræðilegur gagnagrunnur af Íslandi. Gögnin er hægt að taka á leigu bæði til innri nota hjá fyrirtækjum 
og stofnunum sem og í atvinnuskyni vegna ráðgjafastarfsemi verkfræðinga, arkitekta og annarra sérfræðinga. Hægt er að sækja 
prufusvæði (svæði við Eyjafjörð) á DGN eða Shape sniði. Gagnalýsingu fyrir TK-50 er hægt að nálgast <a href="http://kortasja.loftmyndir.is/img/kort/tk50/TK-50_gagnalysing.pdf">hér</a> og notkunarskilmála <a href="http://kortasja.loftmyndir.is/img/kort/tk50/Afnotareglur.pdf">hér</a>. 

<!--
TK-50 er stafrænn landfræðilegur gagnagrunnur af Íslandi. Gögnin er hægt að taka á leigu hjá Loftmyndum ehf. 
bæði til innri nota hjá fyrirtækjum og stofnunum sem og í atvinnuskini vegna ráðgjafastarfsemi
verkfræðinga, arkitekta og annarra sérfræðinga.  Hér að neðan er til reiðu prufa ( svæði við Eyjafjörð) af þessum gögnum
bæði á DGN og Shape sniði sem hlaða má niður til að að sannreyna gögnin.  <a href="http://kortasja.loftmyndir.is/img/kort/tk50/TK-50_gagnalysing.pdf">Hér</a> má lesa skjal
sem kallast gagnalýsing TK-50 sem er ýtarleg yfirferð á öllu því sem í boði er. Hvað varðar 
leiguverð þarf að leita tilboða hjá Loftmyndum en <a href="http://kortasja.loftmyndir.is/img/kort/tk50/Afnotareglur.pdf">hér</a> má finna notkunarskilmála.-->
<br/><br/>
<div align="center"><img src="http://www.map.is/tk-kort/tk50_preview.jpg"></img></div>
<br/>
<form id="tkForm" action="javascript:getDoc();">
    Netfang:<br/>
    <input type="text" id="tkEmail" size="30" onkeyup="javascript:formOK();" onblur="javascript:formOK();" /><br/>
    Nafn:<br/>
    <input type="text" id="tkName" size="30" /><br/><br/>
    <input type="radio" name="doctype" id="dpf" value="pdf" checked>Útlitstillaga af TK-50(6.8 Mb PDF skrá)</input><br/>
    <input type="radio" name="doctype" id="afnot" value="afnot">Afnotareglur (38Kb PDF skrá)</input><br/>
    <input type="radio" name="doctype" id="gagnalysing" value="gagnalysing">TK-50 gagnalýsing (3.2Mb PDF skrá)</input><br/>
    <input type="radio" name="doctype" id="shp" value="shp">Prufusvæði á SHP formi (72Mb ZIP skrá)</input><br/>
    <input type="radio" name="doctype" id="dgn" value="dgn">Prufusvæði á DGN formi (64Mb DGN skrá)</input>
    <br/><br/>
    <input type="submit" id="tkSumbitBtn" value="Sækja kort" disabled="true" />
    
</form>
<br/>
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
