<?php

// ====================
// LOAD THE POPUPS LOOP
// ====================

global $post;
$meta = get_post_meta(get_the_ID());

// Movies designated as site pop-ups
$popups_args = array (
	'post_type'		  => 'movies',
	'meta_query'    => array(
    array(
      'key' 		=> 'is_popup',
      'value'   => 'yes'
    )
	)
);

$popups_query = new WP_Query($popups_args); ?>

	<?php if ($popups_query->have_posts()) : ?>
		<div class="et_pb_bg_layout_dark et_pb_text_align_center" id="movie-show-popups">
			<button class="et_pb_promo_button et_pb_button">Show Specials</button>
		</div>

		<ul id="movie-popups">
		<?php while($popups_query->have_posts()) : $popups_query->the_post(); ?>

			<li id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?>">
				<a href="#post-<?php the_ID(); ?>-details" class="details_popup mfp-hide"></a>
				<?php
					include(locate_template('partials/movie.php'));
					// Clear $meta so it doesn't contaminate the other movie listings
					unset($meta);
				?>
			</li>

		<?php endwhile; ?>
		</ul>
<?php endif; ?>
