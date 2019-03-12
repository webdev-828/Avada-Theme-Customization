<?php
	/**
	 * AvadaRedux ThemeCheck
	 *
	 * @package   AvadaReduxFramework
	 * @author    Dovy <dovy@avadaredux.io>
	 * @license   GPL-3.0+
	 * @link      http://avadaredux.op
	 * @copyright 2015 AvadaReduxFramework
	 */

	/**
	 * AvadaRedux-ThemeCheck class
	 *
	 * @package AvadaRedux_ThemeCheck
	 * @author  Dovy <dovy@avadaredux.io>
	 */
	// Don't duplicate me!
	if ( ! class_exists( 'AvadaRedux_ThemeCheck' ) ) {
		class AvadaRedux_ThemeCheck {

			/**
			 * Plugin version, used for cache-busting of style and script file references.
			 *
			 * @since   1.0.0
			 * @var     string
			 */
			protected $version = '1.0.0';

			/**
			 * Instance of this class.
			 *
			 * @since    1.0.0
			 * @var      object
			 */
			protected static $instance = null;

			/**
			 * Instance of the AvadaRedux class.
			 *
			 * @since    1.0.0
			 * @var      object
			 */
			protected static $avadaredux = null;

			/**
			 * Details of the embedded AvadaRedux class.
			 *
			 * @since    1.0.0
			 * @var      object
			 */
			protected static $avadaredux_details = null;

			/**
			 * Slug for various elements.
			 *
			 * @since   1.0.0
			 * @var     string
			 */
			protected $slug = 'avadaredux_themecheck';

			/**
			 * Initialize the plugin by setting localization, filters, and administration functions.
			 *
			 * @since     1.0.0
			 */
			private function __construct() {

				if ( ! class_exists( 'ThemeCheckMain' ) ) {
					return;
				}

				// Load admin style sheet and JavaScript.
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

				add_action( 'themecheck_checks_loaded', array( $this, 'disable_checks' ) );
				add_action( 'themecheck_checks_loaded', array( $this, 'add_checks' ) );

			}

			/**
			 * Return an instance of this class.
			 *
			 * @since     1.0.0
			 * @return    object    A single instance of this class.
			 */
			public static function get_instance() {

				// If the single instance hasn't been set, set it now.
				if ( null == self::$instance ) {
					self::$instance = new self;
				}

				return self::$instance;
			}

			/**
			 * Return an instance of this class.
			 *
			 * @since     1.0.0
			 * @return    object    A single instance of this class.
			 */
			public static function get_avadaredux_instance() {

				// If the single instance hasn't been set, set it now.
				if ( null == self::$avadaredux && AvadaReduxFramework::$_as_plugin ) {
					self::$avadaredux = new AvadaReduxFramework();
					self::$avadaredux->init();
				}

				return self::$avadaredux;
			}

			/**
			 * Return the AvadaRedux path info, if had.
			 *
			 * @since     1.0.0
			 * @return    object    A single instance of this class.
			 */
			public static function get_avadaredux_details( $php_files = array() ) {
				if ( self::$avadaredux_details === null ) {
					foreach ( $php_files as $php_key => $phpfile ) {
						if ( strpos( $phpfile, 'class' . ' AvadaReduxFramework {' ) !== false ) {
							self::$avadaredux_details               = array(
								'filename' => strtolower( basename( $php_key ) ),
								'path'     => $php_key,
							);
							self::$avadaredux_details['dir']        = str_replace( basename( $php_key ), '', $php_key );
							self::$avadaredux_details['parent_dir'] = str_replace( basename( self::$avadaredux_details['dir'] ) . '/', '', self::$avadaredux_details['dir'] );
						}
					}
				}
				if ( self::$avadaredux_details === null ) {
					self::$avadaredux_details = false;
				}

				return self::$avadaredux_details;
			}

			/**
			 * Disable Theme-Check checks that aren't relevant for ThemeForest themes
			 *
			 * @since    1.0.0
			 */
			function disable_checks() {
				global $themechecks;

				//$checks_to_disable = array(
				//	'IncludeCheck',
				//	'I18NCheck',
				//	'AdminMenu',
				//	'Bad_Checks',
				//	'MalwareCheck',
				//	'Theme_Support',
				//	'CustomCheck',
				//	'EditorStyleCheck',
				//	'IframeCheck',
				//);
				//
				//foreach ( $themechecks as $keyindex => $check ) {
				//	if ( $check instanceof themecheck ) {
				//		$check_class = get_class( $check );
				//		if ( in_array( $check_class, $checks_to_disable ) ) {
				//			unset( $themechecks[$keyindex] );
				//		}
				//	}
				//}
			}

			/**
			 * Disable Theme-Check checks that aren't relevant for ThemeForest themes
			 *
			 * @since    1.0.0
			 */
			function add_checks() {
				global $themechecks;

				// load all the checks in the checks directory
				$dir = 'checks';
				foreach ( glob( dirname( __FILE__ ) . '/' . $dir . '/*.php' ) as $file ) {
					require_once $file;
				}
			}

			/**
			 * Register and enqueue admin-specific style sheet.
			 *
			 * @since     1.0.1
			 */
			public function enqueue_admin_styles() {
				$screen = get_current_screen();
				if ( 'appearance_page_themecheck' == $screen->id ) {
					wp_enqueue_style( $this->slug . '-admin-styles', AvadaReduxFramework::$_url . 'inc/themecheck/css/admin.css', array(), $this->version );
				}
			}

			/**
			 * Register and enqueue admin-specific JavaScript.
			 *
			 * @since     1.0.1
			 */
			public function enqueue_admin_scripts() {

				$screen = get_current_screen();

				if ( 'appearance_page_themecheck' == $screen->id ) {
					wp_enqueue_script( $this->slug . '-admin-script', AvadaReduxFramework::$_url . 'inc/themecheck/js/admin.js', array( 'jquery' ), $this->version );

					if ( ! isset( $_POST['themename'] ) ) {

						$intro = '';
						$intro .= '<h2>AvadaRedux Theme-Check</h2>';
						$intro .= '<p>Extra checks for AvadaRedux to ensure you\'re ready for marketplace submission to marketplaces.</p>';

						$avadaredux_check_intro['text'] = $intro;

						wp_localize_script( $this->slug . '-admin-script', 'avadaredux_check_intro', $avadaredux_check_intro );

					}
				}

			}
		}

		AvadaRedux_ThemeCheck::get_instance();
	}
