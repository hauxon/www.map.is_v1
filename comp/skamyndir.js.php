<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Document    : skamyndir.js.php
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
    
var skamyndircount = 10;    
    
function skamyndirClick(){

    if(getLayerByName("Skámyndir").getVisibility()) 
    {		
        getLayerByName("Skámyndir").setVisibility(false);
        $j('#LSCheckbox_00').removeAttr('checked');
        // breytum iconum í LS
        $j('#skamyndir_icon').removeClass("skamyndir_icon_on");
        $j('#skamyndir_icon').addClass("skamyndir_icon_off"); 
    }
    else
    {
        getLayerByName("Skámyndir").setVisibility(true);
        $j('#LSCheckbox_00').attr('checked','checked');
        // breytum iconum í LS
        $j('#skamyndir_icon').removeClass("skamyndir_icon_off");
        $j('#skamyndir_icon').addClass("skamyndir_icon_on"); 
    }    
}

function skalaflokkur(scale, flokkur){
    this.scale = scale;
    this.flokkur = flokkur;
}

function initSkamyndir(){

    //$j("#testarea").append("<br /><a id='permalinkdialog' href='#' onclick='kraekjaClick();' >krækja</a>");
    //$j("#testarea").append("<br /><a href='#modalpopup' name='printpopup'>prenta</a>");
    //$j("#testarea").append("<br /><a href='http://www.loftmyndir.is/teikningar/skamyndir/2010/Log/2010_08_19/Myndir/DSC_0583.JPG' rel='lightbox[skamyndir]' title='my caption'>Image 1</a><br /><a href='http://www.loftmyndir.is/teikningar/skamyndir/2010/Log/2010_07_21/myndir/loftmyndaflug_206.jpg' rel='lightbox[skamyndir]' title='my caption' alt='prufa'>Image 2</a>");
    
    $j("body").append("<a  id='prettyInitLink' style='display:none;' href='http://www.loftmyndir.is/teikningar/skamyndir/2010/Log/2010_07_21/myndir/loftmyndaflug_206.jpg' rel='prettyPhoto[gallery1]' title='Skámynd'>sést ekki</a>");
    $j("#prettyInitLink").before( generatePictureLinks("Prev", skamyndircount ) );
    $j("#prettyInitLink").after( generatePictureLinks("Next", skamyndircount ) );

    $j(document).ready(function() {        
        $j("a[rel^='prettyPhoto']").prettyPhoto({theme: 'facebook',slideshow:5000, autoplay_slideshow:false});
    });

    $j("#testarea").append("<br /><a id='skamyndirrofi' href='#' onclick='skamyndirClick();' >Skámyndir on/off</a>");       

    $j("body").append('<div id="hidden_clicker_previous" style="display:none"><a class="skamyndir" id="hiddenclickerprevious" href="http://asdf.com" rel="gallery">Hidden Previos Clicker</a></div>');
    $j("body").append('<div id="hidden_clicker" style="display:none"><a class="skamyndir" id="hiddenclicker" href="http://asdf.com"  rel="gallery" >Hidden Next Clicker</a></div>');
    $j("body").append('<div id="hidden_clicker_next" style="display:none"><a class="skamyndir" id="hiddenclickernext" href="http://asdf.com" rel="gallery">Hidden Clicker</a></div>');

    $j("a.skamyndir").fancybox({
        title      	: 'Myndir',
        titlePosition	:'outside',
        showNavArrows	: false,
        titleFormat 	: function(){	

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
        onComplete  : function(){									
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
        
<?php
    // Load wfs layer into map
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
        if( $vectorlayer->layerName == "skamyndir" )
        {
?>
    var the_Scales_<?=$vectorlayer->layerName?> = new Array();
    the_Scales_<?=$vectorlayer->layerName?>['Flokkur1'] = new skalaflokkur(2700000, 1);		//1700000
    the_Scales_<?=$vectorlayer->layerName?>['Flokkur2'] = new skalaflokkur(260000, 2);		//665000
    the_Scales_<?=$vectorlayer->layerName?>['Flokkur3'] = new skalaflokkur(100000, 3);		//133000
    the_Scales_<?=$vectorlayer->layerName?>['Flokkur4'] = new skalaflokkur(66500, 4);		//66500
    the_Scales_<?=$vectorlayer->layerName?>['Flokkur5'] = new skalaflokkur(66500, 5);		//33500

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
                                                     
                            
    //---- Factory to generate rules for the style --
    var the_Rule_<?=$vectorlayer->layerName?>;
    for ( var i in the_Scales_<?=$vectorlayer->layerName?> )
    {
        if( the_Scales_<?=$vectorlayer->layerName?>[i].flokkur ) // tékkar á hvort gildi er til staðar 
        {
            the_Rule_<?=$vectorlayer->layerName?> = new OpenLayers.Rule({
              filter: new OpenLayers.Filter.Comparison({
                  type: OpenLayers.Filter.Comparison.EQUAL_TO,
                  property: "flokkur",
                  value: the_Scales_<?=$vectorlayer->layerName?>[i].flokkur
              }), maxScaleDenominator:the_Scales_<?=$vectorlayer->layerName?>[i].scale
            });

            selectStyle_<?=$vectorlayer->layerName?>.addRules([the_Rule_<?=$vectorlayer->layerName?>]); // Bæti reglunni við select stælinn
            defaultStyle_<?=$vectorlayer->layerName?>.addRules([the_Rule_<?=$vectorlayer->layerName?>]); // Bæti reglunni við default stælinn
        }
    }
    // --- Style factory ends ---------------------------
    var styleMap_<?=$vectorlayer->layerName?> = new OpenLayers.StyleMap();

    styleMap_<?=$vectorlayer->layerName?>.styles["default"] = defaultStyle_<?=$vectorlayer->layerName?>;
    styleMap_<?=$vectorlayer->layerName?>.styles["select"] = selectStyle_<?=$vectorlayer->layerName?>;            

    var <?=$vectorlayer->layerName?>_scales = <?=$vectorlayer->layerScales?>;
    var <?=$vectorlayer->layerName?>_wfs = new OpenLayers.Layer.<?=$vectorlayer->layerType?>("<?=$vectorlayer->layerTitle?> WFS",
        "<?=$vectorlayer->url?>",
        { typename: '<?=$vectorlayer->layerNames?>', maxfeatures: <?=$vectorlayer->maxFeatures?>},
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>, visibility:<?=$vectorlayer->visibility?>,
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

function getClickedNextPrevFeatures( layerName, attributeName, value ){  //layerName: nafn á layer, attributeName: heiti attributa, value: gildi attributa{
	
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

function getPrettyboxPrevDataCallback(data)
{
    var sHtml = "";
    var linkcounter = 0;
    for(var i = 0;i < data.length;i++){
        linkcounter = i;
        var pictureLink = getBigPictureLink(data[i].escaped_im);
        var initHref = pictureLink;
        var initLink = document.getElementById("prettyInitLinkPrev"+i);
        initLink.href = ((initHref != "")?initHref:"");         
        initLink.rel = "prettyPhoto[gallery1]";
        var dateTime = formatDate(data[i].timestamp);
        initLink.title = "Mynd tekin " + dateTime[0] + " klukkan " + dateTime[1];
    }    
    for(var i = linkcounter+1;i < skamyndircount;i++){
        var initLink = document.getElementById("prettyInitLinkPrev"+i);
        initLink.href = "";  
        initLink.rel = "prettyPhoto[gallery0]";
    }
    
    
}

function generatePictureLinks(appendText, count){
    var sHtml = "";
    for(i = 0;i < count;i++){
        sHtml += "<a id='prettyInitLink" + appendText + i + "' style='display:none;' href='http://www.loftmyndir.is/teikningar/skamyndir/2010/Log/2010_07_21/myndir/loftmyndaflug_206.jpg' rel='prettyPhoto[gallery1]' title='Skámynd'>sést ekki</a>"; 
    }
    return sHtml
}

function formatDate(timestamp){
    var picdate = new Date();
    picdate.setDate(timestamp.substring(8, 10));
    picdate.setMonth(timestamp.substring(5, 7));
    picdate.setFullYear(timestamp.substring(0, 4));
    picdate.setHours(timestamp.substring(11, 13));
    picdate.setMinutes(timestamp.substring(14, 16));
    picdate.setSeconds(timestamp.substring(17, 19));
    var dateTime = [];
    dateTime[0] = (( picdate.getDate() > 9)?picdate.getDate(): "0" + picdate.getDate() ) + '.' + (( (picdate.getMonth() + 1 ) > 9)?(picdate.getMonth() + 1):"0" + (picdate.getMonth() + 1) ) + '.' + picdate.getFullYear();
    dateTime[1] = (( (picdate.getHours() + 1 ) > 9)?( picdate.getHours() + 1 ): "0" + ( picdate.getHours() + 1 ) ) + '.' + (( picdate.getMinutes() > 9)?picdate.getMinutes(): "0" + picdate.getMinutes() ) + '.' + (( picdate.getSeconds() > 9)?picdate.getSeconds() + "":"0" + picdate.getSeconds());
    return dateTime;
    //return { 'day': (( picdate.getDate() > 9)?picdate.getDate(): "0" + picdate.getDate() ) + '.' + (( (picdate.getMonth() + 1 ) > 9)?(picdate.getMonth() + 1):"0" + (picdate.getMonth() + 1) ) + '.' + picdate.getFullYear() , 'time': (( (picdate.getHours() + 1 ) > 9)?( picdate.getHours() + 1 ): "0" + ( picdate.getHours() + 1 ) ) + '.' + (( picdate.getMinutes() > 9)?picdate.getMinutes(): "0" + picdate.getMinutes() ) + '.' + (( picdate.getSeconds() > 9)?picdate.getSeconds() + "":"0" + picdate.getSeconds()) };
}

function getBigPictureLink(link){
    var slod = link;
    var islod = slod.indexOf("Loftmyndir");
    var fullpath = "";

    if(islod != -1 ){
        fullpath  = slod.substring(islod + 10);		
    }else{
        islod = slod.indexOf("Flug_200");
        if(islod != -1 ){
                fullpath  = slod.substring(islod - 1);
        }				else{
                fullpath = ""; //alert("Ekki fleiri myndir til að skoða hér, veldu annan stað.");
        }
    }	        
    var finalslod = "http://www.loftmyndir.is/teikningar/skamyndir" + fullpath;    
    return finalslod;
}

function getPrettyboxNextDataCallback(data)
{
    var sHtml = "";
    var linkcounter = 0;
    for(i = 0;i < data.length;i++){
        
        linkcounter = i;
        var pictureLink = getBigPictureLink(data[i].escaped_im);
        var initHref = pictureLink;
        var initLink = document.getElementById("prettyInitLinkNext"+i);
        initLink.href = ((initHref != "")?initHref:"");  
        initLink.rel = "prettyPhoto[gallery1]";
        var dateTime = formatDate(data[i].timestamp);
        initLink.title = "Mynd tekin " + dateTime[0] + " klukkan " + dateTime[1];
    }    
    for(var i = linkcounter+1;i < skamyndircount;i++){
        var initLink = document.getElementById("prettyInitLinkNext"+i);
        initLink.href = "";       
        initLink.rel = "prettyPhoto[gallery0]";
    }    
    $j('#prettyInitLink').trigger('click');         
}

function fillAndOpenPrettybox(timestamp, count)
{   
    $j.ajax({  
      //url: 'http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=prev&value='+escape(timestamp),  
      url: 'proxies/query_proxy.php?type=prev&value='+escape(timestamp) + '&value2=' + count,  
      dataType: 'json',  
      data: null,  
      async: false,  
      success: getPrettyboxPrevDataCallback
    });     
    
    $j.ajax({  
      url: 'proxies/query_proxy.php?type=next&value='+escape(timestamp) + '&value2=' + count,  
      //url: 'http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=next&value='+escape(timestamp),  
      dataType: 'json',  
      data: null,  
      async: false,  
      success: getPrettyboxNextDataCallback
    });
    
    //$j.getJSON('http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=prev&value='+escape(timestamp), getPrettyboxPrevDataCallback);
    //$j.getJSON('http://193.4.153.85:8088/www.map.is/proxies/query_proxy.php?type=next&value='+escape(timestamp), getPrettyboxNextDataCallback);
    
    //$j('#prettyInitLink').trigger('click'); 
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


function onSkamyndirUnselectCallback(feature){
    closeFlex();
    moveFlex();        
}				
function onSkamyndirClickCallback(feature, ix, iy){

    var initHref = getBigPictureLink(feature.attributes.escaped_im);
    var initLink = document.getElementById("prettyInitLink");
    initLink.href = ((initHref != "")?initHref:"");
    var dateTime = formatDate(feature.attributes.timestamp);
    initLink.title = "Mynd tekin " + dateTime[0] + " klukkan " + dateTime[1];

    moveFlex();

    Offsetheight = 0;
    storedFeature = feature.clone();
    storedTooltipFeature = feature.clone();
    var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
    storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();

    // setja default stærð á gluggann. Gert ef tooltipFlex er samnýttur í mismunandi stærðum
    //$j("#ToolTipFlexText").html('<a href="#" onclick="callFancybox();tb=document.getElementById(\'ToolTipFlex\');tb.style.visibility=\'hidden\';"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir/0.jpg" /></a><br />');

    var tooltipFlexObject = [];
    //tooltipFlexObject['toolTipContent'] = '<a href="#" onclick="callFancybox();closeFlex();moveFlex();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a><br />';
    //tooltipFlexObject['toolTipContent'] = '<a href="#" onclick="fillAndOpenPrettybox(\'' + feature.attributes.timestamp + '\', skamyndircount );closeFlex();moveFlex();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a><br />';
    tooltipFlexObject['toolTipContent'] = '<a href="#" onclick="fillAndOpenPrettybox(\'' + feature.attributes.timestamp + '\', skamyndircount );closeFlex();moveFlex();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a>';
    //tooltipFlexObject['toolTipContent'] = '<a href="#" onclick="fillAndOpenPrettybox(\'' + feature.attributes.timestamp + '\' );closeFlex();moveFlex();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a><br />';
    tooltipFlexObject['topOffsetHeight'] = 185;
    tooltipFlexObject['bottomOffsetHeight'] = 30;
    tooltipFlexObject['pixelFromLonLat'] = px;
    tooltipFlexObject['ix'] = ix;
    tooltipFlexObject['iy'] = iy;
    tooltipFlexObject['leftOffset'] = 5;
    tooltipFlexObject['rightOffset'] = 15;

     // senda með innihald, topoffset, bottomoffset, getPixelFromLonLat
     positionTooltipFlex(tooltipFlexObject);
	    
}
function onSkamyndirSelectCallback(feature, ix, iy){

}

//</script>