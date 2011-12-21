<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 toolTipFlex.js.php
 Dependencies: tooltipflex.css
 
 Declares no global variables to be run
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
?>
//<script type="text/javascript">
function initToolTipFlex()
{
        var sHtmlToolTipStart = '<div id="ToolTipFlexContent"><table border="0" cellspacing="0" cellpadding="0"><tr id="top"><td id="starttop"></td><td id="middletop">';
        var sHtmlToolTipMiddle = '</td><td id="endtop"></td></tr><tr id="middle"><td id="startmiddle"></td><td id="ToolTipFlexText"></td><td id="endmiddle"></td></tr><tr id="bottom"><td id="startbottom"></td><td id="middlebottom">';
        var sHtmlToolTipEnd = '</td><td id="endbottom"></td></tr></table></div><a id="fancy_close" onclick="closeFlex();moveFlex();"></a></div>';
        
        var sHtmlToolTip = '<div id="ToolTipFlex">' + sHtmlToolTipStart + sHtmlToolTipMiddle + '<div id="popout_l_b" ></div>' + sHtmlToolTipEnd;
        $j("body").append(sHtmlToolTip);
        
        sHtmlToolTip = '<div id="ToolTipFlexBottom">' + sHtmlToolTipStart + '<div id="popout_l_t" ></div>' + sHtmlToolTipMiddle + sHtmlToolTipEnd;
        $j("body").append(sHtmlToolTip);        
        
        sHtmlToolTip = '<div id="ToolTipFlexLeft">' + sHtmlToolTipStart + sHtmlToolTipMiddle + '<div id="popout_r_b" ></div>' + sHtmlToolTipEnd;
        $j("body").append(sHtmlToolTip);
        
        sHtmlToolTip = '<div id="ToolTipFlexBottomLeft">' + sHtmlToolTipStart + '<div id="popout_r_t" ></div>' + sHtmlToolTipMiddle + sHtmlToolTipEnd;
        $j("body").append(sHtmlToolTip);                
}

function positionTooltipFlex(tooltipFlexObject)
{
     var sHtml = tooltipFlexObject['toolTipContent'];
     var px = tooltipFlexObject['pixelFromLonLat'];
     var topOffsetHeight = tooltipFlexObject['topOffsetHeight'];
     var bottomOffsetHeight = tooltipFlexObject['bottomOffsetHeight'];
     var leftOffset = tooltipFlexObject['leftOffset'];
     var rightOffset = tooltipFlexObject['rightOffset'];
     var ix = tooltipFlexObject['ix'];
     var iy = tooltipFlexObject['iy'];
     var isCluster = ((tooltipFlexObject_Global['isCluster'] == 1)?true:false);
     
     $j("#ToolTipFlexText").html(tooltipFlexObject['toolTipContent']);
     
    if( isCluster ){ 
    }     
     
    // kominn út fyrir
    if( (iy - $j("#ToolTipFlex").height()) < 0 ) { //|| iy < 200) {

        if(ix + $j("#ToolTipFlex").width() + 30 > $j("#map").width()){
            var ToolTipFlex = document.getElementById("ToolTipFlexBottomLeft");
            Offsetheight = bottomOffsetHeight;
            $j("#ToolTipFlexBottomLeft #ToolTipFlexText").html(sHtml);
            $j("#popout_r_t").css("left", $j("#ToolTipFlex").width() - 60  + "px");
            ToolTipFlex.style.left = (px.x - (rightOffset + $j("#ToolTipFlex").width())) + "px";  
            ToolTipFlex.style.top = (px.y + Offsetheight) + "px";     	
            //ToolTipFlex.style.top = (px.y + ($j("#ToolTipFlex").height())) + "px";     	
        }else{
            var ToolTipFlex = document.getElementById("ToolTipFlexBottom");
            Offsetheight = bottomOffsetHeight;
            $j("#ToolTipFlexBottom #ToolTipFlexText").html(sHtml);
            
            ToolTipFlex.style.left = (px.x - leftOffset) + "px";  
            ToolTipFlex.style.top = (px.y + Offsetheight) + "px";     	
            //ToolTipFlex.style.top = (px.y + ($j("#ToolTipFlex").height() + 60 )) + "px";     	
        }
    }else{
        
        if(ix + $j("#ToolTipFlex").width() + 30 > $j("#map").width()){
            var ToolTipFlex = document.getElementById("ToolTipFlexLeft");
            Offsetheight = topOffsetHeight;
            $j("#ToolTipFlexLeft #ToolTipFlexText").html(sHtml); 
            $j("#popout_r_b").css("left", $j("#ToolTipFlex").width() - 60  + "px");
            ToolTipFlex.style.left = (px.x - (rightOffset + $j("#ToolTipFlex").width())) + "px";  
            //ToolTipFlex.style.top =  (px.y - Offsetheight) + "px";  
            ToolTipFlex.style.top =  (px.y - ($j("#ToolTipFlex").height() - 60)) + "px";  
        }else{
            var ToolTipFlex = document.getElementById("ToolTipFlex");
            Offsetheight = topOffsetHeight;
            $j("#ToolTipFlex #ToolTipFlexText").html(sHtml);
            ToolTipFlex.style.left = (px.x - leftOffset) + "px";         
            //ToolTipFlex.style.top =  (px.y - Offsetheight) + "px";  
            ToolTipFlex.style.top =  (px.y - ($j("#ToolTipFlex").height() - 60)) + "px";  
        }
    }    
    if( isCluster ){ 
    }
    ToolTipFlex.style.visibility = "visible";
}


function moveFlex(){
    $j("#ToolTipFlex").css("top","-500px");        
    $j("#ToolTipFlex").css("left","-500px");
    $j("#ToolTipFlexBottom").css("top","-500px");        
    $j("#ToolTipFlexBottom").css("left","-500px");
    $j("#ToolTipFlexLeft").css("top","-500px");        
    $j("#ToolTipFlexLeft").css("left","-500px");
    $j("#ToolTipFlexBottomLeft").css("top","-500px");        
    $j("#ToolTipFlexBottomLeft").css("left","-500px");    
}

function closeFlex(){
    tb=document.getElementById('ToolTipFlex');tb.style.visibility='hidden';
    tb=document.getElementById('ToolTipFlexBottom');tb.style.visibility='hidden';
    tb=document.getElementById('ToolTipFlexLeft');tb.style.visibility='hidden';
    tb=document.getElementById('ToolTipFlexBottomLeft');tb.style.visibility='hidden';
}

//</script>