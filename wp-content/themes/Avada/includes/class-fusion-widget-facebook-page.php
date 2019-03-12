<?php

class Fusion_Widget_Facebook_Page extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'facebook_like',
			'description' => __( 'Adds support for Facebook Page Plugin.', 'Avada' )
		);
		$control_ops = array( 'id_base' => 'facebook-like-widget' );

		parent::__construct( 'facebook-like-widget', 'Avada: Facebook Page Plugin', $widget_ops, $control_ops );

	}

	public function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$language     = get_locale();
		$page_url     = isset( $instance['page_url'] ) ? $instance['page_url'] : '';
		$widget_width = isset( $instance['width'] ) ? $instance['width'] : 268;
		$show_faces   = isset( $instance['show_faces'] ) ? 'true' : 'false';
		$show_stream  = isset( $instance['show_stream'] ) ? 'true' : 'false';
		$show_header  = isset( $instance['show_header'] ) ? 'false' : 'true';
		$small_header  = isset( $instance['small_header'] ) ? 'true' : 'false';
		$height       = '65';

		$height = ( 'true' == $show_faces ) ? '240' : $height;
		$height = ( 'true' == $show_stream ) ? '515' : $height;
		$height = ( 'true' == $show_stream && 'true' == $show_faces && 'true' == $show_header ) ? '540' : $height;
		$height = ( 'true' == $show_stream && 'true' == $show_faces && 'false' == $show_header ) ? '540' : $height;
		$height = ( 'true' == $show_header ) ? $height + 30 : $height;

		echo $before_widget;

		if ( ! $language ) {
			$language = 'en_EN';
		}

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<?php if ( $page_url ) : ?>

			<script>
			(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/<?php echo $language; ?>/sdk.js#xfbml=1&version=v2.5";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			window.fbAsyncInit = function() {
				fusion_resize_page_widget();

				jQuery( window ).resize(function() {
					fusion_resize_page_widget();
				});

				function fusion_resize_page_widget() {
					var $container_width = jQuery( '.<?php echo $args['widget_id']; ?>' ).width();

					if ( $container_width != jQuery('.<?php echo $args['widget_id']; ?> .fb-page' ).data( 'width' ) ) {
						jQuery('.<?php echo $args['widget_id']; ?> .fb-page' ).attr( 'data-width', $container_width );
						FB.XFBML.parse();
					}
				}
			}
			</script>

			<div class="fb-like-box-container <?php echo $args['widget_id']; ?>" id="fb-root">
				<div class="fb-page" data-href="<?php echo $page_url; ?>" data-width="<?php echo $widget_width; ?>" data-adapt-container-width="true" data-small-header="<?php echo $small_header; ?>" data-height="<?php echo $height; ?>" data-hide-cover="<?php echo $show_header; ?>" data-show-facepile="<?php echo $show_faces; ?>" data-show-posts="<?php echo $show_stream; ?>"></div>
			</div>
		<?php endif;

		echo $after_widget;

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['page_url']     = $new_instance['page_url'];
		$instance['width']        = $new_instance['width'];
		$instance['show_faces']   = $new_instance['show_faces'];
		$instance['show_stream']  = $new_instance['show_stream'];
		$instance['show_header']  = $new_instance['show_header'];
		$instance['small_header']  = $new_instance['small_header'];

		return $instance;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'        => __( 'Find us on Facebook', 'Avada' ),
			'page_url'     => '',
			'width'        => '268',
			'show_faces'   => 'on',
			'show_stream'  => false,
			'show_header'  => false,
			'small_header' => false
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<h4 style="line-height: 1.6em;"><?php _e( 'IMPORTANT: Facebook has abandoned color scheme option for the page plugin, thus the option is also no longer available in the widget.', 'Avada' ); ?></a></h4>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'page_url' ); ?>"><?php _e( 'Facebook Page URL:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'page_url' ); ?>" name="<?php echo $this->get_field_name( 'page_url' ); ?>" value="<?php echo $instance['page_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width (has to be between 180 and 500):', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 80px;" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_faces'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_faces' ); ?>" name="<?php echo $this->get_field_name( 'show_faces' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e( 'Show Friends Faces', 'Avada' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_stream'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_stream' ); ?>" name="<?php echo $this->get_field_name( 'show_stream' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_stream' ); ?>"><?php _e( 'Show Posts', 'Avada' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_header'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_header' ); ?>" name="<?php echo $this->get_field_name( 'show_header' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>"><?php _e( 'Show Cover Photo', 'Avada' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['small_header'], 'on' ); ?> id="<?php echo $this->get_field_id( 'small_header' ); ?>" name="<?php echo $this->get_field_name( 'small_header' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'small_header' ); ?>"><?php _e( 'Use Small Header', 'Avada' ); ?></label>
		</p>
	<?php
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
