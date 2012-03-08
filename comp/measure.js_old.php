<?php if(1==2){?>//<script type="text/javascript"><?php }?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
var measureControls;
function initMeasure(){

    //map.addControl(new OpenLayers.Control.MousePosition());

    // Byrjum á að stæla teiknitólið
    var sketchSymbolizers = {
        "Point": {
            pointRadius: 4,
            graphicName: "square",
            fillColor: "white",
            fillOpacity: 1,
            strokeWidth: 1,
            strokeOpacity: 1,
            strokeColor: "#333333"
        },
        "Line": {
            strokeWidth: 2.5,
            strokeOpacity: 1,
            strokeColor: "#ff0090"/*,
            strokeDashstyle: "dash"*/
        },
        "Polygon": {
            strokeWidth: 2,
            strokeOpacity: 1,
            strokeColor: "#666666",
            fillColor: "white",
            fillOpacity: 0.3
        }
    };
    
    var style = new OpenLayers.Style();
    style.addRules([
        new OpenLayers.Rule({symbolizer: sketchSymbolizers})
    ]);
    var styleMap = new OpenLayers.StyleMap({"default": style});


    // Búum til mæli controlið 
    measureControls = {
        line: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Path, {
                persist: true,
                handlerOptions: {
                    layerOptions: {styleMap: styleMap}
                }
            }
        ),
        polygon: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Polygon, {
                persist: true,
                handlerOptions: {
                    layerOptions: {styleMap: styleMap}
                }
            }
        )
    };

    // Öddum contrólinu
    var control;
    for(var key in measureControls) {
        control = measureControls[key];
        control.events.on({
            "measure": handleMeasurements/*,
        //    "measurepartial": handleMeasurements*/
        });
        map.addControl(control);
        
    }
}

// Þessi function sér um að skrifa út mælingarnar
function handleMeasurements(event) {
    var geometry = event.geometry;
    var units = event.units;
    var order = event.order;
    var measure = event.measure;
    var element = document.getElementById('measureOutput');
    var out = "";
    if(order == 1) {
        out += "lengd: " + measure.toFixed(3) + " " + units;
    } else {
        out += "lengd: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
    }
    //element.innerHTML = out;
    measureControls["line"].deactivate();
    pressHeaderButton("Measure"); // Slökkva á takkanum
   alert(out);
}


function toggleControl(type) {
    for(key in measureControls) {
        var control = measureControls[key];
        if(type == key) {
            control.activate();
        } else {
            control.deactivate();
        }
    }
}

/*function toggleControl(element) {
    for(key in measureControls) {
        var control = measureControls[key];
        if(element.value == key && element.checked) {
            control.activate();
        } else {
            control.deactivate();
        }
    }
}

function toggleGeodesic(element) {
    for(key in measureControls) {
        var control = measureControls[key];
        control.geodesic = element.checked;
    }
}

function toggleImmediate(element) {
    for(key in measureControls) {
        var control = measureControls[key];
        control.setImmediate(element.checked);
    }
}*/
