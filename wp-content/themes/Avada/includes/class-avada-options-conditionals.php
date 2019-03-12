<?php

/**
 * WIP
 * These are conditionals that will be used
 * in a future implementation of the customizer.
 * We'll be using them to show/hide options depending on the context.
 */
class Avada_Options_Conditionals {

	/**
	 * Conditional check:
	 * Figure out if WooCommerce is installed.
	 * If WooCommerce is installed, then check that we're on a Woo template.
	 *
	 * @return  bool
	 */
	public static function is_woo() {
		/**
		 * Check if WooCommerce is installed.
		 * If not, then return false
		 */
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}
		/**
		 * Return the result of the is_woocommerce() function (boolean)
		 */
		return is_woocommerce();

	}

	/**
	 * Conditional check:
	 * Figure out if the current layout is boxed or not.
	 *
	 * @return  bool
	 */
	public static function is_boxed() {
		return ( 'Boxed' == get_theme_mod( 'layout' ) ) ? true : false;

	}

	/**
	 * Conditional check:
	 * Figure out if we're using responsive typography or not.
	 *
	 * @return  bool
	 */
	public static function is_responsive_typography() {
		return ( '1' == get_theme_mod( 'typography_responsive' ) ) ? true : false;

	}

	/**
	 * Conditional check:
	 * Figure out if bbPress is installed.
	 * If bbPress is installed, then check that we're on a bbPress template.
	 *
	 * @return  bool
	 */
	public static function is_bbpress() {
		/**
		 * Check if bbPress is installed.
		 * If not, then return false
		 */
		if ( ! function_exists( 'is_bbpress' ) ) {
			return false;
		}
		/**
		 * Return the result of the is_woocommerce() function (boolean)
		 */
		return is_bbpress();

	}

	/**
	 * Conditional check:
	 * Figure out if we're on the blog page.
	 *
	 * @return  bool
	 */
	public static function is_blog() {
		if ( is_front_page() && is_home() ) { // Default homepage
			return true;
		} elseif ( is_front_page() ) { // static homepage
			return false;
		} elseif ( is_home() ) { // blog page
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Conditional check:
	 * Figure out if we're on the contact page or not.
	 *
	 * @return  bool
	 */
	public static function is_contact() {
		if ( is_page_template( 'contact.php' ) ) {
			return true;
		}
		return false;
	}

}
