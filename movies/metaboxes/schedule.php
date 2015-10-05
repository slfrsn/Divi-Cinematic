<?php

// =============================
// MOVIE SCHEDULE META BOX
// =============================

function movie_schedule_content($post) {
	wp_nonce_field(basename(__FILE__), 'movie_schedule_nonce');
	$post_id = $post->ID;
	$meta = get_post_meta($post_id);

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
		<label><span class="first-label">Start Date:</span>
			<input type="text" name="start_date" value="<?=$meta['start_date'][0]?>" placeholder="e.g. <?=date('M. j, Y',strtotime('next Friday'))?>"/>
		</label>
		<label><span>End Date:</span>
			<input type="text" name="end_date" value="<?=$meta['end_date'][0]?>" placeholder="e.g. <?=date('M. j, Y',strtotime('next Friday +6 days'))?>"/>
		</label>
	</p>

	<h3>Showtimes</h3>
	<p>Please enter the nightly and matinee show times in HH:MM PM format (e.g. 7:15 PM). For exceptions, please add them to the "Other times or notes..." field.</p>
	<p>
		<label><span class="first-label">Early:</span>
			<input type="text" name="early" value="<?=$meta['early'][0]?>" placeholder="e.g. <?=$defaults_early?>"/>
		</label>
		<label><span>Late:</span>
			<input type="text" name="late" value="<?=$meta['late'][0]?>" placeholder="e.g. <?=$defaults_late?>"/>
		</label>
	</p>

	<p><label><span class="first-label">Matinees:</span>
		<input type="text" name="matinee" value="<?=$meta['matinee'][0]?>" placeholder="e.g. <?=$defaults_matinee?>"/>
	</label></p>

	<p>
		<label><span class="first-label">Special:</span>
			<input type="text" name="special_description" value="<?=echo_var($meta['special_description'][0])?>" placeholder="Canada Day"/>
		</label>
		<label><span>&#64;</span>
			<input type="text" name="special_showtime" value="<?=echo_var($meta['special_showtime'][0])?>" placeholder="e.g. 2:00 PM"/>
		</label>
	</p>

	<p><label>Other times or notes...<br>
		<textarea name="notes" rows="5"><?=echo_var($meta['notes'][0])?></textarea>
	</label></p>

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
	if(isset($_POST['early'])) { update_post_meta($post_id, 'early', $_POST['early']); }
	if(isset($_POST['late'])) { update_post_meta($post_id, 'late', $_POST['late']); }
	if(isset($_POST['matinee'])) { update_post_meta($post_id, 'matinee', $_POST['matinee']); }
	if(isset($_POST['special_description'])) { update_post_meta($post_id, 'special_description', $_POST['special_description']); }
	if(isset($_POST['special_showtime'])) { update_post_meta($post_id, 'special_showtime', $_POST['special_showtime']); }
	if(isset($_POST['start_date'])) { update_post_meta($post_id, 'start_date', strtotime($_POST['start_date'])); }
	if(isset($_POST['end_date'])) { update_post_meta($post_id, 'end_date', strtotime($_POST['end_date'])); }
	if(!isset($_POST['end_date']) || $_POST['end_date'] == "") { update_post_meta($post_id, 'end_date', 246767472000); }
	save_if_changed('notes', $post_id, true);
}

?>
