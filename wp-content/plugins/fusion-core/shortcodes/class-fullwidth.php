<?php

	class FusionSC_FullWidth {

		public static $args;
		public static $bg_type = 'image';
		private static $parallaxID = 1;
		private $fwc_counter = 1;

		/**
		 * Initiate the shortcode
		 */
		public function __construct() {

			add_filter( 'fusion_attr_fullwidth-shortcode', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_fullwidth-shortcode-fusion-row', array( $this, 'fusion_row' ) );
			add_filter( 'fusion_attr_fullwidth-overlay', array( $this, 'overlay_attr' ) );
			add_filter( 'fusion_attr_fullwidth-parallax', array( $this, 'parallax_attr' ) );
			add_filter( 'fusion_attr_fullwidth-faded', array( $this, 'faded_attr' ) );
			add_shortcode( 'fullwidth', array( $this, 'render' ) );

			// Add plugin specific filters and actions here
			add_action( 'wp_head', array( $this, 'ie9Detector' ) );

		}

		public function ie9Detector() {
			echo "<!--[if IE 9]> <script>var _fusionParallaxIE9 = true;</script> <![endif]-->";
		}

		/**
		 * Render the shortcode
		 *
		 * @param  array  $args    Shortcode paramters
		 * @param  string $content Content between shortcode
		 *
		 * @return string          HTML output
		 */
		function render( $args, $content = '' ) {
			global $smof_data;

			$args = $this->deprecated_args( $args );

			$defaults = FusionCore_Plugin::set_shortcode_defaults(
				array(
					'class'                 => '',
					'id'                    => '',
					'background_parallax'   => 'none',
					'background_color'      => $smof_data['full_width_bg_color'],
					'background_image'      => '',
					'background_position'   => 'center center',
					'background_repeat'     => 'no-repeat',
					'border_color'          => $smof_data['full_width_border_color'],
					'border_size'           => intval( $smof_data['full_width_border_size'] ) . 'px',
					'border_style'          => 'solid',
					'equal_height_columns'  => 'no',
					'enable_mobile'         => 'no',
					'fade'                  => 'no',
					'hundred_percent'       => 'no',
					'menu_anchor'           => '',
					'hide_on_mobile'		=> 'no',
					'overlay_color'         => '',
					'break_parents'         => '0',
					'parallax_speed'        => '0.3',
					'overlay_opacity'       => '0.5',
					'opacity'       		=> '100',
					'padding_bottom'        => '0px',
					'padding_left'          => '',
					'padding_right'         => '',
					'padding_top'           => '0px',
					'video_loop'            => 'yes',
					'video_loop_refinement' => '',
					'video_mp4'             => '',
					'video_mute'            => 'yes',
					'video_ogg'             => '',
					'video_ogv'             => '', // Deprecated
					'video_preview_image'   => '',
					'video_url'             => '',
					'data-bg-height'        => '',
					'data-bg-width'         => '',
					'video_aspect_ratio'    => '',
					'video_webm'            => '',
				), $args
			);

			$defaults['border_size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_size'], 'px' );
			$defaults['padding_bottom'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding_bottom'], 'px' );
			$defaults['padding_left'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding_left'], 'px' );
			$defaults['padding_right'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding_right'], 'px' );
			$defaults['padding_top'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding_top'], 'px' );

			if ( $defaults['video_ogv'] ) {
				$defaults['video_ogg'] =  $defaults['video_ogv'];
			}

			$object_id = get_queried_object_id();
			$c_pageID  = FusionCore_Plugin::c_pageID();

			$width_100 = false;
			$page_template = '';

			if ( function_exists( 'is_woocommerce' ) ) {
				if( is_woocommerce() ) {
					$custom_fields = get_post_custom_values( '_wp_page_template', $c_pageID );
					$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
				}
			}

			if ( is_page_template( '100-width.php' ) || is_page_template( 'blank.php' ) || ( '1' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) || 'yes' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) && 'avada_portfolio' == get_post_type( $c_pageID ) ) || ( avada_is_portfolio_template() && 'yes' == get_post_meta( $c_pageID, 'pyre_portfolio_width_100', true ) ) || '100-width.php' == $page_template ) {

				$width_100 = true;
			}

			if ( empty( $defaults['padding_left'] ) && $defaults['padding_left'] != '0' && $width_100 == true ) {
				$c_pageID  = FusionCore_Plugin::c_pageID();

				if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) || get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) == '0' ) {
					$defaults['padding_left'] = get_post_meta( $c_pageID, 'pyre_hundredp_padding', true );
				} else if ( $smof_data[ 'hundredp_padding'] || $smof_data['hundredp_padding'] == '0' ) {
					$defaults['padding_left'] = $smof_data['hundredp_padding'];
				} else {
					$defaults['padding_left'] = '0px';
				}
				
				// Set the correct paddings and negative margins for the "100% Width Left/Right Padding" option
				$hundredplr_padding = Avada_Sanitize::size( $defaults['padding_left'] );
				$hundredplr_padding_value = Avada_Sanitize::number( $hundredplr_padding );
				$hundredplr_padding_unit = Avada_Sanitize::get_unit( $hundredplr_padding );

				if ( $hundredplr_padding_unit == '%' ) {
					$fullwidth_max_width = 100 - 2 * $hundredplr_padding_value;
					$defaults['padding_left'] = $hundredplr_padding_value / $fullwidth_max_width * 100 . $hundredplr_padding_unit;
				}
			}

			if ( empty( $defaults['padding_right'] ) && $defaults['padding_right'] != '0' && $width_100 == true ) {
				$c_pageID  = FusionCore_Plugin::c_pageID();

				if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) || get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) == '0' ) {
					$defaults['padding_right'] = get_post_meta( $c_pageID, 'pyre_hundredp_padding', true );
				} else if ( $smof_data[ 'hundredp_padding'] || $smof_data['hundredp_padding'] == '0' ) {
					$defaults['padding_right'] = $smof_data['hundredp_padding'];
				} else {
					$defaults['padding_right'] = '0px';
				}
				
				// Set the correct paddings and negative margins for the "100% Width Left/Right Padding" option
				$hundredplr_padding = Avada_Sanitize::size( $defaults['padding_right'] );
				$hundredplr_padding_value = Avada_Sanitize::number( $hundredplr_padding );
				$hundredplr_padding_unit = Avada_Sanitize::get_unit( $hundredplr_padding );

				if ( $hundredplr_padding_unit == '%' ) {
					$fullwidth_max_width = 100 - 2 * $hundredplr_padding_value;
					$defaults['padding_right'] = $hundredplr_padding_value / $fullwidth_max_width * 100 . $hundredplr_padding_unit;
				}				
			}

			if( strpos( $defaults['padding_left'], '%' ) === false && strpos( $defaults['padding_left'], 'px' ) === false ) {
				$defaults['padding_left'] = $defaults['padding_left'] . 'px';
			}

			if( strpos( $defaults['padding_right'], '%' ) === false && strpos( $defaults['padding_right'], 'px' ) === false ) {
				$defaults['padding_right'] = $defaults['padding_right'] . 'px';
			}

			if( strpos( $defaults['padding_top'], '%' ) === false && strpos( $defaults['padding_top'], 'px' ) === false ) {
				$defaults['padding_top'] = $defaults['padding_top'] . 'px';
			}

			if( strpos( $defaults['padding_bottom'], '%' ) === false && strpos( $defaults['padding_bottom'], 'px' ) === false ) {
				$defaults['padding_bottom'] = $defaults['padding_bottom'] . 'px';
			}

			self::$args = $defaults;

			extract( $defaults );

			$outer_html = '';

			self::$bg_type = "image";
			if ( ! empty( self::$args['video_url'] ) || ! empty( self::$args['video_mp4'] ) || ! empty( self::$args['video_webm'] ) || ! empty( self::$args['video_ogv'] ) ) {
				self::$bg_type = "video";
			}

			if ( wp_is_mobile() && self::$bg_type == 'video' && ! empty( self::$args['video_url'] ) ) {

				if ( $video_preview_image ) {
					$video_preview_image_style = sprintf( 'background-image:url(%s);', $video_preview_image );
					$outer_html .= sprintf( '<div class="%s" style="%s"></div>', 'fullwidth-video-image', $video_preview_image_style );
				}

			} elseif ( self::$bg_type == 'video' && ! empty( self::$args['video_url'] ) ) {

				$video_url = self::get_video_provider( self::$args['video_url'] );
				if ( $video_url['type'] == 'youtube' ) {
					$outer_html .= "<div style='visibility: hidden' id='video-" . self::$parallaxID ++ . "' data-youtube-video-id='" . $video_url['id'] . "' data-mute='" . self::$args['video_mute'] . "' data-loop='" . ( self::$args['video_loop'] == 'yes' ? 1 : 0 ) . "' data-loop-adjustment='" . self::$args['video_loop_refinement'] . "' data-video-aspect-ratio='" . self::$args['video_aspect_ratio'] . "' data-overlay-opacity='" . self::$args['overlay_opacity'] . "'><div id='video-" . self::$parallaxID ++ . "-inner'></div></div>";
				} else if ( $video_url['type'] == 'vimeo' ) {
					$outer_html .= '<div id="video-' . self::$parallaxID . '" data-vimeo-video-id="' . $video_url['id'] . '" data-mute="' . self::$args['video_mute'] . '" data-video-aspect-ratio="' . self::$args['video_aspect_ratio'] . '" style="visibility:hidden;"><iframe id="video-iframe-' . self::$parallaxID . '" src="https://player.vimeo.com/video/' . $video_url['id'] . '?api=1&player_id=video-iframe-' . self::$parallaxID ++ . '&html5=1&autopause=0&autoplay=1&badge=0&byline=0&loop=' . ( self::$args['video_loop'] == 'yes' ? '1' : '0' ) . '&title=0" frameborder="0"></iframe></div>';
				}
				if ( $overlay_color ) {
					$outer_html .= sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fullwidth-overlay' ) );
				}

			} elseif ( self::$bg_type == 'video' && empty( self::$args['video_url'] ) ) {
				$video_attributes = 'preload="auto" autoplay';
				$video_src        = '';

				if ( $video_loop == 'yes' ) {
					$video_attributes .= ' loop';
				}

				if ( $video_mute == 'yes' ) {
					$video_attributes .= ' muted';
				}

				if ( $video_mp4 ) {
					$video_src .= sprintf( '<source src="%s" type="video/mp4">', $video_mp4 );
				}

				if ( $video_ogg ) {
					$video_src .= sprintf( '<source src="%s" type="video/ogg">', $video_ogg );
				}

				if ( $video_webm ) {
					$video_src .= sprintf( '<source src="%s" type="video/webm">', $video_webm );
				}

				if ( $overlay_color ) {
					$outer_html .= sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fullwidth-overlay' ) );
				}

				$outer_html .= sprintf( '<div class="%s"><video %s>%s</video></div>', 'fullwidth-video', $video_attributes, $video_src );

				if ( $video_preview_image ) {
					$video_preview_image_style = sprintf( 'background-image:url(%s);', $video_preview_image );
					$outer_html .= sprintf( '<div class="%s" style="%s"></div>', 'fullwidth-video-image', $video_preview_image_style );
				}
			}

			if ( self::$args['fade'] == 'yes' && self::$bg_type == "image" ) {
				self::$bg_type = 'faded';
				$outer_html .= sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fullwidth-faded' ) );
			}

			$parallax_helper = '';
			if ( self::$args['background_parallax'] != 'none' &&
				 self::$args['background_parallax'] != 'fixed'
			) {
				$parallax_helper = sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fullwidth-parallax' ) );
			}

			if( strpos( $smof_data['site_width'], '%' ) === false && strpos( $smof_data['site_width'], 'px' ) === false ) {
				$smof_data['site_width'] = $smof_data['site_width'] . 'px';
			}
			$site_width = (int) $smof_data['site_width'];
			$site_width_percent = false;
			if( strpos( $smof_data['site_width'], '%' ) !== false ) {
				$site_width_percent = true;
			}

			$int_left_padding = (int) self::$args['padding_left'];
			$int_right_padding = (int) self::$args['padding_right'];


            $styles = '';


            if( $defaults['hundred_percent'] == 'yes' && $width_100 == true ) {

				$styles .= '<style type="text/css" scoped="scoped">';


                $styles .= '.fusion-fullwidth-' . $this->fwc_counter . ', .fusion-fullwidth-' . $this->fwc_counter . '.fusion-section-separator {
                        padding-left: ' . self::$args['padding_left'] . ' !important;
                        padding-right: ' . self::$args['padding_right'] . ' !important;
                    }';

                if( ! $site_width_percent ) {
                    $styles .= '@media only screen and (max-width: ' . $smof_data['site_width'] . ') {';
                    if( $int_left_padding != 0 && $int_right_padding != 0 ) {
                        $styles .= '.fusion-fullwidth-' . $this->fwc_counter . ', .fusion-fullwidth-' . $this->fwc_counter . '.fusion-section-separator {
                                margin-left: -' . self::$args['padding_left'] . ' !important;
                                margin-right: -' . self::$args['padding_right'] . ' !important;
                            }';
                    } elseif ( $int_left_padding == 0 && $int_right_padding != 0 ) {
                        $styles .= '.fusion-fullwidth-' . $this->fwc_counter . ', .fusion-fullwidth-' . $this->fwc_counter . '.fusion-section-separator {
                                margin-right: -' . self::$args['padding_right'] . ' !important;
                            }';
                    } elseif ( $int_left_padding != 0 && $int_right_padding == 0 ) {
                        $styles .= '.fusion-fullwidth-' . $this->fwc_counter . ', .fusion-fullwidth-' . $this->fwc_counter . '.fusion-section-separator {
                                margin-left: -' . self::$args['padding_left'] . ' !important;
                            }';
                    }
                    $styles .= '}';
                }

                $styles .= '</style>';
            } else {
                if( ! $site_width_percent ) {
                    $styles .= '<style type="text/css" scoped="scoped">';

                    $styles .= '.fusion-fullwidth-' . $this->fwc_counter . ' {
                            padding-left: ' . self::$args['padding_left'] . ' !important;
                            padding-right: ' . self::$args['padding_right'] . ' !important;
                        }';

                    $styles .= '</style>';
                }
            }

			$html = sprintf( '%s<div %s>%s<div %s>%s</div></div>', $parallax_helper, FusionCore_Plugin::attributes( 'fullwidth-shortcode' ), $styles . $outer_html, FusionCore_Plugin::attributes( 'fullwidth-shortcode-fusion-row' ), do_shortcode( $content ) );

			if ( $defaults['menu_anchor'] ) {
				$html = sprintf( '<div id="%s">%s</div>', $defaults['menu_anchor'], $html );
			}

			$this->fwc_counter++;

			return $html;

		}

		function attr() {
			global $smof_data;

			if( strpos( $smof_data['site_width'], '%' ) === false && strpos( $smof_data['site_width'], 'px' ) === false ) {
				$smof_data['site_width'] = $smof_data['site_width'] . 'px';
			}
			$site_width = (int) $smof_data['site_width'];
			$site_width_percent = false;
			if( strpos( $smof_data['site_width'], '%' ) !== false ) {
				$site_width_percent = true;
			}

			$int_left_padding = (int) self::$args['padding_left'];
			$int_right_padding = (int) self::$args['padding_right'];

			$object_id = get_queried_object_id();
			$c_pageID  = FusionCore_Plugin::c_pageID();

			$width_100 = false;
			$page_template = '';

			if ( function_exists( 'is_woocommerce' ) ) {
				if( is_woocommerce() ) {
				$custom_fields = get_post_custom_values( '_wp_page_template', $c_pageID );
				$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
			}
			}

			if ( is_page_template( '100-width.php' ) || is_page_template( 'blank.php' ) || ( '1' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) || 'yes' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) && 'avada_portfolio' == get_post_type( $c_pageID ) ) || ( avada_is_portfolio_template() && 'yes' == get_post_meta( $c_pageID, 'pyre_portfolio_width_100', true ) ) || '100-width.php' == $page_template ) {

				$width_100 = true;
			}

			$attr['class'] = 'fusion-fullwidth fullwidth-box fusion-fullwidth-' . $this->fwc_counter . ' ';
			$attr['style'] = '';

			$attr['class'] .= sprintf( ' fusion-parallax-%s', self::$args['background_parallax'] );

			if ( self::$args['hundred_percent'] == 'yes' ) {
				$attr['class'] .= ' hundred-percent-fullwidth';

				if ( (int) self::$args['padding_left'] > 0 || (int) self::$args['padding_right'] > 0 ) {
					$attr['class'] .= ' fusion-nonzero-padding';
				}

			} else {
				$attr['class'] .= ' nonhundred-percent-fullwidth';
			}

			if ( self::$bg_type == 'video' ) {
				$attr['class'] .= ' video-background';
			} else
			if ( self::$bg_type == 'faded' ) {
				$attr['class'] .= ' faded-background';
			}

			if ( self::$args['equal_height_columns'] == 'yes' ) {
				$attr['class'] .= ' fusion-equal-height-columns';
			}

			if ( self::$args['border_color'] ) {
				$attr['style'] .= sprintf( 'border-color:%s;', self::$args['border_color'] );
			}

			if ( self::$args['border_size'] ) {
				$attr['style'] .= sprintf( 'border-bottom-width: %s;border-top-width: %s;', self::$args['border_size'], self::$args['border_size'] );
			}

			if ( self::$args['border_style'] ) {
				$attr['style'] .= sprintf( 'border-bottom-style: %s;border-top-style: %s;', self::$args['border_style'], self::$args['border_style'] );
			}

			if ( self::$args['padding_bottom'] ) {
				$attr['style'] .= sprintf( 'padding-bottom:%s;', Avada_Sanitize::get_value_with_unit( self::$args['padding_bottom'] ) );
			}

			if ( self::$args['padding_top'] ) {
				$attr['style'] .= sprintf( 'padding-top:%s;', Avada_Sanitize::get_value_with_unit( self::$args['padding_top'] ) );
			}

            if ( self::$args['padding_left'] ) {
                $attr['style'] .= sprintf( 'padding-left:%s;', Avada_Sanitize::get_value_with_unit( self::$args['padding_left'] ) );
            }

            if ( self::$args['padding_right'] ) {
                $attr['style'] .= sprintf( 'padding-right:%s;', Avada_Sanitize::get_value_with_unit( self::$args['padding_right'] ) );
            }

			if ( self::$args['hundred_percent'] == 'no' && (int) self::$args['padding_left'] > 0 && $width_100 == true ) {
				$attr['style'] .= sprintf( 'padding-left:%s !important;', Avada_Sanitize::get_value_with_unit( self::$args['padding_left'] ) );
			}

			if ( self::$args['hundred_percent'] == 'no' && (int) self::$args['padding_right'] > 0 && $width_100 == true ) {
				$attr['style'] .= sprintf( 'padding-right:%s !important;', Avada_Sanitize::get_value_with_unit( self::$args['padding_right'] ) );
			}

			if ( self::$args['id'] ) {
				$attr['id'] = self::$args['id'];
			}

			if ( self::$args['class'] ) {
				$attr['class'] .= ' ' . self::$args['class'];
			}

			if ( self::$args['background_image'] && self::$args['background_parallax'] == 'fixed' ) {
				$attr['style'] .= 'background-attachment:fixed;';
			}

			if ( self::$args['background_parallax'] == "none" || self::$args['background_parallax'] == "fixed" ) {

				if ( self::$args['background_color'] ) {
					$attr['style'] .= sprintf( 'background-color:%s;', self::$args['background_color'] );
				}

				if ( self::$args['background_image'] && self::$args['background_position'] ) {
					$attr['style'] .= sprintf( 'background-position:%s;', self::$args['background_position'] );
				}

				if ( self::$args['background_image'] && self::$args['background_repeat'] ) {
					$attr['style'] .= sprintf( 'background-repeat:%s;', self::$args['background_repeat'] );
				}

				if ( self::$args['background_image'] && self::$args['background_repeat'] == 'no-repeat' ) {
					$attr['style'] .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';

					// IE 8 background-size: cover filter
					if ( self::$args['background_image'] ) {
						$attr['style'] .= sprintf( '-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'%s\', sizingMethod=\'scale\')";', self::$args['background_image'] );
					}
				}

				if ( self::$bg_type != 'faded' ) {
					if ( self::$args['background_image'] ) {
						$attr['style'] .= sprintf( 'background-image: url(%s);', self::$args['background_image'] );
					}
				}
			}

			if( self::$args['hide_on_mobile'] == 'yes' ) {
				$attr['class'] .= ' fusion-hide-on-mobile';
			}

			return $attr;

		}

		function fusion_row() {
			$attr = array();

			$attr['class'] = 'fusion-row';

			return $attr;
		}

		function resizeImage( $attachmentID, $direction, $velocity, $size = 'cover' ) {
			if ( $size != 'cover' ) {
				return wp_get_attachment_image_src( $attachmentID, $size );
			}

			if ( strtolower( $direction ) != 'none' ) {
				if ( strtolower( $direction ) == 'up' || strtolower( $direction ) == 'down' ) {
					$width  = 1600;
					$height = 1000 + 500 * $velocity;
				} else {
					$width  = 1600 + 500 * $velocity;
					$height = 1000;
				}

				return wp_get_attachment_image_src( $attachmentID, array( $width, $height ) );
			}

			return wp_get_attachment_image_src( $attachmentID, 'full' );
		}

		function get_attachment_id_from_src( $image_src ) {

			global $wpdb;
			$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
			$id    = $wpdb->get_var( $query );

			return $id;

		}

		function parallax_attr() {

			$attr['class'] = 'fusion-bg-parallax';

			$attr['data-bg-align']      	= esc_attr( self::$args['background_position'] );
			$attr['data-direction']      	= self::$args['background_parallax'];
			$attr['data-mute']				= self::$args['video_mute'];
			$attr['data-opacity']			= esc_attr( self::$args['opacity'] );
			$attr['data-velocity']       	= esc_attr( (float) self::$args['parallax_speed'] * -1 );
			$attr['data-mobile-enabled'] 	= ( self::$args['enable_mobile'] == 'yes' ) ? 'true' : 'false';
			$attr['data-break-parents']  	= esc_attr( self::$args['break_parents'] );
			$attr['data-bg-height']      	= esc_attr( self::$args['data-bg-height'] );
			$attr['data-bg-width']       	= esc_attr( self::$args['data-bg-width'] );
			$attr['data-bg-image']       	= esc_attr( self::$args['background_image'] );
			$attr['data-bg-repeat']      	= esc_attr( isset( self::$args['background_repeat'] ) && self::$args['background_repeat'] != "no-repeat" ? 'true' : 'false' );

			return $attr;

		}

		function overlay_attr() {

			$attr['class'] = 'fullwidth-overlay';
			$attr['style'] = '';

			if ( self::$args['overlay_color'] ) {
				$attr['style'] .= sprintf( 'background-color:%s;', self::$args['overlay_color'] );
			}

			if ( self::$args['overlay_opacity'] ) {
				$attr['style'] .= sprintf( 'opacity:%s;', self::$args['overlay_opacity'] );
			}

			return $attr;

		}

		function faded_attr() {

			$attr['class'] = 'fullwidth-faded';
			$attr['style'] = '';

			if ( self::$args['background_image'] && self::$args['background_parallax'] ) {
				$attr['style'] .= sprintf( 'background-attachment:%s;', self::$args['background_parallax'] );
			}

			if ( self::$args['background_color'] ) {
				$attr['style'] .= sprintf( 'background-color:%s;', self::$args['background_color'] );
			}

			if ( self::$args['background_image'] && self::$args['background_image'] ) {
				$attr['style'] .= sprintf( 'background-image: url(%s);', self::$args['background_image'] );
			}

			if ( self::$args['background_image'] && self::$args['background_position'] ) {
				$attr['style'] .= sprintf( 'background-position:%s;', self::$args['background_position'] );
			}

			if ( self::$args['background_image'] && self::$args['background_repeat'] ) {
				$attr['style'] .= sprintf( 'background-repeat:%s;', self::$args['background_repeat'] );
			}

			if ( self::$args['background_image'] && self::$args['background_repeat'] == 'no-repeat' ) {
				$attr['style'] .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
			}

			return $attr;

		}

		/**
		 * Gets the Video ID & Provider from a video URL or ID
		 *
		 * @param    $video_string string The URL or ID of a video
		 *
		 * @return    array container whether the video is a YouTube video or a Vimeo video along with the video ID
		 * @since    3.0
		 */
		protected static function get_video_provider( $video_string ) {

			$video_string = trim( $video_string );

			/*
			 * Check for YouTube
			 */

			$videoID = false;
			if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_string, $id ) ) {
				if ( count( $id > 1 ) ) {
					$videoID = $id[1];
				}
			} else if ( preg_match( '/youtube\.com\/embed\/([^\&\?\/]+)/', $video_string, $id ) ) {
				if ( count( $id > 1 ) ) {
					$videoID = $id[1];
				}
			} else if ( preg_match( '/youtube\.com\/v\/([^\&\?\/]+)/', $video_string, $id ) ) {
				if ( count( $id > 1 ) ) {
					$videoID = $id[1];
				}
			} else if ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $video_string, $id ) ) {
				if ( count( $id > 1 ) ) {
					$videoID = $id[1];
				}
			}

			if ( ! empty( $videoID ) ) {
				return array(
					'type' => 'youtube',
					'id'   => $videoID
				);
			}

			/*
			 * Check for Vimeo
			 */

			if ( preg_match( '/vimeo\.com\/(\w*\/)*(\d+)/', $video_string, $id ) ) {
				if ( count( $id > 1 ) ) {
					$videoID = $id[ count( $id ) - 1 ];
				}
			}

			if ( ! empty( $videoID ) ) {
				return array(
					'type' => 'vimeo',
					'id'   => $videoID
				);
			}

			/*
			 * Non-URL form
			 */

			if ( preg_match( '/^\d+$/', $video_string ) ) {
				return array(
					'type' => 'vimeo',
					'id'   => $video_string
				);
			}

			return array(
				'type' => 'youtube',
				'id'   => $video_string
			);
		}

		public function deprecated_args( $args ) {

			$param_mapping = array(
				'backgroundposition'    => 'background_position',
				'backgroundattachment'  => 'background_parallax',
				'background_attachment' => 'background_parallax',
				'bordersize'            => 'border_size',
				'bordercolor'           => 'border_color',
				'borderstyle'           => 'border_style',
				'paddingtop'            => 'padding_top',
				'paddingbottom'         => 'padding_bottom',
				'paddingleft'           => 'padding_left',
				'paddingright'          => 'padding_right',
				'backgroundcolor'       => 'background_color',
				'backgroundimage'       => 'background_image',
				'backgroundrepeat'      => 'background_repeat',
				'paddingBottom'         => 'padding_bottom',
				'paddingTop'            => 'padding_top',
			);

			if ( ! is_array( $args ) ) {
				$args = array();
			}

			if ( ( array_key_exists( 'backgroundattachment', $args ) && $args['backgroundattachment'] == 'scroll' ) ||
				 ( array_key_exists( 'background_attachment', $args ) && $args['background_attachment'] == 'scroll' )
			) {
				$args['backgroundattachment'] = $args['background_attachment'] = 'none';
			}

			foreach ( $param_mapping as $old => $new ) {
				if ( ! isset( $args[ $new ] ) && isset( $args[ $old ] ) ) {
					$args[ $new ] = $args[ $old ];
					unset( $args[ $old ] );
				}
			}

			return $args;
		}

	}

	new FusionSC_FullWidth();
