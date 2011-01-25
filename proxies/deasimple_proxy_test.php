
<?php
    /*
     * Ælir út querystring á php síðu á geoserver.loftmyndir.is
     */

    $remotePage = $_GET['remotePage'];
    
    switch ($remotePage) {
    case "routing_service_click":
        $routing_click_url = 'http://212.30.228.18/kortasja/leit/routing_service_click.php?';
        break;
    case "routing_service":
        $routing_click_url = 'http://212.30.228.18/kortasja/leit/routing_service.php?';
        break;
    case "eitthvad_annad2":
        $routing_click_url = 'http://someurl.is/somepage.php?';
        break;
    }

    

    //$routing_click_url = 'http://geoserver.loftmyndir.is/kortasja/leit/routing_service_click.php?';
    $lequerystring = $_SERVER['QUERY_STRING'];

    //echo $routing_click_url.$lequerystring;

    $fp = fopen($routing_click_url.$lequerystring, 'r');

        $response = '';

        while ($l = fread($fp, 1024)) $response .= $l;
        fclose($fp);
        echo $response;
?>

