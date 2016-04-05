<?php

// Compress HTML output
ob_start("sanitize_output");
function sanitize_output($buffer) {
    $search = array(
      '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
      '/[^\S ]+\</s',  // strip whitespaces before tags, except space
      '/(\s)+/s'       // shorten multiple whitespace sequences
    );
    $replace = array('>','<','\\1');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

// Convenience method to get the domain from a URL
// This doesn't work with subdomains or complex addresses
function get_host($url) {
	$parsed = parse_url($url);
	// Rip-off off the prefix and TLD and return their delicious centre
	$tokens = explode('.', $parsed['host']);
	return $tokens[sizeof($tokens)-2];
}

// Return first result of a Google search for possible website, trailer, etc.
function suggest_url($query) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.google.ca/search?q='.$query.'&btnI');
	curl_setopt($ch, CURLOPT_HEADER, true);
	// FOLLOWLOCATION is necessary to return the true URL and not the Google redirect
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$a = curl_exec($ch);
	// Return the final URL
	return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
}

// Send the trakt.tv API calls in parallel
// http://docs.trakt.apiary.io/
function multiRequest($urls) {
  $curls  = []; // Array of curl handles
  $result = []; // Data to be returned
  $multi  = curl_multi_init(); // Multi-handle
  // Loop through $urls and create cURL handles, then add them to the multi-handle
  foreach ($urls as $id => $url) {
    $curls[$id] = curl_init();
    curl_setopt($curls[$id], CURLOPT_URL, $url);
    curl_setopt($curls[$id], CURLOPT_HEADER, FALSE);
    curl_setopt($curls[$id], CURLOPT_RETURNTRANSFER, TRUE);
		// We have to send the following header information or the request is rejected
		curl_setopt($curls[$id], CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"trakt-api-version: 2",
			"trakt-api-key: a51efbf689db9b21310f12961a2a5f95babd01ed60a108fd3c70910627b2f41a"
		));
    curl_multi_add_handle($multi, $curls[$id]);
  }
  // Execute the handles
  $running = null;
  do {
    curl_multi_exec($multi, $running);
  } while($running > 0);
  // Get the content and remove the handles
  foreach($curls as $id => $curl) {
    $result[$id] = curl_multi_getcontent($curl);
		$result[$id] = json_decode($result[$id], true);
		$result[$id]['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$result[$id] = json_encode($result[$id]);
    curl_multi_remove_handle($multi, $curl);
  }
	// Close the handles and return the response array
  curl_multi_close($multi);
  return $result;
}

$search = null; // Initialize $search for simpler conditionals

if (!empty($_GET['s'])) { // Begin $search conditional
	$search = strtolower($_GET['s']); // Lowercase to make comparisons simpler
	$search = urlencode($search);

  // Filter Google results when searching for website suggestions
  if (!empty($_GET['google'])) {
  	$forbidden = [ // Websites that should be considered false positives
  		'google',
  		'youtube',
  		'vimeo',
  		'imdb',
  		'dailymotion',
  		'wikipedia',
  		'facebook'
  	];
  	$google = suggest_url(urlencode($_GET['google']));
  	// Only echo the website if it looks valid
  	if (!in_array(get_host($google),$forbidden)) {
  		echo $google;
  	}
  }

  // Only return suggestions from YouTube
  if (!empty($_GET['youtube'])) {
  	$youtube = suggest_url(urlencode($_GET['youtube']));
  	// Only echo the website if it's from YouTube
  	if (get_host($youtube) == 'youtube') {
  		echo $youtube;
  	}
  }

  // Only return suggestions from IMDB
  if (!empty($_GET['imdb'])) {
  	$imdb = suggest_url(urlencode($_GET['imdb'].'+imdb'));
  	// Only echo the website if it's from IMDB
  	if (get_host($imdb) == 'imdb') {
  		// Explode the url path into segments
  		$tokens = explode('/', $imdb);
  		// Return the last path segment (in this case, the IMDB ID)
  		echo $tokens[sizeof($tokens)-2];
  	}
  }

	$imdb = null;
	// Check if the IMDB ID exists in $_GET
  // If it is we can save ourselves a web request
	if (!empty($_GET['id'])) {
    $imdb = $_GET['id'];
	// If not, check if the search query is an IMDB ID.
  } else if (substr($search, 0, 2) == "tt" && strlen($search) == 9) {
		$imdb = $search;
  // Otherwise, leverage Google to get the IMDB ID
	} else {
		// Explode the url path into segments
		$tokens = explode('/', suggest_url($search.'+imdb'));
		// Return the last path segment (in this case, the IMDB ID)
		$imdb = $tokens[sizeof($tokens)-2];
	}

	// Check for POST data and make the API calls
	if (!empty($search)) {
		// Trakt's API requires a separate request to get the cast and crew of a movie
		// We're using a cURL multi-handle to fetch them in parallel
		$data = array(
		  'http://api-v2launch.trakt.tv/movies/'.$imdb.'?extended=full,images',
		  'http://api-v2launch.trakt.tv/movies/'.$imdb.'/people'
		);
		$request = multiRequest($data);
		$movie   = json_decode($request[0],true);
		$people  = json_decode($request[1],true);
	}

	// Prepare "Search on Google / YouTube" query strings
	$param['poster']  = $search.'+official+poster';
	$param['website'] = $search.'+official+movie+website';
	$param['trailer'] = $search.'+official+movie+trailer';

	// Prepare content variables
	$title    = (!empty($movie['title']) ? $movie['title'] : null);
	$year     = (!empty($movie['year']) ? $movie['year'] : null);
	$duration = (!empty($movie['runtime']) ? $movie['runtime'] : null);
	$rating   = (!empty($movie['certification']) ? $movie['certification'] : null);
	$genres   = (!empty($movie['genres']) ? $movie['genres'] : null);
	$cast     = null;
              if (!empty($people['cast'])) {
                $i = 0;
                foreach ($people['cast'] as $person) {
                  $i++;
                  $cast .= $person['person']['name'];
                  if($i == 8) break; // Stop after we've reached 8 cast members
                  if(count($people['cast']) == $i) break; // Stop if our cast list has ended early
                  // We break before this to prevent the last actor from having a trailing comma
                  $cast .= ', ';
                }
              }
	$synopsis = (!empty($movie['overview']) ? $movie['overview'] : null);
	$poster   = (!empty($movie['images']['poster']['medium']) ? $movie['images']['poster']['medium'] : null);
	$trailer  = null; // The actual URL to the YouTube video page
  $youtube  = null; // The YouTube ID
              if (!empty($movie['trailer'])) {
                // Use Regex to grab the YouTube ID
                $regex = '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
                $youtube = preg_replace($regex,'$2',$movie['trailer']);
                $trailer = $movie['trailer'];
              }
	$website  = (!empty($movie['homepage']) ? $movie['homepage'] : null); // suggest_url($param['website']));

} // End $search conditional

if (empty($search) && isset($_GET['status'])) {
  // It's helpful to know if our API is working
  // If the following API call returns a valid title, we'll assume it's working
  $test            = [];
	$test['url']     = array('http://api-v2launch.trakt.tv/movies/tt0848228');
	$test['request'] = multiRequest($test['url']);
	$test['decoded'] = json_decode($test['request'][0],true);
  $test['status']  = isset($test['decoded']['title']) ? 'online' : 'offline';
   // Return the status of the API to our movie edit page
  echo '{ "status" : "'.$test['status'].'" }';
} elseif (!empty($search)) {
  // If the website or trailer are null, we need to fetch them for JSON response
  // Normally this is done with AJAX after page load, but we'll have to sacrifice speed for output here
	$trailer  = (!empty($trailer) ? $trailer : suggest_url($param['trailer']));
	$website  = (!empty($homepage) ? $homepage : suggest_url($param['website']));
  // Build the JSON string
	$json_output = array(
    'movie'      => array(
      'title' 	 => $title,
      'year' 		 => $year,
      'duration' => $duration,
      'rating' 	 => $rating,
      'genres' 	 => $genres,
      'cast' 		 => $cast,
      'synopsis' => $synopsis,
      'poster' 	 => $poster,
      'trailer'  => $trailer,
      'website'  => $website,
      'id'       => array(
        'imdb'   => $imdb,
        'youtube'=> $youtube
      )
	  )
	);
	echo json_encode($json_output);
}

?>
