<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="Map.is - driving directions and routes" name="title" />
    <meta name="description" content="Find local businesses, view maps and get driving directions with Map.is" />
    <meta content="maps, iceland map, directions, street map, roadmap, aerial map, locations, local businesses, yellow pages, kort, íslandskort, vegvísun, götukort, vegakort, loftmynd, staðir, Landkarte, Karte, Islandkarte, Strassenkarte, Strassenatlas, Touristenkarte, Sehenswürdigkeiten, Luftbild, Luftaufnahmen, Strassennetz, Wegenetz, map.is." name="keywords" />
        
    <title>map.is - Á réttri leið</title>
    <LINK REL="SHORTCUT ICON" HREF="http://193.4.153.85:8088/www.map.is/img/icon/favicon.ico"  />
    
    <!-- Firebug Lite fyrir IE -->
    
    <script src="lib/openlayers211.js" type="text/javascript"></script>
    
    <link rel="stylesheet" href="css/ol_default_style.css" type="text/css"/>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <link rel="stylesheet" href="css/header.css" type="text/css"/>
    <link rel="stylesheet" href="css/fullscreen.css" type="text/css"/>
    <link rel="stylesheet" href="css/lm_interface.css" type="text/css"/>
    <link rel="stylesheet" href="css/mapis_ui.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-1.3.1.css" media="screen" />    
    <link rel="stylesheet" href="css/tipsy/tipsy.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/master.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui/jquery.ui.autocomplete.custom.css" type="text/css"  />
    <link rel="stylesheet" href="css/scalebar-LM.css" type="text/css" />
    
    <style type="text/css">
        #LM_zoom-slider_minus { top: 0px; }
        #LM_zoom-slider_plus { top: 0px; }
        #LM_zoom-slider_img { top: 95px; }
    </style>
    <!--[if IE]>
    <style type="text/css">
        #LM_zoom-slider_minus { top: 0px; }
        #LM_zoom-slider_plus { top: 0px; }
        #LM_zoom-slider_img { top: 95px; }
    </style>
    <![endif]--> 
    
    
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui/jquery-ui-1.8.core-and-interactions.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="lib/jquery-ui/jquery-ui-1.8.autocomplete.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="lib/tag-it.js" type="text/javascript" charset="utf-8"></script>

    <!-- Þessi tvö næstu script tilheyra addthis -->
    <!--<script type="text/javascript">

        //var permalinkurinn, var_static_url;

        var addthis_config = {
                                "data_track_clickback":true,
                                ui_language: "is"                                    
                             };   
        var addthis_share = { 
                                email_template: "fyrsta_prufa" ,
                                email_vars: 
                                { permalinkurinn: "http://www.loftmyndir.is?debug=kljh",
                                  static_url: "http://www.loftmyndir.is" }                                  

        }
    </script>-->
    <!--<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4decaf506d8e21d0"></script>-->

    <script type="text/javascript" src="lib/tipsy/jquery.pop.js"></script>            
    <script type="text/javascript" src="lib/tipsy/jquery.tipsy.js"></script>            
    <script type="text/javascript" src="lib/fancybox/jquery.fancybox-1.3.1.js"></script>            
    <script  type='text/javascript' src='lib/easing.js'></script>            

    <link rel="stylesheet" href="css/prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
    <script src="lib/prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>            

    <script type="text/javascript">
    var $j = jQuery.noConflict();
    </script>        

    <script type='text/javascript' src='lib/smoothPopup/smoothpopup.js'></script>
    <script type='text/javascript' src='lib/common.js'></script>
    <script type='text/javascript' src='lib/LMUtil.js'></script>
    <script type='text/javascript' src='lib/kortasja.js.php'></script>
    <script type='text/javascript' src='lib/ScaleBar.js'></script>
    <!--<script type='text/javascript' src='lib/newLayerSwitcher.js'></script>-->
    
    

    <!--<script type="text/javascript">	
    window.onerror = function() {
        //if( true ) return true;
        var message = "";
        for(i=0;i<arguments.length;i++)
            message+=" [ "+arguments[i]+" ] ";
        $j.ajax({
            type:"GET",
            //type:"POST",
            cache:false,
            //dataType: 'json',
            dataType: 'text',
            url:"error.php",
            data:"error="+message + "&site=map_is&page=not_set&callback=?",
            success: function(data){
                if( window.console) console.warn("Returned " + data + ". Report sent about the javascript error");
            }
        });		
      return true; // hide error from browser
    }
    //throw new Error("Error");	        
    </script>   -->       
    
    
    <?php
        require("lib/querystring.js.php");
    ?>
    <?php
        require("lib/redirect.php");
    ?>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-24476964-1']);
	  _gaq.push(['_setDomainName', '.map.is']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>	
    
    <script type='text/javascript' src='lib/malmoprint.js'></script>
    
    <link rel="stylesheet" href="css/combineCSS.php" type="text/css" />
    <link rel="stylesheet" href="css/malmoprint.css" type="text/css" media="print" />
</head>

    <body onload="initmap();initComponents();" onresize="onAppResize();">
<?php
    require("header_include.php");
?>
<div><img id="header_shadow" src="img/routing/header_shadow.png" alt="" /></div>
	
<div id="sliderPanel">
     <a id="sliderPanelBtn" class="open" href="http://www.map.is"></a>
      <div id="sliderAccordion"></div>
     <div id="control"></div>
</div>
<div id="LM_panzoombar">
    <div id="leIsland" class="leIsland" onclick="/*switchCommands('Overview')*/"></div>
</div> 
  	<div id="map" class="hrannarMap"></div>
        <div id="ToolTip"></div>
        <div id="images"></div>
</body>
</html>
