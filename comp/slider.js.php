<?php

/*
        depends on jquery,
	jquery-ui-personalized,
        jquery-ui,
	easing
 */
?>

function initSlider(){
    
    //Texti fyrir um map.is
    var subHeader1 = "<div class='slider_sub_header'>Leitaðu eftir heimilisföngum, örnefnum eða áhugaverðum stöðum</div>";
    var subHeadertxt1 = "<div class='slider_sub_txt'>Þú slærð inn það leitarorð sem þú hefur í huga og map.is reynir að finna það fyrir þig hvort sem það er veitingastaður, heimilsfang, pósthús, foss, apótek eða bara fjall. </div>";
    var subHeader2 = "<br/><div class='slider_sub_header'>Vegvísun, finndu bestu leiðina</div>";
    var subHeadertxt2 = "<div class='slider_sub_txt'>Þú getur notað map.is til að finna leiðir milli tveggja staða með þvi að slá inn heimilisföng eða hægri-smella á kortið til að setja upphafs og endapunkta. <img style='padding-top:3px;' src='img/gui/slider_more_arrow_txt.png' onclick='javascript:$j( \"#sliderAccordion\" ).accordion( \"option\", \"active\", 1 );'/></div>";
    var subHeader3 = "<br/><div class='slider_sub_header'>Deildu kortinu með vinum þínum</div>";
    var subHeadertxt3 = "<div class='slider_sub_txt'>Þegar þú hefur fundið staðinn sem þú varst að leita af getur þú deilt honum með öðrum með því að pósta honum á Facebook eða Twitter eða í tölvupósti.</div>";
    var subHeader4 = "<br/><div class='slider_sub_header'>Val um hefðbundið kort eða loftmynd</div>";
    var subHeadertxt4 = "<div class='slider_sub_txt'>Þú getur skipt á milli loftmynda og landakorts til að glöggva sig betur á aðstæðum. Á  map.is eru loftmyndir sem ná yfir allt landið.  Það skiptir því ekki máli hvort þú ert að skoða Reykjavík eða ert uppi á hálendi.  </div>";
    
    $j("#sliderAccordion").append("<h3><a href='#'>Vegvísun</a></h3><div><p><div id='routingPanel'></div></p></div>");
    $j("#sliderAccordion").append("<h3><a href='#'>Leitarniðurstöður</a></h3><div><p><div id='searchResultPanel'></div></p></div>");
    //$j("#sliderAccordion").append("<h3><a href='#'>Þekjur</a></h3><div><p><div id='testarea'></div></p></div>");
    $j("#sliderAccordion").append("<h3><a href='#'>Um map.is</a></h3><div><p><div id='umMapPanel'>" + subHeader1 + subHeadertxt1 + subHeader2 + subHeadertxt2 + subHeader3 + subHeadertxt3 + subHeader4 + subHeadertxt4 + "</div></p></div>");
}

$j(document).ready(function() {

    initializeSliderClick();
    
    // hafa spalta lokaðan í upphafi
    // ok ekki hafa það þannig!!!! $j("a#sliderPanelBtn").click();
});

function initializeSliderClick()
{
    $j("a#sliderPanelBtn").click(function(e) {

        e.preventDefault();     
        
        var slidepx=$j("div#sliderPanel").width() + 10;

        var duration = 250;
        if ( !$j("div#sliderPanel").is(':animated') ) { 

            //if (parseInt($j("div#sliderPanel").css('marginLeft'), 10) < slidepx) {
            if ($j('#sliderPanelBtn').hasClass('close'))
            {        
                    $j("div#LM_panzoombar").animate({ 
                                    left: "+=350px"
                              }, duration );
                    $j("div.olControlScaleBar").animate({ 
                                    left: "+=350px"
                              }, duration );						  					

                    $j(this).removeClass('close').html('');
                    margin = "+=" + slidepx;

            } else {

                    $j(this).addClass('close').html('');
                    margin = "-=" + slidepx;
                    $j("div#LM_panzoombar").animate({ 
                                    left: "-=350px"
                              }, duration );
                    $j("div.olControlScaleBar").animate({ 
                                    left: "-=350px"
                              }, duration );
            }

            $j("div#sliderPanel").animate({ 
                    marginLeft: margin
              }, {
                            duration: 'slow',
                            easing: 'easeOutQuint'
                    });     
        } 

    }); 
    var leToolBarMover = setTimeout(function(){grabZoomBarAndMove();}, 1000);     
}

function grabZoomBarAndMove(){

	//$j(".olControlScaleBar").hide();
        $j(".olControlScaleBar").css("left", "350px");
	//$j(".olControlScaleBar").fadeIn();	
}

function updateWhenLSIsUpdated(){
    
    var pzbControl = map.getControlsBy("CLASS_NAME","OpenLayers.Control.PanZoomBar")[0];
    pzbControl.destroy();

    map.addControl(new OpenLayers.Control.PanZoomBar({'div':OpenLayers.Util.getElement('LM_panzoombar')}));
    // Hér á að tryggja að customisering á panzoombar haldist við layer breytinguna
    doTheFunkyBar();

    if( $j("#sliderPanelBtn").hasClass("close") ){
        $j(".LM_PanZoomBarContainer").css("left","0px");
        $j("#LM_panzoombar").css("left","0px");
    }else{
        $j(".LM_PanZoomBarContainer").css("left","350px");
        $j("#LM_panzoombar").css("left","350px");
    }
}