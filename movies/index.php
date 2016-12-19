<?php

// =============================
// MOVIE POST TYPE
// =============================

// This file creates a custom post type in WordPress
// for creating movie listings.

// Developed by: Stef Friesen
// URL: http://www.frsn.ca

if (!function_exists('movies_post_type')) {

	// =============================
	// MOVIE LISTING POST TYPE
	// =============================

	add_action('init', 'movies_post_type', 0);
	function movies_post_type() {
		$labels = array(
			'name'                => 'Movies',
			'singular_name'       => 'Movie',
			'menu_name'           => 'Movies',
			'parent_item_colon'   => 'Parent Movies:',
			'all_items'           => 'All Movies',
			'view_item'           => 'View Movie',
			'add_new_item'        => 'Add New Movie',
			'add_new'             => 'Add New Movie',
			'edit_item'           => 'Edit Movie',
			'update_item'         => 'Update Movie',
			'search_items'        => 'Search Movies',
			'not_found'           => 'Not found',
			'not_found_in_trash'  => 'Not found in Trash',
		);
		$args = array(
			'label'               => 'Movies',
			'description'         => 'Movies',
			'labels'              => $labels,
			'supports'            => array('title', 'thumbnail',),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-format-video',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type('movies', $args);
	}

	// =============================
	// GENRES TAXONOMY
	// =============================

	add_action('init', 'movies_genres', 0);
	function movies_genres() {
		register_taxonomy(
			'genre',
			'movies',
			array(
				'labels' => array(
					'name' => 'Genres',
					'add_new_item' => 'Add New Genre',
					'new_item_name' => "New Genre"
				),
				'show_admin_column' => false,
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
			)
		);
	}

	// =============================
	// ADVISORIES TAXONOMY
	// =============================

	add_action('init', 'movies_advisories', 0);
	function movies_advisories() {
		register_taxonomy(
			'advisory',
			'movies',
			array(
				'labels' => array(
					'name' => 'Advisories',
					'add_new_item' => 'Add New Advisory',
					'new_item_name' => "New Advisory"
				),
				'show_admin_column' => false,
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
			)
		);
	}

	// =============================
	// FEATURES TAXONOMY
	// =============================

	add_action('init', 'movies_features', 0);
	function movies_features() {
		register_taxonomy(
			'feature',
			'movies',
			array(
				'labels' => array(
					'name' => 'Features',
					'add_new_item' => 'Add New Feature',
					'new_item_name' => "New Feature"
				),
				'show_admin_column' => false,
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
			)
		);
	}

	// Add Start Date Column to Movie Listings
	add_filter('manage_movies_posts_columns', 'start_date_column_header', 10);
	function start_date_column_header($defaults) {
		$defaults['start_date'] = 'Start Date';
		return $defaults;
	}

	// Handle the Start Date Column Data
	add_action('manage_movies_posts_custom_column', 'start_date_column_content', 10, 2);
	function start_date_column_content($column_name, $post_ID) {
		if ($column_name == 'start_date') {
			$start_date = get_post_meta($post_ID, 'start_date', true);
			$column_data = ($start_date ? date('M. j, Y',$start_date) : '<span style="color:#ff0000;">No date entered</span>');
			echo $column_data;
		}
	}

	// Add End Date Column to Movie Listings
	add_filter('manage_movies_posts_columns', 'end_date_column_header', 10);
	function end_date_column_header($defaults) {
		$defaults['end_date'] = 'End Date';
		return $defaults;
	}

	// Handle the End Date Column Data
	add_action('manage_movies_posts_custom_column', 'end_date_column_content', 10, 2);
	function end_date_column_content($column_name, $post_ID) {
		if ($column_name == 'end_date') {
			$end_date = get_post_meta($post_ID, 'end_date', true);
			$column_data = ($end_date && ($end_date != 246767472000) ? date('M. j, Y',$end_date) : '<span style="color:#aaaaaa;">No date entered</span>');
			echo $column_data;
		}
	}

	// Add Status Column to Movie Listings
	add_filter('manage_movies_posts_columns', 'status_column_header', 10);
	function status_column_header($defaults) {
		$defaults['status'] = 'Status';
		return $defaults;
	}

	// Handle the Status Column Data
	add_action('manage_movies_posts_custom_column', 'status_column_content', 10, 2);
	function status_column_content($column_name, $post_ID) {
		if ($column_name == 'status') {
			$start_date = get_post_meta($post_ID, 'start_date', true);
			$start_week = (int)date('W', $start_date);
			$end_date = get_post_meta($post_ID, 'end_date', true);
			$listing_type = get_post_meta($post_ID, 'listing_type', true);
			$current_date = strtotime('today');
			$current_week = (int)date('W', $current_date);

			$column_data = 'N/A';

			// Status Conditions
			if (!empty($start_date) && ($current_date >= $start_date) && (($current_date <= $end_date) || (empty($end_date)))) { $column_data = '<strong>Now Playing</strong>'; }
			if (empty($start_date)) { $column_data = '<span style="color:#dd3d36;">Missing start date</span>';	}
			if (($current_date < $start_date)) { $column_data = 'Coming Soon';	}
			if (!empty($end_date) && ($current_date > $end_date)) { $column_data = '<span style="color:#dd3d36;">Expired</span>';	}
			if (get_post_status ($post_ID) != 'publish') { $column_data = '<span style="color:#aaa;">Not published</span>';	}
			if (get_post_status ($post_ID) == 'future') { $column_data = 'Scheduled';	}
			if (!empty($listing_type) && ($listing_type == 'popup')) { $column_data = 'Showing as Popup';	}
			if (!empty($listing_type) && ($listing_type == 'widget')) { $column_data = 'Showing in Widget';	}

			echo $column_data;
		}
	}

	// Register the Date Columns as Sortable
	add_filter("manage_edit-movies_sortable_columns", 'status_column_sortable');
	function status_column_sortable($columns) {
		$columns['start_date'] = 'start_date';
		$columns['end_date'] = 'end_date';
		return $columns;
	}

	// Handle the Date Column Sorting
	add_filter('request', 'date_column_orderby');
	function date_column_orderby($vars) {
		// Sort by Start Date
	  if (isset($vars['orderby']) && 'start_date' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
	      'meta_key' => 'start_date',
	      'orderby' => 'meta_value_num'
	  ));
	  }
		// Sort by End Date
	  if (isset($vars['orderby']) && 'end_date' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
	      'meta_key' => 'end_date',
	      'orderby' => 'meta_value_num'
	  ));
	  }
	  return $vars;
	}

	// Remove Tags
	add_action('init', 'movie_tags');
	function movie_tags() {
		register_taxonomy('post_tag', array('movie_tags'));
	}

	// Remove slugs, add Movie Poster
	add_action('do_meta_boxes', 'movie_metaboxes');
	function movie_metaboxes() {
		remove_meta_box('slugdiv', 'movies', 'normal');
		remove_meta_box('postimagediv', 'movies', 'side');
		add_meta_box('postimagediv', 'Poster', 'post_thumbnail_meta_box', 'movies', 'side', 'high');
	}

	// Change 'Set featured image' text
	add_filter('admin_post_thumbnail_html', 'custom_admin_post_thumbnail_html');
	function custom_admin_post_thumbnail_html($content) {
    global $post;
    if ('movies' == $post->post_type) {
			$content = str_replace('Set featured image', 'Upload Poster Image', $content);
			$content = str_replace('Remove featured image', 'Remove Poster Image', $content);
    }
    return $content;
	}

	// Include the special listing widget
	require_once('widget.php');

	// =============================
	// META BOXES
	// =============================

	add_action('add_meta_boxes', 'movie_listing_details');
	function movie_listing_details() {
		add_meta_box(
			'movie_schedule',
			'Schedule',
			'movie_schedule_content',
			'movies',
			'normal',
			'high'
		);
		add_meta_box(
			'movie_links',
			'External Links',
			'movie_links_content',
			'movies',
			'normal',
			'core'
		);
		add_meta_box(
			'movie_details',
			'Details',
			'movie_details_content',
			'movies',
			'normal',
			'high'
		);
		add_meta_box(
			'movie_response',
			'API Response Data',
			'movie_response_content',
			'movies',
			'normal',
			'low'
		);
		add_meta_box(
			'movie_tutorials',
			'Tutorials',
			'movie_tutorials_content',
			'movies',
			'side',
			'high'
		);
	}

	// Include the separate metabox files
	require_once('metaboxes/schedule.php');
	require_once('metaboxes/details.php');
	require_once('metaboxes/links.php');
	require_once('metaboxes/response.php');
	require_once('metaboxes/tutorials.php');

	// =============================
	// FETCH MOVIE DETAILS
	// =============================

	// Add our 'Load Movie Details' button to the Publish box
	add_action('post_submitbox_misc_actions', 'custom_button');
	function custom_button() {
	  global $post_type;
		global $post_id;
		$meta = get_post_meta($post_id);

		$api_url = get_stylesheet_directory_uri().'/movies/api.php?status';
	  if($post_type == 'movies' && empty($meta['json_response'])) {
			echo '
				<div id="major-publishing-actions" style="overflow:hidden">
					<div id="publishing-action" class="fetch-details" style="width:100%">
						<span style="float:left;position:relative;top:3px">
							<span id="api_spinner" class="spinner"></span>
							<span id="api_status">Checking...</span>
						</span>
						<a class="button-primary" id="load_movie" data-url="'.$api_url.'">Fetch Movie Details</a>
					</div>
				</div>
			';
		}
	}

	// Look up rating description
	function rating_description($rating) {
		$rating = strtoupper($rating);
		$rating = str_replace("-", "", $rating);
		$descriptions = [
			'G'   => 'Suitable for viewing by persons of all ages',
			'PG'  => 'Parental guidance advised',
			'14A' => 'Suitable for persons 14 years of age or older',
			'14A' => 'Persons under 18 years of age must view these motion pictures accompanied by an adult',
			'18A' => 'Restricted to persons 18 years of age and over'
		];
		if(array_key_exists($rating,$descriptions)) {
			return $descriptions[$rating];
		}
		return 'NA';
	}

	// Confirm film rating via AJAX
	add_action('wp_ajax_movie_confirm_film_rating', 'movie_confirm_film_rating_ajax');
	function movie_confirm_film_rating_ajax() {
		$cpbc_url = 'http://www.consumerprotectionbc.ca/consumers-film-and-video-homepage/classification-search?submitted=1&featuretitle='.urlencode(preg_replace("/[^a-zA-Z0-9\s]+/", "",$_POST['title']));
		echo file_get_contents($cpbc_url);
		wp_die();
	}

	// Check API status via AJAX
	add_action('wp_ajax_movie_api_status', 'movie_api_status_ajax');
	function movie_api_status_ajax() {
		$api_url		  = get_stylesheet_directory_uri().'/movies/api.php?status';
		$api_request  = file_get_contents($api_url);
		$api_response = json_decode($api_request, true);
		echo $api_response['status'];
		wp_die();
	}

	// AJAX API function to fetch movie details without multiple page reloads
	add_action('wp_ajax_movie_suggestions', 'movie_suggestions_ajax');
	add_action('wp_ajax_nopriv_my_update_pm', 'movie_suggestions_ajax');
	function movie_suggestions_ajax() {
		$api_url		 = get_stylesheet_directory_uri().'/movies/api.php?suggest='.urlencode($_POST['title']);
		$api_request = file_get_contents($api_url);
		echo $api_request;
		wp_die();
	}

	// AJAX API function to fetch movie details without multiple page reloads
	add_action('wp_ajax_movie_fetch', 'movie_details_ajax');
	add_action('wp_ajax_nopriv_my_update_pm', 'movie_details_ajax');
	function movie_details_ajax() {
		$api_url		 = get_stylesheet_directory_uri().'/movies/api.php?tmdb='.urlencode($_POST['tmdb']);
		$api_request = file_get_contents($api_url);
		$movie_obj 	 = json_decode($api_request,true);
		$movie 			 = $movie_obj['movie'];

		// Set the variables
		// Using addcslashes() to double escape quotes sometimes returned in the synopsis
		if (!empty($movie_obj))				  { update_post_meta($_POST['id'], 'json_response', addcslashes($api_request, '"')); }
		if (!empty($movie['duration'])) { update_post_meta($_POST['id'], 'runtime_minutes', $movie['duration']); }
		if (!empty($movie['synopsis'])) { update_post_meta($_POST['id'], 'description', 		$movie['synopsis']); }
		if (!empty($movie['cast']))		  { update_post_meta($_POST['id'], 'starring', 			  $movie['cast']); }
		if (!empty($movie['trailer']))  { update_post_meta($_POST['id'], 'trailer', 				$movie['trailer']); }
		if (!empty($movie['website']))  { update_post_meta($_POST['id'], 'website', 				$movie['website']); }
		if (!empty($movie['rating']))   {
			update_post_meta($_POST['id'], 'rating', $movie['rating']);
			update_post_meta($_POST['id'], 'rating_description', rating_description($movie['rating']));
		}
		// Build the genre string to use in a hidden form input later
		if (!empty($movie['genres'])) {
			$genre_string = '';
			// Create the genre if it doesn't exist
			foreach($movie['genres'] as $genre) {
				if(!term_exists($genre, 'genre')) {
					wp_insert_term(ucwords($genre), 'genre');
				}
				$genre_string .= $genre.',';
			}
		}
		// This is the only output sent back to the AJAX call
		echo $api_request;
		// Add the poster to the media library
		if (!empty($movie['poster'])) {
			$media = media_sideload_image($movie['poster'], $_POST['id'], (!empty($movie['title']) ? $movie['title'] : $_POST['title']));
		}
		wp_die();
	}

	// Set the movie poster on the save action
	add_action('save_post', 'set_movie_poster');
	function set_movie_poster() {
		if (!isset($GLOBALS['post']->ID))
			return NULL;

		if (has_post_thumbnail(get_the_ID()))
			return NULL;

		$args = array(
			'numberposts'    => 1,
			'order'          => 'ASC',
			'post_mime_type' => 'image',
			'post_parent'    => get_the_ID(),
			'post_status'    => NULL,
			'post_type'      => 'attachment'
		);

		$attached_image = get_children($args);
		if ($attached_image) {
			foreach ($attached_image as $attachment_id => $attachment)
				set_post_thumbnail(get_the_ID(), $attachment_id);
		}
	}

	// =============================
	// SCRIPTS AND STYLESHEETS
	// =============================

	// Enqueue the scripts and stylesheets for our movies post type
	add_action('admin_enqueue_scripts', 'movie_admin_scripts', 10, 1);
	function movie_admin_scripts($hook) {
	  global $post;

		// Make sure to only load the scripts on the movie post pages
	  if ($hook == 'post-new.php' || $hook == 'post.php') {
	    if ('movies' === $post->post_type) {
				wp_enqueue_style ('movie-lazy-youtube-css', get_stylesheet_directory_uri() . '/assets/css/lazy-youtube.css');
				wp_enqueue_style ('movie-json-viewer-css', get_stylesheet_directory_uri() . '/assets/css/json-view.css');
				wp_enqueue_style ('movie-movies-css', get_stylesheet_directory_uri() . '/assets/css/movies.css');
		    wp_enqueue_script('movie_youtube', get_stylesheet_directory_uri() . '/assets/js/lazy-youtube.js');
		    wp_enqueue_script('movie_json', get_stylesheet_directory_uri() . '/assets/js/json-view.js');
		    wp_enqueue_script('movie_movies', get_stylesheet_directory_uri() . '/assets/js/movies.js');
			}
		}
	}

	// Enqueue the script for loading movie details in a popup
	add_action('wp_enqueue_scripts', 'page_movies_script');
	function page_movies_script() {
    if (is_page_template('page-movies.php') ||  is_active_widget('', '', 'divicinematicmovie_widget')) {
			wp_enqueue_script('page_movies', get_stylesheet_directory_uri() . '/assets/js/page-movies.js');
		}
	}
}

?>
