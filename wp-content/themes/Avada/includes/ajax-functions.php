<?php
/**
 * Contains functions for Ajax Queries
 *
 * @author		ThemeFusion
 * @copyright	(c) Copyright by ThemeFusion
 * @link		http://theme-fusion.com
 * @package 	FusionFramework
 * @since		Version 1.0
 */

add_action( 'wp_ajax_fusion_cache_map', 'fusion_cache_map' );
add_action( 'wp_ajax_nopriv_fusion_cache_map', 'fusion_cache_map' );

function fusion_cache_map() {
	check_ajax_referer( 'avada_admin_ajax', 'security' );

	$addresses_to_cache = get_option( 'fusion_map_addresses' );

	foreach ( $_POST['addresses'] as $address ) {

		if ( isset( $address['latitude'] ) && isset( $address['longitude'] ) ) {
			$addresses_to_cache[trim( $address['address'] )] = array(
				'address'   => trim( $address['address'] ),
				'latitude'  => $address['latitude'],
				'longitude' => $address['longitude']
			);

			if ( isset( $address['geocoded_address'] ) && $address['geocoded_address'] ) {
				$addresses_to_cache[trim( $address['address'] )]['address'] = $address['geocoded_address'];
			}
		}

	}

	update_option( 'fusion_map_addresses', $addresses_to_cache );

	wp_die();

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
