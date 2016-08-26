var l = (data) => {
	console.log(data);
}

var map;
var ajaxRequest;
var plotlist;
var plotlayers=[];

var ajaxRequest = getXmlHttpObject();

var home = new L.LatLng(47.200549, -1.544480);

function initmap() { //loads the map
	// set up the map
	map = new L.Map('userMap');

	// create the tile layer with correct attribution
	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, {minZoom: 4, maxZoom: 19, attribution: osmAttrib});		

	// start the map in South-East England
	map.setView(home ,12);
	map.addLayer(osm);

	//loads the first plots on mapLoad
	askForPlots();
	map.on('moveend', onMapMove);
}

function getXmlHttpObject() {
	//ajax request function
	if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
	if (window.ActiveXObject)  { return new ActiveXObject("Microsoft.XMLHTTP"); }
	return null;
}

function removeMarkers() {
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}

function askForPlots() {
	// request the marker info with AJAX for the current bounds
	var bounds=map.getBounds();
	var minll=bounds.getSouthWest();
	var maxll=bounds.getNorthEast();
	var url='findInView?minLng='+minll.lng+'&minLat='+minll.lat+'&maxLat='+maxll.lat+'&maxLng='+maxll.lng;

	$.ajax({
	  url: url,
	}).done(function(plotList) { updateDots(plotList); });
}

function updateDots(plotList) {
	removeMarkers();

	for( i=0; i < plotList.length; i++ ) {
		//coords are supposed to be decimals!
		var plot = plotList[i];
		plot.lat = parseFloat(plot.lat);
		plot.lng = parseFloat(plot.lng);

		var plotll = new L.LatLng(plot.lat, plot.lng, true);
		var plotmark = new L.Marker(plotll);

		plotmark.data=plot;
		map.addLayer(plotmark);

		plotmark.bindPopup("<h3>"+plot.name+"</h3>"+plot.note);
		plotlayers.push(plotmark);
	}
}

function removeMarkers() {
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}

//more at https://switch2osm.org/using-tiles/getting-started-with-leaflet/

initmap();

function onMapMove(e) { askForPlots(); }

