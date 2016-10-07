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

function initCarousel() {
	$('#lightSlider').lightSlider({
		loop:true,
		slideMargin: 0,
		pager: false,
		loop:false,
		item:5,
		prevHtml:'<img src="/images/prev.png"/>',
		nextHtml:'<img src="/images/next.png"/>',
		responsive: [
			{
				breakpoint: 1000,
				settings: {
					item: 4,
				}
			},
			{
				breakpoint: 800,
				settings: {
					item: 3,
				}
			},
			{
				breakpoint: 600,
				settings: {
					item: 2,
				}
			},
			{
				breakpoint: 400,
				settings: {
					item: 1,
				}
			}
		]
	});
}

$(document).ready(function () {
	initCarousel();
	initmap();
});

	$(".thumbnail").click(function () {
		var path = $(this).data('path')
		console.log(path);
		$(".img-plot").attr('src', path);
	});


