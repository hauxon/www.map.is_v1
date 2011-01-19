// Byrjum á að laga jquery til svo að ekki verði árekstrar við OL
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
	h_offset = 84;//$j("#wrap").css("top").replace("px","");
	H_CONST = 0;//7; // for buttom margin
	W_CONST = 0;//22; // 2x10 for margins + 1+1 for no scroll
	$j("#map").css("width",(width-W_CONST)+"px").css("height",(height-h_offset-H_CONST )+"px");
	$j("#sliderPanel").css("height",(height-h_offset-H_CONST)+"px");
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
$j(document).ready(function() {


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
	  
    });
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