<?php

class Avada_Init {

	public function __construct() {

		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'after_setup_theme', array( $this, 'set_builder_status' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );
		add_action( 'after_setup_theme', array( $this, 'add_image_size' ) );

		// Check done for buddypress activation, might be good for other plugins too
		if ( empty( $_GET['plugin'] ) ) {
			add_action( 'wp_loaded', array( $this, 'register_third_party_plugin_functions' ), 5 );
		}

		add_action( 'wp', array( $this, 'set_theme_version' ) );

		add_action( 'widgets_init', array( $this, 'widget_init' ) );

		// Allow shortcodes in widget text
		add_filter( 'widget_text', 'do_shortcode' );

		add_filter( 'wp_nav_menu_args', array( $this, 'main_menu_args' ) );
		add_action( 'admin_init', array( $this, 'theme_activation' ) );

		// Term meta migration for WordPress 4.4
		// add_action( 'admin_init', array( $this, 'migrate_term_data' ) );

		add_action( 'avada_before_main_content', array( $this, 'youtube_flash_fix' ) );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

		// Remove post_format from previwe link
		add_filter( 'preview_post_link', array( $this, 'remove_post_format_from_link' ), 9999 );

		// Add home url link for navigation menus
		add_filter( 'wp_nav_menu_objects', array( $this, 'set_correct_class_for_menu_items' ) );

	}

	/**
	 * Load the theme textdomain
	 */
	public function load_textdomain(){

		// wp-content/theme/languages/en_US.mo
		// wp-content/languages/themes/Avada-en_US.mo
		$loaded = load_theme_textdomain( 'Avada', get_template_directory() . '/languages' );

		// wp-content/theme/languages/Avada-en_US.mo
		 if ( ! $loaded ) {
			add_filter( 'theme_locale', array( $this, 'change_locale' ), 10, 2 );
			$loaded = load_theme_textdomain( 'Avada', get_template_directory() . '/languages' );

			// wp-content/theme/languages/avada-en_US.mo
			// wp-content/languages/themes/avada-en_US.mo
			if ( ! $loaded ) {
				remove_filter( 'theme_locale', array( $this, 'change_locale' ) );
				add_filter( 'theme_locale', array( $this, 'change_locale_lowercase' ), 10, 2 );
				$loaded = load_theme_textdomain( 'Avada', get_template_directory() . '/languages' );

				// wp-content/languages/Avada-en_US.mo
				if ( ! $loaded ) {
					remove_filter( 'theme_locale', array( $this, 'change_locale_lowercase' ) );
					add_filter( 'theme_locale', array( $this, 'change_locale' ), 10, 2 );
					$loaded = load_theme_textdomain( 'Avada', dirname( dirname( get_template_directory() ) ) . '/languages' );

					// wp-content/languages/themes/avada/en_US.mo
					if ( ! $loaded ) {
						remove_filter( 'theme_locale', array( $this, 'change_locale' ) );
						load_theme_textdomain( 'Avada', dirname( dirname( get_template_directory() ) ) . '/languages/themes/avada' );
					}
				}
			}
		}
	}

	public function change_locale( $locale, $domain ) {
		return $domain . '-' . $locale;
	}

	public function change_locale_lowercase( $locale, $domain ) {
		return strtolower( $domain ) . '-' . $locale;
	}

	/**
	 * Conditionally add theme_support for fusion_builder
	 */
	public function set_builder_status() {

		if ( Avada()->settings->get( 'disable_builder' ) ) {
			add_theme_support( 'fusion_builder' );
		}

	}

	/**
	 * Stores the theme version in the options table in the WordPress database.
	 */
	public function set_theme_version() {
		if ( function_exists( 'wp_get_theme' ) ) {
			$theme_obj = wp_get_theme();
			$theme_version = $theme_obj->get( 'Version' );

			if ( $theme_obj->parent_theme ) {
				$template_dir  = basename( get_template_directory() );
				$theme_obj     = wp_get_theme( $template_dir );
				$theme_version = $theme_obj->get( 'Version' );
			}

			update_option( 'avada_theme_version', $theme_version );
		}

	}

	/**
	 * Add theme_supports
	 */
	public function add_theme_supports() {

		// Default WP generated title support
		add_theme_support( 'title-tag' );
		// Default RSS feed links
		add_theme_support( 'automatic-feed-links' );
		// Default custom header
		add_theme_support( 'custom-header' );
		// Default custom backgrounds
		add_theme_support( 'custom-background' );
		// Woocommerce Support
		add_theme_support( 'woocommerce' );
		// Post Formats
		add_theme_support( 'post-formats', array( 'gallery', 'link', 'image', 'quote', 'video', 'audio', 'chat' ) );
		// Add post thumbnail functionality
		add_theme_support('post-thumbnails');

	}

	/**
	 * Add image sizes
	 */
	public function add_image_size() {
		add_image_size( 'blog-large', 669, 272, true );
		add_image_size( 'blog-medium', 320, 202, true );
		add_image_size( 'portfolio-full', 940, 400, true );
		add_image_size( 'portfolio-one', 540, 272, true );
		add_image_size( 'portfolio-two', 460, 295, true );
		add_image_size( 'portfolio-three', 300, 214, true );
		add_image_size( 'portfolio-five', 177, 142, true );
		add_image_size( 'recent-posts', 700, 441, true );
		add_image_size( 'recent-works-thumbnail', 66, 66, true );
		// Image sizes used for grid layouts
		add_image_size( '200', 200, '', false );
		add_image_size( '400', 400, '', false );
		add_image_size( '600', 600, '', false );
		add_image_size( '800', 800, '', false );
		add_image_size( '1200', 1200, '', false );
	}

	/**
	 * Register default function when corresponding plugins are not activated
	 *
	 * @since 4.0 in init class
	 *
	 * @return void
	 */
	public function register_third_party_plugin_functions() {

		// WooCommerce functions
		if ( ! function_exists( 'is_woocommerce' ) ) {
			function is_woocommerce() { return false; }
		}

		// bbPress functions
		if ( ! function_exists( 'is_bbpress' ) ) {
			function is_bbpress() { return false; }
		}

		if ( ! function_exists( 'bbp_is_forum_archive' ) ) {
			function bbp_is_forum_archive() { return false; }
		}

		if ( ! function_exists( 'bbp_is_topic_archive' ) ) {
			function bbp_is_topic_archive() { return false; }
		}

		if ( ! function_exists( 'bbp_is_user_home' ) ) {
			function bbp_is_user_home() { return false; }
		}

		if ( ! function_exists( 'bbp_is_search' ) ) {
			function bbp_is_search() { return false; }
		}

		// buddyPress functions
		if ( ! function_exists( 'is_buddypress' ) ) {
			function is_buddypress() { return false; }
		}

		// The Events Calendar functions
		if ( ! function_exists( 'tribe_is_event' ) ) {
			function tribe_is_event() { return false; }
		}

		if ( ! function_exists( 'is_events_archive' ) ) {
			function is_events_archive() { return false; }
		}
	}

	/**
	 * Register navigation menus
	 */
	public function register_nav_menus() {

		register_nav_menu( 'main_navigation', 'Main Navigation' );
		register_nav_menu( 'top_navigation', 'Top Navigation' );
		register_nav_menu( '404_pages', '404 Useful Pages' );
		register_nav_menu( 'sticky_navigation', 'Sticky Header Navigation' );

	}

	public function theme_activation() {

		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {

			update_option( 'shop_catalog_image_size',   array( 'width' => 500, 'height' => '', 0 ) );
			update_option( 'shop_single_image_size',    array( 'width' => 500, 'height' => '', 0 ) );
			update_option( 'shop_thumbnail_image_size', array( 'width' => 120, 'height' => '', 0 ) );

		}

	}

	/*public function migrate_term_data() {
		$version = get_bloginfo( 'version' );
		$function_test = function_exists( 'add_term_meta' );

		if ( version_compare( $version, '4.4', '>=' ) && ! $function_test ) {

		}
	}*/

	public function main_menu_args( $args ) {

		global $post;

		$c_pageID = Avada::c_pageID();

		if ( get_post_meta( $c_pageID, 'pyre_displayed_menu', true ) != '' && get_post_meta( $c_pageID, 'pyre_displayed_menu', true ) != 'default' && ( $args['theme_location'] == 'main_navigation' || $args['theme_location'] == 'sticky_navigation' ) ) {
			$menu = get_post_meta( $c_pageID, 'pyre_displayed_menu', true );
			$args['menu'] = $menu;
		}

		return $args;

	}

	public function youtube_flash_fix() {
		echo '<div class="fusion-youtube-flash-fix">&shy;<style type="text/css"> iframe { visibility: hidden; opacity: 0; } </style></div>';
	}

	public function widget_init() {

		register_widget( 'Fusion_Widget_Ad_125_125' );
		register_widget( 'Fusion_Widget_Contact_Info' );
		register_widget( 'Fusion_Widget_Tabs' );
		register_widget( 'Fusion_Widget_Recent_Works' );
		register_widget( 'Fusion_Widget_Tweets' );
		register_widget( 'Fusion_Widget_Flickr' );
		register_widget( 'Fusion_Widget_Social_Links' );
		register_widget( 'Fusion_Widget_Facebook_Page' );
		register_widget( 'Fusion_Widget_Menu' );

	}

	public function remove_post_format_from_link( $url ) {
		$url = remove_query_arg( 'post_format', $url );
		return $url;
	}

	public function set_correct_class_for_menu_items( $menu_items ) {
		global $wp_query, $wp_rewrite;
		$url = get_home_url();
		$url = str_replace( 'http://', '', $url );
		$url = str_replace( 'https://', '', $url );

		foreach ( (array) $menu_items as $key => $menu_item ) {

			// Make sure we are in a custom menu item with a url placeholder
			if ( 'custom' == $menu_item->object && false !== strpos( $menu_item->url, 'fusion_home_url' ) ) {
				// Replace the fusion_home_url placeholder with the home url
				$menu_item->url = str_replace( 'fusion_home_url', $url, $menu_item->url );

				$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
				$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
				$raw_item_url = strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url;
				$item_url = set_url_scheme( untrailingslashit( $raw_item_url ) );
				$_indexless_current = untrailingslashit( preg_replace( '/' . preg_quote( $wp_rewrite->index, '/' ) . '$/', '', $current_url ) );

				if ( $raw_item_url && in_array( $item_url, array( $current_url, $_indexless_current, $_root_relative_current ) ) ) {
					$menu_items[$key]->classes[] = 'current-menu-item';
					$menu_items[$key]->current = true;
				} elseif ( $item_url == home_url() && is_front_page() ) {
					$menu_items[$key]->classes[] = 'current-menu-item';
				}
			}
		}

	    return $menu_items;
	}



}

// Omit closing PHP tag to avoid "Headers already sent" issues.
