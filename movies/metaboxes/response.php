<?php

// =============================
// RESPONSE META BOX
// =============================

function movie_response_content($post) {
	$meta = get_post_meta($post->ID);
	if (!empty($meta['json_response'])) {
		echo $meta['json_response'][0];
	} else {
		echo 'No response data.';
	}
}

?>
