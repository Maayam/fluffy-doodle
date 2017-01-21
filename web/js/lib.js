var l = (data) => { //cuz i'm lazy
	console.log(data);
}

var throwAjaxError = (MLHttpRequest, textStatus, errorThrown) => {
	alert("AJAX request Failed...\n Text Status: "+textStatus+"\n Error Thrown: "+errorThrown);
}

//this probably shouldn't be defined globally but I can't find any better solution atm 
function setDanced(plotId, user){ //isLoggedin is either '' or '1'
	// l(user);
	// l('danced this plot yeah');
	// l(plotId);
	$('#plot-'+plotId).attr("disabled", true);

	var postData = {
		plotId: plotId,
		username: user
	}

	$.ajax({
		url: path['markAsDanced'],
		type: 'GET',
		data: postData
	}).done(function(data){
		l(data);
		$('#plot-'+plotId).attr("disabled", false);
		swal({
		  title: "Add Performance",
		  text: data,
		  html: true,
		  showCancelButton: true,
		  closeOnConfirm: false,
		  showLoaderOnConfirm: true,
		  animation: "slide-from-top"
		}, function(){
				var data = new FormData($("form[name='form']")[0]);
				//make the actual post request to the plotController
				data.set('plotId', plotId);
				l("form submission");
				l(data);
				$.ajax({
					url:"performance",
					type:"POST",
					data: data,
					contentType: false,
	  			processData: false,
	  			cache:false,
				}).done( function(data){
					//if successful, put the newly added content into a new Popup
					l("request success, returned");
					l(data);
					if(data){
						swal({
							title: "success !",
							type: "success",
							text: "Performance was added successfully !"
						});
					}
					else{
						swal({
							title: "error !",
							type: "error",
							text: "There was an error during form submission. Please try again."
						});
					}
			}); //end post Request
		}); //end swal form Submit
	}); //end popForm
}