<?php
    
  // Method used to lower the request count and overcome IE 30 css file limit

  header('Content-type: text/css');
  ob_start("compress");
  function compress($buffer) {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
  }

  /* your css files 
  include('master.css');
  include('vefmyndavelar.css');
  include('dropdownbox.css');
  include('print.css');
  include('dialog.css');*/
  
  $myFile = "../xml/config.xml";
  $fh = fopen($myFile, 'r');
  $config = new SimpleXMLElement(fread($fh, filesize($myFile)));
  fclose($fh);
  
  
  foreach ($config->xpath('//control') as $control)
    {
        // load CSS file ...usses loadjscssfile function in common.js
        $CSSFile = $control->controlCSS;
        if ( $CSSFile != "" )
            include($CSSFile);
    }
    
    foreach ($config->xpath('//component') as $component)
    {
        // load CSS file ...usses loadjscssfile function in common.js
        $CSSFile = $component->componentCSS;
        if ( $CSSFile != "" )
            include($CSSFile);
    }

  ob_end_flush();
?>