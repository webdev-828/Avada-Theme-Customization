<?php
/**
 * Social Icons Class
 *
 * WARNING: This file is part of the Fusion Core Framework.
 * Do not edit the core files.
 * Add any modifications necessary under a child theme.
 *
 * @package  Fusion/Framework
 * @author   ThemeFusion
 * @link	 http://theme-fusion.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Avada_Social_Icons extends Avada_Social_Icon {

	/**
	 * Renders all soical icons not belonging to shortcodes
	 *
	 * @since 3.5.0
	 * @param  array   $args Holding all necessarry data for social icons
	 * @return string  The HTML mark up for social icons, incl. wrapping container
	 */
	public function render_social_icons( $args ) {

		parent::$args = $args;

		$html  = '';
		$icons = '';
		// Get the social networks setting
		$social_networks = Avada()->settings->get( 'social_media_icons' );
		// Get a list of all the available social networks
		$social_networks_full_array = Avada_Data::fusion_social_icons( true, true );

		// Count how many social icons we have
		$count = count( $social_networks );
		// Get the default color values for social media depending on their location
		$footer_social_links_icon_color = Avada()->settings->get( 'footer_social_links_icon_color' );
		$footer_social_links_box_color  = Avada()->settings->get( 'footer_social_links_box_color' );
		$header_social_links_icon_color = Avada()->settings->get( 'header_social_links_icon_color' );
		$header_social_links_box_color  = Avada()->settings->get( 'header_social_links_box_color' );

		$use_brand_colors = false;
		if ( isset( parent::$args['position'] ) ) {
			if ( 'footer' == parent::$args['position'] ) {
				if ( 'brand' == Avada()->settings->get( 'footer_social_links_color_type' ) ) {
					$use_brand_colors = true;
				}
			} else {
				if ( 'brand' == Avada()->settings->get( 'header_social_links_color_type' ) ) {
					$use_brand_colors = true;
				}
			}
		}

		// Check that we have social networks defined before proceeding
		if ( ! empty( $social_networks ) && isset( $social_networks['url'] ) && ! empty( $social_networks['url'] ) ) {
			for ( $i = 0; $i <= count( $social_networks['url'] ) - 1; $i++ ) {
				// Get the icon's arguments
				$icon                    = ( isset( $social_networks['icon'][ $i ] ) ) ? str_replace( '_link', '', $social_networks['icon'][ $i ] ) : false;
				$url                     = ( isset( $social_networks['url'][ $i ] ) && ! empty( $social_networks['url'][ $i ] ) ) ? $social_networks['url'][ $i ] : false;
				$header_box_color        = ( isset( $social_networks['header_box_color'][ $i ] ) && ! empty( $social_networks['header_box_color'][ $i ] ) ) ? $social_networks['header_box_color'][ $i ] : false;
				$footer_box_color        = ( isset( $social_networks['footer_box_color'][ $i ] ) && ! empty( $social_networks['footer_box_color'][ $i ] ) ) ? $social_networks['footer_box_color'][ $i ] : false;
				$custom_title            = ( isset( $social_networks['custom_title'][ $i ] ) && ! empty( $social_networks['custom_title'][ $i ] ) ) ? $social_networks['custom_title'][ $i ] : '';
				$custom_source           = ( isset( $social_networks['custom_source'][ $i ] ) && isset( $social_networks['custom_source'][ $i ]['url'] ) && ! empty( $social_networks['custom_source'][ $i ]['url'] ) ) ? $social_networks['custom_source'][ $i ]['url'] : '';
				$custom_source_height    = ( isset( $social_networks['custom_source'][ $i ] ) && isset( $social_networks['custom_source'][ $i ]['height'] ) && ! empty( $social_networks['custom_source'][ $i ]['height'] ) ) ? $social_networks['custom_source'][ $i ]['height'] : '';
				$custom_source_width     = ( isset( $social_networks['custom_source'][ $i ] ) && isset( $social_networks['custom_source'][ $i ]['width'] ) && ! empty( $social_networks['custom_source'][ $i ]['width'] ) ) ? $social_networks['custom_source'][ $i ]['width'] : '';

				// Hack for Google+
				if ( in_array( $icon, array( 'google', 'gplus' ) ) ) {
					$icon = 'googleplus';
				}
				// Make sure we have a URL & an icon defined
				if ( $icon && $url ) {
					$icon_args = array(
						'icon' => $icon,
						'url'  => $url,
					);

					$icon_args['icon_color'] = Avada()->settings->get( 'header_social_links_icon_color' );
					$icon_args['box_color']  = Avada()->settings->get( 'header_social_links_box_color' );
					// Use footer args when appropriate
					if ( isset( parent::$args['position'] ) && 'footer' == parent::$args['position'] ) {
						$icon_args['icon_color'] = Avada()->settings->get( 'footer_social_links_icon_color' );
						$icon_args['box_color']  = Avada()->settings->get( 'footer_social_links_box_color' );
					}
					if ( $use_brand_colors ) {
						$brand_icon  = ( 'googleplus' == $icon ) ? 'gplus' : $icon;
						$brand_color = $social_networks_full_array[ $brand_icon ]['color'];
						$icon_args['icon_color'] = ( parent::$args['icon_boxed'] ) ? '#ffffff' : $social_networks_full_array[ $brand_icon ]['color'];
						$icon_args['box_color']  = ( parent::$args['icon_boxed'] ) ? $social_networks_full_array[ $brand_icon ]['color'] : 'transparent';
					}

					// Check if are on the last social icon
					$icon_args['last'] = ( $count == $i ) ? true : false;

					// Custom icons
					if ( 'custom' == $icon && ! empty( $custom_source ) ) {
						$icon_args['custom_source'] = $custom_source;
						$icon_args['custom_source_height'] = $custom_source_height;
						$icon_args['custom_source_width'] = $custom_source_width;
						$icon_args['custom_title']  = $custom_title;
					}

					$icons .= parent::get_markup( $icon_args );

				}
			}
		}

		if ( ! empty( $icons ) ) {
			$attr = array( 'class' => 'fusion-social-networks' );
			if ( parent::$args['icon_boxed'] ) {
				$attr['class'] .= ' boxed-icons';
			}
			$html = '<div ' . fusion_attr( 'social-icons-class-social-networks', $attr ) . '><div ' . fusion_attr( 'fusion-social-networks-wrapper' ) . '>' . $icons;
			if ( isset( parent::$args['position'] ) && ( 'header' == parent::$args['position'] || 'footer' == parent::$args['position'] ) ) {
				$html .= '</div></div>';
			} else {
				$html .= '<div class="fusion-clearfix"></div></div></div>';
			}
		}

		return $html;

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
