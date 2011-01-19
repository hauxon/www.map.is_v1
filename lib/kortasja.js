// Höfum map breytuna glóbal svo við getum referencað hana utan init fallsins
var map = null;

function initmap()
{
	var arr_scales_full = new Array(6800000.0,3400000.0,1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0,1000.0,500.0,250.0);
	var arr_scales_myndkort = new Array(1700000.0,1000000.0,500000.0,250000.0,100000.0,50000.0,25000.0,10000.0,5000.0,2000.0,1000.0,500.0,250.0);
	var panBounds = new OpenLayers.Bounds(143000,255000,866000,735000);//234248.88,297273.25,759064.98,686298.38);

	map = new OpenLayers.Map('map', {
						maxExtent: new OpenLayers.Bounds(143000,255000,866000,735000),
						restrictedExtent: panBounds,
						units:'m',
						maxResolution: '180/256',
						scales:arr_scales_full,
						projection:"EPSG:3057"
						});	


	var myndkort = new OpenLayers.Layer.TMS("Myndkort",
			["http://tc0.loftmyndir.is/tc_r/tilecache.py"],	
			{	layername:'myndkort',
				type:'jpeg', 
				kortasja: 'www.map.is',
				serviceVersion:'',
				isBaseLayer: true,
				displayInLayerSwitcher:true, 
				attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>',  
				transitionEffect:'resize', 
				scales: arr_scales_myndkort,
				buffer:1});

	var skyggtkort = new OpenLayers.Layer.TMS("Skyggt kort",
			["http://tc0.loftmyndir.is/tc/tilecache.py"],		 
			 { 	layername: 'lightsaber', 
				type: 'jpeg', 
				serviceVersion:'' , 
				isBaseLayer: true,
				displayInLayerSwitcher:true,
				attribution: ' © Loftmyndir ehf.<small> Allur réttur áskilinn.</small>',  
				transitionEffect: 'resize',
				buffer: 1});	
			 
	map.addLayers([skyggtkort,myndkort]);
	map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.zoomToMaxExtent();
	
	onAppResize();
}