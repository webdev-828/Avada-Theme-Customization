<?php

class Avada_Dynamic_CSS {

	public static $mode;

	public function __construct() {

		$this->add_options();

		// Set mode
		add_action( 'wp', array( $this, 'set_mode' ) );

		// When a post is saved, reset its caches to force-regenerate the CSS.
		add_action( 'save_post', array( $this, 'reset_post_transient' ) );
		add_action( 'save_post', array( $this, 'post_update_option' ) );

		// When we change the options, reset all caches so that all CSS can be re-generated
		add_action( 'avada_save_options', array( $this, 'reset_all_transients' ) );
		add_action( 'avada_save_options', array( $this, 'clear_cache' ) );
		add_action( 'avada_save_options', array( $this, 'global_reset_option' ) );
		add_action( 'avada_save_options', array( $this, 'clear_cache' ) );
		add_action( 'customize_save_after', array( $this, 'reset_all_caches' ) );

		// Add the CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_css' ), 11 );
		add_action( 'wp_head', array( $this, 'add_inline_css' ), 999 );

	}

	/**
	 * get the current page ID.
	 *
	 * @return  int the current page ID.
	 */
	public static function page_id() {
		global $post;
		$id = false;
		if ( isset( $post ) ) {
			// If this is a  singular page/post then set ID to the page ID.
			// If not, then set it to false.
			$id = ( is_singular() ) ? $post->ID : false;
			// If we're on the WooCommerce shop page, get the ID of the page
			// using the 'woocommerce_shop_page_id' option
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				$id = get_option( 'woocommerce_shop_page_id' );
			}
			// If we're on the posts page, get the ID of the page
			// using the 'page_for_posts' option.
			if ( is_home() ) {
				$id = get_option( 'page_for_posts' );
			}
		}
		return $id;
	}

	/**
	 * Determine if we're using file mode or inline mode.
	 *
	 * @return  string file/inline
	 */
	public static function set_mode() {

		// Check if we're using file mode or inline mode.
		// This simply checks the dynamic_css_compiler options.
		$mode = ( Avada()->settings->get( 'dynamic_css_compiler' ) ) ? 'file' : 'inline';

		// ALWAYS use 'inline' mode when in the customizer.
		global $wp_customize;
		if ( $wp_customize ) {
			return 'inline';
		}

		// Additional checks for file mode.
		if ( 'file' == $mode && self::needs_update() ) {
			// Only allow processing 1 file every 5 seconds.
			$current_time = (int) time();
			$last_time    = (int) get_option( 'avada_dynamic_css_time' );

			if ( 5 <= ( $current_time - $last_time ) ) {
				// If it's been more than 5 seconds since we last compiled a file
				// then attempt to write to the file.
				// If the file-write succeeds then set mode to 'file'.
				// If the file-write fails then set mode to 'inline'.
				$mode = ( self::can_write() && self::make_css() ) ? 'file' : 'inline';
				// If the file exists then set mode to 'file'
				// If it does not exist then set mode to 'inline'.
				if ( 'file' == $mode ) {
					$mode = ( file_exists( self::file( 'path' ) ) ) ? 'file' : 'inline';
				}
			} else {
				// It's been less than 5 seconds since we last compiled a CSS file
				// In order to prevent server meltdowns on weak servers we'll use inline mode instead.
				$mode = 'inline';
			}
		}

		self::$mode = $mode;

	}

	/**
	 * Enqueue the dynamic CSS.
	 *
	 * @return  void
	 */
	public function enqueue_dynamic_css() {

		if ( 'file' == self::$mode ) {
			// Yay! we're using a file for our CSS, so enqueue it.
			wp_enqueue_style( 'avada-dynamic-css', self::file( 'uri' ), array( 'avada-stylesheet' ) );
		}
		// In case of no file mode, the CSS file is not enqueued
		// but it is added in header.php
		// No further action is required here.
	}

	/**
	 * This function takes care of creating the CSS.
	 *
	 * @return  bool 	true/false depending on whether the file is successfully created or not.
	 */
	public static function make_css() {

		global $wp_filesystem;

		// Instantiate the Wordpress filesystem.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		// Creates the content of the CSS file.
		// We're adding a warning at the top of the file to prevent users from editing it.
		// The warning is then followed by the actual CSS content.
		$content = "/********* Compiled - Do not edit *********/\n" . avada_dynamic_css_cached();

		// When using domain-mapping plugins we have to make sure that any references to the original domain
		// are replaced with references to the mapped domain.
		// We're also stripping protocols from these domains so that there are no issues with SSL certificates.
		if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {

			if ( function_exists( 'domain_mapping_siteurl' ) && function_exists( 'get_original_url' ) ) {

				// The mapped domain of the site
				$mapped_domain   = domain_mapping_siteurl( false );
				$mapped_domain   = str_replace( 'https://', '//', $mapped_domain );
				$mapped_domain   = str_replace( 'http://', '//', $mapped_domain );

				// The original domain of the site
				$original_domain = get_original_url( 'siteurl' );
				$original_domain = str_replace( 'https://', '//', $original_domain );
				$original_domain = str_replace( 'http://', '//', $original_domain );

				// Replace original domain with mapped domain
				$content = str_replace( $original_domain, $mapped_domain, $content );

			}

		}

		// Replace wp-content url with relative path
		$upload_dir = wp_upload_dir();
		$content    = str_replace( $upload_dir['baseurl'], '..', $content );
		$content    = str_replace( content_url(), '../..', $content );

		// Strip protocols. This helps avoid any issues with https sites.
		$content = str_replace( 'https://', '//', $content );
		$content = str_replace( 'http://', '//', $content );

		// Since we've already checked if the file is writable in the can_write() method (called by the mode() method)
		// it's safe to continue without any additional checks as to the validity of the file.
		if ( ! $wp_filesystem->put_contents( self::file( 'path' ), $content, FS_CHMOD_FILE ) ) {
			// Writing to the file failed
			return false;
		} else {
			// Writing to the file succeeded.
			// Update the opion in the db so that we know the css for this post has been successfully generated
			// and then return true.
			$page_id = ( self::page_id() ) ? self::page_id() : 'global';
			$option  = get_option( 'avada_dynamic_css_posts', array() );
			$option[ $page_id ] = true;
			update_option( 'avada_dynamic_css_posts', $option );
			// Update the 'avada_dynamic_css_time' option.
			self::update_saved_time();

			return true;
		}

	}

	/*
	 * Determines if the CSS file is writable.
	 *
	 * @return bool
	 */
	public static function can_write() {

		// Get the blog ID.
		$blog_id = 1;
		if ( is_multisite() ) {
			$current_site = get_blog_details();
			$blog_id      = $current_site->blog_id;
		}

		// Get the upload directory for this site.
		$upload_dir = wp_upload_dir();
		// If this is a multisite installation, append the blogid to the filename
		$blog_id = ( is_multisite() && $blog_id > 1 ) ? '_blog-' . $blog_id : null;
		$page_id = ( self::page_id() ) ? self::page_id() : 'global';

		$file_name   = '/avada' . $blog_id . '-' . $page_id . '.css';
		$folder_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'avada-styles';

		// Does the folder exist?
		if ( file_exists( $folder_path ) ) {
			// Folder exists, but is it actually writable?
			if ( ! is_writable( $folder_path ) ) {
				// Folder is not writable.
				// Does the file exist?
				if ( ! file_exists( $folder_path . $file_name ) ) {
					// If the file does not exist, then we can't create it
					// since its parent folder is not writable.
					return false;
				} else {
					// The file exists. Is it writable?
					if ( ! is_writable( $folder_path . $file_name ) ) {
						// Nope, it's not writable.
						return false;
					}
				}
			} else {
				// The folder is writable.
				// Does the file exist?
				if ( file_exists( $folder_path . $file_name ) ) {
					// File exists. Is it writable?
					if ( ! is_writable( $folder_path . $file_name ) ) {
						// Nope, it's not writable
						return false;
					}
				}
			}
		} else {
			// Can we create the folder?
			// returns true if yes and false if not.
			return wp_mkdir_p( $folder_path );
		}

		// If we passed all of the above tests
		// then the file is writable.
		return true;

	}


	/*
	 * Gets the css path or url to the stylesheet
	 *
	 * @var 	string 	path/url
	 * @return 	string  path or url to the file depending on the $target var.
	 *
	 */
	public static function file( $target = 'path' ) {

		/**
		 * Get the blog ID
		 */
		if ( is_multisite() ) {
			$current_site = get_blog_details();
			$blog_id = $current_site->blog_id;
		} else {
			$blog_id = 1;
		}

		// Get the upload directory for this site.
		$upload_dir = wp_upload_dir();
		// If this is a multisite installation, append the blogid to the filename
		$blog_id = ( is_multisite() && $blog_id > 1 ) ? '_blog-' . $blog_id : null;
		$page_id = ( self::page_id() ) ? self::page_id() : 'global';

		$file_name   = 'avada' . $blog_id . '-' . $page_id . '.css';
		$folder_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'avada-styles';
		// The complete path to the file.
		$file_path = $folder_path . DIRECTORY_SEPARATOR . $file_name;
		// Get the URL directory of the stylesheet
		$css_uri_folder = $upload_dir['baseurl'];
		// Build the URL of the file
		$css_uri = trailingslashit( $css_uri_folder ) . 'avada-styles/' . $file_name;
		// Take care of domain mapping.
		// When using domain mapping we have to make sure that the URL to the file
		// does not include the original domain but instead the mapped domain.
		if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
			if ( function_exists( 'domain_mapping_siteurl' ) && function_exists( 'get_original_url' ) ) {
				$mapped_domain   = domain_mapping_siteurl( false );
				$original_domain = get_original_url( 'siteurl' );
				$css_uri = str_replace( $original_domain, $mapped_domain, $css_uri );
			}
		}
		// Strip protocols from the URL.
		// Make sure we don't have any issues with sites using HTTPS/SSL
		$css_uri = str_replace( 'https://', '//', $css_uri );
		$css_uri = str_replace( 'http://', '//', $css_uri );

		// Return the path or the URL
		// depending on the $target we have defined when calling this method.
		if ( 'path' == $target ) {
			return $file_path;
		} elseif ( 'url' == $target || 'uri' == $target ) {
			$timestamp = ( file_exists( $file_path ) ) ? '?timestamp=' . filemtime( $file_path ) : '';
			return $css_uri . $timestamp;
		}

	}

	/**
	 * Reset ALL CSS transient caches.
	 *
	 * @return  void
	 */
	public function reset_all_transients() {
		global $wpdb;
		// Build the query to delete all avada transients and execute the required SQL
		$sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_avada_dynamic_css_%'";
		$wpdb->query( $sql );
	}

	/**
	 * Reset the dynamic CSS transient for a post.
	 *
	 * @param  $post_id       the ID of the post that's being reset.
	 * @return  void
	 */
	public function reset_post_transient( $post_id ) {
		delete_transient( 'avada_dynamic_css_' . $post_id );
	}

	/**
	 * Create settings.
	 *
	 * @return  void
	 */
	public function add_options() {
		// The 'avada_dynamic_css_posts' option will hold an array of posts that have had their css generated.
		// We can use that to keep track of which pages need their CSS to be recreated and which don't.
		add_option( 'avada_dynamic_css_time', array(), '', 'yes' );
		// The 'avada_dynamic_css_time' option holds the time the file writer was last used.
		add_option( 'avada_dynamic_css_time', time(), '', 'yes' );
	}

	/**
	 * Update the avada_dynamic_css_posts option when a post is saved.
	 * This adds the current post's ID in the array of IDs that the 'avada_dynamic_css_posts' option has.
	 *
	 * @return  void
	 */
	public function post_update_option( $post_id ) {
		$option = get_option( 'avada_dynamic_css_posts', array() );
		$option[ $post_id ] = false;
		update_option( 'avada_dynamic_css_posts', $option );
	}

	/**
	 * Update the avada_dynemic_css_posts option when the theme options are saved.
	 * This basically empties the array of page IDS from the 'avada_dynamic_css_posts' option
	 */
	public function global_reset_option() {
		update_option( 'avada_dynamic_css_posts', array() );
	}

	/**
	 * Do we need to update the CSS file?
	 *
	 * @return  bool
	 */
	public static function needs_update() {

		// Get the 'avada_dynamic_css_posts' option from the DB
		$option  = get_option( 'avada_dynamic_css_posts', array() );
		// Get the current page ID
		$page_id = ( self::page_id() ) ? self::page_id() : 'global';
		// If the CSS file does not exist then we definitely need to regenerate the CSS.
		if ( ! file_exists( self::file( 'path' ) ) ) {
			return true;
		}

		// Check if the time of the dynamic-css.php file is newer than the css file itself.
		// If yes, then we need to update the css.
		// This is primarily added here for development purposes.
		$dynamic_css_script = get_template_directory() . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'dynamic_css.php';
		if ( filemtime( $dynamic_css_script ) > filemtime( self::file( 'path' ) ) ) {
			return true;
		}
		// If the current page ID exists in the array of pages defined in the 'avada_dynamic_css_posts' option
		// then the page has already been compiled and we don't need to re-compile it.
		// If it's not in the array then it has not been compiled before so we need to update it.
		return ( ! isset( $option[ $page_id ] ) || ! $option[ $page_id ] ) ? true : false;

	}

	/**
	 * Update the 'avada_dynamic_css_time' option.
	 * This will save in the db the last time that the compiler has run.
	 *
	 * @return  void
	 */
	public static function update_saved_time() {
		update_option( 'avada_dynamic_css_time', time() );
	}

	/**
	 * Clear cache from:
	 *  - W3TC,
	 *  - WordPress Total Cache
	 *  - WPEngine
	 *  - Varnish
	 */
	public function clear_cache() {

		// if W3 Total Cache is being used, clear the cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}

		// if WP Super Cache is being used, clear the cache
		else if ( function_exists( 'wp_cache_clean_cache' ) ) {
			global $file_prefix;
			wp_cache_clean_cache( $file_prefix );
		}

		//  Clear caches on WPEngine-hosted sites
		else if ( class_exists( 'WpeCommon' ) ) {
			WpeCommon::purge_memcached();
			WpeCommon::clear_maxcdn_cache();
			WpeCommon::purge_varnish_cache();
		}

		// Clear Varnish caches
		if ( Avada()->settings->get( 'dynamic_css_compiler' ) && Avada()->settings->get( 'cache_server_ip' ) ) {
			$this->clear_varnish_cache( self::file( 'url' ) );
		}

	}

	/**
	 * Clear varnish cache for the dynamic CSS file
	 *
	 * @param  $url     the URL of the file whose cache we want to reset
	 * @return  void
	 */
	public function clear_varnish_cache( $url ) {
		// Parse the URL for proxy proxies
		$p = parse_url( $url );

		$varnish_x_purgemethod = ( isset( $p['query'] ) && ( 'vhp=regex' == $p['query'] ) ) ? 'regex' : 'default';

		// Build a varniship
		$varniship = get_option( 'vhp_varnish_ip' );
		if ( Avada()->settings->get( 'cache_server_ip' ) ) {
			$varniship = Avada()->settings->get( 'cache_server_ip' );
		} else if ( defined( 'VHP_VARNISH_IP' ) && VHP_VARNISH_IP != false ) {
			$varniship = VHP_VARNISH_IP;
		}
		// If we made varniship, let it sail.
		$purgeme = ( isset( $varniship ) && $varniship != null ) ? $varniship : $p['host'];

		wp_remote_request( 'http://' . $purgeme,
			array(
				'method'  => 'PURGE',
				'headers' => array(
					'host'           => $p['host'],
					'X-Purge-Method' => $varnish_x_purgemethod
				)
			)
		);
	}

	/**
	 * Add Inline CSS
	 *
	 * @return  void
	 */
	public function add_inline_css() {
		global $wp_customize;
		// Inline Dynamic CSS
		// This is here because we need it after all Avada CSS
		// and W3TC can combine it incorrectly
		if ( 'inline' == self::$mode || $wp_customize ) {
			echo "<style id='avada-stylesheet-inline-css' type='text/css'>" . avada_dynamic_css_cached() . '</style>';
		}

	}

	/**
	 * This is just a facilitator that will allow us to reset everything.
	 * Its only job is calling the other methods from this class and reset parts of our caches
	 *
	 * @return  void
	 */
	public function reset_all_caches() {
		$this->reset_all_transients();
		$this->clear_cache();
		$this->global_reset_option();
		$this->clear_cache();
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
