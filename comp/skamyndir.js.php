<?php
	// Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
        
	// Load wfs layers into map
	foreach ($config->xpath('//vectorlayer') as $vectorlayer)
	{              
            if( $vectorlayer->layerName == "skamyndir" )
            {
                include '../comp/' . $vectorlayer->styleMap->componentFileName;
?>
    var <?=$vectorlayer->layerName?>_scales = <?=$vectorlayer->layerScales?>;
    var <?=$vectorlayer->layerName?>_wfs = new OpenLayers.Layer.<?=$vectorlayer->layerType?>("<?=$vectorlayer->layerTitle?> WFS",
        "<?=$vectorlayer->url?>",
        { typename: '<?=$vectorlayer->layerNames?>', maxfeatures: <?=$vectorlayer->maxFeatures?>},
        { 'displayInLayerSwitcher':<?=$vectorlayer->displayInLayerSwitcher?>, 
          extractAttributes: <?=$vectorlayer->visibility?>, scales:<?=$vectorlayer->layerName?>_scales, styleMap:styleMap_<?=$vectorlayer->layerName?>});

    map.addLayers([<?=$vectorlayer->layerName?>_wfs]);
    client_select_wfs_arr.push(<?=$vectorlayer->layerName?>_wfs);

    <?=$vectorlayer->layerName?>_wfs.setVisibility(<?=$vectorlayer->visibility?>);
            
<?php
            }
	}                
?>