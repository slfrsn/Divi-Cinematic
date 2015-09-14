jQuery(document).ready(function($){
	// Turn the status light into a spinner
	$('#api_spinner').addClass('status-light').css('visibility','visible');
	// Check the status of our movie API
	$.post(ajaxurl, {'action': 'movie_api_status'}, function(response) {
		if(response && response === 'online') {
		  $('.status-light').css('background-color','#8BC34A');
		  $('#api_status').text('API is online');
		} else {
		  $('.status-light').css('background-color','#F44336');
		  $('#api_status').text('API is offline');
		}
	});

	// Load the movie details via AJAX call
	$('#load_movie').click(function(e) {
		e.preventDefault(); // Prevent the link from doing it's thing
		// Provide visual confirmation that the AJAX call is in progress
		$('#api_status').html('Fetching...');
		$('#api_spinner').removeClass('status-light').css({ 'visibility':'visible','background-color':'' });
		$('input[name=fetched]').val(1)
		// Function to call, post ID, and post title
		var dataMovie = {
	    action: 'movie_fetch',
	    id: $('#post_id').val(),
      title: $('#title').val()
		};
		$.post(ajaxurl, dataMovie, function(response) {
			// Make sure the response is valid JSON
			if(response && response.indexOf('<') == -1){
				var json   = $.parseJSON(response);
				var genres = json.movie.genres;
				var title  = json.movie.title;
				// Save the response (the string of genres) to a hidden input.
				// If we don't do this they get overwritten when we save because
				// the checkboxes are all unchecked.
				$('input[name="genres"]').val(genres.join(','));
				// Update the title to the official movie format / spelling
				$('#title').val(title);
				// Save and reload the page.
				$('#save-post').click();
			} else {
				$('#api_spinner').addClass('status-light').css('visibility','visible');
	  		$('.status-light').css('background-color','#F44336'); // Set it to red in case it fails
			  $('#api_status').text('No results');
			}
		});
	});

	// Format the JSON response data if there is any
	var response = $('#movie_response .inside').html();
	if ($.trim(response) !== 'No response data.') {
		$('#movie_response .inside').JSONView(response, { collapsed: true });
	}

	// Initialize the lazy YouTube embed
	$('#trailer-frame').lazyYT();

	// Create and destroy the website iFrame when the postbox is open / closed
  $('#website-postbox .postbox').click(function(e) {
		if ($(this).hasClass('closed')) {
			$('#website-frame').attr('src', null);
		} else {
			var src = $('input[name=website]').val();
			$('#website-frame').attr('src', src);
		}
  });
});
