<?php
class FusionSC_SocialLinks {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_social-links-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_social-links-shortcode-social-networks', array( $this, 'social_networks_attr' ) );
		add_filter( 'fusion_attr_social-links-shortcode-icon', array( $this, 'icon_attr' ) );

		add_shortcode( 'social_links', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args    Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render( $args, $content = '' ) {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'              => '',
				'id'                 => '',
				'icons_boxed'        => ( Avada()->settings->get( 'social_links_boxed' ) == 1 ) ? 'yes' : Avada()->settings->get( 'social_links_boxed' ),
				'icons_boxed_radius' => Avada_Sanitize::size( $smof_data['social_links_boxed_radius'] ),
				'color_type'         => '',
				'icon_colors'        => '',
				'box_colors'         => '',
				'icon_order'         => '',
				'show_custom'        => 'no',
				'alignment'          => '',
				'tooltip_placement'  => strtolower( $smof_data['social_links_tooltip_placement'] ),
				'facebook'           => '',
				'twitter'            => '',
				'instagram'          => '',
				'linkedin'           => '',
				'dribbble'           => '',
				'rss'                => '',
				'youtube'            => '',
				'pinterest'          => '',
				'flickr'             => '',
				'vimeo'              => '',
				'tumblr'             => '',
				'google'             => '',
				'googleplus'         => '',
				'digg'               => '',
				'blogger'            => '',
				'skype'              => '',
				'myspace'            => '',
				'deviantart'         => '',
				'yahoo'              => '',
				'reddit'             => '',
				'forrst'             => '',
				'paypal'             => '',
				'dropbox'            => '',
				'soundcloud'         => '',
				'vk'                 => '',
				'xing'               => '',
				'email'              => '',
			),
			$args
		);

		$defaults['icons_boxed_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['icons_boxed_radius'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		self::$args['linktarget'] = ( $smof_data['social_icons_new'] ) ? '_blank' : '_self';

		$social_networks = fusion_core_get_social_networks( $defaults );

		$social_networks = fusion_core_sort_social_networks( $social_networks );

		$icons = fusion_core_build_social_links( $social_networks, 'social-links-shortcode-icon', $defaults );

		$html = '<div ' . FusionCore_Plugin::attributes( 'social-links-shortcode' ) . '>';
			$html .= '<div ' . FusionCore_Plugin::attributes( 'social-links-shortcode-social-networks' ) . '>';
				$html .= '<div ' . FusionCore_Plugin::attributes( 'fusion-social-networks-wrapper' ) . '>';
					$html .= $icons;
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';

		if ( $alignment ) {
			$html = '<div class="align' . $alignment . '">' . $html . '</div>';
		}

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-social-links';

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function social_networks_attr() {

		$attr['class'] = 'fusion-social-networks';

		if ( 'yes' == self::$args['icons_boxed'] ) {
			$attr['class'] .= ' boxed-icons';
		}

		return $attr;

	}

	function icon_attr( $args ) {
		global $smof_data;

		$attr = array();
		$attr['class'] = '';
		$attr['style'] = '';

		$tooltip = ucfirst( $args['social_network'] );
		if ( 'custom_' === substr( $args['social_network'], 0, 7 ) ) {
			$attr['class'] .= 'custom ';
			$tooltip = str_replace( 'custom_', '', $args['social_network'] );
			$args['social_network'] = strtolower( $tooltip );
		}

		$attr['class'] .= sprintf( 'fusion-social-network-icon fusion-tooltip fusion-%s fusion-icon-%s', $args['social_network'], $args['social_network'] );

		$link = $args['social_link'];

		$attr['target'] = self::$args['linktarget'];

		if ( $args['social_network'] == 'mail' ) {
			$link = ( 'http' === substr(  $args['social_link'], 0, 4 ) ) ? $args['social_link'] : 'mailto:' . str_replace( 'mailto:', '', $args['social_link'] );
			$attr['target'] = '_self';
		}

		$attr['href'] = $link;

		if ( $smof_data['nofollow_social_links'] ) {
			$attr['rel'] = 'nofollow';
		}

		if ( $args['icon_color'] ) {
			$attr['style'] = sprintf( 'color:%s;', $args['icon_color'] );
		}

		if ( 'yes' == self::$args['icons_boxed'] && $args['box_color'] ) {
			$attr['style'] .= sprintf( 'background-color:%s;border-color:%s;', $args['box_color'], $args['box_color'] );
		}

		if ( 'yes' == self::$args['icons_boxed'] && self::$args['icons_boxed_radius'] || '0' === self::$args['icons_boxed_radius'] ) {
			if ( 'round' == self::$args['icons_boxed_radius'] ) {
				self::$args['icons_boxed_radius'] = '50%';
			}
			$attr['style'] .= sprintf( 'border-radius:%s;', self::$args['icons_boxed_radius'] );
		}

		if ( 'none' != strtolower( self::$args['tooltip_placement'] ) ) {
			$attr['data-placement'] = strtolower( self::$args['tooltip_placement'] );
			if ( 'Googleplus' == $tooltip ) {
				$tooltip = 'Google+';
			}
			$attr['data-title']  = $tooltip;
			$attr['data-toggle'] = 'tooltip';
		}

		$attr['title'] = $tooltip;

		return $attr;

	}

}

new FusionSC_SocialLinks();
