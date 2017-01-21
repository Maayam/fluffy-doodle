$(document).ready(function(){

var map;
var ajaxRequest;
var plotlist;
var plotlayers=[];
var searchWord = "";
var addPlot = false;
var marker = false;

var home = new L.LatLng(0, 0);

function initmap() { //loads the map
	// set up the map
	map = new L.Map('userMap');

	// create the tile layer with correct attribution
	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, {minZoom: 3, maxZoom: 18, attribution: osmAttrib});

	map.setView(home, 3);

	//Add button to locate the user
	L.easyButton('glyphicon-screenshot', function(){
		map.locate({setView:true, maxZoom:18});
	}, "Locate me").addTo(map);

	var states = [{
		stateName: 'add-plot',
		icon: 'glyphicon-map-marker',
		title: 'Add plot',
		onClick: function(control) {
			addPlot = true;
			control.state('remove-plot');
		}
	}, {
		stateName: 'remove-plot',
		icon: 'glyphicon-remove-sign',
		onClick: function(control) {
			if(marker) {
				map.removeLayer(marker);
			}
			addPlot = false;
			control.state('add-plot');
		}
	}
	];

	// Add button to add plots only if user is logged
	$.ajax({
			url: path['isLoggedinPath'],
			type:"GET",
		}).done(function(data) {
			if(data['isLoggedIn']) {
				addPlotButton = L.easyButton({
					states: states
				});
				addPlotButton.addTo(map);
			}
		});


	map.addLayer(osm);

	//loads the first plots on mapLoad
	askForPlots();
	map.on('moveend', onMapMove);
}


function askForPlots() {

	var box = getBox();

	if(searchWord != "") {
		box['search'] = searchWord;
		box["filter"] = $("#search-type").val();
	}

	$.ajax({
	  url: 'plot/search',
	  type: 'GET',
	  data: box,
		error: function(data, XMLHttpRequest, textStatus, errorThrown) {
			throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
		}
	}).done(function(plotList) { updatePlots(plotList); });
}

function getBox() {
	var bounds=map.getBounds();
	var minll=bounds.getSouthWest();
	var maxll=bounds.getNorthEast();


	return { 'minLng': minll.lng,
			 'minLat': minll.lat,
			 'maxLat': maxll.lat,
			 'maxLng': maxll.lng };
}

function updatePlots(plotList) {
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
		plotlayers.push(plotmark);

		var popupOptions = {
			maxWidth: "auto",
			autoPan: false
		};

		plotmark = plotmark.bindPopup(plot.html, popupOptions).update();
	}
}

function removeMarkers() { //remove Markers that are outside the view
	var bounds = getBox();
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}

function onMapMove(e) { askForPlots(); }

function searchPlotByName() {

	$("#search-form").submit(function(e) {
		var box = getBox();

		box["search"] = $("#search-term").val();
		box["filter"] = $("#search-type").val();

		$.ajax({
			url:"plot/search",
			type:"GET",
			data:box,
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
			}
		}).done(function(result) {
			if(result.length > 0) {
				updatePlots(result);
			} else {
				alert("No plot found.");
			}
		});

		e.preventDefault();
	});
}

function popPlotForm(e){
	//creates a new marker on click
	if(marker){
		//remove the previous marker first (if one)
		map.removeLayer(marker);
	}
	marker = new L.Marker(e.latlng);
	map.addLayer(marker); //insert marker

	//now, get the Form from server
	var coords = {
		'lat': e.latlng.lat,
		'lng': e.latlng.lng
	};
	$.ajax({
		'url': 'plot/form',
		'type': 'GET',
		'data': coords,
	}).done(function(data){
		//when got the form, append it in the marker popup
		var popupOptions = {
			maxWidth: "auto",
			autoPan: false //Disable panning, prevent the popup from closing when panning is done
		};
		marker.bindPopup(data, popupOptions).openPopup();
		console.log(marker);

		//when the form is submitted
		$("form[name='form']").submit(function(e) {
  		e.preventDefault(); //prevent the redirection
			var data = new FormData($(this)[0]);
			//close the form Popup and show a loading text instead
			marker.getPopup().setContent("Loading...").update();
  		//make the actual post request to the plotController
			$.ajax({
				url:"plot",
				type:"POST",
				data:data,
				contentType: false,
  				processData: false,
  				cache:false,
			}).done( function(data){
				//if successful, put the newly added content into a new Popup
				if(data.success){
					l("plot form successfully submited: (map.js in popPlotForm()");
					//display a nice notification instead of this...

					marker.getPopup()
					.setContent(data.html)
					.update()

					//vider la variable marker pour que le marker nouvellement créé ne se fasse pas écraser au prochain clique
					marker = false;
				}
				else{
					marker.getPopup()
					.setContent("Sorry... Form Submission Failed").update();
				}
			}); //end post Request
		}); //end form Submit
	}); //end getForm
} //end popPlotForm()

initmap();

map.on('click', function(e) {
	if(addPlot){
		l('popped');
		popPlotForm(e);
	}
	else{
		l('not popped');
	}
});

function markAsDanced(){
	l('lel');
}

});
