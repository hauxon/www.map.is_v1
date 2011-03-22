<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 clientSelect.js.php
 Dependencies: Needs to be run after all wfs is run
 
 Declares global variables to be run
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
?>      
// ClientSelect er keyrt eftir að kortið hefur initialized og allt wfs hefur verið initialized
function initClientSelect()  
{
    if( client_select_wfs_arr.length > 0 )
    {
        var ttoptions = {
                hover: true,
                onSelect: onClientSelectCallback,
                onUnselect: onClientUnselectCallback,
                clickFeature: onClientClickCallback	
        }        
        var the_select = new OpenLayers.Control.SelectFeature(client_select_wfs_arr, ttoptions);
        map.addControl(the_select);

        the_select.activate();	        
    }
}      


function onClientUnselectCallback(feature){
    //alert("onClientUnselectCallback");
    /*if( feature.layer.name == "Skámyndir WFS" ){
            tb = document.getElementById("ToolTip")
            tb.style.visibility="hidden";			
    }*/
    if( feature.layer.name == "Skámyndir WFS" )
    {
    }    
}				
function onClientClickCallback(feature){
    //alert("onClientClickCallback");
    /*if( feature.layer.name == "Skámyndir WFS" ){
    }*/
    if( feature.layer.name == "Skámyndir WFS" )
    {
	Offsetheight = 0;
	storedFeature = feature.clone();
	storedTooltipFeature = feature.clone();
	var ToolTipFlex = document.getElementById("ToolTipFlex");
	var ToolTipFlexContent = document.getElementById("ToolTipFlexContent");	
	
	sHtml  = "<table border='0' cellspacing='0' cellpadding='0'><tr id='top'><td id='starttop'></td><td id='middletop'></td><td id='endtop'></td></tr><tr id='middle'><td id='startmiddle'></td><td id='middlemiddle'>";
	
	if(feature.layer.name == "Skámyndir WFS"){
		Offsetheight = 185;
		if( feature.attributes.onlinepath != null || feature.attributes.onlinepath != ""  ){
			sHtml += '<a href="#" onclick="callFancybox();"><img height="180" width="240" alt="' + feature.attributes.timestamp + '" src="http://www.loftmyndir.is/teikningar/skamyndir/medium/skamyndir' + feature.attributes.onlinepath + '.jpg" /></a><br />';
		}
	}
	
	if(feature.layer.name == "Vefmyndavélar WFS"){
		if( feature.attributes.eigandi == "Vegagerðin"){
			Offsetheight = 215;
			sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
			sHtml += '<a data-refresh="0" href="' + feature.attributes.slod + '"><img height="180px" width="240px" alt="' + feature.attributes.voktun + '" src="' + feature.attributes.slod + '.jpg" /></a><br />';
			sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
			if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
				sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
			}
		}
		else if( feature.attributes.eigandi == "RÚV" ){
			Offsetheight = 240;
			sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
			sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';
			
			sHtml += '<object width="240px" height="210px" type="application/x-oleobject" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" id="VIDEO">';
			sHtml += '<param value="mms://213.167.158.211/katla/" name="URL">';
			sHtml += '<param value="True" name="SendPlayStateChangeEvents">';
			sHtml += '<param value="True" name="AutoStart">';
			sHtml += '<param value="full" name="uiMode">';
			sHtml += '<param value="1" name="PlayCount">';
			sHtml += '<embed width="240px" height="210px" autostart="true" name="MediaPlayer" src="mms://213.167.158.211/katla/" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" type="application/x-mplayer2">';
			sHtml += '</object>';
			
			sHtml += '</a><br />';
			sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
			if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''"){				
				sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
			}			
		}	
		else if( feature.attributes.eigandi == "Harpa Tónlistar og ráðstefnuhúsið í Reykjavik" ){
			Offsetheight = 215;
			sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
			sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';
			
			sHtml += '<img width="240px" height="180px" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';
			
			sHtml += '</a><br />';
			sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
			if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
			{				
				sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
			}			
		}		
		else{
			Offsetheight = 40;
			sHtml += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
			sHtml += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">Skoða vefmyndavél</a><br />';
			sHtml += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
			if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
			{				
				sHtml += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
			}			
		}
	}
	
		/* if( feature.attributes.eigandi == "Verkfræðistofan Vista" )
			{
				strHtmlContents += feature.attributes.eigandi + " - " + feature.attributes.stadur + "<br />"; 
				strHtmlContents += '<a data-refresh="0" target=\"_blank\" href="' + feature.attributes.slod + '">';
				strHtmlContents += '<iframe scrolling="no" height="180" width="240" noresize="" marginheight="0" marginwidth="0" src="http://194.144.19.40/popup.html" target="main" name="contents"></iframe>';
				//strHtmlContents += '<img width="184" height="138" alt="Camera Image" src="http://harpa-cam.eplica.is/axis-cgi/mjpg/video.cgi?resolution=4CIF&amp;camera=1&amp;dummy=1297951651860">';
				
				strHtmlContents += '</a><br />';
				strHtmlContents += "<span style=\"white-space: nowrap;\"><a target=\"_blank\" href=\"" + feature.attributes.slod + "\">" + feature.attributes.voktun + "</a></span><br />"; 
				if( feature.attributes.ath != null && feature.attributes.ath != "" && feature.attributes.ath != "''")
				{				
					strHtmlContents += "<span style='white-space: nowrap;'>" + feature.attributes.ath + "</span>";
				}			
			}	*/	
	
	sHtml += "</td><td id='endmiddle'></td></tr><tr id='bottom'><td id='startbottom'></td><td id='middlebottom'><div id='popout_l_b' ></div></td><td id='endbottom'></td></tr></table>";				
	
	ToolTipFlexContent.innerHTML = sHtml;
	
	var px = map.getPixelFromLonLat(feature.geometry.getBounds().getCenterLonLat())
	
	storedTooltipFeatureLonLat = feature.geometry.getBounds().getCenterLonLat();
	
	ToolTipOffsetX = 5;  
	ToolTipOffsetY = Offsetheight;
	
	ToolTipFlex.style.left = (px.x - 5) + "px";  
	//ToolTipFlex.style.top = (px.y- 25) + "px";   		
	ToolTipFlex.style.top = (px.y- Offsetheight) + "px";     		
	
	ToolTipFlex.style.visibility = "visible";	    
    }    
}
function onClientSelectCallback(feature){
    //alert("onClientSelectCallback");
    /*if( feature.layer.name == "Skámyndir WFS" ){
            var ToolTip = document.getElementById("ToolTip");
            ToolTip.innerHTML="<div id='ToolTipstart'></div><div id='ToolTipcontent'><div id='tipTxt'><a href='javascript:parent.changeParentUrl(\"http://www.loftmyndir.is/k/nordic_iframe.html\");'>þetta er linkur</a></div></div><div id='ToolTipend'></div>"; // +feature.attributes.nafn + 
            ToolTip.style.visibility="visible";
            var jimX = this.handlers.feature.feature.geometry.getCentroid().x;
            var jimY = this.handlers.feature.feature.geometry.getCentroid().y;		
            px = map.getViewPortPxFromLonLat(new OpenLayers.LonLat(jimX,jimY));		
            ToolTip.style.left= new String((px.x + 5)+"px");
            ToolTip.style.top= new String((px.y - 5)+"px");								
    }*/
    if( feature.layer.name == "Skámyndir WFS" ){
    }
    
}