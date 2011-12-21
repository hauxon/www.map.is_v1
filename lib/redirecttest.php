<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


        $url = 'http'.((empty($_SERVER['HTTPS'])&&$_SERVER['SERVER_PORT']!=443)?'':'s').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $arr = parse_url($url);
        
?><script type="text/javascript">
    /*
     <?php echo print_r($arr ); ?>
     */
        var urllink="<?php echo $url; ?>";
        var scheme="<?php echo ((empty($arr['scheme']))?'':$arr['scheme']); ?>";              
        var host="<?php echo ((empty($arr['host']))?'':$arr['host']); ?>";           
        var port="<?php echo ((empty($arr['port']))?'':$arr['port']) ?>";            
        var path="<?php echo ((empty($arr['path']))?'':$arr['path']); ?>";    
        var fragment="<?php echo ((empty($arr['fragment']))?'':$arr['fragment']); ?>";   

<?php
        
        
        //$explodedurlarrquerystring = explode('?', $url);
        //$explodedurlarrhashbangajax = explode('!', $url);
        //if(count($explodedurlarrquerystring) > 1 || count($explodedurlarrhashbangajax) > 1){
?>
        if( location.hash.slice(1).length > 0 ){
            var isredirect = true;
            location.href="http://193.4.153.85:8088/www.map.is/";
        }
<?php
            //header("Location: http://193.4.153.85:8088/www.map.is");
        //}

?>
</script>    
