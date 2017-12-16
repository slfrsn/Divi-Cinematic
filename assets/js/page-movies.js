jQuery(document).ready(function($){

	var movie_url = '';

	function initialize_movies(elements, container) {
		elements.magnificPopup({
			type:'inline',
			midClick: true,
			callbacks: {
				change: function() {
					movie_url = page_url + '?movie=' + this.content.attr('id');
					window.history.replaceState('', "Title", movie_url);
					console.log(movie_url);
			  },
				close: function() {
					window.history.replaceState('', "Title", page_url);
				},
			},
			gallery: {
				enabled: true,
			}
		});
		requested_movie(elements, container);
	}

	// Open a movie popup if requested via GET query
	function requested_movie(instance, container) {
		var request = window.location.href.split('?movie=').pop();
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
		initialize_movies($details, '#poster-row');
	}

	// Load the popups gallery if it exists
	$popups = $('#movie-popups .details_popup');
	if ($popups.length) {
		initialize_movies($popups, '#movie_popups');
		// Open the popups on page load (only on the home page)
		if ($('body').hasClass('home')) {
			$popups.magnificPopup('open');
		}
		// Open the popups on button press
		$('#movie-show-popups button').click(function() {
			$popups.magnificPopup('open');
		});
	}

	// Load the widget popups gallery if it exists
	$widget_popups = $('#movie-widgets .details_popup');
	if ($widget_popups.length) {
		initialize_movies($widget_popups, '#movie-widgets');
	}

});
