//<script type="text/javascript">
<?php
/*
 * Hvaða layerar birtast er skilgreint í init fallinu í config.xml
 * initBaseLayersChooser() les frá xml/baseLayersChooser.xml
 * velur hvaða Kort og Myndkort layerar eru til staðar í kortasjá
 */
?>
var shownBaseLayersArr = [];
<?php
    // Opnum config XML
    $myFile = "../xml/baseLayersChooser.xml";
    $fh = fopen($myFile, 'r');
    $baseLayersChooser = new SimpleXMLElement(fread($fh, filesize($myFile)));
    fclose($fh);
    $counter = 0;
    foreach ($baseLayersChooser->xpath('//shownBaseLayer') as $shownBaseLayer) 
    {
        ?>
shownBaseLayersArr.push("<?=$shownBaseLayer->name?>");
<?php
        $counter++;
    }
?>

function switchBaseLayers( index )
{
    var oldBaseLayerIndex = findActiveBaseLayer();
    
    // Tökum út selected classann og setjum inn venjulegan
    $j('#mapselbtn' + i).toggleClass('map_sel_selected', false);
    $j('#mapselbtn' + i).toggleClass('map_sel_notselected', true);

    map.setBaseLayer(map.layers[index]);
    updateWhenLSIsUpdated();   // Uppfærir panZoomBarinn 
    
    // Setjum inn selected classann á nýja kortið og setjum inn venjulegan
    $j('#mapselbtn' + index).toggleClass('map_sel_notselected', false);
    $j('#mapselbtn' + index).toggleClass('map_sel_selected', true);
    
    // change background color of map depending on map type
    // needs better implementation, kind of hard coded this way
    if(map.layers[index].layername == "myndkort")
    {
        $j("#map").css("background-color", "#233e59");
        getLayerByName("VegirMyndkort").setVisibility(true);
        getLayerByName("VegirMyndkort").setOpacity(0.5);
        getLayerByName("Örnefni_myndkort").setVisibility(true);    
    }
    else
    {
        $j("#map").css("background-color", "#95abc0");
        getLayerByName("VegirMyndkort").setVisibility(false);
        getLayerByName("Örnefni_myndkort").setVisibility(false);
    }
}

function findActiveBaseLayer()
{
    for( i = 0; i < map.layers.length; i++ )
    {   
        if( map.layers[i].isBaseLayer == true && map.layers[i].visibility == true )
        {
            return i;
        }
    }
}

function initBaseLayersChooser(shownbaselayers){
        
    var headerBtnHTML = "";
    // 86 pixels for each button. button is 74 8 pixels space between
    var btnLeftPosition = 0; 

    //var sHtml = "<div id='baselayers_chooser'>";
    for( u = 0; u < shownBaseLayersArr.length; u++ ){
        for( i = 0; i < map.layers.length; i++ ){
            if(map.layers[i].isBaseLayer){
                //if( shownbaselayers.indexOf(map.layers[i].name) > -1 )
                if( shownBaseLayersArr[u] == map.layers[i].name ){
                    // Fyrsti baselayerinn er alltaf valinn í upphafi
                    if(btnLeftPosition == 0){
                        headerBtnHTML += "<div  onclick='javascript:switchBaseLayers(" + i + ");return false;' id='mapselbtn" + i + "' class='map_sel_selected' style='left:" + btnLeftPosition + "px;'><a href='#' onclick='javascript:switchBaseLayers(" + i + ");return false;'>" + map.layers[i].name + "</a></div>";
                    }
                    else{
                        headerBtnHTML += "<div  onclick='javascript:switchBaseLayers(" + i + ");return false;' id='mapselbtn" + i + "' class='map_sel_notselected' style='left:" + btnLeftPosition + "px;'><a href='#' onclick='javascript:switchBaseLayers(" + i + ");return false;'>" + map.layers[i].name + "</a></div>";
                    }
                    btnLeftPosition += 86;
                    // original frá linkurinn frá Jonasi
                    //sHtml += "<span><a href='#' onclick='javascript:switchBaseLayers(" + i + ");return false;'>" + map.layers[i].name + "</a></span>";
                }
            }
        }
    }
    //sHtml += '</div>';    
    //$j("body").append(sHtml);
    $j('#map_type').html(headerBtnHTML);
}
//</script>
