<?php if (!defined('ABSPATH')) exit;?>
<?php start_row('content', 'left'); ?>
	<td valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td class="listingImage" align="center" valign="top">
        <?=get_the_post_thumbnail($post->ID, array(150,225))?>
      </td>
      <td class="listingContent" align="left" valign="top">
          <p style="margin-top: 0;">
						<strong>
							<?php the_title(); ?>
		          <?=(!empty($meta['rating']) ? '('.$meta['rating'][0].')' : '')?>
						</strong>
						<?=(count($meta['advisories']) > 0 ? '<br><em>'.convert_to_string($meta['advisories'], ', ').'</em>' : '')?>
          </p>
					<?php
          if(!empty($meta['showtimes'][0])):
            echo '<p><span>';
   					$showtimes = $meta['showtimes'][0];
   					$showtimes = htmlspecialchars_decode($showtimes);
   					$showtimes = preg_replace( '/^<[^>]+>|<\/[^>]+>$/', '', $showtimes );
   					echo $showtimes;
            echo '</span></p>';
   				endif;
          if(!empty($meta['description'][0])):
            echo '<p><span>';
   					$description = $meta['description'][0];
   					$description = htmlspecialchars_decode($description);
   					$description = preg_replace('/^<[^>]+>|<\/[^>]+>$/', '', $description);
   					echo $description;
            echo '</span></p>';
   				endif;
   				?>
          <table border="0" cellpadding="0" cellspacing="0">
          	<tbody>
          		<tr>
          			<td align="center" valign="middle" class="button">
                  <a href="<?=$comingsoon_url.'/#'.$post->post_name?>" style="color:<?=$accent_text_colour?>;" title="Watch Trailer">Watch Trailer</a>
                </td>
          		</tr>
          	</tbody>
          </table>
        </td>
      </tr>
    </table>
  </td>
<?php end_row(); ?>
