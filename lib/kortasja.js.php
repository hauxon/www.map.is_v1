
<?php
	// Opnum config XML
	$myFile = "../xml/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>
// Höfum map breytuna global svo við getum referencað hana utan init fallsins
var map = null;
// Aðrar global variables
var markers = null;
// Öllum wfs layerum er bætt við þetta array sem síðan er bætt við client select gaurinn
var client_select_wfs_arr = [];
var client_select = null;
var viewLink;
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
                                            panDuration: 100,
                                            controls: [
                                            new OpenLayers.Control.Navigation(
                                                {dragPanOptions: {enableKinetic: true}}
                                            )],
                                            maxResolution: '180/256',
                                            scales:[<?=$map->scales?>],
                                            projection:"<?=$map->projection?>", 
                                            theme: null
                                            });	
        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
	
<?php 
	} 

	// Load base layers into map
	foreach ($config->xpath('//baseLayer') as $baseLayer) 
	{
?>
                                           
	var <?=$baseLayer->layerName?> = new OpenLayers.Layer.<?=$baseLayer->tileCacheType?>('<?=$baseLayer->layerTitle?>',
                        <?=$baseLayer->url?>,
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
	
	map.addLayers([<?=$baseLayer->layerName?>]);    
        map.addControl(new OpenLayers.Control.Attribution());
	
<?php 
	}

	

	// Load wms layers into map
	foreach ($config->xpath('//layer') as $layer)
	{
?>
    var <?=$layer->layerName?>_scales = [<?=$layer->layerScales?>];
    
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
         scales: <?=$layer->layerName?>_scales,
         unsupportedBrowsers: []});

	map.addLayers([<?=$layer->layerName?>]);	        
<?php
	}
        
?>
        // CONTROLS SEM EKKI ER HÆGT AÐ TAKA ÚT
        
	var scalebar = new OpenLayers.Control.ScaleBar({
		maxWidth: 300, minWidth: 200,
		divisions: 2,   // default is 2
		subdivisions: 5 // default is 2

	});
	map.addControl(scalebar);
        
        //$j(".olControlLayerSwitcher").hide();$j(".olControlPanZoom").hide();
        
        map.addControl(new OpenLayers.Control.PanZoomBar({'div':OpenLayers.Util.getElement('LM_panzoombar')}));
        doTheFunkyBar();
        
        /*layerSwitcher = new OpenLayers.Control.LayerSwitcher();
        map.addControl(layerSwitcher);
        layerSwitcher.minimizeControl();*/
        
        /* Hrannar 30.05.11
        layerSwitcher = new OpenLayers.Control.newLayerSwitcher();
        map.addControl(layerSwitcher);
        layerSwitcher.minimizeControl(); */
        
        //map.zoomToMaxExtent();
        
        if( !isPermalink ){  //ef ekki er kallað með permalinkgenereruðu urli annars sér permalink control um þetta
            //upphafsstaðsetningu og zoom
            var lonlat = new OpenLayers.LonLat(<?=($map->startLon != "")?$map->startLon:"420000"?> ,<?=($map->startLat != "")?$map->startLat:"500000"?>);
            map.setCenter(lonlat, <?=($map->startZoom != "")?$map->startZoom:"2"?>,false,false);            
        }
        
        // CONTROLS SEM HÆGT ER AÐ TAKA ÚT        
        
        markers = new OpenLayers.Layer.Markers("Merki",{'displayInLayerSwitcher':false});
        map.addLayer(markers); 
        <?php
        
            foreach ($config->xpath('//markerlayer') as $markerlayer)
            {
?>		
            //<?=$markerlayer->layerName?> = new OpenLayers.Layer.<?=$markerlayer->layerType?>("<?=$markerlayer->layerTitle?>",{'displayInLayerSwitcher':true });
            //map.addLayer(<?=$markerlayer->layerName?>);
<?php 
            }
        
            
         
        ?>	
       
        initControls();
            
        onAppResize();
	//map.updateSize();                
 }
function zoomToStartPosition(){
    var lonlat = new OpenLayers.LonLat(<?=($map->startLon != "")?$map->startLon:"420000"?> ,<?=($map->startLat != "")?$map->startLat:"500000"?>);
    map.setCenter(lonlat, <?=($map->startZoom != "")?$map->startZoom:"2"?>,false,false); 
}
 
function initAjax()
{

        try
        {
                // Firefox, Opera 8.0+, Safari
                xmlHttp=new XMLHttpRequest();
        }
        catch(e)
        {
                // Internet Explorer
                try
                {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch(e2)
                {
                        try
                        {
                                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch(e3)
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
        xmlHttp.onreadystatechange = callback;
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

/*function moveEndHandler(evt){
}*/		

function setBM( targetZoomLevel ){		
   // map.layers[0].params.LAYERS = theBM[targetZoomLevel];
}		


<?php
foreach ($config->xpath('//component') as $comp)
        {
            
            include '../comp/' . $comp->componentFile;
	}
        
foreach ($config->xpath('//control') as $control)
            {
                
                include '../comp/' . $control->controlFile;
            }
?>

// All component scripts have been added and now we need to INIT them!!!
function initComponents()
{
    <?php
    foreach ($config->xpath('//component') as $comp)
            {
                echo $comp->componentInitScript;
            }
    ?>
}

// All controls scripts have been added and now we need to INIT them!!!
function initControls()
{
<?php
    foreach ($config->xpath('//control') as $control)
            {
                echo $control->controlInitScript . " \n \n";
            }
?>
}
