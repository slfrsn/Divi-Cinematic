jQuery(document).ready(function($){

	function sleep(time) {
		return new Promise((resolve) => setTimeout(resolve, time));
	}

	function set_status_light(state, colour, text, description, is_link) {
		if (state === 0) {
			$('#api_spinner').removeClass('status-light').css({ 'visibility':'visible','background-color':'' });
		} else {
			$('#api_spinner').addClass('status-light').css('visibility','visible');
			$('.status-light').css('background-color',colour);
		}
		if (is_link) {
			$('#api_status').replaceWith('<a id="api_status" href="https://www.themoviedb.org/search?query=' + encodeURIComponent($('#movie_search').val()) + '" target="_blank" title="Search TMDB\'s website">' + text +'</a>');
		} else {
			$('#api_status').replaceWith('<span id="api_status" title="' + description + '">' + text +'</span>');
		}
	}

	function toggle_fetch_button(state) {
		if (state === 0) {
			$('#load_movie').text('Continue').addClass('continue-fetch');
			$('#publish').addClass('button-disabled');
		} else {
			$('#load_movie').text('Fetch Movie Details').removeClass('continue-fetch');
			$('#publish').removeClass('button-disabled');
		}
	}

	function validate_response(response) {
	  try {
	    var json = JSON.parse(response);
	    if (json && typeof json === "object") {
				if (json.status_code) {
					alert(json.status_message);
				}
				if (json.length < 1) {
					return false;
				}
				return json;
	    }
	  }
	  catch (e) { }
	  return false;
	};

	$('#movie_search').focus();
	$('#movie_search').keypress(function (e) {
	  if (e.which == 13) {
	    $('#load_movie').click();
	    return false;
	  }
	});

	function submit_movie(tmdb) {
		set_status_light(0, '', 'Fetching...', '', false);
		var dataMovie = {
	    action: 'movie_fetch',
	    id: $('#post_id').val(),
      tmdb: tmdb
		};
		$.post(ajaxurl, dataMovie, function(response) {
			var json = validate_response(response);
			if (json) {
				var genres = json.movie.genres ? json.movie.genres : [''];
				var title  = json.movie.title ? json.movie.title : '';
				// Save the response (the string of genres) to a hidden input.
				// If we don't do this they get overwritten when we save because
				// the checkboxes are all unchecked.
				$('input[name="genres"]').val(genres.join(','));
				// Update the title to the official movie format / spelling
				$('#title').val(title);
				// Save and reload the page.
				$('#save-post').click();
			} else {
				set_status_light(1, '#F44336', 'No results', '', true);
			}
		});
	}

	// Turn the status light into a spinner
	$('#api_spinner').addClass('status-light').css('visibility','visible');
	// Check the status of our movie API
	$.post(ajaxurl, {'action': 'movie_api_status'}, function(response) {
		if(response && response === 'online') {
		  set_status_light(1, '#8BC34A', 'API is online', '', false);
		} else {
		  set_status_light(1, '#F44336', 'API is offline', '', false);
		}
	});

	// Load the movie details via AJAX call
	$('#load_movie').click(function(e) {
		e.preventDefault(); // Prevent the link from doing it's thing
		if ($(this).hasClass('continue-fetch')) {
			submit_movie($("input:radio[name='suggestions']:checked").val());
		} else {
			$('#movie-suggestions').remove();
		  set_status_light(0, '', 'Searching...', '', false);
			$('input[name=fetched]').val(1)
			// Function to call, post ID, and post title
			var dataMovie = {
		    action: 'movie_suggestions',
		    id: $('#post_id').val(),
	      title: $('#movie_search').val()
			};
			$.post(ajaxurl, dataMovie, function(response) {
				var json = validate_response(response);
				if (json && json.length > 1) {
					var suggestions = '<div class="categorydiv" id="movie-suggestions"><p>Multiple matches found. Please make a selection and press Continue.</p><div class="tabs-panel">';
					$.each(json, function(i, item) {
					  suggestions += '<label><input value="' + json[i].id + '" type="radio" name="suggestions" ' + (i == 0 ? 'checked="checked"' : '') + '><strong>' + json[i].year + ':</strong> ' + json[i].title + '</label>';
					})
					suggestions += '</div></div>';
					$('.fetch-details').append(suggestions);
					set_status_light(1, '#FFC107', json.length + ' matches', '', false);
					toggle_fetch_button(0);
				} else if (json.status_code) {
					set_status_light(1, '#F44336', 'Error: ' + json.status_code, json.status_message, false);
				} else if (!json) {
					set_status_light(1, '#F44336', 'No results', '', true);
				} else {
					submit_movie(json[0].id);
				}
			});
		}
	});

	// Prettify the JSON response data if there is any
	var response = $('#movie_response .inside').html();
	if ($.trim(response) !== 'No response data.') {
		$('#movie_response .inside').JSONView(response, { collapsed: true });
	}

	// Create and destroy the YouTube trailer when the postbox is open / closed
  $('#trailer-postbox .hndle').click(function(e) {
		if ($('#trailer-postbox .postbox').hasClass('closed')) {
			var src = $('input[name=trailer]').val();
			var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
			var match = src.match(regExp);
			var id = (match&&match[7].length==11)? match[7] : false;
			$('#trailer-inside').append('<div class="js-lazyYT" id="trailer-frame" data-youtube-id="' + id + '" data-ratio="16:9" data-parameters="rel=0&autohide=2&iv_load_policy=3&modestbranding=1&color=white"></div>');
			$('#trailer-frame').lazyYT();
		} else {
			$('#trailer-inside').empty();
		}
  });

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
