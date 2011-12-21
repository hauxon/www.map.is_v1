<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
//<script lang="javascript">
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
            strokeColor: "#ff8600"/*,
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

    var vectors_measure = new OpenLayers.Layer.Vector("Maelingar", {style: styleMap, 'displayInLayerSwitcher':false});
    map.addLayer(vectors_measure);
          

    // Búum til mæli controlið 
    
    /*measureControls = {
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
            "measure": handleMeasurements  //,"measurepartial": handleMeasurements
        });
        map.addControl(control);
        
    }*/
    
OpenLayers.Control.Measure.prototype.EVENT_TYPES = ['measure', 'measurepartial', 'measuredynamic']; 
measureControls = {
    line:  new OpenLayers.Control.Measure( 
    OpenLayers.Handler.Path, { 
        textNodes: null, 
        showDistances: true, 
        persist: true, 
        geodesic: false, 
        partialDelay: 300, 
        callbacks: { 
            create: function() {  
                this.textNodes = []; 
		//getLayerByName("Maelingar").destroyFeatures();             
                
            },
            done: function(line) {
                
                //log("total length" + line.getLength() );
                // Flag to know if previous measurement was done
                // Used to earase measurement labels
                this.doneMeasure = true;
                this.textNodes = [];
                measurementDone(line);
            },
            modify: function(point, line) {
                
                // Færum Mælinga layerinn þ.a. textinn lendi ofan á línunni
                // Þyrfti helst að vera kallað í þetta þegar teiknikotrólið 
                // er í init en fann það ekki.
                map.raiseLayer(getLayerByName("Maelingar"), 3);
                                
                // introduce a delay for IE browsers so they will
                // draw the first segment						

                if (!this.showDistances) { 
                        return; 
                } 
                var len = line.geometry.components.length; 
                var from = line.geometry.components[len - 2]; 
                var to = line.geometry.components[len - 1]; 
                var ls = new OpenLayers.Geometry.LineString([ 
                          from,to]); 
                var dist = this.getBestLength(ls); 
                if (!dist[0]) { 
                        return; 
                } 
                var total = this.getBestLength(line.geometry); 
                var label; 
                if (dist[1] == 'm') { 
                        label = dist[0].toFixed(0)+dist[1]; 
                } else { 
                        label = dist[0].toFixed(1)+dist[1]; 
                } 

                var textNode = this.textNodes[len-2] || null; 
                if (textNode && !textNode.layer) { 
                        this.textNodes.pop();
                        textNode = null; 
                } 
                
                // Choose color for labeling.  White for Myndtkort and black for Kort
                // Hrannar 11.10.2011
                if(map.layers[findActiveBaseLayer()].layername == "myndkort")
                {
                    var measureLabelColor = "#FFFFFF";
                }
                else
                {
                    var measureLabelColor = "#000000";
                }
                
                
                if (!textNode) { 
                    var c = ls.getCentroid(); 
                    textNode = new OpenLayers.Feature.Vector( 
                        new OpenLayers.Geometry.Point(c.x,c.y), 
                        {}, { 
                                label: '', 
                                fontColor: measureLabelColor, 
                                fontSize: "10px", 
                                fontFamily: "Arial", 
                                fontWeight: "bold", 
                                labelAlign: "c",
                                labelXOffset: 0,
                                labelYOffset: 0,
                                graphicZIndex: 100000
                        }); 
                        
                    this.textNodes.push(textNode);                     
                    if( !this.handler.control.handler.evt.shiftKey )
                    { 	
                        // Hrannar
                        // Here we check if there was a measurement before
                        // and clean measurement labels (for the legs)
                        if(this.doneMeasure == true)
                        {
                            getLayerByName("Maelingar").destroyFeatures();
                            this.doneMeasure = false;
                        }
                        getLayerByName("Maelingar").addFeatures([textNode]); 
                    }

                } 
                textNode.geometry.x = (from.x+to.x)/2; 
                textNode.geometry.y = (from.y+to.y)/2; 
                textNode.style.label = label; 
                if( !this.handler.control.handler.evt.shiftKey ){
                    textNode.layer.drawFeature(textNode); 
                }				

                this.events.triggerEvent('measuredynamic', { 
                    measure: dist[0], 
                    total: total[0], 
                    units: dist[1], 
                    order: 1, 
                    geometry: ls 
                }); 		
            } 
        }, 
        handlerOptions: { 
            layerOptions: { 
                //styleMap: measureStyleMap //styleMap
                styleMap: styleMap                
                        }
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
    
    var hasMeasure = false; 
    measureControls["line"].events.on({ 
        measurepartial: function(evt) {
           // measureControls["line"].showDistances = true; 
            /*if (hasMeasure) { 		
                if(typeof(handleMeasurements) != 'undefined'){
                        handleMeasurements(evt); 					
                }
                hasMeasure = false; 
            } */
            //log("event measurepartial");
        }, 
        measuredynamic: function(evt) { 
            measureControls["line"].showDistances = true; 
            /*if (hasMeasure) { 					
                hasMeasure = false; 
            } 		*/								
            var measure = evt.total; 
            var units = evt.units; 
            var label; 	
        }, 
        measure: function(evt) { 
            //log("event measure");
            var measure = evt.measure; 
            var units = evt.units; 
            var label; 
            //hasMeasure = true; 
            alert("Le Mess");
            handleMeasurements(evt); 
        }
    });     

    var control;
    for(var key in measureControls) {
        control = measureControls[key];
        /*control.events.on({
            "measure": measure  //,"measurepartial": measurepartial, "measuredynamic": measuredynamic 
        });*/
        map.addControl(control);

    }
}

/*function measure(evt) { 
    var measure = evt.measure; 
    var units = evt.units; 
    var label; 
    hasMeasure = true; 
    handleMeasurements(evt); 
}     

function measuredynamic(evt) { 
    measureControls["line"].showDistances = true; 
    if (hasMeasure) { 					
        hasMeasure = false; 
    } 										

    var measure = evt.total; 
    var units = evt.units; 
    var label; 		
}    

function measurepartial(evt) { 
    measureControls["line"].showDistances = true; 
    if (hasMeasure) { 		
        if(typeof(handleMeasurements) != 'undefined')
        {
                handleMeasurements(evt); 					
        }
        hasMeasure = false; 
    } 
}*/   

function handleMeasurements(event) { 

    //log("entering handleMeasurements()");
    
    var geometry = event.geometry;
    var units = event.units;
    var order = event.order;
    var measure = event.measure;
    var out = "";
    if(order == 1) {
        out += "lengd: " + measure.toFixed(3) + " " + units;
    } else {
        out += "lengd: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
    }
    
    //debugger;
    //getLayerByName("Maelingar").destroyFeatures();
    //measureControls["line"].deactivate();
    
    pressHeaderButton("Measure"); // Slökkva á takkanum
   //log( "mesurement:" + out);
   
}//handleMeasurement

// Þessi function sér um að skrifa út mælingarnar - orginal
/*function handleMeasurements(event) {
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
}*/

/*function toggleControl(type) {

        if(olControl_measureLine.active === true) {
            olControl_measureLine.deactivate();
        } else {
            olControl_measureLine.activate();
        }
}*/

function toggleControl(type) {
    //log("entering toggleControl()");
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
    
function doMeasure()
{
    //log("entering doMeasure()");
    //debugger;
    var type = "line";
    for(key in measureControls) {
        var control = measureControls[key];
        if(type == key) 
        {
            // Control 
            control.activate();
        } else 
        {
            control.deactivate();
        }
    }
    
}

function measurementDone(line)
{
    if(line.getLength() > 1000 ){
        var measure = line.getLength()/1000;
        var out  = "lengd: " + measure.toFixed(1) + " km" ;
    }else
    {
        var measure = line.getLength();
        var out  = "lengd: " + measure.toFixed(0) + " m" ;
    }
    //var element = document.getElementById('measureOutput');
    //element.innerHTML = out;
    measureControls["line"].deactivate();
    pressHeaderButton("Measure"); // Slökkva á takkanum
        
    
    alert(out);
}
