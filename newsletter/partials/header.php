<?php if (!defined('ABSPATH')) exit;?>
<?php start_row('header', 'center'); ?>
	<td valign="top" style="<?=padding(0,0,0,0)?>text-align:center;">
		<a href="<?=esc_attr($website_url)?>" title="<?=$footer_name?>" target="_blank">
			<?php if(!empty($header_image)): ?>
				<img align="center" alt="<?=$footer_name?>" src="<?=esc_attr($header_image)?>" width="300">
			<?php endif; ?>
		</a>
	</td>
<?php end_row(); ?>
