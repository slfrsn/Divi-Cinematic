<?php

// =============================
// MOVIE DETAILS META BOX
// =============================

function movie_details_content($post) {
	wp_nonce_field(basename(__FILE__), 'movie_details_nonce' );
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);
	$ratings_directory = get_stylesheet_directory_uri().'/assets/images/ratings';
?>

	<h3>Run time</h3>
	<p><input type="number" name="runtime_minutes" value="<?=echo_var($meta['runtime_minutes'][0])?>" /> minutes</p>

	<h3>Rating</h3>
	<p>
		<label for="rating-g">
			<input type="radio" name="rating" value="g" <?=echo_checkbox($meta['rating'][0],'g')?>>
			<img src="<?=$ratings_directory?>/g.png" class="rating-icon" title="G">
		</label>
		<label for="rating-pg">
			<input type="radio" name="rating" value="pg" <?=echo_checkbox($meta['rating'][0],'pg')?>>
			<img src="<?=$ratings_directory?>/pg.png" class="rating-icon" title="PG">
		</label>
		<label for="rating-14a">
			<input type="radio" name="rating" value="14a" <?=echo_checkbox($meta['rating'][0],'14a')?>>
			<img src="<?=$ratings_directory?>/14a.png" class="rating-icon" title="14A">
		</label>
		<label for="rating-18a">
			<input type="radio" name="rating" value="18a" <?=echo_checkbox($meta['rating'][0],'18a')?>>
			<img src="<?=$ratings_directory?>/18a.png" class="rating-icon" title="18A">
		</label>
		<label for="rating-r">
			<input type="radio" name="rating" value="r" <?=echo_checkbox($meta['rating'][0],'r')?>>
			<img src="<?=$ratings_directory?>/r.png" class="rating-icon" title="R">
		</label>
		<label for="rating-a">
			<input type="radio" name="rating" value="a" <?=echo_checkbox($meta['rating'][0],'a')?>>
			<img src="<?=$ratings_directory?>/a.png" class="rating-icon" title="A">
		</label>
	</p>

	<p>Please enter a rating description below.</p>
	<p><input type="text" name="rating_description" placeholder="Rating Description..." value="<?=echo_var($meta['rating_description'][0])?>" /></p>

	<h3>Cast</h3>
	<p><textarea name="starring" rows="5" placeholder="Starring..."><?=echo_var($meta['starring'][0])?></textarea></p>

	<h3>Description</h3>
	<p><textarea name="description" rows="5" placeholder="Description..."><?=echo_var($meta['description'][0])?></textarea></p>
	<input type="hidden" name="genres">

<?php }

// Saves the custom meta input
add_action('save_post', 'movie_details_meta_save');
function movie_details_meta_save($post_id) {
	// Check save status
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['movie_details_nonce']) && wp_verify_nonce($_POST['movie_details_nonce'], basename(__FILE__)) ? 'true' : 'false');
	// Exit script depending on save status
	if ($is_autosave || $is_revision || !$is_valid_nonce) { return; }
	// Grab the value of our hidden 'fetched' input
	// This is a boolean that should only return true if we're saving from the AJAX call
	$fetched = get_post_meta($post_id, 'fetched', true);
	if ($fetched) {
		update_post_meta($post_id, 'fetched', 0);
		return;
	}
	// Check for input and sanitizes/saves if needed
	save_if_changed('starring', $post_id, true);
	save_if_changed('rating', $post_id, false);
	save_if_changed('rating_description', $post_id, true);
	save_if_changed('runtime_minutes', $post_id, true);
	save_if_changed('description', $post_id, true);
	if(isset($_POST['genres'])) { // Run through each genre and apply it programmatically
		$set_genres = [];
		$genres_array = explode(',', $_POST['genres']);
		foreach ($genres_array as $genre) {
			$set_genres[] = $genre;
		}
		wp_set_object_terms($post_id, $set_genres, 'genre', true);
	}
}

?>
