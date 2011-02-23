<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>www.map.is -- yeah for jim!?!</title>
<script src="lib/openlayers210.js"></script>
        <link rel="stylesheet" href="css/ol_default_style.css" type="text/css"/>
	<link rel="stylesheet" href="css/style.css" type="text/css"/>
	<link rel="stylesheet" href="css/fullscreen.css" type="text/css"/>
	<link rel="stylesheet" href="css/mapis_ui.css" type="text/css" />
        <link rel="stylesheet" href="css/context_menu.css" type="text/css" />
        <link rel="stylesheet" href="css/routing.css" type="text/css" />

	<script   src="lib/jquery.1.4.4.min.js"></script>
	<script  src="lib/jquery-ui-personalized-1.5.2.min.js"></script>
	<script  type='text/javascript' src='lib/easing.js'></script>
	<script type='text/javascript' src='lib/common.js'></script>
	<script type='text/javascript' src='lib/kortasja.js.php'></script>
</head>

<body onload="initmap();initComponents();" onresize="onAppResize();">
<div id=header><img id="header_logo" src="img/routing/maplogo_bull.gif"/></div>
<img id="header_shadow" src="img/routing/header_shadow.png"/>
	
     <div id="sliderPanel">
        <div id="control">
		<a id="sliderPanelBtn" class="open" href="http://www.map.is" border="0"></a>
	</div>    
    </div>
  
  	<div id="map" class=hrannarMap></div>


</body>
</html>