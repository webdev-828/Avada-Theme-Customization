<?php

class Avada_GoogleMap {

	private $map_id;

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_avada-google-map', array( $this, 'attr' ) );
	}

	/**
	 * Function to get the default shortcode param values applied.
	 *
	 * @param  array  $args  Array with user set param values
	 * @return array  $defaults  Array with default param values
	 */
	public static function set_shortcode_defaults( $defaults, $args ) {

		if ( empty( $args ) || ! is_array( $args ) ) {
			$args = array();
		}

		$args = shortcode_atts( $defaults, $args );

		foreach ( $args as $key => $value ) {
			if ( '' == $value ) {
				$args[$key] = $defaults[$key];
			}
		}

		return $args;

	}

	public static function calc_color_brightness( $color ) {

		if ( in_array( strtolower( $color ), array( 'black', 'navy', 'purple', 'maroon', 'indigo', 'darkslategray', 'darkslateblue', 'darkolivegreen', 'darkgreen', 'darkblue' ) ) ) {
			$brightness_level = 0;
		} elseif ( 0 === strpos( $color, '#' ) ) {
			$color = fusion_hex2rgb( $color );
			$brightness_level = sqrt( pow( $color[0], 2) * 0.299 + pow( $color[1], 2) * 0.587 + pow( $color[2], 2) * 0.114 );
		} else {
			$brightness_level = 150;
		}

		return $brightness_level;
	}

	/**
	 * Function to apply attributes to HTML tags.
	 * Devs can override attributes in a child theme by using the correct slug
	 *
	 *
	 * @param  string $slug	   Slug to refer to the HTML tag
	 * @param  array  $attributes Attributes for HTML tag
	 * @return [type]			 [description]
	 */
	public static function attributes( $slug, $attributes = array() ) {

		$out  = '';
		$attr = apply_filters( "fusion_attr_{$slug}", $attributes );

		if ( empty( $attr ) ) {
			$attr['class'] = $slug;
		}

		foreach ( $attr as $name => $value ) {
			$out .= ! empty( $value ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );
		}

		return trim( $out );

	} // end attr()

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_map( $args, $content = '' ) {

		$defaults = $this->set_shortcode_defaults(
			array(
				'class'                    => '',
				'id'                       => '',
				'animation'                => 'no',
				'address'                  => '',
				'address_pin'              => 'yes',
				'height'                   => '300px',
				'icon'                     => '',
				'infobox'                  => '',
				'infobox_background_color' => '',
				'infobox_content'		   => '',
				'infobox_text_color'       => '',
				'map_style'                => '',
				'overlay_color'            => '',
				'popup'                    => 'yes',
				'scale'                    => 'yes',
				'scrollwheel'              => 'yes',
				'type'                     => 'roadmap',
				'width'                    => '100%',
				'zoom'                     => '14',
				'zoom_pancontrol'          => 'yes',
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$html = '';

		if ( $address ) {
			$addresses       = explode( '|', $address );
			$infobox_content = html_entity_decode( $infobox_content );

			$infobox_content_array = ( $infobox_content ) ? explode( '|', $infobox_content ) : '';
			$icon_array            = ( $icon ) ? explode( '|', $icon ) : '';

			if ( ! empty( $addresses ) ) {
				self::$args['address'] = $addresses;
			}

			$num_of_addresses = count( $addresses );

			if ( $icon && false === strpos( $icon, '|' ) ) {
				for ( $i = 0; $i < $num_of_addresses; $i++ ) {
					$icon_array[$i] = $icon;
				}
			}

			if ( 'theme' == $map_style ) {

				$map_style                = 'custom';
				$icon                     = 'theme';
				$animation                = 'yes';
				$infobox                  = 'custom';
				$infobox_background_color = fusion_hex2rgb( Avada()->settings->get( 'primary_color' ) );
				$infobox_background_color = 'rgba(' . $infobox_background_color[0] . ', ' . $infobox_background_color[1] . ', ' . $infobox_background_color[2] . ', 0.8)';
				$overlay_color            = Avada()->settings->get( 'primary_color' );
				$brightness_level         = $this->calc_color_brightness( Avada()->settings->get( 'primary_color' ) );
				$infobox_text_color       = ( $brightness_level > 140 ) ? '#fff' : '#747474';
			} else if ( 'custom' == $map_style ) {
				if ( '0' == Avada_Color::get_alpha_from_rgba( $overlay_color ) ) {
					$overlay_color = '';
				} else {
					$overlay_color = Avada_Color::rgba2hex( $overlay_color, true );
				}
			}

			if ( 'theme' == $icon && 'custom' == $map_style ) {
				for ( $i = 0; $i < $num_of_addresses; $i++ ) {
					$icon_array[$i] = get_template_directory_uri() . '/assets/images/avada_map_marker.png';
				}
			}

			wp_print_scripts( 'google-maps-api' );
			wp_print_scripts( 'google-maps-infobox' );

			foreach( self::$args['address'] as $add ) {
				$add     = trim( $add );
				$add_arr = explode( "\n", $add );
				$add_arr = array_filter( $add_arr, 'trim' );
				$add     = implode( '<br/>', $add_arr );
				$add     = str_replace( "\r", '', $add );
				$add     = str_replace( "\n", '', $add );

				$coordinates[]['address'] = $add;
			}

			if ( ! is_array( $coordinates ) ) {
				return;
			}

			for ( $i = 0; $i < $num_of_addresses; $i++ ) {
				if ( 0 === strpos( self::$args['address'][$i], 'latlng=' ) ) {
					self::$args['address'][$i] = $coordinates[$i]['address'];
				}
			}

			if ( is_array( $infobox_content_array ) && ! empty( $infobox_content_array ) ) {
				for ( $i = 0; $i < $num_of_addresses; $i++ ) {
					if ( ! array_key_exists( $i, $infobox_content_array ) ) {
						$infobox_content_array[$i] = self::$args['address'][$i];
					}
				}
				self::$args['infobox_content'] = $infobox_content_array;
			} else {
				self::$args['infobox_content'] = self::$args['address'];
			}

			$cached_addresses = get_option( 'fusion_map_addresses' );

			foreach ( self::$args['address'] as $key => $address ) {
				$json_addresses[] = array(
					'address'         => $address,
					'infobox_content' => self::$args['infobox_content'][$key]
				);

				if ( isset( $icon_array ) && is_array( $icon_array ) ) {
					$json_addresses[$key]['marker'] = $icon_array[$key];
				}

				if ( false !== strpos( $address, strtolower( 'latlng=' ) ) ) {
					$json_addresses[$key]['address']     = str_replace( 'latlng=', '', $address );
					$latLng                              = explode(',', $json_addresses[$key]['address']);
					$json_addresses[$key]['coordinates'] = true;
					$json_addresses[$key]['latitude']    = $latLng[0];
					$json_addresses[$key]['longitude']   = $latLng[1];
					$json_addresses[$key]['cache']       = false;

					if ( false !== strpos( self::$args['infobox_content'][$key], strtolower( 'latlng=' ) ) ) {
						$json_addresses[$key]['infobox_content'] = '';
					}

					if ( isset( $cached_addresses[trim( $json_addresses[$key]['latitude'] . ',' . $json_addresses[$key]['longitude'] )] ) ) {
						$json_addresses[$key]['geocoded_address'] = $cached_addresses[trim( $json_addresses[$key]['latitude'] . ',' . $json_addresses[$key]['longitude'] )]['address'];
						$json_addresses[$key]['cache']            = true;
					}
				} else {
					$json_addresses[$key]['coordinates'] = false;
					$json_addresses[$key]['cache']       = false;

					if ( isset( $cached_addresses[trim( $json_addresses[$key]['address'] )] ) ) {
						$json_addresses[$key]['latitude']  = $cached_addresses[trim( $json_addresses[$key]['address'] )]['latitude'];
						$json_addresses[$key]['longitude'] = $cached_addresses[trim( $json_addresses[$key]['address'] )]['longitude'];
						$json_addresses[$key]['cache']     = true;
					}
				}
			}

			$json_addresses = json_encode( $json_addresses );

			$map_id = uniqid( 'fusion_map_' ); // generate a unique ID for this map
			$this->map_id = $map_id;
			ob_start(); ?>
			<script type="text/javascript">
				var map_<?php echo $map_id; ?>;
				var markers = [];
				var counter = 0;
				function fusion_run_map_<?php echo $map_id ; ?>() {
					jQuery('#<?php echo $map_id ; ?>').fusion_maps({
						addresses: <?php echo $json_addresses; ?>,
						address_pin: <?php echo ($address_pin == 'yes') ? 'true' : 'false'; ?>,
						animations: <?php echo ($animation == 'yes') ? 'true' : 'false'; ?>,
						infobox_background_color: '<?php echo $infobox_background_color; ?>',
						infobox_styling: '<?php echo $infobox; ?>',
						infobox_text_color: '<?php echo $infobox_text_color; ?>',
						map_style: '<?php echo $map_style; ?>',
						map_type: '<?php echo $type; ?>',
						marker_icon: '<?php echo $icon; ?>',
						overlay_color: '<?php echo $overlay_color; ?>',
						overlay_color_hsl: <?php echo json_encode( fusion_rgb2hsl( $overlay_color ) ); ?>,
						pan_control: <?php echo ($zoom_pancontrol == 'yes') ? 'true' : 'false'; ?>,
						show_address: <?php echo ($popup == 'yes') ? 'true' : 'false'; ?>,
						scale_control: <?php echo ($scale == 'yes') ? 'true' : 'false'; ?>,
						scrollwheel: <?php echo ($scrollwheel == 'yes') ? 'true' : 'false'; ?>,
						zoom: <?php echo $zoom; ?>,
						zoom_control: <?php echo ($zoom_pancontrol == 'yes') ? 'true' : 'false'; ?>,
					});
				}

				google.maps.event.addDomListener(window, 'load', fusion_run_map_<?php echo $map_id ; ?>);
			</script>
			<?php
			if ( $defaults['id'] ) {
				$html = ob_get_clean() . sprintf( '<div id="%s"><div %s></div></div>', $defaults['id'], $this->attributes( 'avada-google-map' ) );
			} else {
				$html = ob_get_clean() . sprintf( '<div %s></div>', $this->attributes( 'avada-google-map' ) );
			}

		}

		return $html;

	}

	function attr() {

		$attr['class'] = 'shortcode-map fusion-google-map avada-google-map';

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		$attr['id'] = $this->map_id;

		$attr['style'] = sprintf( 'height:%s;width:%s;', self::$args['height'], self::$args['width'] );

		return $attr;

	}

}
