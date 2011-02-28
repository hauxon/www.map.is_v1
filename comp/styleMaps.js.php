<?php
	// Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);

        // Load styleMaps into interface
	// Load wfs layers into map
        //$vectorlayerroot = $config->xpath('//vectorlayer');
	foreach ($config->xpath('//vectorlayer') as $vectorlayer)
	{
            if( $vectorlayer->styleMap->useStyleMap == true )
            {
                ?>
                var styleMap_<?=$vectorlayer->layerName?> = new OpenLayers.StyleMap(	
                    {
                    <?php
                    ?>
                        'default':{ 
                            'fillColor':'<?=$default = $vectorlayer->styleMap->default->fillColor?>',
                            'strokeColor': '<?=$vectorlayer->styleMap->default->strokeColor?>',
                            'strokeWidth': <?=$vectorlayer->styleMap->default->strokeWidth?>,
                            'strokeOpacity': <?=$vectorlayer->styleMap->default->strokeOpacity?>,
                            'fillOpacity': <?=$vectorlayer->styleMap->default->fillOpacity?>,
                            'pointRadius':<?=$vectorlayer->styleMap->default->pointRadius?>},
                     <?php
                     ?>
                        'select': {	
                            'fillColor':'<?=$vectorlayer->styleMap->select->fillColor?>',
                            'strokeColor':'<?=$vectorlayer->styleMap->select->strokeColor?>',
                            'strokeWidth': <?=$vectorlayer->styleMap->select->strokeWidth?>,
                            'fillOpacity':<?=$vectorlayer->styleMap->select->fillOpacity?>,
                            'strokeOpacity': <?=$vectorlayer->styleMap->select->strokeOpacity?>,
                            'pointRadius':<?=$vectorlayer->styleMap->select->pointRadius?>,
                            'cursor': '<?=$vectorlayer->styleMap->select->cursor?>'}
                    }
                );  
                <?php
            }
            else
            {
                echo $vectorlayer->styleMap->anotherStyleMapName;
            }
            
        }
?>