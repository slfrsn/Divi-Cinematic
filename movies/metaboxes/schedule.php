<?php

// =============================
// MOVIE SCHEDULE META BOX
// =============================

function movie_schedule_content($post) {
	wp_nonce_field(basename(__FILE__), 'movie_schedule_nonce');
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);

	// Custom editor settings
	$editor_options = array(
		'media_buttons' => false,
		'wpautop' => false,
		'textarea_rows' => 6,
		'quicktags' => false,
		'tinymce' => array(
      'toolbar1' => 'bold,italic,strikethrough,link,unlink'
		)
	);

	// Get the theme options for default listing times
	$defaults_early = get_theme_mod('divi-cinematic-early', '7:00 PM');
	$defaults_late = get_theme_mod('divi-cinematic-late', '9:00 PM');
	$defaults_matinee = get_theme_mod('divi-cinematic-matinee', '2:00 PM');

	// Check for the 'has_saved' flag
	if (!isset($meta['has_saved']) || !$meta['has_saved'][0]) {
		// Only use the default showtimes if the listing hasn't been saved yet
		// (to prevent the default showtimes from overwriting empty showtimes)
		$meta['early'][0] = $defaults_early;
		$meta['late'][0] = $defaults_late;
		$meta['matinee'][0] = $defaults_matinee;
  }
	// Determine start date
	if (isset($meta['start_date']) && ($meta['start_date'][0] != '')) {
		$meta['start_date'][0] = date('M. j, Y',$meta['start_date'][0]);
	} else {
		$meta['start_date'][0] = date('M. j, Y',strtotime('next Friday'));
	}
	// Determine end date
	if (isset($meta['end_date']) && ($meta['end_date'][0] != 246767472000)) {
		$meta['end_date'][0] = date('M. j, Y',$meta['end_date'][0]);
	} else {
		$meta['end_date'][0] = date('M. j, Y',strtotime('next Friday +6 days'));
	}

?>

	<h3>Active Period</h3>
	<p>Please enter the first and last date this movie will show on. You can use any date format you want, just remember to include the day, month and year.</p>
	<p>
		<label>Start Date: <input type="text" name="start_date" value="<?=$meta['start_date'][0]?>" placeholder="e.g. <?=date('M. j, Y',strtotime('next Friday'))?>"/></label>
		<label>End Date: <input type="text" name="end_date" value="<?=$meta['end_date'][0]?>" placeholder="e.g. <?=date('M. j, Y',strtotime('next Friday +6 days'))?>"/></label>
	</p>

	<h3>Special Listing</h3>
	<p>Selecting one of the following options will remove the listing from the Now Playing and Coming Soon pages.</p>
	<p class="radio-group">
		<label><input type="radio" name="listing_type" value="none" <?php checked(echo_var($meta['listing_type'][0], 'none'), 'none' ); ?>>None</label>
		<label><input type="radio" name="listing_type" value="popup" <?php checked(echo_var($meta['listing_type'][0]), 'popup' ); ?>>Show as Popup</label>
		<label><input type="radio" name="listing_type" value="widget" <?php checked(echo_var($meta['listing_type'][0]), 'widget' ); ?>>Show in Widget</label>
	</p>

	<h3>Showtimes</h3>
	<p>Enter your showtimes here. Basic formatting is allowed (bold, italic, links, etc.)</p>
	<p><?php wp_editor(htmlspecialchars_decode(echo_var($meta['showtimes'][0])), 'showtimes', $editor_options); ?></p>

	<h3>Notes</h3>
	<p>Notes are added to the bottom of the showtimes box in larger font and a contrasting background colour to grab attention.</p>
	<p><?php wp_editor(htmlspecialchars_decode(echo_var($meta['notes'][0])), 'notes', $editor_options); ?></p>

	<input type="hidden" id="post_id" value="<?=$post->ID?>">
	<input type="hidden" name="fetched" value="0">

<?php }

// Save the custom meta input
add_action('save_post', 'movie_schedule_meta_save');
function movie_schedule_meta_save($post_id) {
	// Check save status
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['movie_schedule_nonce']) && wp_verify_nonce($_POST['movie_schedule_nonce'], basename(__FILE__)) ? 'true' : 'false');
	// Exit script depending on save status
	if ($is_autosave || $is_revision || !$is_valid_nonce) { return; }
	// Grab the value of our hidden 'fetched' input
	// This is a boolean that should only return true if we're saving from the AJAX call
	$fetched = get_post_meta($post_id, 'fetched', true);
	if ($fetched) {
		update_post_meta($post_id, 'fetched', 0);
		return;
	}
	// Set a custom value to indicate the post has been saved at least once
	if (isset($_POST['post_title'])) {
		update_post_meta($post_id, 'has_saved', 1);
	}
	// Check for input and sanitizes/saves if needed
	$allowed_tags = '<b><strong><em><del><a><br><p>';
	if (!empty($_POST['showtimes'])) {
    $data=strip_tags(htmlspecialchars_decode($_POST['showtimes']), $allowed_tags);
    update_post_meta($post_id, 'showtimes', $data );
  }
	if (!empty($_POST['notes'])) {
    $data=strip_tags(htmlspecialchars_decode($_POST['notes']), $allowed_tags);
    update_post_meta($post_id, 'notes', $data );
  }
	if(isset($_POST['early'])) { update_post_meta($post_id, 'early', $_POST['early']); }
	if(isset($_POST['late'])) { update_post_meta($post_id, 'late', $_POST['late']); }
	if(isset($_POST['matinee'])) { update_post_meta($post_id, 'matinee', $_POST['matinee']); }
	if(isset($_POST['special_description'])) { update_post_meta($post_id, 'special_description', $_POST['special_description']); }
	if(isset($_POST['special_showtime'])) { update_post_meta($post_id, 'special_showtime', $_POST['special_showtime']); }
	if(isset($_POST['start_date'])) { update_post_meta($post_id, 'start_date', strtotime($_POST['start_date'])); }
	if(isset($_POST['end_date'])) { update_post_meta($post_id, 'end_date', strtotime($_POST['end_date'])); }
	if(!isset($_POST['end_date']) || $_POST['end_date'] == "") { update_post_meta($post_id, 'end_date', 246767472000); }
	update_post_meta($post_id, 'listing_type', (isset($_POST['listing_type']) ? $_POST['listing_type'] : 'none'));
}

?>
