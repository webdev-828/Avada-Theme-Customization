<?php

class Fusion_Widget_Recent_Works extends WP_Widget {

	function __construct() {

		$widget_ops = array('classname' => 'recent_works', 'description' => 'Recent works from the portfolio.');
		$control_ops = array('id_base' => 'recent_works-widget');

		parent::__construct('recent_works-widget', 'Avada: Recent Works', $widget_ops, $control_ops);

	}

	function widget( $args, $instance ) {

		extract( $args );

		$title  = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$number = isset( $instance['number'] ) ? $instance['number'] : 6;

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<div class="recent-works-items clearfix">
			<?php

			$args = array(
				'post_type'      => 'avada_portfolio',
				'posts_per_page' => $number,
				'has_password'   => false
			);
			$portfolio = new WP_Query( $args );
			?>

			<?php if ( $portfolio->have_posts() ) : ?>
				<?php while( $portfolio->have_posts() ) : $portfolio->the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php $url_check     = get_post_meta( get_the_ID(), 'pyre_link_icon_url', true ); ?>
						<?php $new_permalink = ( ! empty( $url_check ) ) ? get_post_meta( get_the_ID(), 'pyre_link_icon_url', true ) : get_permalink(); ?>
						<?php $link_target   = ( 'yes' == get_post_meta( get_the_ID(), 'pyre_link_icon_target', true ) ) ? ' target="_blank"' : ''; ?>

						<a href="<?php echo $new_permalink; ?>"<?php echo $link_target; ?> title="<?php the_title(); ?>">
							<?php the_post_thumbnail('recent-works-thumbnail'); ?>
						</a>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
		</div>
		<?php

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = $new_instance['number'];

		return $instance;

	}

	function form( $instance ) {

		$defaults = array(
			'title'  => __( 'Recent Works', 'Avada' ),
			'number' => 6
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of items to show:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<?php

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
