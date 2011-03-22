<?php
	
	// Sækjum uppl úr config.xml og setjum í breytuna $config
	$myFile = "config/config.xml";
	$fh = fopen($myFile, 'r');
	$config = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);

	/* NOTKUN
	foreach ($config->xpath('//baseLayer') as $baseLayer) 
	{
		echo $baseLayer->layerTitle;
	}*/
?>