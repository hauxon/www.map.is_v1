<?php
    //put your code here
    // Opnum config XML
    $myFile = "../xml/overviewMapStatic.xml";
    $fh = fopen($myFile, 'r');
    $overviewMapStaticXml = new SimpleXMLElement(fread($fh, filesize($myFile)));
    fclose($fh);
    $counter = 0;
    foreach ($overviewMapStaticXml->xpath('//overviewMapStatic') as $overviewMapStatic) 
    {
        ?>
var isOverviewMapOpen = <?=$overviewMapStatic->isOverviewMapOpen?>;
function initOverviewMapStatic()
{
    var graphicoptions = {numZoomLevels: <?=$overviewMapStatic->graphicOptions->numZoomLevels?>, displayInLayerSwitcher:<?=$overviewMapStatic->graphicOptions->displayInLayerSwitcher?>};
    var static_overview_graphic_image = new OpenLayers.Layer.Image(
                    '<?=$overviewMapStatic->imageLayer->name?>',
                    '<?=$overviewMapStatic->imageLayer->overviewImage?>',
                    new OpenLayers.Bounds(<?=$overviewMapStatic->imageLayer->bounds?>),
                    new OpenLayers.Size(<?=$overviewMapStatic->imageLayer->size?>),
                    graphicoptions
            );
    map.addLayers([static_overview_graphic_image]);

    var ovgraphic = static_overview_graphic_image.clone();		
	
    olControlOverviewMap = new OpenLayers.Control.OverviewMap({ 
			maximized: isOverviewMapOpen,  
			mapOptions:{
				theme: null,//'css/overviewstyle.css',
				maxExtent: new OpenLayers.Bounds(<?=$overviewMapStatic->control->maxExtent?>),
				restrictedExtent: new OpenLayers.Bounds(<?=$overviewMapStatic->control->restrictedExtent?>),
				units:'m',
				maxResolution: <?=$overviewMapStatic->control->maxResolution?>, 
				numZoomLevels: <?=$overviewMapStatic->control->numZoomLevels?>,
				projection:"EPSG:3057"
			},
			layers:[ovgraphic]
			/*,  // þarf ekki að nota
			minRatio:64,
			maxRatio:128
			*/
        });

    map.addControl(olControlOverviewMap);

    // verður að activata til að keyra upp custom config
    olControlOverviewMap.activate();	
    // Hafa default opið
    //$j(".olControlOverviewMapElement").show();
    //$j(".olControlAttribution").css( "right", "210px"); //.css( "bottom", "3px");
    
    

    if( isOverviewMapOpen )
    {
            $j(".olControlOverviewMapElement").show();
            $j(".olControlOverviewMapMaximizeButton").hide();
            $j(".olControlOverviewMapMinimizeButton").show();		
            $j(".olControlAttribution").css( "right", "210px"); //.css( "bottom", "3px");
    }
    
    
    $j(document).ready(function() {

        
        
        $j(".olControlOverviewMapMinimizeButton").mouseup(function() {
            $j(".olControlAttribution").css( "right", "20px"); //.css( "bottom", "3px");
        }); 
        
        $j(".olControlOverviewMapMaximizeButton").mouseup(function() {
            $j(".olControlAttribution").css( "right", "210px"); //.css( "bottom", "3px");
        }); 
        
    });    
    
    
}
<?php
        $counter++;
    }
?>

