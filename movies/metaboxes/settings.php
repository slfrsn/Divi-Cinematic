<?php

// =============================
// MOVIE PAGE SETTINGS META BOX
// =============================

function movie_page_settings_content($post) {
	wp_nonce_field(basename(__FILE__), 'movie_page_settings_nonce' );
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);
	if (!isset($meta['page_type']) && isset($meta['status'])) {
		$meta['page_type'][0] = $meta['status'][0];
	} else if (!isset($meta['page_type']) && !isset($meta['status'])) {
		$meta['page_type'][0] = 'nowplaying';
	}
?>

	<p>
		<label style="margin-right:4px;">
			<input type="radio" name="page_type" value="nowplaying" <?=echo_checkbox($meta['page_type'][0],'nowplaying')?>>
			Now Playing
		</label>
		<label>
			<input type="radio" name="page_type" value="comingsoon" <?=echo_checkbox($meta['page_type'][0],'comingsoon')?>>
			Coming Soon
		</label>
	</p>
	<script>
		document.getElementsByClassName('et_pb_toggle_builder_wrapper')[0].style.display = 'none';
	</script>
<?php }

// Saves the custom meta input
add_action('save_post', 'movie_page_settings_meta_save');
function movie_page_settings_meta_save($post_id) {
	// Check save status
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['movie_page_settings_nonce']) && wp_verify_nonce($_POST['movie_page_settings_nonce'], basename(__FILE__)) ? 'true' : 'false');
	// Exit script depending on save status
	if ($is_autosave || $is_revision || !$is_valid_nonce) { return; }
	// Check for input and sanitizes/saves if needed
	save_if_changed('page_type', $post_id, true);
}

?>
