<!-- Popup for #<?=$post->post_name;?> -->
<div id="<?=$post->post_name;?>" class="white_popup <?=(!is_singular('movies') ? 'mfp-hide' : '')?>">
	<?php
		$meta = get_post_meta(get_the_ID());
		$meta['genres'] = wp_get_post_terms(get_the_ID(), 'genre');
		$meta['advisories'] = wp_get_post_terms(get_the_ID(), 'advisory');
		$meta['features'] = wp_get_post_terms(get_the_ID(), 'feature');
		$meta['info-bar'] = array_merge($meta['genres'],$meta['features']);
		$share_url = (isset($page_url) ? urlencode_percent($page_url.'?movie='.$post->post_name) : get_the_permalink());
	?>
	<div class="white_popup_top" data-start="<?=$meta['start_date'][0]?>" data-end="<?=$meta['end_date'][0]?>">
		<div class="movie_poster">
			<?=get_the_post_thumbnail($post->ID, array(250, 370), array('class' => ''))?>
		</div>
		<div class="movie_details">
			<h2><?=(empty($meta['listing_label'][0]) ? '' : '<strong>'.$meta['listing_label'][0].'</strong>: ')?><?php the_title(); ?></h2>
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
				<?php if(isset($meta['website'][0]) && !empty($meta['website_confirm'][0])) { echo '| <a href="'.$meta['website'][0].'" target="_blank" title="Official Website" rel="nofollow external">Official Website</a>'; } ?>
			</p>

			<div class="movie_info">
				<?php $comma = (empty($meta['early'][0]) ? '' : ', '); ?>
				<div class="showtimes" style="background-color: <?=et_get_option('secondary_nav_bg')?>">
					<strong>Showtimes for: </strong>
					<?php
						if(!empty($meta['showtime_override'][0])) {
						  $showtime_override = $meta['showtime_override'][0];
					    $showtime_override = htmlspecialchars_decode($showtime_override);
					    echo $showtime_override;
						} else {
							echo date('F j',$meta['start_date'][0]).' - '.date('F j',$meta['end_date'][0]);
						}
					?>
				</div>
				<div>
					<?php
						if(!empty($meta['showtimes'][0])) {
						  $showtimes = $meta['showtimes'][0];
					    $showtimes = htmlspecialchars_decode($showtimes);
					    echo $showtimes;
						}
					?>
					<span class="movie_small_details">
					<?php
						if(!empty($meta['runtime_minutes'][0])) { echo '<strong>Runtime: </strong>'.$meta['runtime_minutes'][0].' min<br>'; }
						if(count($meta['advisories']) > 0) { echo '<strong>Advisories: </strong>'.convert_to_string($meta['advisories'], ', '); }
					?>
					</span>
				</div>
					<?php
						if(!empty($meta['notes'][0])) {
						  $notes = $meta['notes'][0];
					    $notes = htmlspecialchars_decode($notes);
					    echo '<div class="movie_notes">'.$notes.'</div>';
						}
					?>
				</div>
			<br>
			<?php if(!empty($meta['starring'][0])) { echo '<p><strong>Starring: </strong>'.$meta['starring'][0].'</p>'; } ?>
			<?php if(!empty($meta['description'][0])) { echo '<p><strong>Synopsis: </strong>'.$meta['description'][0].'</p>'; } ?>

				<?php if (isset($GLOBALS['et_monarch'])) {
					$monarch_post_types = $GLOBALS['et_monarch']->monarch_options['sharing_inline_post_types'];
					if (isset($monarch_post_types) && in_array('movies', $monarch_post_types)) { ?>
						<div class="et_social_inline et_social_mobile_on et_social_inline_top">
							<div class="et_social_networks et_social_autowidth et_social_simple et_social_rectangle et_social_top et_social_no_animation et_social_nospace et_social_outer_dark">
								<span class="share_label">Share:</span>
								<ul class="et_social_icons_container">
									<li class="et_social_facebook">
										<a href="http://www.facebook.com/sharer.php?u=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="facebook" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank" title="Share this on Facebook">
											<i class="et_social_icon et_social_icon_facebook"></i>
											<span class="et_social_overlay"></span>
										</a>
									</li>
									<li class="et_social_twitter">
										<a href="http://twitter.com/share?url=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="twitter" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank" title="Share this on Twitter">
											<i class="et_social_icon et_social_icon_twitter"></i>
											<span class="et_social_overlay"></span>
										</a>
									</li>
									<li class="et_social_googleplus">
										<a href="https://plus.google.com/share?url=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="googleplus" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank" title="Share this on Google+">
											<i class="et_social_icon et_social_icon_googleplus"></i>
											<span class="et_social_overlay"></span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					<?php } ?>
				<?php } ?>

<!-- <div class="et_social_inline et_social_mobile_on et_social_inline_top">
	<div class="et_social_networks et_social_4col et_social_darken et_social_rectangle et_social_left et_social_no_animation et_social_nospace et_social_withnetworknames et_social_outer_dark">

		<ul class="et_social_icons_container">
			<li class="et_social_facebook">
				<a href="http://www.facebook.com/sharer.php?u=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="facebook" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank">
					<i class="et_social_icon et_social_icon_facebook"></i>
					<div class="et_social_network_label">
						<div class="et_social_networkname">
							Facebook
						</div>
					</div>
					<span class="et_social_overlay"></span>
				</a>
			</li>
			<li class="et_social_twitter">
				<a href="http://twitter.com/share?url=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="twitter" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank">
					<i class="et_social_icon et_social_icon_twitter"></i>
					<div class="et_social_network_label">
						<div class="et_social_networkname">
							Twitter
						</div>
					</div>
					<span class="et_social_overlay"></span>
				</a>
			</li>
			<li class="et_social_googleplus">
				<a href="https://plus.google.com/share?url=<?=$share_url?>" class="et_social_share" rel="nofollow" data-social_name="googleplus" data-post_id="<?=get_the_ID()?>" data-social_type="share" data-location="inline" target="_blank">
					<i class="et_social_icon et_social_icon_googleplus"></i>
					<div class="et_social_network_label">
						<div class="et_social_networkname">
							Google+
						</div>
					</div>
					<span class="et_social_overlay"></span>
				</a>
			</li>
		</ul>
	</div>
</div> -->

		</div>
	</div>

	<?php if(isset($meta['trailer'][0]) && !empty($meta['trailer_confirm'][0])) { ?>
	<div class="white_popup_bottom">
		<iframe width="900" height="400" src="<?=preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1",$meta['trailer'][0])?>?rel=0&amp;autohide=2&amp;iv_load_policy=3&amp;modestbranding=1&amp;color=white" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
</div>
