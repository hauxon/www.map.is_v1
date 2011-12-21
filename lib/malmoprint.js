/**
 * @requires OpenLayers/Control.js
 */


var olControlPrint;
var pmarkers;

function initMalmoPrint()
{
    olControlPrint = new OpenLayers.Control.Print({});
}

OpenLayers.Control.Print = OpenLayers.Class(OpenLayers.Control, {
	
	initialize : function() {},
	
	activate : function() 
        {
            if(this.active===true)
                return false;
            
            var baseLocation=document.URL.split("?")[0];
            var webParams=utils.getParams(null,true);
            this.createPrintWindow(webParams);
            this.closeLegends();
            return OpenLayers.Control.prototype.activate.apply(this,arguments);
        },
	
	deactivate : function() 
        {
            if(this.active!==true)
                return false;
            
            if($j("#print-iframe").length)
                $j("#print-iframe").empty().remove();
            
            if(this.dialogDiv)
            {
                this.dialogDiv.dialog("destroy");
                this.dialogDiv.empty().remove();
            }
            return OpenLayers.Control.prototype.deactivate.apply(this,arguments);
        },
	
	destroy : function() 
        {
            this.deactivate();
            return OpenLayers.Control.prototype.destroy.apply(this,arguments);
        },
	
	/**
	 *  
	 *  This method/function should be called when the map has been REINITIALIZED
	 *  and LOADED. The reason if the following.
	 *  
	 *  I noticed that in order to get all tiles at the correct position
	 *  when printing, the map must be rendered again. The best solution I found
	 *  here was to reload the map. This means the map must be recreated from a URL
	 *  - the map should be recreated just as it looked when the user pressed the
	 *  "Print"-button (with the same visible layers and same zoom and center point).
	 *  
	 *  All WMS-layers, TileCache layers and vector layers must have been
	 *  loaded and rendered BEFORE this function is called. Otherwise you might end up
	 *  with a few missing layers.
	 *  
	 *  @param url {String}
	 *      The URL to the map configuration which should be printed when loaded.
	 *  @param size {Object}
	 *      Associative array containing keys "w" and "h" which holds the width {Integer}
	 *      and height {Integer} of the map (iFrame) which will be printed.
	 *  
	 */
	startPrint : function(url, size) {
		/*var iFrame = $j("#print-iframe");
		if (iFrame && iFrame.length) {
			iFrame.empty().remove();
		}
		iFrame = $j("<iframe id='print-iframe' src='"+url+"' width='"+size.w+" height='"+size.h+"' />");
		$j("body").append(iFrame);
		iFrame.css({
			"position" : "absolute",
			"left" : "0px",
			"top" : "0px",
			"z-index" : "0",
			"height" : size.h + "px",
			"width" : size.w + "px",
			"opacity" : "0",
			"filter" : "alpha(opacity=0)"
		});*/
	},
	
	/**
	 * Create an image of the map with any PIL suported 
	 * 
	 * @param map {Object} An instance of OpenLayers.Map.
	 * @param format {String} jpeg, png, pdf etc...
	 * @param quality {Optional} {Integer} 0<=100 where 100 is best quality. This parameter applies only to jpeg format.
	 * @param headerText {Optional} {String} When creating a PDF this text will be put on top of the map.
	 */
	createImage : function(map, format, quality, headerText) {

                format = format || this.exportMap.defaultFormat;
		quality = quality || 100;
		headerText = headerText || null;
                this.printWin=window;
		
		var mapConfigJSON = this.getMapConfigAsJSON.call(this, map);
		var	size = map.getSize(),
			scale = map.getScale();//map.resolutions[map.zoom];
		var outputPath = "F:/apachewww/www.map.is/serverside/img/" + "img" + "." + format;//this.exportFolder + "img" + "." + format;
		var self = this;
                
                
                //printWin.blur();
                //printWin.close();

                //doc=$j(window.printWin.document);
                
                
		$j.ajax({
			type: 'POST',
			url: "http://193.4.153.85:8088/www.map.is/serverside/printIt.py",
			data : {
				width : size.w, // {Integer} width of map
				height : size.h, // {Integer} height of map
				layers : mapConfigJSON, // {String} the JSON-string with geodata.
				outputPath : outputPath, // {String} The path for the created image which we want to featch later on
				quality : quality, // {Integer} Quality of the image (0<=100)
				headerText : headerText, // {String} Text header for the pdf
				imageFolderPath : self.imageFolderPath, // {String} The path to the folder where you store all marker/feature images which are pasted into the map (vector layers).
				scale : scale, // The scale - used for creating a scalebar (not implemented yet).
                                webContentPath : "serverside/img/"
			},
			success: function(text) {
                                //debugger;
				//$j("body").empty().append(text); // for debugging, to see the error msg from the server.
				//var picURL = self.publicExportFolder + text, doc = $j(parent.document);
                                var picURL = text, doc = $j(parent.document);
                                var win=window.parent;
                                
                                var whenImageIsLoaded=function()
                                {
                                       // window.print();
                                       // window.close();
                                        //doc.find("#print-confirmPrint").attr("disabled",false);
                                        
                                }
                    
				
				// PRINT MODE - PUT THE IMAGE INTO A PRINT WINDOW.
                                self.printMode = 1; // harðkóðað til að byrja með
				if (self.printMode==1) {
                                    //debugger;
                                    //doc=$j(window.opener.document);
                                    
                                    // Búum til element til að 
                                    var print_img_div =$j("<div id='print_img_div' />");
                                    var print_logo_div =$j("<div id='print_logo_div' ><img src='img/routing/maplogo_bull.gif' width='69' height='53'></div>");
                                    var print_description_div =$j("<div id='print_description_div' />");
                                    var print_routing_div =$j("<div id='print_routing_div' />");
                                    var img = $j("<img src='"+picURL+"' />");
                                    
                                    img.attr("src",picURL);
                                    img.attr("id","print_img");
                                    img.attr("width","600");
                                    img.attr("height","600");
                                    img.attr("style","border:1px solid #333333;");
                                    
                                    // Bíðum eftir að myndin er hlaðin inn áður en við prentum hana út
                                    img.load(whenImageIsLoaded);
                                                                
                                    //Bætum prent DIVinum við boddíið og setjum myndina inn í
                                    $j("body").append(print_img_div);
                                    $j("body").append(print_logo_div); 
                                    $j("body").append(print_description_div); 
                                    $j("body").append(print_routing_div); 
                                    $j("#print_img_div").append(img);    
                                    $j("#print_description_div").append($j( "#prentakorttxt").val() );
                                    debugger;
                                    window.print();
                                    
                                    // Eyðum prent DIVum  út eftir prentun
                                    $j("#print_img_div").remove();
                                    $j("#print_logo_div").remove();
                                    $j("#print_description_div").remove();
                                    $j("#print_routing_div").remove();
                                    
                                    //$j("#print_img_div").html("");
                                    
                                    /*  Frystum popup útfærsluna  í bili ...eða alfarið
                                     *  
                                    //var printWin=window.open("",'imageExportResult','width=900, height=700, left=200,top=100');
                                    var printWin =  window.open("http://193.4.153.85:8088/www.map.is/prent.php","_blank","toolbar=no, location=no, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=no, width=800, height=600");
                                    var prentgluggabody = $j(printWin.window.document.body);
                                    //$j("body").empty().append(img);
                                    prentgluggabody.empty().append(img);
                                    img.css({
                                            "margin-top" : "100px",
                                            "margin-left" : "85px", //"90px"
                                            "border" : "1px solid #444",
                                            "width":"800px",
                                            "height":"600px",
                                            "z-order" : "10001"
                                    });
                                    var headerDiv=$j("<div />"),headerTextDiv=$j("<div />");
                                    headerText=headerText||"www.map.is";
                                    headerTextDiv.text(headerText);
                                    headerDiv.append(headerTextDiv);
                                    prentgluggabody.prepend(headerDiv);
                                    headerDiv.css({"position":"absolute","left":"33px","top":"50px","text-align":"center","width":"600px","height":"40px","border":"none","line-height":"40px"});
                                    headerTextDiv.css({"font-weight":"normal","font-size":"30px","text-align":"center","color":"#444"});
                                    
                                    // prenta popparann
                                    printWin.window.print();
                                    
                                    */

                                    // Safari and Chrome -> print window does not appear without delay.
                                    /*var browser = OpenLayers.Util.getBrowserName();
                                    switch (browser) {
                                    case "safari":
                                            setTimeout("window.print();", 1500);
                                            break;
                                    default:
                                            window.print();
                                    }
                                    // "Enable" print button again.
                                    doc.find("#print-confirmPrint").attr("disabled", false);*/
				}
				
				// EXPORT MODE - CREATE A BUTTON WHICH WILL DOWNLOAD THE IMAGE.
				else if (self.printMode==2) {
                                    debugger;
                                    // Export - create download interface.
                                    var divDownload = doc.find("#print-divDownload"),
                                            btnDownload = doc.find("#print-btnDownload");
                                    divDownload.show();
                                    btnDownload.click(function(e) {
                                            window.location.href = picURL;
                                    });
				}
                                
                                // Hide the loading animation of the parent window.
				/*var loadAnim = doc.find("#print-loadAnim"),
					dimmer = doc.find("#print-dimmer");
				loadAnim.remove();
				dimmer.remove();*/
			}
		});
	},
        
        
        createPrintWindow:function(webParams)
        {
                var div=$j("<div id='print-dialogdiv' />");
                var mapDiv=$j("<div id='print-mapdiv' />");
                div.append(mapDiv);
                var self=this;
                div=utils.makeDialog(div,
                {position:[10,10],width:720,height:550,titleText:"Prenta kort",onClose:function()
                        {
                                var isIE=(SMap.browser.search(/msie/i)!=-1);
                                self.deactivate();
                                if(isIE&&self.hasExported===true)
                                {
                                        var url=utils.getParams(document.location.href.split("?")[0],false);
                                        document.location=url;
                                }
                        }
                });
                div.dialog("open");
          
                this.dialogDiv=div;
                var pMap=this.makePrintMap(webParams);
                this.pMap=pMap;
                var sideDiv=$j("<div id='print-sidediv' />");
                div.append(sideDiv);
                var divPrint=$j("<div id='print-divPrint' class='print-catDiv' />"),divExport=$("<div id='print-divExport' class='print-catDiv' />"),divDownload=$("<div id='print-divDownload' />");
                var catButtonPrint=$j("<div id='print-catBtnPrint' class='print-catBtn'>"+this.printMap.flapLabel+"</div>"),catButtonExport=$j("<div id='print-catBtnExport' class='print-catBtn'>"+this.exportMap.flapLabel+"</div>"),catButtonDiv=$j("<div id='print-catBtnContainer' />");
                catButtonDiv.append(catButtonPrint)
                if(this.exportMap.flapLabel)
                {
                        catButtonDiv.append(catButtonExport);
                }
                this.catButtons={"print":catButtonPrint,"export":catButtonExport};

                this.catDivs={"print":divPrint,"export":divExport};

                for(var key in this.catButtons)
                {
                        var btn=this.catButtons[key];
                        btn.click( function()
                        {
                                var btnID=$(this).attr("id");
                                var cat=btnID.replace(/print-catBtn/g,"").toLowerCase();
                                self.pressCatButton.call(self,cat);
                        });
                }
                divExport.append(divDownload);
                sideDiv.append(catButtonDiv).append(divPrint).append(divExport);
                divDownload.hide();
                var printHelp=$j("<div class='print-help' id='print-printHelp' />");
                divPrint.append(printHelp);
                var writeHeaderLabel=$j("<div class='print-label' id='print-writeheader-lbl2' />");
                writeHeaderLabel.text("Skriv en rubrik (valfritt)");
                var writeHeaderTag=$j("<input type='text' class='print-entry' id='print-writeheader2' />");
                divPrint.append(writeHeaderLabel);
                divPrint.append(writeHeaderTag);
                var confirmPrint=$j("<input type='button' class='print-confirmButton' id='print-confirmPrint'></input>");
                confirmPrint.val(this.printMap.confirmLabel);
                divPrint.append(confirmPrint);
                confirmPrint.css("width",this.printMap.buttonWidth+"px");
                confirmPrint.click(function()
                {
                        self.showUserConditions(self.doPrint);
                });
                if(this.exportMap.flapLabel)
                {	
                    var exportHelp=$j("<div class='print-help' id='print-exportHelp' />");
                    divExport.append(exportHelp);
                    var selectFormatLabel=$j("<div class='print-label' />");
                    selectFormatLabel.text("Välj format");
                    divExport.append(selectFormatLabel);
                    var selectFormatTag=$j("<select class='print-options' id='print-selectformat' />"),formats=this.exportMap.formats;
                    for(var i=0,len=formats.length;i<len;i++)
                    {
                            var option=$j("<option value='"+formats[i]+"'>"+formats[i]+"</option>");
                            selectFormatTag.append(option);
                    }
                    divExport.append(selectFormatTag);
                    selectFormatTag.change(function(e)
                    {
                            self.updateFormat();
                    });
                    var writeHeaderLabel=$j("<div class='print-label' id='print-writeheader-lbl' />");
                    writeHeaderLabel.text("Skriv en rubrik");
                    var writeHeaderTag=$j("<input type='text' class='print-entry' id='print-writeheader' />");
                    divExport.append(writeHeaderLabel);
                    divExport.append(writeHeaderTag);
                    var confirmExport=$j("<input type='button' class='print-confirmButton' id='print-confirmExport'></input>");
                    confirmExport.val(this.exportMap.confirmLabel);
                    confirmExport.click(function()
                    {
                            self.showUserConditions(self.doExport);
                    });
                    divExport.append(confirmExport);
                    confirmExport.css("width",this.exportMap.buttonWidth+"px");
                    var btnDownload=$j("<input type='button' class='print-confirmButton' id='print-btnDownload'></input>"),btnGoBack=$j("<input type='button' class='print-confirmButton' id='print-btnGoBack'></input>"),downloadHelp=$j("<div class='print-help' id='print-downloadHelp' />"),animArrow=$j("<img id='print-animarrow' src='img/UI/printArrow.png' />");
                    btnDownload.val("Ladda ner");
                    btnGoBack.val("Tillbaka");
                    btnGoBack.click(function()
                    {
                            divDownload.hide();
                            self.pressCatButton("export");
                    });
                    divDownload.append(downloadHelp).append(btnDownload).append(btnGoBack).append(animArrow);
                    this.updateFormat();
                    divPrint.hide();
                }
                $j.getJSON("help/help.js",
                function(data)
                {
                        var obj=data.print;
                        $("#print-printHelp").html(obj.printText);
                        if(self.exportMap.flapLabel)
                        {
                                $("#print-exportHelp").html(obj.exportText);
                                $("#print-downloadHelp").html(obj.downloadText);
                        }
                });
                this.pressCatButton("print");
                this.addUserConditions();
        },
        
        
        doPrint:function()
        {
                //this.startLoadAnim.call(this);
                //var btn=$j("#print-confirmPrint");
                //$j(btn).attr("disabled",true);
                //var headerText=$j("#print-writeheader2").val();
                //headerText=headerText.length?","+headerText:"";
                //var url=utils.getParams(document.URL.split("?")[0],null,this.pMap)+"&printmap=1"+headerText,size=this.pMap.getSize();
                var url = "http://193.4.153.85:8088/www.map.is/printPop.php";
                var printWin=window.open(url,'imageExportResult','width='+(size.w)+', height='+(size.h)+', left=200,top=100');
                printWin.blur();
                printWin.blur();
        },
        
        
        makePrintMap:function(webParams)
        {
            var pMap=new OpenLayers.Map("print-mapdiv",{projection:map.projection,units:"m"});
            var mapConfigInst=new MapConfig(pMap);
            /*mapConfigInst.handleMapParams();
            mapConfigInst.handleWebParams(webParams,false);*/
            // Setja í staðin parametra sjálfir
            
            mapConfigInst.addOverlays();
            SMap.mapLoaded(pMap);
            return pMap;
        },

  
        
	
        
	/**
	 * Get the geometry (no. of dimensions) of the input feature.
	 * Returns these values:
	 * polygon: 2
	 * line: 1
	 * point: 0
	 * undefined: null
	 * 
	 * @param geometry {OpenLayers.Geometry}
	 * @return {Integer} or {null}
	 *     Number of dimensions of the input feature
	 * 
	 */
	getGeomType : function(geometry) {
		objArea = geometry.getArea();
		objLen = geometry.getLength();
		
		if (objArea!=0) {
			geometry=2; // polygon=2
		}
		else {
			if (objLen!=0) {
				geometry = 1; // line=1
			}
			else {
				geometry = 0; // point=0
			}
		}
		return geometry;
	
	},
	
	getMapConfigAsJSON : function(map) {
		// go through all layers, and collect a list of objects
	    // each object is a tile's URL and the tile's pixel location relative to the viewport

            var layersArr = [];
		
	    var size = map.getSize(); // Used for determining if vector features are within bounds or not.
	    
	    var layers = map.layers;
	    
	    for (var layerIndex in layers) 
            {
	    	// if the layer isn't visible, not in range, or is turned off - continue iteration.
	        var layer = map.layers[layerIndex];
	        var layerName = layer.name;
			
		// Get the config object for this layer (used to find out which image is used by a vector layer.)
		var t = utils.getLayerWithName(layerName) || {};
	        
                if (!layer.getVisibility()) 
                    continue;
	        
	        if (!layer.calculateInRange())
                    continue;
	        
	        var zIndex = layer.getZIndex();
	        
	        // ---------- Store the layer style ----------------------------------------------------------
	        if (layer.CLASS_NAME=="OpenLayers.Layer.Vector" && layer.features && layer.features.length) 
                {
                    //debugger;
                    //var s = layer.styleMap.styles["default"].defaultStyle;
                    var s = layer.style;
                    var features = layer.features;
                    var layerConfig = 
                    {
                        url : 			s.externalGraphic || null,
                        zIndex :		zIndex || null,
                        layerType :		"vector",
                        layerName : 		t.displayName || s.name || null,
                        legendImage :		t.markerImage || null,
                        fillColor : 		s.fillColor || null,
                        fillOpacity : 		s.fillOpacity ? parseInt(s.fillOpacity*255) : 255,
                        graphicWidth :		s.graphicWidth || null,
                        graphicHeight : 	s.graphicHeight || null,
                        strokeColor :		s.strokeColor || null,
                        strokeOpacity : 	s.strokeOpacity ? parseInt(s.strokeOpacity*255) : 255,
                        strokeWidth : 		s.strokeWidth || null,
                        pointRadius :		s.pointRadius || null,
                        features : []
                    };
	        	
                    var graphicXOffset = s.graphicXOffset ? parseInt(s.graphicXOffset) : 0;
                    var graphicYOffset = s.graphicYOffset ? parseInt(s.graphicYOffset) : 0;
				
                    // ----- Iterate through all features in this layer and store each feature's position. -----------
                    var featuresArr = [];
                    for (var i=0, len=features.length; i<len; i++) 
                    {
	        	var f = features[i];
	        	var geometry = f.geometry;
	        	var nodes = geometry.getVertices();
	        	var geomTypes = {
	        			0 : "point",
	        			1 : "line",
	        			2 : "polygon"
	        	};
	        	var geomNr = this.getGeomType(geometry);
	        	var geomType = geomTypes[geomNr];
	        		
	        	var lenJ = nodes.length;
	        	var nodesArr = []; // Holds the coordinates {Array([x1,y1], [x2,y2])} for each feature
	        		
	        	// Make all nodes into view port pixels instead of lon-lat,
	        	// so that they can be drawn in the image on server-side.
	        	for (var j=0; j<lenJ; j++) 
                        {	
                            var n = nodes[j];
                            var lonLat = new OpenLayers.LonLat(n.x, n.y);
                            var px = map.getPixelFromLonLat(lonLat);
                            px = new OpenLayers.Pixel(px.x+graphicXOffset, px.y+graphicYOffset);

                            // Only store node if within view port.
                            var intersects=true;
                            if (geomType=="point") {
                                intersects = utils.xyIntersectsRectangle(
                                                    [px.x, px.y],
                                                    [0, 0, size.w, size.h]
                                    );
                            }
                            if (intersects===true) {
                                    nodesArr.push([px.x, px.y]);
                            }
	        	}
                        // Don't store a node outside view port, in the featuresArr.
                        if (nodesArr.length==0) {
                                continue;
                        }
                        // Extend this layers config with specific data for this feature (nodes and geomType).
                        var featureConfig = {
                                        geomType : 	geomType,
                                        nodes : 	nodesArr
                        };
                        featuresArr.push(featureConfig);
                    }
                    layerConfig.features = featuresArr;
                    layersArr.push(layerConfig);
	        }
                else if (layer.CLASS_NAME=="OpenLayers.Layer.Markers" && layer.markers && layer.markers.length) 
                {
                    // MARKERS KLÁRA!!!!!! ==============================================
                    for ( var k = 0; k < layer.markers.length; k++ )
                    {
                        var url = layer.markers[k].icon.url;
                        
                    }
                }
	        else {
	        	// iterate through their grid's tiles, collecting each tile's extent and pixel location at this moment
		        for (tilerow in layer.grid) {
		            for (tilei in layer.grid[tilerow]) {
		                var tile     = layer.grid[tilerow][tilei];
		                var url      = layer.getURL(tile.bounds);
		                var position = tile.position;
		                var opacity  = layer.opacity ? parseInt(255*layer.opacity) : 255;
		                layersArr.push({
		                	url: url,
		                	zIndex: zIndex,
		                	x: position.x,
		                	y: position.y,
		                	opacity: opacity,
		                	layerType : "tile",
							layerName : t.displayName || layer.name || null,
							legendImage : t.markerImage || null
		                });
		            }
		        }
	        }
	    }
	    var layersArr_json = JSON.stringify(layersArr);
	    return layersArr_json;
	}
});

var utils={};

utils.xyIntersectsRectangle=function(xy,rectangleNodes)
{
    var polygon=new OpenLayers.Geometry.Polygon([new OpenLayers.Geometry.LinearRing([new OpenLayers.Geometry.Point(rectangleNodes[0],rectangleNodes[1]),new OpenLayers.Geometry.Point(rectangleNodes[2],rectangleNodes[1]),new OpenLayers.Geometry.Point(rectangleNodes[2],rectangleNodes[3]),new OpenLayers.Geometry.Point(rectangleNodes[0],rectangleNodes[3]),new OpenLayers.Geometry.Point(rectangleNodes[0],rectangleNodes[3])])]);
    var point=new OpenLayers.Geometry.Point(xy[0],xy[1]);
    return polygon.intersects(point);
};
 
utils.getLayerWithName=function(name)
{
    var tr=null;
    if(pMap.hasOwnProperty(name))
    {
        tr=pMap[name];
    }
    else
    {
        for(var i=0,len=pMap.layers.length;i<len;i++)
        {
            var t=pMap.layers[i];
            if(t.name==name)
            {
                tr=t;
                break;
            }
        }
    }
    return tr;
};


utils.getParams=function(location,asDict,map)
{
	location=location||null;
	asDict=asDict||false;
	var webParamsInst=new pMap.WebParams(map),webParams=null;
	map=map||pMap.map;
	if(asDict===true)
	{
		webParams=webParamsInst.makeParamsDict(true);
	}
	else
	{
		webParams=webParamsInst.makeParams(location);
	}
	webParamsInst=null;
	return webParams;
};


var scriptsFolderURL = "http://193.4.153.85:8088/www.map.is/serverside/";

var exportFolder = "F:/apachewww/www.map.is/serverside/img/";




MapConfig = OpenLayers.Class(
{
    /**
	 * Constructor of the class
    */
    initialize : function(map) {
            this.map = map;
    },
    
    addOverlays : function() 
    {
        /**
            * If you add all layers at once - it might take a longer time
            * for the map to start up if you have many layers. However, the
            * layers might load slightly faster the first time you
            * click on a layer since they already loaded to the map (only
            * the visibility property for the layer has to be changed.
            * The recommended way if you have more than ca 4 layers is to
            * avoid loading all layers at once (set arg to false)
            * 
            * true -> load all layers from start
            * false -> do not load layers from start (only first time you click on a layer).
            * 			Note! The exception is where layer property startVisible is true (in SMap).
            */
        var overlayInst = new SMap.Overlay(this.map);

        overlayInst.countLayersToLoad();
        if (SMap.overlays && SMap.overlays.length>0) {
                overlayInst.bindEventVisibilityChanged();
                overlayInst.addOverlays(false);
        }
        overlayInst=null;
    }
});




// Smá tilraun:
var print_wait_win = null;
function PrintMap() {
    //-- post a wait message
    print_wait_win = window.open("pleasewait.html", "print_wait_win", "scrollbars=no, status=0, height=5, width=10, resizable=1");

    // go through all layers, and collect a list of objects
    // each object is a tile's URL and the tile's pixel location relative to the viewport
    var size  = pMap.getSize();
    var tiles = [];
    for (layername in pMap.layers) {
        // if the layer isn't visible at this range, or is turned off, skip it
        var layer = pMap.layers[layername];
        if (!layer.getVisibility()) continue;
        if (!layer.calculateInRange()) continue;
        // iterate through their grid's tiles, collecting each tile's extent and pixel location at this moment
        for (tilerow in layer.grid) {
            for (tilei in layer.grid[tilerow]) {
                var tile     = layer.grid[tilerow][tilei]
                var url      = layer.getURL(tile.bounds);
                var position = tile.position;
                var opacity  = layer.opacity ? parseInt(100*layer.opacity) : 100;
                tiles[tiles.length] = {url:url, x:position.x, y:position.y, opacity:opacity};
            }
        }
    }

    // hand off the list to our server-side script, which will do the heavy lifting
    var tiles_json = JSON.stringify(tiles);
    var printparams = 'width='+size.w + '&height='+size.h + '&tiles='+escape(tiles_json) ;
    
    OpenLayers.Request.POST(
      { 
        url:'lib/print.php',
        data:OpenLayers.Util.getParameterString({width:size.w,height:size.h,tiles:tiles_json}),
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        callback: function(request) 
        {
           print_wait_win.close();
           window.open(request.responseText);
        }
      }
      
    );
}


function LM_createPrintMap()
{
    // First we create a DIV wich will hold the printMap
    var printMapHTML = "";
    var diologTestDiv = $j("<div id='dialog' title='Prenta kort'><div id='printMapContainer'><div id='pmap'  class='hrannarMap' style='width:600px;height:600px;'></div></div><div id=printMapRightSpace></div></div>");
    
    $j(function()
    {
        diologTestDiv.dialog({ height: 626, width: 800, zindex: 1000, resizable: false, modal: true });
    });
    
    $j( "#dialog" ).bind( "dialogclose", function(event, ui) {
        // Eyðum prent kortinu út
        delete pMap;
        //pmarkers.destroy();
        $j(this).remove();
    });

    var printRightSpaceHTML = "";// = "Upplýsingar og leiðbeiningar fyrir prentun.<br><br><a href=javascript:printTheFuckingMap();>PRENTA KORT</a>";
    printRightSpaceHTML += '<br>Kortið sett upp fyrir prentun.<br/><br/>'
    printRightSpaceHTML += 'Þú getur stillt kortið af fyrir prentun með því að draga það til.  Smelltu svo á "Prenta" hnappinn hér að neðan þegar kortið er tilbúið til prentunar.<br/><br/>';
    printRightSpaceHTML += 'Ef þú vilt hafa skýringartexta með í prentuninni getur skrifað hann í reitinn hér fyrir neðan.<br/><br/>';
    printRightSpaceHTML += '<textarea id=prentakorttxt style="width:180px;height:80px;"></textarea><br/><br/>';
    printRightSpaceHTML += '<!--input id=prentakortrouting type="checkbox" checked="checked" /> Prenta vegvísun<br/><br/ -->';
    printRightSpaceHTML += '<div align=center><input type=button id=prentakortbtn value=PRENTA onclick=printTheFuckingMap(); /></div>';
    $j("#printMapRightSpace").html(printRightSpaceHTML);
    
    // Create new OL Map instance
    pMap = new OpenLayers.Map('pmap', {
                                            maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
                                            // ?php if ($map->restrictedExtent != ""){ ?>restrictedExtent: new OpenLayers.Bounds("<?=$map->restrictedExtent?>"),<?php } 
                                            //restrictedExtent: new OpenLayers.Bounds(map.restrictedExtent),
                                            units:'m',
                                            panDuration: 100,
                                            controls: [
                                                new OpenLayers.Control.Navigation(
                                                {dragPanOptions: {enableKinetic: true}}
                                            )],
                                            maxResolution: '180/256',
                                            scales:[map.scales],
                                            center:map.center,
                                            projection:"EPSG:3057"
                                            });	                                   
                                            
                                            
    var lightsaberp = new OpenLayers.Layer.TMS("Lightsaber_prent",
    ["http://tc0.loftmyndir.is/tc_r/tilecache.py"],
    { layername:'lightsaber',
    type:'jpeg',
    kortasja: 'www.map.is',
    serviceVersion:'',
    isBaseLayer: true,
    displayInLayerSwitcher:true,
    attribution: ' © Loftmyndir ehf. Allur réttur áskilinn.',
    transitionEffect:'resize',
    scales: [6800000,3400000,1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000],
    //maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
    //bbox: new OpenLayers.Bounds(143000,255000,866000,735000),
    //scales:[1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000,1000,500,250],
    buffer:1});

    var myndkortp = new OpenLayers.Layer.TMS("Myndkort_prent",
    ["http://tc0.loftmyndir.is/tc_r/tilecache.py"],
    { layername:'myndkort',
    type:'jpeg',
    kortasja: 'www.map.is',
    serviceVersion:'',
    isBaseLayer: true,
    displayInLayerSwitcher:true,
    attribution: ' © Loftmyndir ehf. Allur réttur áskilinn.',
    transitionEffect:'resize',
    scales: [6800000,3400000,1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000],
    //maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
    //bbox: new OpenLayers.Bounds(143000,255000,866000,735000),
    //scales:[1700000,1000000,500000,250000,100000,50000,25000,10000,5000,2000,1000,500,250],
    buffer:1});

    // Þurfum að athuga hvaða bakgrunnskort er virkt
    if(map.baseLayer.layername == "lightsaber")
        pMap.addLayers([lightsaberp]);
    else 
        pMap.addLayers([myndkortp]);
                                        
    pMap.zoomTo(map.zoom); // Súmmum á samastað og aðalkortið
    pMap.panTo(map.center); //...og staðsetjum okkur á sama stað
    
    // rúllum í gegnum layerana of afritum þá sem eru sýnilegir
    for ( var i = 0;  i < map.layers.length; i++)
    {
        //Afritum alla sýnilega layera
        if(map.layers[i].visibility)
        {
            // Ath hvort um marker er að ræða
            if( map.layers[i].CLASS_NAME == "OpenLayers.Layer.Markers" )
            {
                pmarkers = new OpenLayers.Layer.Markers("Merki_prent",{'displayInLayerSwitcher':false});
                
                for ( var j = 0;  j < map.layers[i].markers.length; j++)
                {
                    var icon = map.layers[i].markers[j].icon.clone();
                    map.layers[i].markers[j].icon.clone();
                    pmarkers.addMarker(new OpenLayers.Marker(map.layers[i].markers[j].lonlat,icon));
                }
            }
            else
            {
                //Bæta layer á pMap
                pMap.addLayer(map.layers[i].clone() );
            }
            
        }
        
    }
    
    // Bætum markerum við seinast til að þeir komi ofan á aðra layera
    if ( pmarkers.length != 0 )
    {
        pMap.addLayer(pmarkers);
    }
    
    
    //olControlPrint = new OpenLayers.Control.Print({});
    pMap.addControl(olControlPrint );

}

function printTheFuckingMap()
{
    
    
    
    
    var printSize = new OpenLayers.Size();
    printSize.w = 600;
    printSize.h = 600;
    olControlPrint.createImage(pMap, "jpeg");
    olControlPrint.startPrint("http://193.4.153.85:8088/www.map.is/serverside/printIt.py", printSize);
}