

var l = (data) => { //cuz i'm lazy
	console.log(data);
}
var throwAjaxError = (MLHttpRequest, textStatus, errorThrown) => {
	alert("AJAX request Failed...\n Text Status: "+textStatus+"\n Error Thrown: "+errorThrown);
}

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
	var osm = new L.TileLayer(osmUrl, {minZoom: 4, maxZoom: 18, attribution: osmAttrib});		

	// start the map in South-East England
	map.setView(home ,12);
	map.addLayer(osm);

	//loads the first plots on mapLoad
	askForPlots();
	map.on('moveend', onMapMove);
}


function askForPlots() {
	// request the marker info with AJAX for the current bounds
	var bounds=map.getBounds();
	var minll=bounds.getSouthWest();
	var maxll=bounds.getNorthEast();

	$.ajax({
	  url: 'plot/search',
	  type: 'GET',
	  data: {
	  	'filter': 'plotsInBox',
		'minLng': minll.lng,
		'minLat': minll.lat,
		'maxLat': maxll.lat,
		'maxLng': maxll.lng
	  },
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
		}
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

function onMapMove(e) { askForPlots(); }

function searchPlotByName() {
	$("#search-form").submit(function(e) {
		$.ajax({
			url:"plot/search",
			type:"GET",
			data:{
				'filter': 'findByName',
				"search" : $("#search-term").val()
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
			}
		}).done(function(result) {
			if(result.length > 0) {
				updateDots(result);
			} else {
				alert("No plot found.");
			}
		});

		e.preventDefault();
	});
}

function popFormOnClick(){
	//this could probably be improved... probably...
	var marker = false;
	map.on('click', function(e) {
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
			'url': '/plot/form',
			'type': 'GET', 
			'data': coords,
		}).done(function(data){
			//when got the form, append it in the marker popup
			marker.bindPopup("<div id='plot-form-popup'></div>").openPopup();
			$(".leaflet-popup-content-wrapper").append(data);

			var newName, newNote; //I need those variables global

			//when the form is submitted
			$("form[name='plot']").submit(function(e) {
    			e.preventDefault(); //prevent the redirection

    			//build the data to POST to the server
    			newName = $('#plot_Name').val();
				newNote = $('#plot_Note').val();
    			var data = { //pass this data
    				'plot': {
						'Lat': $('#plot_Lat').val(), //values of the different fields
						'Lng': $('#plot_Lng').val(), //this can be improved I think
						'Name': newName,
						'Note': newNote,
						'_token': $('#plot__token').val()
						}
				};

				//close the form Popup and show a loading text instead
				marker.unbindPopup()
				.bindPopup("Loading...")
				.openPopup();

    			//make the actual post request to the plotController
				$.post( "/plot", data)
				.done( function(data){
					//if successful, put the newly added content into a new Popup
					if(data.success){
						l("plot form successfully submited: (map.js in popFormOnClick()");
						//display a nice notification instead of this...
						marker.unbindPopup()
						.bindPopup("<h3>"+newName+"</h3>"+newNote)
						.openPopup();

						//vider la variable marker pour que le marker nouvellement créé ne se fasse pas écraser au prochain clique
						marker = false;
					}
					else{
						marker.unbindPopup()
						.bindPopup("Sorry... Form Submission Failed")
						.openPopup();
					}
				}); //end post Request
			}); //end form Submit
		}); //end getForm
	}); //end onMapClick
} //end popFormOnClick()
initmap();
popFormOnClick();
searchPlotByName();
