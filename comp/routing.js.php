<! --script -->
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 routing.js.php
 Dependensies: Context menu needs to run first
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
<?php
	// Opnum postnumer.xml
	$myFile = "../xml/postnumer.xml";
	$fh = fopen($myFile, 'r');
	$postnumerXML = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
        // Rodum upp postnumerum fyrir drop-down i vidmoti
        $pnrOptions = "";
	foreach ($postnumerXML->xpath('//Postnumer') as $pnr)
	{
            // Puslad saman HTML fyrir drop-down field
            $pnrOptions .= "<option value=" . $pnr->zip . ">" . $pnr->zip . " " . $pnr->HeitiNF . "</option>";
        }
?>
// Global breytur fyrir routing
var routeFromHereCoords = "";
var routeToHereCoords = "";
var client_layers = [];
var submitFromAddr = "";
var chosenFromZip = "";
var submitToAddress = "";
var chosenToZip = "";
var vectors = null;


// Hér kemur það sem þarf að keyra eftir að kortið hefur initað sig
function initRouting()
{
    /*var wms_routing_vegir = new OpenLayers.Layer.WMS.Untiled( "Vegir overlay","http://212.30.228.18/geoserver/wms",
    {layers:'postgis:routing_vegir',format:'image/jpeg',transparent: true, styles:'line_routingtest'},
    {'displayInLayerSwitcher':true, 'isBaseLayer':false,visibility:false});
    map.addLayer(wms_routing_vegir);*/

     //////////////////////////////////////////////////////////////////////////// Vector
     // Setjum útlit á vegvísunar layerinn
     var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
     //layer_style.fillOpacity = 0.2;
     //layer_style.graphicOpacity = 1;
     var style_routing = OpenLayers.Util.extend({}, layer_style);
     style_routing.strokeColor = "#bd1776"; // bleikfjólublár
     //style_routing.graphicName = "star";
     //style_routing.pointRadius = 0;
     style_routing.strokeWidth = 4;
     style_routing.strokeOpacity = 0.9;
     style_routing.strokeLinejoin = "miter";
     style_routing.strokeLineCap = "butt";

    // Setjum inn vectorlayer til að birta niðurstöðu vegvísunar á kortinu
     vectors = new OpenLayers.Layer.Vector("Vegvísun", {style: style_routing, 'displayInLayerSwitcher':true});
     //client_layers.push(vectors);
     map.addLayer(vectors);
    //////////////////////////////////////////////////////////////////////////// Vector end

    //$j("#sliderAccordion").append("<h3><a href='#'>Leit</a></h3><div><p><div id='searchResultPanel'>Velkomin á map.is</div></p></div>");
    //Insert HTML Container for Routing
    //RoutingHtml = "<h3><a href='#'>Vegvísun</a></h3><div>";
    RoutingHtml = "<div id='RoutingDIV'>";
    RoutingHtml += "<img id='vegvisun_divider1' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
    RoutingHtml += "<div id='vegvisun_A'><img src='img/routing/routing_marker_A.png'></div><div id='fromtext'>Frá:</div><input id='routing_from_addr' type='text'>";
    RoutingHtml += "<div id='vegvisun_B'><img src='img/routing/routing_marker_B.png'></div><div id='totext'>Til:</div><input id='routing_to_addr' type='text'>";
    RoutingHtml += "<img id='vegvisun_divider2' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
    RoutingHtml += "<img id='routingLoading' src='img/gui/loading_16x16_trans.gif' width='16' height='16' /><div id='routingLoadingTxt'>...reikna út vegvísun.</div><input id='clear_routing_button' class='hidden' type='button' value='Hreinsa' onclick='clearRoutingResults();'><input id='routing_button' type='button' value='Finna leið' onclick='clearZips();getRoute();'>";
    //Insert HTML container for routing results
    RoutingHtml += "<div id='RoutingResultsDIV'><div id='rResultsHtml'></div></div>";
    RoutingHtml += "</div>";
    //RoutingHtml += "</div>";
    $j("#routingPanel").append(RoutingHtml);
    
    

    // Búum til nýtt context menu (f. hægrismell)
    var cuztomContextMenu = "<!-- The second Menu(Special) --><ul class=context-menu id=special-menu>";
    cuztomContextMenu += "<li><a href=javascript:contextZoomIn();><div class='contextMenuLiDiv'><div class='contextMenuIconDiv'><img class='ContextIconZoomInImg' src='img/clearpix.gif' /></div><div class='contextMenuTextDiv'>Þysja inn</div></div></a></li>";
    cuztomContextMenu += "<li><a href=javascript:contextZoomOut();><div class='contextMenuLiDiv'><div class='contextMenuIconDiv'><img class='ContextIconZoomOutImg' src='img/clearpix.gif' /></div><div class='contextMenuTextDiv'>Þysja út</div></div></a></li>";
    cuztomContextMenu += "<li><a href=javascript:centerMapHere();><div class='contextMenuLiDiv'><div class='contextMenuIconDiv'><img class='ContextIconCenterHereImg' src='img/clearpix.gif' /></div><div class='contextMenuTextDiv'>Miðja kort hér</div></div></a></li>";
    cuztomContextMenu += "<li><a href=javascript:routeFromHere();><div class='contextMenuLiDiv'><div class='contextMenuIconDiv'><img class='ContextIconRouteFromImg' src='img/clearpix.gif' /></div><div class='contextMenuTextDiv'>Vegvísun frá</div></div></a></li>";
    cuztomContextMenu += "<li><a href=javascript:routeToHere();><div class='contextMenuLiDiv'><div class='contextMenuIconDiv'><img class='ContextIconRouteToImg' src='img/clearpix.gif' /></div><div class='contextMenuTextDiv'>Vegvísun til</div></div></li></a></ul>";
    $j("body").append(cuztomContextMenu); // Öddum þessu á domið

    ContextMenu.set("special-menu", "map");

    // Display select button in the toolbar
    //document.getElementById("tbSelect").style.visibility = 'visible';

     //switchCommands('DragPan');

    /* var clickWMSFeature = new OpenLayers.Control.Click();
     map.addControl(clickWMSFeature);
     clickWMSFeature.activate(); */

     // höfum höndina á default
     //document.body.style.cursor='pointer';
     
     markers.setZIndex(10000); // svo að markerar komi alltaf ofan á.
     
     
   // To handle Enter keypress 
   $j('#routing_to_addr').keyup(
        function (e)
        {
            if (e.keyCode == 13) //Enter
            { 
                if ( $j('#routing_to_addr').val() != '' )
                {
                    if ( $j('#routing_from_addr').val() != '' )
                    {
                        // ----- Let's route!! ------
                        // make sure inputbox background color is white
                        $j('#routing_from_addr').css( 'background-color', '#FFFFFF' );
                        $j('#routing_to_addr').css( 'background-color', '#FFFFFF' );
                        $j("#rResultsHtml").html("");
                        clearZips();
                        getRoute();
                    }
                    
                    else
                    {
                        $j('#routing_from_addr').css( 'background-color', '#FFDDDD' ); //pink warning background color
                        $j('#routing_from_addr').focus();
                    }
                }
                
            }
        }
    )
        
    $j('#routing_from_addr').keyup(
        function (e)
        {
            if (e.keyCode == 13) //Enter
            { 
                if ( $j('#routing_from_addr').val() != '' )
                {
                    if ( $j('#routing_to_addr').val() != '' )
                    {
                        // ----- Let's route!! ------
                        // make sure inputbox background color is white
                        $j('#routing_from_addr').css( 'background-color', '#FFFFFF' );
                        $j('#routing_to_addr').css( 'background-color', '#FFFFFF' );
                        $j("#rResultsHtml").html("");
                        clearZips();
                        getRoute();
                    }
                    else
                    {
                        $j('#routing_to_addr').css( 'background-color', '#FFDDDD' ); //pink warning background color
                        $j('#routing_to_addr').focus();
                    }
                }
                
            }
        }
    )
     
}


// Býr til javascript object fyrir vegvísun sem inniheldur öll helstu gildi
function routingDataObject(roadname, length,speed,vegnumer,sdirection,tdirection,yfirbord,klassi,roundabout,oneway,source,target,source2target)
{
	this.roadname=roadname;
	this.length=length;
	this.speed=speed;
	this.vegnumer=vegnumer;
	this.sdirection=sdirection;
	this.tdirection=tdirection;
	this.yfirbord=yfirbord;
	this.klassi=klassi;
	this.roundabout=roundabout;
	this.oneway=oneway;
	this.source=source;
	this.target=target;
	this.source2target=source2target; // true s->t  - false t->s - til að vita hvernig við erum að ferðast í gegnum legginn
}

function getRoute()
{

        //Hreinsa gamlar niðurstöður
	vectors.destroyFeatures();
        markers.clearMarkers();

	// From:
	if (chosenFromZip == "")
	{
		var fromZips = new Array();
		// prófum þetta helvíti - frá
		fromZips = saekjaPostnumer($j("#routing_from_addr").val(), "from");
		/*if (from_city == "nada")
		{
			fromZips = checkAddressUnique( escape( $j("#routing_from_addr").val() ) );
		}*/
		if (fromZips.length > 1)
		{
			htmlZippoz = "<b><font color=red >ATH!</font></b> Heimilisfangið <b>" + $j("#routing_from_addr").val()  + "</b> fannst á " + fromZips.length + " stöðum. Þrengið leitina með því að velja póstnúmer.<br>";
			for( i=0; i < fromZips.length; i++)
			{ 
				var svf = fromZips[i][2]; // Þéttbýlisstaður
				if (svf == "")
					svf = fromZips[i][1]; // Ef ekki þéttblisstaður þá birta sveitarfélagsnafn
				//htmlZippoz += "<input type=radio name=fromZipRadio value=" + fromZips[i][0] + " onclick='javascript:chooseFromZip("+fromZips[i][0]+")'>" + fromZips[i][0] + " " + svf + "</input><br>";
                                htmlZippoz += "<input type=radio name=fromZipRadio value=" + fromZips[i][0] + " onclick='javascript:chooseFromZip("+fromZips[i][0]+","+fromZips[i][3]+")'>" + fromZips[i][0] + " " + svf + "</input><br>";
			}
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");
			return;
		}
		else if (fromZips.length == 1)
		{
			selectFromPoint(fromZips[0]);
		}
		else
		{
			// Ekkert fannst
			// Birta skilaboð í niðurstöðureitinn og hætta
			$j("#routing_from_addr").css("background-color","#FFDDDD");
			htmlZippoz = "<b><font color=red >ATH!</font></b> Heimilisfangið ''" + $j("#routing_from_addr").val() + "'' fannst ekki <b><br>";
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");

			return; // No need to seach further from address could not be located
		}
	}


	// To:
	if (chosenToZip == "")
	{
		var toZips = new Array();
		// prófum þetta helvíti - frá
		toZips = saekjaPostnumer($j("#routing_to_addr").val(), "to");
		if (toZips.length > 1)
		{
			htmlZippoz = "<b><font color=red >ATH!</font></b> Heimilisfangið <b>" + $j("#routing_to_addr").val()  + "</b> fannst á " + toZips.length + " stöðum. Þrengið leitina með því að velja póstnúmer.<br>";
			for( i=0; i < toZips.length; i++)
			{
				var svf = toZips[i][2]; // Þéttbýlisstaður
				if (svf == "")
					svf = toZips[i][1]; // Ef ekki þéttbýlisstaður þá birta sveitarfélagsnafn
				//htmlZippoz += "<input type=radio name=fromZipRadio value=" + toZips[i][0] + " onclick='javascript:chooseToZip("+toZips[i][0]+")'>" + toZips[i][0] + " " + svf + "</input><br>";
                                htmlZippoz += "<input type=radio name=fromZipRadio value=" + toZips[i][0] + " onclick='javascript:chooseToZip("+toZips[i][0]+","+toZips[i][3]+")'>" + toZips[i][0] + " " + svf + "</input><br>";
			}
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");
			return;
		}
		else if (toZips.length == 1)
		{
			selectToPoint(toZips[0]);

		}
		else
		{
			// Ekkert fannst
			// Birta skilaboð í niðurstöðureitinn og hætta
			$j("#routing_to_addr").css("background-color","#FFDDDD");
			htmlZippoz = "<b><font color=red >ATH!</font></b> Heimilisfangið ''" + $j("#routing_to_addr").val() + "'' fannst ekki <b><br>";
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");
			return; // Ekki þörf á að halda áfram þar sem heimilisfang fannst ekki
		}
	}

	// Jæja nú ættu leitarskilyrðin að vera klár til að sækja leiðina
	//getRoutePath(submitFromAddr,chosenFromZip,submitToAddr,chosenToZip);
        getRoutePathClick();
        
        //Birta Hreinsa takkann
        $j("#clear_routing_button").removeClass('hidden').html('');
        $j("#clear_routing_button").addClass('visible').html('');
        
 }
 
function clearRoutingResults()
{
    //Hreinsa gamlar niðurstöður
    vectors.destroyFeatures();
    markers.clearMarkers();
    clearZips();
    // Hreinsa Akstursleiðbeiningar
    $j("#rResultsHtml").html("");
    // Fela Akstursleiðbeiningaglugga
    $j("#RoutingResultsDIV").css("visibility","visible");
    //Birta Hreinsa takkann
    $j("#clear_routing_button").removeClass('hidden').html('');
    $j("#clear_routing_button").addClass('visible').html('');
    
    //Fela Hreinsa takkann
    $j("#clear_routing_button").removeClass('visible').html('');
    $j("#clear_routing_button").addClass('hidden').html('');
}

function selectFromPoint(pointInfoArr)
{
    chosenFromZip = pointInfoArr[0];
    $j("#rResultsHtml").html("");
    $j("#routing_from_addr").css("background-color","#FFFFFF");
    $j("#RoutingResultsDIV").css("visibility","hidden");

    // GeoJSON to OL object
    var in_options = {
                'internalProjection': map.baseLayer.projection,
                'externalProjection': new OpenLayers.Projection("EPSG:3057")
            };
    var gj = new OpenLayers.Format.GeoJSON(in_options);
    var startpoint = gj.read(pointInfoArr[3])[0].geometry.components[0]; // Reads the first point out of the multipoint return object
    var lonlat  = new OpenLayers.LonLat(startpoint.x,startpoint.y); // Gather endpoint coordinates
    routeFromHereCoords = lonlat; // Assign the endpoint coordiantes to global variable 'routeToHereCoords' (used for the routing)
    //Add marker for start point . First we check if there is already a "routeFrom" marker
    var marker = getMarkerByName("routeFrom");
    if (typeof(marker) == "undefined" || marker == "") {
            var size = new OpenLayers.Size(24, 27);
            var offset = new OpenLayers.Pixel(-10, -27);
            var icon = new OpenLayers.Icon('img/routing/routing_marker_A.png', size, offset);
            marker = new OpenLayers.Marker(lonlat, icon);
            marker.name = "routeFrom";
            markers.addMarker(marker);
    }
    else{
            marker.moveTo(lonlat);
    }
}

function selectToPoint(pointInfoArr)
{
    chosenToZip = pointInfoArr[0];
    $j("#rResultsHtml").html("");
    $j("#routing_to_addr").css("background-color","#FFFFFF");
    $j("#RoutingResultsDIV").css("visibility","hidden");

    // GeoJSON to OL object
    var in_options = {
                'internalProjection': map.baseLayer.projection,
                'externalProjection': new OpenLayers.Projection("EPSG:3057")
            };
    var gj = new OpenLayers.Format.GeoJSON(in_options);
    var endpoint = gj.read(pointInfoArr[3])[0].geometry.components[0]; // Reads the first point out of the multipoint return object
    var lonlat  = new OpenLayers.LonLat(endpoint.x,endpoint.y); // Gather endpoint coordinates
    routeToHereCoords = lonlat; // Assign the endpoint coordiantes to global variable 'routeToHereCoords' (used for the routing)
    //Add marker for end point . First we check if there is already a "routeTo" marker
    var marker = getMarkerByName("routeTo");
    if (typeof(marker) == "undefined" || marker == "") {
            var size = new OpenLayers.Size(24, 27);
            var offset = new OpenLayers.Pixel(-10, -27);
            var icon = new OpenLayers.Icon('img/routing/routing_marker_B.png', size, offset);
            marker = new OpenLayers.Marker(lonlat, icon);
            marker.name = "routeTo";
            markers.addMarker(marker);
    }
    else{
            marker.moveTo(lonlat);
    }
}

 // tekur inn innihaldið úr innsláttarboxunum og finnur út hvað er gata/húsnúmer og hvað er pnr/sveitarf
 // Tekur inn innihald innsláttorboxins og hvort um er að ræða to eða from boxið.
 function saekjaPostnumer(inputAddress , ToFrom)
 {
	var retZips = new Array(); // tekur við póstnúmerum úr uppflettingu

	inputAddress=inputAddress.replace(/\,/g," " ); // strip commas
	inputAddress=inputAddress.replace(/\./g," " ); // strip points
	var addrArr = inputAddress.split(" "); // Splittum addressunni í fylki

	if (addrArr.length == 1) // bara eitt orð  glugganum
	{
		if (ToFrom == "from")
		{ submitFromAddr = inputAddress }
		else
		{ submitToAddr = inputAddress }
		retZips = checkAddressUnique( escape( inputAddress ) );
	}
	else
	{
		// Fleiri en eitt orð eru í innsláttarglugganum
		// Byrjum á að athuga hvort að við fáum niðurstöðu með að leita eftir tveim fysrtu orðunum
		// Áður en við gerum fyrirspurnina skulum við athuga hvort annað orðið er ekki örugglega tala
		// og sleppum því að gera kall ef svo er ekki

		if (isNumeric(addrArr[1])) // Ef orð númer 2 er tala
		{
			// Annað orðið er tala og því næsta skref að leita eftir gata + húsnúmer
			retZips = checkAddressUnique( escape( addrArr[0] + " " + addrArr[1] ) );

			if (retZips.length == 0) // Ekkert fannst.  Pórfum þá núna leita eftir zip
			{
				if (ToFrom == "from")
				{ submitFromAddr = addrArr[0] }
				else
				{ submitToAddr = addrArr[0] }
				retZips = checkAddressAndZipUnique( escape( addrArr[0]), escape( addrArr[1] ) ); // IMPLEMENT!
			}
			else
			{
				if (ToFrom == "from")
				{ submitFromAddr = addrArr[0] + " " +  addrArr[1] }
				else
				{ submitToAddr = addrArr[0] + " " + addrArr[1] }
			}
		}
		else
		{
			// Orð nr.2 er úr bókstöfum þ.a. hlýtur að vera svf/þéttb
			if (ToFrom == "from")
			{ submitFromAddr = addrArr[0] }
			else
			{ submitToAddr = addrArr[0] }
			retZips = checkAddressAndZipUnique( escape( addrArr[0]), escape(addrArr[1] ) ); // IMPLEMENT!
		}

		// Nú erum við búin að athuga með niðurstöður úr fyrstu tveimur orðunum
		// Næst er að athuga hvort það eru fleiri orð og hvort þau geta þrengt niðurstöðurnar
		if (addrArr.length > 2)
		{

			if (ToFrom == "from")
			{ submitFromAddr = addrArr[0] + " " +  addrArr[1] }
			else
			{ submitToAddr = addrArr[0] + " " + addrArr[1] }

			if (isNumeric(addrArr[2])) // Ef orð númer 3 er tala þá ætti það að vera póstnúmer
			{
				if (LM_CityFromZips[addrArr[2]] != "undefined") // talan er póstnúmer
				{
					// Ferðumst núna í gegnum niðurstöðufylkið og sjáum hvort við finnum ekki póstnúmerið
					for( i=0; i < retZips.length; i++)
					{
						if (addrArr[2] == retZips[i][0]) // match fannst
						{
							var temp = retZips[i]; // þar sem við fundum rétt póstfang tökum við afrit af því
							retZips = []; // hreinsum arrayið til að losna við það sem ekki á við
							retZips.push(temp); // og setjum póstnúmerið eitt inn
						}
					}
				}
			}
			else // Orð númer 3 er ekki númer og því líkast til svf/thettb
			{
				// Sendum gata + húsn + svf/thettb niður í db
				retZips = checkAddressAndZipUnique( escape( addrArr[0] + " " + addrArr[1]), escape(addrArr[2] ) ); // IMPLEMENT!
			}
		}
		// Við ignorum önnur orð þ.e. 4 og yfir
	}

	// Jæja nú erum við líklega komin með lista af mögulegur addressum
	return retZips;
 }

 function checkAddressAndZipUnique(sValue1, sValue2)
 {
	// Checks if address is unique.  Returns an array of zips if not unique.
	// If address is unique the return array holds one zip.
	// If no matching address is found return zip hold 666!

	var zipArray = new Array();
	//sendSyncAJAXRequest("proxies/leit_proxy.php?sKey=checkAddressAndZipUnique&url=http://geoserver.loftmyndir.is/kortasja/leit/routing_service.php&sValue=" + sValue1+"|"+sValue2+"&remotePage=routing_service");
	sendSyncAJAXRequest("db/routingDB.php?sKey=checkAddressAndZipUnique&url=http://geoserver.loftmyndir.is/kortasja/leit/routing_service.php&sValue=" + sValue1+"|"+sValue2+"&remotePage=routing_service");
        var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("zip").text();
		zippo[1] = $j(this).find("svf_nafn").text();
		zippo[2] = $j(this).find("tettbyliss").text();
                zippo[3] = $j(this).find("the_geom").text();
		zipArray.push( zippo ); // insert zip to array
	});
	if (zipArray.length == 0)
	{
		//zipArray.push( 666 ); // Return 666 if there is no match
	}
	return zipArray;
 }


 function chooseFromZip(zip,geom)
 {
    chosenFromZip = zip;
    from_addr = $j("#routing_from_addr").val();
    from_addr += "," + LM_CityFromZipsNefni[zip];
    $j("#routing_from_addr").val(from_addr);
    $j("#rResultsHtml").html("");
    var arr = new Array();
    arr[0] = zip;
    arr[3] = geom;
    selectFromPoint(arr);
    getRoute();
 }

  function chooseToZip(zip,geom)
 {
	chosenToZip = zip;
	to_addr = $j("#routing_to_addr").val();
	to_addr += "," + LM_CityFromZipsNefni[zip];
	$j("#routing_to_addr").val(to_addr);
	$j("#rResultsHtml").html("");
        var arr = new Array();
        arr[0] = zip;
        arr[3] = geom;
        selectToPoint(arr);
	getRoute();
 }

 function checkAddressUnique(sValue)
 {
	// Checks if address is unique.  Returns an array of zips if not unique.
	// If address is unique the return array holds one zip.
	// If no matching address is found return zip hold 666!

	//((sendSyncAJAXRequest("proxies/leit_proxy.php?sKey=checkUniqueAddress&url=http://geoserver.loftmyndir.is/kortasja/leit/routing_service.php&sValue=" + theAddress + "&remotePage=routing_service", checkAddressCallback);
	var zipArray = new Array();
	//sendSyncAJAXRequest("proxies/leit_proxy.php?sKey=checkUniqueAddress&sValue=" + sValue); //Úrelt
        sendSyncAJAXRequest("db/routingDB.php?sKey=checkUniqueAddress&sValue=" + sValue);
	var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("zip").text();
		zippo[1] = $j(this).find("svf_nafn").text();
		zippo[2] = $j(this).find("tettbyliss").text();
                zippo[3] = $j(this).find("the_geom").text();
		zipArray.push( zippo ); // insert zip to array
	});
	if (zipArray.length == 0)
	{
		//zipArray.push( 666 ); // Return 666 if there is no match
	}
	return zipArray;
 }

 function getZippo(cityname)
 {
	for (i in LM_CityFromZipsNefni)

	{
		var regX = new RegExp(LM_CityFromZipsNefni[i], "gi");
		if ( cityname.match(regX) )
		{
			return i;
		}
	}

	return "nada";
 }

// Sends an AJAX request to routing_proxy with adresses. 
function getRoutePath(addrFrom,zipFrom,addrTo,zipTo)
{
    //Hreinsa gamlar niðurstöður
    vectors.destroyFeatures();
    // Jæja sækja leiðina
    sendAJAXRequest('proxies/deasimple_proxy_test.php?request=route&to_addr=' + escape(addrTo) + '&to_city=' + zipTo + '&from_addr=' + escape(addrFrom) + '&from_city=' + zipFrom + '&remotePage=routing_service',displayResultsGeoJSON);
}

// Sends an AJAX request to simple_php_proxy with coordinates (routeFromHere/routeToHere click)
function getRoutePathClick()
{
	if (routeFromHereCoords != "" && routeToHereCoords != "")
	{
            // Sýna "loading" icon og texta
            $j("#routingLoading").css("visibility","visible");
            $j("#routingLoadingTxt").css("visibility","visible");
        
		//Hreinsa gamlar niðurstöður
		vectors.destroyFeatures();
		// Jæja sækja leiðina
		sendAJAXRequest('proxies/deasimple_proxy.php?request=route&xfrom=' + routeFromHereCoords.lon + '&yfrom=' + routeFromHereCoords.lat + '&xto=' + routeToHereCoords.lon + '&yto=' + routeToHereCoords.lat + '&remotePage=routing_service_click', displayResultsGeoJSON);
	}
}

function displayResultsGeoJSON(resp)
{

    if(xmlHttp.readyState == 4)
    {

        $j("#routingLoading").css("visibility","hidden");
        $j("#routingLoadingTxt").css("visibility","hidden");
    
            // Now we have a positive routing result.  The returned data is delimetered with || (two pipes)
            // First we need to read through the GeoJSON responseText to make an array of features by splitting
            // the response by the "||" delimeter to an array of elements (elements[]).  Each element consists of
            // geometry to be read by GeoJSON and a list of parameters.  Geometry and data are delimetered by two
            // minuses ("--") and need to be split up for further processing (into datapair[]).  The datapair[]
            // variable holds the geometry of each leg in position 0 and data in position 1.  Geometry [0] is put
            // into an array of features (features[]) and data [1] is further split up (by ",") into an array
            // of routingDataObjects (rData[]).   ....complicated, but works!

            /*
             * [rData] object properties
             * 
             * roadname         Name of road
             * length           Road length in meters
             * speed            Maximum road speed
             * vegnumer         Roadnumber from Vegagerdin
             * sdirection       Direction in degrees at source point
             * tdirection       Direction in degrees at target point
             * yfirbord         Road surface
             * klassi           Road class - specified by LM
             * roundabout       Bool - True if road is a roundabout
             * oneway           Bool _ True if road is oneway
             * source           Id of source point
             * target           Id of target point
             * source2target    Bool - True if routing traverses from source to target and false if it's from taget to source.
             */


            // Phase 1: 
            // Create an array to hold each legs info
            var rData = new Array();
            rData = fillRoutingDataArray(xmlHttp.responseText);


            // Phase 2:
            // This function determines the direction that is is travelled through the leg.  Either S->T or T->S(reverse)
            // When we know the direction we can later determine the azimuth and degrees of turn.
            var drivingDirection = []; // Will hold each leg traversing direction
            getDirectionOfEachLeg(rData, drivingDirection);


            //
            // Read routing response - Phase 3
            // Here we need to determine orientation of where we are heading.  We can use the drivingDirection array
            // to calculate actual driving direction.  Since we can calculate direction we can determine if we need
            // to turn (left, right etc.) and put the info into the trunArray[].
            //

            var totalLength = 0;
            var eachRoadLength = 0;
            var rrhtml = "<hr><b><font size=2 color=#003A6B>Akstursleiðbeiningar</font></b><br><br>";
            var previousRoad = new routingDataObject("",0,0,0,0); // Empty to begin with
            var roadcounter = 0;
            var fromRoundabout = false;
            var startDirection = "";
            var heading;


            // Byrjum á að lúppa okkur í gegnum leggjalistann til að finna út hvaða horn tengist hverju og með hve miklu horni
            var turnArray = [];
            function turnObject(endDegree, TurnTxt, reverse)
            {
                this.endDegree = endDegree;  // gráða við enda leggjarins til að reikna út horn við næsta legg
                this.turnTxt = TurnTxt; // texti til að nota í útskrift á leiðarvísun
                this.reverse = reverse; // true ef akstursstefna er T->S
            }

            // Loop through the result array an compare source and target points
            // When done we have another array of same length containing direction, text and travelling direction (regular or reverse)
            for(var j=0; j<rData.length; ++j)
            {

                var turnObj = new turnObject();
                if (j==0) // Fyrsti leggur
                {
                        // Fyrir fyrsta legg athugum við fyrst hvort ekið er eftir teiknaðri akstursstefnu (frá source til target)
                        // Ef akstursstefnan er frá target -> source þurfum breyta stefnunnu sem er gefin upp til samræmis
                        // með því að bæta 180 við töluna eða draga frá eftir hvoru megin við 180° talan er.
                        if( rData[j].source == 0 ) // stefnan er S->T og því ekki öfug
                        {
                                startDirection = parseInt(rData[j].sdirection);
                                turnObj.endDegree = parseInt(rData[j].tdirection); // Endinn er target
                                turnObj.reverse = false;
                        }
                        else // target == 0 - Keyrt í öfuga átt við verðum að snúa gráðutölunni við.
                        {
                                startDirection = rotate180( parseInt(rData[j].tdirection) );
                                turnObj.endDegree = rotate180( parseInt(rData[j].sdirection) ); // Endinn er target
                                turnObj.reverse = true;
                        }
                        //  Now we have the start direction and ca write out direction in human language
                        if (startDirection >= 0 && startDirection < 24 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í norður eftir ";
                        }
                        else if (startDirection >= 24 && startDirection < 69 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í norð-austur eftir ";
                        }
                        else if (startDirection >= 69 && startDirection < 114 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í austur eftir ";
                        }
                        else if (startDirection >= 114 && startDirection < 159 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í suð-austur eftir ";
                        }
                        else if (startDirection >= 159 && startDirection < 204 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í suður eftir ";
                        }
                        else if (startDirection >= 204 && startDirection < 249 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í suð-vestur eftir ";
                        }
                        else if (startDirection >= 249 && startDirection < 294 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í vestur eftir ";
                        }
                        else if (startDirection >= 294 && startDirection < 339 )
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í norð-vestur eftir ";
                        }
                        else if (startDirection >= 339)
                        {
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið í norður eftir ";
                        }
                }
                else
                {
                        // Finnum út úr því í hvora áttina við ökum S->T eða T->S
                        if (turnArray[j-1].reverse == true) // Vegurinn á undan endar á S
                        {
                                if ( rData[j].source == rData[j-1].source) // Núverandi vegur byrjar á S
                                {
                                        startDirection =  parseInt(rData[j].sdirection);
                                        turnObj.endDegree =  parseInt(rData[j].tdirection);
                                        turnObj.reverse = false;
                                }
                                else // núverandi vegur byrjar á T
                                {
                                        startDirection = rotate180(  parseInt(rData[j].tdirection) );
                                        turnObj.endDegree = rotate180( parseInt(rData[j].sdirection) );
                                        turnObj.reverse = true;
                                }
                        }
                        else // Vegurinn á undan endar á T
                        {
                                if ( rData[j].source == rData[j-1].target) // Núverandi vegur byrjar á S
                                {
                                        startDirection =  parseInt(rData[j].sdirection);
                                        turnObj.endDegree =  parseInt(rData[j].tdirection);
                                        turnObj.reverse = false;
                                }
                                else // núverandi vegur byrjar T
                                {
                                        startDirection = rotate180(  parseInt(rData[j].tdirection) );
                                        turnObj.endDegree = rotate180( parseInt(rData[j].sdirection) );
                                        turnObj.reverse = true;
                                }
                        }

                        // Finnum hornið, zimmzalabimm!
                        // We need to calculate angle only after roadname changes and keep it until displayed (when road changes again) 
                        if  (rData[j].roadname != rData[j-1].roadname)
                        {
                            
                            var a = calcAngle( (turnArray[j-1].endDegree), startDirection);
                            turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> ";
                        
                            if ( a < -10 )
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/turn_left.png> ";
                            
                            if ( a > 10 )
                                turnObj.turnTxt = " <img src=http://www.loftmyndir.is/k/img/routing/turn_right.png> ";
                                
                            
                                /*
                                if(rData[j].roundabout == "Y")
                                turnObj.turnTxt += " O ";    */
                            
                        }else
                        {
                            // sama as on last leg since road has not changed
                            turnObj.turnTxt = turnArray[j-1].turnTxt;
                        }

                }
                
                

                // Skilum turn objectinum í turn fylkið
                turnArray.push(turnObj);

            }


            //
            // Read routing response - Phase 4
            // All info should now be gathered so we need to write routing directons to the client(browser) HTML.
            //

            // Skrifum út leiðina í html panellinn
            for(var j=0; j<rData.length; ++j)
            {

                if (j==0)
                {
                    // Setjum inn vegupplýsingar í routing object til að bera saman við næst
                    previousRoad.roadname = rData[j].roadname;
                    previousRoad.length = Math.round(rData[j].length);
                    previousRoad.sdirection = rData[j].sdirection;
                    previousRoad.tdirection = rData[j].tdirection;
                    previousRoad.speed=rData[j].speed;
                    previousRoad.vegnumer=rData[j].vegnumer;
                    previousRoad.sdirection=rData[j].sdirection;
                    previousRoad.tdirection=rData[j].tdirection;
                    previousRoad.yfirbord=rData[j].yfirbord;
                    previousRoad.klassi=rData[j].klassi;
                    previousRoad.roundabout=rData[j].roundabout;
                    previousRoad.oneway=rData[j].oneway;
                    previousRoad.source=rData[j].source;
                    previousRoad.target=rData[j].target;	
                    
                    //console.log(j+". " +rData[j].roadname + " - " + rData[j].length +"m");
                    
                    //eachRoadLength = rData[j].length/1;
                }
                else
                {
                    // Ok leg #2 and the fun begins!
                    
                    // Lets make some boolean variables to make the code easier to read
                    var lastRow = false;
                    var newRoad = false;
                    var enteringRoundabout = false;
                    
                    // Set variables according to current state
                    if(j == rData.length-1)
                        {lastRow = true;}
                    if(rData[j].roadname != previousRoad.roadname)
                        {newRoad = true;}
                    if(previousRoad.roundabout == "N" && rData[j].roundabout == "Y")
                        {enteringRoundabout = true;}             
                    // fromRoundabout is true if we drove through a roundabout before entering the road
                    if (previousRoad.roundabout == "Y")
                        {fromRoundabout = true;}
                    var roundaboutInsert = "";
                    if( fromRoundabout )
                    {
                        roundaboutInsert = "Gegnum hringtorg og út á ";
                    }
                        
                    // We will have two main ways of writing out the direction 
                    // #1 - is normal writeout where we're in the middle of the routing
                    // #2 - is the last row in routing results where we have to write out the current leg in addition to the road we came from

           
                    // #1 - All rows but the last

                    
                    if(!lastRow && newRoad && !fromRoundabout)
                    {
                        // Nýr vegur og vorum ekki í hringtorgi
                        // Nú þurfum við að skrifa úr vegvísun
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + previousRoad.roadname + " ";
                        var finalLength = eachRoadLength/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                        eachRoadLength = 0;
                        //console.log("(!lastRow && newRoad && !fromRoundabout)");
                    }
                    if(!lastRow && !newRoad && !fromRoundabout)
                    {
                         // Sami vegur og ekkert hringtorg
                         // við þurfum að skrifa út ef við erum að fara að aka inn í hringtorg
                         if(enteringRoundabout)
                         {
                            roadcounter++;
                            var legLength = "";
                            rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + previousRoad.roadname + " ";
                            var finalLength = eachRoadLength/1;
                            if (eachRoadLength > 1000){
                                legLength = (finalLength/1000).toFixed(1) + "km";}
                            else{
                                legLength = Math.round(finalLength) + "m";}
                            rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                            eachRoadLength = 0;
                         }
                         //console.log("(!lastRow && !newRoad && !fromRoundabout)");
                    }
                    if(!lastRow && !newRoad && fromRoundabout)
                    {
                         // Sami vegur áfram og við komum úr hringtorgi
                         // Hér þarf ekkert að gera nema að telja áfram
                         //console.log("(!lastRow && !newRoad && fromRoundabout)");
                    }
                    if(!lastRow && newRoad && fromRoundabout)
                    {
                        // Nýr vegur og vorum í hringtorgi
                        // Þar sem við komum úr hringtorgi höldum við áfram að telja og skrifum ekkert
                        if(previousRoad.roundabout != "Y") // flagg um hringtorg virkt en við erum komin í gegn
                         {
                            roadcounter++;
                            var legLength = "";
                            rrhtml +=  roadcounter + ". " + " <img src=http://www.loftmyndir.is/k/img/routing/round_straight.png> " + "Gegnum hringtorg og út á " + previousRoad.roadname + " ";
                            var finalLength = eachRoadLength/1;
                            if (eachRoadLength > 1000){
                                legLength = (finalLength/1000).toFixed(1) + "km";}
                            else{
                                legLength = Math.round(finalLength) + "m";}
                            rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                            eachRoadLength = 0;
                            fromRoundabout = false;
                            roundaboutInsert = "";
                         }
                         //console.log("(!lastRow && newRoad && fromRoundabout)");
                        
                    }
                    
                    // #2 - Last row states
                    // Hérum við komin á síðust niðurstöðulínu úr vegvísun
                    if(lastRow && newRoad && fromRoundabout)
                    {
                        // Nýr vegur og vorum að koma úr hringtorgi
                        // Hér þurfum við að skrifa út fyrri veginn sem við erum á og leggja saman við lengdina á leggjum úr hringtorgi
                        // og skrifa út síðasta legginn
                        // fyrri leggur
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + " <img src=http://www.loftmyndir.is/k/img/routing/round_straight.png> " + roundaboutInsert + previousRoad.roadname + " ";
                        var finalLength = eachRoadLength + rData[j].length/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                        // Seinni leggur
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + turnArray[j].turnTxt + rData[j].roadname + " ";
                        var finalLength =  rData[j].length/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#000;height:1px;background-color:#000'>";
                        
                    }
                    if(lastRow && newRoad && !fromRoundabout)
                    {
                        // Nýr vegur og vorum ekki í hringtorgi.
                        // Hér þurfum við fyrst að skrifa úr vegkaflann á undan og svo stubbinn sem við erum á.
                        // previous legs
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + previousRoad.roadname + " ";
                        var finalLength = eachRoadLength/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                        // Last leg
                        rrhtml +=  roadcounter + ". " + turnArray[j].turnTxt + roundaboutInsert + rData[j].roadname + " ";
                        var finalLength = rData[j].length/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#000;height:1px;background-color:#000'>";
                    }
                    if(lastRow && !newRoad && fromRoundabout)
                    {
                        // Sami vegur og við komum úr hringtorgi.
                        // Hér þurfum við að skrifa út veginn sem við erum á og leggja saman við lengdina á leggjum úr hringtorgi
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + " <img src=http://www.loftmyndir.is/k/img/routing/round_straight.png> " + roundaboutInsert + rData[j].roadname + " ";
                        var finalLength = eachRoadLength + rData[j].length/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#000;height:1px;background-color:#000'>";
                        eachRoadLength = 0;
                    }
                    if(lastRow && !newRoad && !fromRoundabout)
                    {
                        // Síðasti leggur er sami og áður og ekki hringtorg.
                        // Lengdin á fyrri leggjum lögð saman og síðasti vegurinn skrifaður út.
                        roadcounter++;
                        var legLength = "";
                        rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + rData[j].roadname + " ";
                        var finalLength = eachRoadLength + rData[j].length/1;
                        if (eachRoadLength > 1000){
                            legLength = (finalLength/1000).toFixed(1) + "km";}
                        else{
                            legLength = Math.round(finalLength) + "m";}
                        rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                        eachRoadLength = 0;
                    }
                    
                    /*
                    // if roadname has changed and were not in a roundabout ..or we have come to the last row ..or we have entered a roundabout
                    // it's time for writing out directions for road we've been travelling
                    if( (newRoad && !fromRoundabout) || enteringRoundabout || lastRow)
                    {
                        //console.log(rData[j].roadname + " -  Ný eða síðastaa gata => skrifa út");
                        
                        // Það er kominn nýr vegur eða síðasta færsla
                        // skrifum veginn sem við erum með út.
                       
                        var roundaboutInsert = "";
                        
                        if( fromRoundabout )
                        {
                            roundaboutInsert = "Gegnum hringtorg og út á ";
                        }
                        
                        

                        // format length
                        var legLength = "";
                        
                        if(!newRoad && lastRow && !fromRoundabout) // We are on last leg and previous leg has same name as the current one
                        {
                            roadcounter++;
                            rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + previousRoad.roadname + " ";
                            var finalLength = eachRoadLength + rData[j].length/1;
                            if (eachRoadLength > 1000){
                                legLength = (finalLength/1000).toFixed(1) + "km";
                            }
                            else
                            {
                                legLength = Math.round(finalLength) + "m";
                            }
                            rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                        }else
                        {                            
                            if (!fromRoundabout)
                            {
                                roadcounter++;
                                rrhtml +=  roadcounter + ". " + turnArray[j-1].turnTxt + roundaboutInsert + previousRoad.roadname + " ";
                                if (eachRoadLength > 1000){
                                    legLength = (eachRoadLength/1000).toFixed(1) + "km";
                                }
                                else
                                {
                                    legLength = Math.round(eachRoadLength) + "m";
                                }
                                rrhtml += " " + legLength + "<HR witdth='80%' style='border:0;color:#DDD;height:1px;background-color:#DDD'>";
                            }
                        }

                       

                        previousRoad.length = Math.round(rData[j].length);
                        
                        // Last road in array and road and last road has different name
                        if (newRoad && lastRow)
                        {
                            roadcounter++;
                            
                            var legLength = "";
                            
                            if(fromRoundabout) 
                            {
                                // We need to add roundabout lenght and info
                                rrhtml +=  roadcounter + ". "+ roundaboutInsert + rData[j].roadname;
                                var legAndRoundaboutLength = rData[j].length/1 + eachRoadLength/1;
                                if (legAndRoundaboutLength > 1000)
                                    {legLength = (legAndRoundaboutLength/1000).toFixed(1) + " km";}
                                else
                                    {legLength = Math.round(legAndRoundaboutLength) + " m";}
                            }
                            else
                            {
                                rrhtml +=  roadcounter + ". " + rData[j].roadname;
                                if (rData[j].length > 1000)
                                    {legLength = (rData[j].length/1000).toFixed(1) + " km";}
                                else
                                    {legLength = Math.round(rData[j].length) + " m";}
                            }
                            
                                
                            rrhtml += " " + legLength + "<HR witdth=80%>";
                        } 
                        
                        fromRoundabout = false;
                        
                        eachRoadLength = 0;

                    }
                    else
                    {
                        // Við erum enn á sama legg og leggjum bara saman vegalengdina
                        //eachRoadLength += rData[j-1].length/1;
                        //console.log(j + ". " + previousRoad.roadname + " - leg length " + rData[j].length + " -total:" + previousRoad.length + " fjoldi: " + rData.length);
                    }*/
                    
                    var hringtxt = "";
                    if (rData[j].roundabout == "Y")
                        hringtxt = " - hringtorg"; 
                    //console.log(j+". " +rData[j].roadname + " - " + rData[j].length +"m /x " + (eachRoadLength/1+rData[j].length/1) + "m (" + Math.round((totalLength+(rData[j].length/1))) + "m)" + hringtxt);

                    // Fetch info into a routing object for comparison with 
                    previousRoad.roadname = rData[j].roadname;
                    //previousRoad.length = Math.round(rData[j].length);
                    previousRoad.sdirection = rData[j].sdirection;
                    previousRoad.tdirection = rData[j].tdirection;
                    previousRoad.speed=rData[j].speed;
                    previousRoad.vegnumer=rData[j].vegnumer;
                    previousRoad.sdirection=rData[j].sdirection;
                    previousRoad.tdirection=rData[j].tdirection;
                    previousRoad.yfirbord=rData[j].yfirbord;
                    previousRoad.klassi=rData[j].klassi;
                    previousRoad.roundabout=rData[j].roundabout;
                    previousRoad.oneway=rData[j].oneway;
                    previousRoad.source=rData[j].source;
                    previousRoad.target=rData[j].target;
                }

                totalLength += ((rData[j].length)/1);
                eachRoadLength += ((rData[j].length)/1);
            }

            var roundTotalLength;

            if (totalLength > 1000){
                roundTotalLength = (Math.round(totalLength)/1000).toFixed(1) + "km";
            }
            else
            {
                roundTotalLength = Math.round(totalLength) + "m";
            }

            rrhtml += "Heildarlengd " + roundTotalLength ;

            //alert("Heildarlengd: " + (Math.round(totalLength)/1000).toFixed(1) + " km");
            $j("#rResultsHtml").html(rrhtml);
            $j("#RoutingResultsDIV").css("visibility","visible");
            rData = [];
    }
}


function calcAngle(firstAngle, secondAngle)
{
    var difference = secondAngle - firstAngle;
    while (difference < -180) 
        {difference += 360}
    while (difference > 180) 
        {difference -= 360}
    return difference;
}


function getDirectionOfEachLeg( rData, drivingDirection )
{
    //
    // Read routing response - Phase 2
    // Runs through the path and determines driving direction, if we are travelling from source to target or wise versa.
    // Array drivingDirection[] holds direction info as bool, true for S->T and false for T->S
    //

    // Setjum upplýsingar um í hvaða röð er ferðast í gegnum vertexana til að geta áttað okkur á akstursstefnu.
    var vertexholderS = "";
    var vertexholderT = "";
    for(var j=0; j<rData.length; ++j)
    {
        if ( vertexholderS == rData[j].source )
        {
                // source vertex á fyrri legg tengist source vertex á þeim sem við erum að skoða
                // Það þýðir að leggurinn á undan var gegn akstursstefnu en sá sem við erum að skoðs er í akstursstefnu
                drivingDirection[j-1] = false;
                if (j == rData.length-1)
                        drivingDirection[j-1] = true; //Síðasta umferð
        }

        if ( vertexholderS == rData[j].target )
        {
                // source vertex á fyrri legg tengist source vertex á þeim sem við erum að skoða
                // Það þýðir að báðir leggirnir eru gegn akstursstefnu
                drivingDirection[j-1] = false;
                if (j == rData.length-1)
                        drivingDirection[j-1] = false; //Síðasta umferð
        }
        if ( vertexholderT == rData[j].source )
        {
                // source vertex á fyrri legg tengist source vertex á þeim sem við erum að skoða
                // Það þýðir að báðir leggirnir eru í akstursstefnu
                drivingDirection[j-1] = true;
                if (j == rData.length-1)
                        drivingDirection[j-1] = true; //Síðasta umferð
        }

        if ( vertexholderT == rData[j].target )
        {
                // source vertex á fyrri legg tengist source vertex á þeim sem við erum að skoða
                // Það þýðir að leggurinn á undan var í akstursstefnu en ekki sá sem við erum að skoða
                drivingDirection[j-1] = true;
                if ( j == rData.length-1)
                        drivingDirection[j-1] = false; //Síðasta umferð
        }

        vertexholderS = rData[j].source;
        vertexholderT = rData[j].target;
    }
}


function fillRoutingDataArray(responseText)
{
//
        // Read routing response - Phase 1
        // Read gemetry into vectors layer and metadata into array of metadata ( rData[] )
        //
        var in_options = {
                            'internalProjection': map.baseLayer.projection,
                            'externalProjection': new OpenLayers.Projection("EPSG:3057")
                        };
        var gj = new OpenLayers.Format.GeoJSON(in_options);
        var elements = responseText.split("||");
        var features = [];
        var rData = [];
        for(i = 0;i<elements.length;i++)
        {
                // datapair is geom and other data 0 being the geometry and 1 the rest (roadname, length etc.)
                var datapair = elements[i].split("|--|");
                features.push(gj.read(datapair[0])[0]);  //  gj reader turns text into OpenLayers feature object witch is then inserted into the features array

                // Routing metadata is read into an array of routingDataObjects (rData[])
                var databits = datapair[1].split(",");
                var rObj = new routingDataObject(databits[0],databits[1],databits[2],databits[3],databits[4],databits[5],databits[6],databits[7],databits[8],databits[9],databits[10],databits[11]);
                rData.push(rObj);
        }
        // Now we have two arrays of same length, features and rData.

        
        // Here we insert the features to a layer and calculate bounds for zooming in or out to the routing result
        var bounds;
        if(features)
        {
                if(features.constructor != Array)
                {
                        features = [features];
                }
                for(var i=0; i<features.length; ++i) {
                        if (!bounds)
                        {
                                // Here we need to make bounds from the first feature
                                bounds = features[i].geometry.getBounds();
                        }
                        else
                        {
                                // This will extend the bounds we already have to include the feature in the loop.
                                bounds.extend(features[i].geometry.getBounds());
                        }
                }
                vectors.addFeatures(features); // Add featuers to layer
                map.zoomToExtent(bounds); // Zoom to routing bounds

                return rData;
        }
        else
        {
                alert('Engir vegir i vegvisun');
                return null;
        }
}


// ************* Hjálparföll fyrir vegvísun ******************************************
// Hreinsar globalbreytur sem geyma póstnúmer
function clearZips()
{
	chosenFromZip = "";
	chosenToZip = "";
}

// Sækir marker eftir nafni (routing markerar - frá - til)
function getMarkerByName(markerName)
{
	for (i=0; i < markers.markers.length; i++)
	{
		if(markers.markers[i].name == markerName)
			return markers.markers[i];
	}
        return ""; // skilar tómastreng ef engir markerar fundust
}

// Notað til að snúa um 180°
function rotate180(input)
{
	if( input  > 180 )
		return input-180;
	else
		return input+180;
}

//  ********* Föll sem snúa að "Contex menu" (hægrismell) ******************************
// Function to handle "Route from here" on right mouse click on map (custom) context menu
// Finds road vertext close to where the user clicked to route from (start point)
function routeFromHere()
{
	var maptop = $j('#map').position().top; //$j('#map').css('top').replace("px","");
	var mapleft = $j('#map').position().left; //$j('#map').css('left').replace("px","");
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX-mapleft , rightClickMousePosY-maptop) );
	// Global variable to routeFrom location
	routeFromHereCoords = lonlat;

	// returns path is routable
	getRoutePathClick();
	//Add marker for starting point
	// First we check if there is already a "routeFrom" marker
	var marker = getMarkerByName("routeFrom");

	if (typeof(marker) == "undefined" || marker == "") {
		var size = new OpenLayers.Size(24, 27);
		var offset = new OpenLayers.Pixel(-10, -27);
		var icon = new OpenLayers.Icon('img/routing/routing_marker_A.png', size, offset);
		marker = new OpenLayers.Marker(lonlat, icon);
		marker.name = "routeFrom";
		markers.addMarker(marker);
	}
	else{
		//marker.lonlat = lonlat;
		var newPx = map.getLayerPxFromLonLat(lonlat);
		marker.moveTo(newPx);
	}
}
// Function to handle "Route to here" on right mouse click on map (custom) context menu
// Finds road vertext close to where the user clicked to route to (end point)
function routeToHere()
{
	var maptop = $j('#map').position().top;//$j('#map').css('top').replace("px","");
	var mapleft = $j('#map').position().left; //$j('#map').css('left').replace("px","");
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX-mapleft , rightClickMousePosY-maptop) );

	// TODO: Insert marker for routing end point
	// Find nearest road/address
	// Udate "To" field in routing panel
	// Check if "From" field is already filled and do routing automagically if so


	routeToHereCoords = lonlat;

	getRoutePathClick();

	//Add marker for starting point
	// First we check if there is already a "routeTo" marker
	var marker = getMarkerByName("routeTo");

	if (typeof(marker) == "undefined" || marker == "" ) {
		var size = new OpenLayers.Size(24, 27);
		var offset = new OpenLayers.Pixel(-10, -27);
		var icon = new OpenLayers.Icon('img/routing/routing_marker_B.png', size, offset);
		marker = new OpenLayers.Marker(lonlat, icon);
		marker.name = "routeTo";
		markers.addMarker(marker);
	}
	else{
		//marker.lonlat = lonlat;
		var newPx = map.getLayerPxFromLonLat(lonlat);
		marker.moveTo(newPx);
	}
}
// Function to handle "Center map here"  on right mouse click on map (custom) context menu
// Centers the map to where the user clicked
function centerMapHere()
{
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	map.setCenter( lonlat,map.getScale());
}
// Function to handle "Zoom in"  on right mouse click on map (custom) context menu
// Zooms the map in 2 levels and centers the map to where the user clicked
function contextZoomIn()
{
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	map.setCenter( lonlat,(map.getZoom()+2) );
}
// Function to handle "Zoom out"  on right mouse click on map (custom) context menu
// Zooms the map out 2 levels and centers the map to where the user clicked
function contextZoomOut()
{
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	var zoomy = map.getZoom()-2; // Get current zoom level from map minus 2 since we're zooming out
	map.zoomTo(zoomy);
}
<!-- /script -->