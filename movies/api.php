<?php

$dev_mode = false;

if ($dev_mode) {
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);
}

function trakt($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "trakt-api-version: 2",
    "trakt-api-key: a51efbf689db9b21310f12961a2a5f95babd01ed60a108fd3c70910627b2f41a"
  ));
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}

function sort_by_year($a, $b) {
	if($a['movie']['year'] == $b['movie']['year']){ return 0 ; }
	return ($a['movie']['year'] < $b['movie']['year']) ? 1 : -1;
}

$status = (isset($_GET['status']) ? true : false);
$suggest = (!empty($_GET['suggest']) ? urlencode($_GET['suggest']) : null);
$imdb = (!empty($_GET['imdb']) ? urlencode($_GET['imdb']) : null);

if ($status) {
  // It's helpful to know if our API is working
  // If the following API call returns a valid title, we'll assume it's working
  $test            = [];
	$test['request'] = trakt('http://api.trakt.tv/movies/tt0848228');
	$test['decoded'] = json_decode($test['request'],true);
  $test['status']  = isset($test['decoded']['title']) ? 'online' : 'offline';
   // Return the status of the API to our movie edit page
  echo json_encode(array('status' => $test['status']));
}

if ($suggest) {
  $request = trakt('https://api.trakt.tv/search/movie?query='.$suggest.'&type=movie&fields=title');
  $movies = json_decode($request, true);

  usort($movies, 'sort_by_year');

  if (count($movies) > 0) {
    $suggestions = [];
    foreach ($movies as $movie) {
    	$title = (!empty($movie['movie']['title']) ? $movie['movie']['title'] : null);
    	$year  = (!empty($movie['movie']['year']) ? $movie['movie']['year'] : 'NA');
      $id    = (!empty($movie['movie']['ids']['imdb']) ? $movie['movie']['ids']['imdb'] : null);
      $suggestions[] = array(
        'title' => $title,
        'year'  => $year,
        'imdb'  => $id
      );
    }
    echo json_encode($suggestions);
  } else {
    echo json_encode(array());
  }
}

if ($imdb) {
	$requests['movie'] = trakt('https://api.trakt.tv/movies/'.$imdb.'/?extended=full,images');
	$movie = json_decode($requests['movie'], true);

	$requests['trakt'] = trakt('https://api.trakt.tv/movies/'.$imdb.'/people');
	$people = json_decode($requests['trakt'],true);

  if ($dev_mode) {
    echo '<strong>Movie Summary Request</strong><br>'.json_encode($movie).'<br><br>';
    echo '<strong>Movie Cast Request</strong><br>'.json_encode($people).'<br><br>';
  }

  if ((strpos($requests['movie'], 'status') !== false) && !$dev_mode) {
    $error = json_decode($requests['movie']);
    $error = array(
      'status' => $error->status,
      'description' => 'undefined'
    );
    $status_codes = array(
      '200' => 'Success',
      '201' => 'Success - new resource created (POST)',
      '204' => 'Success - no content to return (DELETE)',
      '400' => 'Bad Request - request couldn\'t be parsed',
      '401' => 'Unauthorized - OAuth must be provided',
      '403' => 'Forbidden - invalid API key or unapproved app',
      '404' => 'Not Found - method exists, but no record found',
      '405' => 'Method Not Found - method doesn\'t exist',
      '409' => 'Conflict - resource already created',
      '412' => 'Precondition Failed - use application/json content type',
      '422' => 'Unprocessible Entity - validation errors',
      '429' => 'Rate Limit Exceeded',
      '500' => 'Server Error',
      '503' => 'Service Unavailable - server overloaded (try again in 30s)',
      '504' => 'Service Unavailable - server overloaded (try again in 30s)',
      '520' => 'Service Unavailable - Cloudflare error',
      '521' => 'Service Unavailable - Cloudflare error',
      '522' => 'Service Unavailable - Cloudflare error'
    );
  	if (array_key_exists($error['status'], $status_codes)) {
      $error['description'] = $status_codes[$error['status']];
  	}
    echo json_encode($error);
  }

	// Prepare "Search on Google / YouTube" query strings
	$param['poster']  = $suggest.'+official+poster';
	$param['website'] = $suggest.'+official+movie+website';
	$param['trailer'] = $suggest.'+official+movie+trailer';

	// Prepare content variables
	$title    = (!empty($movie['title']) ? $movie['title'] : ' ');
	$year     = (!empty($movie['year']) ? $movie['year'] : ' ');
	$duration = (!empty($movie['runtime']) ? $movie['runtime'] : ' ');
	$rating   = (!empty($movie['certification']) ? $movie['certification'] : ' ');
	$genres   = (!empty($movie['genres']) ? $movie['genres'] : ' ');
	$cast     = ' ';
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
	$synopsis = (!empty($movie['overview']) ? $movie['overview'] : ' ');
	$poster   = (!empty($movie['images']['poster']['medium']) ? $movie['images']['poster']['medium'] : null);
	$trailer  = (!empty($movie['trailer']) ? $movie['trailer'] : ' ');
	$website  = (!empty($movie['homepage']) ? $movie['homepage'] : ' '); // suggest_url($param['website']));

  // If the website or trailer are null, we need to fetch them for JSON response
  // Normally this is done with AJAX after page load, but we'll have to sacrifice speed for output here
	// $trailer  = ''; // (!empty($trailer) ? $trailer : suggest_url($param['trailer']));
	// $website  = ''; // (!empty($homepage) ? $homepage : suggest_url($param['website']));
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
      'website'  => $website
	  )
	);
  if ($dev_mode) { echo '<strong>Output to WordPress</strong><br>'; }
	echo json_encode($json_output);
}

?>
