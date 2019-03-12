<?php

class Avada_Patcher_Filesystem {

	public static $target      = 'avada';
	public static $source      = null;
	public static $destination = null;

	public function __construct( $target = 'avada', $source = null, $destination = null ) {
		if ( null == $source || null == $destination ) {
			return;
		}
		self::$target      = $target;
		self::$source      = $source;
		self::$destination = $destination;
		// Instantiate the WordPress filesystem
		$this->init_filesystem();
		// Write the source contents to the destination.
		$this->write_file();
	}

	/**
	 * Make sure the WordPress Filesystem class in properly instatiated.
	 */
	public function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
	}

	/**
	 * Get remote contents
	 *
	 * @var $url        the URL we're getting our data from.
	 * @return bool|string 	the contents of the remote URL
	 */
	public function get_remote( $url ) {
		$response = wp_remote_get( $url );
		if ( is_array( $response ) ) {
			return $response['body'];
		}
		return false;
	}

	/**
	 * Write our contents to the destination file.
	 */
	public function write_file() {
		$contents = $this->get_remote( self::$source );
		if ( ! $contents ) {
			return;
		}

		$target = false;
		if ( 'avada' == self::$target ) {
			$target = get_template_directory();
		} elseif ( 'fusion-core' == self::$target ) {
			if ( defined( 'FUSION_CORE_PATH' ) ) {
				$target = FUSION_CORE_PATH;
			}
		}
		if ( false === $target ) {
			return;
		}
		global $wp_filesystem;
		$path = wp_normalize_path( $target . '/' . self::$destination );
		$wp_filesystem->put_contents( $path, $contents, FS_CHMOD_FILE );
	}

}
