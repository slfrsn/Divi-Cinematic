<?php

// Set a global timezone for use in our code
date_default_timezone_set('America/Vancouver');

// Include the movie post type
require_once('movies/index.php');

// Shorthand function for echoing variables that might not exist
function echo_var(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

// Shorthand function for echoing checkbox values
function echo_checkbox(&$var, $value, $default = false) {
	if (isset($var)) {
		checked($var, $value);
	}
}

// RFC 3986 URL encoding
function urlencode_percent($string) {
  $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
  $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
  return str_replace($entities, $replacements, urlencode($string));
}

// Shorthand function for input saves in our metaboxes
function save_if_changed($value, $post_id, $is_string) {
	if (isset($_POST[$value]) && ($_POST['fetched'] != true)) {
		$stored = get_post_meta($post_id, $value, true);
		$proposed = $_POST[$value];
		if($proposed != $stored) { // Only save the value if it has changed
			if($is_string) {
				update_post_meta($post_id, $value, sanitize_text_field($proposed));
			} else { // For saving checkbox values, etc., use this
				update_post_meta($post_id, $value, $proposed);
			}
		}
	}
}

// Function to convert arrays to strings with a delimiter
function convert_to_string($array, $delimiter) {
	$copy = $array;
	$string = '';
	foreach ($array as $value) {
			$string .= $value->name;
	    if (next($copy )) {
	        $string .= $delimiter; // Add delimiter for all elements instead of last
	    }
	}
	return $string;
}

add_action('customize_register', 'divi_cinematic_customizer');
function divi_cinematic_customizer($wp_customize) {
  $wp_customize->add_panel('divi_cinematic_options', array(
    'priority' => 1,
    'capability' => 'edit_theme_options',
    'title' => 'Divi Cinematic',
    'description' => 'Customize movie listing settings',
  ));

  $wp_customize->add_section('movie_listing_section', array(
    'title' => 'Movie Listing Settings',
    'panel' => 'divi_cinematic_options',
  ));

  $wp_customize->add_setting('movie_changeover_day', array(
   'default' => 'Friday',
  ));

  $wp_customize->add_control('movie_changeover_day', array(
    'type' => 'select',
    'label' => 'Movies change over on...',
    'section' => 'movie_listing_section',
    'choices' => array(
        'Sunday' => 'Sunday',
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday'
      ),
  ));

  $wp_customize->add_section('divi_cinematic_social_preview', array(
    'title' => 'Social Media Appearance',
    'panel' => 'divi_cinematic_options',
  ));

  $wp_customize->add_setting('divi_cinematic_social_description', array(
   'description' => 'Friday',
  ));

  $wp_customize->add_control('divi_cinematic_social_description', array(
    'type' => 'text',
    'label' => 'Website description',
    'section' => 'divi_cinematic_social_preview',
  ));

  $wp_customize->add_setting('divi_cinematic_social_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,'divi_cinematic_social_image',array(
   'label'      => 'Website Image',
   'section'    => 'divi_cinematic_social_preview',
   'settings'   => 'divi_cinematic_social_image',
   )));

}

/* Overwrite Divi's meta function */
function elegant_description() {
	global $post;

  $page_object = get_queried_object();
  $page_id = get_queried_object_id();
  $page_meta = get_post_meta($page_id);

  $site_name = get_bloginfo('name');
  $title = $site_name;
  $description = esc_attr(get_theme_mod('divi_cinematic_social_description', ''));
  $url = get_permalink($page_object, false);
  $type = 'website';
  $image = get_theme_mod('divi_cinematic_social_image', '');

  if (!empty($page_meta['page_type']) && $page_meta['page_type'][0] == 'comingsoon') {
    $movies_args = movies_query_args('comingsoon');
    $status = 'Coming Soon';
  } else {
    $movies_args = movies_query_args('nowplaying');
    $status = 'Now Playing';
  }

  // Movie Listing Summaries
	if (is_page_template('page-movies.php') && !isset($_GET['movie'])) {
    $movies_query = new WP_Query($movies_args);
    $counter = 0;
		$count = $movies_query->post_count;
  	if ($movies_query->have_posts()):
      $description = $status.': ';
      $count = $movies_query->post_count;
      $counter = 0;
			while($movies_query->have_posts()) : $movies_query->the_post();
				++$counter;
        $description .= $post->post_title;
        $description .= ($counter < $count ? ', ' : '');
      endwhile;
    endif;
	}

  // Single Movies
  if (is_page_template('page-movies.php') && isset($_GET['movie'])) {
    $movie = get_page_by_path($_GET['movie'], OBJECT, 'movies' );
    $movie_meta = get_post_meta($movie->ID);
    $title = $movie->post_title.' ('.$status.' at '.get_bloginfo('name').')';
    $type = 'article';
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($movie->ID), 'single-post-thumbnail');
    $image = $image[0];
    if (!empty($movie_meta['showtimes'][0])) {
      $description = 'Showtimes: '.$movie_meta['showtimes'][0].' / Click here for trailer and more information!';
      $description = str_replace('<br />', ' / ', $description);
      $description = strip_tags($description);
    }
    $url = $url.'?movie='.$movie->post_name;
	}

  echo '<meta property="og:site_name" content="'.$site_name.'">';
	echo '<meta property="og:title" content="'.$title.'">';
	echo '<meta property="og:description" content="'.esc_attr($description).'">';
	echo '<meta property="og:url" content="'.$url.'">';
	echo '<meta property="og:type" content="'.$type.'">';
	echo '<meta property="og:image" content="'.$image.'" />';
}

// Our theme needs it's own textdomain for translation
add_action('after_setup_theme', 'divi_cinematic_setup');
function divi_cinematic_setup(){
	load_child_theme_textdomain('divi-cinematic', get_stylesheet_directory_uri() . '/languages');
}

// Automatic theme updates from the GitHub repository
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1);
function automatic_GitHub_updates($data) {
  // Theme information
  $theme   = get_stylesheet(); // Get the folder name of the current theme
  $current = wp_get_theme()->get('Version'); // Get the version of the current theme
  // GitHub information
  $user = 'slfrsn'; // The GitHub username hosting the repository
  $repo = 'divi-cinematic'; // Repository name as it appears in the URL
  // Get the latest release tag from the repository. The User-Agent header must be sent, as per
  // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
  $file = @json_decode(@file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
      stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
  ));
  if($file) {
    // Strip the version number of any non-alpha characters (excluding the period)
    // This way you can still use tags like v1.1 or ver1.1 if desired
    $update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    // Only return a response if the new version number is higher than the current version
    if($update > $current) {
      $data->response[$theme] = array(
          'theme'       => $theme,
          'new_version' => $update,
          'url'         => 'https://github.com/'.$user.'/'.$repo,
          'package'     => $file->assets[0]->browser_download_url,
      );
    }
  }
  return $data;
}

//Dequeue JavaScripts
// function project_dequeue_unnecessary_scripts() {
// if (is_page_template('page-movies.php')) {
//   wp_dequeue_script( 'et_pb_admin_js' );
//   wp_deregister_script( 'et_pb_admin_js' );
//     }
// }
// add_action('wp_print_scripts', 'project_dequeue_unnecessary_scripts');

// Enqueue the scripts and stylesheets for our interactive tutorials
add_action('admin_enqueue_scripts', 'tutorial_scripts', 10, 1);
function tutorial_scripts($hook) {
  if($hook === 'edit.php') {
    wp_enqueue_style ( 'tutorial-intro-css', get_stylesheet_directory_uri() . '/assets/css/intro.css' );
  	wp_enqueue_style ( 'tutorial-intro-theme-css', get_stylesheet_directory_uri() . '/assets/css/intro-theme.css' );
    wp_enqueue_script( 'tutorial_intro', get_stylesheet_directory_uri() . '/assets/js/intro.js' );
    wp_enqueue_script( 'tutorial_tutorials', get_stylesheet_directory_uri() . '/assets/js/tutorials.js' );
  }
}

// Replace the footer copyright text
// We could override footer.php entirely, but this way we don't have to
// check for syntax changes every time the parent theme gets updated
add_action('wp_head', 'buffer_start');
add_action('wp_footer', 'buffer_end');
function callback($buffer) {
	$source  = '#<p id="footer-info">(.*?)</p>#s';
	$replace = '<p id="footer-info">'.sprintf( __( 'Designed & Powered by %1$s', 'divi-cinematic' ), '<a href="http://www.onetrix.com"><img src="'.get_stylesheet_directory_uri().'/assets/images/onetrix.png" alt="O-Netrix Solutions" title="O-Netrix Solutions" id="onetrix" /></a>' ).'</p>';
  return preg_replace($source,$replace,$buffer);
}
function buffer_start() { ob_start("callback"); }
function buffer_end() { ob_end_flush(); }

?>
