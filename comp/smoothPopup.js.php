<?php

/*
 *  open
 *  $j("#smoothPopupHeader").text("Titill")
 *  $j("#smoothPopupArea").html("<div>Texti</div>");                  
 *  enablePopup(); 
 * 
 *  close
 *  disablePopup
 */
?>
//<script type="text/javascript">
function initSmoothPopup()
{
    var sHtml = "";
    sHtml += '<div id="smoothPopup">';
    //sHtml += '<div id="smoothtooltiptop"><div id="smoothtooltip_lt"></div><div id="smooothtooltip_mt"><div id="ttDeiliTxt">ljkækl</div></div><div id="smoothtooltip_rt"></div></div>';
    sHtml += '<div id="smoothPopupCloseDiv"><a id="smoothPopupClose" href="#" onclick="disablePopup();"></a></div><div id="smoothPopupHeader"></div><div id="smoothPopupArea"></div></div></div><div id="backgroundPopup"></div>';
    //sHtml += '<div id="smoothtooltipbottom"><div id="smoothtooltip_lb"></div><div id="smooothtooltip_mb"><div id="ttDeiliTxt">ljkækl</div></div><div id="smoothtooltip_rb"></div></div>';
    sHtml += '</div>';
    $j("body").append(sHtml);
    //$j("body").append('<div id="smoothPopup"><a id="smoothPopupClose" href="#" onclick="disablePopup();">x</a><div id="smoothPopupHeader"></div><div id="smoothPopupArea"></div></div></div><div id="backgroundPopup"></div>');
}
//</script>