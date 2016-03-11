jQuery(document).ready(function($){
	// Use Divi's 'magnific' to popup the movie details
	$details = $('#poster-row .details_popup');
	if ($details.length) {
		$details.magnificPopup({
			type:'inline',
			midClick: true,
			gallery: {
				enabled: true,
			}
		});
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
		// Open the widget_popups on button press
		// $('#movie-show-widgets').click(function() {
		// 	$widget_popups.magnificPopup.open({
		// 	  items: {
		// 	    src: '#post-2320-details'
		// 	  },
		// 	  type: 'inline'
		// 	}, 0);
		// });
	}

});
