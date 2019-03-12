<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}


	class AvadaRedux_Welcome {

		/**
		 * @var string The capability users should have to view the page
		 */
		public $minimum_capability = 'manage_options';
		public $display_version = "";
		public $avadaredux_loaded = false;

		/**
		 * Get things started
		 *
		 * @since 1.4
		 */
		public function __construct() {

			add_action( 'avadaredux/loaded', array( $this, 'init' ) );

			add_action( 'wp_ajax_avadaredux_support_hash', array( $this, 'support_hash' ) );

		}

		public function init() {

			if ( $this->avadaredux_loaded ) {
				return;
			}
			$this->avadaredux_loaded = true;
			add_action( 'admin_menu', array( $this, 'admin_menus' ) );

			if ( isset( $_GET['page'] ) ) {
				if ( substr( $_GET['page'], 0, 6 ) == "avadaredux-" ) {
					$version               = explode( '.', AvadaReduxFramework::$_version );
					$this->display_version = $version[0] . '.' . $version[1];
					add_filter( 'admin_footer_text', array( $this, 'change_wp_footer' ) );
					add_action( 'admin_head', array( $this, 'admin_head' ) );
				} else {
					$this->check_version();
				}
			} else {
				$this->check_version();
			}
			update_option( 'avadaredux_version_upgraded_from', AvadaReduxFramework::$_version );
			set_transient( '_avadaredux_activation_redirect', true, 30 );

		}


		public function check_version() {
			global $pagenow;

			if ( $pagenow == "admin-ajax.php" || ( $GLOBALS['pagenow'] == "customize" && isset( $_GET['theme'] ) && ! empty( $_GET['theme'] ) ) ) {
				return;
			}

			$saveVer = AvadaRedux_Helpers::major_version( get_option( 'avadaredux_version_upgraded_from' ) );
			$curVer  = AvadaRedux_Helpers::major_version( AvadaReduxFramework::$_version );
			$compare = false;

			if ( AvadaRedux_Helpers::isLocalHost() ) {
				$compare = true;
			} else if ( class_exists( 'AvadaReduxFrameworkPlugin' ) ) {
				$compare = true;
			} else {
				$avadaredux = AvadaReduxFrameworkInstances::get_all_instances();

				if ( is_array( $avadaredux ) ) {
					foreach ( $avadaredux as $panel ) {
						if ( $panel->args['dev_mode'] == 1 ) {
							$compare = true;
							break;
						}
					}
				}
			}

			if ( $compare ) {
				$redirect = false;
				if ( empty( $saveVer ) ) {
					$redirect = true; // First time
				} else if ( version_compare( $curVer, $saveVer, '>' ) ) {
					$redirect = true; // Previous version
				}
				if ( $redirect && ! defined( 'WP_TESTS_DOMAIN' ) && AvadaReduxFramework::$_as_plugin ) {
					add_action( 'init', array( $this, 'do_redirect' ) );
				}
			}
		}

		public function do_redirect() {
			if ( ! defined( 'WP_CLI' ) ) {
				wp_redirect( admin_url( 'tools.php?page=avadaredux-about' ) );
				exit();
			}
		}

		public function change_wp_footer() {
			echo __( 'If you like <strong>AvadaRedux</strong> please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/avadaredux-framework?filter=5#postform" target="_blank" class="avadaredux-rating-link" data-rated="Thanks :)">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. A huge thank you from AvadaRedux in advance!', 'avadaredux-framework' );
		}

		public function support_hash() {

			if ( ! wp_verify_nonce( $_POST['nonce'], 'avadaredux-support-hash' ) ) {
				die();
			}

			$data          = get_option( 'avadaredux_support_hash' );
			$data          = wp_parse_args( $data, array( 'check' => '', 'identifier' => '' ) );
			$generate_hash = true;
			$system_info   = AvadaRedux_Helpers::compileSystemStatus();
			$newHash       = md5( json_encode( $system_info ) );
			$return        = array();
			if ( $newHash == $data['check'] ) {
				unset( $generate_hash );
			}
			$post_data = array(
				'hash'          => md5( network_site_url() . '-' . $_SERVER['REMOTE_ADDR'] ),
				'site'          => esc_url( home_url( '/' ) ),
				'tracking'      => AvadaRedux_Helpers::getTrackingObject(),
				'system_status' => $system_info,
			);
			//$post_data = json_encode( $post_data );
			$post_data = serialize( $post_data );

			if ( isset( $generate_hash ) && $generate_hash ) {
				$data['check']      = $newHash;
				$data['identifier'] = "";
				$response           = wp_remote_post( 'http://support.avadaredux.io/v1/', array(
						'method'      => 'POST',
						'timeout'     => 65,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'compress'    => true,
						'headers'     => array(),
						'body'        => array(
							'data'      => $post_data,
							'serialize' => 1
						)
					)
				);

				if ( is_wp_error( $response ) ) {
					echo json_encode( array(
						'status'  => 'error',
						'message' => $response->get_error_message()
					) );
					die( 1 );
				} else {
					$response_code = wp_remote_retrieve_response_code( $response );
					if ( $response_code == 200 ) {
						$response = wp_remote_retrieve_body( $response );
						$return   = json_decode( $response, true );
						if ( isset( $return['identifier'] ) ) {
							$data['identifier'] = $return['identifier'];
							update_option( 'avadaredux_support_hash', $data );
						}
					} else {
						$response = wp_remote_retrieve_body( $response );
						echo json_encode( array(
							'status'  => 'error',
							'message' => $response
						) );
					}
				}
			}

			if ( ! empty( $data['identifier'] ) ) {
				$return['status']     = "success";
				$return['identifier'] = $data['identifier'];
			} else {
				$return['status']  = "error";
				$return['message'] = esc_html__( "Support hash could not be generated. Please try again later.", 'avadaredux-framework' );
			}

			echo json_encode( $return );

			die( 1 );
		}

		/**
		 * Register the Dashboard Pages which are later hidden but these pages
		 * are used to render the Welcome and Credits pages.
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function admin_menus() {

			$page = 'add_management_page';

			// About Page
			$page(
				esc_html__( 'Welcome to AvadaRedux Framework', 'avadaredux-framework' ), esc_html__( 'AvadaRedux Framework', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-about', array(
					$this,
					'about_screen'
				)
			);

			// Changelog Page
			$page(
				esc_html__( 'AvadaRedux Framework Changelog', 'avadaredux-framework' ), esc_html__( 'AvadaRedux Framework Changelog', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-changelog', array(
					$this,
					'changelog_screen'
				)
			);

			// Support Page
			$page(
				esc_html__( 'Get Support', 'avadaredux-framework' ), esc_html__( 'Get Support', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-support', array(
					$this,
					'get_support'
				)
			);

			// Support Page
			$page(
				esc_html__( 'AvadaRedux Extensions', 'avadaredux-framework' ), esc_html__( 'AvadaRedux Extensions', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-extensions', array(
					$this,
					'avadaredux_extensions'
				)
			);


			// Credits Page
			$page(
				esc_html__( 'The people that develop AvadaRedux Framework', 'avadaredux-framework' ), esc_html__( 'The people that develop AvadaRedux Framework', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-credits', array(
					$this,
					'credits_screen'
				)
			);

			// Status Page
			$page(
				esc_html__( 'AvadaRedux Framework Status', 'avadaredux-framework' ), esc_html__( 'AvadaRedux Framework Status', 'avadaredux-framework' ), $this->minimum_capability, 'avadaredux-status', array(
					$this,
					'status_screen'
				)
			);

			//remove_submenu_page( 'tools.php', 'avadaredux-about' );
			remove_submenu_page( 'tools.php', 'avadaredux-status' );
			remove_submenu_page( 'tools.php', 'avadaredux-changelog' );
			remove_submenu_page( 'tools.php', 'avadaredux-getting-started' );
			remove_submenu_page( 'tools.php', 'avadaredux-credits' );
			remove_submenu_page( 'tools.php', 'avadaredux-support' );
			remove_submenu_page( 'tools.php', 'avadaredux-extensions' );


		}

		/**
		 * Hide Individual Dashboard Pages
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function admin_head() {

			// Badge for welcome page
			//$badge_url = AvadaReduxFramework::$_url . 'assets/images/avadaredux-badge.png';
			?>

			<script
				id="avadaredux-qtip-js"
				src='<?php echo esc_url( AvadaReduxFramework::$_url ); ?>assets/js/vendor/qtip/jquery.qtip.js'>
			</script>

			<script
				id="avadaredux-welcome-admin-js"
				src='<?php echo esc_url( AvadaReduxFramework::$_url ) ?>inc/welcome/js/avadaredux-welcome-admin.js'>
			</script>

			<?php
			if ( isset ( $_GET['page'] ) && $_GET['page'] == "avadaredux-support" ) :
				?>
				<script
					id="jquery-easing"
					src='<?php echo esc_url( AvadaReduxFramework::$_url ); ?>inc/welcome/js/jquery.easing.min.js'>
				</script>
			<?php endif; ?>

			<link rel='stylesheet' id='avadaredux-qtip-css'
				href='<?php echo esc_url( AvadaReduxFramework::$_url ); ?>assets/css/vendor/qtip/jquery.qtip.css'
				type='text/css' media='all'/>

			<link rel='stylesheet' id='elusive-icons'
				href='<?php echo esc_url( AvadaReduxFramework::$_url ); ?>assets/css/vendor/elusive-icons/elusive-icons.css'
				type='text/css' media='all'/>

			<link rel='stylesheet' id='avadaredux-welcome-css'
				href='<?php echo esc_url( AvadaReduxFramework::$_url ); ?>inc/welcome/css/avadaredux-welcome.css'
				type='text/css' media='all'/>
			<style type="text/css">
				.avadaredux-badge:before {
				<?php echo esc_js(is_rtl() ? 'right' : 'left'); ?> : 0;
				}

				.about-wrap .avadaredux-badge {
				<?php echo esc_js(is_rtl() ? 'left' : 'right'); ?> : 0;
				}

				.about-wrap .feature-rest div {
					padding- <?php echo esc_js(is_rtl() ? 'left' : 'right'); ?>: 100px;
				}

				.about-wrap .feature-rest div.last-feature {
					padding- <?php echo esc_js(is_rtl() ? 'right' : 'left'); ?>: 100px;
					padding- <?php echo esc_js(is_rtl() ? 'left' : 'right'); ?>: 0;
				}

				.about-wrap .feature-rest div.icon:before {
					margin: <?php echo esc_js(is_rtl() ? '0 -100px 0 0' : '0 0 0 -100px'); ?>;
				}
			</style>
			<?php
		}

		/**
		 * Navigation tabs
		 *
		 * @access public
		 * @since  1.9
		 * @return void
		 */
		public function tabs() {
			$selected = isset ( $_GET['page'] ) ? $_GET['page'] : 'avadaredux-about';
			$nonce    = wp_create_nonce( 'avadaredux-support-hash' );
			?>
			<input type="hidden" id="avadaredux_support_nonce" value="<?php echo esc_attr( $nonce ); ?>"/>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab <?php echo $selected == 'avadaredux-about' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-about' ), 'tools.php' ) ) ); ?>">
					<?php esc_attr_e( "What's New", 'avadaredux-framework' ); ?>
				</a> <a class="nav-tab <?php echo $selected == 'avadaredux-extensions' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-extensions' ), 'tools.php' ) ) ); ?>">
					<?php esc_attr_e( 'Extensions', 'avadaredux-framework' ); ?>
				</a> <a class="nav-tab <?php echo $selected == 'avadaredux-changelog' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-changelog' ), 'tools.php' ) ) ); ?>">
					<?php esc_attr_e( 'Changelog', 'avadaredux-framework' ); ?>
				</a> <a class="nav-tab <?php echo $selected == 'avadaredux-credits' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-credits' ), 'tools.php' ) ) ); ?>">
					<?php _e( 'Credits', 'avadaredux-framework' ); ?>
				</a> <a class="nav-tab <?php echo $selected == 'avadaredux-support' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-support' ), 'tools.php' ) ) ); ?>">
					<?php esc_attr_e( 'Support', 'avadaredux-framework' ); ?>
				</a> <a class="nav-tab <?php echo $selected == 'avadaredux-status' ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'avadaredux-status' ), 'tools.php' ) ) ); ?>">
					<?php esc_attr_e( 'Status', 'avadaredux-framework' ); ?>
				</a>
			</h2>
			<?php
		}

		/**
		 * Render About Screen
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function about_screen() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/about.php';

		}

		/**
		 * Render Changelog Screen
		 *
		 * @access public
		 * @since  2.0.3
		 * @return void
		 */
		public function changelog_screen() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/changelog.php';

		}

		/**
		 * Render Changelog Screen
		 *
		 * @access public
		 * @since  2.0.3
		 * @return void
		 */
		public function avadaredux_extensions() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/extensions.php';

		}


		/**
		 * Render Get Support Screen
		 *
		 * @access public
		 * @since  1.9
		 * @return void
		 */
		public function get_support() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/support.php';

		}

		/**
		 * Render Credits Screen
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function credits_screen() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/credits.php';

		}

		/**
		 * Render Status Report Screen
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function status_screen() {
			// Stupid hack for Wordpress alerts and warnings
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/status_report.php';

		}

		/**
		 * Parse the AvadaRedux readme.txt file
		 *
		 * @since 2.0.3
		 * @return string $readme HTML formatted readme file
		 */
		public function parse_readme() {
			if ( file_exists( AvadaReduxFramework::$_dir . 'inc/fields/raw/parsedown.php' ) ) {
				require_once AvadaReduxFramework::$_dir . 'inc/fields/raw/parsedown.php';
				$Parsedown = new Parsedown();
				$data = @wp_remote_get( AvadaReduxFramework::$_url . '../CHANGELOG.md' );
				if ( isset( $data ) && ! empty( $data ) ) {
					$data = @wp_remote_retrieve_body( $data );
					return $Parsedown->text( trim( str_replace( '# AvadaRedux Framework Changelog', '', $data ) ) );
				}
			}

			return '<script src="' . 'http://gist-it.appspot.com/https://github.com/avadareduxframework/avadaredux-framework/blob/master/CHANGELOG.md?slice=2:0&footer=0">// <![CDATA[// ]]></script>';

		}

		public function actions() {
			?>
			<p class="avadaredux-actions">
				<a href="http://docs.avadareduxframework.com/" class="docs button button-primary">Docs</a>
				<a href="http://wordpress.org/plugins/avadaredux-framework/" class="review-us button button-primary"
					target="_blank">Review Us</a>
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MMFMHWUPKHKPW"
					class="review-us button button-primary" target="_blank">Donate</a>
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://avadareduxframework.com"
					data-text="Reduce your dev time! AvadaRedux is the most powerful option framework for WordPress on the web"
					data-via="AvadaReduxFramework" data-size="large" data-hashtags="AvadaRedux">Tweet</a>
				<script>!function( d, s, id ) {
						var js, fjs = d.getElementsByTagName( s )[0], p = /^http:/.test( d.location ) ? 'http' : 'https';
						if ( !d.getElementById( id ) ) {
							js = d.createElement( s );
							js.id = id;
							js.src = p + '://platform.twitter.com/widgets.js';
							fjs.parentNode.insertBefore( js, fjs );
						}
					}( document, 'script', 'twitter-wjs' );</script>
			</p>
			<?php
		}

		/**
		 * Render Contributors List
		 *
		 * @since 1.4
		 * @uses  AvadaRedux_Welcome::get_contributors()
		 * @return string $contributor_list HTML formatted list of all the contributors for AvadaRedux
		 */
		public function contributors() {
			$contributors = $this->get_contributors();

			if ( empty ( $contributors ) ) {
				return '';
			}

			$contributor_list = '<ul class="wp-people-group">';

			foreach ( $contributors as $contributor ) {
				$contributor_list .= '<li class="wp-person">';
				$contributor_list .= sprintf( '<a href="%s" title="%s" target="_blank">', esc_url( 'https://github.com/' . $contributor->login ), esc_html( sprintf( __( 'View %s', 'avadaredux-framework' ), esc_html( $contributor->login ) ) )
				);
				$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
				$contributor_list .= '</a>';
				$contributor_list .= sprintf( '<a class="web" href="%s" target="_blank">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
				$contributor_list .= '</a>';
				$contributor_list .= '</li>';
			}

			$contributor_list .= '</ul>';

			return $contributor_list;
		}

		/**
		 * Retreive list of contributors from GitHub.
		 *
		 * @access public
		 * @since  1.4
		 * @return array $contributors List of contributors
		 */
		public function get_contributors() {
			$contributors = get_transient( 'avadaredux_contributors' );

			if ( false !== $contributors ) {
				return $contributors;
			}

			$response = wp_remote_get( 'https://api.github.com/repos/AvadaReduxFramework/avadaredux-framework/contributors', array( 'sslverify' => false ) );

			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				return array();
			}

			$contributors = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! is_array( $contributors ) ) {
				return array();
			}

			set_transient( 'avadaredux_contributors', $contributors, 3600 );

			return $contributors;
		}
	}

	// new AvadaRedux_Welcome();
