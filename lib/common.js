// Byrjum � a� laga jquery til svo a� ekki ver�i �rekstrar vi� OL
var $j = jQuery.noConflict();



// ---- Automatic map resizeing ------------------------------------ //

function getWindowHeight() {
    if (window.self && self.innerHeight) {
        return self.innerHeight;
    }
    if (document.documentElement && document.documentElement.clientHeight) {
        return document.documentElement.clientHeight;
    }
    return 0;
}
function getViewPortSize()
{
	var viewportwidth;
	var viewportheight;

	// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight

	if (typeof window.innerWidth != 'undefined')
	{
	  viewportwidth = window.innerWidth,
	  viewportheight = window.innerHeight
	}

	// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)

	else if (typeof document.documentElement != 'undefined'
	 && typeof document.documentElement.clientWidth !=
	 'undefined' && document.documentElement.clientWidth != 0)
	{
	   viewportwidth = document.documentElement.clientWidth,
	   viewportheight = document.documentElement.clientHeight
	}

	// older versions of IE

	else
	{
	   viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
	   viewportheight = document.getElementsByTagName('body')[0].clientHeight
	}
        return {width:viewportwidth,height:viewportheight};
}

//onAppResize - Event handler for document.onresize that dynamically sets the height/width of the #map div (in fullscreen mode only)
function onAppResize()
{	
    //Calculate map size according to browser size
    viewPort = getViewPortSize();
    var height = viewPort.height;
    var width= viewPort.width;
    h_offset = 85;//$j("#wrap").css("top").replace("px","");
    //H_CONST = 42;//7; // for buttom margin
    //W_CONST = 22;//22; // 2x10 for margins + 1+1 for no scroll
    //H_CONST = 10;//7; // for buttom margin
    //W_CONST = 10;//22; // 2x10 for margins + 1+1 for no scroll
    H_CONST = 0; // for buttom margin
    W_CONST = 0; // 2x10 for margins + 1+1 for no scroll
    //$j("#map").css("width",(width-W_CONST)+"px").css("height",(height-h_offset-H_CONST )+"px");
    //$j("#sliderPanel").css("height",(height-h_offset-H_CONST-14)+"px");
    $j("#map").css("width",(width-W_CONST)+"px").css("height",(height-h_offset-H_CONST )+"px");
    $j("#sliderPanel").css("height",(height-h_offset)+"px");
    $j("#sliderAccordion").accordion("resize"); 
    //height = viewPort.height;
    //width= viewPort.width;
    //$j("#map").css("width",(width+1)+"px").css("height",(height + 1)+"px");
}

function calculateOffsetTop(element, opt_top) {
    var top = opt_top || null;
    var offset = 0;
    for (var elem = element; elem && elem != opt_top; elem = elem.offsetParent) {
        offset += elem.offsetTop;
    }
    return offset;
}


// Slider code begins -------------------------------
/*$j(document).ready(function() {


      $j("a#sliderPanelBtn").click(function(e) {
      
        e.preventDefault();
        
        var slidepx=$j("div#sliderPanel").width() + 10;
    	
    	if ( !$j("div#sliderPanel").is(':animated') ) { 
        
			//if (parseInt($j("div#sliderPanel").css('marginLeft'), 10) < slidepx) {
			if ($j('#sliderPanelBtn').hasClass('close'))
			{		
     			$j(this).removeClass('close').html('');

      			margin = "+=" + slidepx;

    		} else {
				
				$j(this).addClass('close').html('');

      			margin = "-=" + slidepx;

    		}
		
        	$j("div#sliderPanel").animate({ 
        		marginLeft: margin
      		}, {
                    duration: 'slow',
                    easing: 'easeOutQuint'
                }); 	
    	} 

      }); 
	  
    });*/
// Slider code ends ---------------------------------

// -----
function getTileUrl (bounds)
  {
                if (bounds.left < 143000 ||
                        bounds.right >  866000 ||
                        bounds.top > 735000 ||
                        bounds.bottom < 255000 
						// || map.getZoom() > 8
					)
                {
                        return 'blanktile.png';
                }
  
        var res = this.map.getResolution();
        var x = Math.round ((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
        var y = Math.round ((bounds.bottom - this.maxExtent.bottom) / (res * this.tileSize.h));
        var z = this.map.getZoom();
        
        return "tms/" + z + "/" + x + "/" + y + "." + this.type; 
}

function getElementsByClassName(class_name)
{
      var all_obj,ret_obj=new Array(),j=0,teststr;

      if(document.all)
            all_obj=document.all;
      else if(document.getElementsByTagName && !document.all)
	  all_obj=document.getElementsByTagName("*");

      for(i=0;i<all_obj.length;i++)
      {
	  if(all_obj[i].className.indexOf(class_name)!=-1)
	  {
		teststr=","+all_obj[i].className.split(" ").join(",")+",";
		if(teststr.indexOf(","+class_name+",")!=-1)
		{
		  ret_obj[j]=all_obj[i];
		  j++;
		}
	  }
      }
      return ret_obj;
}

function isNumeric(sText)

{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;


   for (i = 0; i < sText.length && IsNumber == true; i++)
      {
      Char = sText.charAt(i);
      if (ValidChars.indexOf(Char) == -1)
         {
         IsNumber = false;
         }
      }
   return IsNumber;

}


function getLayerByName(layerName)
{
    layersLength = map.layers.length
    for (i=0; i < layersLength; i++)
    {
        if(map.layers[i].name == layerName)
        return map.layers[i];
    }
} 



/******************************
* doTheFunkyBar
******************************/
function doTheFunkyBar()
{
    // Change images in panZoom stuff
    var zoombar = document.getElementById("LM_panzoombar");
    var control_id="";
    // Browser incompatability HACK
    if(zoombar.children != undefined)
    control_id = zoombar.children[5].id.split("_")[1];
    else if (zoombar.childNodes != undefined)
    control_id = zoombar.childNodes[5].id.split("_")[1];
    //var control_id=document.getElementById("LM_panzoombar").children[5].id.split("_")[1]
    var pzbControl = map.getControlsBy("CLASS_NAME","OpenLayers.Control.PanZoomBar")[0];
    //panup
    pzbControl.buttons[0].innerHTML = '<img style="position: relative; width: 18px; height: 19px;" src="img/controls/LM_panzoompanup3.png">';
    //panleft
    pzbControl.buttons[1].innerHTML = '<img style="position: relative; width: 19px; height: 18px;" src="img/controls/LM_panzoompanleft3.png">';
    //panright
    pzbControl.buttons[2].innerHTML = '<img style="position: relative; width: 19px; height: 18px;" src="img/controls/LM_panzoompanright3.png">';
    //pandown
    pzbControl.buttons[3].innerHTML = '<img style="position: relative; width: 18px; height: 19px;" src="img/controls/LM_panzoompandown3.png">';
    //zoomin
    pzbControl.buttons[4].innerHTML = '<img id="LM_zoom-slider_plus"  style="position: relative; width: 18px; height: 13px;" src="img/controls/LM_zoom-plus3.png">'; // position: relative;
    //zoomout
    pzbControl.buttons[5].innerHTML = '<img id="LM_zoom-slider_minus" style="position: relative; width: 18px; height: 13px;" src="img/controls/LM_zoom-minus3.png">'; // position: relative;
    //sliderBtn
    pzbControl.slider.innerHTML = '<img id="LM_zoom-slider_img" src="img/controls/LM_zoom-slider3.png" style="padding:0px;margin:0px;position: relative;width: 18px; height: 11px;left: 31px;">';
    //SliderBg
    pzbControl.buttons[4].nextSibling.style.backgroundImage = "url(img/controls/LM_zoombar_thin.png)";
    pzbControl.buttons[4].nextSibling.style.width="10px";
    pzbControl.buttons[4].nextSibling.style.left="47px";
    pzbControl.buttons[4].nextSibling.style.top="177px";
    $j("#LM_panzoombar").removeAttr('style');
    //$j("#LM_panzoombar").addClass('LM_PanZoomBarContainer');
    pzbControl.buttons[0].className="OpenLayers_Control_PanZoom_panup";
    arr = pzbControl.buttons[0].id.split(".")
    var panupObj = arr[0]+"\\\."+arr[1]+"\\\."+arr[2];
    $j("#"+panupObj).attr('style', null).addClass('OpenLayers_Control_PanZoom_panup');
    $j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_pandown").attr('style', null).addClass('OpenLayers_Control_PanZoom_pandown');
    $j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_panleft").attr('style', null).addClass('OpenLayers_Control_PanZoom_panleft');
    $j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_panright").attr('style', null).addClass('OpenLayers_Control_PanZoom_panright');
    $j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_zoomin").attr('style', null).addClass('OpenLayers_Control_PanZoom_zoomin');
    //$j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_zoomout").attr('style', null).addClass('OpenLayers_Control_PanZoom_zoomout_'+map.scales.length);
    $j("#OpenLayers\\.Control\\.PanZoomBar_"+control_id+"_zoomout").attr('style', null).addClass('OpenLayers_Control_PanZoom_zoomout_'+map.getNumZoomLevels());
    $j("#LM_zoom-slider_minus").attr('style', null).addClass('LM_zoom-slider_minus_'+map.getNumZoomLevels());
    $j("#OpenLayers_Control_PanZoomBar_ZoombarOpenLayers").attr('style', "").addClass('OpenLayers_Control_PanZoomBar_ZoombarOpenLayers');
    $j("#leIsland").remove();
    $j("#LM_panzoombar").append('<div id=leIsland class=leIsland></div>');
    $j("#leIsland").click(function (){zoomToStartPosition()});
}

function kraekjaClick()
{
    // use tinyUrl to generate shorter link string
    //var linkur = sendSyncAJAXRequest('proxies/tinyUrl_proxy.asp?longUrl=' + escape( ( (theBaseMap[map.zoom] == "lightsaber")?viewLink.div.childNodes[0]:viewLink.div.childNodes[0].toString().replace( "layers=B0","layers=0B" ).replace( "layers=0B0","layers=00B" ) ) ) );
    var linkur = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    strHTML = "<div id=sendMailDescriptDiv><b>Hér er hægt að búa til krækju á kortið til að senda td. í tölvupósti eða setja inn á heimasíðu/spjallvef.<br></b><br><br>";
    strHTML += "<div id=sendMailToDiv>Hægrismelltu á textann og veldu 'Copy'. Settu svo krækjuna inn í td. póstforrit með 'Paste' skipuninni.</b><br><input onClick='javascript:document.getElementById(\"inputLinkEmail\").focus();document.getElementById(\"inputLinkEmail\").select();' type=text id=inputLinkEmail value=" + linkur + " READONLY><br>";
    strHTML += "</div><div id=sendMailToDiv><br><br>Hægrismelltu á textann og veldu 'Copy'.  Settu svo krækjuna inn í vefsíðu með 'Paste' skipuninni. <i>(iframe)</i><br>";
    strHTML += "<input type=text id=inputLinkHTML READONLY onClick='javascript:document.getElementById(\"inputLinkHTML\").focus();document.getElementById(\"inputLinkHTML\").select();' value='<iframe width=500 height=400 frameborder=0 scrolling=no marginheight=0 marginwidth=0 src=" + linkur + "></iframe><br /><small><a href=" + linkur + " style=color:#0000FF;text-align:left><font face=Verdana,Arial,Hevetica size=-2> &nbsp; Skoða stærra kort</font></a></small>'></div>";
    //strHTML += "<div id=sendMailButtonsDiv><input type=button value='Loka' onclick='disablePopup();'></div>";
    openDialog("Afrita krækju á kort",strHTML, true, false, false);
    // openDialog("prump2","kljæl ækjkæ lækjklæ læjk æljkl jæljklæ", true);
    //$j("#permalinkur").html(strHTML);
    //$j("#sResultHeader").html("Afrita krækju á kort");
    //$j("#contactArea").html(strHTML);
    //loadPopup();
    //centerPopup();
} 


function getPermaText(){
    var final_link = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    var sHtml = "<div id='sendMailDescriptDiv'><b>Hér er hægt að búa til krækju á kortið til að senda td. í tölvupósti eða setja inn á heimasíðu/spjallvef.<br></b><br><br>";
    sHtml += "<div id='sendMailToDiv'>Hægrismelltu á textann og veldu 'Copy'. Settu svo krækjuna inn í td. póstforrit með 'Paste' skipuninni.</b><br><input onClick='javascript:document.getElementById(\"inputLinkEmail\").focus();document.getElementById(\"inputLinkEmail\").select();' type=text id=inputLinkEmail value=" + final_link + " READONLY><br>";
    sHtml += "</div><!--div id=sendMailToDiv><br><br>Hægrismelltu á textann og veldu 'Copy'. Settu svo krækjuna inn í vefsíðu með 'Paste' skipuninni. <i>(iframe)</i><br>";
    sHtml += "<input type='text' id='inputLinkHTML' READONLY onClick='javascript:document.getElementById(\"inputLinkHTML\").focus();document.getElementById(\"inputLinkHTML\").select();' value='<iframe width=500 height=400 frameborder=0 scrolling=no marginheight=0 marginwidth=0 src=" + final_link + "></iframe><br /><small><a href=" + final_link + " style=color:#0000FF;text-align:left><font face=Verdana,Arial,Hevetica size=-2> &nbsp; Skoða stærra kort</font></a></small>'></div><br / --><br />";
    return sHtml;
}


function getEmailText( message_text ){
    var strHTML = "<div class='sendMailText' id='sendMail' style='background:#FFFFFF'>";
    strHTML += "<div class='sendMailToDivTitle' id=''>Hér getur þú sent slóð á kortið á tölvupóstfang</div>";
    strHTML += "<div class='sendMailToDiv' id=''><div><b>Til:</b></div><div><input type='text' class='sendMailTo'  id='sendMailToSendMail'/></div>Aðskiljið netföng með, eða ; &nbsp;&nbsp;&nbsp;<i> (t.d. abc@gmail.com; def@gmail.com)</i></div>";
    strHTML += "<div class='sendMailFromDiv'><div><b>Frá:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Ath. þennan reit verður að fylla út</i></div><div><input type='text' class='sendMailFrom' id='sendMailFromSendMail'/></div><input type='checkbox' id='sendMailCopy2MeSendMail' /> Senda afrit á netfangið mitt</div>";
    strHTML += "<div class='sendMailMessageDiv'><div><b>Skilaboð:</b></div><div><textarea class='sendMailMessage' id='sendMailMessageSendMail'>" + message_text + "</textarea><div></div>";
    strHTML += "<div class='sendMailButtonsDiv'><input type='button' value='Senda' onclick='sendMail();closeModalWindow();' /> <input type='button' value='Hætta við' onclick='closeModalWindow();' /></div>";    
    strHTML += "</div>";
    return strHTML;
}

function loadjscssfile(filename, filetype)
{
	if (filetype=="js"){ //if filename is a external JavaScript file
		var fileref=document.createElement('script');
		fileref.setAttribute("type","text/javascript");
		fileref.setAttribute("src", filename);
	}
	else if (filetype=="css"){ //if filename is an external CSS file
		var fileref=document.createElement("link");
		fileref.setAttribute("rel", "stylesheet");
		fileref.setAttribute("type", "text/css");
		fileref.setAttribute("href", filename);
                log(" css file: " + filename);
	}
	if (typeof fileref!="undefined")
	document.getElementsByTagName("head")[0].appendChild(fileref);
}

function log(message)
{
	try
		{
			console.log(message)
		}
	catch (e)
		{

		}
}
