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

	// Confirm film rating via AJAX
  // This scrapes the Consumer Protection BC website to grab the first result
  // It's pretty ghetto, so we need to make sure it degrades gracefully
	$('#rating-confirm').click(function(e) {
		e.preventDefault(); // Prevent the link from doing it's thing
		$('#rating-spinner').css('visibility','visible');
		var dataRating = {
	    action: 'movie_confirm_film_rating',
      title: $('#title').val()
		};
  	$.post(ajaxurl, dataRating, function(response) {
      $('#rating-spinner').css('visibility','hidden');
      if(response) {
        // Find the second cell on the second row of the second table (I know, right?!)
        var found = $('.searchlicense:eq(2) tr:nth-child(2) td:nth-child(2)', $(response)).html();
        // Update the response message
        $('#rating-response span').html('Response received as: ' + found + '. ');
        // Add the classes and show the message
        // We add the classes with javascript because if we don't WordPress
        // will move it to the top of the page
        $('#rating-response').addClass('notice notice-success').show();
        // If `found` returns null we need to forward the user to the page
        if (!found) {
          $('#rating-response span').html('No response received. ');
          $('#rating-response').addClass('notice notice-error').show();
        }
  		} else {
        $('#rating-response span').html('No response received. ');
        $('#rating-response').addClass('notice notice-error').show();
  		}
  	});
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
