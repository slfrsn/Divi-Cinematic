<?php

// =============================
// FETCH MOVIE DETAILS META BOX
// =============================

function movie_fetch_details_content($post) {
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);
	if(empty($meta['json_response'])) { ?>
		<input type="text" id="movie_search" value="" placeholder="Search by movie title..."/>
		<div id="major-publishing-actions" style="overflow:hidden">
			<div id="publishing-action" class="fetch-details" style="width:100%">
				<span style="float:left;position:relative;top:3px">
					<span id="api_spinner" class="spinner"></span>
					<span id="api_status">Checking...</span>
				</span>
				<a class="button-primary" id="load_movie" data-url="<?=get_stylesheet_directory_uri().'/movies/api.php?status'?>">Search</a>
			</div>
		</div>
	<?php } else {
		echo '<span id="movie_details_saved">Movie details have been saved.</span>';
	} ?>
<?php } ?>
