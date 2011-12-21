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

var poiIcons = new Array();
poiIcons["Airport"] = "Airport.png";poiIcons["Art"] = "Art.png";poiIcons["ATM"] = "ATM.png";poiIcons["Auto Service"] = "Auto_Service.png";poiIcons["Bank"] = "Bank.png";poiIcons["Bus Station"] = "Bus_Station.png";poiIcons["Camping"] = "Camping.png";poiIcons["Car Rental"] = "Car_Rental.png";poiIcons["Church"] = "Church.png";poiIcons["Cinema"] = "Cinema.png";poiIcons["City Hall"] = "City_Hall.png";poiIcons["Comunity Center"] = "Comunity_Center.png";poiIcons["Doctor"] = "Doctor.png";poiIcons["Emergency Shelter"] = "Emergency_Shelter.png";poiIcons["Ferry"] = "Ferry.png";poiIcons["Fire Station"] = "Fire_Station.png";poiIcons["Fishing"] = "Fishing.png";poiIcons["Golf"] = "Golf.png";poiIcons["Government Building"] = "Government Building.png";poiIcons["Hospital"] = "Hospital.png";poiIcons["Ice Skating"] = "Ice_Skating.png";poiIcons["Leisure"] = "Leisure.png";poiIcons["Library"] = "Library.png";poiIcons["Lodging"] = "Lodging.png";poiIcons["Monument"] = "Monument.png";poiIcons["Museum"] = "Museum.png";poiIcons["Park"] = "Park.png";poiIcons["Parking House"] = "Parking_House.png";poiIcons["Parking Lot"] = "Parking_Lot.png";poiIcons["Performing"] = "Performing.png";poiIcons["Petrol Station"] = "Petrol_Station.png";poiIcons["Pharmacy"] = "Pharmacy.png";poiIcons["Police"] = "Police.png";poiIcons["Post"] = "Post.png";poiIcons["Rest Area"] = "Rest_Area.png";poiIcons["Restaurant"] = "Restaurant.png";poiIcons["School"] = "School.png";poiIcons["Shop"] = "Shop.png";poiIcons["Shopping Centre"] = "Shopping_Centre.png";poiIcons["Sightseeing"] = "Sightseeing.png";poiIcons["Ski Resort"] = "Ski_Resort.png";poiIcons["Sport Centre"] = "Sport_Centre.png";poiIcons["Sport Stadium"] = "Sport_Stadium.png";poiIcons["Swimming Pool"] = "Swimming_Pool.png";poiIcons["Tennis"] = "Tennis.png";poiIcons["Tourist Info"] = "Tourist_Info.png";poiIcons["University"] = "University.png";poiIcons["View"] = "View.png";

var poiScales = new Array();
poiScales['Rest Area'] = new poi(20000, 1);poiScales['Park'] = new poi(10000, 4);poiScales['Camping'] = new poi(20000, 7);poiScales['Leisure'] = new poi(10000, 8);poiScales['Auto Service'] = new poi(10000, 9);poiScales['Petrol Station'] = new poi(10000, 10);poiScales['Car Rental'] = new poi(10000, 12);poiScales['Parking Lot'] = new poi(10000, 15);poiScales['Parking House'] = new poi(10000, 16);poiScales['Lodging'] = new poi(10000, 18);poiScales['Restaurant'] = new poi(10000, 19);poiScales['Museum'] = new poi(10000, 20);poiScales['Cinema'] = new poi(10000, 21);poiScales['Performing'] = new poi(10000, 22);poiScales['Tourist Info'] = new poi(33000, 27);poiScales['Sightseeing'] = new poi(33000, 28);poiScales['Monument'] = new poi(20000, 29);poiScales['View'] = new poi(33000, 30);poiScales['Church'] = new poi(33000, 31);poiScales['Sport Stadium'] = new poi(20000, 32);poiScales['Sport Centre'] = new poi(10000, 33);poiScales['Swimming'] = new poi(33000, 35);poiScales['Ice Skating'] = new poi(10000, 36);poiScales['Ski Resort'] = new poi(33000, 37);poiScales['Tennis'] = new poi(10000, 38);poiScales['Golf'] = new poi(20000, 40);poiScales['Hospital'] = new poi(20000, 41);poiScales['Doctor'] = new poi(10000, 43);poiScales['Pharmacy'] = new poi(20000, 45);poiScales['City Hall'] = new poi(10000, 47);poiScales['Government Building'] = new poi(10000, 48);poiScales['School'] = new poi(10000, 49);poiScales['University'] = new poi(10000, 50);poiScales['Library'] = new poi(10000, 52);poiScales['Police'] = new poi(33000, 53);poiScales['Post'] = new poi(20000, 54);poiScales['Shopping Centre'] = new poi(33000, 56);poiScales['Shop'] = new poi(20000, 57);poiScales['Bank'] = new poi(10000, 58);poiScales['Verslun og þjónusta'] = new poi(1000, 58);poiScales['ATM'] = new poi(10000, 59);poiScales['Airport'] = new poi(33000, 61);poiScales['Ferry'] = new poi(20000, 62);poiScales['Airport Arrival'] = new poi(10000, 65);poiScales['Airport Departure'] = new poi(10000, 66);poiScales['Bus Station'] = new poi(20000, 71);poiScales['Art'] = new poi(20000, 97);poiScales['Fire Station'] = new poi(20000, 98);poiScales['Comunity Center'] = new poi(10000, 300);poiScales['Fishing'] = new poi(33000, 300);poiScales['Emergency Shelter'] = new poi(33000, 301);

//Sýnir hvernig maður sækir gildin í fylkið
//alert(poiScales['Fishing'].scale);

function poi(scale, themeid){
    this.scale = scale;
    this.themeid = themeid;
}

var select_wfs_arr = [];


function routingPrintClick(){
    var fjoldi_lina_i_dalk = 10;
    var fjoldi = 0;
    var sHtml = "";
    jQuery.each( $j("#RoutingResultsDIV #rResultsHtml img"), function(i,val){

        if( i == 0 ){
            sHtml += "<div id='routingPrintResults' style='z-index:15;top:500px;left:500px;background-color:white;'>";
            sHtml += "<div style='float:left;'>";
        }

        sHtml += $j(this);

        if( i+1 == $j("#RoutingResultsDIV #rResultsHtml img").length ){

            sHtml += "</div><div>";
        }
        else if( ((i+1)%fjoldi_lina_i_dalk) == 0 ){

            sHtml += "</div>";
            sHtml += "<div style='float:left;'>";
        }
    });    
    //return sHtml;
    $j("body").append(sHtml);
}

function initPOI(){
    
    $j("#testarea").append("<br /><a id='poirofi' href='#' onclick='poiClick();' >POI on/off</a>");     
    //$j("#testarea").append("<br /><a href='#' onclick='routingPrintClick();' style='color:yellow'>prenta niðurstöður</a>");
<?php
   // Load wfs layer into map    
        foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
        if( $vectorlayer->layerName == "poi" )
        {
?>
    var defaultStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style({ 

            'fillColor':'white',
            'strokeColor': 'white',
            'strokeWidth': 1,
            'strokeOpacity': 0.1,
            'fillOpacity': 0.1,
            'pointRadius':3            
            
       });
            
    var selectStyle_<?=$vectorlayer->layerName?> = new OpenLayers.Style({	
            'fillColor':'blue',
            'strokeColor':'white',
            'strokeWidth': 8,
            'fillOpacity':0.3,
            'strokeOpacity': 0.3,
            'pointRadius':8,
            'cursor': 'pointer'            
        });

    //---- Factory to generate rules for the style --
    var themeRule;
    for ( var i in poiScales ){
        if( poiScales[i].themeid ){   // tékkar á hvort gildi er til staðar
            themeRule = new OpenLayers.Rule({
              filter: new OpenLayers.Filter.Comparison({
                  type: OpenLayers.Filter.Comparison.EQUAL_TO,
                  property: "themeid",
                  value: poiScales[i].themeid
              }), maxScaleDenominator:poiScales[i].scale
            });

            if( poiScales[i].themeid == "Police"){
                    alert(poiScales[i].themeid + " - skali:" + poiScales[i].scale);
            }
            defaultStyle_<?=$vectorlayer->layerName?>.addRules([themeRule]);
            selectStyle_<?=$vectorlayer->layerName?>.addRules([themeRule]); 
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
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>,  visibility:<?=$vectorlayer->visibility?>,
          extractAttributes: <?=$vectorlayer->extractAttributes?>, scales:<?=$vectorlayer->layerName?>_scales, styleMap:styleMap_<?=$vectorlayer->layerName?>});

    map.addLayers([<?=$vectorlayer->layerName?>_wfs]);
    client_select_wfs_arr.push(<?=$vectorlayer->layerName?>_wfs);

    <?=$vectorlayer->layerName?>_wfs.setVisibility(<?=$vectorlayer->visibility?>);
    /*
    var <?=$vectorlayer->layer->layerName?>_scales = [<?=$vectorlayer->layer->layerScales?>];
    
    var <?=$vectorlayer->layer->layerName?> = new OpenLayers.Layer.WMS.Untiled("<?=$vectorlayer->layer->layerTitle?>",
        ["<?=$vectorlayer->layer->url?>"],
        {layers: '<?=$vectorlayer->layer->layerNames?>',
        styles: '<?=$vectorlayer->layer->layerStyles?>',
         format:'<?=$vectorlayer->layer->format?>',
         transparent: true},
        {singleTile:true,
        'visibility':<?=$vectorlayer->layer->visibility?>,
        'displayInLayerSwitcher':true,
         'isBaseLayer': false,
         scales: <?=$vectorlayer->layer->layerName?>_scales,
         unsupportedBrowsers: []});

	map.addLayers([<?=$vectorlayer->layer->layerName?>]);    
    */
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
        
        
/*
<layer>
    <layerTitle>POI Vector</layerTitle>
    <layerName>poi</layerName>
    <layerType>WMS.Untiled</layerType>
    <url>http://geoserver.loftmyndir.is/geoserver/wms?</url>
    <layerNames>isl_poi_ordered</layerNames>
    <layerStyles></layerStyles>
    <format>image/png</format>
    <transparent>true</transparent>
    <visibility>false</visibility>
    <singleTile>true</singleTile>
    <displayInLayerSwitcher>true</displayInLayerSwitcher>
    <layerScales>50000, 25000, 10000, 5000, 2000, 1000, 500, 250</layerScales>
    <isBaseLayer>false</isBaseLayer>
    <unsupportedBrowsers>[]</unsupportedBrowsers>
</layer>         
*/
    }   
?>
}

function poiClick(){

    if(getLayerByName("POI Vector").getVisibility()) {		
      getLayerByName("POI Vector").setVisibility(false);
      $j('#LSCheckbox_02').removeAttr('checked');
      // breytum iconum í LS
      $j('#poi_icon').removeClass("poi_icon_on");
      $j('#poi_icon').addClass("poi_icon_off"); 
    }else{
      getLayerByName("POI Vector").setVisibility(true);
      $j('#LSCheckbox_02').attr('checked','checked');
      // breytum iconum í LS
      $j('#poi_icon').removeClass("poi_icon_off");
      $j('#poi_icon').addClass("poi_icon_on"); 
    }    
}

/*************************** OPENLAYER MOUSE HANDLERAR *******************************/
function onPoiClickCallback(feature, ix, iy){
    
}

function onPoiSelectCallback(feature, ix, iy){
    
    moveFlex();
    var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())	

    var aukaUppl = "";
    if( feature.attributes.ath != undefined ){
        if (feature.attributes.subcategor.toUpperCase() != feature.attributes.ath.toUpperCase() ){
            aukaUppl = " (" + feature.attributes.ath + ")";
        }
    }

    var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())		

    var upsidedowntextoffset = 52;
    strHtmlContents = "<div id='poiContainer' style='position:relative;top:-5px;left:-5px;height:125px !important;width:277px !important;z-index:15000;overflow:hidden;'>";  
    strHtmlContents += "    <div style='top:" + 4 + "px;' id='poiTemeIcon'>";
    strHtmlContents += "        <img  src='img/poi/" + poiIcons[feature.attributes.theme] + "'>";
    strHtmlContents += "    </div>";

    var headerName = feature.attributes.name;

    if (headerName.length > 31)
        headerName = headerName.substring(0,30) + "...";		

    strHtmlContents += "    <div style='top:" + 7 + "px;' id='poiHeader'><i>"+headerName+"</i></div>";
    strHtmlContents += "    <div style='top:" + 21 + "px;' id='poiSubHeader'>"+aukaUppl+"</div>";
    strHtmlContents += "    <div style='top:" + 42 + "px;' id='poiAddressIcon'>";
    strHtmlContents += "        <img  src='img/poi/address_icon.gif' border=0>";
    strHtmlContents += "    </div>";
    strHtmlContents += "    <div style='top:" + 41 + "px;' id='poiAddressText'>";

    var street = "";
    if(feature.attributes.street != undefined){
            strHtmlContents+= feature.attributes.street+"<br/>";
    }
    strHtmlContents+= feature.attributes.zip + " " + LM_CityFromZips[feature.attributes.zip] + "</div>" ;

    if(feature.attributes.phone_no != undefined ){
            strHtmlContents+= "<div style='top:" + 71 + "px;' id='poiTelephoneText'>Sími:  " + feature.attributes.phone_no.substr(0,3) + " " + feature.attributes.phone_no.substr(3,5) + "</div>";
    }		
    if(feature.attributes.homepage != undefined ){
            strHtmlContents +="<div style='top:" + 88 + "px;' id='poiHomePageIcon'><img  src='img/poi/webpage_icon.gif' border='0'></div>";
            strHtmlContents+= "<div style='top:" + 90 + "px;' id='poiHomePageText' class='poiHomePageText'><a href=http://" + feature.attributes.homepage + " target='_blank'>Skoða heimasíðu</a></div>";
    }
    var nananame = feature.attributes.name;
    var lelezippo = feature.attributes.zip;
    strHtmlContents += "<div style='top:" + 23 + "px;'  id='poiEditIcon'><a href='#modalpopup2'><img  src='img/poi/edit_icon.gif' border='0'></a></div>";
    strHtmlContents += "<div style='top:" + 22 + "px;' id='poiEditText'><a href='#modalpopup2'>Leiðrétta</a></div>";
    // Senda
    strHtmlContents += "<div style='top:" + 111 + "px;' id='poiSendIcon'><a href='#modalpopup' title='Smelltu hér til að senda kortið í tölvupósti'><img  src='img/poi/send_icon.gif' border='0'></a></div>";
    strHtmlContents += "<div style='top:" + 113 + "px;' id='poiSendText'><a href='#modalpopup' title='Smelltu hér til að senda kortið í tölvupósti'>Senda</a></div>";
    // Facebook
    strHtmlContents += "<div style='top:" + 111 + "px;' id='poiFacebookIcon'><a rel='nofollow' onclick=\"shareFacebook();\" href='#' title='Smelltu hér til að deila kortinu á Facebook'><img  src='img/poi/facebook_icon.gif' border='0'></a></div>";
    strHtmlContents += "<div style='top:" + 113 + "px;' id='poiFacebookText'><a rel='nofollow' onclick=\"shareFacebook();\" href='#' title='Smelltu hér til að deila kortinu á Facebook'>Facebook</a></div>";
    // Twitter má lagfæra til geta notað íslenska stafi
    strHtmlContents += "<div style='top:" + 111 + "px;' id='poiTwitterIcon'><a rel='nofollow' onclick='shareTwitter();' href='#' title='Smelltu hér til að deila kortinu á Twitter'><img src='img/poi/twitter_icon.gif' border='0'></a></div>";
    strHtmlContents += "<div style='top:" + 113 + "px;' id='poiTwitterText'><a rel='nofollow' onclick='shareTwitter();' href='#' title='Smelltu hér til að deila kortinu á Twitter'>Twitter</a></div>";
    strHtmlContents += "</div>";

    var tooltipFlexObject = [];
    tooltipFlexObject['toolTipContent'] = strHtmlContents;
    tooltipFlexObject['topOffsetHeight'] = 185;
    tooltipFlexObject['bottomOffsetHeight'] = 30;
    tooltipFlexObject['pixelFromLonLat'] = px;
    tooltipFlexObject['ix'] = ix;
    tooltipFlexObject['iy'] = iy;
    tooltipFlexObject['leftOffset'] = 5;
    tooltipFlexObject['rightOffset'] = 15;

     // senda með innihald, topoffset, bottomoffset, getPixelFromLonLat
     positionTooltipFlex(tooltipFlexObject);   
    
    var linkur = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    var linkur_text = "Hæ, mig langar að deila með þér þessu korti af www.map.is - Loftmyndir ehf. Vefslóð(stytt):<" + linkur + ">";
    /*strHTML = "<div class='sendMailText' id='sendMail' style='background:#FFFFFF'>";
    strHTML += "<div class='sendMailToDivTitle' id=''>Hér getur þú sent slóð á kortið á tölvupóstfang</div>";
    strHTML += "<div class='sendMailToDiv' id=''><div><b>Til:</b></div><div><input type='text' class='sendMailTo'  id='sendMailToSendMail'/></div>Aðskiljið netföng með, eða ; &nbsp;&nbsp;&nbsp;<i> (t.d. abc@gmail.com; def@gmail.com)</i></div>";
    strHTML += "<div class='sendMailFromDiv'><div><b>Frá:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Ath. þennan reit verður að fylla út</i></div><div><input type='text' class='sendMailFrom' id='sendMailFromSendMail'/></div><input type='checkbox' id='sendMailCopy2MeSendMail' /> Senda afrit á netfangið mitt</div>";
    strHTML += "<div class='sendMailMessageDiv'><div><b>Skilaboð:</b></div><div><textarea class='sendMailMessage' id='sendMailMessageSendMail'>Hæ, mig langar að deila með þér þessu korti af www.map.is - Loftmyndir ehf. Vefslóð(stytt):<" + linkur + "></textarea><div></div>";
    strHTML += "<div class='sendMailButtonsDiv'><input type='button' value='Senda' onclick='sendMail();closeModalWindow();' /> <input type='button' value='Hætta við' onclick='closeModalWindow();' /></div>";    
    strHTML += "</div>";*/
    
    //$j("#modalpopup").html(strHTML); 
    $j("#modalpopup").html(getEmailText(linkur_text));
    doModal($j('#poiSendIcon a')); 
    doModal($j('#poiSendText a')); 
    
    strHTML = "<div class='sendMailText'id='sendMailCorrection' style='background:#FFFFFF'>";
    strHTML += "<div class='sendMailToDivTitle'>Senda leiðréttingu | " + nananame  + " - " + LM_CityFromZips[lelezippo] + "</div>";
    strHTML += "<div class='sendMailMessageDiv'>Ef upplýsingar á kortinu eru rangar getur þú sent okkur athugasemd.</div>";
    linkur = viewLink.div.childNodes[0];
    var featureInfo = nananame  + " - " + LM_CityFromZips[lelezippo];
    strHTML += "<div class='sendMailMessageDiv2'><div><b>Skilaboð:</b></div><div><textarea class='sendMailMessage' id='sendMailMessageCorrection'></textarea></div></div>";
    strHTML += "<div class='sendMailButtonsDiv2'><input type='button' value='Senda' onclick='sendCorrectionMail(\"" + featureInfo + "\");closeModalWindow();'> <input type='button' value='Hætta við' onclick='closeModalWindow();'></div>";
    strHTML += "</div>";
    
    $j("#modalpopup2").html(strHTML); 
    doModal($j('#poiEditIcon a')); 
    doModal($j('#poiEditText a')); 
    
}

function onPoiUnselectCallback(feature){
    closeFlex();
    moveFlex();
}

function displayLeidrettingForm(featureName, zip){
    strHTML = "<div id=sendMailToDiv>Ef upplýsingar á kortinu eru rangar getur þú sent okkur athugasemd.</div>";
    var linkur = viewLink.div.childNodes[0];
    var featureInfo = featureName  + " - " + LM_CityFromZips[zip];
    strHTML += "<div id='sendMailMessageDiv'><b>Skilaboð:</b><br><textarea id='sendMailMessage'></textarea></div>";
    strHTML += "<div id='sendMailButtonsDiv'><input type='button' value='Senda' onclick='sendCorrectionMail(\"" + featureInfo + "\");'> <input type='button' value='Hætta við' onclick='disablePopup();'></div>";
    $j("#sResultHeader").html("Senda leiðréttingu | " + featureName  + " - " + LM_CityFromZips[zip] );
    $j("#contactArea").html(strHTML);
    centerPopup();
    loadPopup();
}

function sendCorrectionMail(featureInfo){
    var message = escape($j("#sendMailMessageCorrection").val());
    var stringToQuery = "to=jonas@loftmyndir.is&";
    stringToQuery += "from=noreply@loftmyndir.is&";
    stringToQuery += "message=" + escape(featureInfo) + " | " + message;
    sendAJAXRequest("http://www.loftmyndir.is/k/sendCorrectionEmail.asp?"+stringToQuery, sendEmailCallback)
}

// Twitter sharing
function shareTwitter(title){
    // Get location and use tinyUrl.
    // Needs to be synchronous to make sure tinyUrl is retrieved before sumitting to Twitter
    var url = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    if ( title == "" )
    title = "Skoda kortasja"; // Default if title is emtpy
    // Construct the url to send to the popup window
    var newWindowUrl = "http://twitter.com/home?status=" + encodeURIComponent(title) + " >> " + url;
    var W;        
    w = window.open(newWindowUrl, "_blank","toolbar=0,status=0,width=626,height=436");
    return false;
}
// Facebook sharing
function shareFacebook(){

    var title = "www.map.is - Loftmyndir ehf"; // Default if title is emtpy
    // Construct the url to send to the popup window
    var url = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );  //viewLink.div.childNodes[0].toString().replace("#","")
    window.open('http://www.facebook.com/sharer.php?u='+url+'&t='+encodeURIComponent(title),'sharer','toolbar=0,status=0,width=626,height=436');
    return false;
} 

function displaySendForm(){
    // use tinyUrl to generate shorter link string
    var linkur = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    strHTML = "<div style='font-weight:bold;font-size:12px;'>Hér getur þú sent slóð á kortið á tölvupóstfang</div>";
    strHTML += "<div id='sendMailToDiv'><b>Til:</b><br /><input type='text' id='sendMailTo' /><br>Aðskiljið netföng með, eða ; &nbsp;&nbsp;&nbsp;<i> (t.d. abc@gmail.com; def@gmail.com)</i></div>";
    strHTML += "<div id='sendMailFromDiv'><b>Frá:</b><br><input type='text' id='sendMailFrom' /> <b>Ath. þennan reit verður að fylla út</b><br /><input type='checkbox' id='sendMailCopy2Me' /> Senda afrit á netfangið mitt</div>";
    strHTML += "<div id='sendMailMessageDiv'><b>Skilaboð:</b><br><textarea id='sendMailMessage'>Hæ, mig langar að deila með þér þessu korti af www.map.is - Loftmyndir ehf. Vefslóð(stytt):<" + linkur + "></textarea></div>";
    strHTML += "<div id='sendMailButtonsDiv'><input type='button' value='Senda' onclick='sendMail();' /> <input type='button' value='Hætta við' onclick='disablePopup();' /></div>";
    $j("#contactArea").html(strHTML);
    centerPopup();
    loadPopup();
}

function sendMail(){
    var from = $j("#sendMailFromSendMail").val();
    if ( from == "" ){
        alert("Sláðu inn netfang sendanda!");
        return;
    }
    var message = escape($j("#sendMailMessageSendMail").val());
    var sendMeCopy = $j("#sendMailCopy2MeSendMail").attr('checked');
    var to = $j("#sendMailToSendMail").val();
    if(sendMeCopy){
        to += "," + from; // Bæta sendanda aftast í listann
    }
    to = to.replace(";",","); //Skipti semikommum út fyrir kommur
    var emailList = to.split(","); //splitta í fylki
    var verifiedEmails = new Array();
    var teljari = 0;
    while(teljari < emailList.length){
        if(echeck( trim( emailList[teljari] ) )){
                verifiedEmails.push( trim( emailList[teljari] ) );
        }
        teljari += 1;
    }
    var checkedEmails = "";
    teljari = 0;
    while(teljari < verifiedEmails.length){
        //alert(emailList[teljari]);
        checkedEmails += verifiedEmails[teljari];
        teljari += 1;
        if (teljari != verifiedEmails.length)
                checkedEmails += ",";
    }
    if (checkedEmails == ""){
        alert("Vinsamlegast sláið inn gilt netfang!");
        return;
    }
    var stringToPost = "to=" + checkedEmails + ",";
    stringToPost += "from=" + from + ",";
    stringToPost += "message=" + message;
    var stringToQuery = "to=" + checkedEmails + "&";
    stringToQuery += "from=" + from + "&";
    stringToQuery += "message=" + message;
    sendAJAXRequest("http://www.loftmyndir.is/k/sendEmail.asp?"+stringToQuery, sendEmailCallback);
    //to be continued!!!!
}

// Checks if emails are valid
// In: Email (string)
// Out: true or flase (boolean)
function echeck(str) {
    var at="@";
    var dot=".";
    var lat=str.indexOf(at);
    var lstr=str.length;
    var ldot=str.indexOf(dot);
    if (str.indexOf(at)==-1){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.indexOf(at,(lat+1))!=-1){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.indexOf(dot,(lat+2))==-1){
            //alert("Invalid E-mail ID")
            return false;
    }
    if (str.indexOf(" ")!=-1){
            //alert("Invalid E-mail ID")
            return false;
    }
    return true;
}
function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}
function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
function sendEmailCallback(){
    // resdyState 4 means response complete and time to work the results
    if(xmlHttp.readyState==4)
    {
            //alert(xmlHttp.responseText);
            disablePopup();
    }
}
//<script>