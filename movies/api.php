<?php

$key = 'dc6299fd1adb4e32cf16017eecb33295'; // TMDB API Key
$urls = array(
  api     => 'https://api.themoviedb.org/3',
  image   => 'https://image.tmdb.org/t/p/w780',
  youtube => 'https://www.youtube.com/watch?v='
);

function tmdb($url) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{}",
    CURLOPT_HTTPHEADER => array(
      "content-type: application/json"
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
    return null;
  } else {
    if (strpos($response, 'status_code') !== false) {
      echo $response;
    }
    return $response;
  }
}

function sort_by_year($a, $b) {
	if($a['release_date'] == $b['release_date']){ return 0 ; }
	return ($a['release_date'] < $b['release_date']) ? 1 : -1;
}

$status  = (isset($_GET['status']) ? true : false);
$suggest = (!empty($_GET['suggest']) ? urlencode($_GET['suggest']) : null);
$tmdb    = (!empty($_GET['tmdb']) ? urlencode($_GET['tmdb']) : null);

if ($status) {
  // If the following API call returns a token we'll assume it's working
  $test            = [];
	$test['request'] = tmdb($urls['api'].'/authentication/token/new?api_key='.$key);
	$test['decoded'] = json_decode($test['request'],true);
  $test['status']  = isset($test['decoded']['success']) ? 'online' : 'offline';
  echo json_encode(array('status' => $test['status']));
}

if ($suggest) {
  $request = tmdb($urls['api'].'/search/movie?api_key='.$key.'&language=en-US&query='.$suggest.'&page=1');
  $movies = json_decode($request, true);

  usort($movies['results'], 'sort_by_year');

  if (count($movies['results']) > 0) {
    $suggestions = [];
    foreach ($movies['results'] as $movie) {
    	$title = (!empty($movie['title']) ? $movie['title'] : null);
    	$year  = (!empty($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'NA');
      $id    = (!empty($movie['id']) ? $movie['id'] : null);
      $suggestions[] = array(
        'title' => $title,
        'year'  => $year,
        'id'    => $id
      );
    }
    echo json_encode($suggestions);
  }
}

if ($tmdb) {
	$request = tmdb($urls['api'].'/movie/'.$tmdb.'?api_key='.$key.'&language=en-US&append_to_response=videos,credits,release_dates');
	$movie = json_decode($request, true);

	$title    = (!empty($movie['title']) ? $movie['title'] : null);
	$year     = (!empty($movie['release_date']) ? intval(date('Y', strtotime($movie['release_date']))) : null);
	$duration = (!empty($movie['runtime']) ? $movie['runtime'] : null);
	$rating   = null;
              if (!empty($movie['release_dates']['results'])) {
                foreach ($movie['release_dates']['results'] as $release) {
                  if($release['iso_3166_1'] == 'CA') {
                    if (isset($release['release_dates'][0]['certification'])) {
                      $rating = $release['release_dates'][0]['certification'];
                    }
                  }
                }
              }
	$genres   = [];
              if (!empty($movie['genres'])) {
                foreach ($movie['genres'] as $genre) {
                  $genres[] = $genre['name'];
                }
              }
	$cast     = null;
              if (!empty($movie['credits']['cast'])) {
                $i = 0;
                foreach ($movie['credits']['cast'] as $person) {
                  $i++;
                  $cast .= $person['name'];
                  if($i == 8) break; // Stop after we've reached 8 cast members
                  if(count($movie['credits']['cast']) == $i) break; // Stop if our cast list has ended early (prevents a trailing comma)
                  $cast .= ', ';
                }
              }
	$synopsis = (!empty($movie['overview']) ? $movie['overview'] : null);
	$poster   = (!empty($movie['poster_path']) ? $urls['image'].$movie['poster_path'] : null);
	$trailer  = (!empty($movie['videos']['results']) ? $urls['youtube'].$movie['videos']['results'][0]['key'] : null);
	$website  = (!empty($movie['homepage']) ? $movie['homepage'] : null);

	$json_output = array(
    'movie'      => array(
      'title' 	 => $title,
      'id'     	 => $tmdb,
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

	echo json_encode($json_output);
}

?>
