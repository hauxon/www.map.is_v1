<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Document    : vefmyndavelar.js.php
    Created on  : 1.3.2011, 16:38:20
    Author      : jonas
    Description : Used to style skamyndir.js.php component
    Dependencies: Uses clientSelect component
 
    Declares no global variables to be run
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
        // Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>

function initVefmyndavelar()
{                    
        
<?php
   // Load wfs layer into map
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
        if( $vectorlayer->layerName == "vefmyndavelar" )
        {
?>
    var defaultStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style({ 'fillColor':'white',
                            'strokeColor': 'white',
                            //'strokeWidth': 5,
                            'strokeWidth': 1,
                            //'strokeWidth': 15,
                            'strokeOpacity': 0.1,
                            'fillOpacity': 0.1,
                            //'pointRadius':5},
                            //'pointRadius':15
                            //'pointRadius':5	
                            'pointRadius':3});
    var selectStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style({	
                            'fillColor':'blue',
                            'strokeColor':'white',
                            'strokeWidth': 8,
                            //'strokeWidth': 12,
                            'fillOpacity':0.3,
                            //'fillOpacity':0.6,
                            'strokeOpacity': 0.3,
                            //'strokeOpacity': 0.6,
                            'pointRadius':8,
                            //'pointRadius':30,
                            'cursor': 'pointer'});

    var styleMap_<?=$vectorlayer->layerName?> = new OpenLayers.StyleMap();

    styleMap_<?=$vectorlayer->layerName?>.styles["default"] = defaultStyle_<?=$vectorlayer->layerName?>;
    styleMap_<?=$vectorlayer->layerName?>.styles["select"] = selectStyle_<?=$vectorlayer->layerName?>;            

    var <?=$vectorlayer->layerName?>_scales = <?=$vectorlayer->layerScales?>;
    var <?=$vectorlayer->layerName?>_wfs = new OpenLayers.Layer.<?=$vectorlayer->layerType?>("<?=$vectorlayer->layerTitle?> WFS",
        "<?=$vectorlayer->url?>",
        { typename: '<?=$vectorlayer->layerNames?>', maxfeatures: <?=$vectorlayer->maxFeatures?>},
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>, 
          extractAttributes: <?=$vectorlayer->extractAttributes?>, scales:<?=$vectorlayer->layerName?>_scales, styleMap:styleMap_<?=$vectorlayer->layerName?>});

    map.addLayers([<?=$vectorlayer->layerName?>_wfs]);
    client_select_wfs_arr.push(<?=$vectorlayer->layerName?>_wfs);

    <?=$vectorlayer->layerName?>_wfs.setVisibility(<?=$vectorlayer->visibility?>);

    getLayerByName("<?=$vectorlayer->layerTitle?>").events.register("visibilitychanged", this, function(){ 
            if(getLayerByName("<?=$vectorlayer->layerTitle?>").getVisibility()) 
            {		
                    getLayerByName("<?=$vectorlayer->layerTitle?> WFS").setVisibility(true);
            }
            else
            {
                    getLayerByName("<?=$vectorlayer->layerTitle?> WFS").setVisibility(false);
            }
    });  

<?php
        }
    }  
    
?>    
    
}


function onVefmyndavelarUnselectCallback(feature){
    tb = document.getElementById("ToolTipFlex")
    tb.style.visibility="hidden";    
}				
function onVefmyndavelarClickCallback(feature, ix, iy){
	Offsetheight = 0;
	storedFeature = feature.clone();
	storedTooltipFeature = feature.clone();
	var ToolTipFlex = document.getElementById("ToolTipFlex");	
        
        var sHtml = "<div style='line-height:1'>";
        if( feature.attributes.eigandi == "Vegagerðin"){
                Offsetheight = 215;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0" href="' + feature.attributes.slod + '"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
                sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }
        }
        else if( feature.attributes.eigandi == "RÚV" ){
                Offsetheight = 240;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';

                sHtml += '<object width="240px" height="210px" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" id="VIDEO">';
                sHtml += '<param value="mms://213.167.158.211/katla/" name="URL">';
                sHtml += '<param value="True" name="SendPlayStateChangeEvents">';
                sHtml += '<param value="True" name="AutoStart">';
                sHtml += '<param value="full" name="uiMode">';
                sHtml += '<param value="1" name="PlayCount">';
                sHtml += '<embed width="240px" height="210px" autostart="true" name="MediaPlayer" src="mms://213.167.158.211/katla/" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" type="application/x-mplayer2">';
                sHtml += '</object>';

                sHtml += '</a><br />';
                sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }			
        }	
        else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
                Offsetheight = 215;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';

                sHtml += '<img width="240px" height="180px" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

                sHtml += '</a><br />';
                sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
                {				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }			
        }		
        else{
                Offsetheight = 40;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">Skoða vefmyndavél</a><br />';
                sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
                {				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }			
        }

// Ekki eyða Verkfræðistofan Vista, verður klárað seinna
        /* if( feature.attributes.eigandi == "Verkfræðistofan Vista" )
                {
                        strHtmlContents += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                        strHtmlContents += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';
                        strHtmlContents += '<iframe scrolling="no" height="180" width="240" noresize="" marginheight="0" marginwidth="0" src="http://194.144.19.40/popup.html" target="main" name="contents"></iframe>';
                        //strHtmlContents += '<img width="184" height="138" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

                        strHtmlContents += '</a><br />';
                        strHtmlContents += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
                        if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
                        {				
                                strHtmlContents += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                        }			
                }	*/	
        
        sHtml += "</div>";
        $j("#ToolTipFlexText").html(sHtml);
	
	var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
	
	storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();
	
	ToolTipOffsetX = 5;  
	ToolTipOffsetY = Offsetheight;
	
	ToolTipFlex.style.left = (px.x - 5) + "px";  
	//ToolTipFlex.style.top = (px.y- 25) + "px";   		
	ToolTipFlex.style.top = (px.y- Offsetheight) + "px";     		
	
	ToolTipFlex.style.visibility = "visible";	    
    //}    
}
function onVefmyndavelarSelectCallback(feature, ix, iy){
    
}

