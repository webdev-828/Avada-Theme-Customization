<?php
defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );

// Don't resize images
function avada_filter_image_sizes( $sizes ) {
	return array();
}
// Hook importer into admin init
add_action( 'wp_ajax_fusion_import_demo_data', 'fusion_importer' );
function fusion_importer() {
	global $wpdb;

	if ( current_user_can( 'manage_options' ) ) {
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers

		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
			$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			include $wp_importer;
		}

		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			$wp_import = get_template_directory() . '/includes/plugins/importer/wordpress-importer.php';
			include $wp_import;
		}

		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) { // check for main import class and wp import class
			if( ! isset($_POST['demo_type']) || trim($_POST['demo_type']) == '' ) {
				$demo_type = 'classic';
			} else {
				$demo_type = $_POST['demo_type'];
			}

			switch($demo_type) {
				case 'agency':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/agency_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/agency_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/agency_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/agency_demo/fusion_slider.zip';
				break;
				case 'app':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/app_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/app_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/app_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/app_demo/fusion_slider.zip';
				break;
				case 'travel':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/travel_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/travel_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/travel_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/travel_demo/fusion_slider.zip';
				break;
				case 'cafe':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/cafe_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/cafe_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/cafe_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/cafe_demo/fusion_slider.zip';
				break;
				case 'fashion':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/fashion_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/fashion_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/fashion_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/fashion_demo/fusion_slider.zip';
				break;
				case 'architecture':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/architecture_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/architecture_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/architecture_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/architecture_demo/fusion_slider.zip';
				break;
				case 'hosting':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/hosting_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/hosting_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/hosting_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/hosting_demo/fusion_slider.zip';
				break;
				case 'hotel':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/hotel_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/hotel_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/hotel_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/hotel_demo/fusion_slider.zip';
				break;
				case 'law':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/law_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/law_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/law_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/law_demo/fusion_slider.zip';
				break;
				case 'lifestyle':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/lifestyle_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/lifestyle_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/lifestyle_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/lifestyle_demo/fusion_slider.zip';
				break;
				case 'church':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/church_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/church_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = true;
					$sidebars = array(
						'PageSidebar' => 'Page Sidebar'
					);

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/church_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/church_demo/fusion_slider.zip';
				break;
				case 'gym':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/gym_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/gym_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/gym_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/gym_demo/fusion_slider.zip';
				break;
				case 'photography':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/photography_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/photography_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/photography_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/photography_demo/fusion_slider.zip';
				break;				
				case 'modern_shop':
					// is a shop demo?
					$shop_demo = true;
					// Set pages
					$woopages = array(
						'woocommerce_shop_page_id' => 'Full Shop With Sidebar',
						'woocommerce_cart_page_id' => 'Cart',
						'woocommerce_checkout_page_id' => 'Checkout',
						'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
						'woocommerce_thanks_page_id' => 'Order Received',
						'woocommerce_myaccount_page_id' => 'My Account',
						'woocommerce_edit_address_page_id' => 'Edit My Address',
						'woocommerce_view_order_page_id' => 'View Order',
						'woocommerce_change_password_page_id' => 'Change Password',
						'woocommerce_logout_page_id' => 'Logout',
						'woocommerce_lost_password_page_id' => 'Lost Password'
					);
					$woo_xml = get_template_directory() . '/includes/plugins/importer/modern_shop_demo/avada.xml';

					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/modern_shop_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/modern_shop_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = true;
					$sidebars = array(
						'Shop' => 'Shop'
					);

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/modern_shop_demo/widget_data.json';

					$layerslider_exists = false;
					$revslider_exists = false;

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/modern_shop_demo/fusion_slider.zip';
				break;
				case 'classic_shop':
					// is a shop demo?
					$shop_demo = true;
					// Set pages
					$woopages = array(
						'woocommerce_shop_page_id' => 'Shop Full Width',
						'woocommerce_cart_page_id' => 'Shopping Cart',
						'woocommerce_checkout_page_id' => 'Checkout',
						'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
						'woocommerce_thanks_page_id' => 'Order Received',
						'woocommerce_myaccount_page_id' => 'My Account',
						'woocommerce_edit_address_page_id' => 'Edit My Address',
						'woocommerce_view_order_page_id' => 'View Order',
						'woocommerce_change_password_page_id' => 'Change Password',
						'woocommerce_logout_page_id' => 'Logout',
						'woocommerce_lost_password_page_id' => 'Lost Password'
					);
					$woo_xml = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/avada.xml';

					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = true;
					$sidebars = array(
						'Men' => 'Men',
						'Women' => 'Women',
						'Accessories' => 'Accessories',
						'Products Sidebar' => 'ProductsSidebar',
						'Content Widget 1' => 'ContentWidget1',
						'Content Widget 2' => 'ContentWidget2',
						'Content Widget 3' => 'ContentWidget3',
						'Content Widget 4' => 'ContentWidget4',
						'Promotion' => 'Promotion'
					);
					$sidebars = array_flip( $sidebars ); // lazy code

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/widget_data.json';

					$layerslider_exists = false;

					$revslider_exists = true;
					$rev_directory = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/revsliders/';

					// reading settings
					$homepage_title = 'Home 1';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/classic_shop_demo/fusion_slider.zip';
				break;
				case 'landing_product':
				// is a shop demo?
					$shop_demo = true;
					// Set pages
					$woopages = array(
						'woocommerce_shop_page_id' => 'Shop',
						'woocommerce_cart_page_id' => 'Cart',
						'woocommerce_checkout_page_id' => 'Checkout',
						'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
						'woocommerce_thanks_page_id' => 'Order Received',
						'woocommerce_myaccount_page_id' => 'My Account',
						'woocommerce_edit_address_page_id' => 'Edit My Address',
						'woocommerce_view_order_page_id' => 'View Order',
						'woocommerce_change_password_page_id' => 'Change Password',
						'woocommerce_logout_page_id' => 'Logout',
						'woocommerce_lost_password_page_id' => 'Lost Password'
					);
					$woo_xml = get_template_directory() . '/includes/plugins/importer/landing_product_demo/avada.xml';

					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/landing_product_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/landing_product_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = false;

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/landing_product_demo/widget_data.json';

					$layerslider_exists = false;

					$revslider_exists = true;
					$rev_directory = get_template_directory() . '/includes/plugins/importer/landing_product_demo/revsliders/';

					// reading settings
					$homepage_title = 'Homepage';

					$fs_exists = false;
				break;
				case 'forum':
					$shop_demo = false;
					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/forum_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/forum_demo/theme_options.json';

					// Register Custom Sidebars
					$sidebar_exists = true;
					$sidebars = array(
						'News Sidebar' => 'NewsSidebar',
						'Forum Sidebar' => 'ForumSidebar',
						'Apple Sidebar' => 'AppleSidebar',
						'Android Sidebar' => 'AndroidSidebar',
						'Microsoft Sidebar' => 'MicrosoftSidebar'
					);
					$sidebars = array_flip( $sidebars ); // lazy code

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/forum_demo/widget_data.json';

					$layerslider_exists = false;

					$revslider_exists = false;
					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/forum_demo/fusion_slider.zip';
				break;
				default:
					// is a shop demo?
					$shop_demo = true;
					// Set pages
					$woopages = array(
						'woocommerce_shop_page_id' => 'Shop',
						'woocommerce_cart_page_id' => 'Cart',
						'woocommerce_checkout_page_id' => 'Checkout',
						'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
						'woocommerce_thanks_page_id' => 'Order Received',
						'woocommerce_myaccount_page_id' => 'My Account',
						'woocommerce_edit_address_page_id' => 'Edit My Address',
						'woocommerce_view_order_page_id' => 'View Order',
						'woocommerce_change_password_page_id' => 'Change Password',
						'woocommerce_logout_page_id' => 'Logout',
						'woocommerce_lost_password_page_id' => 'Lost Password'
					);
					$woo_xml = get_template_directory() . '/includes/plugins/importer/classic_demo/avada.xml';

					$theme_xml_file = get_template_directory() . '/includes/plugins/importer/classic_demo/avada.xml';
					$theme_options_file = get_template_directory() . '/includes/plugins/importer/classic_demo/theme_options.json';


					// Register Custom Sidebars
					$sidebar_exists = true;
					$sidebars = array(
						'ContactSidebar' => 'Contact Sidebar',
						'FAQ' => 'FAQ',
						'HomepageSidebar' => 'Home Page Sidebar',
						'Portfolio' => 'Portfolio',
						'Megamenu1' => 'Megamenu1',
						'Megamenu2' => 'Megamenu2',
						'Twitter' => 'Twitter',
						'PageWidget1' => 'Page Widget 1',
						'PageWidget2' => 'Page Widget 2',
					);

					// Sidebar Widgets File
					$widgets_file = get_template_directory() . '/includes/plugins/importer/classic_demo/widget_data.json';

					$layerslider_exists = true;
					$layer_directory = get_template_directory() . '/includes/plugins/importer/classic_demo/layersliders/';

					$revslider_exists = true;
					$rev_directory = get_template_directory() . '/includes/plugins/importer/classic_demo/revsliders/';

					// reading settings
					$homepage_title = 'Home';

					$fs_exists = true;
					$fs_url = get_template_directory() . '/includes/plugins/importer/classic_demo/fusion_slider.zip';
			}

			add_filter('intermediate_image_sizes_advanced', 'avada_filter_image_sizes');

			/* Import Woocommerce if WooCommerce Exists */
			if( class_exists('WooCommerce') && $shop_demo == true ) {
				$importer = new WP_Import();
				$theme_xml = $woo_xml;
				$importer->fetch_attachments = true;
				ob_start();
				$importer->import($theme_xml);
				ob_end_clean();

				foreach($woopages as $woo_page_name => $woo_page_title) {
					$woopage = get_page_by_title( $woo_page_title );
					if(isset( $woopage ) && $woopage->ID) {
						update_option($woo_page_name, $woopage->ID); // Front Page
					}
				}

				// We no longer need to install pages
				delete_option( '_wc_needs_pages' );
				delete_transient( '_wc_activation_redirect' );

				// Flush rules after install
				flush_rewrite_rules();
			} else {
				$importer = new WP_Import();
				/* Import Posts, Pages, Portfolio Content, FAQ, Images, Menus */
				$theme_xml = $theme_xml_file;
				$importer->fetch_attachments = true;
				//ob_start();
				$importer->import($theme_xml);
				//ob_end_clean();

				flush_rewrite_rules();
			}

			// Set imported menus to registered theme locations
			$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
			$menus = wp_get_nav_menus(); // registered menus

			if($menus) {
				if ( $demo_type == 'classic' ) {
					$opmenu = get_page_by_title( 'One Page' );
				} else if ( $demo_type == 'landing_product' ) {
					$opmenu = get_page_by_title( 'Homepage' );
				}
				foreach($menus as $menu) { // assign menus to theme locations
					if( $demo_type == 'classic' ) {
						if( $menu->name == 'Main' ) {
							$locations['main_navigation'] = $menu->term_id;
						} else if( $menu->name == '404' ) {
							$locations['404_pages'] = $menu->term_id;
						} else if( $menu->name == 'Top' ) {
							$locations['top_navigation'] = $menu->term_id;
						}

						// Assign One Page Menu
						if(isset( $opmenu ) && $opmenu->ID && $menu->name == 'One Page') {
							update_post_meta($opmenu->ID, 'pyre_displayed_menu', $menu->term_id);
						}
					} elseif( $demo_type == 'agency' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'app' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'travel' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'cafe' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'fashion' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'architecture' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'hosting' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'hotel' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'law' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'lifestyle' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'church' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'gym' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'photography' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}						
					} elseif( $demo_type == 'modern_shop' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'classic_shop' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						} else if( $menu->name == 'Top Secondary Menu' ) {
							$locations['top_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'landing_product' ) {
						// Assign One Page Menu
						if ( isset( $opmenu ) && $opmenu->ID && $menu->name == 'Landing Page Menu' ) {
							update_post_meta( $opmenu->ID, 'pyre_displayed_menu', $menu->term_id );
						}

						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					} elseif( $demo_type == 'forum' ) {
						if( $menu->name == 'Main Menu' ) {
							$locations['main_navigation'] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations

			// Import Theme Options
			$theme_options_json = file_get_contents( $theme_options_file );
			$theme_options = json_decode( $theme_options_json, true );
			$theme_options['logo_retina'] = '';
			$theme_options_db_name = Avada::get_original_option_name();
			update_option( $theme_options_db_name, $theme_options );			

			// Add sidebar widget areas
			if($sidebar_exists == true) {
				update_option( 'sbg_sidebars', $sidebars );

				foreach( $sidebars as $sidebar ) {
					$sidebar_class = avada_name_to_class( $sidebar );
					register_sidebar(array(
						'name'=>$sidebar,
						'id' => 'avada-custom-sidebar-' . strtolower( $sidebar_class ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget' => '</div>',
						'before_title' => '<div class="heading"><h4 class="widget-title">',
						'after_title' => '</h4></div>',
					));
				}
			}

			// Add data to widgets
			if( isset( $widgets_file ) && $widgets_file ) {
				$widgets_json = $widgets_file; // widgets data file
				$widgets_json = file_get_contents( $widgets_json );
				$widget_data = $widgets_json;
				$import_widgets = fusion_import_widget_data( $widget_data );
			}

			// Import Layerslider
			if( function_exists( 'layerslider_import_sample_slider' ) && $layerslider_exists == true ) { // if layerslider is activated
				// Get importUtil
				include WP_PLUGIN_DIR . '/LayerSlider/classes/class.ls.importutil.php';

				$layer_files = fusion_get_import_files( $layer_directory, 'zip' );

				foreach( $layer_files as $layer_file ) { // finally import layer slider
					$import = new LS_ImportUtil($layer_file);
				}

				// Get all sliders
				// Table name
				$table_name = $wpdb->prefix . "layerslider";

				// Get sliders
				$sliders = $wpdb->get_results( "SELECT * FROM $table_name
													WHERE flag_hidden = '0' AND flag_deleted = '0'
													ORDER BY date_c ASC" );

				if(!empty($sliders)):
				foreach($sliders as $key => $item):
					$slides[$item->id] = $item->name;
				endforeach;
				endif;

				if($slides){
					foreach($slides as $key => $val){
						$slides_array[$val] = $key;
					}
				}

				// Assign LayerSlider
				if( $demo_type == 'classic' ) {
					$lspage = get_page_by_title( 'Layer Slider' );
					if(isset( $lspage ) && $lspage->ID && $slides_array['Avada Full Width']) {
						update_post_meta($lspage->ID, 'pyre_slider', $slides_array['Avada Full Width']);
					}
				}
			}

			// Import Revslider
			if( class_exists('UniteFunctionsRev') && $revslider_exists == true ) { // if revslider is activated
				$rev_files = fusion_get_import_files( $rev_directory, 'zip' );

				$slider = new RevSlider();
				foreach( $rev_files as $rev_file ) { // finally import rev slider data files

					$filepath = $rev_file;

					ob_start();
					$slider->importSliderFromPost(true, false, $filepath);
					ob_clean();
					ob_end_clean();
				}
			}

			// Set reading options
			$homepage = get_page_by_title( $homepage_title );
			if(isset( $homepage ) && $homepage->ID) {
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID); // Front Page
			}

			// Fusion Sliders Import
			if( $fs_exists == true ) {
				@avada_import_fsliders( $fs_url );
			}

			update_option( 'avada_imported_demo', 'true' );

			echo 'imported';

			exit;
		}
	}
}

// Parsing Widgets Function
// Thanks to http://wordpress.org/plugins/widget-settings-importexport/
function fusion_import_widget_data( $widget_data ) {
	$json_data = $widget_data;
	$json_data = json_decode( $json_data, true );

	$sidebar_data = $json_data[0];
	$widget_data = $json_data[1];

	foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
		$widgets[ $widget_data_title ] = '';
		foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
			if( is_int( $widget_data_key ) ) {
				$widgets[$widget_data_title][$widget_data_key] = 'on';
			}
		}
	}
	unset($widgets[""]);

	foreach ( $sidebar_data as $title => $sidebar ) {
		$count = count( $sidebar );
		for ( $i = 0; $i < $count; $i++ ) {
			$widget = array( );
			$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
			$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
			if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
				unset( $sidebar_data[$title][$i] );
			}
		}
		$sidebar_data[$title] = array_values( $sidebar_data[$title] );
	}

	foreach ( $widgets as $widget_title => $widget_value ) {
		foreach ( $widget_value as $widget_key => $widget_value ) {
			$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
		}
	}

	$sidebar_data = array( array_filter( $sidebar_data ), $widgets );

	fusion_parse_import_data( $sidebar_data );
}

function fusion_parse_import_data( $import_array ) {
	global $wp_registered_sidebars;
	$sidebars_data = $import_array[0];
	$widget_data = $import_array[1];
	$current_sidebars = get_option( 'sidebars_widgets' );
	$new_widgets = array( );

	foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

		foreach ( $import_widgets as $import_widget ) :
			//if the sidebar exists
			if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) :
				$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
				$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
				$current_widget_data = get_option( 'widget_' . $title );
				$new_widget_name = fusion_get_new_widget_name( $title, $index );
				$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

				if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
					while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
						$new_index++;
					}
				}
				$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
				if ( array_key_exists( $title, $new_widgets ) ) {
					if ( 'nav_menu' == $title & ! is_numeric( $index ) ) {
						$menu = wp_get_nav_menu_object( $index );
						$menu_id = $menu->term_id;
						$new_widgets[$title][$new_index] = $menu_id;
					} else {
						$new_widgets[$title][$new_index] = $widget_data[$title][$index];
					}				
					$multiwidget = $new_widgets[$title]['_multiwidget'];
					unset( $new_widgets[$title]['_multiwidget'] );
					$new_widgets[$title]['_multiwidget'] = $multiwidget;
				} else {
					if ( 'nav_menu' == $title & ! is_numeric( $index ) ) {
						$menu = wp_get_nav_menu_object( $index );
						$menu_id = $menu->term_id;
						$current_widget_data[$new_index] = $menu_id;
					} else {
						$current_widget_data[$new_index] = $widget_data[$title][$index];
					}
					$current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : false;
					$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
					$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
					unset( $current_widget_data['_multiwidget'] );
					$current_widget_data['_multiwidget'] = $multiwidget;
					$new_widgets[$title] = $current_widget_data;
				}

			endif;
		endforeach;
	endforeach;

	if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
		update_option( 'sidebars_widgets', $current_sidebars );

		foreach ( $new_widgets as $title => $content )
			update_option( 'widget_' . $title, $content );

		return true;
	}

	return false;
}

function fusion_get_new_widget_name( $widget_name, $widget_index ) {
	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array( );
	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				$all_widget_array[] = $widget;
			}
		}
	}
	while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
		$widget_index++;
	}
	$new_widget_name = $widget_name . '-' . $widget_index;
	return $new_widget_name;
}

if( function_exists( 'layerslider_import_sample_slider' ) ) {
	function avada_import_sample_slider( $layerslider_data ) {
		// Base64 encoded, serialized slider export code
		$sample_slider = $layerslider_data;

		// Iterate over the sliders
		foreach($sample_slider as $sliderkey => $slider) {

			// Iterate over the layers
			foreach($sample_slider[$sliderkey]['layers'] as $layerkey => $layer) {

				// Change background images if any
				if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'])) {
					$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'] = LS_ROOT_URL.'sampleslider/'.basename($layer['properties']['background']);
				}

				// Change thumbnail images if any
				if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'])) {
					$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'] = LS_ROOT_URL.'sampleslider/'.basename($layer['properties']['thumbnail']);
				}

				// Iterate over the sublayers
				if(isset($layer['sublayers']) && !empty($layer['sublayers'])) {
					foreach($layer['sublayers'] as $sublayerkey => $sublayer) {

						// Only IMG sublayers
						if($sublayer['type'] == 'img') {
							$sample_slider[$sliderkey]['layers'][$layerkey]['sublayers'][$sublayerkey]['image'] = LS_ROOT_URL.'sampleslider/'.basename($sublayer['image']);
						}
					}
				}
			}
		}

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Append duplicate
		foreach($sample_slider as $key => $val) {

			// Insert the duplicate
			$wpdb->query(
				$wpdb->prepare("INSERT INTO $table_name
									(name, data, date_c, date_m)
								VALUES (%s, %s, %d, %d)",
								$val['properties']['title'],
								json_encode($val),
								time(),
								time()
				)
			);
		}
	}
}

// Rename sidebar
function avada_name_to_class($name){
	$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);
	return $class;
}

/**
 * Import Fusion Sliders
 */
function avada_import_fsliders( $zip_file ) {
	$upload_dir = wp_upload_dir();
	$base_dir = trailingslashit( $upload_dir['basedir'] );
	$fs_dir = $base_dir . 'fusion_slider_exports/';

	@unlink ( $fs_dir . 'sliders.xml' );
	@unlink ( $fs_dir . 'settings.json' );

	if( file_exists( $fs_dir ) && is_dir( $fs_dir ) ) {
		@fusion_slider_delete_dir( $fs_dir );
	}

	$zip = new ZipArchive();
	$zip->open( $zip_file );
	$zip->extractTo( $fs_dir );
	$zip->close();

	if ( !defined('WP_LOAD_IMPORTERS') ) {
		define('WP_LOAD_IMPORTERS', true);
	}

	if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
		$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		include $wp_importer;
	}

	if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
		$wp_import = plugin_dir_path( __FILE__ ) . 'libs/wordpress-importer.php';
		include $wp_import;
	}

	if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
		$loop = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => -1, 'meta_key' => '_thumbnail_id' ) );

		while( $loop->have_posts() ) { $loop->the_post();
			$thumbnail_ids[get_post_meta( get_the_ID(), '_thumbnail_id', true )] = get_the_ID();
		}

		foreach( new DirectoryIterator( $fs_dir ) as $file ) {
			if( $file->isDot() || $file->getFilename() == '.DS_Store' ) {
				continue;
			}

			$image_path = pathinfo( $fs_dir . $file->getFilename() );
			if( $image_path['extension'] != 'xml' && $image_path['extension'] != 'json' ) {
				$filename = $image_path['filename'];
				$new_image_path = $upload_dir['path'] . '/' . $image_path['basename'];
				$new_image_url = $upload_dir['url'] . '/' . $image_path['basename'];
				@copy( $fs_dir . $file->getFilename(), $new_image_path );

				// Check the type of tile. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $new_image_path ), null );

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'		   => $new_image_url,
					'post_mime_type' => $filetype['type'],
					'post_title'	 => preg_replace( '/\.[^.]+$/', '', basename( $new_image_path ) ),
					'post_content'   => '',
					'post_status'	=> 'inherit'
				);

				// Insert the attachment.
				$attach_id = wp_insert_attachment( $attachment, $new_image_path, $thumbnail_ids[$filename] );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $new_image_path );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				set_post_thumbnail( $thumbnail_ids[$filename], $attach_id );
			}
		}

		$url = wp_nonce_url( 'edit.php?post_type=slide&page=fs_export_import' );
		if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
			return; // stop processing here
		}

		if( WP_Filesystem( $creds ) ) {
			global $wp_filesystem;

			$settings = $wp_filesystem->get_contents( $fs_dir . 'settings.json' );

			$decode = json_decode( $settings, TRUE );

			foreach( $decode as $slug => $settings ) {
				$get_term = get_term_by( 'slug', $slug, 'slide-page' );

				if( $get_term ) {
					update_option( 'taxonomy_' . $get_term->term_id, $settings );
				}
			}
		}
	}
}

function fusion_slider_delete_dir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = fusion_get_import_files( $dirPath, '*' );

    foreach ($files as $file) {
        if (is_dir($file)) {
            $this->deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

/*
* Returns all files in directory with the given filetype. Uses glob() for older
* php versions and recursive directory iterator otherwise.
*
* @param string $directory Directory that should be parsed
* @param string $filetype The file type
*
* @return array $files File names that match the $filetype
*/
function fusion_get_import_files( $directory, $filetype ) {
	$phpversion = phpversion();
	$files = array();

	// Check if the php version allows for recursive iterators
	if ( version_compare( $phpversion, '5.2.11', '>' ) ) {
		if ( $filetype != '*' )  {
			$filetype = '/^.*\.' . $filetype . '$/';
		} else {
			$filetype = '/.+\.[^.]+$/';
		}
		$directory_iterator = new RecursiveDirectoryIterator( $directory );
		$recusive_iterator = new RecursiveIteratorIterator( $directory_iterator );
		$regex_iterator = new RegexIterator( $recusive_iterator, $filetype );

		foreach( $regex_iterator as $file ) {
			$files[] = $file->getPathname();
		}
	// Fallback to glob() for older php versions
	} else {
		if ( $filetype != '*' )  {
			$filetype = '*.' . $filetype;
		}

		foreach( glob( $directory . $filetype ) as $filename ) {
			$filename = basename( $filename );
			$files[] = $directory . $filename;
		}
	}

	return $files;
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
