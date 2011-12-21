<script type="text/javascript">
    var isPermalink = false;
    var isPrint = false;
    var printHeader = "";
    var printInput = "";
<?php

        //Sækja breytur fyrir permalink control sem þarf að gera í index skjali
        // default gildi í byrjun (ekki í notkun)
        $zoom = "2";
        $lon = "420000";
        $lat = "500000";
        
        $_GET['zoom'] = isset($_GET['zoom']) ? $_GET['zoom']: "";         
        $_GET['print'] = isset($_GET['print']) ? $_GET['print']: "";         
        // ef zoom er skilgreint verður permalink control virkur
        if( !empty($_GET['zoom']) ){
?>
            isPermalink = true;
<?php
        }
        
        if( !empty($_GET['print']) ){
?>
            isPrint = true;
<?php
            $_GET['title'] = isset($_GET['title']) ? $_GET['title']: ""; 
            $_GET['input'] = isset($_GET['input']) ? $_GET['input']: ""; 
?>
            printTitle = "<?php echo $_GET['title']?>";
            printInput = "<?php echo $_GET['input']?>";
<?php
        
        }
?>
</script>
        