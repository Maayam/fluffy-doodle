

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
var searchWord = "";

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

	var box = getBox();
	
	if(searchWord != "") {
		box['search'] = searchWord;
	}
	
	$.ajax({
	  url: 'plot/search',
	  type: 'GET',
	  data: box,
		error: function(data, XMLHttpRequest, textStatus, errorThrown) {
			throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
		}
	}).done(function(plotList) { updateDots(plotList); });
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

		var popupOptions = {
			maxWidth: "auto",
			autoPan: false
		};

		plotmark = plotmark.bindPopup(plot.html, popupOptions).update();
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
		var box = getBox();
		
		box["search"] = $("#search-term").val();
	
		searchWord = box["search"];
		
		$.ajax({
			url:"plot/search",
			type:"GET",
			data:box,
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
			var popupOptions = {
				maxWidth: "auto",
				autoPan: false //Disable panning, prevent the popup from closing
							   //when panning is done
			};
			marker.bindPopup(data, popupOptions).openPopup();

			var newName, newNote; //I need those variables global

			//when the form is submitted
			$("form[name='form']").submit(function(e) {
    			e.preventDefault(); //prevent the redirection
				
				var data = new FormData($(this)[0]);

				//close the form Popup and show a loading text instead
				marker.getPopup().setContent("Loading...").update();

    			//make the actual post request to the plotController
				$.ajax({
					url:"/plot",
					type:"POST",
					data:data,
					contentType: false,
    				processData: false,
    				cache:false,
				}).done( function(data){
					//if successful, put the newly added content into a new Popup
					if(data.success){
						l("plot form successfully submited: (map.js in popFormOnClick()");
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
	}); //end onMapClick
} //end popFormOnClick()
initmap();
popFormOnClick();
searchPlotByName();
