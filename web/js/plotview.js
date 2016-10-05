var map;

function initmap() {

	var options = {
		scrollWheelZoom:true,
		dragging:false,
	};
	
	map = new L.Map('map-plot', options);

	var lat = $('#plot-header').data('lat');
	var lng = $('#plot-header').data('lng');
		
	var home = new L.LatLng(lat, lng);

	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, {minZoom: 4, maxZoom: 18, attribution: osmAttrib});		
	var optionsMarker = {
		clickable: false,
	};

	map.setView(home, 12);
	map.addLayer(osm);
	
	L.marker(home, optionsMarker).addTo(map);
}

initmap();

