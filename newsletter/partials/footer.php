<?php if (!defined('ABSPATH')) exit;?>
<?php start_row('footer', 'center'); ?>
	<td valign="top" style="text-align:center">
		<p>
			<strong><?=$footer_name?></strong><br>
			<?=$footer_address?><br>
			<br>
			Want to change how you receive these emails?<br>
			You can <a href="{profile_url}" target="_blank">update your preferences</a> or <a href="{unsubscription_confirm_url}" target="_blank">unsubscribe from this list</a>.<br>
			<br><a href="{email_url}" target="_blank">View this email in your browser</a>
		</p>
	</td>
<?php end_row(); ?>
