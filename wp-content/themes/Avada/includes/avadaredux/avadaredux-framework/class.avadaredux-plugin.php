<?php

	/**
	 * AvadaReduxFrameworkPlugin main class
	 *
	 * @package     AvadaReduxFramework\AvadaReduxFrameworkPlugin
	 * @since       3.0.0
	 */

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'AvadaReduxFrameworkPlugin' ) ) {

		/**
		 * Main AvadaReduxFrameworkPlugin class
		 *
		 * @since       3.0.0
		 */
		class AvadaReduxFrameworkPlugin {

			/**
			 * @const       string VERSION The plugin version, used for cache-busting and script file references
			 * @since       3.0.0
			 */

			const VERSION = '3.5.9.8';

			/**
			 * @access      protected
			 * @var         array $options Array of config options, used to check for demo mode
			 * @since       3.0.0
			 */
			protected $options = array();

			/**
			 * Use this value as the text domain when translating strings from this plugin. It should match
			 * the Text Domain field set in the plugin header, as well as the directory name of the plugin.
			 * Additionally, text domains should only contain letters, number and hypens, not underscores
			 * or spaces.
			 *
			 * @access      protected
			 * @var         string $plugin_slug The unique ID (slug) of this plugin
			 * @since       3.0.0
			 */
			protected $plugin_slug = 'avadaredux-framework';

			/**
			 * @access      protected
			 * @var         string $plugin_screen_hook_suffix The slug of the plugin screen
			 * @since       3.0.0
			 */
			protected $plugin_screen_hook_suffix = null;

			/**
			 * @access      protected
			 * @var         string $plugin_network_activated Check for plugin network activation
			 * @since       3.0.0
			 */
			protected $plugin_network_activated = null;

			/**
			 * @access      private
			 * @var         \AvadaReduxFrameworkPlugin $instance The one true AvadaReduxFrameworkPlugin
			 * @since       3.0.0
			 */
			private static $instance;

			/**
			 * Get active instance
			 *
			 * @access      public
			 * @since       3.1.3
			 * @return      self::$instance The one true AvadaReduxFrameworkPlugin
			 */
			public static function instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;
					self::$instance->get_avadaredux_options();
					self::$instance->includes();
					self::$instance->hooks();
				}

				return self::$instance;
			}

			// Shim since we changed the function name. Deprecated.
			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;
					self::$instance->get_avadaredux_options();
					self::$instance->includes();
					self::$instance->hooks();
				}

				return self::$instance;
			}

			/**
			 * Get AvadaRedux options
			 *
			 * @access      public
			 * @since       3.1.3
			 * @return      void
			 */
			public function get_avadaredux_options() {

				// Setup defaults
				$defaults = array(
					'demo' => false,
				);

				// If multisite is enabled
				if ( is_multisite() ) {

					// Get network activated plugins
					$plugins = get_site_option( 'active_sitewide_plugins' );

					foreach ( $plugins as $file => $plugin ) {
						if ( strpos( $file, 'avadaredux-framework.php' ) !== false ) {
							$this->plugin_network_activated = true;
							$this->options                  = get_site_option( 'AvadaReduxFrameworkPlugin', $defaults );
						}
					}
				}

				// If options aren't set, grab them now!
				if ( empty( $this->options ) ) {
					$this->options = get_option( 'AvadaReduxFrameworkPlugin', $defaults );
				}
			}

			/**
			 * Include necessary files
			 *
			 * @access      public
			 * @since       3.1.3
			 * @return      void
			 */
			public function includes() {
				// Include AvadaReduxCore
				if ( file_exists( dirname( __FILE__ ) . '/AvadaReduxCore/framework.php' ) ) {
					require_once dirname( __FILE__ ) . '/AvadaReduxCore/framework.php';
				}

				if ( isset( AvadaReduxFramework::$_as_plugin ) ) {
					AvadaReduxFramework::$_as_plugin = true;
				}

				if ( file_exists( dirname( __FILE__ ) . '/AvadaReduxCore/avadaredux-extensions/config.php' ) ) {
					require_once dirname( __FILE__ ) . '/AvadaReduxCore/avadaredux-extensions/config.php';
				}

				// Include demo config, if demo mode is active
				if ( $this->options['demo'] && file_exists( dirname( __FILE__ ) . '/sample/sample-config.php' ) ) {
					require_once dirname( __FILE__ ) . '/sample/sample-config.php';
				}
			}

			/**
			 * Run action and filter hooks
			 *
			 * @access      private
			 * @since       3.1.3
			 * @return      void
			 */
			private function hooks() {
				add_action( 'wp_loaded', array( $this, 'options_toggle_check' ) );

				// Activate plugin when new blog is added
				add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

				// Display admin notices
				add_action( 'admin_notices', array( $this, 'admin_notices' ) );

				// Edit plugin metalinks
				add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );

				add_action( 'activated_plugin', array( $this, 'load_first' ) );

				do_action( 'avadaredux/plugin/hooks', $this );
			}

			public function load_first() {
				$plugin_dir = AvadaRedux_Helpers::cleanFilePath( WP_PLUGIN_DIR ) . '/';
				$self_file  = AvadaRedux_Helpers::cleanFilePath( __FILE__ );

				$path = str_replace( $plugin_dir, '', $self_file );
				$path = str_replace( 'class.avadaredux-plugin.php', 'avadaredux-framework.php', $path );

				if ( $plugins = get_option( 'active_plugins' ) ) {
					if ( $key = array_search( $path, $plugins ) ) {
						array_splice( $plugins, $key, 1 );
						array_unshift( $plugins, $path );
						update_option( 'active_plugins', $plugins );
					}
				}
			}

			/**
			 * Fired on plugin activation
			 *
			 * @access      public
			 * @since       3.0.0
			 *
			 * @param       boolean $network_wide True if plugin is network activated, false otherwise
			 *
			 * @return      void
			 */
			public static function activate( $network_wide ) {
				if ( function_exists( 'is_multisite' ) && is_multisite() ) {
					if ( $network_wide ) {
						// Get all blog IDs
						$blog_ids = self::get_blog_ids();

						foreach ( $blog_ids as $blog_id ) {
							switch_to_blog( $blog_id );
							self::single_activate();
						}
						restore_current_blog();
					} else {
						self::single_activate();
					}
				} else {
					self::single_activate();
				}

				delete_site_transient( 'update_plugins' );
			}

			/**
			 * Fired when plugin is deactivated
			 *
			 * @access      public
			 * @since       3.0.0
			 *
			 * @param       boolean $network_wide True if plugin is network activated, false otherwise
			 *
			 * @return      void
			 */
			public static function deactivate( $network_wide ) {
				if ( function_exists( 'is_multisite' ) && is_multisite() ) {
					if ( $network_wide ) {
						// Get all blog IDs
						$blog_ids = self::get_blog_ids();

						foreach ( $blog_ids as $blog_id ) {
							switch_to_blog( $blog_id );
							self::single_deactivate();
						}
						restore_current_blog();
					} else {
						self::single_deactivate();
					}
				} else {
					self::single_deactivate();
				}

				delete_option( 'AvadaReduxFrameworkPlugin' );
			}

			/**
			 * Fired when a new WPMU site is activated
			 *
			 * @access      public
			 * @since       3.0.0
			 *
			 * @param       int $blog_id The ID of the new blog
			 *
			 * @return      void
			 */
			public function activate_new_site( $blog_id ) {
				if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
					return;
				}

				switch_to_blog( $blog_id );
				self::single_activate();
				restore_current_blog();
			}

			/**
			 * Get all IDs of blogs that are not activated, not spam, and not deleted
			 *
			 * @access      private
			 * @since       3.0.0
			 * @global      object $wpdb
			 * @return      array|false Array of IDs or false if none are found
			 */
			private static function get_blog_ids() {
				global $wpdb;

				// Get an array of IDs
				$sql = "SELECT blog_id FROM $wpdb->blogs
					WHERE archived = '0' AND spam = '0'
					AND deleted = '0'";

				return $wpdb->get_col( $sql );
			}

			/**
			 * Fired for each WPMS blog on plugin activation
			 *
			 * @access      private
			 * @since       3.0.0
			 * @return      void
			 */
			private static function single_activate() {
				$notices   = get_option( 'AvadaReduxFrameworkPlugin_ACTIVATED_NOTICES', array() );
				$notices[] = __( 'AvadaRedux Framework has an embedded demo.', 'avadaredux-framework' ) . ' <a href="./plugins.php?AvadaReduxFrameworkPlugin=demo">' . __( 'Click here to activate the sample config file.', 'avadaredux-framework' ) . '</a>';

				update_option( 'AvadaReduxFrameworkPlugin_ACTIVATED_NOTICES', $notices );
			}

			/**
			 * Display admin notices
			 *
			 * @access      public
			 * @since       3.0.0
			 * @return      void
			 */
			public function admin_notices() {
				do_action( 'AvadaReduxFrameworkPlugin_admin_notice' );
				$notices = get_option( 'AvadaReduxFrameworkPlugin_ACTIVATED_NOTICES', '' );
				if ( !empty( $notices ) ) {
					foreach ( $notices as $notice ) {
						echo '<div class="updated notice is-dismissible"><p>' . $notice . '</p></div>';
					}

					delete_option( 'AvadaReduxFrameworkPlugin_ACTIVATED_NOTICES' );
				}
			}

			/**
			 * Fired for each blog when the plugin is deactivated
			 *
			 * @access      private
			 * @since       3.0.0
			 * @return      void
			 */
			private static function single_deactivate() {
				delete_option( 'AvadaReduxFrameworkPlugin_ACTIVATED_NOTICES' );
			}

			/**
			 * Turn on or off
			 *
			 * @access      public
			 * @since       3.0.0
			 * @global      string $pagenow The current page being displayed
			 * @return      void
			 */
			public function options_toggle_check() {
				global $pagenow;

				if ( $pagenow == 'plugins.php' && is_admin() && ! empty( $_GET['AvadaReduxFrameworkPlugin'] ) ) {
					$url = './plugins.php';

					if ( $_GET['AvadaReduxFrameworkPlugin'] == 'demo' ) {
						if ( $this->options['demo'] == false ) {
							$this->options['demo'] = true;
						} else {
							$this->options['demo'] = false;
						}
					}

					if ( is_multisite() && is_network_admin() && $this->plugin_network_activated ) {
						update_site_option( 'AvadaReduxFrameworkPlugin', $this->options );
					} else {
						update_option( 'AvadaReduxFrameworkPlugin', $this->options );
					}

					wp_redirect( $url );
				}
			}

			/**
			 * Add settings action link to plugins page
			 *
			 * @access      public
			 * @since       3.0.0
			 * @return      void
			 */
			public function add_action_links( $links ) {
				// In case we ever want to do this...
				return $links;

				/**
				 * return array_merge(
				 *      array( 'avadaredux_plugin_settings' => '<a href="' . admin_url( 'plugins.php?page=' . 'avadaredux_plugin_settings' ) . '">' . __( 'Settings', 'avadaredux-framework' ) . '</a>' ),
				 *      $links
				 * );
				 */
			}

			/**
			 * Edit plugin metalinks
			 *
			 * @access      public
			 * @since       3.0.0
			 *
			 * @param       array  $links The current array of links
			 * @param       string $file  A specific plugin row
			 *
			 * @return      array The modified array of links
			 */
			public function plugin_metalinks( $links, $file ) {
				if ( strpos( $file, 'avadaredux-framework.php' ) !== false && is_plugin_active( $file ) ) {

					$new_links = array(
						'<a href="' . 'http://' . 'docs.avadareduxframework.com/" target="_blank">' . __( 'Docs', 'avadaredux-framework' ) . '</a>',
						'<a href="' . 'https://' . 'github.com/AvadaReduxFramework/avadaredux-framework" target="_blank">' . __( 'Repo', 'avadaredux-framework' ) . '</a>',
						'<a href="' . 'http://' . 'build.avadareduxframework.com/" target="_blank">' . __( 'Builder', 'avadaredux-framework' ) . '</a>',
						'<a href="' . admin_url( 'tools.php?page=avadaredux-support' ) . '">' . __( 'Get Support', 'avadaredux-framework' ) . '</a>',
					);

					if ( ( is_multisite() && $this->plugin_network_activated ) || ! is_network_admin() || ! is_multisite() ) {
						if ( $this->options['demo'] ) {
							$new_links[3] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?AvadaReduxFrameworkPlugin=demo" style="color: #bc0b0b;">' . __( 'Deactivate Demo Mode', 'avadaredux-framework' ) . '</a></span>';
						} else {
							$new_links[3] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?AvadaReduxFrameworkPlugin=demo" style="color: #bc0b0b;">' . __( 'Activate Demo Mode', 'avadaredux-framework' ) . '</a></span>';
						}
					}

					$links = array_merge( $links, $new_links );
				}

				return $links;
			}
		}
	}
