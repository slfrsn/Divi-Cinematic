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
} elseif ($meta['status'][0] == 'comingsoon' ) {
	$movies_args = array (
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
}

// Movies designated as site pop-ups
$popups_args = array (
	'post_type'		  => 'movies',
	'meta_query'    => array(
		array(
			'key'   	  => 'start_date',
			'value' 	  => strtotime('today'),
			'type' 		  => 'NUMERIC',
			'compare'   => '<='
		),
		array(
			'key'       => 'end_date',
			'value'     => strtotime('today'),
			'type' 		  => 'NUMERIC',
			'compare'   => '>='
		),
    array(
	    'key' 		  => 'listing_type',
			'value'		  => 'popup',
			'compare'   => 'LIKE'
    )
	)
);

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
						<?php
							include(locate_template('partials/movie.php'));
							// Clear $meta so it doesn't contaminate the other movie listings
							unset($meta);
						?>
					</li>
				<?php
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
	<?php include(locate_template('partials/popups.php')); ?>
</div> <!-- #page-container -->

<?php get_footer(); ?>
