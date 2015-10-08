<!-- Details for <?php the_title(); ?> (post-<?php the_ID(); ?>) -->
<div id="post-<?php the_ID(); ?>-details" class="white_popup <?=(!is_singular('movies') ? 'mfp-hide' : '')?>">
	<?php
		$meta = get_post_meta(get_the_ID());
		$meta['genres'] = wp_get_post_terms(get_the_ID(), 'genre');
		$meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
		$meta['features'] = wp_get_post_terms(get_the_ID(), 'feature');
		$meta['info-bar'] = array_merge($meta['genres'],$meta['features']);
	?>
	<div class="white_popup_top" data-start="<?=$meta['start_date'][0]?>" data-end="<?=$meta['end_date'][0]?>">
		<div class="movie_poster">
			<?=get_the_post_thumbnail($post->ID, array(250, 370), array('class' => ''))?>
		</div>

		<div class="movie_details">
			<h2><?php the_title(); ?></h2>
			<p class="post-meta">
				<?php if(!empty($meta['rating'][0]) && $meta['rating'][0] != 'NA') {
					$rating_descriptions = [
						'G'   => 'Suitable for viewing by persons of all ages',
						'PG'  => 'Parental guidance advised',
						'14A' => 'Suitable for persons 14 years of age or older',
						'14A' => 'Persons under 18 years of age must view these motion pictures accompanied by an adult',
						'18A' => 'Restricted to persons 18 years of age and over'
					];
				?>
					<a href="http://www.consumerprotectionbc.ca/consumers-film-and-video-homepage/categoriesandadvisories" target="_blank" rel="external nofollow">
						<img src="<?=get_stylesheet_directory_uri().'/assets/images/ratings/'.$meta['rating'][0].'.png'?>" title="Rated <?=strtoupper($meta['rating'][0])?>: <?=$rating_descriptions[$meta['rating'][0]]?>" class="rating-image">
					</a>
					<?php }

					$taxonomies = array_merge($meta['genres'],$meta['features']);

					?>
				<?=convert_to_string($meta['info-bar'], ' | ')?>
				<?php if(isset($meta['website'][0]) && $meta['website_confirm'][0] != '') { echo '| <a href="'.$meta['website'][0].'" target="_blank" title="Official Website" rel="nofollow external">Official Website</a>'; } ?>
			</p>

			<div class="movie_info">
				<?php
					$comma = (empty($meta['early'][0]) ? '' : ', ');
					echo '<div class="showtimes" style="background-color: '.et_get_option('secondary_nav_bg').'"><strong>Showtimes for: </strong>'.date('F j',$meta['start_date'][0]).' - '.date('F j',$meta['end_date'][0]).'</div>';
				?>
				<div>
					<?php
						if(!empty($meta['early'][0]) || !empty($meta['late'][0])) { echo '<strong>Nightly: </strong>'.$meta['early'][0].$comma.$meta['late'][0].'<br>'; }
						if(!empty($meta['matinee'][0])) { echo '<strong>Weekend Matinees: </strong>'.$meta['matinee'][0].'<br>'; }
						if(!empty($meta['special_description'][0]) && !empty($meta['special_showtime'][0])) {
							echo '<strong>'.$meta['special_description'][0].': </strong>'.$meta['special_showtime'][0].'<br>';
						}
					?>
					<span class="movie_small_details">
					<?php
						if(!empty($meta['runtime_minutes'][0])) { echo '<strong>Runtime: </strong>'.$meta['runtime_minutes'][0].' min<br>'; }
						if(count($meta['advisories']) > 0) { echo '<strong>Advisories: </strong>'.convert_to_string($meta['advisories'], ', '); }
					?>
					</span>
				</div>
				<?php if(!empty($meta['notes'][0])) { echo '<div class="movie_notes">'.$meta['notes'][0].'</div>'; } ?>
			</div>
			<br>
			<?php if(!empty($meta['starring'][0])) { echo '<p><strong>Starring: </strong>'.$meta['starring'][0].'</p>'; } ?>
			<?php if(!empty($meta['description'][0])) { echo '<p><strong>Synopsis: </strong>'.$meta['description'][0].'</p>'; } ?>
		</div>
	</div>

	<?php if(isset($meta['trailer'][0]) && $meta['trailer_confirm'][0] != '') { ?>
	<div class="white_popup_bottom">
		<iframe width="900" height="400" src="<?=preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1",$meta['trailer'][0])?>?rel=0&amp;autohide=2&amp;iv_load_policy=3&amp;modestbranding=1&amp;color=white" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
</div>
