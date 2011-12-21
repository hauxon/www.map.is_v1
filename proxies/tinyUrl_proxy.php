<?php
    // Notað til að sækja stutta útgáfu af krækju á kort
    //$_GET['longURL'] = isset($_GET['longURL']) ? (empty($_GET['longURL'])?"":$_GET['longURL']: "";   

    $sendUrl = "http://tinyurl.com/api-create.php?url=" . $_GET['longURL'];
    
    header('Content-type: text/plain; charset=UTF-8');
    
    $fp = fopen($sendUrl, 'r');
    $response = '';
    while ($l = fread($fp, 1024)) $response .= $l;
    fclose($fp);
    echo $response;
    exit;    
    
?>