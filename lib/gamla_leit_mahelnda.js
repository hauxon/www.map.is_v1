/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/** ********** Search JavaScript ************* **/

// Skítamix til að tryggja að leitin endi ekki upp á Hofsjökli í fyrsta smelli
//parent.map.panTo( new parent.OpenLayers.LonLat( 517886.32431, 491785.815 ) );


// Here we need to define a parameter array to hold parameters for links in search results
var linkParameters = new Array() ;

var searchXMLHttp;

function searchResultsCallback()
{
	if(xmlHttp.readyState==4)
 	{
		currPage = 1;
		searchXMLHttp = xmlHttp;
		displayResults();
	}
}

function formatResultTable( sType)
{
	switch (sType)
	{
		case "vegir":
			format_usvegir();
			break;
		case "ornefni":
			format_ornefni();
			break;
		case "vegbutar":
			format_vegbutar();
			break;
		case "heimilisfong":
			format_heimilisfong();
			break;
		case "heimilisfongSkaga":
			format_heimilisfong();
			break;
		case "heimilisfongDala":
			format_heimilisfong();
			break;
		case "heimilisfongSkutu":
			format_heimilisfong();
			break;
		case "heimilisfongBorgarf":
			format_heimilisfong();
			break;	
		case "heimilisfongBorgarb":
			format_heimilisfong();
			break;	
		case "heimilisfongSkorrad":
			format_heimilisfong();
			break;
		case "heimilisfongHval":
			format_heimilisfong();
			break;
		case "heimilisfongHvera":
			format_heimilisfong();
			break;
		case "heimilisfongVogar":
			format_heimilisfong();
			break;
		case "heimilisfongDjupa":
			format_heimilisfong();
			break;
		case "heimilisfongAkureyri":
			format_heimilisfong();
			break;
		case "heimilisfongArborg":
			format_heimilisfong();
			break;
		case "ni_plontur_isl":
			format_ni_plontur_isl();
			break;
		case "ni_plontur_lat":
			format_ni_plontur_lat();
			break;
		case "ni_plontur_reitir":
			format_ni_plontur_reitir();	
			break;
		case "lmi_ornefni_leit":
			format_lmi_ornefni_leit();		
			break;
		case "landsnet_kks_leit":
			format_landsnet_kks_leit();		
			break;
		case "poi1":
			format_poi1();		
			break;
		case "nytjaland":
			format_nytjaland();		
			break;
		case "svfnr":
			format_svfnr();		
			break;
		case "skipunr":
			format_skipunr();		
			break;
		case "rarik_maelisnr":
			format_rarik_notkun();		
			break;
		case "rarik_kt_vskv":
			format_rarik_notkun();		
			break;
		case "rarik_kt_maelisst":
			format_rarik_notkun();		
			break;
		case "rarik_veitunumer":
			format_rarik_notkun();		
			break;
		case "rarik_vidskiptamadur":
			format_rarik_notkun();		
			break;
		case "bondi_raektunarspildur":
			format_bondi();		
			break;
		case "rarik_notkst":
			format_rarik_notkun();		
			break;
		case "rarik_landnr":
			format_rarik_notkun();		
			break;
		default:
			alert("default_format")
			break;
	}
}

var heimiliFlagg = false; // Notast til að vita hvort fyrirspurn hafi verið send afur.
function displayResults()
{

	var searchType = $j("#searchDropDown option:selected").val();
	
	formatResultTable( searchType );
	// readyState 4 means response complete and time to work the results
	if(searchXMLHttp.readyState==4) // HH if(xmlHttp.readyState==4)
 	{
		var numberOfResults = $j(searchXMLHttp.responseXML).find("row").length; // HH var numberOfResults = $j(xmlHttp.responseXML).find("row").length;
		log("# results " + numberOfResults);
		//If result is only 1 there's no need to cunstruct a pop-up window.
		
		//state: no results and second request has not been sent
		if(numberOfResults == 0 && heimiliFlagg == false) 
		{	 
			var queryText = document.getElementById("searchText").value;
			if(searchType == "heimilisfong")
			{
				sendAJAXRequest("proxies/leit_proxy.asp?sKey=heimilisfong&sValue=" + escape(queryText)+"&nCounty="+SveitarfelagsNumer, searchResultsCallback);
				heimiliFlagg = true; // tells us that heimili sent a second search request
				return;
			}
		}
		
		heimiliFlagg = false; // Sets flag to false (normal).  If code reaches here the request is ready to be processed.
		
		if(numberOfResults == 1)
		{
			
		}
		
		var strHTML = "<Table cellpadding=0 cellspacing=0 border=0 class=mainSearchTable>";
		strHTML += "<tr><td class=sTableHeaderLCorner><img src=img/clear.gif width=4 height=10 border=0></td>"; //1
		
		// Check number of displayers rows
		var noDisplayedColumns = 0; // To keep number of rows that need to be displayed in the result table
		for( i=0; i < aResultColumn.length; i++)
		{
			if(aResultColumn[i]['displayInTable'] == true)
			{
				noDisplayedColumns++;
			}
		}
		
		// Let's roll though the array to generate the header 
		var columnCounter = 0;
		for( i=0; i < aResultColumn.length; i++)
		{
			columnCounter++;
			if(aResultColumn[i]['displayInTable'] == true)
			{
				strHTML += "<td width=" + aResultColumn[i]['columnWidth'] + " class=sTableHeader>&nbsp;" + aResultColumn[i]['tableHeaderTxt'] + "</td>";
				if(columnCounter != (noDisplayedColumns-1) )
				{
					strHTML += "<td class=sTableHeaderDivider><img src=img/clear.gif width=1 height=10 border=0></td>";
				}
			}
		}
		strHTML += "<td class=sTableHeaderRCorner><img src=img/clear.gif width=4 height=10 border=0></td></tr>"; //13
		
		var totalColumns = (2 + noDisplayedColumns + noDisplayedColumns - 1); // corners + columns + dividers (columns-1)
		strHTML += "<tr><td colspan=" + totalColumns + " class=sTableBody>";  
		strHTML += "<Table width=100% cellpadding=0 cellspacing=0 border=0 class=mainSearchResultTable>";	// Table containg search Result list
		
		// Now we start working with the XML resultset
		var returnXML = searchXMLHttp.responseText;// HH var returnXML = xmlHttp.responseText;
		var counter = 0;		
		
		// Lesa GML Test
		var leGML = new OpenLayers.Format.GML( {extractAttributes: true} );
		leGML.read(returnXML);
		//var leAttribs = leGML.attributes;
		var pointxCoords = new Array();
		var pointyCoords = new Array();
		
		//var filter = "A";
		//Clear markers if we have any
		this.markers.clearMarkers();
		var labelLayer = getLayerByName("Merki");
		delLabels(labelLayer);  
		
		$j(searchXMLHttp.responseXML).find("row").each(function()  // HH $j(xmlHttp.responseXML).find("row").each(function() 
		{
			// First we need to decide if result needs to be filtered
			// if variable filter is defined) -> do filtering
			if ( typeof(filter) !== "undefined")
			{
				if(filter != "")
				{
					if ( $j(this).find( aResultColumn[3]['SQLColumn'] ).text() != filter)
					{
						return; // Returns from each loop if row doen not fit filtering conditions
					}
				}
			}
			
			
			// Calculate what lines to display depending on what resultpage we're in
			var first = (currPage*noPerPage)-noPerPage;
			if ( counter >= first && counter < (first+noPerPage) )
			{
				var totalDisplayedCols = 0; //Counter of columns actually displayed.
				
				resColLength = aResultColumn.length;
				for(var i=0; i < resColLength; i++) // Loops through the columns
				{
					var rowClass = "";
					if( counter%2 == 0)
					{ rowClass = "sTableResults"; }
					else
					{ rowClass = "sTableResultsAlt"; }
			
					columnCounter++;
					
					// Add parameter if one is supplied
					if ( aResultColumn[i]['linkParameter'] )
					{
						linkParameters[counter] = $j(this).find( aResultColumn[i]['SQLColumn'] ).text();
					}
					
					if(aResultColumn[i]['displayInTable'] == true)
					{
						totalDisplayedCols++;
						
						var isPoint = false; // We need to know if the result is a point
						var scale = 10000;
						var row = $j(this);
						var GML =  $j(this).find("boundary").text() ;
						var geom = $j(this).find("geom").text() ;
						var coordList = "";		
						var coordObj = fillCoordList( GML, geom );
						
						
						coordList = coordObj.coordList;
						//isPoint = coordObj.isPoint;
						var CoordsArray = coordList.split(",");
						if (CoordsArray.length == 2)
						    isPoint = true;						
						if(isPoint)
						{
							var steard = Number($j(this).find("staerd").text());
							if (steard != 0)
							{
								scale = setResultScale(steard );
							}
							else
							{
								scale = setResultScale( 6 )
							}
						}
						
						if(numberOfResults == 1)
						{
							
							var coordArray = coordList.split(",");
							if(isPoint == true)
							{
								//log("I'm a single point");
								
								//Add marker
								
								//Insert coordinates into the x and y coords arrrays (for resultset bounds later)
								var pointCoords = coordList.split(",");
								pointxCoords.push(pointCoords[0]);
								pointyCoords.push(pointCoords[1]); 
								
								//Add marker
								var size = new OpenLayers.Size(32,32);
								var offset = new OpenLayers.Pixel(-(size.w/2-8), -size.h);
								var icon = new OpenLayers.Icon('img/teiknibola.png',size,offset);
								
								//alert(pointCoords[0] + " " + pointCoords[1]);
								
								var leMarker = new OpenLayers.Marker(new OpenLayers.LonLat(pointCoords[0],pointCoords[1]),icon);
								leMarker.labelText = $j(this).find( aResultColumn[i]['SQLColumn'] ).text();
								markers.addMarker(leMarker);
								//debugger;
								searchToXY( trim(coordArray[0]), trim(coordArray[1]), scale,
																		{
																			noResults:1,
																			address:extractValueByAttribute('heimilisfa',this.childNodes),//this.childNodes[1].firstChild.data,
																			pnr:extractValueByAttribute('postnumer',this.childNodes),//this.childNodes[3].firstChild.data,
																			svf:extractValueByAttribute('tettbyliss',this.childNodes),//this.childNodes[5].firstChild.data
																			marker:true
																		}
																	);
							}
							else
							{
								//log( coordList );
								zoomToBounds( coordList );
								
								// draw featureoutline if svfnr option in dropdownbox
								if($j("#searchDropDown option:selected").val() == "svfnr")
									drawSearchFeature(returnXML);
									
								if($j("#searchDropDown option:selected").val() == "nytjaland")
									nytjaTT(returnXML);
									
							}							
							
							//strHTML = "";
							
							return;
						}
						
						var colWidth = "";
						if ( aResultColumn[i]['columnWidth'] != "" )
							colWidth = "width=" + aResultColumn[i]['columnWidth'];
						strHTML += "<td " + colWidth + " class=" + rowClass + ">";
						
						// Show Link
						if ( aResultColumn[i]['showLink'] )
						{
							var linkType = aResultColumn[i]['linkType'];
							var linkText = $j(this).find( aResultColumn[i]['SQLColumn'] ).text();
							
							switch(linkType)
							{
							case 'jsFunction':
							  // Set up a javascript function to call when link is clicked
							  // The javascript function needs to be implemented elsewhere (but within the scope)
							  // i is index of the parameter array used in the function we are calling
							  strHTML	+= "<a href=# onclick=searchLinkClick('" + linkType + "'," + counter + ")>" + linkText + "</a>";
							  break;    
							case 'extLink':
							  // Set up a link to an external webpage
							  strHTML	+= "<a href=# onclick=searchLinkClick('" + linkType + "'," + counter + ")>" + linkText + "</a>";
							  break;
							default:
								// No linkType was set 
								if (!isPoint)
								{
									// Go directly to point if result is only one
									if( numberOfResults == 1 ) 
									{
										//zoomToBounds(coordList);
										//return false;
									}
									
									if($j("#searchDropDown option:selected").val() == "svfnr")
									{
										var postXML = "<?xml version='1.0'  encoding='ISO-8859-1'?><root><row><nafn>" + linkText +"</nafn><numer>" + $j(this).find( aResultColumn[1]['SQLColumn'] ).text() +"</numer><geom>";
										postXML += escape(geom);
										postXML += "</geom></row></root>";
										
										strHTML	+= "&nbsp;<a href=# onclick=\"zoomToBoundsDraw('" + coordList + "', '" + escape(postXML) + "')\">" + $j(this).find( aResultColumn[i]['SQLColumn'] ).text() + "</a></td>";
									}
									else if($j("#searchDropDown option:selected").val() == "nytjaland")
									{
										var postXML = "<?xml version='1.0'  encoding='ISO-8859-1'?><root><row><heiti>" + linkText +"</heiti><geom>";
										postXML += escape(geom);
										postXML += "</geom></row></root>";
										
										strHTML	+= "&nbsp;<a href=# onclick=\"zoomToBoundsNytja('" + coordList + "', '" + escape(postXML) + "')\">" + $j(this).find( aResultColumn[i]['SQLColumn'] ).text() + "</a></td>";
									}
									else
									{
									// If link has "showLink" = true we link to zoomToBounds script with extracted coordinates
										//debugger;
										if (coordList!= "")
										{
											strHTML	 += "&nbsp;<a href=# onclick=zoomToBounds('" + coordList + "')>" + $j(this).find( aResultColumn[i]['SQLColumn'] ).text() + "</a></td>";
										}
										else
										{
											strHTML	+= "&nbsp;<font color=#2F2F2F>" + $j(this).find( aResultColumn[i]['SQLColumn'] ).text() + "</font></td>";
										}
									//alert("Not point");
									}
								}
								else
								{			
									// If link has "showLink" = true we link to searchToXYscript with extracted coordinates
				
									address = $j(this).find( aResultColumn[i]['SQLColumn'] ).text();
									strHTML	+= "&nbsp;<a href=# onclick='searchToXY(" + coordList + "," + scale + ",this);addTeiknibola("+coordList+");'>";
									strHTML += address + "</a></td>";

									//Insert coordinates into the x and y coords arrrays (for resultset bounds later)
									var pointCoords = coordList.split(",");
									pointxCoords.push(pointCoords[0]);
									pointyCoords.push(pointCoords[1]);
									
									//Add marker
									var size = new OpenLayers.Size(32,32);
									var offset = new OpenLayers.Pixel(-(size.w/2-8), -size.h);
									var icon = new OpenLayers.Icon('img/teiknibola.png',size,offset);
									
									
									var leMarker = new OpenLayers.Marker(new OpenLayers.LonLat(pointCoords[0],pointCoords[1]),icon);
									leMarker.labelText = $j(this).find( aResultColumn[i]['SQLColumn'] ).text();
									markers.addMarker(leMarker);
									
								}
							}
						}
						else
						{
							// else just diplay the text for the column
							
							//if en need to translate
							if (aResultColumn[i]['translate'])
							{
								strHTML	+= "&nbsp;" + getTranslatedText( $j(this).find( aResultColumn[i]['SQLColumn'] ).text() ) + "</td>";
							}else
							{
								strHTML	+= "&nbsp;" + $j(this).find( aResultColumn[i]['SQLColumn'] ).text() + "</td>";
							}
						}
						
						// Do not insert column divider if we're at tge last column
						//alert("totalDisplayedCols:" + totalDisplayedCols + " noDisplayedColumns:" + noDisplayedColumns + " Modulus:" + columnCounter%noDisplayedColumns);
						if(totalDisplayedCols%noDisplayedColumns != 0 )
						{
							strHTML += "<td class=sTableHeaderDivider><img src=img/clear.gif width=1 height=10 border=0></td>";
						}
					}
				}
				strHTML += "</TR>";
			}	
			counter++; 
	    });	
		
		// Here we need to find the outermost coordinates of the set to construct a bounding box.
		
		var xmax, xmin,ymax,ymin;
		for( j=0; j < pointxCoords.length; j++)
		{
			if(j == 0)
			{
				// Insert first coordinates into the variables
				xmax = pointxCoords[j];
				xmin = pointxCoords[j];
				ymax = pointyCoords[j];
				ymin = pointyCoords[j];
			}
			else
			{
				if(pointxCoords[j] > xmax)
					xmax = pointxCoords[j];
				if(pointxCoords[j] < xmin)
					xmin = pointxCoords[j];
				if(pointyCoords[j] > ymax)
					ymax = pointyCoords[j];
				if(pointyCoords[j] < ymax)
					ymin = pointyCoords[j];
			}
		}
		
		searchBounds = new OpenLayers.Bounds(xmin,ymin,xmax,ymax);
		log("fit bounds start");
		if(counter > 1)
		{
			if ( typeof(xmin) != "undefined" )
			{	
				fitMapToResults(searchBounds);
			}
		}
		
		var labelLayer = getLayerByName("Merki");
		addLabels(labelLayer);
		
		
		if(counter > noPerPage)
		{
			//Calculate number of pages to display
			//noPages = Math.ceil( numberOfResults/noPerPage );
			noPages = Math.ceil( counter/noPerPage ); 
			//alert("results: " + numberOfResults + " - no pages: " + noPages);
			strHTML += "<tr><td colspan=" + totalColumns + "><img src=img/clear.gif width=50 height=5 border=0><br>";
			if(currPage != 1)
			{
				strHTML += "&nbsp;<a href=# onclick=javascript:gotoPage(" + (currPage-1) + "); >Fyrri</a>";
			}
			for( i = 1; i <= noPages; i++)
			{
				if( i == currPage )
				{
					strHTML += "&nbsp;<b><font color=red>" + i + "</font></b>";
				}
				else
				{
					strHTML += "&nbsp;<a href=# onclick=javascript:gotoPage(" + i + "); >" + i + "</a>";
				}
			}
			if( currPage != noPages)
			{
				strHTML += "&nbsp;<a href=# onclick=javascript:gotoPage(" + (currPage+1) + "); >Næsta</a>";
			}
			strHTML += "<br><img src=img/clear.gif width=50 height=5 border=0><br>&nbsp;Leit fyrir ''" + document.getElementById("searchText").value + "'' skilaði " + counter + " niðurstöðum<br></td></tr>";
			
		}
				
		if (counter > 1)
		{
			if ( $j("#searchDropDown option:selected").val() == "heimilisfong" )
			{
				strHTML += "<tr><td><img src=img/clear.gif width=50 height=2 border=0><br>&nbsp;<a href=# onclick='disablePopup();'>Skoða allar niðurstöður á korti</a><br><img src=img/clear.gif width=50 height=2 border=0</td></tr>";
			}
		}
		
		counter = 0; //reset counter 
		
		if (numberOfResults == 0)
		{
			strHTML += "<tr><td>Ekkert fannst!</td></tr>";
		}
		else
		{
			// þarf ekki eftie auto zoom strHTML += "<br><b>Skoða allar niðurstöður á korti</b><br><br>";
			//setPOIlayer( $j("#searchText").val() );
			//smap.addLayer(isl_poi);	
		}
		
		strHTML += "</table>";
		strHTML += "</td></tr></table>";
		//document.getElementById("contactArea").innerHTML =  strHTML;
		$j("#contactArea").html(strHTML);
		$j("#sResultHeader").text("Leitarniðurstöður fyrir '"+$j("#searchText").val()+"'");
		
		if(numberOfResults != 1)
		{
			//if(clientNameNefni == "Visit Iceland")
				
			//centering with css
			centerPopup();
			//load popup
			$j("#sResultHeader").text("Leitarniðurstöður");
			loadPopup();
		}
	}//if readystate == 4
}


function gotoPage( pageNo )
{
	//alert( pageNo );
	currPage = pageNo;
	displayResults();
}

function setResultScale( no )
{
	//debugger;
	switch ( no )
	{
		case 0:
			scale = 250000;
			break;
		case 1:
			scale = 100000;
			break;
		case 2:
			scale = 50000;
			break;
		case 3:
			scale = 10000;
			break;
		case 4:
			scale = 10000;
			break;
		case 5:
			scale = 5000;
			break;
		case 6:
			scale = 2000;
			break;
		default:
			scale = 10000;
			break;
	}
	var sType = $j("#searchDropDown option:selected").val();
	if ( sType == "heimilisfong" )
	{
		scale = 500;
	}
	if ( sType == "heimilisfongSkaga" )
	{
		scale = 1000;
	}
	if ( sType == "heimilisfongAkureyri" )
	{
		scale = 1000;
	}
	if ( sType == "poi1" )
	{
		scale = 1000;
	}
	
	if ( sType == "heimilisfong" )
	{
		scale = 500;
	}
	if (clientNameNefni == "Skipulagsstofnun")
	{
		scale = 5000;
	}
	
	return scale;
}

function searchLinkClick(linkType, index)
{
	if (linkType == "jsFunction")
		searchLinkActionJS( linkParameters[index] );
	if (linkType == "extLink")
		searchLinkActionURL( linkParameters[index] );
}

function isdefined( variable)
{
    return (typeof(window[variable]) == "undefined")?  false: true;
}


// labels 
var AutoSizeAnchored = OpenLayers.Class(OpenLayers.Popup.Anchored, {'autoSize': true});

function addLabels(layer)
{
   //alert(isdefined(layer.markers));
	var theMarkers = layer.markers;

    if(layer.visibility == false || layer.popupFlag==true || !theMarkers)
    {
        var Msg = "";
        if(layer.popupFlag==true)
            Msg += "Layer " + layer.name + " hat schon Labels.\r\n";
        if(layer.visibility == false)
            Msg += "Layer " + layer.name + " muss sichtbar sein.\r\n";
        if(!theMarkers)
            Msg += "Layer " + layer.name + " hat keine Features\r\n";

        //alert(Msg);
        return false;
    }

    for(var i=0;i<theMarkers.length;i++)
    {
        //var objBounds = theMarkers[i].geometry.getBounds();

        //var x = theMarkers[i].lonlat.loon//(objBounds.left+objBounds.right)/2;
        //var y = theMarkers[i].lonlat.lon//(objBounds.top+objBounds.bottom)/2;

        var theAtt = theMarkers[i].labelText;//attributes.name.replace(/^sundial, /i,"");

        if(!theAtt)
            theAtt = "Feature " + i;

        var ll = theMarkers[i].lonlat; // new OpenLayers.LonLat(x,y);
        var objid = OpenLayers.Util.createUniqueID("LABEL_" + i + "_");
        popupClass = AutoSizeAnchored;
        markerLabelContentHTML = '<span id="' + objid + '" class="markerLabelHtml" >' + theAtt + '</span>';
        addLabel(ll, layer, map, popupClass, markerLabelContentHTML);
        layer.popupFlag = true;
    }
}
function addLabel(ll, layer, map, popupClass, popupContentHTML, closeBox, overflow) {

    var feature = new OpenLayers.Feature(layer, ll);
    feature.closeBox = closeBox;
    feature.popupClass = popupClass;
    feature.data.popupContentHTML = popupContentHTML;
    feature.data.overflow = (overflow) ? "auto" : "hidden";

    feature.popup = feature.createPopup(feature.closeBox);
    feature.popup.name = "LABEL_" + layer.id;
    map.addPopup(feature.popup);
    feature.popup.show();
    //opener.document.getElementById(feature.popup.id).onmousedown="dragStart(event, this.div)";
    document.getElementById(feature.popup.id).style.display="block";
    document.getElementById(feature.popup.id).style.cursor="default"; // has to be set unless you want text cursor
    document.getElementById(feature.popup.id).style.backgroundColor="transparent";
    document.getElementById(feature.popup.id).name=feature.popup.name;

    // Event if label is clicked
	/*feature.popup.events.register("mousedown", feature.popup, function (e) {
        dragStart(e, this.id);
    });*/

}

function delLabels(layer)
{
    var theMarkers = layer.markers;

    if(layer.visibility == false || layer.popupFlag==false || !theMarkers)
    {
        var Msg = "";
        if(layer.popupFlag==false)
            Msg += "Layer " + layer.name + " hat keine Labels.\r\n";
        if(layer.visibility == false)
            Msg += "Layer " + layer.name + " muss sichtbar sein.\r\n";
        if(!theMarkers)
            Msg += "Layer " + layer.name + " hat keine Features\r\n";

        //alert(Msg);
		return false;
    }

    var Anz=1;
    var obj = map.popups;

    var arrLabels=[];
    for(var i=0;i<obj.length;i++)
        if(obj[i].name=="LABEL_" + layer.id)
            arrLabels[arrLabels.length]=obj[i];

    for(var i=0;i<arrLabels.length;i++)
        eval(arrLabels[i]).destroy();

    layer.popupFlag = false;
}

function fitMapToResults(bounds)
{
	// Add procedure to zoom wider for single results
	map.zoomToExtent(searchBounds);
}

function requestShortURL(longURL, success) {
    //var API = 'http://reque.st/create.api.php?json&url=',
    //    URL = API + encodeURIComponent(longURL) + '&callback=?';
    //console.log('tweet apit url: ' + URL);
    
	// Tilraun
	var URL = 'http://tinyurl.com/api-create.php?url=' + longURL;
	
	$j.getJSON(URL, 
	function(data)
	{
        var blabla = 0;
		success && success(data.url);
    });
}


function fillCoordList( GML, geom )
{
	//debugger;
	var coordList = "";
	var isPoint = false;
	var retObj;

	if (GML == "<gml:MultiGeometry></gml:MultiGeometry>" || GML == "<gml:MultiGeometry srsName=\"EPSG:3057\"></gml:MultiGeometry>")
	{
		GML = geom;//$j(this).find("geom").text() ;
		isPoint = true;
		// Lets decide scale if info is available 
				// taka út í bili ... scale = setResultScale( Number($j(this).find("staerd").text()) );
		
		//alert(  Number($j(this).find("staerd").text()) );
		
		scale = setResultScale( 4 ); // Sett inn svo að þetta lendi ekki default á 250000
	}

	if( GML.match("MultiPoint srsName") == null )
	{
		var pointz = GML.split("<gml:Point>");
		if (pointz.length == 1)
			pointz = GML.split("<gml:Point srsName=\"EPSG:3057\">");
		for (var idx = 1; idx < pointz.length ; idx++ ) 
		{
			var temp = pointz[idx].split("</gml:Point>");
			pointz[idx-1] = temp[0]; // put coordinates into array again but at index minus one
			 scale = setResultScale( 4 );
		}
	}
	else
	{
		var pointz = GML.split("<gml:Point>");
		//pointz = pointz.split("<gml:Point srsName=\"EPSG:3057\">");
		for (var idx = 1; idx < pointz.length ; idx++ )
		{
			var temp = pointz[idx].split("</gml:Point>");
			pointz[idx-1] = temp[0]; // put coordinates into array again but at index minus one
			
			scale = setResultScale( 4 ); // Sett inn svo að þetta lendi ekki default á 250000
		}
	}
	
	pointz.pop(); // remove last element since it's garbage
							
	if (pointz.length > 1){}
		//isPoint = false;
	if(GML.indexOf("Polygon") >= 0){//Here we are dealing with a polygon
		temp=GML.split("gml:coordinates>");
		temp=temp[1].split("</");
		coor = temp[0].split(" ");
		pointz.push("<gml:coordinates>"+coor[0]+"</gml:coordinates>");
		pointz.push("<gml:coordinates>"+coor[2]+"</gml:coordinates>");
	}	
	else if(GML.indexOf("Point") >= 0){ //We have a point
		isPoint = true;
	}	
		
	for (var j = 0; j < pointz.length ; j++ )
	{
		if (j > 0)
			coordList += ",";
		var coordCut = pointz[j].split("<gml:coordinates>");
		var coordz = coordCut[1].split("</gml:coordinates>");
		coordList += coordz[0];	
	}
	
	
	return {'coordList':coordList,'isPoint':isPoint};
}
function extractValueByAttribute (attr,nodes){
	for(i = 0;i<nodes.length;i++)
	{
		if(nodes[i].tagName != undefined && nodes[i].tagName == attr)
			return (nodes[i].textContent != undefined) ? nodes[i].textContent : nodes[i].text;
	}
}



function getXMLDoc(xml)
{
	try //Internet Explorer
	{
		xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async="false";
		xmlDoc.loadXML(xml);
	}
	catch(e)
	{
		try //Firefox, Mozilla, Opera, etc.
		{
			parser=new DOMParser();
			xmlDoc=parser.parseFromString(xml,"text/xml");
		}
		catch(e)
		{
			alert(e.message);
		return;
		}
	}
	
	return xmlDoc;
}


function addTeiknibola(x, y)
{
	//Add marker
	var size = new OpenLayers.Size(32,32);
	var offset = new OpenLayers.Pixel(-(size.w/2-8), -size.h);
	var icon = new OpenLayers.Icon('img/teiknibola.png',size,offset);
	var leMarker = new OpenLayers.Marker( new OpenLayers.LonLat(x,y),icon );
	markers.addMarker(leMarker);
}