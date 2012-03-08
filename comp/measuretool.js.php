<?php if(1==2){?>//<script type="text/javascript"><?php }?>
<?php
?>

var measureControls;

function initMeasure()
{
    map.addControl(new OpenLayers.Control.MousePosition());

    // style the sketch fancy
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

    // allow testing of specific renderers via "?renderer=Canvas", etc
    var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
    renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

    measureControls = {
        line: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Path, {
                showDistances: true, 
                persist: true,
                geodesic: false, 
                partialDelay: 300,
                handlerOptions: {
                    layerOptions: {
                        renderers: renderer,
                        styleMap: styleMap
                    }
                },
                callbacks: { 
                    create: function() {  
                        
                        this.textNodes = []; // býr til fylki til að halda utan um vegalengdir á leggjunum
                        
                        //getLayerByName("Maelingar").destroyFeatures();  
                        //debugger;
                        //$j("#measurementResults").html("");

                    },
                    done: function(line) {

                        //log("total length" + line.getLength() );
                        // Flag to know if previous measurement was done
                        // Used to earase measurement labels
                        this.doneMeasure = true;
                        //this.textNodes = [];
                        //measureControls["line"].deactivate();
                        //measurementDone(line);
                        if (line.getLength() >= 1000)
                        {
                            var linelength = (line.getLength()/1000);
                            $j("#measurementResults").html(linelength.toFixed(2) + " km");
                        }
                        else
                        {
                            var linelength = (line.getLength());
                            $j("#measurementResults").html(linelength.toFixed(0) + " m");
                        }
                        
                        
                        //Place the result div
                        var lonlatino = new OpenLayers.LonLat(line.components[line.components.length -1].x,line.components[line.components.length -1].y);
                        var pixpix = new OpenLayers.Pixel;
                        pixpix =  map.getPixelFromLonLat( lonlatino );
                        $j("#measurementResults").css("top", (pixpix.y + $j("#map").position().top - 7) );
                        $j("#measurementResults").css("left", (pixpix.x + $j("#map").position().left + 5) );
                        
                         if(map.layers[findActiveBaseLayer()].layername == "myndkort")
                        {
                           $j("#measurementResults").css("color", "#FFFFFF");
                        }
                        else
                        {
                            $j("#measurementResults").css("color", "#333333");
                        }
                        
                        
                        $j("#measurementResults").show();
                        //alert( linelength.toFixed(2) + " km");
                    },
                    modify: function(point, line) {
                        
                        
                        /* modify begins */
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
                            var measureLabelColor = "#333333";
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
                                    $j("#measurementResults").html("");
                                    $j("#measurementResults").hide();
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
                        /* modify ends*/
                    } 
                }
            }
        ),
        polygon: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Polygon, {
                persist: true,
                handlerOptions: {
                    layerOptions: {
                        renderers: renderer,
                        styleMap: styleMap
                    }
                }
            }
        )
    };

    var control;
    for(var key in measureControls) {
        control = measureControls[key];
        control.events.on({
            "measure": handleMeasurements,
            "measurepartial": handleMeasurements
        });
        map.addControl(control);
    }
    
    // Add div to display results
    var mDiv = '<div id="measurementResults"></div>';
    $j("body").append(mDiv);
    $j("#measurementResults").hide();
}

function handleMeasurements(event) {
    var geometry = event.geometry;
    var units = event.units;
    var order = event.order;
    var measure = event.measure;
    //var element = document.getElementById('output');

    var out = "";
    if(order == 1) {
        out += "measure: " + measure.toFixed(3) + " " + units;
    } else {
        out += "measure: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
    }
    
   
    //$j("#measurementResults").show();
    
    //element.innerHTML = out;
}

function doMeasure()
{
    // new measurement -> hide last result
    $j("#measurementResults").html("");
    $j("#measurementResults").hide();
    
    //debugger;
    // Hrannar
    if(measureControls.line.active)
    {
        measureControls.line.deactivate();
        getLayerByName('Maelingar').destroyFeatures();
    }
    else
    {
        measureControls.line.activate();
    }
    
    
    // activate Measure control
    /*var type = "line"; //line hardcoded for now
    
    for(key in measureControls) {
        var control = measureControls[key];
        if(type == key) 
            control.activate();
        else 
            control.deactivate();
    } */
}