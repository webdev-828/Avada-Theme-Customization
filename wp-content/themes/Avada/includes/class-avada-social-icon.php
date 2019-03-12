<?php

class Avada_Social_Icon {

	public static $args = array();

	public static $iconfont_prefix = 'fusion-icon-';

	/**
	 * Creates the markup for a single icon.
	 *
	 * @var 	$args 	array
	 * @return 	string
	 */
	public static function get_markup( $args ) {

		$icon_options = array(
			'class' => '',
			'style' => ''
		);
		if ( isset( $args['social_network'] ) ) {
			$icon_options['social_network'] = $args['social_network'];
		} elseif ( isset( $args['icon'] ) ) {
			$icon_options['social_network'] = $args['icon'];
		}
		$icon_options['social_link'] = '';
		if ( isset( $args['social_link'] ) ) {
			$icon_options['social_link'] = $args['social_link'];
		} elseif ( isset( $args['url'] ) ) {
			$icon_options['social_link'] = $args['url'];
		}
		if ( isset( $args['icon_color'] ) ) {
			$icon_options['icon_color'] = $args['icon_color'];
		}
		if ( isset( $args['box_color'] ) ) {
			$icon_options['box_color'] = $args['box_color'];
		}
		$icon_options['last'] = ( isset( $args['last'] ) ) ? $args['last'] : false;

		$icon_padding = Avada_Sanitize::size( Avada()->settings->get( 'header_social_links_boxed_padding' ) );
		$custom = '';
		$is_custom_icon = ( isset( $args['custom_source'] ) && isset( $args['custom_title'] ) ) ? true : false;
		// This is a custom icon
		if ( $is_custom_icon ) {
			// Get the position
			$position = ( isset( self::$args['position'] ) && 'footer' == self::$args['position'] ) ? 'footer' : 'header';
			// Get the line-height
			$line_height_option = ( 'header' == $position ) ? 'header_social_links_font_size' : 'footer_social_links_font_size';
			$line_height        = Avada_Sanitize::size( Avada()->settings->get( $line_height_option ) );
			// Get the padding
			$padding_option = ( 'header' == $position ) ? 'header_social_links_boxed_padding' : 'footer_social_links_boxed_padding';
			$icon_padding   = Avada_Sanitize::size( Avada()->settings->get( $padding_option ) );
			// calculate the max-height for the custom icon
			$max_height = ( self::$args['icon_boxed'] ) ? 'calc(' . $line_height . ' + (2 * ' . $icon_padding . ') + 2px)' : $line_height;

			$custom = '<img src="' . $args['custom_source'] . '" style="width:auto;max-height:' . $max_height . ';" alt="' . $args['custom_title'] . '" />';

		}

		if ( 'custom' === substr( $icon_options['social_network'], 0, 7 ) ) {
			$icon_options['class'] .= 'custom ';
			$tooltip = str_replace( 'custom', '', $args['custom_title'] );
			// $icon_options['social_network'] = strtolower( $tooltip );
		} else {
			$tooltip = ucfirst( $icon_options['social_network'] );
		}

		$icon_options['social_network'] = ( 'email' == $icon_options['social_network'] ) ? 'mail' : $icon_options['social_network'];

		$icon_options['class'] .= 'fusion-social-network-icon fusion-tooltip fusion-' . $icon_options['social_network'] . ' ' . self::$iconfont_prefix . $icon_options['social_network'];
		$icon_options['class'] .= ( $args['last'] ) ? ' fusion-last-social-icon' : '';

		$icon_options['href'] = $icon_options['social_link'];

		if ( 'googleplus' == $icon_options['social_network'] && false !== strpos( $icon_options['social_link'], 'share?' ) ) {
			$icon_options['onclick'] = 'javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;';
		}

		if ( self::$args['linktarget'] ) {
			$icon_options['target'] = '_blank';
		}

		if ( 'mail' == $icon_options['social_network'] ) {
			$icon_options['href']   = ( 'http' === substr( $icon_options['social_link'], 0, 4 ) ) ? $icon_options['social_link'] : 'mailto:' . str_replace( 'mailto:', '', $icon_options['social_link'] );
			$icon_options['target'] = '_self';
		}

		if ( Avada()->settings->get( 'nofollow_social_links' ) ) {
			$icon_options['rel'] = 'nofollow';
		}

		if ( $args['icon_color'] ) {
			$icon_options['style'] .= 'color:' . $args['icon_color'] . ';';
		}

		if ( $is_custom_icon ) {
			// We need a top offset for boxed mode, based on the padding.
			$top_offset = ( self::$args['icon_boxed'] ) ? 'top:-' . $icon_padding . ';' : '';
			$icon_options['style'] .= 'position:relative;' . $top_offset;
		}

		if ( ! $is_custom_icon && self::$args['icon_boxed'] && $args['box_color'] ) {
			$icon_options['style'] .= 'background-color:' . $args['box_color'] . ';border-color:' . $args['box_color'] . ';';
		}

		if ( ! $is_custom_icon && self::$args['icon_boxed'] && ( self::$args['icon_boxed_radius'] || '0' === self::$args['icon_boxed_radius'] ) ) {
			self::$args['icon_boxed_radius'] = ( 'round' == self::$args['icon_boxed_radius'] ) ? '50%' : self::$args['icon_boxed_radius'];
			$icon_options['style'] .= 'border-radius:' . self::$args['icon_boxed_radius'] . ';';
		}

		if ( 'none' != strtolower( self::$args['tooltip_placement'] ) ) {
			$icon_options['data-placement'] = strtolower( self::$args['tooltip_placement'] );
			$tooltip = ( $tooltip == 'Googleplus' ) ? 'Google+' : $tooltip;
			$icon_options['data-title']  = $tooltip;
			$icon_options['data-toggle'] = 'tooltip';
		}

		$icon_options['title'] = $tooltip;

		$icon_options = apply_filters( 'fusion_attr_social-icons-class-icon', $icon_options );

		$properties = '';
		foreach ( $icon_options as $name => $value ) {
			$properties .= ! empty( $value ) ? ' ' . esc_html( $name ) . '="' . esc_attr( $value ) . '"' : esc_html( " {$name}" );
		}

		return '<a ' . $properties . '><span class="screen-reader-text">' . $tooltip . '</span>' . $custom . '</a>';

	}

}
