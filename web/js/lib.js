var l = (data) => { //cuz i'm lazy
	console.log(data);
}

var throwAjaxError = (MLHttpRequest, textStatus, errorThrown) => {
	alert("AJAX request Failed...\n Text Status: "+textStatus+"\n Error Thrown: "+errorThrown);
}

//this probably shouldn't be defined globally but I can't find any better solution atm 
function setDanced(plotId, user){ //isLoggedin is either '' or '1'
	l(user);
	l('danced this plot yeah');
	l(plotId);
}