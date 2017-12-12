<?php

// =============================
// MOVIE LINKS META BOX
// =============================

function movie_links_content($post) {
	wp_nonce_field(basename(__FILE__), 'movie_links_nonce');
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);
	$trailer = get_post_meta($post_id, 'trailer', true);
	$website = get_post_meta($post_id, 'website', true);
?>

	<a href="https://www.youtube.com/results?search_query=<?=urlencode($post->post_title)?>+trailer" target="_blank" class="button right">Search for Trailer</a>
	<h3>Trailer</h3>
	<p>Please enter the YouTube URL of an official movie trailer. </p>
	<p><input type="url" name="trailer" placeholder="Official trailer..." value="<?=$trailer?>" /></p>

	<label>
		<input type="checkbox" name="trailer_confirm" value="yes" <?=echo_checkbox($meta['trailer_confirm'][0],'yes')?>>
		Yes, this is the correct trailer for this movie.
	</label>

	<?php if (!empty($trailer) && $trailer != ' ') { ?>
	<div id="trailer-postbox" class="postbox-container">
		<div class="meta-box-sortables">
			<div class="postbox closed">
				<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: External Links</span><span class="toggle-indicator" aria-hidden="true"></span></button>
				<h3 class='hndle'><span>View Trailer</span></h3>
				<div id="trailer-inside" class="inside">
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<br><br>

	<a href="https://duckduckgo.com/?q=<?=urlencode($post->post_title)?>+official+website" target="_blank" class="button right">Search for Website</a>
	<h3>Website</h3>
	<p>Please enter the URL of the official movie website.</p>
	<p><input type="url" name="website" placeholder="Official website..." value="<?=$website?>" /></p>

	<label>
		<input type="checkbox" name="website_confirm" value="yes" <?=echo_checkbox($meta['website_confirm'][0],'yes')?>>
		Yes, this is the correct website for this movie.
	</label>

	<?php if (!empty($website)) { ?>
	<div id="website-postbox" class="postbox-container">
		<div class="meta-box-sortables">
			<div class="postbox closed">
				<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: External Links</span><span class="toggle-indicator" aria-hidden="true"></span></button>
				<h3 class='hndle'><span>View Website</span></h3>
				<div class="inside">
					<iframe src="" height="600" id="website-frame" frameborder="0" data-src="<?=$website?>"></iframe>
				</div>
			</div>
		</div>
	</div>
	<p><strong>Hint:</strong> Some websites won't load in a frame. If the preview isn't loading, try copying and pasting the website address into a new tab.</p>
<?php }
}

// Save the custom meta input
add_action('save_post', 'movie_links_meta_save');
function movie_links_meta_save($post_id) {
	// Check save status
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['movie_links_nonce']) && wp_verify_nonce($_POST['movie_links_nonce'], basename(__FILE__)) ? 'true' : 'false');
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
	save_if_changed('trailer', $post_id, true);
	save_if_changed('website', $post_id, true);
	save_if_changed('trailer_confirm', $post_id, true);
	save_if_changed('website_confirm', $post_id, true);
}

?>
