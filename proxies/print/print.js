/**
 * @requires OpenLayers/Control.js
 */

OpenLayers.Control.Print = OpenLayers.Class(OpenLayers.Control, {
	
	initialize : function() {},
	
	activate : function() {},
	
	deactivate : function() {},
	
	destroy : function() {},
	
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
		var iFrame = $("#print-iframe");
		if (iFrame && iFrame.length) {
			iFrame.empty().remove();
		}
		iFrame = $("<iframe id='print-iframe' src='"+url+"' width='"+size.w+" height='"+size.h+"' />");
		$("body").append(iFrame);
		iFrame.css({
			"position" : "absolute",
			"left" : "0px",
			"top" : "0px",
			"z-index" : "0",
			"height" : size.h + "px",
			"width" : size.w + "px",
			"opacity" : "0",
			"filter" : "alpha(opacity=0)"
		});
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
		
		var mapConfigJSON = this.getMapConfigAsJSON.call(this, map);
		var	size = map.getSize(),
			scale = map.resolutions[map.zoom];
		var outputPath = this.exportFolder + "img" + "." + format;
		var self = this;
		$.ajax({
			type: 'POST',
			url: this.scriptsFolderURL + "printIt.py",
			data : {
				width : size.w, // {Integer} width of map
				height : size.h, // {Integer} height of map
				layers : mapConfigJSON, // {String} the JSON-string with geodata.
				outputPath : outputPath, // {String} The path for the created image which we want to featch later on
				quality : quality, // {Integer} Quality of the image (0<=100)
				headerText : headerText, // {String} Text header for the pdf
				imageFolderPath : self.imageFolderPath, // {String} The path to the folder where you store all marker/feature images which are pasted into the map (vector layers).
				scale : scale // The scale - used for creating a scalebar (not implemented yet).
			},
			success: function(text) {
				//$("body").empty().append(text); // for debugging, to see the error msg from the server.
				var picURL = self.publicExportFolder + text,
					doc = $(parent.document);
				// Hide the loading animation of the parent window.
				var loadAnim = doc.find("#print-loadAnim"),
					dimmer = doc.find("#print-dimmer");
				loadAnim.remove();
				dimmer.remove();
				
				// PRINT MODE - PUT THE IMAGE INTO A PRINT WINDOW.
				if (self.printMode==1) {
					var img = $("<img src='"+picURL+"' />");
					$("body").empty().append(img);
					img.css({
						"margin-top" : "100px",
						"margin-left" : "85px", //"90px"
						"border" : "1px solid #444"
					});
					
					// Safari and Chrome -> print window does not appear without delay.
					var browser = OpenLayers.Util.getBrowserName();
					switch (browser) {
					case "safari":
						setTimeout("window.print();", 1500);
						break;
					default:
						window.print();
					}
					// "Enable" print button again.
					doc.find("#print-confirmPrint").attr("disabled", false);
				}
				
				// EXPORT MODE - CREATE A BUTTON WHICH WILL DOWNLOAD THE IMAGE.
				else if (self.printMode==2) {
					// Export - create download interface.
					var divDownload = doc.find("#print-divDownload"),
						btnDownload = doc.find("#print-btnDownload");
					divDownload.show();
					btnDownload.click(function(e) {
						window.location.href = picURL;
					});
				}
			}
		});
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
	    
	    for (var layerIndex in layers) {
	    	
	    	// if the layer isn't visible, not in range, or is turned off - continue iteration.
	        var layer = map.layers[layerIndex];
	        var layerName = layer.name;
			
			// Get the config object for this layer (used to find out which image is used by a vector layer.)
			var t = utils.getLayerWithName(layerName) || {};
	        if (!layer.getVisibility()) {
	        	continue;
	        }
	        if (!layer.calculateInRange()) {
	        	continue;
	        }
	        var zIndex = layer.getZIndex();
	        
	        // ---------- Store the layer style ----------------------------------------------------------
	        if (layer.CLASS_NAME=="OpenLayers.Layer.Vector" && layer.features && layer.features.length) {
	        	var s = layer.styleMap.styles["default"].defaultStyle;
	        	var features = layer.features;
	        	var layerConfig = {
	        			url : 				s.externalGraphic || null,
	        			zIndex :			zIndex || null,
	        			layerType :			"vector",
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
	        	for (var i=0, len=features.length; i<len; i++) {
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
	        		for (var j=0; j<lenJ; j++) {
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