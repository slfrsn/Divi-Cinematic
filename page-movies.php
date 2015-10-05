<?php

/* Template Name: Movie Page */

get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used(get_the_ID());

// ====================
// LOAD THE MOVIE LOOP
// ====================

global $post;
$meta = get_post_meta(get_the_ID());

// Now Playing
if ($meta['status'][0] == 'nowplaying') {
	$movies_args = array (
		'post_type'		  => 'movies',
		'meta_query'    => array(
			array(
				'key'   	  => 'start_date', //
				'value' 	  => strtotime('today'),
				'type' 		  => 'NUMERIC',
				'compare'   => '<=', // if start date is less than or equal to today.
			),
				array(
					'key'     => 'end_date',
					'value'   => strtotime('today'), // Thu, 19 Mar 2015 00:00:00 GMT
					'type' 		=> 'NUMERIC',
					'compare' => '>=', // if end date is greater than or equal to midnight today.
				)
		)
	);
// Coming Soon
} elseif ($meta['status'][0] == 'comingsoon' ) {
	$movies_args = array (
		'post_type'		=> 'movies',
		'meta_query'  => array(
			array(
				'key'   	=> 'start_date',
				'value' 	=> strtotime('now'),
				'type' 		=> 'NUMERIC',
				'compare' => '>', // value -> operator -> key
			),
			array(
				'key'   	=> 'start_date',
				'value' 	=> strtotime('-1 week'), // today minus one week
				'type' 		=> 'NUMERIC',
				'compare' => '>', // value -> operator -> key
			)
		)
	);
}

$movies_query = new WP_Query($movies_args); ?>

<div id="main-content" style="background-color: <?=et_get_option('secondary_nav_bg')?>">
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php
				$counter = 0;
				$count = $movies_query->post_count;
			?>

			<ul id="poster-row" style="width: <?=(($count < 4 && $count > 0) ? $count*30 : '100')?>%; margin: 0 auto;">

				<?php
				if ($movies_query->have_posts()) :
					while($movies_query->have_posts()) : $movies_query->the_post();
					++$counter;

					// Check if the number of movies is even or odd and calculate position adjustments.
					if ($count % 2 == 0) {
						$middle = $count / 2;
						if ($counter == $middle) {
							$top = 50;
							$left = 0;
							$zindex = 100;
							$scale = 1.3;
							$rotate = -5;
						} elseif ($counter == ($middle+1)) {
							$top = 50;
							$left = 0;
							$zindex = 100;
							$scale = 1.3;
							$rotate = 5;
						} elseif ($counter < $middle) {
							$top = ($middle-$counter)*(200 / ($count - 2))+50;
							$left = ($middle-$counter)*30;
							$zindex = 100-($middle-$counter);
							$rotate = ($middle-$counter)*-10-5;
							$scale = 1.3-(($middle-$counter)*0.1);
						} elseif ($counter > ($middle+1)) {
							$top = ($counter-($middle+1))*(200 / ($count - 2))+50;
							$left = ($counter-($middle+1))*-30;
							$zindex = 100-($counter-($middle+1));
							$rotate = ($counter-($middle+1))*10+5;
							$scale = 1.3-(($counter-$middle)*0.1);
						}
					} else {
						$middle = round($count / 2,0);
						if ($counter == $middle) {
							$top = 50;
							if ($count < 4) { $top = 60; }
							$left = 0;
							$zindex = 100;
							$rotate = 0;
							$scale = 1.3;
						} elseif ($counter < $middle) {
							$top = ($middle-$counter)*(300 / ($count - 1));
							$left = ($middle-$counter)*30;
							$zindex = 100-($middle-$counter);
							$rotate = ($middle-$counter)*-10;
							$scale = 1.3-(($middle-$counter)*0.1);
						} elseif ($counter > $middle) {
							$top = ($counter-$middle)*(300 / ($count - 1));
							$left = ($counter-$middle)*-30;
							$zindex = 100-($counter-$middle);
							$rotate = ($counter-($middle))*10;
							$scale = 1.3-(($counter-$middle)*0.1);
						}
					}

					if ($count < 4) { $scale = 1; }

					$css  = "width:".(100/$count)."%;";
					$css .= "top:".$top."px;";
					$css .= "left:".$left."px;";
					$css .= "z-index:".$zindex.";";
					$css .= "-webkit-transform:rotate(".$rotate."deg) scale(".$scale.");";
					$css .= "-moz-transform:rotate(".$rotate."deg) scale(".$scale.");";
					$css .= "-ms-transform:rotate(".$rotate."deg) scale(".$scale.");";
					$css .= "-o-transform:rotate(".$rotate."deg) scale(".$scale.");";
					$css .= "-moz-transition: all 0.".round((($top/50)+1))."s ease-in-out;";
					$css .= "-webkit-transition: all 0.".round((($top/50)+1))."s ease-in-out;";
					$css .= "transition: all 0.".round((($top/50)+1))."s ease-in-out;";

					?>

					<!-- Poster for <?php the_title(); ?> (post-<?php the_ID(); ?>) -->
					<li id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> poster count-<?=$count?>" style="<?=$css?>">
						<a href="#post-<?php the_ID(); ?>-details" class="details_popup">
							<?=get_the_post_thumbnail($post->ID, 'large', array('class' => ''))?>
						</a>
					</li>

					<!-- Details for <?php the_title(); ?> (post-<?php the_ID(); ?>) -->
					<div id="post-<?php the_ID(); ?>-details" class="white_popup mfp-hide">
						<?php
							$meta = get_post_meta(get_the_ID());
							$meta['genres'] = wp_get_post_terms(get_the_ID(), 'genre');
							$meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
							$meta['features'] = wp_get_post_terms(get_the_ID(), 'feature');
						?>
						<div class="white_popup_top" data-start="<?=$meta['start_date'][0]?>" data-end="<?=$meta['end_date'][0]?>">
							<div class="movie_poster">
								<?=get_the_post_thumbnail($post->ID, array(250, 370), array('class' => ''))?>
							</div>

							<div class="movie_details">
								<h2><?php the_title(); ?></h2>
								<p class="post-meta">
									<?php if(!empty($meta['rating'][0]) && $meta['rating'][0] != 'NA') {
										$rating_descriptions = [
											'G'   => 'Suitable for viewing by persons of all ages',
											'PG'  => 'Parental guidance advised',
											'14A' => 'Suitable for persons 14 years of age or older',
											'14A' => 'Persons under 18 years of age must view these motion pictures accompanied by an adult',
											'18A' => 'Restricted to persons 18 years of age and over'
										];
									?>
										<a href="http://www.consumerprotectionbc.ca/consumers-film-and-video-homepage/categoriesandadvisories" target="_blank" rel="external nofollow">
											<img src="<?=get_stylesheet_directory_uri().'/assets/images/ratings/'.$meta['rating'][0].'.png'?>" title="Rated <?=strtoupper($meta['rating'][0])?>: <?=$rating_descriptions[$meta['rating'][0]]?>" class="rating-image">
										</a>
										<?php } ?>
									<?=convert_to_string($meta['genres'], ' | ')?>
									<?=convert_to_string($meta['features'], ' | ')?>
									<?php if(isset($meta['website'][0]) && $meta['website_confirm'][0] != '') { echo '| <a href="'.$meta['website'][0].'" target="_blank" title="Official Website" rel="nofollow external">Official Website</a>'; } ?>
								</p>

								<div class="movie_info">
									<?php
										$comma = (empty($meta['early'][0]) ? '' : ', ');
										echo '<div class="showtimes" style="background-color: '.et_get_option('secondary_nav_bg').'"><strong>Showtimes for: </strong>'.date('F j',$meta['start_date'][0]).' - '.date('F j',$meta['end_date'][0]).'</div>';
									?>
									<div>
										<?php
											if(!empty($meta['early'][0]) || !empty($meta['late'][0])) { echo '<strong>Nightly: </strong>'.$meta['early'][0].$comma.$meta['late'][0].'<br>'; }
											if(!empty($meta['matinee'][0])) { echo '<strong>Weekend Matinees: </strong>'.$meta['matinee'][0].'<br>'; }
											if(!empty($meta['special_description'][0]) && !empty($meta['special_showtime'][0])) {
												echo '<strong>'.$meta['special_description'][0].': </strong>'.$meta['special_showtime'][0].'<br>';
											}
										?>
										<span class="movie_small_details">
										<?php
											if(!empty($meta['runtime_minutes'][0])) { echo '<strong>Runtime: </strong>'.$meta['runtime_minutes'][0].' min<br>'; }
											if(count($meta['advisories']) > 0) { echo '<strong>Advisories: </strong>'.convert_to_string($meta['advisories'], ', '); }
										?>
										</span>
									</div>
									<?php if(!empty($meta['notes'][0])) { echo '<div class="movie_notes">'.$meta['notes'][0].'</div>'; } ?>
								</div>
								<br>
								<?php if(!empty($meta['starring'][0])) { echo '<p><strong>Starring: </strong>'.$meta['starring'][0].'</p>'; } ?>
								<?php if(!empty($meta['description'][0])) { echo '<p><strong>Synopsis: </strong>'.$meta['description'][0].'</p>'; } ?>
							</div>
						</div>

						<?php if(isset($meta['trailer'][0]) && $meta['trailer_confirm'][0] != '') { ?>
						<div class="white_popup_bottom">
							<iframe width="900" height="400" src="<?=preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1",$meta['trailer'][0])?>?rel=0&amp;autohide=2&amp;iv_load_policy=3&amp;modestbranding=1&amp;color=white" frameborder="0" allowfullscreen></iframe>
						</div>
						<?php } ?>
					</div>

				</li>

			<?php
				// Clear $meta so it doesn't contaminate the other movie listings
				unset($meta);
				endwhile;
				else:
			?>
				<div class="no-movies">
					<h1><?php esc_html_e('No movies are currently listed','divi-cinematic'); ?></h1>
					<p><?php esc_html_e('Please check back later.','divi-cinematic'); ?></p>
				</div>
			<?php endif; ?>
			</ul><!-- .poster-row -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #page-container -->

<?php get_footer(); ?>
