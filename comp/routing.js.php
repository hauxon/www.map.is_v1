
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 routing.js.php
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
     layer_style.fillOpacity = 0.2;
     layer_style.graphicOpacity = 1;
     var style_routing = OpenLayers.Util.extend({}, layer_style);
     style_routing.strokeColor = "#bd1776"; // bleikfjólublár
     style_routing.graphicName = "star";
     style_routing.pointRadius = 5;
     style_routing.strokeWidth = 5;
     style_routing.strokeOpacity = 0.99;
     style_routing.strokeLinejoin = "miter";

    // Setjum inn vectorlayer til að birta niðurstöðu vegvísunar á kortinu
     vectors = new OpenLayers.Layer.Vector("Vegvísun", {style: style_routing, 'displayInLayerSwitcher':true});
     //client_layers.push(vectors);
     map.addLayer(vectors);
    //////////////////////////////////////////////////////////////////////////// Vector end


    //Insert HTML Container for Routing
    RoutingHtml = "<div id='RoutingDIV'><img src='img/vegvisun/vegvisun_header_logo.png' id='vegvisun_header_logo'><div id='headerText'>Vegvísun</div>";
    RoutingHtml += "<img id='vegvisun_close_btn' src='img/vegvisun/vegvisun_close.gif'><img id='vegvisun_divider1' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
    RoutingHtml += "<div id='vegvisun_A'><img src='img/vegvisun/vegvisun_A.png'></div><div id='fromtext'>Frá:</div><input id='routing_from_addr' type='text'>";
    RoutingHtml += "<div id='vegvisun_B'><img src='img/vegvisun/vegvisun_B.png'></div><div id='totext'>Til:</div><input id='routing_to_addr' type='text'>";
    RoutingHtml += "<img id='vegvisun_divider2' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
    RoutingHtml += "<input id='routing_button' type='button' value='Finna leið' onclick='clearZips();getRoute();'></div>";
    $j("#sliderPanel").append(RoutingHtml);

    //Insert HTML container for routing results
    RoutingResultsHtml = "<div id='RoutingResultsDIV'>";
    RoutingResultsHtml += "<div id='rResultsHtml'></div>";
    RoutingResultsHtml += "</div>";
    $j("#sliderPanel").append(RoutingResultsHtml);

    // Búum til nýtt context menu (f. hægrismell)
    var cuztomContextMenu = "<!-- The second Menu(Special) --><ul class=context-menu id=special-menu><li><a href=javascript:contextZoomIn();>Þysja inn</a></li>";
    cuztomContextMenu += "<li><a href=javascript:contextZoomOut();>Þysja út</a></li><li><a href=javascript:centerMapHere();>Miðja kort hér</a></li>";
    cuztomContextMenu += "<li><a href=javascript:routeFromHere();>Vegvísun frá</a></li><li><a href=javascript:routeToHere();>Vegvísun til</a></li></ul>";
    $j("body").append(cuztomContextMenu); // Öddum þessu á domið

    ContextMenu.set("special-menu", "map");

    // Display select button in the toolbar
    //document.getElementById("tbSelect").style.visibility = 'visible';

     //switchCommands('DragPan');

    /* var clickWMSFeature = new OpenLayers.Control.Click();
     map.addControl(clickWMSFeature);
     clickWMSFeature.activate(); */

     // höfum höndina á default
     document.body.style.cursor='pointer';
}









 /****************** Click Event ************************************************/
 // Til að nota seinna
 /*var lon;
 var lat;
 OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {

 defaultHandlerOptions: {
     'single': true,
     'double': false,
     'pixelTolerance': 5,
     'stopSingle': false,
     'stopDouble': false
 },

 initialize: function(options) {
     this.handlerOptions = OpenLayers.Util.extend(
     {}, this.defaultHandlerOptions
     );
     OpenLayers.Control.prototype.initialize.apply(
     this, arguments
     );
     this.handler = new OpenLayers.Handler.Click(
     this, {
     'click': this.trigger
     }, this.handlerOptions
     );
 },
 trigger: function(e) {
     var lonlat = map.getLonLatFromViewPortPx(e.xy);
     lon = lonlat.lon/1;
     lat = lonlat.lat/1;

     this.url = wms_routing_vegir.getFullRequestString({
         REQUEST: "GetFeatureInfo",
         EXCEPTIONS: "application/vnd.ogc.se_xml",
         BBOX: map.getExtent().toBBOX(),
         X: e.xy.x,
         Y: e.xy.y,
         INFO_FORMAT: 'text/plain',
         QUERY_LAYERS: 'postgis:routing_vegir',
         FEATURE_COUNT: 5,
         srs: 'EPSG:3057',
         WIDTH: map.size.w,
         HEIGHT: map.size.h},
         "proxies/getFeatureInfoProxy_routing.asp"
     ),
     urlResults = OpenLayers.loadURL(this.url, null, this, this.requestSuccess, this.requestOops);
 },
 requestSuccess: function(request) {
     // ------------requestSuccess - Lesum úr svarinu ---------------------
     //Athuga hvort eitthvað skilaði sér og gera ekkert
     if(request.responseText.indexOf("|noFeatures|") != -1)
     return;

     var linur = request.responseText.split('\n');
     var nidurstodurIndex = -1; // Sett á -1 til þæginda hér að neðan
     var nidurstodur = [[]];
     var nyFaersla = [];// = false;
     var faerslaIndex = 0;
     for (i = 1; i < (linur.length); i++)
     {
         //if ( linur[i] == "--------------------------------------------\r" )
         if ( linur[i].toString().indexOf( "--------------------------------" ) != -1)
         {
             if (nyFaersla == false) // Ef ekki ný færsla
             {
                 nidurstodurIndex++; // index/talning fyrir niðurstöður
                 nyFaersla = true; // Ný færsla byrjar
                 if(nidurstodurIndex != 0)
                 {
                     // stækka fylkið)
                     nidurstodur.push([]);
                 }
             }
             else
             {
                nyFaersla = false; // þar sem nyFaersla er true og strikin komu er henni lokið
             }

             faerslaIndex = 0; // Færsla er alltaf ný eða engin við upphaf og enda
         }
         else
         {
             var breyta = linur[i].split(' = ');

             //Bý til pláss fyrir skemmtilegheitin
             nidurstodur[(nidurstodurIndex)].push([]);

             // Setjum eigindaparið inn í fylkisskrýmslið
             nidurstodur[(nidurstodurIndex)][faerslaIndex].push(breyta[0]);
             nidurstodur[(nidurstodurIndex)][faerslaIndex].push(breyta[1]);

             faerslaIndex++;
         }
     }
     debugger;
     // ------------requestSuccess end ------------------------------------

 },
 requestOops: function(request) {
    alert("Villa kom upp við að sækja gögn frá: "+this.url);
 }

 })*/






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
            var size = new OpenLayers.Size(43, 35);
            var offset = new OpenLayers.Pixel(-10, -27);
            var icon = new OpenLayers.Icon('img/routing/LM_routing_markerA.png', size, offset);
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
            var size = new OpenLayers.Size(43, 35);
            var offset = new OpenLayers.Pixel(-10, -27);
            var icon = new OpenLayers.Icon('img/routing/LM_routing_markerB.png', size, offset);
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
	//debugger;
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
	sendSyncAJAXRequest("proxies/leit_proxy.php?sKey=checkAddressAndZipUnique&url=http://geoserver.loftmyndir.is/kortasja/leit/routing_service.php&sValue=" + sValue1+"|"+sValue2+"&remotePage=routing_service");
	var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("postnumer").text();
		zippo[1] = $j(this).find("sveitarfel").text();
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
	sendSyncAJAXRequest("proxies/leit_proxy.php?sKey=checkUniqueAddress&sValue=" + sValue);
	var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("postnumer").text();
		zippo[1] = $j(this).find("sveitarfel").text();
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

                // Now we have a positive routing result.  The returned data is delimetered with || (two pipes)
                // First we need to read through the GeoJSON responseText to make an array of features by splitting
                // the response by the "||" delimeter to an array of elements (elements[]).  Each element consists of
                // geometry to be read by GeoJSON and a list of parameters.  Geometry and data are delimetered by two
                // minuses ("--") and need to be split up for further processing (into datapair[]).  The datapair[]
                // variable holds the geometry of each leg in position 0 and data in position 1.  Geometry [0] is put
                // into an array of features (features[]) and data [1] is further split up (by ",") into an array
                // of routingDataObjects (rData[]).   ....complicated, but works!

                //
                // Read routing response - Phase 1
                // Read gemetry into vectors layer and metadata into array of metadata ( rData[] )
                //
		var in_options = {
                                    'internalProjection': map.baseLayer.projection,
                                    'externalProjection': new OpenLayers.Projection("EPSG:3057")
                                };
		var gj = new OpenLayers.Format.GeoJSON(in_options);
		var elements = xmlHttp.responseText.split("||");
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
			vectors.addFeatures(features);
			map.zoomToExtent(bounds);
		}
		else
		{
			alert('Engir vegir i vegvisun');
		}


                //
                // Read routing response - Phase 2
                // Runs through the path and determines driving direction, if we are travelling from source to target or wise versa.
                // Array drivingDirection[] holds direction info as bool, true for S->T and false for T->S
                //

		// Setjum upplýsingar um í hvaða röð er ferðast í gegnum vertexana til að geta áttað okkur á akstursstefnu.
		var drivingDirection = [];
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

		//
                // Read routing response - Phase 3
                // Here we need to determine orientation of where we are heading.  We can use the drivingDirection array
                // to calculate actual driving direction.  Since we can calculate direction we can determine if we need
                // to turn (left, right etc.) and put the info into the trunArray[].
                //

		var totalLength = 0;
		var rrhtml = "<hr><b><font size=2 color=#003A6B>Akstursleiðbeiningar</font></b><br><br>";
		var totalRoad = new routingDataObject("",0,0,0,0); // Empty to begin with
		var roadcounter = 0;
		var startDirection = "";
		var heading;

		// ------------------------------- Orientation array end ---------------------------------- //
		// Byrjum á að lúppa okkur í gegnum leggjalistann til að finna út hvaða horn tengist hverju og með hve miklu horni
                var turnArray = [];
		function turnObject(endDegree, TurnTxt, reverse)
		{
			this.endDegree = endDegree;  // gráða við enda leggjarins til að reikna út horn við næsta legg
			this.turnTxt = TurnTxt; // texti til að nota í útskrift á leiðarvísun
			this.reverse = reverse; // true ef akstursstefna er T->S
		}
		for(var j=0; j<rData.length; ++j)
		{
			//debugger;
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
					turnObj.turnTxt = "Ekið í norður eftir ";
				}
				else if (startDirection >= 24 && startDirection < 69 )
				{
					turnObj.turnTxt = "Ekið í norð-vestur eftir ";
				}
				else if (startDirection >= 69 && startDirection < 114 )
				{
					turnObj.turnTxt = "Ekið í vestur eftir ";
				}
				else if (startDirection >= 114 && startDirection < 159 )
				{
					turnObj.turnTxt = "Ekið í suð-vestur eftir ";
				}
				else if (startDirection >= 159 && startDirection < 204 )
				{
					turnObj.turnTxt = "Ekið í suður eftir ";
				}
				else if (startDirection >= 204 && startDirection < 249 )
				{
					turnObj.turnTxt = "Ekið í suð-austur eftir ";
				}
				else if (startDirection >= 249 && startDirection < 294 )
				{
					turnObj.turnTxt = "Ekið í austur eftir ";
				}
				else if (startDirection >= 294 && startDirection < 339 )
				{
					turnObj.turnTxt = "Ekið í norð-austur eftir ";
				}
				else if (startDirection >= 339)
				{
					turnObj.turnTxt = "Ekið í norður eftir ";
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
				var a = (turnArray[j-1].endDegree)-startDirection;
				if (a > 180)
                                {
                                    a = a-360; // maður beygir aldrei meira en 180 gráður í sömu átt
                                }
				/*if (Math.abs(a) <= 10)
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið <b>áfram</b> eftir ";
                                }
				if ( a > 10 )
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_left.png> Taktu <b>vinstri</b> beygju inn á ";
                                }
				if ( a < -10 )
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_right.png> Taktu <b>hægri</b> beygju inn á ";
                                }*/
                                if (Math.abs(a) <= 10)
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> ";
                                }
				if ( a > 10 )
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_left.png> ";
                                }
				if ( a < -10 )
                                {
                                    turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_right.png> ";
                                }

				//turnObj.turnTxt = a;
			}

			// Skilum turn objectinum í turn fylkið
			turnArray.push(turnObj);
		}

		// ------------------------------- Orientation array end ---------------------------------- //


                //
                // Read routing response - Phase 4
                // All info shoundd now be gathered so we need to write routing directons to the client(browser) HTML.
                //

		// Skrifum út leiðina í html panellinn
		for(var j=0; j<rData.length; ++j)
		{
			if (j==0)
			{
				// Setjum inn vegupplýsingar í routing object til að bera saman við næst
				totalRoad.roadname = rData[j].roadname;
				totalRoad.length = Math.round(rData[j].length);
				totalRoad.sdirection = rData[j].sdirection;
				totalRoad.tdirection = rData[j].tdirection;
				totalRoad.speed=rData[j].speed;
				totalRoad.vegnumer=rData[j].vegnumer;
				totalRoad.sdirection=rData[j].sdirection;
				totalRoad.tdirection=rData[j].tdirection;
				totalRoad.yfirbord=rData[j].yfirbord;
				totalRoad.klassi=rData[j].klassi;
				totalRoad.roundabout=rData[j].roundabout;
				totalRoad.oneway=rData[j].oneway;
				totalRoad.source=rData[j].source;
				totalRoad.target=rData[j].target;
				/*
				if( rData[j].source == 0 ) // stefnan er S->T og því ekki öfug
				{
					heading = parseInt(rData[j].sdirection);
					//alert ("-> Source - stefna:" + " - tdirection:" + rData[j].stdirection);
					totalRoad.source2target = true;
				}
				else
				{
					totalRoad.source2target = false;
					if(  parseInt(rData[j].tdirection)  > 180 )
					{
						heading = (parseInt(rData[j].tdirection))-180;
						//alert ("-> Target - stefna:" + heading + " - tdirection:" + rData[j].tdirection);
					}
					else
					{
						heading = (parseInt(rData[j].tdirection))+180;
						//alert ("-> Target - stefna:" + heading + " - tdirection:" + rData[j].tdirection);
					}
				}

				if (heading >= 0 && heading < 23 )
				{
					//alert(rData[j].sdirection);
					startDirection = "norður";
				}
				else if (heading >= 24 && heading < 68 )
				{
					startDirection = "norð-vestur";
				}
				else if (heading >= 69 && heading < 113 )
				{
					startDirection = "vestur";
				}
				else if (heading >= 114 && heading < 158 )
				{
					startDirection = "suð-vestur";
				}
				else if (heading >= 159 && heading < 203 )
				{
					startDirection = "suður";
				}
				else if (heading >= 204 && heading < 248 )
				{
					startDirection = "suð-austur";
				}
				else if (heading >= 249 && heading < 293 )
				{
					startDirection = "austur";
				}
				else if (heading >= 294 && heading < 338 )
				{
					startDirection = "norð-austur";
				}
				else if (heading >= 339)
				{
					startDirection = "norður";
				}

				//log(j + ". " +totalRoad.roadname + " - leg length " + rData[j].length + " -total:" + totalRoad.length +  "  -- Direction: " + rData[j].sdirection ); */
			}
			else{
				//debugger;
				if (rData[j].roadname != totalRoad.roadname)
				{
					// Það er kominn nýr vegur eða síðasta færsla
					// skrifum veginn sem við erum með út.
					roadcounter++;
					/*
					var beygjuhorn;
					// Finnum út akstursstefnu og úr fyrri vektor og berum saman við núverandi
					if (totalRoad.source2target)
					{
						// source2target segir okkur að fyrri leggur var S->T og target því tengipunktur okkar við núverandi línu
						if(totalRoad.target == rData[j].source)
						{
							// Núverandi lína er líka S->T
							beygjuhorn = Math.abs(totalRoad.tdirection-rData[j].sdirection);
							if (beygjuhorn > 180)
							{
								beygjuhorn = 360-beygjuhorn;
								alert("Frá " +totalRoad.roadname + " til hægri " + beygjuhorn + "°");
							}
							else
							{
								alert("Frá " +totalRoad.roadname + " til vinstri " + beygjuhorn + "°");
							}
						}
						else
						{
							// Núverandi lína er T->S
							beygjuhorn = Math.abs(totalRoad.tdirection-rData[j].tdirection);
							if (beygjuhorn > 180)
							{
								beygjuhorn = 360-beygjuhorn;
								alert("Frá " +totalRoad.roadname + " til hægri " + beygjuhorn + "°");
							}
							else
							{
								alert("Frá " +totalRoad.roadname + " til vinstri " + beygjuhorn + "°");
							}
						}
					}
					else
					{
						// source2target segir okkur að fyrri leggur var T->S og source því tengipunktur okkar við núverandi línu
						if(totalRoad.source == rData[j].source)
						{
							// Núverandi lína er S->T
							beygjuhorn = Math.abs(totalRoad.sdirection-rData[j].sdirection);
							if (beygjuhorn > 180)
							{
								beygjuhorn = 360-beygjuhorn;
								alert("Frá " +totalRoad.roadname + " til hægri " + beygjuhorn + "°");
							}
							else
							{
								alert("Frá " + totalRoad.roadname + " til vinstri " + beygjuhorn + "°");
							}
						}
						else
						{
							// Núverandi lína er T->S
							beygjuhorn = Math.abs(totalRoad.sdirection-rData[j].tdirection);
							if (beygjuhorn > 180)
							{
								beygjuhorn = 360-beygjuhorn;
								alert("Frá " +totalRoad.roadname + " til hægri " + beygjuhorn + "°");
							}
							else
							{
								alert("Frá " +totalRoad.roadname + " til vinstri " + beygjuhorn + "°");
							}
						}
					}


					var directionTxt = "";
					if (heading != "done")
					{
						directionTxt = "Aktu í "+ startDirection + " eftir ";
						heading = "done";
					}
					else
					{
						// Beygjur
						var beygja = Math.abs(totalRoad.sdirection-totalRoad.tdirection);
					}*/

					if (totalRoad.roadname != "")
                                        {
                                            rrhtml +=  roadcounter + ". "+ turnArray[j-1].turnTxt + totalRoad.roadname + ", ";
                                        }
                                        else
                                        {
                                            rrhtml +=  roadcounter + ". "+ turnArray[j-1].turnTxt + "Hringtorg" + ", ";
                                        }
                                        // format length
					var legLength = "";
					if (totalRoad.length > 1000){
						legLength = (Math.round(totalRoad.length)/1000).toFixed(1) + "km";
					}
					else
					{
						legLength = Math.round(totalRoad.length) + "m";
					}

					rrhtml += " " + legLength + "<HR witdth=80%>";

					totalRoad.length = Math.round(rData[j].length);

					if ((j+1) == rData.length)
					{
						roadcounter++;
						rrhtml +=  roadcounter + ". " + rData[j].roadname;
						var legLength = "";
						if (totalRoad.length > 1000){
							legLength = (Math.round(totalRoad.length)/1000).toFixed(1) + " km";
						}
						else
						{
							legLength = Math.round(totalRoad.length) + " m";
						}

						rrhtml += " " + legLength + "<HR witdth=80%>";

						totalRoad.length = Math.round(rData[j].length);
					}
				}
				else
				{
					// Við erum enn á sama legg og leggjum bara saman vegalengdina
					totalRoad.length = totalRoad.length + Math.round(rData[j-1].length);
					//log(j + ". " +totalRoad.roadname + " - leg length " + rData[j].length + " -total:" + totalRoad.length + " fjoldi: " + rData.length);
				}



				// Setjum inn uppl um götuna til að nota næst
				totalRoad.roadname = rData[j].roadname;
				totalRoad.length = Math.round(rData[j].length);
				totalRoad.sdirection = rData[j].sdirection;
				totalRoad.tdirection = rData[j].tdirection;
				totalRoad.speed=rData[j].speed;
				totalRoad.vegnumer=rData[j].vegnumer;
				totalRoad.sdirection=rData[j].sdirection;
				totalRoad.tdirection=rData[j].tdirection;
				totalRoad.yfirbord=rData[j].yfirbord;
				totalRoad.klassi=rData[j].klassi;
				totalRoad.roundabout=rData[j].roundabout;
				totalRoad.oneway=rData[j].oneway;
				totalRoad.source=rData[j].source;
				totalRoad.target=rData[j].target;

			}

			totalLength += ((rData[j].length)/1);
			//log("-----lina " + j + " ------- " +  rData[j].roadname + " - leg length " + rData[j].length + " -total:" + totalRoad.length);
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
	var maptop = $j('#map').css('top').replace("px","");
	var mapleft = $j('#map').css('left').replace("px","");
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX-mapleft , rightClickMousePosY-maptop) );
	// Global variable to routeFrom location
	routeFromHereCoords = lonlat;

	// returns path is routable
	getRoutePathClick();

	//Add marker for starting point
	// First we check if there is already a "routeFrom" marker
	var marker = getMarkerByName("routeFrom");

	if (typeof(marker) == "undefined" || marker == "") {
		var size = new OpenLayers.Size(43, 35);
		var offset = new OpenLayers.Pixel(-10, -27);
		var icon = new OpenLayers.Icon('img/routing/LM_routing_markerA.png', size, offset);
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
	var maptop = $j('#map').css('top').replace("px","");
	var mapleft = $j('#map').css('left').replace("px","");
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
		var size = new OpenLayers.Size(43, 35);
		var offset = new OpenLayers.Pixel(-10, -27);
		var icon = new OpenLayers.Icon('img/routing/LM_routing_markerB.png', size, offset);
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

