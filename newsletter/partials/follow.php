<?php
	if (!defined('ABSPATH')) exit;
	if ($disable_social) return;
?>
<?php start_row('content', 'center'); ?>
	<td valign="top" style="text-align:center">
		<p>
			<?php
				if (!empty($website_url)) echo '<a href="'.esc_attr($website_url).'">'.esc_attr($website_title).'</a>';
				if (!empty($website_url) && !empty($facebook_url)) echo ' | ';
				if (!empty($facebook_url)) echo '<a href="'.esc_attr($facebook_url).'">'.esc_attr($facebook_title).'</a>';
			?>
		</p>
	</td>
<?php end_row(); ?>
