<?php
	// Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>

function skalaflokkur(scale, flokkur)
{
    this.scale = scale;
    this.flokkur = flokkur;
}

function initSkamyndir()
{
	var tooltipflexpopup = '<div id="ToolTipFlex"><div id="ToolTipFlexContent"></div><a id="fancy_close"  onclick="var tb = document.getElementById( \'ToolTipFlex\' );tb.style.visibility =\'hidden\'"></a></div>';
        
	$j("body").append(tooltipflexpopup);
	
	map.events.register("moveend", map, function moveEndHandler(e) {
		//tbs = document.getElementById("ToolTipFlex");
		//if( tbs  != null )
		//	tbs.style.visibility = "hidden";
		
		});  // "moveend":moveEndHandler	

		
	$j("body").append('<div id="hidden_clicker_previous" style="display:none"><a class="skamyndir" id="hiddenclickerprevious" href="http://asdf.com" rel="gallery">Hidden Previos Clicker</a></div>');
	$j("body").append('<div id="hidden_clicker" style="display:none"><a class="skamyndir" id="hiddenclicker" href="http://asdf.com"  rel="gallery" >Hidden Next Clicker</a></div>');
	$j("body").append('<div id="hidden_clicker_next" style="display:none"><a class="skamyndir" id="hiddenclickernext" href="http://asdf.com" rel="gallery">Hidden Clicker</a></div>');


	$j("a.skamyndir").fancybox({
				title      		: 'Myndir',
				titlePosition	:'outside',
				showNavArrows	: true,
				titleFormat 	: function()
									{	
										var titletext = skamyndirfeature.attributes.timestamp;
										var picdate = new Date();
										//var millitime = Date.parse(titletext);
										// "2010:08:19 16:29:20
										picdate.setDate(titletext.substring(8, 10));
										picdate.setMonth(titletext.substring(5, 7));
										picdate.setFullYear(titletext.substring(0, 4));
										picdate.setHours(titletext.substring(11, 13));
										picdate.setMinutes(titletext.substring(14, 16));
										picdate.setSeconds(titletext.substring(17, 19));
										return '<div class="fancybox-title-outside" id="fancybox-title" style="width: 100%; padding-left: 10px; padding-right: 10px; bottom: -40px;"><span id="fancybox-title-wrap"><span id="fancybox-title-left"></span><span id="fancybox-title-main">' + (( picdate.getDate() > 9)?picdate.getDate(): "0" + picdate.getDate() ) + '.' + (( (picdate.getMonth() + 1 ) > 9)?(picdate.getMonth() + 1):"0" + (picdate.getMonth() + 1) ) + '.' + picdate.getFullYear() + '  ' + (( (picdate.getHours() + 1 ) > 9)?( picdate.getHours() + 1 ): "0" + ( picdate.getHours() + 1 ) ) + '.' + (( picdate.getMinutes() > 9)?picdate.getMinutes(): "0" + picdate.getMinutes() ) + '.' + (( picdate.getSeconds() > 9)?picdate.getSeconds() + "":"0" + picdate.getSeconds()) + '</span><span id="fancybox-title-right"></span></span></div>';
									},
//<div class="fancybox-title-outside" id="fancybox-title" style="width: 1024px; padding-left: 10px; padding-right: 10px; bottom: -40px; display: block;"><span id="fancybox-title-wrap"><span id="fancybox-title-left"></span><span id="fancybox-title-main">Myndir</span><span id="fancybox-title-right"></span></span></div>									
				onComplete		: function()
									{									
										//var featPrevNext = getNextPrevFeature( "Skámyndir WFS", "timestamp", feature.attributes.timestamp );
										//setNextPrevLinks( tfeature.attributes.escaped_image_link , featPrevNext );									
										//alert("ONCOMPLETE");
										//var titletext = skamyndirfeature.attributes.timestamp;
										//$j.fancybox.title = titletext + ""; 
									}
		});

	/*$j.fancybox.next = function() {
		if( skamyndirnextfeature != null)
		{	
			SkamyndirDirection = "next";
			
			//storedTempFeature = skamyndirnextfeature.clone();
			
			var timestamp = skamyndirnextfeature.attributes.timestamp;
			getClickedNextPrevFeatures( "Skámyndir WFS", "timestamp", timestamp );
			
			//skamyndirfeature = storedTempFeature;
			
			setClickedNextPrevLinks( "escaped_im" );	
			
			//$j("#fancybox-title").text(titletext);			
			//$j.fancybox.title = titletext + ""; 
			return $j.fancybox.pos(1);
		}
		else
		{
			alert("Ekki fleiri myndir til að skoða hér, flettu í hina áttina.");
		}
		
 	};

	$j.fancybox.prev = function() {
		if( skamyndirpreviousfeature != null)
		{
			SkamyndirDirection = "prev";
			
			//storedTempFeature = skamyndirpreviousfeature.clone();
			
			var timestamp = skamyndirpreviousfeature.attributes.timestamp;
			
			//skamyndirfeature = storedTempFeature;
			
			getClickedNextPrevFeatures( "Skámyndir WFS", "timestamp", timestamp );
			setClickedNextPrevLinks( "escaped_im" );	
			
			//$j("#fancybox-title").text(titletext);
			//$j.fancybox.title = titletext + ""; 
			return $j.fancybox.pos(1);
		}
		else
		{
			alert("Ekki fleiri myndir til að skoða hér, flettu í hina áttina.");
		}
		
	};	*/	
		
	/**VisibilityChanged events* /
	myndir.events.register("visibilitychanged", this, function(){ 
		if(myndir.getVisibility()) 
		{		
			getLayerByName("Skámyndir WFS").setVisibility(true);
		}
		else
		{
			getLayerByName("Skámyndir WFS").setVisibility(false);
		}
	});	*/	                
        
<?php
	// Load wfs layers into map
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
        if( $vectorlayer->layerName == "skamyndir" )
        {
            //include '../comp/' . $vectorlayer->styleMap->componentFileName;
?>
    var the_Scales = new Array();
    the_Scales['Flokkur1'] = new skalaflokkur(2700000, 1);		//1700000
    the_Scales['Flokkur2'] = new skalaflokkur(260000, 2);		//665000
    the_Scales['Flokkur3'] = new skalaflokkur(100000, 3);		//133000
    the_Scales['Flokkur4'] = new skalaflokkur(66500, 4);		//66500
    the_Scales['Flokkur5'] = new skalaflokkur(66500, 5);		//33500

    var defaultStyle = new OpenLayers.Style({ 'fillColor':'white',
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
    var selectStyle = new OpenLayers.Style({	
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
                            
	/*var selectStyle = new OpenLayers.Style({
                        'fillOpacity':1,
                        'strokeOpacity': 1,        
			pointRadius: 30,
			externalGraphic: "http://3w.loftmyndir.is/images/stories/starfsfolk/gissurmynd.jpg"
		});*/  
    //---- Factory to generate rules for the style --
    var the_Rule;
    for ( var i in the_Scales )
    {
            if( the_Scales[i].flokkur ) // tékkar á hvort gildi er til staðar 
            {
                    the_Rule = new OpenLayers.Rule({
                      filter: new OpenLayers.Filter.Comparison({
                          type: OpenLayers.Filter.Comparison.EQUAL_TO,
                          property: "flokkur",
                          value: the_Scales[i].flokkur
                      }), maxScaleDenominator:the_Scales[i].scale
                    });

                    selectStyle.addRules([the_Rule]); // Bæti reglunni við select stælinn
                    defaultStyle.addRules([the_Rule]); // Bæti reglunni við default stælinn
            }
    }
    // --- Style factory ends ---------------------------
    var styleMap_skamyndir = new OpenLayers.StyleMap();

    styleMap_skamyndir.styles["default"] = defaultStyle;
    styleMap_skamyndir.styles["select"] = selectStyle;            

    var <?=$vectorlayer->layerName?>_scales = <?=$vectorlayer->layerScales?>;
    var <?=$vectorlayer->layerName?>_wfs = new OpenLayers.Layer.<?=$vectorlayer->layerType?>("<?=$vectorlayer->layerTitle?> WFS",
        "<?=$vectorlayer->url?>",
        { typename: '<?=$vectorlayer->layerNames?>', maxfeatures: <?=$vectorlayer->maxFeatures?>},
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>, 
          extractAttributes: <?=$vectorlayer->visibility?>, scales:<?=$vectorlayer->layerName?>_scales, styleMap:styleMap_<?=$vectorlayer->layerName?>});

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

function getClickedNextPrevFeatures( layerName, attributeName, value )  //layerName: nafn á layer, attributeName: heiti attributa, value: gildi attributa
{

	var clickedFeature = null;
	var tempHigherFeature = null;
	var tempLowerFeature = null;
	var higheststringvalue = "zzzzzzzzzzzzzzzzzzz";  // 2010:08:19 16:29:20
	var loweststringvalue = "                  ";
	var FeatureFromLayer = null;
	skamyndirfeature = null;
	skamyndirpreviousfeature = null;
	skamyndirnextfeature = null;
	var featureZoom = ( map.zoom + 1) + "";
	for (var fff = 0; fff < getLayerByName(layerName).features.length; fff++) 
	{
		FeatureFromLayer = getLayerByName(layerName).features[fff];
		if( FeatureFromLayer.attributes != null )
		{ 
			if( FeatureFromLayer.attributes[attributeName] != null )
			{ 
				if( FeatureFromLayer.attributes[attributeName] > value  && higheststringvalue > value /* && featureZoom == FeatureFromLayer.attributes["flokkur"]*/ ) 
				{
					higheststringvalue = FeatureFromLayer.attributes[attributeName];
					tempHigherFeature = FeatureFromLayer.clone();
				}
				else if( FeatureFromLayer.attributes[attributeName] < value  && loweststringvalue < value /* && featureZoom == FeatureFromLayer.attributes["flokkur"] */ )
				{
					loweststringvalue = FeatureFromLayer.attributes[attributeName];
					tempLowerFeature = FeatureFromLayer.clone();
				}
				else if( FeatureFromLayer.attributes[attributeName] == value /* && featureZoom == FeatureFromLayer.attributes["flokkur"] */ )
				{
					clickedFeature = FeatureFromLayer.clone();
				}
			}
		}
	}	
	skamyndirnextfeature = tempHigherFeature;  
	skamyndirpreviousfeature = tempLowerFeature; 
	skamyndirfeature = clickedFeature;
}

// kannski senda með alla parametra
function callFancybox()
{
		//var tfeature = feature.clone();
		//var featPrevNext = getNextPrevFeatureLinks( "Skámyndir WFS", "timestamp", feature.attributes.timestamp );
		//setNextPrevLinks( tfeature.attributes.escaped_image_link , featPrevNext.prev, featPrevNext.next );
		getClickedNextPrevFeatures( "Skámyndir WFS", "timestamp", storedFeature.attributes.timestamp );
		setClickedNextPrevLinks( "escaped_im" );		
		$j('#hiddenclicker').trigger('click');
}

function onFeatureClickClientCallback(feature)
{	
	Offsetheight = 0;
	storedFeature = feature.clone();
	storedTooltipFeature = feature.clone();
	var ToolTipFlex = document.getElementById("ToolTipFlex");
	var ToolTipFlexContent = document.getElementById("ToolTipFlexContent");	
	
	sHtml  = "<table border='0' cellspacing='0' cellpadding='0'><tr id='top'><td id='starttop'></td><td id='middletop'></td><td id='endtop'></td></tr><tr id='middle'><td id='startmiddle'></td><td id='middlemiddle'>";
	
	if(feature.layer.name == "Skámyndir WFS"){
		Offsetheight = 185;
		if( feature.attributes.onlinepath != null || feature.attributes.onlinepath != ""  ){
			sHtml += '<a href="#" onclick="callFancybox();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a><br />';
		}
	}
	
	if(feature.layer.name == "Vefmyndavélar WFS"){
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
	}
	
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
	
	sHtml += "</td><td id='endmiddle'></td></tr><tr id='bottom'><td id='startbottom'></td><td id='middlebottom'><div id='popout_l_b'></div></td><td id='endbottom'></td></tr></table>";				
	
	ToolTipFlexContent.innerHTML = sHtml;
	
	var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
	
	storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();
	
	ToolTipOffsetX = 5;  
	ToolTipOffsetY = Offsetheight;
	
	ToolTipFlex.style.left = (px.x - 5) + "px";  
	//ToolTipFlex.style.top = (px.y- 25) + "px";   		
	ToolTipFlex.style.top = (px.y- Offsetheight) + "px";     		
	
	ToolTipFlex.style.visibility = "visible";	
}

/*function moveTooltip()
{
	var ToolTipFlex = document.getElementById("ToolTipFlex");
	ToolTipFlex.style.left = (px.x - 5) + "px";  
	//ToolTipFlex.style.top = (px.y- 25) + "px";   		
	ToolTipFlex.style.top = (px.y- Offsetheight) + "px";  	
}*/


function setClickedNextPrevLinks(attributeName)
{
	var slod = ((skamyndirfeature != null)?skamyndirfeature.attributes[attributeName]:"");
	var previous_slod = ((skamyndirpreviousfeature != null)?skamyndirpreviousfeature.attributes[attributeName]:"");
	var next_slod = ((skamyndirnextfeature != null)?skamyndirnextfeature.attributes[attributeName]:"");

	var fullpreviouspath = "";
	var fullnextpath = ""; 
	var fullpath = ""; 

	var islod = slod.indexOf("Loftmyndir");
	var islodprev = previous_slod.indexOf("Loftmyndir");
	var islodnext = next_slod.indexOf("Loftmyndir");

	if(islod != -1 )
	{
		fullpath  = slod.substring(islod + 10);		
	}
	else
	{
		islod = slod.indexOf("Flug_200");
		if(islod != -1 )
		{
			fullpath  = slod.substring(islod - 1);
		}				
		else
		{
			fullpath = ""; //alert("Ekki fleiri myndir til að skoða hér, veldu annan stað.");
		}
	}		
	
	if(islodprev != -1 )
	{
		fullpreviouspath = previous_slod.substring(islodprev + 10);		
	}
	else
	{
		islodprev = previous_slod.indexOf("Flug_200");
		if(islodprev != -1 )
		{
			fullpreviouspath = previous_slod.substring(islodprev - 1);			
		}				
		else
		{
			fullpreviouspath = ""; //alert("Ekki fleiri myndir til að skoða hér, veldu annan stað.");
		}
	}		

	if(islodnext != -1 )
	{
		fullnextpath = next_slod.substring(islodnext + 10); 			
	}
	else
	{
		islodnext = next_slod.indexOf("Flug_200");
		if(islodnext != -1 )
		{
			fullnextpath = next_slod.substring(islodnext - 1);					
		}				
		else
		{
			fullnextpath = ""; //alert("Ekki fleiri myndir til að skoða hér, veldu annan stað.");
		}
	}		
	
	
	var strHTML;
	var skamyndirlink = "http://www.loftmyndir.is/teikningar/skamyndir" + fullpath;
	var skamyndirpreviouslink = "http://www.loftmyndir.is/teikningar/skamyndir" + fullpreviouspath;
	var skamyndirnextlink = "http://www.loftmyndir.is/teikningar/skamyndir" + fullnextpath;
	callBoxFancy(skamyndirlink,skamyndirpreviouslink,skamyndirnextlink);
	//var featureOrgID = getClientFeatureByAttributeName( fornleifar_WFS_name, "verkefni_id", id ).id;
	//var featureOrgID = getNextFeatureByTimestamp( "Skámyndir WFS", "timestamp", feature.attributes.timestamp ).escaped_image_link;
	//var featureOrgID = getPrevFeatureByTimestamp( Skámyndir WFS, "timestamp", feature.attributes.timestamp ).escaped_image_link;}
	//$j('#hiddenclicker').trigger('click');
}

function callBoxFancy(my_href, my_previous_href, my_next_href) {
	var j1 = document.getElementById("hiddenclicker");
	j1.href = my_href;
	
	var jprev1 = document.getElementById("hiddenclickerprevious");
	jprev1.href = my_previous_href;
	
	var jnext1 = document.getElementById("hiddenclickernext");
	jnext1.href = my_next_href;	
	
	//$j('#hiddenclicker').trigger('click');
}