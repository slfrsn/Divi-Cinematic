<?php

get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">

			<?php
				while ( have_posts() ) : the_post();
					include(locate_template('partials/movie.php'));
				// Clear $meta so it doesn't contaminate the other movie listings
				unset($meta);
				endwhile;
			?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
