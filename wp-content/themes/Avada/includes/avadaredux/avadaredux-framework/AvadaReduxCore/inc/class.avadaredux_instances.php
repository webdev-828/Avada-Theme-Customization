<?php

	/**
	 * AvadaRedux Framework Instance Container Class
	 * Automatically captures and stores all instances
	 * of AvadaReduxFramework at instantiation.
	 *
	 * @package     AvadaRedux_Framework
	 * @subpackage  Core
	 */
	class AvadaReduxFrameworkInstances {

		/**
		 * AvadaReduxFrameworkInstances
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * AvadaReduxFramework instances
		 *
		 * @var array
		 */
		private static $instances;

		/**
		 * Get Instance
		 * Get AvadaReduxFrameworkInstances instance
		 * OR an instance of AvadaReduxFramework by [opt_name]
		 *
		 * @param  string $opt_name the defined opt_name
		 *
		 * @return object                class instance
		 */
		public static function get_instance( $opt_name = false ) {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			if ( $opt_name && ! empty( self::$instances[ $opt_name ] ) ) {
				return self::$instances[ $opt_name ];
			}

			return self::$instance;
		}

		/**
		 * Get all instantiated AvadaReduxFramework instances (so far)
		 *
		 * @return [type] [description]
		 */
		public static function get_all_instances() {
			return self::$instances;
		}

		private function __construct() {

			add_action( 'avadaredux/construct', array( $this, 'capture' ), 5, 1 );

			$hash = md5( trailingslashit( network_site_url() ) . '-avadaredux' );
			add_action( 'wp_ajax_nopriv_' . $hash, array( $this, 'tracking_arg' ) );
			add_action( 'wp_ajax_' . $hash, array( $this, 'tracking_arg' ) );

			if (!class_exists('AvadaRedux_Tracking') || !method_exists('AvadaRedux_Tracking', 'trackingObject')) {
				$hash = md5( md5( AUTH_KEY . SECURE_AUTH_KEY . '-avadaredux' ) . '-support' );
				add_action( 'wp_ajax_nopriv_' . $hash, array( $this, 'support_args' ) );
				add_action( 'wp_ajax_' . $hash, array( $this, 'support_args' ) );
			}


		}

		function tracking_arg() {
			echo md5( AUTH_KEY . SECURE_AUTH_KEY . '-avadaredux' );
			die();
		}

		function support_args() {

			$this->options             = get_option( 'avadaredux-framework-tracking' );
			$this->options['dev_mode'] = false;

			if ( ! isset( $this->options['hash'] ) || ! $this->options['hash'] || empty( $this->options['hash'] ) ) {
				$this->options['hash'] = md5( network_site_url() . '-' . $_SERVER['REMOTE_ADDR'] );
				update_option( 'avadaredux-framework-tracking', $this->options );
			}

			if ( isset( $_GET['avadaredux_framework_disable_tracking'] ) && ! empty( $_GET['avadaredux_framework_disable_tracking'] ) ) {
				$this->options['allow_tracking'] = false;
				update_option( 'avadaredux-framework-tracking', $this->options );
			}

			if ( isset( $_GET['avadaredux_framework_enable_tracking'] ) && ! empty( $_GET['avadaredux_framework_enable_tracking'] ) ) {
				$this->options['allow_tracking'] = true;
				update_option( 'avadaredux-framework-tracking', $this->options );
			}

			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
			header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
			header( 'Cache-Control: no-store, no-cache, must-revalidate' );
			header( 'Cache-Control: post-check=0, pre-check=0', false );
			header( 'Pragma: no-cache' );
			$instances = AvadaReduxFrameworkInstances::get_all_instances();

			if ( isset( $_REQUEST['i'] ) && ! empty( $_REQUEST['i'] ) ) {
				if ( is_array( $instances ) && ! empty( $instances ) ) {
					foreach ( $instances as $opt_name => $data ) {
						if ( md5( $opt_name . '-debug' ) == $_REQUEST['i'] ) {
							$array = $instances[ $opt_name ];
						}
						if ($data->args['dev_mode']) {
							$this->options['dev_mode'] = $data->args['dev_mode'];
						}
					}
				}
				if ( isset( $array ) ) {
					if ( isset( $array->extensions ) && is_array( $array->extensions ) && ! empty( $array->extensions ) ) {
						foreach ( $array->extensions as $key => $extension ) {
							if ( isset( $extension->$version ) ) {
								$array->extensions[ $key ] = $extension->$version;
							} else {
								$array->extensions[ $key ] = true;
							}
						}
					}

					if ( isset( $array->import_export ) ) {
						unset( $array->import_export );
					}

					if ( isset( $array->debug ) ) {
						unset( $array->debug );
					}
				} else {
					die();
				}

			} else {
				$array = AvadaRedux_Helpers::trackingObject();
				if ( is_array( $instances ) && ! empty( $instances ) ) {
					$array['instances'] = array();
					foreach ( $instances as $opt_name => $data ) {
						$array['instances'][] = $opt_name;
					}
				}
				$array['key'] = md5( AUTH_KEY . SECURE_AUTH_KEY );
			}

			echo @json_encode( $array, true );
			die();
		}

		function capture( $AvadaReduxFramework ) {
			$this->store( $AvadaReduxFramework );
		}

		private function store( $AvadaReduxFramework ) {
			if ( $AvadaReduxFramework instanceof AvadaReduxFramework ) {
				$key                     = $AvadaReduxFramework->args['opt_name'];
				self::$instances[ $key ] = $AvadaReduxFramework;
			}
		}
	}
