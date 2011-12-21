<?php
/* Setur inn accordion í sliderPanel sliderinn ef viðkomandi kortasjá þarf
 * depends on jquery,
 * jquery-ui-personalized,
 * jquery-ui,
 * easing
 */
?>

// Slider code begins -------------------------------
function initAccordion(){
    $j("#sliderAccordion").accordion({ fillSpace: true, animated:'easeInOutQuad', duration:'fast', collapsible: true });
}
