<?php class DiviCinematicMovieWidget extends WP_Widget {

	function __construct(){
    $widget_ops = array(
      'classname' => 'divicinematicmovie_widget',
      'description' => 'A special movie listing widget with image fallback'
    );
  	parent::__construct( 'divicinematicmovie_widget', 'Divi Cinematic Movie Widget', $widget_ops);
	}

	// Front-end output
	function widget($args, $instance){
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : esc_html( $instance['title'] ) );
		$overlay = apply_filters( 'widget_overlay', empty( $instance['overlay'] ) ? '' : esc_html( $instance['overlay'] ) );
		$image = empty( $instance['image'] ) ? '' : esc_url( $instance['image'] );

		global $post;
		$meta = get_post_meta(get_the_ID());
		$widget_args = array (
			'post_type'  => 'movies',
			'meta_query' => array(
		    array(
		      'key'   => 'listing_type',
		      'value' => 'widget'
		    )
			)
		);
		$widget_query = new WP_Query($widget_args);

		if ($title) echo $before_title . $title . $after_title;
		if ($widget_query->have_posts()) : ?>
			<ul id="movie-widgets">
			<?php
				$counter = 0;
				$random = rand(1, $widget_query->post_count); // Only showing one post at time, so make it random

				while($widget_query->have_posts()) : $widget_query->the_post();
				++$counter;
			?>

				<li id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?>" style="<?=($counter != $random ? 'display:none' : '')?>">
					<a href="#post-<?php the_ID(); ?>-details" class="details_popup">
						<div>
							<h4><?=$instance['overlay']?></h4>
							Click for Details
						</div>
						<?=get_the_post_thumbnail($post->ID, 'medium', array('class' => ''))?>
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
		<?php endif;
	}

	// Saves the settings
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['overlay'] = sanitize_text_field($new_instance['overlay']);
		$instance['image'] = esc_url($new_instance['image']);
		return $instance;
	}

	// Back-end form
	function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'image'=>'', 'overlay'=>'' ));
		$title = esc_attr($instance['title']);
		$overlay = esc_attr($instance['overlay']);
		$image = esc_url($instance['image']);
  ?>
		<p>
      <p>Widget Title:</p>
      <input class="widefat" name="<?=$this->get_field_name( 'title')?>" type="text" value="<?=esc_attr($title)?>" />
      <p>Image Title Overlay:</p>
      <input class="widefat" name="<?=$this->get_field_name( 'overlay')?>" type="text" value="<?=esc_attr($overlay)?>" />
      <p>Enter the URL of an image you'd like to display when there are no special movies listed in the widget area:</p>
			<input class="widefat" name="<?=$this->get_field_name( 'image')?>" type="text" value="<?=esc_attr($image)?>" />
		</p>
  <?php
	}
}

function DiviCinematicMovieWidgetInit() {
	register_widget('DiviCinematicMovieWidget');
}

add_action('widgets_init', 'DiviCinematicMovieWidgetInit');
