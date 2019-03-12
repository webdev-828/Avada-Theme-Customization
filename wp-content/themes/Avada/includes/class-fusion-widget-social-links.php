<?php

class Fusion_Widget_Social_Links extends WP_Widget {

	public function __construct() {

		$widget_ops  = array( 'classname' => 'social_links', 'description' => '' );
		$control_ops = array( 'id_base' => 'social_links-widget' );

		parent::__construct( 'social_links-widget', 'Avada: Social Links', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$add_class = '';
		$style     = '';
		$nofollow  = ( Avada()->settings->get( 'nofollow_social_links' ) ) ? ' rel="nofollow"' : '';
		$to_social_networks = Avada()->settings->get( 'social_media_icons' );

		if ( ! isset( $instance['tooltip_pos'] ) || '' == $instance['tooltip_pos'] ) {
			$instance['tooltip_pos'] = 'top';
		}

		if ( ! isset( $instance['icon_color'] ) || '' == $instance['icon_color'] ) {
			$instance['icon_color'] = '#bebdbd';
		}

		if ( ! isset( $instance['boxed_icon'] ) || '' == $instance['boxed_icon'] ) {
			$instance['boxed_icon'] = 'Yes';
		}

		if ( ! isset( $instance['boxed_color'] ) || '' == $instance['boxed_color'] ) {
			$instance['boxed_color'] = '#e8e8e8';
		}

		if ( ! isset( $instance['boxed_icon_radius'] ) || '' == $instance['boxed_icon_radius'] ) {
			$instance['boxed_icon_radius'] = '4px';
		}

		if ( ! isset($instance['linktarget']) || '' == $instance['linktarget'] ) {
			$instance['linktarget'] = '_self';
		}

		if ( ! isset( $instance['color_type'] ) || '' == $instance['color_type'] ) {
			$instance['color_type'] = 'custom';
		}

		if ( isset( $instance['boxed_icon'] ) && isset( $instance['boxed_icon_radius'] ) && 'Yes' == $instance['boxed_icon'] && ( $instance['boxed_icon_radius'] || '0' === $instance['boxed_icon_radius'] ) ) {
			$instance['boxed_icon_radius'] = ( 'round' == $instance['boxed_icon_radius'] ) ? '50%' : $instance['boxed_icon_radius'];
			$style .= 'border-radius:' . $instance['boxed_icon_radius'] . ';';
		}

		if ( isset( $instance['boxed_icon'] )  && 'Yes' == $instance['boxed_icon'] && isset( $instance['boxed_icon_padding'] )  && isset( $instance['boxed_icon_padding'] ) ) {
			$style .= 'padding:' . $instance['boxed_icon_padding'] . ';';
		}

		if ( isset( $instance['boxed_icon'] ) && 'Yes' == $instance['boxed_icon'] ) {
			$add_class .= ' boxed-icons';
		}

		if ( ! isset( $instance['icons_font_size'] ) || '' == $instance['icons_font_size'] ) {
			$instance['icons_font_size'] = '16px';
		}

		$style .= 'font-size:' . $instance['icons_font_size'] . ';';

		$social_networks = array();
		foreach ( $instance as $name => $value ) {

			if ( false !== strpos( $name, '_link' ) && $value ) {
				$new_value = str_replace( '_link', '', $name );

				if ( 'facebook' == $new_value ) {
					$new_value = 'fb';
				} elseif ( 'gplus' == $new_value ) {
					$new_value = 'google';
				}
				$social_networks[ $name ] = $new_value;
			}
		}

		$social_networks_ordered = array();
		
		if ( $to_social_networks && 0 < count( $to_social_networks['avadaredux_repeater_data'] ) ) {
			// Loop through the set of social networks and order them according to the Theme Options > Social Media tab ordering
			// Append those icons that are not set in Theme Options at the end
			foreach ( $social_networks as $name => $value ) {

				if ( 'fb' == $value ) {
					$compare_value = 'facebook';
				} elseif ( 'google' == $value ) {
					$compare_value = 'gplus';
				} else {
					$compare_value = $value;
				}

				$social_network_position = array_search( $compare_value, $to_social_networks['icon'] );

				if ( $social_network_position || 0 === $social_network_position ) {
					$social_networks_ordered[ $social_network_position ] = $name;
					unset( $social_networks[ $name ] );
				} else {
					$social_networks[ $name ] = $value . '_link';
				}
			}

			// Make sure all custom icons from Theme Options > Social Media tab are included, if the widget option is set
			if ( isset( $instance['show_custom'] ) && 'Yes' == $instance['show_custom'] ) {
				$custom_icon_indices = array_keys( $to_social_networks['icon'], 'custom' );

				foreach ( $custom_icon_indices as $name => $index ) {

					$network_icon_height = $to_social_networks['custom_source'][$index]['height'];
					$network_icon_width	= $to_social_networks['custom_source'][$index]['width'];

					$social_networks_ordered[ $index ] = array(
						'network_name'			=> $to_social_networks['custom_title'][$index],
						'network_icon'			=> $to_social_networks['custom_source'][$index]['url'],
						'network_icon_height'	=> $network_icon_height,
						'network_icon_width'	=> $network_icon_width,
						'network_link'			=> $to_social_networks['url'][$index],
					);
				}
			}
		}
		ksort( $social_networks_ordered );
		$social_networks_ordered = array_merge( $social_networks_ordered, $social_networks );

		$icon_colors     = array();
		$icon_colors_max = 1;

		if ( isset( $instance['icon_color'] ) && $instance['icon_color'] ) {
			$icon_colors     = explode( '|', $instance['icon_color'] );
			$icon_colors_max = count( $icon_colors );
		}

		$box_colors     = array();
		$box_colors_max = 1;

		if ( isset( $instance['boxed_color'] ) && $instance['boxed_color'] ) {
			$box_colors     = explode( '|', $instance['boxed_color'] );
			$box_colors_max = count( $box_colors );
		}

		echo $before_widget;

		if ( $title ) :
			echo $before_title . $title . $after_title;
		endif;
		?>

		<div class="fusion-social-networks<?php echo $add_class; ?>">

			<div class="fusion-social-networks-wrapper">
				<?php
				$icon_color_count = 0;
				$box_color_count  = 0;

				foreach ( $social_networks_ordered as $name => $value ) {

					if ( is_string( $value ) ) {
						$name = $value;
						$value = str_replace( '_link', '', $value );

						$value = ( 'fb' == $value ) ? 'facebook' : $value;
						$value = ( 'google' == $value ) ? 'googleplus' : $value;
						$value = ( 'email' == $value ) ? 'mail' : $value;

						$tooltip = $value;
						$tooltip = ( 'googleplus' == $tooltip ) ? 'Google+' : $tooltip;
					} else {
						$tooltip = $value['network_name'];
					}

					$icon_style = '';
					$box_style  = '';

					if ( 'brand' == $instance['color_type'] ) {
						// if not custom social icon
						if ( is_string( $value ) ) {
							// Get a list of all the available social networks
							$social_icon_boxed_colors  = Avada_Data::fusion_social_icons( false, true );
							$social_icon_boxed_colors['googleplus'] = array( 'label' => 'Google+', 'color' => '#dc4e41' );
							$social_icon_boxed_colors['mail'] = array( 'label' => esc_html__( 'Email Address', 'fusion-core' ), 'color' => '#000000' );

							$color = ( 'Yes' == $instance['boxed_icon'] ) ? '#ffffff' : $social_icon_boxed_colors[$value]['color'];
							$bg_color = ( 'Yes' == $instance['boxed_icon'] ) ? $social_icon_boxed_colors[$value]['color'] : '';

							$icon_style = 'color:' . $color . ';';
							$box_style = 'background-color:' . $bg_color . ';border-color:' . $bg_color . ';';
						}
					} else {
						if ( isset( $icon_colors[ $icon_color_count ] ) && $icon_colors[ $icon_color_count ] ) {
							$icon_style = 'color:' . trim( $icon_colors[ $icon_color_count ] ) . ';';
						} elseif ( isset( $icon_colors[ ( $icon_colors_max - 1 ) ] ) ) {
							$icon_style = 'color:' . trim( $icon_colors[ ( $icon_colors_max - 1 ) ] ) . ';';
						}

						if ( isset( $instance['boxed_icon'] ) && 'Yes' == $instance['boxed_icon'] && isset( $box_colors[ $box_color_count ] ) && $box_colors[ $box_color_count ] ) {
							$box_style = 'background-color:' . trim( $box_colors[ $box_color_count ] ) . ';border-color:' . trim( $box_colors[ $box_color_count ] ) . ';';
						} elseif ( isset( $instance['boxed_icon'] ) && 'Yes' == $instance['boxed_icon'] && isset( $box_colors[ ( $box_colors_max - 1 ) ] ) && ( ! isset( $box_colors[ $box_color_count ] ) || ! $box_colors[ $box_color_count ] ) ) {
							$box_style = 'background-color:' . trim( $box_colors[ ( $box_colors_max - 1 ) ] ) . ';border-color:' . trim( $box_colors[ ( $box_colors_max - 1 ) ] ) . ';';
						}
					}

					$tooltip_params = ' ';
					if ( 'none' != strtolower( $instance['tooltip_pos'] ) ) {
						$tooltip_params = sprintf( ' data-placement="%s" data-title="%s" data-toggle="tooltip" data-original-title="" ', strtolower( $instance['tooltip_pos'] ), ucwords( $tooltip ) );
					}

					if ( is_string( $value ) ) : ?>
						<a class="fusion-social-network-icon fusion-tooltip fusion-<?php echo $value; ?> fusion-icon-<?php echo $value; ?>" href="<?php echo $instance[ $name ]; ?>"<?php echo $tooltip_params; ?>title="<?php echo ucwords( $tooltip ); ?>" <?php echo $nofollow; ?> target="<?php echo $instance['linktarget']; ?>" style="<?php echo $style; echo $icon_style; echo $box_style; ?>"></a>

					<?php else : ?>

						<a class="fusion-social-network-icon fusion-tooltip" target="<?php echo $instance['linktarget']; ?>" href="<?php echo $value['network_link']; ?>"<?php echo $nofollow; echo $tooltip_params; ?>title="" style="<?php echo $style; ?>"><img src="<?php echo $value['network_icon']; ?>" height="<?php echo $value['network_icon_height']; ?>" width="<?php echo $value['network_icon_width']; ?>" alt="<?php echo $value['network_name']; ?>" /></a>
					<?php endif;

					$icon_color_count++;
					$box_color_count++;

				}
				?>
			</div>
		</div>

		<?php echo $after_widget;

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']              = $new_instance['title'];
		$instance['linktarget']         = $new_instance['linktarget'];
		$instance['icons_font_size']    = $new_instance['icons_font_size'];
		$instance['icon_color']         = $new_instance['icon_color'];
		$instance['boxed_icon']         = $new_instance['boxed_icon'];
		$instance['boxed_color']        = $new_instance['boxed_color'];
		$instance['color_type']         = $new_instance['color_type'];
		$instance['boxed_icon_radius']  = $new_instance['boxed_icon_radius'];
		$instance['boxed_icon_padding'] = $new_instance['boxed_icon_padding'];
		$instance['tooltip_pos']        = $new_instance['tooltip_pos'];
		$instance['show_custom']        = $new_instance['show_custom'];
		$instance['fb_link']            = $new_instance['fb_link'];
		$instance['flickr_link']        = $new_instance['flickr_link'];
		$instance['rss_link']           = $new_instance['rss_link'];
		$instance['twitter_link']       = $new_instance['twitter_link'];
		$instance['vimeo_link']         = $new_instance['vimeo_link'];
		$instance['youtube_link']       = $new_instance['youtube_link'];
		$instance['instagram_link']     = $new_instance['instagram_link'];
		$instance['pinterest_link']     = $new_instance['pinterest_link'];
		$instance['tumblr_link']        = $new_instance['tumblr_link'];
		$instance['google_link']        = $new_instance['google_link'];
		$instance['dribbble_link']      = $new_instance['dribbble_link'];
		$instance['digg_link']          = $new_instance['digg_link'];
		$instance['linkedin_link']      = $new_instance['linkedin_link'];
		$instance['blogger_link']       = $new_instance['blogger_link'];
		$instance['skype_link']         = $new_instance['skype_link'];
		$instance['forrst_link']        = $new_instance['forrst_link'];
		$instance['myspace_link']       = $new_instance['myspace_link'];
		$instance['deviantart_link']    = $new_instance['deviantart_link'];
		$instance['yahoo_link']         = $new_instance['yahoo_link'];
		$instance['reddit_link']        = $new_instance['reddit_link'];
		$instance['paypal_link']        = $new_instance['paypal_link'];
		$instance['dropbox_link']       = $new_instance['dropbox_link'];
		$instance['soundcloud_link']    = $new_instance['soundcloud_link'];
		$instance['vk_link']            = $new_instance['vk_link'];
		$instance['xing_link']          = $new_instance['xing_link'];
		$instance['email_link']         = $new_instance['email_link'];

		return $instance;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'              => __( 'Get Social', 'Avada' ),
			'linktarget'         => '',
			'icons_font_size'    => '16px',
			'icon_color'         => '',
			'boxed_icon'         => 'No',
			'boxed_color'        => '',
			'color_type'         => 'custom',
			'boxed_icon_radius'  => '4px',
			'boxed_icon_padding' => '8px',
			'tooltip_pos'        => 'top',
			'rss_link'           => '',
			'fb_link'            => '',
			'twitter_link'       => '',
			'dribbble_link'      => '',
			'google_link'        => '',
			'linkedin_link'      => '',
			'blogger_link'       => '',
			'tumblr_link'        => '',
			'reddit_link'        => '',
			'yahoo_link'         => '',
			'deviantart_link'    => '',
			'vimeo_link'         => '',
			'youtube_link'       => '',
			'pinterest_link'     => '',
			'digg_link'          => '',
			'flickr_link'        => '',
			'forrst_link'        => '',
			'myspace_link'       => '',
			'skype_link'         => '',
			'instagram_link'     => '',
			'vk_link'            => '',
			'xing_link'          => '',
			'dropbox_link'       => '',
			'soundcloud_link'    => '',
			'paypal_link'        => '',
			'email_link'         => '',
			'show_custom'        => 'No',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'linktarget' ); ?>"><?php _e( 'Link Target:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'linktarget' ); ?>" name="<?php echo $this->get_field_name( 'linktarget' ); ?>" value="<?php echo $instance['linktarget']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'icons_font_size' ); ?>"><?php _e( 'Icons Font Size:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'icons_font_size' ); ?>" name="<?php echo $this->get_field_name( 'icons_font_size' ); ?>" value="<?php echo $instance['icons_font_size']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'color_type' ); ?>"><?php _e( 'Icons Color Type:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'color_type' ); ?>" name="<?php echo $this->get_field_name( 'color_type' ); ?>" class="widefat" style="width:100%;">
				<option value="custom" <?php echo ( 'custom' == $instance['color_type'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Custom Color', 'Avada' ); ?></option>
				<option value="brand" <?php echo ( 'brand' == $instance['color_type'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Brand Colors', 'Avada' ); ?></option>
			</select>
		</p>

		<p class="avada-widget-color-type-option-child">
			<label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php _e( 'Icons Color Hex Code:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" value="<?php echo $instance['icon_color']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'boxed_icon' ); ?>"><?php _e( 'Icons Boxed:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'boxed_icon' ); ?>" name="<?php echo $this->get_field_name( 'boxed_icon' ); ?>" class="widefat" style="width:100%;">
				<option value="No" <?php echo ( 'No' == $instance['boxed_icon'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'No', 'Avada' ); ?></option>
				<option value="Yes" <?php echo ( 'Yes' == $instance['boxed_icon'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Yes', 'Avada' ); ?></option>
			</select>
		</p>

		<p class="avada-widget-color-type-option-child avada-widget-boxed-icon-background">
			<label for="<?php echo $this->get_field_id( 'boxed_color' ); ?>"><?php _e( 'Boxed Icons Background Color Hex Code:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'boxed_color' ); ?>" name="<?php echo $this->get_field_name( 'boxed_color' ); ?>" value="<?php echo $instance['boxed_color']; ?>" />
		</p>

		<p class="avada-widget-boxed-icon-option-child">
			<label for="<?php echo $this->get_field_id( 'boxed_icon_radius' ); ?>"><?php _e( 'Boxed Icons Radius:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'boxed_icon_radius' ); ?>" name="<?php echo $this->get_field_name( 'boxed_icon_radius' ); ?>" value="<?php echo $instance['boxed_icon_radius']; ?>" />
		</p>

		<p class="avada-widget-boxed-icon-option-child">
			<label for="<?php echo $this->get_field_id( 'boxed_icon_padding' ); ?>"><?php _e( 'Boxed Icons Padding:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'boxed_icon_padding' ); ?>" name="<?php echo $this->get_field_name( 'boxed_icon_padding' ); ?>" value="<?php echo $instance['boxed_icon_padding']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tooltip_pos' ); ?>"><?php _e( 'Tooltip Position:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'tooltip_pos' ); ?>" name="<?php echo $this->get_field_name( 'tooltip_pos' ); ?>" class="widefat" style="width:100%;">
				<option value="Top" <?php echo ( 'Top' == $instance['tooltip_pos'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Top', 'Avada' ); ?></option>
				<option value="Right" <?php echo ( 'Right' == $instance['tooltip_pos'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Right', 'Avada' ); ?></option>
				<option value="Bottom" <?php echo ( 'Bottom' == $instance['tooltip_pos'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Bottom', 'Avada' ); ?></option>
				<option value="Left" <?php echo ( 'Left' == $instance['tooltip_pos'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Left', 'Avada' ); ?></option>
				<option value="None" <?php echo ( 'None' == $instance['tooltip_pos'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'Avada' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_custom' ); ?>"><?php _e( 'Show Custom Icons:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'show_custom' ); ?>" name="<?php echo $this->get_field_name( 'show_custom' ); ?>" class="widefat" style="width:100%;">
				<option value="No" <?php echo ( 'No' == $instance['show_custom'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'No', 'Avada' ); ?></option>
				<option value="Yes" <?php echo ( 'Yes' == $instance['show_custom'] ) ? 'selected="selected"' : ''; ?>><?php _e( 'Yes', 'Avada' ); ?></option>
			</select>
		</p>

		<?php
		// Create social network fields
		$social_networks_full_array = Avada_Data::fusion_social_icons( false, true );

		foreach ( $social_networks_full_array as $key => $value ) {

			if ( 'facebook' == $key ) {
				$key = 'fb';
			} elseif ( 'gplus' == $key ) {
				$key = 'google';
			}

			echo '<p>';
			echo '<label for="' . $this->get_field_id( $key . '_link' ) . '">' . sprintf( __( '%s Link:', 'Avada' ), $value['label'] ) . '</label>';
			echo '<input class="widefat" type="text" id="' . $this->get_field_id( $key . '_link' ) . '" name="' . $this->get_field_name( $key . '_link' ) . '" value="' . $instance[$key . '_link'] . '" />';
			echo '</p>';

		}

		$color_type_id  = $this->get_field_id( 'color_type' );
		$boxed_icon_id = $this->get_field_id( 'boxed_icon' );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var $color_type_field = $("#<?php echo $color_type_id; ?>");
				var $boxed_icon_field = $("#<?php echo $boxed_icon_id; ?>");

				function checkBoxedIcons() {
					var color_type = $color_type_field.val();
					var boxed_icon = $boxed_icon_field.val();

					if ( boxed_icon === 'No' ) {
						$boxed_icon_field.parent().parent().find('.avada-widget-boxed-icon-option-child').hide();
						$boxed_icon_field.parent().parent().find('.avada-widget-boxed-icon-background').hide();
					} else {
						$boxed_icon_field.parent().parent().find('.avada-widget-boxed-icon-option-child').show();

						if ( color_type === 'custom' ) {
							$boxed_icon_field.parent().parent().find('.avada-widget-boxed-icon-background').show();
						}
					}
				}

				function checkColorType() {
					var color_type = $color_type_field.val();
					var boxed_icon = $boxed_icon_field.val();

					if ( color_type === 'brand' ) {
						$color_type_field.parent().parent().find('.avada-widget-color-type-option-child').hide();
					} else {
						$color_type_field.parent().parent().find('.avada-widget-color-type-option-child').show();

						if ( boxed_icon === 'No' ) {
							$boxed_icon_field.parent().parent().find('.avada-widget-boxed-icon-background').hide();
						}
					}
				}

				checkColorType();
				checkBoxedIcons();

				$color_type_field.on('change', function() {
					checkColorType();
				});
				$boxed_icon_field.on('change', function() {
					checkBoxedIcons();
				});

			});
		</script>
		<?php
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
