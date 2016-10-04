var map;

function initmap() {
	map = new L.Map('map-plot');

	var lat = $('#plot-header').data('lat');
	var lng = $('#plot-header').data('lng');
		
	var home = new L.LatLng(lat, lng);

	// create the tile layer with correct attribution
	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, {minZoom: 4, maxZoom: 18, attribution: osmAttrib});		

	// start the map in South-East England
	map.setView(home ,12);
	map.addLayer(osm);
	
	L.marker(home).addTo(map);
}

initmap();

