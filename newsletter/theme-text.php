<?php
	if (!defined('ABSPATH')) exit;

	global $newsletter, $post;
	include('variables.php');

	if ($include_movies):
		if ($movies_query->have_posts()):
			echo $theme_subject."\r\n\r\n";
			while($movies_query->have_posts()): $movies_query->the_post();
				$meta = get_post_meta(get_the_ID());
				$meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
				$title_id = preg_replace("/[^0-9a-zA-Z ]/m", "", get_the_title($post));
				$title_id = preg_replace("/ /", "-", $title_id);
				$title_id = strtolower($title_id);

				the_title();
			  echo ' ('.$meta['rating'][0].')'."\r\n";
			  echo "------------------------------\r\n";
			  if(count($meta['advisories']) > 0):
			    echo convert_to_string($meta['advisories'], ', ').'\r\n';
			  endif;
			  if(!empty($meta['showtimes'][0])):
			    $showtimes = $meta['showtimes'][0];
			    $showtimes = htmlspecialchars_decode($showtimes);
			    $showtimes = preg_replace( '/^<[^>]+>|<\/[^>]+>$/', '', $showtimes );
			    $showtimes = preg_replace("/<br\W*?\/>/", "\r\n", $showtimes);
			    echo $showtimes."\r\n";
			  endif;
			  if(!empty($meta['description'][0])):
			    $description = $meta['description'][0];
			    $description = htmlspecialchars_decode($description);
			    $description = preg_replace('/^<[^>]+>|<\/[^>]+>$/', '', $description);
			    $description = preg_replace("/<br\W*?\/>/", "\r\n", $description);
			    echo '"'.$description.'"'."\r\n";
			  endif;
			  echo $comingsoon_url.'/#'.$title_id."\r\n\r\n";

				unset($meta);
			endwhile;
		else:
			echo "There are no movies listed. Please check back later.\r\n\r\n";
		endif;
	endif;
?>
This is a text-only version of our newsletter. You can view the full email online here: {email_url}

<?=$footer_name."\r\n"?>
<?=$footer_address."\r\n"?>

Want to change how you receive these emails?
You can update your preferences here: {profile_url} or unsubscribe here: {unsubscription_confirm_url}
