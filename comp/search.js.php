<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* BABYSTEPS TOWARDS REACHING STATE OF SEARCHVANA ...Googleegoo beware!
 * #1 Create user interface and add to page
 * #2 Prepare search for submission
 * #3 Create a proxy script to speak to DB
 * #4 Do some kind of magic with the DB to return sensible results
 * #5 Create a UI to display the results
 */
?>

function submitMapSearch()
{
    $j("#sliderAccordion").accordion("activate", false);
    theKeyTheKey = $j('#searchInputString').val();
    $j.getJSON('db/mapSearchQueryDB.php?searchString=' + theKeyTheKey, displayMapSearchResults);
}

function displayMapSearchResults(data)
{    
    
    
    var displayedResults = 0;
    if (data.length > 15)
        displayedResults = 15;
    else
        displayedResults = data.length;

    htmlString = '<div style="font-size: 8pt">Leitarniðurstöður 1-' + displayedResults + ' (af ' + data.length + ')<br/><br/></div>';
    var features = []; // To hold return features (markers)
    var in_options = { 'internalProjection': map.baseLayer.projection, 'externalProjection': new OpenLayers.Projection("EPSG:3057") }; 
    jQuery.each(data, function(index,item){
    
        
		// finnum flokkinn
        var resultType = "nadazippiddy";
        var addressTxt = "";
        var street = "";
        if(item.street)
            street = item.street + ", ";
        var zip = "";
        if(item.zip)
            zip = item.zip + " ";
        var city = "";
        if(item.city)
            city = item.city;
        switch(item.type)
        {
        case "poi":
          resultType = item.poi_theme_id;
          addressTxt = street + zip + city;
          break;
        case "postfong":
          resultType = "postfong";
          addressTxt = zip + item.city;
          break;
        case "ornefni_punkt":
          resultType = "ornefni_punkt"
          addressTxt = city;
          break;
         case "ornefni_lin":
          resultType = "ornefni_lin"
          addressTxt = city;
          break;
        default:
          //code to be executed if n is different from case 1 and 2
        }
        
        // Create a feature from each search item
        var gj = new OpenLayers.Format.GeoJSON(in_options);
	features.push(gj.read(item.the_geomjson));
        
        var centerLonLat = features[index][0].geometry.getBounds().getCenterLonLat();
        
        var x = centerLonLat.lon;
        var y = centerLonLat.lat;
        //var x = features[index][0].geometry.x;
        //var y = features[index][0].geometry.y;
        htmlString += '<div id="searchResultBox-' + (index/1+1) + '" class="searchResultBox">';
	htmlString += '<div id="searchResultNumberDropHolder-' + (index/1+1) + '" class="searchResultNumberDropHolder"><div id="searchResultNumberDrop-' + (index/1+1) + '" class="place-index-icon place-index-icon-' + (index/1+1) + '"></div></div>';
	htmlString += '<div id="searchResultBoxTextContainer-' + (index/1+1) + '" class="searchResultBoxTextContainer">';
        htmlString += '<div id="searchResultTypeIceon-' + (index/1+1) + '" class="searchResultTypeIceon"><div id="s_result_icon-' + (index/1+1) + '" class="s_result_icon s_result_icon_' + resultType + '"></div></div>';
        htmlString += '<div id="searchResultHeaderText-' + (index/1+1) + '" class="searchResultHeaderText"><a href=# onclick="zoomToXY(' + x + ', ' + y + ', 10)">' + item.searchkeyword + '</a></div>';
        htmlString += '<div id="searchResultContent-' + (index/1+1) + '" class="searchResultContent">' + addressTxt;
	if (item.type == 'poi')
        {
            if(item.phone)
            {
                htmlString += '<br/>sími: ' + item.phone;
            }
            if(item.webpage)
            {
                htmlString += ' <font color="#BBBBBB">&#8226;</font> <a href="' + item.webpage + '" target="blank">' + item.webpage + '</a>';
            }
        }
        htmlString += '</div>';
        htmlString += '</div></div>';
        
        
        
        
             
        var typeToken;
        switch (item.type) {
            case ("postfong"):
                typeToken = "-P-";
                break;
            case ("ornefni_punkt"):
                typeToken = "-OP-";
                break;
            case ("ornefni_lin"):
                typeToken = "-OL-";
                break;
            case ("poi"):
                typeToken = "-POI-";
                break;
            default:
                typeToken = "-DOA-";
         }
         
                    
       
        // Here's where we piece together the HTML
        //htmlString += typeToken + " " + item.searchkeyword + "</br> ";
        
    });
    
    // Sendum niðurstöðurnar í spaltann vinstramegin
    $j("#searchResultPanel").html(htmlString);
    
    // opna spalta
    //$j('#suggestions').hide();
    //$j("#sliderAccordion").accordion("activate", false);
    $j("#sliderAccordion").accordion("activate", 1);
    
    var bounds;
    if(features) 
    {
        if(features.constructor != Array) 
        {
                features = [features];
        }
    
        //Byrjum á merkerunum
        markers.clearMarkers();
        var size = new OpenLayers.Size(24, 27);
        var offset = new OpenLayers.Pixel(-(size.w / 2 ), -(size.h));
        
        // Setti sprite-inn í ótímabundna frystingu þar sem útfærslan virtist tímafrek
        //var icon = new OpenLayers.Icon('img/routing/places_sprite_ovi.png', size, offset);
    
    
        var leMarker = new OpenLayers.Marker();
        
        // Tryggjum að hægt sé að smella á markerana með því færa þá upp í Z-index
        markers.setZIndex(10000);
        
        for(var i=0; i<features.length; i++) 
        {
            // add marker
            var icon_url = 'img/routing/mapis_markers/i' + (i+1) + '.png';
            var icon = new OpenLayers.Icon(icon_url, size, offset);
            // Sækjum miðpunkt geometríunnar til að hægt sé að setja marker á línur og polygona
            //var geometryBounds = new OpenLayers.Bounds( features[i][0].geometry.getBounds() );
            var geometryBounds = features[i][0].geometry.getBounds();
            var markerLonLat = features[i][0].geometry.getBounds().getCenterLonLat();
            leMarker = new OpenLayers.Marker(new OpenLayers.LonLat(markerLonLat.lon, markerLonLat.lat), icon);
            
            leMarker.markerLonLat = markerLonLat;
            
            leMarker.events.register("click", leMarker, function(evt) { zoomToXY(this.markerLonLat.lon,this.markerLonLat.lat,10) });
            
            markers.addMarker(leMarker);
            
            if (!bounds) {
                    bounds = features[i][0].geometry.getBounds();
            } else {
                    bounds.extend(features[i][0].geometry.getBounds());
            }
            
            // Opna sliderinn ef hann er lokaður til að sýna leitarniðurstöður
            if ($j('#sliderPanelBtn').hasClass('close'))
            {
                $j("a#sliderPanelBtn").click();
            }
            
        }
        vectors.addFeatures(features);
        map.zoomToExtent(bounds);
    } 
    else 
    {
        alert('Villa kom upp. ID virkar ekki');
    }

}

function initSearch()
{

   
}

function addSearchMarker(x, y, number)
{
	//Add marker
	var size = new OpenLayers.Size(32,32);
	var offset = new OpenLayers.Pixel(-(size.w/2-8), -size.h);
	var icon = new OpenLayers.Icon('img/teiknibola.png',size,offset);
	var leMarker = new OpenLayers.Marker( new OpenLayers.LonLat(x,y),icon );
	markers.addMarker(leMarker);
}

function zoomToXY(Lon, Lat, zoomLevel)
{
    var lonlat = new OpenLayers.LonLat( Number(Lon), Number(Lat) );
    map.setCenter( lonlat,zoomLevel);
}
