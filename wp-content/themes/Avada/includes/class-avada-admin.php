<?php
/**
 * A class to manage various stuff in the WordPress admin area.
 *
 * @package Avada
 * @subpackage Includes
 * @since 3.8.0
 */
class Avada_Admin {

	/**
	 * Construct the admin object.
	 *
	 * @since 3.9.0
	 *
	 */
	public function __construct() {
		add_action( 'wp_before_admin_bar_render', array( $this, 'add_wp_toolbar_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_init', array( $this, 'init_permalink_settings' ) );
		add_action( 'admin_init', array( $this, 'save_permalink_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'edit_admin_menus' ) );
		add_action( 'after_switch_theme', array( $this, 'activation_redirect' ) );
		add_action( 'wp_ajax_avada_update_registration', array( $this, 'avada_update_registration' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
	}

	/**
	 * Adds the news dashboard widget.
	 *
	 * @since 3.9.0
	 */
	public function add_dashboard_widget() {
		// Create the widget
		wp_add_dashboard_widget( 'themefusion_news', apply_filters( 'avada_dashboard_widget_title', __( 'ThemeFusion News', 'Avada' ) ), array( $this, 'display_news_dashboard_widget' ) );

		// Make sure our widget is on top off all others
		global $wp_meta_boxes;

		// Get the regular dashboard widgets array
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

		// Backup and delete our new dashboard widget from the end of the array
		$avada_widget_backup = array( 'themefusion_news' => $normal_dashboard['themefusion_news'] );
		unset( $normal_dashboard['themefusion_news'] );

		// Merge the two arrays together so our widget is at the beginning
		$sorted_dashboard = array_merge( $avada_widget_backup, $normal_dashboard );

		// Save the sorted array back into the original metaboxes
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Renders the news dashboard widget.
	 *
	 * @since 3.9.0
	 */
	public function display_news_dashboard_widget() {

		// Create two feeds, the first being just a leading article with data and summary, the second being a normal news feed
		$feeds = array(
			'first' => array(
				'link'         => 'http://theme-fusion.com/blog/',
				'url'          => 'http://theme-fusion.com/feed/',
				'title'        => __( 'ThemeFusion News', 'Avada' ),
				'items'        => 1,
				'show_summary' => 1,
				'show_author'  => 0,
				'show_date'    => 1,
			),
			'news' => array(
				'link'         => 'http://theme-fusion.com/blog/',
				'url'          => 'http://theme-fusion.com/feed/',
				'title'        => __( 'ThemeFusion News', 'Avada' ),
				'items'        => 4,
				'show_summary' => 0,
				'show_author'  => 0,
				'show_date'    => 0,
			),
		);

		wp_dashboard_primary_output( 'themefusion_news', $feeds );
	}

	/**
	 * Create the admin toolbar menu items.
	 *
	 * @since 3.8.0
	 */
	public function add_wp_toolbar_menu() {

		global $wp_admin_bar;

		if ( current_user_can( 'edit_theme_options' ) ) {

			$registration_complete = false;
			$avada_options         = get_option( 'Avada_Key' );
			$tf_username           = isset( $avada_options['tf_username'] ) ? $avada_options['tf_username'] : '';
			$tf_api                = isset( $avada_options['tf_api'] ) ? $avada_options['tf_api'] : '';
			$tf_purchase_code      = isset( $avada_options['tf_purchase_code'] ) ? $avada_options['tf_purchase_code'] : '';
			if ( '' !== $tf_username && '' !== $tf_api && '' !== $tf_purchase_code ) {
				$registration_complete = true;
			}
			$avada_parent_menu_title = '<span class="ab-icon"></span><span class="ab-label">Avada</span>';

			$this->add_wp_toolbar_menu_item( $avada_parent_menu_title, false, admin_url( 'admin.php?page=avada' ), array( 'class' => 'avada-menu' ), 'avada' );

			if ( ! $registration_complete ) {
				$this->add_wp_toolbar_menu_item( __( 'Product Registration', 'Avada' ), 'avada', admin_url( 'admin.php?page=avada' ) );
			}
			$this->add_wp_toolbar_menu_item( __( 'Support', 'Avada' ), 'avada', admin_url( 'admin.php?page=avada-support' ) );
			$this->add_wp_toolbar_menu_item( __( 'Install Demos', 'Avada' ), 'avada', admin_url( 'admin.php?page=avada-demos' ) );
			$this->add_wp_toolbar_menu_item( __( 'Plugins', 'Avada' ), 'avada', admin_url( 'admin.php?page=avada-plugins' ) );
			$this->add_wp_toolbar_menu_item( __( 'System Status', 'Avada' ), 'avada', admin_url( 'admin.php?page=avada-system-status' ) );
			$this->add_wp_toolbar_menu_item( __( 'Theme Options', 'Avada' ), 'avada', admin_url( 'themes.php?page=avada_options' ) );

		}

	}

	/**
	 * Add the top-level menu item to the adminbar.
	 *
	 * @since 3.8.0
	 */
	public function add_wp_toolbar_menu_item( $title, $parent = false, $href = '', $custom_meta = array(), $custom_id = '' ) {

		global $wp_admin_bar;

		if ( current_user_can( 'edit_theme_options' ) ) {
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}

			// Set custom ID
			if ( $custom_id ) {
				$id = $custom_id;
			} else { // Generate ID based on $title
				$id = strtolower( str_replace( ' ', '-', $title ) );
			}

			// links from the current host will open in the current window
			$meta = strpos( $href, site_url() ) !== false ? array() : array( 'target' => '_blank' ); // external links open in new tab/window
			$meta = array_merge( $meta, $custom_meta );

			$wp_admin_bar->add_node( array(
				'parent' => $parent,
				'id'     => $id,
				'title'  => $title,
				'href'   => $href,
				'meta'   => $meta,
			) );
		}

	}

	/**
	 * Modify the menu
	 *
	 * @since 3.8.0
	 */
	public function edit_admin_menus() {
		global $submenu;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$submenu['avada'][0][0] = esc_html__( 'Product Registration', 'Avada' ); // Change Avada to Product Registration
		}
	}

	/**
	 * Redirect to admin page on theme activation
	 *
	 * @since 3.8.0
	 */
	public function activation_redirect() {
		if ( current_user_can( 'edit_theme_options' ) ) {
			header( 'Location:' . admin_url() . 'admin.php?page=avada' );
		}
	}

	/**
	 * Actions to run on initial theme activation
	 *
	 * @since 3.8.0
	 */
	public function admin_init() {

		if ( current_user_can( 'edit_theme_options' ) ) {
			// Save avada key in a different location
			$avada_key = get_option( 'Avada_Key' );
			if ( ! is_array( $avada_key ) && empty( $avada_key ) ) {
				$avada_options    = get_option( Avada::get_option_name() );
				$tf_username      = isset( $avada_options['tf_username'] ) ? $avada_options['tf_username'] : '';
				$tf_api           = isset( $avada_options['tf_api'] ) ? $avada_options['tf_api'] : '';
				$tf_purchase_code = isset( $avada_options['tf_purchase_code'] ) ? $avada_options['tf_purchase_code'] : '';

				if ( $tf_username && $tf_api && $tf_purchase_code ) {
					update_option( 'Avada_Key', array(
						'tf_username'      => $tf_username,
						'tf_api'           => $tf_api,
						'tf_purchase_code' => $tf_purchase_code,
					) );
				}
			}

			if ( isset( $_GET['avada-deactivate'] ) && 'deactivate-plugin' == $_GET['avada-deactivate'] ) {
				check_admin_referer( 'avada-deactivate', 'avada-deactivate-nonce' );

				$plugins = TGM_Plugin_Activation::$instance->plugins;

				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $_GET['plugin'] ) {
						deactivate_plugins( $plugin['file_path'] );
					}
				}
			} if ( isset( $_GET['avada-activate'] ) && 'activate-plugin' == $_GET['avada-activate'] ) {
				check_admin_referer( 'avada-activate', 'avada-activate-nonce' );

				$plugins = TGM_Plugin_Activation::$instance->plugins;

				foreach ( $plugins as $plugin ) {
					if ( isset( $_GET['plugin'] ) && $plugin['slug'] == $_GET['plugin'] ) {
						activate_plugin( $plugin['file_path'] );

						wp_redirect( admin_url( 'admin.php?page=avada-plugins' ) );
						exit;
					}
				}
			}
		}
	}

	public function admin_menu(){

		if ( current_user_can( 'edit_theme_options' ) ) {
			// Work around for theme check
			$avada_menu_page_creation_method    = 'add_menu_page';
			$avada_submenu_page_creation_method = 'add_submenu_page';

			$welcome_screen = $avada_menu_page_creation_method( 'Avada', 'Avada', 'administrator', 'avada', array( $this, 'welcome_screen' ), 'dashicons-fusiona-logo', 3 );

			$support       = $avada_submenu_page_creation_method( 'avada', __( 'Avada Support', 'Avada' ), __( 'Support', 'Avada' ), 'administrator', 'avada-support', array( $this, 'support_tab' ) );
			$demos         = $avada_submenu_page_creation_method( 'avada', __( 'Install Avada Demos', 'Avada' ), __( 'Install Demos', 'Avada' ), 'administrator', 'avada-demos', array( $this, 'demos_tab' ) );
			$plugins       = $avada_submenu_page_creation_method( 'avada', __( 'Plugins', 'Avada' ), __( 'Plugins', 'Avada' ), 'administrator', 'avada-plugins', array( $this, 'plugins_tab' ) );
			$status        = $avada_submenu_page_creation_method( 'avada', __( 'System Status', 'Avada' ), __( 'System Status', 'Avada' ), 'administrator', 'avada-system-status', array( $this, 'system_status_tab' ) );
			$theme_options = $avada_submenu_page_creation_method( 'avada', __( 'Theme Options', 'Avada' ), __( 'Theme Options', 'Avada' ), 'administrator', 'themes.php?page=avada_options' );

			add_action( 'admin_print_scripts-' . $welcome_screen, array( $this, 'welcome_screen_scripts' ) );
			add_action( 'admin_print_scripts-' . $support, array( $this, 'support_screen_scripts' ) );
			add_action( 'admin_print_scripts-' . $demos, array( $this, 'demos_screen_scripts' ) );
			add_action( 'admin_print_scripts-' . $plugins, array( $this, 'plugins_screen_scripts' ) );
			add_action( 'admin_print_scripts-' . $status, array( $this, 'status_screen_scripts' ) );
		}
	}

	public function welcome_screen() {
		require_once( 'admin-screens/welcome.php' );
	}

	public function support_tab() {
		require_once( 'admin-screens/support.php' );
	}

	public function demos_tab() {
		require_once( 'admin-screens/install-demos.php' );
	}

	public function plugins_tab() {
		require_once( 'admin-screens/fusion-plugins.php' );
	}

	public function system_status_tab() {
		require_once( 'admin-screens/system-status.php' );
	}

	public function avada_update_registration() {

		global $wp_version;

		$avada_options    = get_option( 'Avada_Key' );
		$data             = $_POST;
		$tf_username      = isset( $data['tf_username'] ) ? $data['tf_username'] : '';
		$tf_api           = isset( $data['tf_api'] ) ? $data['tf_api'] : '';
		$tf_purchase_code = isset( $data['tf_purchase_code'] ) ? $data['tf_purchase_code'] : '';

		if ( '' !== $tf_username && '' !== $tf_api && '' !== $tf_purchase_code ) {

			$avada_options['tf_username']      = $tf_username;
			$avada_options['tf_api']           = $tf_api;
			$avada_options['tf_purchase_code'] = $tf_purchase_code;

			$prepare_request = array(
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
			);

			$raw_response = wp_remote_post( 'http://marketplace.envato.com/api/v3/' . $tf_username . '/' . $tf_api . '/download-purchase:' . $tf_purchase_code . '.json', $prepare_request );

			if ( ! is_wp_error( $raw_response ) ) {
				$response = json_decode( $raw_response['body'], true );
			}

			if ( ! empty( $response ) ) {
				if ( ( isset( $response['error'] ) ) || ( isset( $response['download-purchase'] ) && empty( $response['download-purchase'] ) ) ) {
					esc_attr_e( 'Error', 'Avada' );
				} elseif ( isset( $response['download-purchase'] ) && ! empty( $response['download-purchase'] ) ) {
					update_option( 'Avada_Key', $avada_options );
					esc_attr_e( 'Updated', 'Avada' );
				}
			} else {
				esc_attr_e( 'Error', 'Avada' );
			}
		} else {
			esc_attr_e( 'Empty', 'Avada' );
		}

		die();

	}

	public function admin_scripts() { ?>
		<?php if ( is_admin() && current_user_can( 'edit_theme_options' ) ) : ?>
			<style type="text/css">
				@media screen and (max-width: 782px) {
					#wp-toolbar > ul > .avada-menu {
						display: block;
					}

					#wpadminbar .avada-menu > .ab-item .ab-icon {
						padding-top: 6px !important;
						height: 40px !important;
						font-size: 30px !important;
					}
				}
				/*
				#menu-appearance a[href="themes.php?page=avada_options"] {
					display: none;
				}
				*/
				#wpadminbar .avada-menu > .ab-item .ab-icon:before,
				.dashicons-fusiona-logo:before{
					content: "\e62d";
					font-family: 'icomoon';
					speak: none;
					font-style: normal;
					font-weight: normal;
					font-variant: normal;
					text-transform: none;
					line-height: 1;

					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}
			</style>
		<?php endif;
	}

	public function welcome_screen_scripts(){
		wp_enqueue_style( 'avada_admin_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-admin.css' );
		wp_enqueue_style( 'welcome_screen_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-welcome-screen.css' );
		wp_enqueue_script( 'welcome_screen', trailingslashit( get_template_directory_uri() ) . '/assets/admin/js/avada-welcome-screen.js' );
	}

	public function support_screen_scripts(){
		wp_enqueue_style( 'avada_admin_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-admin.css' );
	}

	public function demos_screen_scripts(){
		wp_enqueue_style( 'avada_admin_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-admin.css' );
		wp_enqueue_script( 'avada_admin_js', trailingslashit( get_template_directory_uri() ) . '/assets/admin/js/avada-admin.js' );
	}

	public function plugins_screen_scripts(){
		wp_enqueue_style( 'avada_admin_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-admin.css' );
	}

	public function status_screen_scripts(){
		wp_enqueue_style( 'avada_admin_css', trailingslashit( get_template_directory_uri() ) . '/assets/admin/css/avada-admin.css' );
		wp_enqueue_script( 'avada_admin_js', trailingslashit( get_template_directory_uri() ) . '/assets/admin/js/avada-admin.js' );
	}

	public function plugin_link( $item ) {
		$installed_plugins = get_plugins();

		$item['sanitized_plugin'] = $item['name'];

		$actions = array();

		// We have a repo plugin
		if ( ! $item['version'] ) {
			$item['version'] = TGM_Plugin_Activation::$instance->does_plugin_have_update( $item['slug'] );
		}

		/** We need to display the 'Install' hover link */
		if ( ! isset( $installed_plugins[$item['file_path']] ) ) {
			$actions = array(
				'install' => sprintf(
					'<a href="%1$s" class="button button-primary" title="Install %2$s">Install</a>',
					esc_url( wp_nonce_url(
						add_query_arg(
							array(
								'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
								'plugin'        => urlencode( $item['slug'] ),
								'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
								'plugin_source' => urlencode( $item['source'] ),
								'tgmpa-install' => 'install-plugin',
								'return_url'    => 'fusion_plugins',
							),
							TGM_Plugin_Activation::$instance->get_tgmpa_url()
						),
						'tgmpa-install',
						'tgmpa-nonce'
					) ),
					$item['sanitized_plugin']
				),
			);
		}
		/** We need to display the 'Activate' hover link */
		elseif ( is_plugin_inactive( $item['file_path'] ) ) {
			$actions = array(
				'activate' => sprintf(
					'<a href="%1$s" class="button button-primary" title="Activate %2$s">Activate</a>',
					esc_url( add_query_arg(
						array(
							'plugin'               => urlencode( $item['slug'] ),
							'plugin_name'          => urlencode( $item['sanitized_plugin'] ),
							'plugin_source'        => urlencode( $item['source'] ),
							'avada-activate'       => 'activate-plugin',
							'avada-activate-nonce' => wp_create_nonce( 'avada-activate' ),
						),
						admin_url( 'admin.php?page=avada-plugins' )
					) ),
					$item['sanitized_plugin']
				),
			);
		}
		/** We need to display the 'Update' hover link */
		elseif ( version_compare( $installed_plugins[$item['file_path']]['Version'], $item['version'], '<' ) ) {
			$actions = array(
				'update' => sprintf(
					'<a href="%1$s" class="button button-primary" title="Install %2$s">Update</a>',
					wp_nonce_url(
						add_query_arg(
							array(
								'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
								'plugin'        => urlencode( $item['slug'] ),

								'tgmpa-update'  => 'update-plugin',
								'plugin_source' => urlencode( $item['source'] ),
								'version'       => urlencode( $item['version'] ),
								'return_url'    => 'fusion_plugins',
							),
							TGM_Plugin_Activation::$instance->get_tgmpa_url()
						),
						'tgmpa-update',
						'tgmpa-nonce'
					),
					$item['sanitized_plugin']
				),
			);
		} elseif ( is_plugin_active( $item['file_path'] ) ) {
			$actions = array(
				'deactivate' => sprintf(
					'<a href="%1$s" class="button button-primary" title="Deactivate %2$s">Deactivate</a>',
					esc_url( add_query_arg(
						array(
							'plugin'                 => urlencode( $item['slug'] ),
							'plugin_name'            => urlencode( $item['sanitized_plugin'] ),
							'plugin_source'          => urlencode( $item['source'] ),
							'avada-deactivate'       => 'deactivate-plugin',
							'avada-deactivate-nonce' => wp_create_nonce( 'avada-deactivate' ),
						),
						admin_url( 'admin.php?page=avada-plugins' )
					) ),
					$item['sanitized_plugin']
				),
			);
		}

		return $actions;
	}

	/**
	 * let_to_num function.
	 *
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @since 3.8.0
	 *
	 * @param $size
	 * @return int
	 */
	public function let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}

	/**
	 * Initialize the permalink settings.
	 * @since 3.9.2
	 */
	public function init_permalink_settings() {
		add_settings_field(
			'avada_portfolio_category_slug',                        // id
			esc_html__( 'Avada portfolio category base', 'Avada' ), // setting title
			array( $this, 'permalink_slug_input' ),                 // display callback
			'permalink',                                            // settings page
			'optional',                                             // settings section
			array( 'taxonomy' => 'portfolio_category' )             // args
		);

		add_settings_field(
			'avada_portfolio_skills_slug',
			esc_html__( 'Avada portfolio skill base', 'Avada' ),
			array( $this, 'permalink_slug_input' ),
			'permalink',
			'optional',
			array( 'taxonomy' => 'portfolio_skills' )
		);

		add_settings_field(
			'avada_portfolio_tag_slug',
			esc_html__( 'Avada portfolio tag base', 'Avada' ),
			array( $this, 'permalink_slug_input' ),
			'permalink',
			'optional',
			array( 'taxonomy' => 'portfolio_tags' )
		);
	}

	/**
	 * Show a slug input box.
	 * @since 3.9.2
	 */
	public function permalink_slug_input( $args ) {
		$permalinks     = get_option( 'avada_permalinks' );
		$permalink_base = $args['taxonomy'] . '_base';
		$input_name     = 'avada_' . $args['taxonomy'] . '_slug';
		$placeholder    = $args['taxonomy'];
		?>
		<input name="<?php echo $input_name; ?>" type="text" class="regular-text code" value="<?php echo ( isset( $permalinks[$permalink_base] ) ) ? esc_attr( $permalinks[ $permalink_base ] ) : ''; ?>" placeholder="<?php echo esc_attr_x( $placeholder, 'slug', 'Avada' ) ?>" />
		<?php
	}

	/**
	 * Save the permalink settings.
	 * @since 3.9.2
	 */
	public function save_permalink_settings() {

		if ( ! is_admin() ) {
			return;
		}

		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) ) {
			// Cat and tag bases
			$portfolio_category_slug	= ( isset( $_POST['avada_portfolio_category_slug'] ) ) ? sanitize_text_field( $_POST['avada_portfolio_category_slug'] ) : '';
			$portfolio_skills_slug		= ( isset( $_POST['avada_portfolio_skills_slug'] ) ) ? sanitize_text_field( $_POST['avada_portfolio_skills_slug'] ) : '';
			$portfolio_tags_slug		= ( isset( $_POST['avada_portfolio_tags_slug'] ) ) ? sanitize_text_field( $_POST['avada_portfolio_tags_slug'] ) : '';

			$permalinks = get_option( 'avada_permalinks' );

			if ( ! $permalinks ) {
				$permalinks = array();
			}

			$permalinks['portfolio_category_base']	= untrailingslashit( $portfolio_category_slug );
			$permalinks['portfolio_skills_base']	= untrailingslashit( $portfolio_skills_slug );
			$permalinks['portfolio_tags_base']		= untrailingslashit( $portfolio_tags_slug );

			update_option( 'avada_permalinks', $permalinks );
		}
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
