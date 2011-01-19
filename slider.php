<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>www.map.is</title>
	<script src="http://openlayers.org/api/OpenLayers.js"></script>
	<script src="lib/jquery.1.4.4.min.js"></script>
	<script src="lib/jquery-ui-personalized-1.5.2.min.js"></script>
	<script type='text/javascript' src='lib/easing.js'></script>
	<script type="text/javascript" src="lib/common.js"></script>
	<script type="text/javascript" src="lib/kortasja.js.php"></script>
	<script type="text/javascript">
	// Slider code begins -------------------------------
	$j(document).ready(function() {


		  $j("a#controlbtn").click(function(e) {
		  
			e.preventDefault();
			
			var slidepx=$j("div#linkblock").width() + 10;
			
			if ( !$j("div#maincontent").is(':animated') ) { 
			
				if (parseInt($j("div#maincontent").css('marginLeft'), 10) < slidepx) {
				
					$j(this).removeClass('close').html('');

					margin = "+=" + slidepx;
					margin2 = "-=" + slidepx;

				} else {
					
					$j(this).addClass('close').html('');

					margin = "-=" + slidepx;
					margin2 = "+=" + slidepx;

				}
			
				$j("div#maincontent").animate({ 
					marginLeft: margin
				}, {
						duration: 'slow',
						easing: 'easeOutQuint'
					});
				$j("div#map").animate({ 
					width: margin2
				}, {
						duration: 'slow',
						easing: 'easeOutQuint',
						queue: false, 
						complete: function(){map.updateSize();}
					}
					);
			
			
			} 

			//updateMapSize();

		  }); 

		});
	// Slider code ends ---------------------------------
	</script>

	<link rel="stylesheet" href="css/ol_default_style.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/fullscreen.css" type="text/css">


<link rel="stylesheet" href="css/mapis_ui.css" type="text/css" />
</head>

<body onload="initmap();" onresize="onAppResize();">
<div id=header></div>
<div id="wrap">  
  <div id="maincontent">
	<div id="control">
		<a id="controlbtn" class="open" href="http://aext.net" border="0"></a>
	</div>
     <div id="linkblock">
      <h4>Spaltadjofull</h4>
      <ul id="yourlist">
        <li> 
          <a href="http://aext.net/category/css/" title="CSS & XHTML">Tinky Winky</a>
        </li>
        <li> 
          <a href="http://aext.net/category/php/" title="Resources">Dipsy</a>
        </li>
        <li> 
          <a href="http://aext.net/category/resources/" title="Resources">Lala</a>
        </li>
        <li> 
          <a href="http://aext.net/category/theme-layout/" title="Themes & Layouts">Po</a>
        </li>
      </ul>
    
    </div>
  
  	<div id="map" class=hrannarMap></div>
  
  </div>

</div>
</body>
</html>