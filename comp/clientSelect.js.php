<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 
    Document    : clientSelect.js.php
    Created on  : 1.3.2011, 16:38:20
    Author      : jonas
    Description : Used to create openlayers client select
    Dependencies: Needs to be run before all wfs components
 
    Declares no global variables to be run. Declared in kortasja.js.php
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
        // Opnum config XML
	$myFile = "../config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
?>      
// ClientSelect er keyrt eftir að kortið hefur initialized og allt wfs hefur verið initialized
function initClientSelect()  
{
    if( client_select_wfs_arr.length > 0 ){
        var ttoptions = {
                hover: true,
                onSelect: onClientSelectCallback,
                onUnselect: onClientUnselectCallback,
                clickFeature: onClientClickCallback	
        }        
        the_select = new OpenLayers.Control.SelectFeature(client_select_wfs_arr, ttoptions);
        map.addControl(the_select);
        the_select.hover = true;
        the_select.activate();	        
    }
}      


function onClientUnselectCallback(feature){
<?php    
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
?>
    if( feature.layer.name == "<?=$vectorlayer->layerTitle?> WFS" ){   
        <?=$vectorlayer->onUnSelect?>			
    }
<?php
    }      
?>
}				
function onClientClickCallback(feature){
<?php    
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
?>
    if( feature.layer.name == "<?=$vectorlayer->layerTitle?> WFS" ){   
        <?=$vectorlayer->onClick?>
    }
<?php
    }      
?>
}
function onClientSelectCallback(feature){
<?php    
    foreach ($config->xpath('//vectorlayer') as $vectorlayer)
    {              
?>
    if( feature.layer.name == "<?=$vectorlayer->layerTitle?> WFS" ){   
        <?=$vectorlayer->onSelect?>
    }
<?php
    }      
?>    
}