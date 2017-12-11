<?php class DiviCinematicMovieWidget extends WP_Widget {

	function __construct(){
    $widget_ops = array(
      'classname' => 'divicinematicmovie_widget',
      'description' => 'A special movie listing widget with image fallback'
    );
  	parent::__construct('divicinematicmovie_widget', 'Divi Cinematic Movie Widget', $widget_ops);
	}

	// Front-end output
	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title'] ) ? '' : esc_html($instance['title']));
		$check = empty($instance['check']) ?  0 : esc_attr($instance['check']);
		$image = empty($instance['image']) ? '' : esc_url($instance['image']);

		global $post;
		$widget_args = movies_query_args('widget');
		$widget_query = new WP_Query($widget_args);

		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;
		if ($widget_query->have_posts()) : ?>
			<ul id="movie-widgets">
			<?php
				$counter = 0;
				$random = rand(1, $widget_query->post_count); // Only showing one post at time, so make it random

				while($widget_query->have_posts()) : $widget_query->the_post();
				$meta = get_post_meta(get_the_ID());
				++$counter;
			?>
				<li id="<?=$post->post_name;?>-link" class="post-<?php the_ID(); ?>" style="<?=($counter != $random ? 'display:none' : '')?>">
					<a href="#<?=$post->post_name;?>" class="details_popup">
					<?php if (!empty($meta['listing_label'][0]) && $check == 1): ?>
						<div style="background-color: <?=et_get_option('primary_nav_bg')?>; border-color: <?=et_get_option('primary_nav_bg')?>">
							<h4><?=$meta['listing_label'][0]?></h4>
							Click for Details
						</div>
					<?php endif; ?>
						<?=get_the_post_thumbnail($post->ID, 'large', array('class' => ''))?>
					</a>
					<?php
						include(locate_template('partials/movie.php'));
						// Clear $meta so it doesn't contaminate the other movie listings
						unset($meta);
					?>
				</li>

			<?php endwhile; ?>
			</ul>

		<?php else: ?>
			<a href="<?=$instance['image']?>" class="et_pb_lightbox_image">
				<img src="<?=$instance['image']?>" class="et-waypoint et_pb_image et_pb_animation_off et-animated">
			</a>
		<?php
			endif;

			echo $after_widget;
	}

	// Saves the settings
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['check'] = esc_attr($new_instance['check']);
		$instance['image'] = esc_url($new_instance['image']);
		return $instance;
	}

	// Back-end form
	function form($instance){
		//Defaults
		$instance = wp_parse_args((array) $instance, array('title' => '', 'image'=>''));
		$title = esc_attr($instance['title']);
		$check = esc_attr($instance['check']);
		$image = esc_url($instance['image']);
  ?>
      <p>Widget Title:</p>
      <p><input class="widefat" name="<?=$this->get_field_name('title')?>" type="text" value="<?=esc_attr($title)?>" /></p>
      <label>
        <input class="widefat" name="<?=$this->get_field_name('check')?>" type="checkbox" value="1" <?php checked( '1', $check ); ?>/>
				Show the floating label tooltip
			</label>
      <p>Enter the URL of an image you'd like to display when there are no special movies listed in the widget area:</p>
			<p><input class="widefat" name="<?=$this->get_field_name('image')?>" type="text" value="<?=esc_attr($image)?>" /></p>
  <?php
	}
}

function DiviCinematicMovieWidgetInit() {
	register_widget('DiviCinematicMovieWidget');
}

add_action('widgets_init', 'DiviCinematicMovieWidgetInit');
