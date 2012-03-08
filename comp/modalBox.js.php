<?php if(1==2){?>//<script type="text/javascript"><?php }?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
//#dialog is the id of a DIV defined in the code below 
//<a href="#dialog" name="modal">Simple Modal Window</a>
 
function initModalBox(){
    //generic modalbox - $j("#modalpopup").html("<div id='printText'>texti</div>"); doModal($j('#button')); 
    var sHtml = '<div id="modalboxes">';
    sHtml += '<div id="modalpopup" class="modalwindow">';
    sHtml += '<a href="#" class="modalclose">Close it</a>';
    sHtml += '</div>';
    
    sHtml += '<div id="modalpopup2" class="modalwindow">';
    sHtml += '<a href="#" class="modalclose">Close it</a>';
    sHtml += '</div>';    
    
    //specific modalbox -  doModalPopup($j('a[name=printpopup]'),"<div id='printText'>texti</div>")
    sHtml += '<div id="printpopup" class="modalwindow">';
    sHtml += '<a href="#" class="modalclose">Close it</a>';
    sHtml += '</div>';        
    
    // Do not remove div#mask, because you'll need it to fill the whole screen
    sHtml += '<div id="modalmask"></div>';
    sHtml += '</div>';
    $j("body").append( sHtml );

    //if close button is clicked
    $j('.modalwindow .modalclose').click(function (e) {
        //Cancel the link behavior
        e.preventDefault();
        $j('#modalmask, .modalwindow').hide();
    });     

    //if mask is clicked
    $j('#modalmask').click(function () {
        $j(this).hide();
        $j('.modalwindow').hide();
    });    

    
}

function closeModalWindow(){
    $j('#modalmask, .modalwindow').hide();
}



function doModalPopup(button, text){
    
    var id = $j(button).attr('href');
    $j(id).html(text);
    
    $j(button).click(function(e) {
        //Cancel the link behavior
        e.preventDefault();
        var id = $j(this).attr('href');

        var maskHeight = $j(window).height() + $j("body").height();
        var maskWidth = $j(window).width();

        //Set height and width to mask to fill up the whole screen
        $j('#modalmask').css({'width':maskWidth,'height':maskHeight});

        //transition effect     
        $j('#modalmask').fadeIn(1000);    
        $j('#modalmask').fadeTo("slow",0.8);  

        //Get the window height and width
        var winH = $j(window).height();
        var winW = $j(window).width();

        //Set the popup window to center
        $j(id).css('top',  winH/2-$j(id).height()/2);
        $j(id).css('left', winW/2-$j(id).width()/2);

        //transition effect
        $j(id).fadeIn(2000);  
    });
}

function doModal(button){
    
    $j(button).click(function(e) {
        e.preventDefault();
        var id = $j(this).attr('href');
        var maskHeight = $j(window).height(); // + $j("body").height();
        var maskWidth = $j(window).width();
        $j('#modalmask').css({'width':maskWidth,'height':maskHeight});
        $j('#modalmask').fadeIn(1000);    
        $j('#modalmask').fadeTo("slow",0.8);  
        var winH = $j(window).height();
        var winW = $j(window).width();
        $j(id).css('top',  winH/2-$j(id).height()/2);
        $j(id).css('left', winW/2-$j(id).width()/2);
        $j(id).fadeIn(2000);  
    });
}
 //</script>