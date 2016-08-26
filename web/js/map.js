var map;
var ajaxRequest;
var plotlist;
var plotlayers=[];

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
}

function getXmlHttpObject() { 
	if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
	if (window.ActiveXObject)  { return new ActiveXObject("Microsoft.XMLHTTP"); }
	return null;
}

//more at https://switch2osm.org/using-tiles/getting-started-with-leaflet/

initmap();