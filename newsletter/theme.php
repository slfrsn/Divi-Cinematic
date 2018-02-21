<?php
  /*
   * Name: &bull; Divi Cinematic &bull;
   * Type: standard
   * This template was generated using MailChimp and needs to be optimized.
   */

  if (!defined('ABSPATH')) exit;

  global $newsletter, $post;
  include('variables.php');
  include('functions.php');
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">
			<?php include('style.php');?>
		</style>
	</head>
	<body>
		<center>
			<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tr>
          <td align="center" valign="top" id="bodyCell">
            <table border="0" cellpadding="0" cellspacing="0" width="600px" id="container">
              <?php
                include('partials/header.php');
                if ($include_movies) {
                  include('partials/subheader.php');
                  if ($movies_query->have_posts()) {
                    while($movies_query->have_posts()) {
                      $movies_query->the_post();
                      $meta = get_post_meta(get_the_ID());
                      $meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
                      include('partials/listing.php');
                      include('partials/divider.php');
                      unset($meta);
                    }
					$listing_count = $movies_query->post_count;
                  }
				    if ($include_scheduled) {
                      if ($scheduled_movies_query->have_posts()) {
                        while($scheduled_movies_query->have_posts()) {
                          $scheduled_movies_query->the_post();
                          $meta = get_post_meta(get_the_ID());
                          $meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
                          include('partials/listing.php');
                          include('partials/divider.php');
                          unset($meta);
                        }
						$listing_count = $scheduled_movies_query->post_count;
                      }
                    }
					if ($include_popups) {
                      if ($popups_movies_query->have_posts()) {
                        while($popups_movies_query->have_posts()) {
                          $popups_movies_query->the_post();
                          $meta = get_post_meta(get_the_ID());
                          $meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
                          include('partials/listing.php');
                          include('partials/divider.php');
                          unset($meta);
                        }
						$listing_count = $popups_movies_query->post_count;
                    }
					}
                    if ($include_widget) {
                      if ($widget_movies_query->have_posts()) {
                        while($widget_movies_query->have_posts()) {
                          $widget_movies_query->the_post();
                          $meta = get_post_meta(get_the_ID());
                          $meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
                          include('partials/listing.php');
                          include('partials/divider.php');
                          unset($meta);
                        }
						$listing_count = $widget_movies_query->post_count;
                    }
					if ($listing_count == 0) {
                    	include('partials/no-listings.php');
                    	include('partials/divider.php');
					}
 					}
                } else {
                  include('partials/divider.php');
                  include('partials/text.php');
                  include('partials/divider.php');
                }
                include('partials/follow.php');
                include('partials/footer.php');
              ?>
            </table>
          </td>
        </tr>
			</table>
		</center>
	</body>
</html>




<?php if (1 == 4) { ?>
  <tr>
    <td align="center" valign="top" id="bodyCell">
      <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
        <tr>
        <td align="center" valign="top" width="600" style="width:600px;">
      <![endif]-->
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
        <?php include('partials/header.php');?>
        <?php if($include_movies) include('partials/subheader.php');?>
        <?php if(!$include_movies) include('partials/no-listings.php');?>
        <tr>
          <td valign="top" id="templateBody">
            <?php
            if ($include_movies):
              if ($movies_query->have_posts()):
                while($movies_query->have_posts()): $movies_query->the_post();
                  $meta = get_post_meta(get_the_ID());
                  $meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
                  $title_id = preg_replace("/[^0-9a-zA-Z ]/m", "", get_the_title($post));
                  $title_id = preg_replace("/ /", "-", $title_id);
                  $title_id = strtolower($title_id);
                  include('partials/listing.php');
                  include('partials/divider.php');
                  unset($meta);
                endwhile;
              else:
                include('partials/no-listings.php');
                include('partials/divider.php');
              endif;
            endif;
            include('partials/follow.php');
            ?>
          </td>
        </tr>
        <?php include('partials/footer.php'); ?>
      </table>
      <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
      <![endif]-->
    </td>
  </tr>
<?php } ?>
