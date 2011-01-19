<?php
	// Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>
// Höfum map breytuna glóbal svo við getum referencað hana utan init fallsins
var map = null;

function initmap()
{

<?php	
	// Load OpenLayers map into interface
	foreach ($config->xpath('//map') as $map) 
	{
?>		
	map = new OpenLayers.Map('map', {
						maxExtent: new OpenLayers.Bounds(<?=$map->maxExtent?>),
						<?php if ($map->restrictedExtent != ""){ ?>restrictedExtent: new OpenLayers.Bounds(<?=$map->restrictedExtent?>), <?php } ?>
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
?>	

	map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.zoomToMaxExtent();
	
	onAppResize();
	map.updateSize();
	
	var lonlat = new OpenLayers.LonLat(420000,500000);
	map.setCenter(lonlat, 2,false,false);
}