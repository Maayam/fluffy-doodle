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
		var html = "<ul id='tag-search-result' class='list-unstyled'>";
		result.forEach(function(v){
			html += "<li id='"+v.id+"'>"+v.name+"</li>"
		})
		html += "</ul>";
		$("#tag-search-term").after(html);
	});
}

var timeout = false;
$("#tag-search-term").on("input", function(e){
	l("tag search changed!");
	var term = $("#tag-search-term").val();
	$("#tag-search-result").remove();

	//don't spam with requests!! send a request only if nothing was typed for a while
	if(timeout){
		clearTimeout(timeout);
	}
	if(term != "" && term.length >= 0){
		timeout = setTimeout(function(){
			searchTagsByName(term);
		}, 300);
	}
});