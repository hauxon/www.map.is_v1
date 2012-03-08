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
	$myFile = "../xml/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>
//<script type="text/javascript">  
function initVefmyndavelar()
{                    
    $j("#testarea").append("<br /><a id='vefmyndavelarrofi' href='#' onclick='vefmyndavelarClick();' >Vefmyndavélar on/off</a>"); 
    $j("body").append('<a  id="hidden_clicker_iframe" class="iframe" style="display:none" href="http://www.example">This goes to iframe</a>');
    $j("body").append('<a  id="hidden_clicker_vefmyndavelar" class="vefmyndavelar" style="display:none" href="http://www.example">normal fancybox</a>');    
<?php
   // Load wfs layer into map
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
        if( $vectorlayer->layerName == "vefmyndavelar" )
        {
?>
    var defaultStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style(
                        { 
                                'fillColor':'white',
                                'strokeColor': 'white',
                                //'strokeWidth': 5,
                                'strokeWidth': 1,
                                //'strokeWidth': 15,
                                'strokeOpacity': 0.1,
                                'fillOpacity': 0.1,
                                //'pointRadius':5},
                                //'pointRadius':15
                                //'pointRadius':5	
                                'pointRadius':3
                        });
    var selectStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style(
                            {	              
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
                                'cursor': 'pointer'
                            }
                            ,
                            {
                                context: {
                                        xOff:function(feature) {
                                                            return Math.floor( Math.cos( feature.attributes.stefna ) * 20 );
                                                        }, 
                                        yOff:function(feature) {
                                                            return Math.floor( Math.sin( feature.attributes.stefna ) * 20 );
                                                        }
                                             }// end context
                                             /*,                            
                                
                               // rules: [
                                */
                                
                                
                                        /*new OpenLayers.Rule({
                                            // a rule contains an optional filter
                                            filter: new OpenLayers.Filter.Comparison({
                                                type: OpenLayers.Filter.Comparison.LESS_THAN,
                                                property: "foo", // the "foo" feature attribute
                                                value: 25
                                            }),
                                            // if a feature matches the above filter, use this symbolizer
                                            symbolizer: {
                                                externalGraphic: "../img/marker-blue.png"
                                            }
                                        }),*/
                                        
                                        
                                        /*new OpenLayers.Rule({
                                            filter: new OpenLayers.Filter.Comparison({
                                                type: OpenLayers.Filter.Comparison.BETWEEN,
                                                property: "stefna",
                                                lowerBoundary: 0,
                                                upperBoundary: 180
                                            }),
                                            symbolizer: {
                                                externalGraphic: "http://www.loftmyndir.is/teikningar/skamyndir/webcamera_big_rot.png"
                                            }
                                        })*/
                                        
                                        
                                        
                                        
                                        /*,
                                        new OpenLayers.Rule({
                                            filter: new OpenLayers.Filter.Comparison({
                                                type: OpenLayers.Filter.Comparison.BETWEEN,
                                                property: "foo",
                                                lowerBoundary: 50,
                                                upperBoundary: 75
                                            }),
                                            symbolizer: {
                                                externalGraphic: "../img/marker-gold.png"
                                            }
                                        }),
                                        new OpenLayers.Rule({
                                            // apply this rule if no others apply
                                            elseFilter: true,
                                            symbolizer: {
                                                externalGraphic: "../img/marker.png"
                                            }
                                        })*/
                                        
                                        
                                        
                                        
                                    //]// end rules
                                } // end options 
                            ); //end selectstyle
                            
  
    var styleMap_<?=$vectorlayer->layerName?> = new OpenLayers.StyleMap();

    //styleMap_<?=$vectorlayer->layerName?>.styles["default"] = style; //defaultStyle_<?=$vectorlayer->layerName?>;
    //styleMap_<?=$vectorlayer->layerName?>.styles["select"] = defstyle; //selectStyle_<?=$vectorlayer->layerName?>;            
    styleMap_<?=$vectorlayer->layerName?>.styles["default"] = defaultStyle_<?=$vectorlayer->layerName?>;
    styleMap_<?=$vectorlayer->layerName?>.styles["select"] = selectStyle_<?=$vectorlayer->layerName?>;            

    //'${thumbnail}'
    
    
    
    
    var <?=$vectorlayer->layerName?>_scales = <?=$vectorlayer->layerScales?>;
    var <?=$vectorlayer->layerName?>_wfs = new OpenLayers.Layer.<?=$vectorlayer->layerType?>("<?=$vectorlayer->layerTitle?> WFS",
        "<?=$vectorlayer->url?>",
        //{strategies: [strategy]},        
        { typename: '<?=$vectorlayer->layerNames?>', maxfeatures: <?=$vectorlayer->maxFeatures?>},
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>,  visibility:<?=$vectorlayer->visibility?>,
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
 
  /*$j("a.iframe").fancybox({
                //title      	: 'Vegagerðin vefmyndavélar',
                ///titlePosition	:'outside',
                showNavArrows	: false
		});*/
   $j("a.iframe").fancybox({             
        //'title' : 'Vegagerðin vefmyndavélar',
         //'width' : '90%',
         //'height' : '90%',
         'width' : '90%',
         'height' : '90%',
         'autoScale' : true,
         'autoDimensions': true,
         //'transitionIn' : 'none',
         //'transitionOut' : 'none',
         'type' : 'iframe'
     });    
     
  $j("a.vefmyndavelar").fancybox({             
        showNavArrows	: false
     });         
    
}

// notað við cluster prufu
function display(event) {
    var f = event.feature;
    var el = $("output");
    if(f.cluster) {
        alert( "cluster of " + f.attributes.count ); //el.innerHTML = "cluster of " + f.attributes.count;
    } else {
        alert( "unclustered " + f.geometry); //((el.innerHTML = "unclustered " + f.geometry;
    }
} 

function vefmyndavelarClick(){
    //map.layers[16].setvisibility(true);
    if(getLayerByName("Vefmyndavélar").getVisibility()) 
    {		
      getLayerByName("Vefmyndavélar").setVisibility(false);
      $j('#LSCheckbox_01').removeAttr('checked');
      // breytum iconum í LS
      $j('#vefmyndavelar_icon').removeClass("vefmyndavelar_icon_on");
      $j('#vefmyndavelar_icon').addClass("vefmyndavelar_icon_off"); 
    }
    else
    {
      getLayerByName("Vefmyndavélar").setVisibility(true);
      $j('#LSCheckbox_01').attr('checked','checked');
      // breytum iconum í LS
      $j('#vefmyndavelar_icon').removeClass("vefmyndavelar_icon_off");
      $j('#vefmyndavelar_icon').addClass("vefmyndavelar_icon_on");
    }    
}

function onVefmyndavelarUnselectCallback(feature){
    
    closeFlex();
    moveFlex();  
    /*tb = document.getElementById("ToolTipFlex")
    tb.style.visibility="hidden";    
    tb2 = document.getElementById("ToolTipFlexBottom")
    tb2.style.visibility="hidden"; */   
}

function showBigVefmyndavel(link){
     var j1 = document.getElementById("hidden_clicker_iframe");
    j1.href = link; 
    $j('#hidden_clicker_iframe').trigger('click');
}

function showBigPictureVefmyndavel(link){
     var j1 = document.getElementById("hidden_clicker_vefmyndavelar");
    j1.href = link; 
    $j('#hidden_clicker_vefmyndavelar').trigger('click');    
}

var tooltipFlexObject_Global = [];

function onVefmyndavelarSelectCallback(feature, ix, iy){
//function onVefmyndavelarClickCallback(feature, ix, iy){    
    //positionTooltipFlex(tooltipFlexObject_Global);
}

function onVefmyndavelarClick2Callback(feature, ix, iy){
	/*Offsetheight = 0;
	storedFeature = feature.clone();
	storedTooltipFeature = feature.clone();
	var ToolTipFlex = document.getElementById("ToolTipFlex");*/
        moveFlex();          
        var sHtml = "<div style='line-height:1'>";
        /*if( feature.attributes.eigandi == "Vegagerðin"){
            sHtml = "<div style='line-height:1'>";
        }*/
        if( feature.attributes.eigandi == "Vegagerðin"){
                //Offsetheight = 215;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                //sHtml += '<a data-refresh="0" href="' + feature.attributes.slod + '"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
                //sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
                //sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';">' + feature.attributes.voktun + '</a></span><br />'; 
                //sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '.jpg\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
                sHtml += '<a data-refresh="1" href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '.jpg\');closeFlex();moveFlex();"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
                sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />';                 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }
        }
        else if( feature.attributes.eigandi == "RÚV" ){
                //Offsetheight = 240;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">';

                sHtml += '<object width="240px" height="210px" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" id="VIDEO">';
                sHtml += '<param value="mms://213.167.158.211/katla/" name="URL">';
                sHtml += '<param value="True" name="SendPlayStateChangeEvents">';
                sHtml += '<param value="True" name="AutoStart">';
                sHtml += '<param value="full" name="uiMode">';
                sHtml += '<param value="1" name="PlayCount">';
                sHtml += '<embed width="240px" height="210px" autostart="true" name="MediaPlayer" src="mms://213.167.158.211/katla/" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" type="application/x-mplayer2">';
                sHtml += '</object>';

                sHtml += '</a><br />';
                sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }			
        }	
        else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
                //Offsetheight = 215;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0"   href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">';

                sHtml += '<img width="240px" height="180px" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

                sHtml += '</a><br />';
                sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
                if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
                {				
                        sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
                }			
        }		
        else{
                //Offsetheight = 40;
                sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
                sHtml += '<a data-refresh="0"  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">Skoða vefmyndavél</a><br />';
                sHtml += '<span style="white-space: nowrap;"><a   href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
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
        
        
	Offsetheight = 0;
	storedFeature = feature.clone();
	storedTooltipFeature = feature.clone();
	var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
	storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();
        // kominn uppfyrir leyfilega hæð, header svæði efst        
        //$j("#ToolTipFlexText").html(sHtml);
        //if( (iy - $j("#ToolTipFlex").height()) < 0 || iy < 200) {
        
        //var tooltipFlexObject = [];
 
        if( feature.attributes.eigandi == "Vegagerðin"){
            tooltipFlexObject_Global['topOffsetHeight'] = 220;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 28;
        }
        else if( feature.attributes.eigandi == "RÚV" ){
            tooltipFlexObject_Global['topOffsetHeight'] = 240;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 30;
        }	
        else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
            tooltipFlexObject_Global['topOffsetHeight'] = 215;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 45;
        }		
        else{
            tooltipFlexObject_Global['topOffsetHeight'] = 50;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 40;
        }                
        
        tooltipFlexObject_Global['toolTipContent'] = sHtml;
        tooltipFlexObject_Global['pixelFromLonLat'] = px;
        tooltipFlexObject_Global['ix'] = ix;
        tooltipFlexObject_Global['iy'] = iy;
        tooltipFlexObject_Global['leftOffset'] = 5;
        tooltipFlexObject_Global['rightOffset'] = 15;

         // senda með innihald, topoffset, bottomoffset, getPixelFromLonLat
         positionTooltipFlex(tooltipFlexObject_Global);                 
   
}



function onVefmyndavelarClickCallback(feature, ix, iy){
    moveFlex(); 
    
    Offsetheight = 0;
    storedFeature = feature.clone();
    storedTooltipFeature = feature.clone();
    var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
    storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();
    
    tooltipFlexObject_Global['pixelFromLonLat'] = px;
    tooltipFlexObject_Global['ix'] = ix;
    tooltipFlexObject_Global['iy'] = iy;
    tooltipFlexObject_Global['leftOffset'] = 5;
    tooltipFlexObject_Global['rightOffset'] = 15;     
    tooltipFlexObject_Global['isCluster'] = 0;  
    
    if( feature.attributes.fjoldi > 1){
 
        tooltipFlexObject_Global['isCluster'] = 1;  
        
        //$j.getJSON('http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=vefmyndavelar&value='+escape(feature.attributes.stadur), vefmyndavelar_Cluster_Callback);
        $j.getJSON('proxies/query_proxy.php?type=vefmyndavelar&value='+escape(feature.attributes.stadur), vefmyndavelar_Cluster_Callback);
        return;       
    }else{
        //$j.getJSON('http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=vefmyndavelar&value='+escape(feature.attributes.stadur), vefmyndavelar_Callback);
        //return;
        var sHtml = "<div style='line-height:1'>";
        if( feature.attributes.eigandi == "Vegagerðin"){
            //Offsetheight = 215;
            sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
            //sHtml += '<a data-refresh="0" href="' + feature.attributes.slod + '"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
            //sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';">' + feature.attributes.voktun + '</a></span><br />'; 
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '.jpg\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
            sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '.jpg\');closeFlex();moveFlex();"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigPictureVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />';                 
            if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                    sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
            }
            sHtml += "<span>Vefmyndavél " + getVefmyndavelStefna( feature.attributes.stefna ) + "</span>";
        }
        else if( feature.attributes.eigandi == "RÚV" ){
            //Offsetheight = 240;
            sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
            sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">';

            sHtml += '<object width="240px" height="210px" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" id="VIDEO">';
            sHtml += '<param value="mms://213.167.158.211/katla/" name="URL">';
            sHtml += '<param value="True" name="SendPlayStateChangeEvents">';
            sHtml += '<param value="True" name="AutoStart">';
            sHtml += '<param value="full" name="uiMode">';
            sHtml += '<param value="1" name="PlayCount">';
            sHtml += '<embed width="240px" height="210px" autostart="true" name="MediaPlayer" src="mms://213.167.158.211/katla/" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" type="application/x-mplayer2">';
            sHtml += '</object>';

            sHtml += '</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
            if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
                    sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
            }			
            sHtml += "<span>Vefmyndavél " + getVefmyndavelStefna( feature.attributes.stefna ) + "</span>";
        }	
        else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
            //Offsetheight = 215;
            sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
            sHtml += '<a data-refresh="0"   href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">';

            sHtml += '<img width="240px" height="180px" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

            sHtml += '</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
            if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
            {				
                    //sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
            }			
            sHtml += "<span>Vefmyndavél " + getVefmyndavelStefna( feature.attributes.stefna ) + "</span>";
        }		
        else{
            //Offsetheight = 40;
            sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
            sHtml += '<a data-refresh="0"  href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">Skoða vefmyndavél</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a   href="#" onclick="showBigVefmyndavel(\'' + feature.attributes.slod + '\');closeFlex();moveFlex();">' + feature.attributes.voktun + '</a></span><br />'; 
            if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
            {				
                    //sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
            }			
            sHtml += "<span>Vefmyndavél " + getVefmyndavelStefna( feature.attributes.stefna ) + "</span>";
        }	
        //sHtml += "<div>Vefmyndavél vísar í " + getVefmyndavelStefna( feature.attributes.stefna ) + "</div>";
        sHtml += "</div>";
        

        if( feature.attributes.eigandi == "Vegagerðin"){
            tooltipFlexObject_Global['topOffsetHeight'] = 220;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 28;
        }
        else if( feature.attributes.eigandi == "RÚV" ){
            tooltipFlexObject_Global['topOffsetHeight'] = 240;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 30;
        }	
        else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
            tooltipFlexObject_Global['topOffsetHeight'] = 215;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 45;
        }		
        else{
            tooltipFlexObject_Global['topOffsetHeight'] = 50;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 40;
        }                

        tooltipFlexObject_Global['toolTipContent'] = sHtml;
        tooltipFlexObject_Global['pixelFromLonLat'] = px;
        tooltipFlexObject_Global['ix'] = ix;
        tooltipFlexObject_Global['iy'] = iy;
        tooltipFlexObject_Global['leftOffset'] = 5;
        tooltipFlexObject_Global['rightOffset'] = 15;

         // senda með innihald, topoffset, bottomoffset, getPixelFromLonLat
         positionTooltipFlex(tooltipFlexObject_Global);
     
     }

}

function vefmyndavelar_Cluster_Callback(data){

    var sHtml = "";
    var sHtml = "<div id='vefcluster'>";
    for(var i = 0;i < data.length;i++){
        
        if( data[i].eigandi == "Vegagerðin"){
            
            sHtml += "<div style='line-height:1;float:left;" + (( i != data.length - 1 )?"padding-right:10px;":"") + "'>";
            //if( i > 0 ) sHtml += "<div style='align:center;'>------------------------------</div>";
            tooltipFlexObject_Global['topOffsetHeight'] = 170; // 150
            tooltipFlexObject_Global['bottomOffsetHeight'] = 20;            
            if(i == 0){
                sHtml += "<div style='width: 140px;'>" + data[i].eigandi + " - " + data[i].stadur + "</div>"; 
            }else{
                sHtml += "<div style='width: 140px;visibility:hidden;'>" + data[i].eigandi + " - " + data[i].stadur + "</div>"; 
            }
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + data[i].slod + '.jpg\');closeFlex();moveFlex();"><img height="180px" width="240px" alt="' + data[i].voktun + '" src="' + data[i].slod + '.jpg" /></a><br />';
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + data[i].slod + '.jpg\');closeFlex();moveFlex();"><img height="75px" width="100px" alt="' + data[i].voktun + '" src="' + data[i].slod + '.jpg" /></a><br />';
            sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + data[i].slod + '.jpg\');closeFlex();moveFlex();"><img height="112px" width="150px" alt="' + data[i].voktun + '" src="' + data[i].slod + '.jpg" /></a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigPictureVefmyndavel(\'' + data[i].slod + '\');closeFlex();moveFlex();">' + data[i].voktun + '</a></span><br />';
            if(i == 0){                 
                if( data[i].ath != null && data[i].ath != "" && data[i].ath != "''"){				
                        //sHtml += "<span style='white-space: nowrap;'>" + data[i].ath + "</span>";
                        //sHtml += "<span>" + data[i].ath + "</span>";
                     
                }        
                
            }
            sHtml += "<div style='width:100px'>Vefmyndavél " + getVefmyndavelStefna( data[i].stefna ) + "</div>";
            sHtml += "</div>";
        }else{

            sHtml += "<div style='line-height:1;padding-top:5px;float:left;" + (( i != data.length - 1 )?"padding-right:10px;":"") + "'>";
            //if( i > 0 ) sHtml += "<div style='align:center;'>------------------------------</div>";
            tooltipFlexObject_Global['topOffsetHeight'] = 50;
            tooltipFlexObject_Global['bottomOffsetHeight'] = 20;
            sHtml += data[i].eigandi + " - " + data[i].stadur + "<br />"; 
            sHtml += '<a data-refresh="0"  href="#" onclick="showBigVefmyndavel(\'' + data[i].slod + '\');closeFlex();moveFlex();">Skoða vefmyndavél</a><br />';
            sHtml += '<span><a   href="#" onclick="showBigVefmyndavel(\'' + data[i].slod + '\');closeFlex();moveFlex();">' + data[i].voktun + '</a></span><br />'; 
            //sHtml += '<span style="white-space: nowrap;"><a href="#" onclick="showBigVefmyndavel(\'' + data[i].slod + '\');closeFlex();moveFlex();">' + data[i].voktun + '</a></span>'; 
            if( data[i].ath != null && data[i].ath != "" && data[i].ath != "''"){
                
                    //sHtml += "<span style='white-space: nowrap; display:block;width:130px;'>" + data[i].ath + "</span>";
                    //sHtml += "<span display:block;width:130px;'>" + data[i].ath + "</span>";
                     
            }		
           sHtml += "<div style='width:100px'>Vefmyndavél " + getVefmyndavelStefna( data[i].stefna ) + "</div>";
           sHtml += "</div>";
        }        
    }
    sHtml += "</div>";
    tooltipFlexObject_Global['toolTipContent'] = sHtml;     

    positionTooltipFlex(tooltipFlexObject_Global);     
     
}

function getVefmyndavelStefna(gradur){
    
    var graduHeiti = "";
    if(gradur > 22 && gradur <= 66 ){
        graduHeiti = "vísar í norðaustur";
        //graduHeiti = "norðaustur";
    }else if(gradur > 66 && gradur <= 112 ){
        graduHeiti = "vísar í austur";
        //graduHeiti = "austur";
    }else if(gradur > 112 && gradur <= 157 ){
        graduHeiti = "vísar í suðaustur";
        //graduHeiti = "suðaustur";
    }else if(gradur > 157 && gradur <= 202 ){
        graduHeiti = "vísar í suður";
        //graduHeiti = "suður";
    }else if(gradur > 202 && gradur <= 247 ){
        graduHeiti = "vísar í suðvestur";
        //graduHeiti = "suðvestur";
    }else if(gradur > 247 && gradur <= 292 ){
        graduHeiti = "vísar í vestur";
        //graduHeiti = "vestur";
    }else if(gradur > 292 && gradur <= 343 ){
        graduHeiti = "vísar í norðvestur";
        //graduHeiti = "norðvestur";
    }else if(gradur > 343 && gradur <= 360 || gradur >= 0 && gradur <= 22 ){
        graduHeiti = "vísar í norður";
        //graduHeiti = "norður";
    }else if(gradur == 666 ){
        graduHeiti = "vísar niður á veg";
        //graduHeiti = "niður á veg";
    }else if(gradur == 999 ){
        graduHeiti = "sýnir fleiri en eina átt";
        //graduHeiti = "sýnir fleiri en eina átt";
    }    
    return graduHeiti;
}

function vefmyndavelar_Callback(data){

     var sHtml = "<div style='line-height:1'>";
    if( data[0].eigandi == "Vegagerðin"){
            //Offsetheight = 215;
            sHtml += data[0].eigandi + " - " + data[0].stadur + "<br />"; 
            //sHtml += '<a data-refresh="0" href="' + data[0].slod + '"><img height="180px" width="240px" alt="' + data[0].voktun + '" src="' + data[0].slod + '.jpg" /></a><br />';
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + data[0].voktun + '" src="' + data[0].slod + '.jpg" /></a><br />';
            //sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';">' + data[0].voktun + '</a></span><br />'; 
            //sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + data[0].slod + '.jpg\');tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180px" width="240px" alt="' + data[0].voktun + '" src="' + data[0].slod + '.jpg" /></a><br />';
            sHtml += '<a data-refresh="0" href="#" onclick="showBigPictureVefmyndavel(\'' + data[0].slod + '.jpg\');closeFlex();moveFlex();"><img height="180px" width="240px" alt="' + data[0].voktun + '" src="' + data[0].slod + '.jpg" /></a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigPictureVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">' + data[0].voktun + '</a></span><br />';                 
            if( data[0].ath != null && data[0].ath != "" && data[0].ath != "''"){				
                    sHtml += "<span style='white-space: nowrap;'>" + data[0].ath + "</span>";
            }
            sHtml += "<span style='white-space: nowrap;'>Vefmyndavél " + getVefmyndavelStefna( data[0].stefna ) + "</span>";
    }
    else if( data[0].eigandi == "RÚV" ){
            //Offsetheight = 240;
            sHtml += data[0].eigandi + " - " + data[0].stadur + "<br />"; 
            sHtml += '<a data-refresh="0" href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">';

            sHtml += '<object width="240px" height="210px" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" id="VIDEO">';
            sHtml += '<param value="mms://213.167.158.211/katla/" name="URL">';
            sHtml += '<param value="True" name="SendPlayStateChangeEvents">';
            sHtml += '<param value="True" name="AutoStart">';
            sHtml += '<param value="full" name="uiMode">';
            sHtml += '<param value="1" name="PlayCount">';
            sHtml += '<embed width="240px" height="210px" autostart="true" name="MediaPlayer" src="mms://213.167.158.211/katla/" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" type="application/x-mplayer2">';
            sHtml += '</object>';

            sHtml += '</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">' + data[0].voktun + '</a></span><br />'; 
            if( data[0].ath != null && data[0].ath != "" && data[0].ath != "''"){				
                    sHtml += "<span style='white-space: nowrap;'>" + data[0].ath + "</span>";
            }			
            sHtml += "<span style='white-space: nowrap;'>Vefmyndavél " + getVefmyndavelStefna( data[0].stefna ) + "</span>";
    }	
    else if( data[0].eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
            //Offsetheight = 215;
            sHtml += data[0].eigandi + " - " + data[0].stadur + "<br />"; 
            sHtml += '<a data-refresh="0"   href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">';

            sHtml += '<img width="240px" height="180px" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

            sHtml += '</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">' + data[0].voktun + '</a></span><br />'; 
            //sHtml += '<span style="white-space: nowrap;"><a  href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">' + data[0].voktun + '</a></span>'; 
            if( data[0].ath != null && data[0].ath != "" && data[0].ath != "''")
            {				
                    sHtml += "<span style='white-space: nowrap;'>" + data[0].ath + "</span>";
            }
            sHtml += "<span style='white-space: nowrap;'>Vefmyndavél " + getVefmyndavelStefna( data[0].stefna ) + "</span>";
            
    }		
    else{
            //Offsetheight = 40;
            sHtml += data[0].eigandi + " - " + data[0].stadur + "<br />"; 
            sHtml += '<a data-refresh="0"  href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">Skoða vefmyndavél</a><br />';
            sHtml += '<span style="white-space: nowrap;"><a   href="#" onclick="showBigVefmyndavel(\'' + data[0].slod + '\');closeFlex();moveFlex();">' + data[0].voktun + '</a></span><br />'; 
            if( data[0].ath != null && data[0].ath != "" && data[0].ath != "''")
            {				
                    sHtml += "<span style='white-space: nowrap;'>" + data[0].ath + "</span>";
            }	
            sHtml += "<span style='white-space: nowrap;'>Vefmyndavél " + getVefmyndavelStefna( data[0].stefna ) + "</span>";
    }
    // Ekki eyða Verkfræðistofan Vista, verður klárað seinna
    /* if( data[0].eigandi == "Verkfræðistofan Vista" )
    {
            strHtmlContents += data[0].eigandi + " - " + data[0].stadur + "<br />"; 
            strHtmlContents += '<a data-refresh="0" target=\"_blank\" href="' + data[0].slod + '">';
            strHtmlContents += '<iframe scrolling="no" height="180" width="240" noresize="" marginheight="0" marginwidth="0" src="http://194.144.19.40/popup.html" target="main" name="contents"></iframe>';
            //strHtmlContents += '<img width="184" height="138" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';

            strHtmlContents += '</a><br />';
            strHtmlContents += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + data[0].slod + "\">" + data[0].voktun + "</a></span><br />"; 
            if( data[0].ath != null && data[0].ath != "" && data[0].ath != "''")
            {				
                    strHtmlContents += "<span style='white-space: nowrap;'>" + data[0].ath + "</span>";
            }			
    }	*/	


    sHtml += "</div>";

    //var tooltipFlexObject = [];

    if( data[0].eigandi == "Vegagerðin"){
        tooltipFlexObject_Global['topOffsetHeight'] = 220;
        tooltipFlexObject_Global['bottomOffsetHeight'] = 28;
    }
    else if( data[0].eigandi == "RÚV" ){
        tooltipFlexObject_Global['topOffsetHeight'] = 240;
        tooltipFlexObject_Global['bottomOffsetHeight'] = 30;
    }	
    else if( data[0].eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
        tooltipFlexObject_Global['topOffsetHeight'] = 215;
        tooltipFlexObject_Global['bottomOffsetHeight'] = 45;
    }		
    else{
        tooltipFlexObject_Global['topOffsetHeight'] = 50;
        tooltipFlexObject_Global['bottomOffsetHeight'] = 40;
    }                

    tooltipFlexObject_Global['toolTipContent'] = sHtml;

     // senda með innihald, topoffset, bottomoffset, getPixelFromLonLat
     positionTooltipFlex(tooltipFlexObject_Global);    //  prufa preload onselect
}



//</script>