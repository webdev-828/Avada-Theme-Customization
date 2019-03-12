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

class Avada_Social_Sharing extends Avada_Social_Icon {

	/**
	 * Renders all soical icons not belonging to shortcodes
	 *
	 * @since 3.5.0
	 * @param  array   $args Holding all necessarry data for social icons
	 * @return string  The HTML mark up for social icons, incl. wrapping container
	 */
	public function render_social_icons( $args ) {

		parent::$args = $args;

		// Get a list of all the available social networks
		$social_networks_full_array = Avada_Data::fusion_social_icons( true, true );

		if ( isset( parent::$args['authorpage'] ) && parent::$args['authorpage'] == 'yes' ) {
			$social_networks = $this->get_authorpage_social_links_array( parent::$args );
		} else {
			$social_networks = $this->get_sharingbox_social_links_array( parent::$args );
		}

		$html = $icons = '';

		$i = 0;
		$per_icon_colors = ( 'brand' == Avada()->settings->get( 'sharing_social_links_color_type' ) ) ? true : false;
		$number_of_social_networks = count( $social_networks );
		foreach ( $social_networks as $network => $icon_args ) {

			$icon_options = array(
				'social_network' => $network,
				'social_link'    => $icon_args['url'],
			);

			if ( $per_icon_colors ) {
				$network_for_colors = str_replace( 'sharing_', '', $network );
				$network_for_colors = ( in_array( $network_for_colors, array( 'google', 'googleplus' ) ) ) ? 'gplus' : $network_for_colors;
				if ( parent::$args['icon_boxed'] ) {
					$icon_options['icon_color'] = '#ffffff';
					$icon_options['box_color']  = $social_networks_full_array[ $network_for_colors ]['color'];
				} else {
					$icon_options['icon_color'] = $social_networks_full_array[ $network_for_colors ]['color'];
					$icon_options['box_color']  = '#ffffff';
				}
			} else {
				$icon_options['icon_color'] = Avada()->settings->get( 'sharing_social_links_icon_color' );
				$icon_options['box_color']  = Avada()->settings->get( 'sharing_social_links_box_color' );
			}

			// Check if are on the last social icon;
			// $i needs to be incremented first to make it match the count() value
			$i++;
			$icon_options['last'] = ( $i == $number_of_social_networks ) ? true : false;

			$icons .= parent::get_markup( $icon_options );

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

	/**
	 * Set up the array for sharing box social networks.
	 *
	 * @since 3.5.0
	 * @param  array  $args Holding all necessarry data for social icons
	 * @return array  The social links array containing the social media and links to them.
	 */
	function get_sharingbox_social_links_array( $args ) {

		$social_links_array = array();

		if ( Avada()->settings->get( 'sharing_facebook' ) ) {
			$social_links_array['facebook'] = array(
				'url'        => 'http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=' . $args['link'] . '&p&#91;images&#93;&#91;0&#93;=' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) . '&p&#91;title&#93;=' . rawurlencode( $args['title'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_twitter' ) ) {
			$social_links_array['twitter'] = array(
				'url'        => 'https://twitter.com/share?text=' . rawurlencode( html_entity_decode( $args['title'], ENT_COMPAT, 'UTF-8' ) ) . '&url=' . rawurlencode( $args['link'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_linkedin' ) ) {
			$social_links_array['linkedin'] = array(
				'url'        => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $args['link'] . '&amp;title=' . rawurlencode( $args['title'] ) . '&amp;summary=' . rawurlencode( $args['description'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_reddit' ) ) {
			$social_links_array['reddit'] = array(
				'url'        => 'http://reddit.com/submit?url=' . $args['link'] . '&amp;title=' . rawurlencode( $args['title'] ),
			);
		}

		if( Avada()->settings->get( 'sharing_tumblr' ) ) {
			$social_links_array['tumblr'] = array(
				'url'        => 'http://www.tumblr.com/share/link?url=' . rawurlencode( $args['link'] ) . '&amp;name=' . rawurlencode( $args['title'] ) .'&amp;description=' . rawurlencode( $args['description'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_google' ) ) {
			$social_links_array['googleplus'] = array(
				'url'        => 'https://plus.google.com/share?url=' . $args['link'],
			);
		}

		if ( Avada()->settings->get( 'sharing_pinterest' ) ) {
			$social_links_array['pinterest'] = array(
				'url'        => 'http://pinterest.com/pin/create/button/?url=' . urlencode( $args['link'] ) . '&amp;description=' . rawurlencode( $args['description'] ) . '&amp;media=' . rawurlencode( $args['pinterest_image'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_vk' ) ) {
			$social_links_array['vk'] = array(
				'url'        => 'http://vkontakte.ru/share.php?url=' . rawurlencode( $args['link'] ) . '&amp;title=' . rawurlencode( $args['title'] ) . '&amp;description=' . rawurlencode( $args['description'] ),
			);
		}

		if ( Avada()->settings->get( 'sharing_email' ) ) {
			$social_links_array['email'] = array(
				'url'        => 'mailto:?subject=' . $args['title'] . '&amp;body=' . $args['link'],
			);
		}

		return $social_links_array;

	}

	/**
	 * Set up the array for author page social networks.
	 *
	 * @since 3.5.0
	 * @param  array  $args Holding all necessarry data for social icons
	 * @return array  The social links array containing the social media and links to them.
	 */
	function get_authorpage_social_links_array( $args ) {

		$social_links_array = array();

		if ( get_the_author_meta( 'author_facebook', $args['author_id'] ) ) {
			$social_links_array['facebook'] = array(
				'url'        => get_the_author_meta( 'author_facebook', $args['author_id'] ),
			);
		}

		if ( get_the_author_meta( 'author_twitter', $args['author_id'] ) ) {
			$social_links_array['twitter'] = array(
				'url'        => get_the_author_meta( 'author_twitter', $args['author_id'] ),
			);
		}

		if ( get_the_author_meta( 'author_linkedin', $args['author_id'] ) ) {
			$social_links_array['linkedin'] = array(
				'url'        => get_the_author_meta( 'author_linkedin', $args['author_id'] ),
			);
		}

		if ( get_the_author_meta( 'author_dribble', $args['author_id'] ) ) {
			$social_links_array['dribbble'] = array(
				'url'        => get_the_author_meta( 'author_dribble', $args['author_id'] ),
			);
		}

		if ( get_the_author_meta( 'author_gplus', $args['author_id'] ) ) {
			$social_links_array['googleplus'] = array(
				'url'        => get_the_author_meta( 'author_gplus', $args['author_id'] ),
			);
		}

		if ( get_the_author_meta( 'email', $args['author_id'] ) ) {
			$social_links_array['email'] = array(
				'url'        => 'mailto:' . get_the_author_meta( 'email', $args['author_id'] ),
			);
		}

		return $social_links_array;
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
