<?php
	if (!defined('ABSPATH')) exit;

	$header_image = (empty($theme_options['theme_header_image']) ? '' : $theme_options['theme_header_image']);
	$background_colour = (empty($theme_options['theme_background_colour']) ? '#333333' : $theme_options['theme_background_colour']);
	$accent_colour = (empty($theme_options['theme_accent_colour']) ? '#222222' : $theme_options['theme_accent_colour']);
	$accent_text_colour = (empty($theme_options['theme_accent_text_colour']) ? '#FFFFFF' : $theme_options['theme_accent_text_colour']);
	$include_movies = (isset($theme_options['theme_include_movies']) ? true : false);
	$comingsoon_url = (empty($theme_options['theme_comingsoon_url']) ? '' : $theme_options['theme_comingsoon_url']);
	$disable_social = (isset($theme_options['theme_disable_links']) ? true : false);
	$website_title = (empty($theme_options['theme_website_title']) ? '' : $theme_options['theme_website_title']);
	$website_url = (empty($theme_options['theme_website_url']) ? '' : $theme_options['theme_website_url']);
	$facebook_title = (empty($theme_options['theme_facebook_title']) ? '' : $theme_options['theme_facebook_title']);
	$facebook_url = (empty($theme_options['theme_facebook_url']) ? '' : $theme_options['theme_facebook_url']);
	$footer_name = (empty($theme_options['theme_footer_name']) ? '' : $theme_options['theme_footer_name']);
	$footer_address = (empty($theme_options['theme_footer_address']) ? '' : $theme_options['theme_footer_address']);

	if ($include_movies) {
		$meta = get_post_meta(get_the_ID());
		$movies_args = movies_query_args('comingsoon');
		$start_date = date('F j',strtotime('next '.get_theme_mod('movie_changeover_day', 'Friday').''));
		$end_date = date('F j, Y',strtotime('next '.get_theme_mod('movie_changeover_day', 'Friday').' +6 days'));
		$theme_subject = 'Movies playing '.$start_date.' to '.$end_date;
		$movies_query = new WP_Query($movies_args);
	}
?>
