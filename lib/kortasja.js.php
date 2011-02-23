
<?php
	// Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>
// Höfum map breytuna global svo við getum referencað hana utan init fallsins

var map = null;
var markers = null;


function initmap()
{

<?php	
    

        // Load OpenLayers map into interface
	foreach ($config->xpath('//map') as $map) 
	{
    
?>		
	map = new OpenLayers.Map('map', {
                                            maxExtent: new OpenLayers.Bounds(<?=$map->maxExtent?>),
                                            <?php if ($map->restrictedExtent != ""){ ?>restrictedExtent: new OpenLayers.Bounds("<?=$map->restrictedExtent?>"),<?php } ?>
                                            units:'m',
                                            maxResolution: '180/256',
                                            scales:[<?=$map->scales?>],
                                            projection:"<?=$map->projection?>"
                                            });	
	
<?php 
	} 

	// Load base layers into map
	foreach ($config->xpath('//baseLayer') as $baseLayer) 
	{
?>		
	var <?=$baseLayer->layerName?> = new OpenLayers.Layer.<?=$baseLayer->tileCacheType?>("<?=$baseLayer->layerTitle?>",
			["<?=$baseLayer->url?>"],	
			{	layername:'<?=$baseLayer->layerName?>',
				type:'<?=$baseLayer->imageFormat?>', 
				kortasja: '<?=$baseLayer->logName?>',
				serviceVersion:'',
				isBaseLayer: true,
				displayInLayerSwitcher:true, 
				attribution: '<?=$baseLayer->attribution?>',  
				transitionEffect:'<?=$baseLayer->transitionEffect?>', 
				scales: [<?=$baseLayer->scales?>],
				//maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
				//bbox: new OpenLayers.Bounds(143000,255000,866000,735000),
				//scales:[1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000,1000,500,250],
				buffer:<?=$baseLayer->buffer?>});
	
	map.addLayer(<?=$baseLayer->layerName?>);
	
<?php 
	}

	

	// Load base layers into map
	foreach ($config->xpath('//layer') as $layer)
	{
?>

        var <?=$layer->layerName?> = new OpenLayers.Layer.WMS.Untiled("<?=$layer->layerTitle?>",
            ["<?=$layer->url?>"],
            {layers: '<?=$layer->layerNames?>',
            styles: '<?=$layer->layerStyles?>',
             format:'<?=$layer->format?>',
             transparent: true},
            {singleTile:true,
            'visibility':<?=$layer->visibility?>,
            'displayInLayerSwitcher':true,
             'isBaseLayer': false,
             unsupportedBrowsers: []});

	map.addLayer(<?=$layer->layerName?>);

<?php
	}
?>




	map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.zoomToMaxExtent();
	
	onAppResize();
	map.updateSize();
	
	var lonlat = new OpenLayers.LonLat(420000,500000);
	map.setCenter(lonlat, 2,false,false);

        markers = new OpenLayers.Layer.Markers("Merki",{'displayInLayerSwitcher':false});
        map.addLayer(markers);
 }

function initAjax()
{

        try
        {
                // Firefox, Opera 8.0+, Safari
                xmlHttp=new XMLHttpRequest();
        }
        catch (e)
        {
                // Internet Explorer
                try
                {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                alert("Your browser does not support AJAX!");
                        }
                }

        }
}
/****************************** sendAJAXRequest ****/
function sendAJAXRequest(url, callback)
{
        initAjax();
        xmlHttp.onreadystatechange = callback
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
}

function sendSyncAJAXRequest(url)
{
	initAjax();
	//xmlHttp.onreadystatechange = callback;
	xmlHttp.open("GET",url,false);
	xmlHttp.send(null);
	return xmlHttp.responseText;
	//alert(xmlHttp.responseText);
}


//LMzips -------------
// Notkun: LMzips["101"] skilar "Reykjavík"
// Skilar bæjarfélagi í þágufalli
var LM_CityFromZips = {"101":"Reykjavík",
"102":"(Millilanda Póstur)",
"103":"Reykjavíkurborg",
"104":"Reykjavíkborg",
"105":"Reykjavíkborg",
"107":"Reykjavíkborg",
"108":"Reykjavíkborg",
"109":"Reykjavíkborg",
"110":"Reykjavíkborg",
"111":"Reykjavíkborg",
"112":"Reykjavíkborg",
"113":"Reykjavíkborg",
"116":"Reykjavíkborg",
"121":"Reykjavíkborg",
"123":"Reykjavíkborg",
"124":"Reykjavíkborg",
"125":"Reykjavíkborg",
"127":"Reykjavíkborg",
"128":"Reykjavíkborg",
"129":"Reykjavíkborg",
"130":"Reykjavíkborg",
"132":"Reykjavíkborg",
"150":"Reykjavíkborg",
"155":"Reykjavíkborg",
"170":"Seltjarnarnesi",
"172":"Seltjarnarnesi",
"190":"Vogum",
"200":"Kópavogi",
"201":"Kópavogi",
"202":"Kópavogi",
"203":"Kópavogi",
"210":"Garðabæ",
"212":"Garðabæ",
"220":"Hafnarfirði",
"221":"Hafnarfirði",
"222":"Hafnarfirði",
"225":"Álftanesi",
"230":"Reykjanesbæ",
"232":"Reykjanesbæ",
"233":"Reykjanesbæ",
"235":"Reykjanesbæ",
"240":"Grindavík",
"245":"Sandgerði",
"250":"Garði",
"260":"Reykjanesbæ",
"270":"Mosfellsbæ",
"300":"Akranesi",
"301":"Akranesi",
"302":"Akranesi",
"310":"Borgarnesi",
"311":"Borgarnesi",
"320":"Reykholt í Borgarfirði",
"340":"Stykkishólmi",
"345":"Flatey á Breiðafirði",
"350":"Grundarfirði",
"355":"Ólafsvík",
"356":"Snæfellsbæ",
"360":"Hellissandi",
"370":"Búðardal",
"371":"Búðardal",
"380":"Reykhólahreppi",
"400":"Ísafirði",
"401":"Ísafirði",
"410":"Hnífsdal",
"415":"Bolungarvík",
"420":"Súðavík",
"425":"Flateyri",
"430":"Suðureyri",
"450":"Patreksfirði",
"451":"Patreksfirði",
"460":"Tálknafirði",
"465":"Bíldudal",
"470":"Þingeyri",
"471":"Þingeyri",
"500":"Stað",
"510":"Hólmavík",
"512":"Hólmavík",
"520":"Drangsnesi",
"522":"Kjörvogi",
"523":"Bæ",
"524":"Norðurfirði",
"530":"Hvammstanga",
"531":"Hvammstanga",
"540":"Blönduósi",
"541":"Blönduósi",
"545":"Skagaströnd",
"550":"Sauðárkróki",
"551":"Sauðárkróki",
"560":"Varmahlíð",
"565":"Hofsós",
"566":"Hofsós",
"570":"Fljótum",
"580":"Siglufirði",
"600":"Akureyri",
"601":"Akureyri",
"602":"Akureyri",
"603":"Akureyri",
"610":"Grenivík",
"611":"Grímsey",
"620":"Dalvík",
"621":"Dalvík",
"625":"Ólafsfirði",
"630":"Hrísey",
"640":"Húsavík",
"641":"Húsavík",
"645":"Fosshólli",
"650":"Laugum",
"660":"Mývatni",
"670":"Kópaskeri",
"671":"Kópaskeri",
"675":"Raufarhöfn",
"680":"Þórshöfn",
"681":"Þórshöfn",
"685":"Bakkafirði",
"690":"Vopnafirði",
"700":"Egilsstöðum",
"701":"Egilsstöðum",
"710":"Seyðisfirði",
"715":"Mjóafirði",
"720":"Borgarfirði (eystri)",
"730":"Reyðarfirði",
"735":"Eskifirði",
"740":"Neskaupstað",
"750":"Fáskrúðsfirði",
"755":"Stöðvarfirði",
"760":"Breiðdalsvík",
"765":"Djúpavogi",
"780":"Höfn í Hornafirði",
"781":"Höfn í Hornafirði",
"785":"Öræfum",
"800":"Selfossi",
"801":"Selfossi",
"802":"Selfossi",
"810":"Hveragerði",
"815":"Þorlákshöfn",
"820":"Eyrarbakka",
"825":"Stokkseyri",
"840":"Laugarvatni",
"845":"Flúðum",
"850":"Hellu",
"851":"Hellu",
"860":"Hvolsvelli",
"861":"Hvolsvelli",
"870":"Vík",
"871":"Vík",
"880":"Kirkjubæjarklaustri",
"900":"Vestmannaeyjum",
"902":"Vestmannaeyjum"};

var LM_CityFromZipsNefni = {"101":"Reykjavík",
"102":"(Millilanda Póstur)",
"103":"Reykjavík",
"104":"Reykjavík",
"105":"Reykjavík",
"107":"Reykjavík",
"108":"Reykjavík",
"109":"Reykjavík",
"110":"Reykjavík",
"111":"Reykjavík",
"112":"Reykjavík",
"113":"Reykjavík",
"116":"Reykjavík",
"121":"Reykjavík",
"123":"Reykjavík",
"124":"Reykjavík",
"125":"Reykjavík",
"127":"Reykjavík",
"128":"Reykjavík",
"129":"Reykjavík",
"130":"Reykjavík",
"132":"Reykjavík",
"150":"Reykjavík",
"155":"Reykjavík",
"170":"Seltjarnarnes",
"172":"Seltjarnarnes",
"190":"Vogar",
"200":"Kópavogur",
"201":"Kópavogur",
"202":"Kópavogur",
"203":"Kópavogur",
"210":"Garðabær",
"212":"Garðabær",
"220":"Hafnarfjörður",
"221":"Hafnarfjörður",
"222":"Hafnarfjörður",
"225":"Álftanes",
"230":"Reykjanesbær",
"232":"Reykjanesbær",
"233":"Reykjanesbær",
"235":"Reykjanesbær",
"240":"Grindavík",
"245":"Sandgerði",
"250":"Garður",
"260":"Reykjanesbær",
"270":"Mosfellsbær",
"300":"Akranes",
"301":"Akranes",
"302":"Akranes",
"310":"Borgarnes",
"311":"Borgarnes",
"320":"Borgarbyggð",
"340":"Stykkishólmur",
"345":"Flatey",
"350":"Grundarfjörður",
"355":"Ólafsvík",
"356":"Snæfellsbær",
"360":"Hellissandur",
"370":"Búðardalur",
"371":"Búðardalur",
"380":"Reykhólahreppur",
"400":"Ísafirður",
"401":"Ísafirður",
"410":"Hnífsdalur",
"415":"Bolungarvík",
"420":"Súðavík",
"425":"Flateyri",
"430":"Suðureyri",
"450":"Patreksfjörður",
"451":"Patreksfjörður",
"460":"Tálknafjörður",
"465":"Bíldudalur",
"470":"Þingeyri",
"471":"Þingeyri",
"500":"Staður",
"510":"Hólmavík",
"512":"Hólmavík",
"520":"Drangsnes",
"522":"Kjörvogur",
"523":"Bær",
"524":"Norðurfjörður",
"530":"Hvammstangi",
"531":"Hvammstangi",
"540":"Blönduós",
"541":"Blönduós",
"545":"Skagaströnd",
"550":"Sauðárkrókur",
"551":"Sauðárkrókur",
"560":"Varmahlíð",
"565":"Hofsós",
"566":"Hofsós",
"570":"Fljót",
"580":"Siglufjörður",
"600":"Akureyri",
"601":"Akureyri",
"602":"Akureyri",
"603":"Akureyri",
"610":"Grenivík",
"611":"Grímsey",
"620":"Dalvík",
"621":"Dalvík",
"625":"Ólafsfjörður",
"630":"Hrísey",
"640":"Húsavík",
"641":"Húsavík",
"645":"Fosshóll",
"650":"Laugar",
"660":"Mývatn",
"670":"Kópasker",
"671":"Kópasker",
"675":"Raufarhöfn",
"680":"Þórshöfn",
"681":"Þórshöfn",
"685":"Bakkafjörður",
"690":"Vopnafjörður",
"700":"Egilsstaðir",
"701":"Egilsstaðir",
"710":"Seyðisfjörður",
"715":"Mjóafjörður",
"720":"Bakkagerði",
"730":"Reyðarfjörður",
"735":"Eskifjörður",
"740":"Neskaupstaður",
"750":"Fáskrúðsfjörður",
"755":"Stöðvarfjörður",
"760":"Breiðdalsvík",
"765":"Djúpavogur",
"780":"Höfn",
"781":"Höfn",
"785":"Öræfi",
"800":"Selfoss",
"801":"Selfoss",
"802":"Selfoss",
"810":"Hveragerði",
"815":"Þorlákshöfn",
"820":"Eyrarbakki",
"825":"Stokkseyri",
"840":"Laugarvatn",
"845":"Flúðir",
"850":"Hella",
"851":"Hella",
"860":"Hvolsvöllur",
"861":"Hvolsvöllur",
"870":"Vík",
"871":"Vík",
"880":"Kirkjubæjarklaustur",
"900":"Vestmannaeyjar",
"902":"Vestmannaeyjar"};


<?php
foreach ($config->xpath('//component') as $comp)
        {
            include '../comp/' . $comp->componentFile;
	}
?>

function initComponents()
{
    <?php
    foreach ($config->xpath('//component') as $comp)
            {
                echo $comp->componentInitScript;
            }
    ?>
}