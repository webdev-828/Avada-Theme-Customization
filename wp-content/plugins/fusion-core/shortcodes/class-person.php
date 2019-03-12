<?php
class FusionSC_Person {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_person-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_person-shortcode-image-container', array( $this, 'image_container_attr' ) );
		add_filter( 'fusion_attr_person-shortcode-href', array( $this, 'href_attr' ) );
		add_filter( 'fusion_attr_person-shortcode-img', array( $this, 'img_attr' ) );
		add_filter( 'fusion_attr_person-shortcode-author', array( $this, 'author_attr' ) );
		add_filter( 'fusion_attr_person-shortcode-social-networks', array( $this, 'social_networks_attr' ) );
		add_filter( 'fusion_attr_person-shortcode-icon', array( $this, 'icon_attr' ) );
		add_filter( 'fusion_attr_person-desc', array( $this, 'desc_attr' ) );

		add_shortcode( 'person', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args     Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'                    => '',
				'id'                       => '',
				'lightbox'                 => 'no',
				'linktarget'               => '_self',
				'name'                     => '',
				'social_icon_boxed'        => ( Avada()->settings->get( 'social_links_boxed' ) == 1 ) ? 'yes' : Avada()->settings->get( 'social_links_boxed' ),
				'social_icon_boxed_colors' => '',
				'social_icon_boxed_radius' => Avada_Sanitize::size( $smof_data['social_links_boxed_radius'] ),
				'social_icon_color_type'   => '',
				'social_icon_colors'       => '',
				'social_icon_font_size'    => Avada_Sanitize::size( $smof_data['social_links_font_size'] ),
				'social_icon_order'        => '',
				'social_icon_padding'      => ( isset( $smof_data['social_links_boxed_padding'] ) ) ?  Avada_Sanitize::size( $smof_data['social_links_boxed_padding'] ) : '0px',
				'social_icon_tooltip'      => strtolower( $smof_data['social_links_tooltip_placement'] ),
				'pic_bordercolor'          => strtolower( $smof_data['person_border_color'] ),
				'pic_borderradius'         => intval( $smof_data['person_border_radius'] ) . 'px',
				'pic_bordersize'           => intval( $smof_data['person_border_size'] ) . 'px',
				'pic_link'                 => '',
				'pic_style'                => 'none',
				'pic_style_color'          => strtolower( $smof_data['person_style_color'] ),
				'show_custom'              => 'no',
				'picture'                  => '',
				'title'                    => '',
				'hover_type'               => 'none',
				'background_color'         => strtolower( $smof_data['person_background_color'] ),
				'content_alignment'        => strtolower( $smof_data['person_alignment'] ),
				'icon_position'            => strtolower( $smof_data['person_icon_position'] ),
				'facebook'                 => '',
				'twitter'                  => '',
				'instagram'                => '',
				'linkedin'                 => '',
				'dribbble'                 => '',
				'rss'                      => '',
				'youtube'                  => '',
				'pinterest'                => '',
				'flickr'                   => '',
				'vimeo'                    => '',
				'tumblr'                   => '',
				'google'                   => '',
				'googleplus'               => '',
				'digg'                     => '',
				'blogger'                  => '',
				'skype'                    => '',
				'myspace'                  => '',
				'deviantart'               => '',
				'yahoo'                    => '',
				'reddit'                   => '',
				'forrst'                   => '',
				'paypal'                   => '',
				'dropbox'                  => '',
				'soundcloud'               => '',
				'vk'                       => '',
				'xing'                     => '',
				'email'                    => '',
			), $args
		);

		$defaults['pic_bordersize']           = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['pic_bordersize'], 'px' );
		$defaults['pic_borderradius']         = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['pic_borderradius'], 'px' );
		$defaults['social_icon_boxed_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['social_icon_boxed_radius'], 'px' );
		$defaults['social_icon_font_size']    = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['social_icon_font_size'], 'px' );
		$defaults['social_icon_padding']      = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['social_icon_padding'], 'px' );

		if ( '0px' != $defaults['pic_borderradius'] && ! empty( $defaults['pic_borderradius'] ) && 'bottomshadow' == $defaults['pic_style'] ) {
			$defaults['pic_style'] = 'none';
		}

		if ( 'round' == $defaults['pic_borderradius'] ) {
			$defaults['pic_borderradius'] = '50%';
		}

		extract( $defaults );

		self::$args = $defaults;

		self::$args['styles'] = '';

		$rgb = FusionCore_Plugin::hex2rgb( $defaults['pic_style_color'] );

		if ( 'glow' == $pic_style ) {
			self::$args['styles'] .= "-moz-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		}

		if ( 'dropshadow' == $pic_style  ) {
			self::$args['styles'] .= "-moz-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		}

		if ( $pic_borderradius ) {
			self::$args['styles'] .= '-webkit-border-radius:' . self::$args['pic_borderradius'] . ';-moz-border-radius:' . self::$args['pic_borderradius'] . ';border-radius:' . self::$args['pic_borderradius'] . ';';
		}

		$inner_content = $social_icons_content = $social_icons_content_top = $social_icons_content_bottom = '';

		if ( $picture ) {
			$picture = '<img ' . FusionCore_Plugin::attributes( 'person-shortcode-img' ) . ' />';

			if ( $pic_link ) {
				$picture = '<a ' . FusionCore_Plugin::attributes( 'person-shortcode-href' ) . '>' . $picture . '</a>';
			}

			$picture =  '<div ' . FusionCore_Plugin::attributes( 'person-shortcode-image-wrapper' ) . '><div ' . FusionCore_Plugin::attributes( 'person-shortcode-image-container' ) . '>' . $picture . '</div></div>';
		}

		if ( $name || $title || $content ) {

			$social_networks = fusion_core_get_social_networks( $defaults );
			// todo: icon_order - needed ?
			$social_networks = fusion_core_sort_social_networks( $social_networks );



			// if ( isset( $social_networks['custom'] ) && 'yes' == $defaults['show_custom'] ) {
			// 	if ( is_array( $social_networks['custom'] ) && ! empty( $social_networks['custom'] ) ) {
			// 		foreach ( $social_networks['custom'] as $custom_key => $url ) {


			// 			if ( ! isset( $smof_data['social_media_icons']['custom_source'][ $custom_key ] ) || empty( $smof_data['social_media_icons']['custom_source'][ $custom_key ] ) || empty( $url ) ) {
			// 				unset( $social_networks['custom'][ $custom_key ] );
			// 			}
			// 		}
			// 		if ( empty( $social_networks['custom'] ) ) {
			// 			unset( $social_networks['custom'] );
			// 		}
			// 	}
			// }


			$icons = fusion_core_build_social_links( $social_networks, 'person-shortcode-icon', $defaults );

			if ( 0 < count( $social_networks ) ) {
				$social_icons_content_top  = '<div ' . FusionCore_Plugin::attributes( 'person-shortcode-social-networks' ) . '>';
				$social_icons_content_top .= '<div ' . FusionCore_Plugin::attributes( 'fusion-social-networks-wrapper' ) . '>' . $icons . '</div>';
				$social_icons_content_top .= '</div>';

				$social_icons_content_bottom  = '<div ' . FusionCore_Plugin::attributes( 'person-shortcode-social-networks' ) . '>';
				$social_icons_content_bottom .= '<div ' . FusionCore_Plugin::attributes( 'fusion-social-networks-wrapper' ) . '>' . $icons . '</div>';
				$social_icons_content_bottom .= '</div>';
			}

			if ( 'top' == self::$args['icon_position'] ) {
				$social_icons_content_bottom = '';
			} else {
				$social_icons_content_top = '';
			}

			$person_author_wrapper = '<div ' . FusionCore_Plugin::attributes( 'person-author-wrapper' ) . '><span ' . FusionCore_Plugin::attributes( 'person-name' ) . '>' . $name . '</span><span ' . FusionCore_Plugin::attributes( 'person-title' ) . '>' . $title . '</span></div>';

			if ( 'right' == $content_alignment ) {
				$person_author_content = $social_icons_content_top . $person_author_wrapper;
			} else {
				$person_author_content = $person_author_wrapper . $social_icons_content_top;
			}

			$inner_content .= '<div ' . FusionCore_Plugin::attributes( 'person-desc' ) . '>';
				$inner_content .= '<div ' . FusionCore_Plugin::attributes( 'person-shortcode-author' ) . '>' . $person_author_content . '</div>';
				$inner_content .= '<div ' . FusionCore_Plugin::attributes( 'person-content fusion-clearfix' ) . '>' . do_shortcode( $content ) . '</div>';
				$inner_content .= $social_icons_content_bottom;
			$inner_content .= '</div>';

		}

		return sprintf( '<div %s>%s%s</div>', FusionCore_Plugin::attributes( 'person-shortcode' ), $picture, $inner_content );

	}

	function attr() {

		$attr = array();

		$attr['class']  = 'fusion-person person';
		$attr['class'] .= ' fusion-person-' . self::$args['content_alignment'];
		$attr['class'] .= ' fusion-person-icon-' . self::$args['icon_position'];

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function image_container_attr() {

		$attr = array();

		$attr['class'] = 'person-image-container';

		if ( self::$args['hover_type'] ) {
			$attr['class'] .= ' hover-type-' . self::$args['hover_type'];
		}

		if ( 'glow' == self::$args['pic_style'] ) {
			$attr['class'] .= ' glow';
		} elseif ( 'dropshadow' == self::$args['pic_style'] ) {
			$attr['class'] .= ' dropshadow';
		} elseif ( 'bottomshadow' == self::$args['pic_style'] ) {
			$attr['class'] .= ' element-bottomshadow';
		}

		$attr['style'] = self::$args['styles'];

		return $attr;

	}

	function href_attr() {

		$attr = array();

		$attr['href'] = self::$args['pic_link'];

		if ( 'yes' == self::$args['lightbox'] ) {
			$attr['class'] = 'lightbox-shortcode';
			$attr['href']  = self::$args['picture'];
		} else {
			$attr['target'] = self::$args['linktarget'];
		}

		return $attr;

	}

	function img_attr() {

		$attr = array(
			'class' => 'person-img img-responsive',
			'style' => '',
		);

		if ( self::$args['pic_borderradius'] ) {
			$attr['style'] .= '-webkit-border-radius:' . self::$args['pic_borderradius'] . ';-moz-border-radius:' . self::$args['pic_borderradius'] . ';border-radius:' . self::$args['pic_borderradius'] . ';';
		}

		if ( self::$args['pic_bordersize'] ) {
			$attr['style'] .= 'border:' . self::$args['pic_bordersize'] . ' solid ' . self::$args['pic_bordercolor'] . ';';
		}

		$attr['src'] = self::$args['picture'];
		$attr['alt'] = self::$args['name'];

		return $attr;

	}

	function author_attr() {
		return array(
			'class' => 'person-author',
		);
	}

	function desc_attr() {

		$attr = array(
			'class' => 'person-desc',
		);

		if ( self::$args['background_color'] && 'transparent' != self::$args['background_color'] && '0' != Avada_Color::get_alpha_from_rgba( self::$args['background_color'] ) ) {
			$attr['style']  = 'background-color:' . self::$args['background_color'] . ';padding:40px;margin-top:0;';
		}

		return $attr;
	}

	function social_networks_attr() {

		$attr = array(
			'class' => 'fusion-social-networks',
		);

		if ( 'yes' == self::$args['social_icon_boxed'] ) {
			$attr['class'] .= ' boxed-icons';
		}

		return $attr;

	}

	function icon_attr( $args ) {
		global $smof_data;

		$attr = array(
			'class' => 'fusion-social-network-icon fusion-tooltip fusion-' . $args['social_network'] . ' fusion-icon-' . $args['social_network'],
		);

		$link   = $args['social_link'];
		$target = ( $smof_data['social_icons_new'] ) ? '_blank' : '_self';

		if ( 'mail' == $args['social_network'] ) {
			$link   = 'mailto:' . str_replace( 'mailto:', '', $args['social_link'] );
			$target = '_self';
		}

		$attr['href']   = $link;
		$attr['target'] = $target;

		if ( $smof_data['nofollow_social_links'] ) {
			$attr['rel'] = 'nofollow';
		}

		$attr['style'] = '';

		if ( $args['icon_color'] ) {
			$attr['style'] = 'color:' . $args['icon_color'] . ';';
		}

		if ( 'yes' == self::$args['social_icon_boxed'] && $args['box_color'] ) {
			$attr['style'] .= 'background-color:' . $args['box_color'] . ';border-color:' . $args['box_color'] . ';';
		}

		if ( 'yes' == self::$args['social_icon_boxed'] && self::$args['social_icon_boxed_radius'] || '0' === self::$args['social_icon_boxed_radius'] ) {
			if ( 'round' == self::$args['social_icon_boxed_radius'] ) {
				self::$args['social_icon_boxed_radius'] = '50%';
			}
			$attr['style'] .= 'border-radius:' . self::$args['social_icon_boxed_radius'] . ';';
		}

		if ( self::$args['social_icon_font_size'] ) {
			$attr['style'] .= 'font-size:' . self::$args['social_icon_font_size'] . ';';
		}

		if ( 'yes' == self::$args['social_icon_boxed'] && self::$args['social_icon_padding'] ) {
			$attr['style'] .= 'padding:' . self::$args['social_icon_padding'] . ';';
		}

		$attr['data-placement'] = self::$args['social_icon_tooltip'];
		$tooltip = $args['social_network'];
		$tooltip = ( 'googleplus' == $tooltip ) ? 'Google+' : $tooltip;

		$attr['data-title'] = ucfirst( $tooltip );
		$attr['title']      = ucfirst( $tooltip );

		if ( 'none' != self::$args['social_icon_tooltip'] ) {
			$attr['data-toggle'] = 'tooltip';
		}

		return $attr;

	}

}

new FusionSC_Person();
