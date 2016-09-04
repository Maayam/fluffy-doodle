function searchTagsByName(text){
	l("made 'tag/search' AJAX request");
	$.ajax({
		url:"tag/search",
		type:"GET",
		data:{
			'filter': 'findByName',
			"search" : $("#tag-search-term").val()
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			throwAjaxError(XMLHttpRequest, textStatus, errorThrown);
		}
	}).done(function(result) {
		l("tag search ajax request successful ! responded the following");
		l(data);
	});
}

var timeout = false;
$("#tag-search-term").on("input", function(e){
	l("tag search changed!");
	var term = $("#tag-search-term").val();

	//don't spam with requests!! send a request only if nothing was typed for a while
	if(timeout){
		clearTimeout(timeout);
	}
	if(term != ""){
		timeout = setTimeout(function(){
			searchTagsByName(term);
		}, 700);
	}
});