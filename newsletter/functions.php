<?php
	if (!defined('ABSPATH')) exit;

	function start_row($class, $align = 'center') {
		echo '<tr class="'.$class.'">
						<td align="'.$align.'" valign="top" style="'.padding('20px','20px','20px','20px').'">
							<table align="'.$align.'" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="min-width:100%;max-width:100%;">
								<tr>';
 	}
	function end_row() {
		echo '</tr>
				</table>
			</td>
		</tr>';
	}
	function padding($top, $right, $bottom, $left) {
		return 'padding-top:'.$top.';mso-padding-top-alt:'.$top.';
					 	padding-right:'.$right.';mso-padding-right-alt:'.$right.';
					 	padding-bottom:'.$bottom.';mso-padding-bottom-alt:'.$bottom.';
					 	padding-left:'.$left.';mso-padding-left-alt:'.$left.';';
	}
	function margin($top, $right, $bottom, $left) {
		return 'margin-top:'.$top.';mso-margin-top-alt:'.$top.';
					 	margin-right:'.$right.';mso-margin-right-alt:'.$right.';
					 	margin-bottom:'.$bottom.';mso-margin-bottom-alt:'.$bottom.';
					 	margin-left:'.$left.';mso-margin-left-alt:'.$left.';';
	}
	function format_text_block_plain($object, $property, $prefix, $suffix) {
		if(!empty($object[$property][0])):
			$value = $object[$property][0];
			$value = htmlspecialchars_decode($value);
			$value = preg_replace( '/^<[^>]+>|<\/[^>]+>$/', '', $value );
			$value = preg_replace("/<br\W*?\/>/", "\r\n", $value);
			echo $prefix.strip_tags($value).$suffix;
			unset($object);
		endif;
	}
	function format_text_block_html($object, $property) {
		if(!empty($object[$property][0])):
			echo '<p><span>';
			$value = $object[$property][0];
			$value = htmlspecialchars_decode($value);
			$value = preg_replace( '/^<[^>]+>|<\/[^>]+>$/', '', $value );
			echo $value;
			echo '</span></p>';
		endif;
	}

?>
