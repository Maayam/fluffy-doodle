var offset = 10;

$(document).ready(function () {

	$(window).data('ready', true);
	var id = $("#tag-name").data("id");
	
	$(window).scroll(function() {

		if(!$(window).data('ready')) {
			return;
		}

		if($(window).scrollTop() + $(window).height() == $(document).height()) {
			$(window).data('ready', false);

			$('.container #loader').fadeIn(400);

			$.ajax({
				url: "/view/tag/"+id,
				type: "GET",
				data: {'offset': offset}
			}).done(function(data) {
				$('.container #loader').before(data['html']);

				if(!data['end'])
					$(window).data('ready', true);

				offset+=10;
			});
					
			$('.container #loader').fadeOut(400);
		}
	});
});
