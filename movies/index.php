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

	// =============================
	// QUERY ARGUMENTS
	// =============================

	function movies_query_args($status) {
		if ($status == 'nowplaying') {
			return array (
				'post_type'		  => 'movies',
				'meta_query'    => array(
					array(
						'key'   	  => 'start_date',
						'value' 	  => strtotime('today'),
						'type' 		  => 'NUMERIC',
						'compare'   => '<='
					),
					array(
						'key'     => 'end_date',
						'value'   => strtotime('today'),
						'type' 		=> 'NUMERIC',
						'compare' => '>='
					),
		      array(
				    'key' 		=> 'listing_type',
						'value'		=> array('popup','widget'),
						'compare' => 'NOT IN'
			    )
				)
			);

		// Coming Soon
	} elseif ($status == 'comingsoon') {
			return array (
				'post_type'		=> 'movies',
				'meta_query'  => array(
					array(
						'key'   	=> 'start_date',
						'value' 	=> strtotime('now'),
						'type' 		=> 'NUMERIC',
						'compare' => '>'
					),
					array(
						'key'   	=> 'start_date',
						'value' 	=> strtotime('-1 week'),
						'type' 		=> 'NUMERIC',
						'compare' => '>'
					),
		      array(
				    'key' 		=> 'listing_type',
						'value'		=> array('popup','widget'),
						'compare' => 'NOT IN'
			    )
				)
			);
		} elseif ($status == 'popups') {
			return array (
				'post_type'  => 'movies',
				'meta_query' => array(
			    array(
			      'key'    => 'listing_type',
			      'value'  => 'popup'
			    )
				)
			);
		} elseif ($status == 'widget') {
			return array (
				'post_type'  => 'movies',
				'meta_query' => array(
					array(
						'key'   => 'listing_type',
						'value' => 'widget'
					)
				)
			);
		}
	}

	// Add Columns to the Movie Listings Post List
	add_filter('manage_movies_posts_columns', 'movies_column_headers', 10);
	function movies_column_headers($defaults) {
    $defaults['featured_image'] = 'Poster';
		$defaults['trailer'] = 'Trailer';
		$defaults['showtimes'] = 'Show Times';
		$defaults['start_date'] = 'Start Date';
		$defaults['end_date'] = 'End Date';
		$defaults['status'] = 'Status';
		// Columns created specifically for the quick edit box. These are meant to be hidden
		$defaults['showtime_override'] = 'Showtime Override';
		$defaults['listing_label'] = 'Special Listing Label';
		$defaults['listing_type'] = 'Special Listing Type';
		$defaults['notes'] = 'Notes';
		return $defaults;
	}

	// Handle the Column Data
	add_action('manage_movies_posts_custom_column', 'movies_column_data', 10, 2);
	function movies_column_data($column_name, $post_ID) {
		switch ($column_name) {
	    case 'start_date':
				$start_date = get_post_meta($post_ID, 'start_date', true);
				if ($start_date) {
					echo '<span data-meta="'.date('M. j, Y',$start_date).'">'.date('M. j, Y',$start_date).'</span>';
				} else {
					echo '<span data-meta="" style="color:#a00;">No date entered</span>';
				}
				break;
	    case 'end_date':
				$end_date = get_post_meta($post_ID, 'end_date', true);
				if ($end_date && ($end_date != 246767472000)) {
					echo '<span data-meta="'.date('M. j, Y',$end_date).'">'.date('M. j, Y',$end_date).'</span>';
				} else {
					echo '<span data-meta="" style="color:#a00;">No date entered</span>';
				}
				break;
			case 'status':
				$start_date = get_post_meta($post_ID, 'start_date', true);
				$end_date = get_post_meta($post_ID, 'end_date', true);
				if (empty($start_date) || empty($end_date)) {
					echo '<span style="color:#a00;">Start and end dates are required</span>';
					break;
				}
				$start_week = (int)date('W', $start_date);
				$listing_type = get_post_meta($post_ID, 'listing_type', true);
				$current_date = strtotime('today');
				$current_week = (int)date('W', $current_date);
				$column_data = 'N/A';
				// Status Conditions
				if (!empty($start_date) && ($current_date >= $start_date) && (($current_date <= $end_date) || (empty($end_date)))) { $column_data = '<strong>Now Playing</strong>'; }
				if (empty($start_date)) { $column_data = '<span style="color:#a00;">Missing start date</span>';	}
				if (($current_date < $start_date)) { $column_data = 'Coming Soon';	}
				if (!empty($end_date) && ($current_date > $end_date)) { $column_data = '<span style="color:#a00;">Expired</span>';	}
				if (get_post_status ($post_ID) != 'publish') { $column_data = '<span style="color:#aaa;">Not published</span>';	}
				if (get_post_status ($post_ID) == 'future') { $column_data = 'Scheduled';	}
				if (!empty($listing_type) && ($listing_type == 'popup')) { $column_data = 'Showing as Popup';	}
				if (!empty($listing_type) && ($listing_type == 'widget')) { $column_data = 'Showing in Widget';	}
				echo $column_data;
				break;
			case 'showtimes':
				$showtimes = get_post_meta($post_ID, 'showtimes', true);
				if ($showtimes) {
					echo '<span data-meta="'.$showtimes.'">'.$showtimes.'</span>';
				} else {
					echo '<span data-meta="" style="color:#a00;">No showtimes entered</span>';
				}
				break;
			case 'featured_image':
				$poster_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_ID),'full');
        echo '<a href="'.$poster_url[0].'?TB_iframe=true&width=600&height=900" class="thickbox" title="Poster for '.get_the_title().'">'.get_the_post_thumbnail($post_ID, 'small-square', '').'</a>';
			break;
			case 'trailer':
				$trailer = get_post_meta($post_ID, 'trailer', true);
				if ($trailer) { ?>
					<a href="<?=preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1",$trailer)?>?rel=0&amp;autohide=2&amp;iv_load_policy=3&amp;modestbranding=1&amp;color=white&amp;TB_iframe=true&width=800&height=450" class="thickbox button" title="Trailer for <?=get_the_title()?>"><span class="dashicons dashicons-video-alt3"></span></a>
					<?php
					echo '<span class="button copy_to_clipboard" title="Copy to clipboard"><span class="dashicons dashicons-admin-links"></span></span><input value="'.$trailer.'"></input>';
				} else {
					echo '<span data-meta="" style="color:#a00;">No trailer entered</span>';
				}
				break;
			case 'showtime_override':
				$showtime_override = get_post_meta($post_ID, 'showtime_override', true);
				if ($showtime_override) {
					echo '<span data-meta="'.$showtime_override.'">'.$showtime_override.'</span>';
				}
				break;
			case 'listing_label':
				$listing_label = get_post_meta($post_ID, 'listing_label', true);
				if ($listing_label) {
					echo '<span data-meta="'.$listing_label.'">'.$listing_label.'</span>';
				}
				break;
			case 'listing_type':
				$listing_type = get_post_meta($post_ID, 'listing_type', true);
				if ($listing_type) {
					echo '<span data-meta="'.$listing_type.'">'.$listing_type.'</span>';
				}
				break;
			case 'notes':
				$notes = get_post_meta($post_ID, 'notes', true);
				if ($notes) {
					echo '<span data-meta="'.$notes.'">'.$notes.'</span>';
				}
				break;
			}
	}

	// Register the Date Columns as Sortable
	add_filter("manage_edit-movies_sortable_columns", 'date_column_sortable');
	function date_column_sortable($columns) {
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

	add_action('quick_edit_custom_box', 'quickedit_posts_custom_box', 10, 2);
	function quickedit_posts_custom_box( $col, $type ) {
			if( $col != 'showtimes' || $type != 'movies' ) {
					return;
			} ?>
			<legend class="inline-edit-legend">Active Period</legend>
			<fieldset class="inline-edit-col-right"><div class="inline-edit-col">
				<div class="inline-edit-group">
					<label>
						<span class="title">Start Date</span>
						<span class="input-text-wrap"><input type="text" name="start_date" value=""></span>
					</label>
					<label>
						<span class="title">End Date</span>
						<span class="input-text-wrap"><input type="text" name="end_date" value=""></span>
					</label>
					<label>
						<span class="title">Custom</span>
						<span class="input-text-wrap"><input type="text" name="showtime_override" value="" placeholder="e.g. <?=date('F j',strtotime('next Saturday')).' - '.date('F j',strtotime('next Saturday +3 days'))?>"></span>
					</label>
				</div>
			</fieldset>

			<legend class="inline-edit-legend">Special Listing</legend>
			<fieldset class="inline-edit-col-right"><div class="inline-edit-col">
				<div class="inline-edit-group">
					<label>
						<span class="title">Label</span>
						<span class="input-text-wrap"><input type="text" name="listing_label" value="" placeholder="e.g. Special Showtime"></span>
					</label>
					<label>
						<span class="title">Type</span>
						<span class="input-text-wrap">
							<label style="display:inline-block;margin-right:8px;"><input type="radio" name="listing_type" value="none">None</label>
							<label style="display:inline-block;margin-right:8px;"><input type="radio" name="listing_type" value="popup">Show as Popup</label>
							<label style="display:inline-block;"><input type="radio" name="listing_type" value="widget">Show in Widget</label>
						</span>
					</label>
				</div>
			</fieldset>

			<legend class="inline-edit-legend">Showtimes</legend>
			<fieldset class="inline-edit-col-right"><div class="inline-edit-col">
				<div class="inline-edit-group">
					<label>
						<span class="title">Showtimes</span>
						<span class="input-text-wrap">
							<span class=""><strong>Note:</strong> due to limitations, quick edit will load the raw HTML only.</span>
							<textarea name="showtimes"></textarea>
						</span>
					</label>
					<label>
						<span class="title">Notes</span>
						<span class="input-text-wrap">
							<span class=""><strong>Note:</strong> due to limitations, quick edit will load the raw HTML only.</span>
							<textarea name="notes"></textarea>
						</span>
					</label>
				</div>
			</fieldset>
			<?php
	}

	// Remove Tags
	add_action('init', 'movie_tags');
	function movie_tags() {
		register_taxonomy('post_tag', array('movie_tags'));
	}

	// Remove slugs, add Movie Poster
	add_action('do_meta_boxes', 'movie_metaboxes');
	function movie_metaboxes() {
		global $post;
		remove_meta_box('slugdiv', 'movies', 'normal');
		remove_meta_box('postimagediv', 'movies', 'side');
		add_meta_box('postimagediv', 'Poster', 'post_thumbnail_meta_box', 'movies', 'side', 'high');
		if(!empty($post)) {
			if(get_post_meta($post->ID, '_wp_page_template', true) == 'page-movies.php') {
				remove_meta_box('et_settings_meta_box', 'page', 'side');
				add_meta_box(
					'movie_page_settings',
					'Movie Page Settings',
					'movie_page_settings_content',
					'page',
					'side',
					'high'
				);
				remove_meta_box('et_settings_meta_box', 'page', 'side');
			}
		}
	}

	require_once('widget.php');

	// =============================
	// META BOXES
	// =============================

	add_action('add_meta_boxes', 'movie_listing_details');
	function movie_listing_details() {
		global $post;
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
			'movie_fetch_details',
			'Fetch Movie Details',
			'movie_fetch_details_content',
			'movies',
			'side',
			'high'
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
	require_once('metaboxes/settings.php');
	require_once('metaboxes/schedule.php');
	require_once('metaboxes/details.php');
	require_once('metaboxes/links.php');
	require_once('metaboxes/fetch.php');
	require_once('metaboxes/response.php');
	require_once('metaboxes/tutorials.php');

	// =============================
	// FETCH MOVIE DETAILS
	// =============================

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

	add_image_size('summary-thumbnail', 66, 100, false);
	add_image_size('small-square', 80, 80, true);
	add_image_size('email-thumbnail', 150, 225, true);

	// =============================
	// SCRIPTS AND STYLESHEETS
	// =============================

	// Enqueue the scripts and stylesheets for our movies post type
	add_action('admin_enqueue_scripts', 'movie_admin_scripts', 10, 1);
	function movie_admin_scripts($hook) {
	  global $post;

		// Make sure to only load the scripts on the movie post pages
    if (isset($post) && $post->post_type == 'movies') {
	  	if ($hook == 'post-new.php' || $hook == 'post.php') {
				wp_enqueue_style ('movie-lazy-youtube-css', get_stylesheet_directory_uri() . '/assets/css/lazy-youtube.css');
				wp_enqueue_style ('movie-json-viewer-css', get_stylesheet_directory_uri() . '/assets/css/json-view.css');
				wp_enqueue_style ('movie-movies-css', get_stylesheet_directory_uri() . '/assets/css/movies.css');
		    wp_enqueue_script('movie-youtube-js', get_stylesheet_directory_uri() . '/assets/js/lazy-youtube.js');
		    wp_enqueue_script('movie-json-js', get_stylesheet_directory_uri() . '/assets/js/json-view.js');
		    wp_enqueue_script('movie-movies-js', get_stylesheet_directory_uri() . '/assets/js/movies.js');
			}
	  	if ($hook == 'edit.php') {
				wp_enqueue_style ('movie-movies-list-css', get_stylesheet_directory_uri() . '/assets/css/movies-list.css');
	    	wp_enqueue_script('movie-movies-list-js', get_stylesheet_directory_uri() . '/assets/js/movies-list.js');
				add_thickbox();
			}
		}
	}

	// Enqueue the script for loading movie details in a popup
	add_action('wp_enqueue_scripts', 'page_movies_script');
	function page_movies_script() {
    if (is_page_template('page-movies.php') || is_active_widget('', '', 'divicinematicmovie_widget')) {
			wp_enqueue_script('page_movies', get_stylesheet_directory_uri() . '/assets/js/page-movies.js');
		}
	}
}

?>
