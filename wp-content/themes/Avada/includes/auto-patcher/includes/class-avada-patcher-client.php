<?php

class Avada_Patcher_Client {

	/**
	 * @var bool|array
	 */
	public static  $patches;
	/**
	 * @var string
	 */
	private static $transient_name     = 'avada_patches';
	/**
	 * @var string
	 */
	public         $remote_patches_uri = 'https://theme-fusion.com/avada_patch/';

	/**
	 * Gets an array of all our patches.
	 * If we have these cached then use caches,
	 * otherwise query the server.
	 *
	 * @return array
	 */
	public static function get_patches() {
		$client = new self();
		if ( $client->get_cached() ) {
			self::$patches = $client->get_cached();
		} else {
			self::$patches = $client->query_patch_server();
			$client->cache_response();
		}
		return $client->prepare_patches( self::$patches );
	}

	/**
	 * Queries the patches server for a list of patches.
	 *
	 * @return bool|array
	 */
	private function query_patch_server() {
		$response = wp_remote_get( $this->remote_patches_uri );
		// return false if we couldn't get to the server
		if ( ! is_array( $response ) ) {
			return false;
		}
		// return false if the response does not have a body
		if ( ! isset( $response['body'] ) ) {
			return false;
		}
		$json = $response['body'];
		// Response may have comments from caching plugins making it invalid
		if ( false !== strpos( $response['body'], '<!--' ) ) {
			$json = explode( '<!--', $json );
			return json_decode( $json[0] );
		}
		return json_decode( $json );
	}

	/**
	 * Decodes patches if needed.
	 *
	 * @return array
	 */
	private function prepare_patches() {
		self::$patches = (array) self::$patches;
		$patches = array();

		if ( ! empty( self::$patches ) ) {
			foreach ( self::$patches as $patch_id => $patch_args ) {
				$patches[ $patch_id ] = (array) $patch_args;
				if ( empty( $patch_args ) ) {
					continue;
				}
				foreach ( $patch_args as $key => $patch ) {
					$patches[ $patch_id ][ $key ] = (array) $patch;
					foreach( $patches[ $patch_id ]['patch'] as $patch_key => $args ) {
						$args = (array) $args;
						$args['reference'] = base64_decode( $args['reference'] );
						$patches[ $patch_id ]['patch'][ $patch_key ] = $args;
					}
				}
			}
		}
		return $patches;
	}

	/**
	 * Gets the cached patches
	 */
	private function get_cached() {
		// Force getting new options from the server if needed.
		if ( $_GET && isset( $_GET['avada-reset-cached-patches'] ) ) {
			$this->reset_cache();
			return false;
		}
		return get_site_transient( self::$transient_name );
	}

	/**
	 * Caches the patches using transients
	 *
	 * @return void
	 */
	private function cache_response() {
		if ( false !== self::$patches && ! empty( self::$patches ) ) {
			// Cache for 30 minutes
			set_site_transient( self::$transient_name, self::$patches, 30 * 60 );
		}
	}

	/**
	 * Resets the transient cache
	 *
	 * @return void
	 */
	private function reset_cache() {
		delete_site_transient( self::$transient_name );
	}
}
