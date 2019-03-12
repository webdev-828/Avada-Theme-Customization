<?php
class FusionSC_SharingBox {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_sharingbox-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_sharingbox-shortcode-tagline', array( $this, 'tagline_attr' ) );
		add_filter( 'fusion_attr_sharingbox-shortcode-social-networks', array( $this, 'social_networks_attr' ) );
		add_filter( 'fusion_attr_sharingbox-shortcode-icon', array( $this, 'icon_attr' ) );

		add_shortcode( 'sharing', array( $this, 'render' ) );

	}

	/**
	 * Render the parent shortcode
	 * @param  array $args    Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'              => '',
				'id'                 => '',
				'backgroundcolor'    => strtolower( $smof_data['social_bg_color'] ),
				'description'        => '',
				'color_type'         => '',
				'icon_colors'        => '',
				'box_colors'         => '',
				'icons_boxed'        => ( Avada()->settings->get( 'sharing_social_links_boxed' ) == 1 ) ? 'yes' : Avada()->settings->get( 'sharing_social_links_boxed' ),
				'icons_boxed_radius' => ( isset( $smof_data['sharing_social_links_boxed_radius'] ) ) ? Avada_Sanitize::size( $smof_data['sharing_social_links_boxed_radius'] ) : '0px',
				'link'               => '',
				'pinterest_image'    => '',
				'social_networks'    => $this->get_theme_options_settings(),
				'tagline'            => '',
				'tagline_color'      => strtolower( $smof_data['sharing_box_tagline_text_color'] ),
				'title'              => '',
				'tooltip_placement'  => strtolower( $smof_data['sharing_social_links_tooltip_placement'] ),
			), $args
		);

		$defaults['icons_boxed_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['icons_boxed_radius'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		$use_brand_colors = false;

		if ( 'brand' == $color_type || ( '' == $color_type && 'brand' == Avada()->settings->get( 'sharing_social_links_color_type' ) ) ) {
			$use_brand_colors = true;
			// Get a list of all the available social networks
			$social_icon_boxed_colors = Avada_Data::fusion_social_icons( false, true );
			$social_icon_boxed_colors['googleplus'] = array( 'label' => 'Google+', 'color' => '#dc4e41' );
			$social_icon_boxed_colors['mail'] = array( 'label' => esc_html__( 'Email Address', 'fusion-core' ), 'color' => '#000000' );

		} elseif ( '' == $color_type && 'custom' == Avada()->settings->get( 'social_links_color_type' ) ) {
			// Custom social icon colors from theme options
			$icon_colors = explode( '|', strtolower( $smof_data['sharing_social_links_icon_color'] ) );
			$box_colors  = explode( '|', strtolower( $smof_data['sharing_social_links_box_color'] ) );

		} else {
			$icon_colors = explode( '|', $icon_colors );
			$box_colors  = explode( '|', $box_colors );
		}

		$num_of_icon_colors = count( $icon_colors );
		$num_of_box_colors  = count( $box_colors );
		$social_networks = explode( '|', $social_networks );

		$icons = '';

		for ( $i = 0; $i < count( $social_networks ); $i++ ) {
			if ( 1 == $num_of_icon_colors ) {
				if ( ! is_array( $icon_colors ) ) {
					$icon_colors = array( $icon_colors );
				}		
				$icon_colors[ $i ] = $icon_colors[0];
			}

			if ( 1 == $num_of_box_colors ) {
				if ( ! is_array( $box_colors ) ) {
					$box_colors = array( $box_colors );
				}				
				$box_colors[ $i ] = $box_colors[0];
			}

			$network = $social_networks[ $i ];

			if ( true == $use_brand_colors ) {
				$icon_options = array(
					'social_network' => $network,
					'icon_color'     => ( 'yes' == $icons_boxed ) ? '#ffffff' : $social_icon_boxed_colors[$network]['color'],
					'box_color'      => ( 'yes' == $icons_boxed ) ? $social_icon_boxed_colors[$network]['color'] : '',
				);

			} else {
				$icon_options = array(
					'social_network' => $network,
					'icon_color'     => $i < count( $icon_colors ) ? $icon_colors[ $i ] : '',
					'box_color'      => $i < count( $box_colors ) ? $box_colors[ $i ] : '',
				);
			}

			$icons .= '<a ' . FusionCore_Plugin::attributes( 'sharingbox-shortcode-icon', $icon_options ) . '></a>';
		}

		$html = '<div ' . FusionCore_Plugin::attributes( 'sharingbox-shortcode' ) . '>';
			$html .= '<h4 ' . FusionCore_Plugin::attributes( 'sharingbox-shortcode-tagline' ) . '>' . $tagline . '</h4>';
			$html .= '<div ' . FusionCore_Plugin::attributes( 'sharingbox-shortcode-social-networks' ) . '>';
				$html .= $icons;
			$html .= '</div>';
		$html .= '</div>';

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'share-box fusion-sharing-box';

		if ( 'yes' == self::$args['icons_boxed'] ) {
			$attr['class'] .= ' boxed-icons';
		}

		if ( self::$args['backgroundcolor'] ) {
			$attr['style'] = 'background-color:' . self::$args['backgroundcolor'] . ';';

			if ( 'transparent' == self::$args['backgroundcolor'] || '0' == Avada_Color::get_alpha_from_rgba( self::$args['backgroundcolor'] ) ) {
				$attr['style'] .= 'padding:0;';
			}
		}

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		$attr['data-title']       = self::$args['title'];
		$attr['data-description'] = self::$args['description'];
		$attr['data-link']        = self::$args['link'];
		$attr['data-image']       = self::$args['pinterest_image'];

		return $attr;

	}

	function tagline_attr() {

		$attr['class'] = 'tagline';

		if ( self::$args['tagline_color'] ) {
			$attr['style'] = 'color:' . self::$args['tagline_color'] . ';';
		}

		return $attr;

	}

	function social_networks_attr() {

		$attr['class'] = 'fusion-social-networks';

		if ( 'yes' == self::$args['icons_boxed'] ) {
			$attr['class'] .= ' boxed-icons';
		}

		if ( ! self::$args['tagline'] ) {
			$attr['style'] = 'text-align: inherit;';
		}

		return $attr;

	}

	function icon_attr( $args ) {
		global $smof_data;

		$description = self::$args['description'];
		$link        = self::$args['link'];
		$title       = self::$args['title'];
		$image       = rawurlencode( self::$args['pinterest_image'] );

		$attr['class'] = 'fusion-social-network-icon fusion-tooltip fusion-' . $args['social_network'] . ' fusion-icon-' . $args['social_network'];

		$social_link = '';
		switch( $args['social_network'] ) {
			case 'facebook':
				$social_link = 'http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=' . $link . '&p&#91;images&#93;&#91;0&#93;=' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) . '&p&#91;title&#93;=' . rawurlencode( $title );
				break;
			case 'twitter':
				$social_link = 'https://twitter.com/share?text=' . rawurlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ) . '&url=' . rawurlencode( $link );
				break;
			case 'linkedin':
				$social_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( $link ) . '&amp;title=' . rawurlencode( $title ) . '&amp;summary=' . rawurlencode( $description );
				break;
			case 'reddit':
				$social_link = 'http://reddit.com/submit?url=' . $link . '&amp;title=' . $title;
				break;
			case 'tumblr':
				$social_link = 'http://www.tumblr.com/share/link?url=' . rawurlencode( $link ) . '&amp;name=' . rawurlencode( $title ) . '&amp;description=' . rawurlencode( $description );
				break;
			case 'googleplus':
				$social_link     = 'https://plus.google.com/share?url=' . $link;
				$attr['onclick'] = 'javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;';
				break;
			case 'pinterest':
				$social_link = 'http://pinterest.com/pin/create/button/?url=' . rawurlencode( $link ) . '&amp;description=' . rawurlencode( $description ) . '&amp;media=' . $image;
				break;
			case 'vk':
				$social_link = 'http://vkontakte.ru/share.php?url=' . rawurlencode( $link ) . '&amp;title=' . rawurlencode( $title ) . '&amp;description=' . rawurlencode( $description );
				break;
			case 'mail':
				$social_link = 'mailto:?subject=' . rawurlencode( $title ) . '&amp;body=' . rawurlencode( $link );
				break;
		}

		$attr['href'] = $social_link;

		$attr['target'] = ( $smof_data['social_icons_new'] && 'mail' != $args['social_network'] ) ? '_blank' : '_self';

		if ( $smof_data['nofollow_social_links'] ) {
			$attr['rel'] = 'nofollow';
		}

		$attr['style'] = ( $args['icon_color'] ) ? 'color:' . $args['icon_color'] . ';' : '';

		if ( isset( self::$args['icons_boxed'] ) && 'yes' == self::$args['icons_boxed'] && $args['box_color'] ) {
			$attr['style'] .= 'background-color:' . $args['box_color'] . ';border-color:' . $args['box_color'] . ';';
		}

		if ( 'yes' == self::$args['icons_boxed'] && self::$args['icons_boxed_radius'] || '0' === self::$args['icons_boxed_radius'] ) {
			if ( 'round' == self::$args['icons_boxed_radius'] ) {
				self::$args['icons_boxed_radius'] = '50%';
			}
			$attr['style'] .= 'border-radius:' . self::$args['icons_boxed_radius'] . ';';
		}

		$attr['data-placement'] = self::$args['tooltip_placement'];
		$tooltip = $args['social_network'];
		if ( 'googleplus' == $tooltip ) {
			$tooltip = 'Google+';
		}
		$attr['data-title'] = ucfirst( $tooltip );
		$attr['title']      = ucfirst( $tooltip );

		if ( 'none' != self::$args['tooltip_placement'] ) {
			$attr['data-toggle'] = 'tooltip';
		}

		return $attr;

	}

	function get_theme_options_settings() {
		global $smof_data;
		$social_media = array();

		if ( $smof_data['sharing_facebook'] ) {
			$social_media[] = array(
				'network'    => 'facebook',
			);
		}

		if ( $smof_data['sharing_twitter'] ) {
			$social_media[] = array(
				'network'    => 'twitter',
			);
		}

		if ( $smof_data['sharing_linkedin'] ) {
			$social_media[] = array(
				'network'    => 'linkedin',
			);
		}

		if ( $smof_data['sharing_reddit'] ) {
			$social_media[] = array(
				'network'    => 'reddit',
			);
		}

		if ( $smof_data['sharing_tumblr'] ) {
			$social_media[] = array(
				'network'    => 'tumblr',
			);
		}

		if ( $smof_data['sharing_google'] ) {
			$social_media[] = array(
				'network'    => 'googleplus',
			);
		}

		if ( $smof_data['sharing_pinterest'] ) {
			$social_media[] = array(
				'network'    => 'pinterest',
			);
		}

		if ( $smof_data['sharing_vk'] ) {
			$social_media[] = array(
				'network'    => 'vk',
			);
		}

		if ( $smof_data['sharing_email'] ) {
			$social_media[] = array(
				'network'    => 'mail',
			);
		}

		$networks = array();

		foreach ( $social_media as $network ) {
			$networks[] = $network['network'];
		}
		return implode( '|', $networks );

	}

}

new FusionSC_SharingBox();
