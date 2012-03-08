<?php

/*
component sem birtir prentatakka og implementar prentvirkni
 */
?>
//<script type="text/javascript">
function initPrint(){

    //linkur búinn til með href á generic modal glugga href='#modalpopup, nafn á generic modalglugga, þarf að setja text líka - sjá modalbox
    //$j("#testarea").append("<br /><a id='modalbutton' href='#modalpopup'>prenta modalbutton með generic modalpopup</a>");
    //búinn til samnýttyr modalgluggi úr link
    //doModal($j('#modalbutton'));
    
    //þarf að setja texta áður en smellt er á glugga
    //$j("#modalpopup").append("<div id='printText' style='background:#FFFFFF'>prufa prufa</div>");

    //linkur búinn til með href sem er nafn á sér glugga sem er inn í modalboxes containerinn- sjá modalbox
    //$j("#testarea").append("<br /><a href='#modalpopup' name='printpopup'>prenta modalpopup</a>");
    //búinn til modalgluggi úr link og texti settur
    //doModalPopup($j('a[name=printpopup]'),"<div id='printText' style='background:#FFFFFF'>prufa prufa</div>");
    
    //linkur búinn til með href sem er nafn á sér glugga sem er inn í modalboxes containerinn- sjá modalbox
    //$j("#testarea").append("<br /><a href='#printpopup' name='printpopup'>prenta printpopup</a>");
    //búinn til modalgluggi úr link og texti settur
    doModalPopup($j('a[name=printpopup]'),"<div id='printText' style='background:#FFFFFF'>prufa prufa</div>");
    
    var sHtml = "";
    sHtml += '<div id="printcontainer">';
    sHtml += '<fieldset id="print_menu">';
    sHtml += "<div id='printText' style='background:#FFFFFF'>";
    sHtml += "<div id='printInfo'>Hér er hægt að setja inn texta sem kemur með á kortið. </div>";
    sHtml += "<div id='printHeaderTitle'>Titill</div>";
    sHtml += "<div id='printHeaderBox'><input type='text' id='printHeader' value='' /></div>";
    sHtml += "<div id='printInputTitle'>Texti</div>";
    sHtml += "<div id='printInputBox'><textarea id='printInput' name='printInput' class='printInput'></textarea></div>";
    //sHtml += "<div id='sendToPrint'><span style='padding-right:20px'><a href='#' onclick='doPrint();'>Hætta við</a></span><a id='sendToPrintButton' href='#' onclick='openPrintWindow();doPrint();'>Áfram</a></div>";
    sHtml += "<div id='sendToPrint'><span style='padding-right:20px'><a href='#' onclick='doPrint();'>Hætta við</a></span><a id='sendToPrintButton' href='#' onclick='doPrint();openPrint();'>Áfram</a></div>";
    sHtml += "</div>";    
    sHtml += '</fieldset>';
    sHtml += '</div>';
    
    $j("body").append(sHtml);
    $j(document).ready(function() {
        $j("fieldset#print_menu").mouseup(function() {
            return false;
        }); 
        
        $j(document).mouseup(function(e) {
            if( $j(e.target).is("#headerBtnDivPrint") || $j(e.target).is("#headerBtnPrint") ){
               if( $j("fieldset#print_menu").hasClass('menu-open') ) {
                    headerButtonClear("Print");
                }                
            }else{
                
                if( $j("fieldset#print_menu").hasClass('menu-open') ) {
                    headerButtonClear("Print");
                    $j("fieldset#print_menu").toggle();
                    $j("fieldset#print_menu").toggleClass("menu-open");                 
                }
            }
        });
    });    
}

function doPrint(){
    if( $j("fieldset#print_menu").hasClass('menu-open') ) {
        headerButtonClear("Print");        
    }else{
        $j("#printHeader").val("");
        $j("#printInput").val("");          
    }
    $j("fieldset#print_menu").toggle();
    $j("fieldset#print_menu").toggleClass("menu-open");    
}

function openPrintStart() {
    $j("#smoothPopupHeader").text("Setja titil og texta")
    var sHtml = "<div id='printText' style='background:#FFFFFF'>";
    sHtml += "<div id='printInfo'>Hér er hægt að setja inn texta sem kemur með á kortið. </div>";
    sHtml += "<div id='printHeaderTitle'>Titill</div>";
    sHtml += "<div id='printHeaderBox'><input type='text' id='printHeader' value='' /></div>";
    sHtml += "<div id='printInputTitle'>Texti</div>";
    sHtml += "<div id='printInputBox'><textarea id='printInput' name='printInput' class='printInput'></textarea></div>";
    sHtml += "<div id='sendToPrint'><span style='padding-right:20px'><a href='#' onclick='disablePopup();'>Hætta við</a></span><a id='sendToPrintButton' href='#' onclick='openPrintWindow();disablePopup();'>Áfram</a></div>";
    //sHtml += "<div id='sendToPrint'><span style='padding-right:20px'><a href='#' onclick='disablePopup();'>Hætta við</a></span><a id='sendToPrintButton' href='#' onclick='openPrint();disablePopup();'>Áfram</a></div>";
    sHtml += "</div>";
    $j("#smoothPopupArea").html(sHtml);                
    pressHeaderButton("Print");
    enablePopup();    
}

function openPrintWindow(){
    var minnkaprent = 350;
    if ($j('#sliderPanelBtn').hasClass('close')){
        minnkaprent = 0;
    }else{
        minnkaprent = 350;        
    }    
    // tilraun til að vera með dynamic stærð eftir hvort slider er opinn eða ekki
    //var printWindow = window.open ( "http://193.4.153.192:8088/www.map.is/?print=1","printWindow","width=" + ($j("#map").width() - minnkaprent) + "px,height=" + $j("#map").height() + "px" );
    // Minnka alltaf, virðist virka best í browserum
    printWindow = window.open( viewLink.div.childNodes[0].toString().replace("#","") + "&print=1&title=" + $j("#printHeader").val() + "&input=" + $j("#printInput").val(), "printWindow", "status=0, toolbar=0, scrollbars=0, resizable=0, menubar=0, height=650px, width=850px");    
}

function openPrint(){
    preparePrint2();
    window.print();
    cleanupPrint2();
}

function preparePrint(){
    
    // Hér er prentun í nýjum glugga

    //$j("#map").css("height", $j("#map").height() + 84 + "px" );
    $j("#map").css("height", $j("#map").height() + 42 + "px" );

    $j("#sliderPanel").css( "visibility", "hidden");
    $j(".olControlScaleBar").css( "visibility", "hidden");
    $j("#LM_panzoombar").css( "visibility", "hidden");
    $j(".olControlnewLayerSwitcher").css( "visibility", "hidden");
    $j(".olControlOverviewMap").css( "visibility", "hidden");
    $j("#header").css( "visibility", "hidden");
    $j("#printButton").css( "visibility", "hidden");
    $j("#searchDiv").css( "visibility", "hidden");
    $j("#inputString").css( "visibility", "hidden");
    $j("#baselayers_chooser").css( "visibility", "hidden");
    $j("#olControlOverviewMapMaximizeButton_innerImage_chooser").css( "visibility", "hidden");   
    $j("#header_shadow").css( "visibility", "hidden");
    $j("#map").css( "top", "44px");
    $j("#map").css("left", "-" + 135 + "px");
    $j("body").css("background-color", "#FFFFFF");
    $j(".olControlAttribution").css( "visibility", "hidden"); // þarf ekki lengur að vera á myndunum
    //$j(".olControlAttribution").css( "top", "100px").css( "left", "100px");
    
    var minnkaprent = 135;
    if ($j('#sliderPanelBtn').hasClass('close'))    {
        minnkaprent = 0;
    }  
    $j("#map").css("width", $j("#map").width() + minnkaprent + "px" );
    $j("body").append("<div id='printHeaderResult'><b>" + printTitle + "</b></div><div id='printInputResult'>" + printInput + "</div>")
    $j("body").append("<div id='mapDotIsLMLogo'><img src='img/LM_logo.jpg'></div>");  //<div id='mapDotIslogoFollower'></div>")
    $j("body").append("<div id='mapDotIsLogo'><img src='img/routing/maplogo_bull.png'></div>");  //<div id='mapDotIslogoFollower'></div>")



}

function cleanupPrint(){
        
    // Hér er hreinsað eftir prentun original
    $j("#printHeader").val("");
    $j("#printInput").val("");   
    
    $j("#sliderPanel").css( "visibility", "visible");
    $j(".olControlScaleBar").css( "visibility", "visible");
    $j("#LM_panzoombar").css( "visibility", "visible");
    $j(".olControlnewLayerSwitcher").css( "visibility", "visible");
    $j(".olControlOverviewMap").css( "visibility", "visible");
    $j("#header").css( "visibility", "visible");
    $j("#printButton").css( "visibility", "visible");
    $j("#searchDiv").css( "visibility", "visible");
    $j("#inputString").css( "visibility", "visible");
    $j("#baselayers_chooser").css( "visibility", "visible");
    $j("#olControlOverviewMapMaximizeButton_innerImage_chooser").css("visibility", "visible");   
    $j("#header_shadow").css( "visibility", "visible");
    $j("#map").css( "top", "84px");
    $j("#map").css("left", "0px");
    $j("body").css("background-color", "#FFFFFF");
    //$j(".olControlAttribution").css( "visibility", "visible");  // þarf að vera á myndunum
    
    $j("#map").css("height", $j("#map").height() - 84 + "px" );    
    var minnkaprent = 135;
    if ($j('#sliderPanelBtn').hasClass('close')) {
        minnkaprent = 0;
    }  
    $j("#map").css("width", $j("#map").width() - minnkaprent + "px" );      
}

function preparePrint2(){
    
    // Hér er prentun prentun í sama glugga
    //$j("#map").css( "top", "0px");
    //$j(".hrannarMap").css( "top", "0px");   
    
    //$j("#map").css("height", $j("#map").height() + 84 + "px" );
    
    //$j(".hrannarMap").css("height", $j(".hrannarMap").height() + 84 + "px" );    
    //
    //$j("#sliderPanel").resize();
    $j("#sliderPanel").css( "visibility", "hidden");
    //$j("#sliderPanel").css( "left", "-500px").css( "top", "-500px");
    $j(".olControlScaleBar").css( "visibility", "hidden");
    $j("#LM_panzoombar").css( "visibility", "hidden");
    $j(".olControlnewLayerSwitcher").css( "visibility", "hidden");
    $j(".olControlOverviewMap").css( "visibility", "hidden");
    $j("#header").css( "visibility", "hidden");
    $j("#printButton").css( "visibility", "hidden");
    $j("#searchDiv").css( "visibility", "hidden");
    $j("#inputString").css( "visibility", "hidden");
    $j("#baselayers_chooser").css( "visibility", "hidden");
    $j("#olControlOverviewMapMaximizeButton_innerImage_chooser").css( "visibility", "hidden");   
    $j("#header_shadow").css( "visibility", "hidden");
    $j("body").css("background-color", "#FFFFFF");
    
    /*
    //$j(".olControlAttribution").css( "visibility", "hidden");
    var extra_olControlAttribution = $j(".olControlAttribution").clone(); //$j(".olControlAttribution")[0];
    $j(extra_olControlAttribution).css( "visibility", "hidden");
    //$j("body").append($j(extra_olControlAttribution).css( "visibility", "visible").css( "bottom", "0px").css( "right", "0px"));
    */
    
    //$j("body").append($j(".olControlAttribution").clone().css( "bottom", "0px").css( "right", "0px"));
    $j("body").css("background-color", "#FFFFFF");
    //$j("body").append("<div id='printResults' style='height:70px;width;position:absolute;bottom:0px;left:300px;z-index:12'><div><b>" + $j("#printHeader").val() + "</b></div><div>" + $j("#printInput").val() + "</div></div>");    
    $j("body").append("<div id='printResults' style='position:absolute;height:70px;width;position:absolute;top:20px;left:300px;z-index:12'><div><b>" + $j("#printHeader").val() + "</b></div><div>" + $j("#printInput").val() + "</div></div>");    
    //$j("body").append("<div id='printTrans' style='position:absolute;background-image:url(img/blue-trans3.png); height:84px; width:100%; left: 0px;bottom: 0px'></div>");  
    //$j("body").append("<div id='printTrans' style='position:absolute;height:84px; width:100%; left: 0px;bottom: 0px'></div>");  
    //$j("body").append("<div id='mapLogoIs' style='position:absolute;left: 20px;bottom: 20px'><img src='img/routing/maplogo_bull.png'></div>");
    $j("body").append("<div id='mapLogoIs' style='position:absolute;left: 20px;top: 20px'><img src='img/routing/maplogo_bull.png'></div>");
}



function cleanupPrint2(){
        
   // Hér er hreinsað eftir prentun í sama glugga
   
    //$j("#map").css( "top", "84px");
    //$j(".hrannarMap").css( "top", "84px");
    //
    //$j("#map").css("height", $j("#map").height() - 84 + "px" );   
    //$j("#map").css("height", $j("#map").height() - 84 + "px" );   
    
    //$j(".hrannarMap").css( "top", "84px");
    //$j(".hrannarMap").css("height", $j(".hrannarMap").height() - 84 + "px" );
   
    $j("#printHeader").val("");
    $j("#printInput").val("");   
   
    $j("#sliderPanel").css( "visibility", "visible");
    $j(".olControlScaleBar").css( "visibility", "visible");
    $j("#LM_panzoombar").css( "visibility", "visible");
    $j(".olControlnewLayerSwitcher").css( "visibility", "visible");
    $j(".olControlOverviewMap").css( "visibility", "visible");
    $j("#header").css( "visibility", "visible");
    $j("#printButton").css( "visibility", "visible");
    $j("#searchDiv").css( "visibility", "visible");
    $j("#inputString").css( "visibility", "visible");
    $j("#baselayers_chooser").css( "visibility", "visible");
    $j("#olControlOverviewMapMaximizeButton_innerImage_chooser").css("visibility", "visible");   
    $j("#header_shadow").css( "visibility", "visible");
    //$j("#map").css( "top", "84px");
    //$j("#map").css("left", "0px");
    $j("body").css("background-color", "#FFFFFF");
    //$j(".olControlAttribution").css( "visibility", "visible");  
    
    
    /*if( $j(".olControlAttribution").length > 1 ){
        var extra_olControlAttribution = $j(".olControlAttribution")[1];
        $j(extra_olControlAttribution).remove();
    }*/
    //$j(".olControlAttribution").css( "visibility", "visible");
    
    $j("#printResults").remove();
    //$j("#mapDotIsLMLogo").remove();
    //$j("#mapDotIsLogo").remove();    
    $j("#mapLogoIs").remove();    
    //$j("#printTrans").remove();
}

function timeOutPrint(){
    window.print();
    window.close();
}

function stopLoading(){
    preparePrint();
    $j("#loading").fadeOut(500);
    setTimeout('timeOutPrint();', 1000);
}	

$j(document).ready(function() {        
    if( isPrint ){
        $j("body").append("<div id='loading' style='height:100%;width:100%;background-image:url(\"http://www.loftmyndir.is/k/img/loading_animated.gif\");z-index:1015;position:absolute;top:0px;left:0px;background-repeat:no-repeat;background-position:center;background-color:#DEDEDE;'></div>");
        setTimeout('stopLoading();', 4000);
    } 
});


//</script>

