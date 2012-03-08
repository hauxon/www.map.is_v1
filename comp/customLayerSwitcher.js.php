<?php if(1==2){?>//<script type="text/javascript"><?php }?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


//Code to check if standard layerswitcher is needed

// Map.is style layerswitcher implementatione


function initCustomLayerSwitcher()
{
    var LSHTML = "";
    LSHTML += "<div id=LSHeaderDiv>";
    LSHTML += "<img id=LSImg class=LSImg src='img/header_img/layers_icon.png' onclick='javascript:pressLSButton();'  onMouseOver='javascript:LSButtonHover();' onMouseOut='javascript:LSButtonOFF();'>";
    LSHTML += "</div>";
    
    LSHTML += "<div id='LSListContainerDiv' onMouseOver='javascript:showLS();'><div id='LSHeader'></div><div id='LSListDiv'>";
            LSHTML += "<span class='LSTextEntry' onclick='skamyndirClick();'><div id='skamyndir_icon' class='skamyndir_icon_off'><a href='#'></a></div><div id='skamyndir_txt'>Skámyndir</div></span><br/>";
    LSHTML += "<span class='LSTextEntry' onclick='vefmyndavelarClick();'><div id='vefmyndavelar_icon' class='vefmyndavelar_icon_off'></div><div id='vefmyndavelar_txt'>Vefmyndavélar</div></span><br/>";
    LSHTML += "<span class='LSTextEntry' onclick='poiClick();'><div id='poi_icon' class='poi_icon_off'></div><div id='poi_txt'>Áhugaverðir staðir</div></span>";
    LSHTML += "</div></div>"; 
    
    $j('body').append(LSHTML);
    
     $j('#LSListContainerDiv').mouseleave(function() {
     hideLS();
     });
}

function pressLSButton()
{
    //alert('press LS');
}

function LSButtonHover()
{
    $j('#LSListContainerDiv').fadeIn("fast");
}

function LSButtonOFF()
{
    //$j('#LSListContainerDiv').fadeOut("fast");
}

function hideLS(evt)
{
    $j('#LSListContainerDiv').fadeOut("fast");
}

function showLS()
{
    $j('#LSListContainerDiv').show();
}