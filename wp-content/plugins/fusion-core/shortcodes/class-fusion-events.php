<?php
class FusionSC_FusionEvents {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_shortcode('fusion_events', array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		$defaults =	shortcode_atts(
			array(
				'class'			=> '',
				'id'			=> '',
				'cat_slug'		=> '',
				'columns'		=> '4',				
				'number_posts'	=> '4',
				'picture_size'	=> 'cover'
			), $args
		);

		extract( $defaults );

		if( class_exists( 'Tribe__Events__Main' ) ) {

			$html = '';

			$args = array(
				'post_type' => 'tribe_events',
				'posts_per_page' => $number_posts,
			);

			if ( $cat_slug ) {
				$terms = explode( '|', $cat_slug );
				$args['tax_query'] = array(
					array(
						'taxonomy' 	=> 'tribe_events_cat',
						'field'    	=> 'slug',
						'terms'		=> array_map( 'trim', $terms ),
					),
				);
			}

			switch ( $columns ) {
				case '1':
					$column_class = 'full-one';
				break;
				case '2':
					$column_class = 'one-half';
				break;
				case '3':
					$column_class = 'one-third';
				break;
				case '4':
					$column_class = 'one-fourth';
				break;
				case '5':
					$column_class = 'one-fifth';
				break;
				case '6':
					$column_class = 'one-sixth';
				break;
			}

			$events = new WP_Query( $args );

			if ( $events->have_posts() ) {
				if( $id ) {
					$id = ' id="'  . $id . '"';
				}
				$html .= '<div class="fusion-events-shortcode ' . $class .'"' . $id . '>';
					$i = 1;
					$last = false;
					$columns = (int) $columns;

					while ( $events->have_posts() ) {
						$events->the_post();

						if ( $i == $columns ) {
							$last = true;
						}

						if ( $i > $columns ) {
							$i = 1;
							$last = false;
						}

						if( $columns == 1 ) {
							$last = true;
						}

						$html .= '<div class="fusion-' . $column_class . ' fusion-spacing-yes fusion-layout-column ' . ( ( $last ) ? 'fusion-column-last' : '' ) .'">';
							$html .= '<div class="fusion-column-wrapper">';
								$thumb_id = get_post_thumbnail_id();
								$thumb_link = wp_get_attachment_image_src( $thumb_id, 'full', true );
								$thumb_url = '';
								
								if ( has_post_thumbnail( get_the_ID() ) ) {
									$thumb_url = $thumb_link[0];
								} elseif ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
									$thumb_url = esc_url( trailingslashit( Tribe__Events__Pro__Main::instance()->pluginUrl ) . 'src/resources/images/tribe-related-events-placeholder.png' );
								}							
								
								$img_class = ( has_post_thumbnail( get_the_ID() ) ) ? '' : 'fusion-events-placeholder';
								
								if ( $thumb_url ) {
									if ( has_post_thumbnail( get_the_ID() ) && $picture_size == 'auto' ) {
										$thumb_img = get_the_post_thumbnail( get_the_ID(), 'full' );
									} else {
										$thumb_img = '<img class="' . $img_class . '" src="' . $thumb_url . '" alt="' . esc_attr( get_the_title( get_the_ID() ) ) . '" />';
									}
									$thumb_bg = '<span class="tribe-events-event-image" style="background-image: url(' . $thumb_url . '); -webkit-background-size: cover; background-size: cover; background-position: center center;"></span>';
								}
								$html .= '<div class="fusion-events-thumbnail hover-type-' . Avada()->settings->get( 'ec_hover_type' ) . '">';
									$html .='<a href="' . esc_url( tribe_get_event_link() ) . '" class="url" rel="bookmark">';
									
									if ( $thumb_url ) {
										if ( $picture_size == 'auto' ) {
											$html .= $thumb_img;
										} else {
											$html .= $thumb_bg;
										}
									} else {
										ob_start();
										/**
										 * avada_placeholder_image hook
										 *
										 * @hooked avada_render_placeholder_image - 10 (outputs the HTML for the placeholder image)
										 */
										do_action( 'avada_placeholder_image', 'fixed' );

										$placeholder = ob_get_clean();
										$html .= str_replace( 'fusion-placeholder-image', ' fusion-placeholder-image tribe-events-event-image', $placeholder );
									}
									
									$html .= '</a>';
								$html .= '</div>';
								$html .= '<div class="fusion-events-meta">';
									$html .= '<h2><a href="' . esc_url( tribe_get_event_link() ) . '" class="url" rel="bookmark">' . get_the_title() . '</a></h2>';
									$html .= '<h4>' . tribe_events_event_schedule_details() . '</h4>';
								$html .= '</div>';
							$html .= '</div>';
						$html .= '</div>';
						if( $last ) {
							$html .= '<div class="fusion-clearfix"></div>';
						}
						$i++;
					}
					wp_reset_query();
					$html .= '<div class="fusion-clearfix"></div>';
				$html .= '</div>';
			}
			
			return $html;
			
		}
	}

}

new FusionSC_FusionEvents();