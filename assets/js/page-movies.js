jQuery(document).ready(function($){

	// Open a movie popup if requested via GET query
	function requested_movie(instance, container) {
		var request = window.location.href.split('#').pop().split('=').pop();
		if(request && request.length) {
			request = $('#' + request);
			var movie_index = $('.white_popup').index(request);
			if ($(container).find(request).length > 0) {
				instance.magnificPopup('open', movie_index);
			}
		}
	}

	// Use Divi's 'magnific' to popup the movie details
	$details = $('#poster-row .details_popup');
	if ($details.length) {
		$details.magnificPopup({
			type:'inline',
			midClick: true,
			callbacks: {
				change: function() {
					window.history.replaceState('', "Title", "?movie=" + this.content.attr('id'));
			  },
				close: function() {
					window.history.replaceState('', "Title", page_url);				    
				},
			},
			gallery: {
				enabled: true,
			}
		});
		requested_movie($details, '#poster-row');
	}

	// Load the popups gallery if it exists
	$popups = $('#movie-popups .details_popup');
	if ($popups.length) {
		$popups.magnificPopup({
			type:'inline',
			midClick: true,
			gallery: {
				enabled: true,
			}
		});
		// Open the popups on page load (only on the home page)
		if ($('body').hasClass('home')) {
			$popups.magnificPopup('open');
		}
		// Open the popups on button press
		$('#movie-show-popups button').click(function() {
			$popups.magnificPopup('open');
		});
		requested_movie($popups, '#movie-popups');
	}

	// Load the widget popups gallery if it exists
	$widget_popups = $('#movie-widgets .details_popup');
	if ($widget_popups.length) {
		$widget_popups.magnificPopup({
			type:'inline',
			midClick: true,
			gallery: {
				enabled: true,
			}
		});
		requested_movie($widget_popups, '#movie-widgets');
	}

});
