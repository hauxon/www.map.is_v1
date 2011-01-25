/*****************************
* CHECKLIST
- Logo
 > Should be placed under the /img directory
 > Name should be of the form [client].jpg (yes - that means jpeg)
 > Dimensions of the logo should be 110x44 pixels - no more, no less, no way.
* REQUIRED VARS
 - Start location
 start_lon
 start_lat
 start_zoom (relative to client_scales array)
 - Boolean - Tells the map whether to display these common layers or not
 doVegir
 doOrnefni
 doSveitabaeir
 doGotuheiti
 doHusnumer
- Array of OpenLayers.Layer objects
 client_layers - layers that are specific to this client
/******************************
 * OPTIONAL VARS
- Array of Integers
 client_scales - defaults to all scales
 All scales reference:[1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000,1000,500,250]
- AreaJumpers
 Implement areajumper for client - defaults to Nothing
- Sveitarfelagsnumer
 For the Search to function locally - defaults to the whole of Iceland


******************************/
//This var is set in common.js as false - set to true if you wish to be able to display schematics
hasTeikningar = true;
var panBounds = new OpenLayers.Bounds(234248.88,297273.25,759064.98,686298.38);
var SveitarfelagsNumer = "";
var start_lon = 500000;
var start_lat = 500000;
var start_zoom =0;
var client_scales = [1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0,1000.0,500.0,250.0];
custom_ornefni_scales = [50000.0,25000.0,10000.0, 5000.0, 2000.0, 1000.0, 500.0, 250.0]; // default 6 scalar með örnefni 1700000.0, 1000000.0, 500000.0, 250000.0, 100000.0,
/********************************
* Switches for common layers:
********************************/
var doSvMaski = ""; // tómastrengur fyrir engan maska
var doVegir = false;
var doOrnefni = false;
var doSveitabaeir = true;
var doGotuheiti = true;
var doHusnumer = true;
var doWFS = false;
var doPOI = false;
var disablePOIWFS = true;
var doClientSelectWMS = true; // Select tólið er f. WMS

isCustomBaseMap = false;
isCustomOrnefni = false;

var clientNameNefni = "Loftmyndir";
var clientNameThol = "Loftmyndir";
var clientNameThagu = "Loftmyndum";
var clientNameEignar = "Loftmynda";

/********************************
* Layers particular to client - layers that are potentially common to all clients are defined in ../common.js
********************************/

// Globale veiii!
var routeFromHereCoords = "";
var routeToHereCoords = "";


 var client_layers = [];

 /////////////////// Vector
 /*
 * Layer style
 */
 // we want opaque external graphics and non-opaque internal graphics
 var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
 layer_style.fillOpacity = 0.2;
 layer_style.graphicOpacity = 1;

 /*
 * Blue style
 */
 var style_blue = OpenLayers.Util.extend({}, layer_style);
 style_blue.strokeColor = "#bd1776";
 //style_blue.fillColor = "#bd1776";
 style_blue.graphicName = "star";
 style_blue.pointRadius = 5;
 style_blue.strokeWidth = 5;
 style_blue.strokeOpacity = 0.99;
 //style_blue.rotation = 45;
 style_blue.strokeLinejoin = "miter";
 //style_blue.strokeLinecap = "round";

 var vectors = new OpenLayers.Layer.Vector("Vegvísun", {style: style_blue, 'displayInLayerSwitcher':true});
 client_layers.push(vectors);



 //client_layers.push(new OpenLayers.Layer.Vector("Teikningar", {style: style_blue}))
/////////////////// Vector end


 var lightsaber_scales = [6800000.0,3400000.0,1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0,1000.0]
client_layers.push( new OpenLayers.Layer.WMS("Lightsaber",
 ["http://tc0.loftmyndir.is/tc_wsgi",
 "http://tc1.loftmyndir.is/tc_wsgi",
 "http://tc2.loftmyndir.is/tc_wsgi",
 "http://tc3.loftmyndir.is/tc_wsgi"],
 {layers:'lightsaber',format:'image/jpeg', kortasja: clientNameNefni },
 {
 scales:lightsaber_scales,
 'isBaseLayer':
 true,displayInLayerSwitcher:false,
 attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>',
 transitionEffect:'resize',
 buffer: 1}))

client_layers.push( new OpenLayers.Layer.WMS("Lightsaber PNG",
 ["http://tc0.loftmyndir.is/tc_wsgi",
 "http://tc1.loftmyndir.is/tc_wsgi",
 "http://tc2.loftmyndir.is/tc_wsgi",
 "http://tc3.loftmyndir.is/tc_wsgi"],
 {layers:'lightsaber_png',format:'image/jpeg', kortasja: clientNameNefni },
 {'isBaseLayer': true,displayInLayerSwitcher:false, attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>', transitionEffect:'resize', buffer: 1}))


 /*client_layers.push( new OpenLayers.Layer.WMS.Untiled( "Dijkstra","http://geoserver.loftmyndir.is/geoserver/ows?service=wms",
  {layers:'postgis:dijsktra_result',format:'image/jpeg',transparent: true},
  {'displayInLayerSwitcher':true, 'isBaseLayer':false,'visibility':true})); */


/********************************
* Areajumpers - shortcuts to notable areas
********************************/
//jumparr[AreaLabel,lon,lat,zoom]
jumpArr = [];



var areaJumperHTML = "";//Appended to HTML later on
for(i=0;i<jumpArr.length;i++)
{
 areaJumperHTML+='<a onclick="javascript:zoomTo('+jumpArr[i][1]+', '+jumpArr[i][2]+', '+jumpArr[i][3]+');" href="#">'+jumpArr[i][0]+'</a>';
 if(i!=jumpArr.length-1)
 areaJumperHTML+='<font color="#CCCCCC">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</font>';
}


var submitFromAddr = "";
var chosenFromZip = "";
var submitToAddress = "";
var chosenToZip = "";
function clearZips()
{
	chosenFromZip = "";
	chosenToZip = "";
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
				htmlZippoz += "<input type=radio name=fromZipRadio value=" + fromZips[i][0] + " onclick='javascript:chooseFromZip("+fromZips[i][0]+")'>" + fromZips[i][0] + " " + svf + "</input><br>"
			}
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");
			return;
		}
		else if (fromZips.length == 1)
		{
			chosenFromZip = fromZips[0][0];
			$j("#rResultsHtml").html("");
			$j("#routing_from_addr").css("background-color","#FFFFFF");
			$j("#RoutingResultsDIV").css("visibility","hidden");

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
				htmlZippoz += "<input type=radio name=fromZipRadio value=" + toZips[i][0] + " onclick='javascript:chooseToZip("+toZips[i][0]+")'>" + toZips[i][0] + " " + svf + "</input><br>"
			}
			$j("#rResultsHtml").html(htmlZippoz);
			$j("#RoutingResultsDIV").css("visibility","visible");
			return;
		}
		else if (toZips.length == 1)
		{
			chosenToZip = toZips[0][0];
			$j("#rResultsHtml").html("");
			$j("#routing_to_addr").css("background-color","#FFFFFF");
			$j("#RoutingResultsDIV").css("visibility","hidden");
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
	getRoutePath(submitFromAddr,chosenFromZip,submitToAddr,chosenToZip)
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
	sendSyncAJAXRequest("proxies/leit_proxy.asp?sKey=checkAddressAndZipUnique&sValue=" + sValue1+"|"+sValue2);
	var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("postnumer").text();
		zippo[1] = $j(this).find("sveitarfel").text();
		zippo[2] = $j(this).find("tettbyliss").text();
		zipArray.push( zippo ); // insert zip to array
	});
	if (zipArray.length == 0)
	{
		//zipArray.push( 666 ); // Return 666 if there is no match
	}
	return zipArray;
 }


 function chooseFromZip(zip)
 {
	chosenFromZip = zip;
	from_addr = $j("#routing_from_addr").val();
	from_addr += "," + LM_CityFromZipsNefni[zip];
	$j("#routing_from_addr").val(from_addr);
	$j("#rResultsHtml").html("");
	getRoute();
 }

  function chooseToZip(zip)
 {
	chosenToZip = zip;
	to_addr = $j("#routing_to_addr").val();
	to_addr += "," + LM_CityFromZipsNefni[zip];
	$j("#routing_to_addr").val(to_addr);
	$j("#rResultsHtml").html("");
	getRoute();
 }

 function checkAddressUnique(sValue)
 {
	// Checks if address is unique.  Returns an array of zips if not unique.
	// If address is unique the return array holds one zip.
	// If no matching address is found return zip hold 666!

	//((sendSyncAJAXRequest("proxies/leit_proxy.asp?sKey=checkUniqueAddress&sValue=" + theAddress, checkAddressCallback);
	var zipArray = new Array();
	sendSyncAJAXRequest("proxies/leit_proxy.asp?sKey=checkUniqueAddress&sValue=" + sValue);
	var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
	//alert(numberOfResults);
	// Lets walk through the response XML and pick out the zip codes
	$j(xmlHttp.responseXML).find("row").each(function()
	{
		var zippo = new Array();
		zippo[0] = $j(this).find("postnumer").text();
		zippo[1] = $j(this).find("sveitarfel").text();
		zippo[2] = $j(this).find("tettbyliss").text();
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

function getRoutePath(addrFrom,zipFrom,addrTo,zipTo)
{
	// Jæja sækja leiðina
	sendAJAXRequest('proxies/routing_proxy.asp?request=route&to_addr=' + escape(addrTo) + '&to_city=' + zipTo + '&from_addr=' + escape(addrFrom) + '&from_city=' + zipFrom + '',retrievedGeoJSON);
}

function getRoutePathClick()
{
	if (routeFromHereCoords != "" && routeToHereCoords != "")
	{
		//Hreinsa gamlar niðurstöður
		vectors.destroyFeatures();
		// Jæja sækja leiðina
		sendAJAXRequest('proxies/routing_proxy_click.asp?request=route&xfrom=' + routeFromHereCoords.lon + '&yfrom=' + routeFromHereCoords.lat + '&xto=' + routeToHereCoords.lon + '&yto=' + routeToHereCoords.lat + '', retrievedGeoJSON);
	}
}

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

function rotate180(input)
{
	if( input  > 180 )
		return input-180;
	else
		return input+180;
}

function retrievedGeoJSON(resp)
{

	if(xmlHttp.readyState == 4)
	{
		var rData = [];

		var in_options = {
		'internalProjection': map.baseLayer.projection,
		'externalProjection': new OpenLayers.Projection("EPSG:3057")
		};

		var gj = new OpenLayers.Format.GeoJSON(in_options);
		var elements = xmlHttp.responseText.split("||");
		//alert(xmlHttp.responseText);
		var features = [];
		for(i = 0;i<elements.length;i++)
		{
			// datapair is geom and other data 0 beaing the geometry and 1 the rest (roadname, length etc.)
			var datapair = elements[i].split("|--|");
			features.push(gj.read(datapair[0])[0]);

			var databits = datapair[1].split(",");
			var rObj = new routingDataObject(databits[0],databits[1],databits[2],databits[3],databits[4],databits[5],databits[6],databits[7],databits[8],databits[9],databits[10],databits[11]);
			rData.push(rObj);
			//alert(datapair[1]);
		}
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
					bounds = features[i].geometry.getBounds();
				}
				else
				{
					bounds.extend(features[i].geometry.getBounds());
				}
			}
			vectors.addFeatures(features);
			map.zoomToExtent(bounds);
		}
		else
		{
			alert('Villa kom upp. ID virkar ekki');
		}

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

		//debugger;

		var totalLength = 0;
		var rrhtml = "<hr><b><font size=2>Akstursleiðbeiningar</font></b><br><br>";
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
			debugger;
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
			else // rest
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
					a = a-360; // maður beygir aldrei meira en 180 gráður í sömu átt

				if (Math.abs(a) <= 10)
					turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/drive_straight.png> Ekið <b>áfram</b> eftir ";

				if ( a > 10 )
					turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_left.png> Taktu <b>vinstri</b> beygju inn á ";

				if ( a < -10 )
					turnObj.turnTxt = "<img src=http://www.loftmyndir.is/k/img/routing/turn_right.png> Taktu <b>hægri</b> beygju inn á ";

				//turnObj.turnTxt = a;
			}

			// Skilum turn objectinum í turn fylkið
			turnArray.push(turnObj);
		}

		alert(turnArray[0].turnTxt);
		// ------------------------------- Orientation array end ---------------------------------- //


		// Skrifum út leiðina í html panellinn
		for(var j=0; j<rData.length; ++j)
		{
			if (j==0)
			{
				// Setjum inn vegupplýsingar í routing object til að bera saman við næst
				totalRoad.roadname = rData[j].roadname;
				//totalRoad.length = Math.round(rData[j].length);
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


					if (totalRoad.roundabout == "Y")
					{
						rrhtml +=  roadcounter + ". Ekið inn í hringtorg ";
					}
					else
					{
						var rname = totalRoad.roadname;
						rrhtml +=  roadcounter + ". "+ turnArray[j-1].turnTxt + rname + "";
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

					rrhtml += " " + legLength + "<img src=img/vegvisun/vegvisun_divider_pixel.gif height id=vegvisun_divider_generic>";

					totalRoad.length = Math.round(rData[j].length);

					if ((j+1) == rData.length)
					{
						roadcounter++;
						rrhtml +=  roadcounter + ". " + rData[j].roadname;
						var legLength = "";
						if (totalRoad.length > 1000){
							legLength = (Math.round(totalRoad.length)/1000).toFixed(1) + "km";
						}
						else
						{
							legLength = Math.round(totalRoad.length) + "m";
						}

						rrhtml += " " + legLength + "<img src=img/vegvisun/vegvisun_divider_pixel.gif id=vegvisun_divider_generic>";

						totalRoad.length = Math.round(rData[j].length);
					}
				}
				else
				{
					// Við erum enn á sama legg og leggjum bara saman vegalengdina
					totalRoad.length += Math.round(rData[j].length);
				}



				// Setjum inn uppl um götuna til að nota næst
				totalRoad.roadname = rData[j].roadname;
				//totalRoad.length = Math.round(rData[j].length);
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
			//}

			totalLength += ((rData[j].length)/1);
		//

		}


		var roundTotalLength;
		if (totalLength > 1000){
							roundTotalLength = (Math.round(totalLength)/1000).toFixed(1) + " km";
						}
						else
						{
							roundTotalLength = Math.round(totalLength) + " m";
						}

		rrhtml += "Heildarlengd " + roundTotalLength ;

		//alert("Heildarlengd: " + (Math.round(totalLength)/1000).toFixed(1) + " km");
		$j("#rResultsHtml").html(rrhtml);
		$j("#RoutingResultsDIV").css("visibility","visible");
		rData = [];
	}
}


function mapLayerChangedClient(event)
{

}

function routeFromHere()
{
	// Function to handle "Route from here" on right mouse click on map (custom) context menu
	// Finds road vertext close to where the user clicked to route from (start point)
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

	if (typeof(marker) == "undefined") {
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

function routeToHere()
{
	// Function to handle "Route to here" on right mouse click on map (custom) context menu
	// Finds road vertext close to where the user clicked to route to (end point)
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

	if (typeof(marker) == "undefined") {
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

function getMarkerByName(markerName)
{
	for (i=0; i < markers.markers.length; i++)
	{
		if(markers.markers[i].name == markerName)
			return markers.markers[i];
	}
}

function centerMapHere()
{
	// Function to handle "Center map here"  on right mouse click on map (custom) context menu
	// Centers the map to where the user clicked
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	map.setCenter( lonlat,map.getScale());
}
function contextZoomIn()
{
	// Function to handle "Zoom in"  on right mouse click on map (custom) context menu
	// Zooms the map in 2 levels and centers the map to where the user clicked
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	map.setCenter( lonlat,(map.getZoom()+2) );
}
function contextZoomOut()
{
	// Function to handle "Zoom out"  on right mouse click on map (custom) context menu
	// Zooms the map out 2 levels and centers the map to where the user clicked
	var lonlat = map.getLonLatFromViewPortPx(new OpenLayers.Pixel(rightClickMousePosX , rightClickMousePosY) );
	var zoomy = map.getZoom()-2; // Get current zoom level from map minus 2 since we're zooming out
	map.zoomTo(zoomy);
}

function clientConfig()
{

	var zipOptions = "<option value=101>101 Reykjavík</option>";
	zipOptions += "<option value=102>102 (Millilanda Póstur)</option>";
	zipOptions += "<option value=103>103 Reykjavík</option>";
	zipOptions += "<option value=104>104 Reykjavík</option>";
	zipOptions += "<option value=105>105 Reykjavík</option>";
	zipOptions += "<option value=107>107 Reykjavík</option>";
	zipOptions += "<option value=108>108 Reykjavík</option>";
	zipOptions += "<option value=109>109 Reykjavík</option>";
	zipOptions += "<option value=110>110 Reykjavík</option>";
	zipOptions += "<option value=111>110 Reykjavík</option>";
	zipOptions += "<option value=112>112 Reykjavík</option>";
	zipOptions += "<option value=113>113 Reykjavík</option>";
	zipOptions += "<option value=116>116 Reykjavík</option>";
	zipOptions += "<option value=121>121 Reykjavík</option>";
	zipOptions += "<option value=123>123 Reykjavík</option>";
	zipOptions += "<option value=124>124 Reykjavík</option>";
	zipOptions += "<option value=125>125 Reykjavík</option>";
	zipOptions += "<option value=127>127 Reykjavík</option>";
	zipOptions += "<option value=128>128 Reykjavík</option>";
	zipOptions += "<option value=129>129 Reykjavík</option>";
	zipOptions += "<option value=130>130 Reykjavík</option>";
	zipOptions += "<option value=132>132 Reykjavík</option>";
	zipOptions += "<option value=150>150 Reykjavík</option>";
	zipOptions += "<option value=155>155 Reykjavík</option>";
	zipOptions += "<option value=170>170 Seltjarnarnesi</option>";
	zipOptions += "<option value=172>172 Seltjarnarnesi</option>";
	zipOptions += "<option value=190>190 Vogum</option>";
	zipOptions += "<option value=200>200 Kópavogi</option>";
	zipOptions += "<option value=201>201 Kópavogi</option>";
	zipOptions += "<option value=202>202 Kópavogi</option>";
	zipOptions += "<option value=203>203 Kópavogi</option>";
	zipOptions += "<option value=210>210 Garðabæ</option>";
	zipOptions += "<option value=212>212 Garðabæ</option>";
	zipOptions += "<option value=220>220 Hafnarfirði</option>";
	zipOptions += "<option value=221>221 Hafnarfirði</option>";
	zipOptions += "<option value=222>222 Hafnarfirði</option>";
	zipOptions += "<option value=225>225 Álftanesi</option>";
	zipOptions += "<option value=230>230 Reykjanesbæ</option>";
	zipOptions += "<option value=232>232 Reykjanesbæ</option>";
	zipOptions += "<option value=233>233 Reykjanesbæ</option>";
	zipOptions += "<option value=235>235 Reykjanesbæ</option>";
	zipOptions += "<option value=240>240 Grindavík</option>";
	zipOptions += "<option value=245>245 Sandgerði</option>";
	zipOptions += "<option value=250>250 Garði</option>";
	zipOptions += "<option value=260>260 Reykjanesbæ</option>";
	zipOptions += "<option value=270>270 Mosfellsbæ</option>";
	zipOptions += "<option value=300>300 Akranesi</option>";
	zipOptions += "<option value=301>301 Akranesi</option>";
	zipOptions += "<option value=302>302 Akranesi</option>";
	zipOptions += "<option value=310>310 Borgarnesi</option>";
	zipOptions += "<option value=311>311 Borgarnesi</option>";
	zipOptions += "<option value=320>320 Reykholt í Borgarfirði</option>";
	zipOptions += "<option value=340>340 Stykkishólmi</option>";
	zipOptions += "<option value=345>345 Flatey á Breiðafirði</option>";
	zipOptions += "<option value=350>350 Grundarfirði</option>";
	zipOptions += "<option value=355>355 Ólafsvík</option>";
	zipOptions += "<option value=356>356 Snæfellsbæ</option>";
	zipOptions += "<option value=360>360 Hellissandi</option>";
	zipOptions += "<option value=370>370 Búðardal</option>";
	zipOptions += "<option value=371>371 Búðardal</option>";
	zipOptions += "<option value=380>380 Reykhólahreppi</option>";
	zipOptions += "<option value=400>400 Ísafirði</option>";
	zipOptions += "<option value=401>401 Ísafirði</option>";
	zipOptions += "<option value=410>410 Hnífsdal</option>";
	zipOptions += "<option value=415>415 Bolungarvík</option>";
	zipOptions += "<option value=420>420 Súðavík</option>";
	zipOptions += "<option value=425>425 Flateyri</option>";
	zipOptions += "<option value=430>430 Suðureyri</option>";
	zipOptions += "<option value=450>450 Patreksfirði</option>";
	zipOptions += "<option value=451>451 Patreksfirði</option>";
	zipOptions += "<option value=460>460 Tálknafirði</option>";
	zipOptions += "<option value=465>465 Bíldudal</option>";
	zipOptions += "<option value=470>470 Þingeyri</option>";
	zipOptions += "<option value=471>471 Þingeyri</option>";
	zipOptions += "<option value=500>500 Stað</option>";
	zipOptions += "<option value=510>510 Hólmavík</option>";
	zipOptions += "<option value=512>512 Hólmavík</option>";
	zipOptions += "<option value=520>520 Drangsnesi</option>";
	zipOptions += "<option value=522>522 Kjörvogi</option>";
	zipOptions += "<option value=523>523 Bæ</option>";
	zipOptions += "<option value=524>524 Norðurfirði</option>";
	zipOptions += "<option value=530>530 Hvammstanga</option>";
	zipOptions += "<option value=531>531 Hvammstanga</option>";
	zipOptions += "<option value=540>540 Blönduósi</option>";
	zipOptions += "<option value=541>541 Blönduósi</option>";
	zipOptions += "<option value=545>545 Skagaströnd</option>";
	zipOptions += "<option value=550>550 Sauðárkróki</option>";
	zipOptions += "<option value=551>551 Sauðárkróki</option>";
	zipOptions += "<option value=560>560 Varmahlíð</option>";
	zipOptions += "<option value=565>565 Hofsós</option>";
	zipOptions += "<option value=566>566 Hofsós</option>";
	zipOptions += "<option value=570>570 Fljótum</option>";
	zipOptions += "<option value=580>580 Siglufirði</option>";
	zipOptions += "<option value=600>600 Akureyri</option>";
	zipOptions += "<option value=602>602 Akureyri</option>";
	zipOptions += "<option value=603>603 Akureyri</option>";
	zipOptions += "<option value=610>610 Grenivík</option>";
	zipOptions += "<option value=611>611 Grímsey</option>";
	zipOptions += "<option value=620>620 Dalvík</option>";
	zipOptions += "<option value=621>621 Dalvík</option>";
	zipOptions += "<option value=625>625 Ólafsfirði</option>";
	zipOptions += "<option value=630>630 Hrísey</option>";
	zipOptions += "<option value=640>640 Húsavík</option>";
	zipOptions += "<option value=641>641 Húsavík</option>";
	zipOptions += "<option value=645>645 Fosshólli</option>";
	zipOptions += "<option value=650>650 Laugum</option>";
	zipOptions += "<option value=660>660 Mývatni</option>";
	zipOptions += "<option value=670>670 Kópaskeri</option>";
	zipOptions += "<option value=671>671 Kópaskeri</option>";
	zipOptions += "<option value=675>675 Raufarhöfn</option>";
	zipOptions += "<option value=680>680 Þórshöfn</option>";
	zipOptions += "<option value=681>681 Þórshöfn</option>";
	zipOptions += "<option value=685>685 Bakkafirði</option>";
	zipOptions += "<option value=690>690 Vopnafirði</option>";
	zipOptions += "<option value=700>700 Egilsstöðum</option>";
	zipOptions += "<option value=701>701 Egilsstöðum</option>";
	zipOptions += "<option value=710>710 Seyðisfirði</option>";
	zipOptions += "<option value=715>715 Mjóafirði</option>";
	zipOptions += "<option value=720>720 Borgarfirði (eystri)</option>";
	zipOptions += "<option value=730>730 Reyðarfirði</option>";
	zipOptions += "<option value=735>735 Eskifirði</option>";
	zipOptions += "<option value=740>740 Neskaupstað</option>";
	zipOptions += "<option value=750>750 Fáskrúðsfirði</option>";
	zipOptions += "<option value=755>755 Stöðvarfirði</option>";
	zipOptions += "<option value=760>760 Breiðdalsvík</option>";
	zipOptions += "<option value=765>765 Djúpavogi</option>";
	zipOptions += "<option value=780>780 Höfn í Hornafirði</option>";
	zipOptions += "<option value=781>781 Höfn í Hornafirði</option>";
	zipOptions += "<option value=785>785 Öræfum</option>";
	zipOptions += "<option value=800>800 Selfossi</option>";
	zipOptions += "<option value=801>801 Selfossi</option>";
	zipOptions += "<option value=802>802 Selfossi</option>";
	zipOptions += "<option value=810>810 Hveragerði</option>";
	zipOptions += "<option value=815>815 Þorlákshöfn</option>";
	zipOptions += "<option value=820>820 Eyrarbakka</option>";
	zipOptions += "<option value=825>825 Stokkseyri</option>";
	zipOptions += "<option value=840>840 Laugarvatni</option>";
	zipOptions += "<option value=845>845 Flúðum</option>";
	zipOptions += "<option value=850>850 Hellu</option>";
	zipOptions += "<option value=851>851 Hellu</option>";
	zipOptions += "<option value=860>860 Hvolsvelli</option>";
	zipOptions += "<option value=861>861 Hvolsvelli</option>";
	zipOptions += "<option value=870>870 Vík</option>";
	zipOptions += "<option value=871>871 Vík</option>";
	zipOptions += "<option value=880>880 Kirkjubæjarklaustri</option>";
	zipOptions += "<option value=900>900 Vestmannaeyjum</option>";
	zipOptions += "<option value=902>902 Vestmannaeyjum</option>";

	 //Insert HTML Container for Routing
	RoutingHtml = "<div id='RoutingDIV'><img src='img/vegvisun/vegvisun_header_logo.png' id='vegvisun_header_logo'><div id='headerText'>Vegvísun</div>";
	RoutingHtml += "<img id='vegvisun_close_btn' src='img/vegvisun/vegvisun_close.gif'><img id='vegvisun_divider1' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
	RoutingHtml += "<div id='vegvisun_A'><img src='img/vegvisun/vegvisun_A.png'></div><div id='fromtext'>Frá:</div><input id='routing_from_addr' type='text'>";
	RoutingHtml += "<div id='vegvisun_B'><img src='img/vegvisun/vegvisun_B.png'></div><div id='totext'>Til:</div><input id='routing_to_addr' type='text'>";
	//RoutingHtml += "<div id='cityFromText'>Bæjarfélag:</div><select id='routing_from_city'>"+zipOptions+"</select>"
	//RoutingHtml += "<div id='cityToText'>Bæjarfélag:</div><select id='routing_to_city'>"+zipOptions+"</select>"
	RoutingHtml += "<img id='vegvisun_divider2' src='img/vegvisun/vegvisun_divider_pixel.gif'>";
	RoutingHtml += "<input id='routing_button' type='button' value='Finna leið' onclick='clearZips();getRoute();'></div>";
	$j("body").append(RoutingHtml);

	//Insert HTML container for routing results
	RoutingResultsHtml = "<div id='RoutingResultsDIV'>";
	RoutingResultsHtml += "<div id='rResultsHtml'></div>";
	RoutingResultsHtml += "</div>";
	$j("body").append(RoutingResultsHtml);

	/*document.getElementById("RoutingDIV").style.visibility = "visible"; */

	// Búum til nýtt context menu (f. hægrismell)
	var cuztomContextMenu = "<!-- The second Menu(Special) --><ul class=context-menu id=special-menu><li><a href=javascript:contextZoomIn();>Þysja inn</a></li>";
	cuztomContextMenu += "<li><a href=javascript:contextZoomOut();>Þysja út</a></li><li><a href=javascript:centerMapHere();>Miðja kort hér</a></li>";
	cuztomContextMenu += "<li><a href=javascript:routeFromHere();>Vegvísun frá</a></li><li><a href=javascript:routeToHere();>Vegvísun til</a></li></ul>";
	$j("body").append(cuztomContextMenu); // Öddum þessu á domið

	ContextMenu.set("special-menu", "map");



 var wms_routing_vegir = new OpenLayers.Layer.WMS.Untiled( "Vegir overlay","http://212.30.228.18/geoserver/wms",
 {layers:'postgis:routing_vegir',format:'image/jpeg',transparent: true, styles:'line_routingtest'},
 {'displayInLayerSwitcher':true, 'isBaseLayer':false,visibility:false});
 map.addLayer(wms_routing_vegir);



 // Display select button in the toolbar
 document.getElementById("tbSelect").style.visibility = 'visible';

 /****************** Click Event ************************************************/
 // Til að nota seinna
 var lon;
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

 })

 //switchCommands('DragPan');

 var clickWMSFeature = new OpenLayers.Control.Click();
 map.addControl(clickWMSFeature);
 clickWMSFeature.activate();

 // höfum höndina á default
 document.body.style.cursor='pointer';




}

function clientWFS()
{
 // Insert code here
}