var map;
var map_div;

//Inject CSS
var headID = document.getElementsByTagName("head")[0];         
var cssNode = document.createElement('link');
    cssNode.type = 'text/css';
    cssNode.rel = 'stylesheet';
    cssNode.href = 'http://www.map.is/inline/inline.css';
    cssNode.media = 'screen';
    headID.appendChild(cssNode);
    
//Inject OpenLayers
var headID = document.getElementsByTagName("head")[0];         
var newScript = document.createElement('script');
    newScript.type = 'text/javascript';
    newScript.src = 'http://www.map.is/inline/OpenLayers.js'
headID.appendChild(newScript)        


function init(div)
{
    //Generate HTML
    map_div = document.getElementById(div);
    var map_contents = '<div id="baseSwitcher"><span id="photo" class="map_sel_selected"><a onclick="javascript:switchBaseLayers(0);return false;" href=#>Mynd</a></span><br>'
        +'<span id="lightsaber" class="map_sel_notselected"><a  onclick="javascript:switchBaseLayers(1);return false;" href=#>Kort</a></span>'
        +'</div><div id="mapLogoDiv"><a href="http://www.map.is" target=_blank><img alt="www.map.is" border=0 id="mapLogoImg" src=http://www.map.is/inline/map_logo_dokk.png></a>'
        +'</div><div id="LM_linkDiv"><a id="LM_linkA" href=http://3w.loftmyndir.is target=_blank>Allur réttur áskilinn Loftmyndir ehf.</a> </div>'
    map_div.setAttribute('class', 'smallmap')
    map_div.innerHTML = map_contents;
    var arr_scales = [6800000.0,3400000.0,1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0];
    var panBounds = new OpenLayers.Bounds(234248.88,297273.25,759064.98,686298.38);
    map = new OpenLayers.Map(div, {
        controls: [] ,
        maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
        restrictedExtent: panBounds,
        units:'m',
        maxResolution: '180/256',
        scales:arr_scales,
        projection:"EPSG:3057"
    });	
    var tc_servers = ["http://tc0.loftmyndir.is/tc_r","http://tc1.loftmyndir.is/tc_r","http://tc2.loftmyndir.is/tc_r","http://tc3.loftmyndir.is/tc_r"];
    map.addLayer(new OpenLayers.Layer.WMS("Myndkort",
        tc_servers,	
        {layers:'myndkort',format:'image/jpeg', kortasja: 'test' },
        {singleTile:false, 'isBaseLayer': true,displayInLayerSwitcher:true, attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>',  transitionEffect:'resize', buffer:1}))
         
     map.addLayer(new OpenLayers.Layer.WMS("lightsaber",
        tc_servers,		 
        { layers: 'lightsaber', format: 'image/jpeg', kortasja: 'test' },
        { singleTile:false, 'isBaseLayer': true,displayInLayerSwitcher:true,attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>',  transitionEffect: 'resize',buffer: 1, scales:[6800000.0,3400000.0,1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0]}));			 
                
    map.addControls([
            new OpenLayers.Control.PanPanel({zoomWorldIcon:true}),
            new OpenLayers.Control.ZoomPanel(),
            new OpenLayers.Control.MouseDefaults()
        ]);
    map.zoomToMaxExtent();
 }
 
 function switchBaseLayers(index) {
    if(index == 0)//myndkort
    {
        map_div.style.backgroundColor = "#233E59";
        document.getElementById("photo").setAttribute('class', 'map_sel_selected');
        document.getElementById("lightsaber").setAttribute('class', 'map_sel_notselected');
        document.getElementById("mapLogoImg").setAttribute('src', 'http://www.map.is/inline/map_logo_dokk.png');
        document.getElementById("LM_linkA").style.color= '#E8E8E8';
        
    }
    else//lightsaber
    {
        map_div.style.backgroundColor = "#95ABC0";
        document.getElementById("lightsaber").setAttribute('class', 'map_sel_selected');
        document.getElementById("photo").setAttribute('class', 'map_sel_notselected');
        document.getElementById("mapLogoImg").setAttribute('src', 'http://www.map.is/inline/map_logo_ljos.png');
       document.getElementById("LM_linkA").style.color= "#3D4043";
    }
    map.setBaseLayer(map.layers[index]);
        
}