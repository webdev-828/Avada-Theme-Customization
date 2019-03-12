<?php

/**
 * A helper class that depending on the active multilingual plugin
 * will get the available languages as well as the active language.
 * Currently handles compatibility with WPML & PolyLang.
 */
class Avada_Multilingual {

	// Are we using WPML?
	private static $is_wpml = false;
	// Are we using PolyLang?
	private static $is_pll = false;
	// An array of all available languages
	private static $available_languages = array();
	// The active language
	private static $active_language = 'en';
	// The "main" language
	private static $main_language = 'en';

	/**
	 * The main class constructor.
	 * Sets the static properties of this object.
	 */
	public function __construct() {

		// Set the $is_pll property.
		self::$is_pll = self::is_pll();
		// Set the $is_wpml property
		self::$is_wpml = self::is_wpml();

		// Set the $available_languages property
		self::set_available_languages();
		// Set the $main_language properly
		self::set_main_language();
		// Set the $active_language property
		self::set_active_language();

	}

	/**
	 * Sets the available languages depending on the active plugin.
	 */
	private static function set_available_languages() {
		if ( self::$is_pll ) {
			self::$available_languages = self::get_available_languages_pll();
		} elseif ( self::$is_wpml ) {
			self::$available_languages = self::get_available_languages_wpml();
		}
	}

	/**
	 * Gets the $active_language protected property
	 */
	public static function get_active_language() {
		self::set_active_language();
		return self::$active_language;
	}

	/**
	 * Sets the active language
	 *
	 * @param string|bool
	 */
	public static function set_active_language( $lang = false ) {

		if ( is_string( $lang ) && ! empty( $lang ) ) {
			self::$active_language = $lang;
		}
		// If we have not defined a language, then autodetect
		if ( false == $lang || empty( $lang ) ) {
			// No need to proceed if both WPML & PLL are inactive
			if ( ! self::$is_pll && ! self::$is_wpml ) {
				return 'en';
			}
			// Preliminary work for PLL.
			// Adds the WPML compatibility layer.
			if ( self::$is_pll && function_exists( 'pll_define_wpml_constants' ) ) {
				pll_define_wpml_constants();
			}
			// PLL-WPML compatibility is active, we can now work easier.
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				self::$active_language = ICL_LANGUAGE_CODE;
				if ( 'all' == ICL_LANGUAGE_CODE ) {
					Avada::set_language_is_all( true );
					if ( self::$is_wpml ) {
						global $sitepress;
						self::$active_language = $sitepress->get_default_language();
					} elseif ( self::$is_pll ) {
						self::$active_language = pll_default_language( 'slug' );
					}
				}
			}
		}
	}

	/**
	 * Gets the $available_languages protected property
	 */
	public static function get_available_languages() {
		if ( empty( self::$available_languages ) ) {
			self::set_available_languages();
		}
		return self::$available_languages;
	}

	/**
	 * Get the available languages from WPML
	 *
	 * @return array
	 */
	private static function get_available_languages_wpml() {
		// Do not continue processing if we're not using WPML
		if ( ! self::$is_wpml ) {
			return array();
		}
		$wpml_languages = icl_get_languages( 'skip_missing=0' );
		$languages      = array();
		foreach ( $wpml_languages as $language_key => $args ) {
			$languages[] = $args['code'];
		}
		return $languages;

	}

	/**
	 * Gets the default language
	 *
	 * @return string
	 */
	public static function get_default_language() {
		self::set_main_language();
		return self::$main_language;
	}

	/**
	 * Sets the $main_language based on the active plugin
	 *
	 * @return void
	 */
	private static function set_main_language() {
		if ( self::$is_pll ) {
			self::$main_language = self::get_main_language_pll();
		} elseif ( self::$is_wpml ) {
			self::$main_language = self::get_main_language_wpml();
		}
	}

	/**
	 * Get the default language for WPML
	 *
	 * @return string
	 */
	private static function get_main_language_wpml() {
		global $sitepress;
		return $sitepress->get_default_language();
	}

	/**
	 * Get the default language for PolyLang
	 *
	 * @return string
	 */
	private static function get_main_language_pll() {
		return pll_default_language( 'slug' );
	}

	/**
	 * Get the available languages from PolyLang
	 *
	 * @return array
	 */
	private static function get_available_languages_pll() {
		// Do not continue processing if we're not using PLL
		if ( ! self::$is_pll ) {
			return array();
		}

		global $polylang;
		// Get the PLL languages object
		$pll_languages_obj = $polylang->model->get_languages_list();
		// Parse the object and get a usable array
		$pll_languages = array();
		foreach ( $pll_languages_obj as $pll_language_obj ) {
			$pll_languages[] = $pll_language_obj->slug;
		}

		return $pll_languages;
	}

	/**
	 * Determine if we're using PolyLang
	 *
	 * @return bool
	 */
	private static function is_pll() {
		return ( function_exists( 'pll_default_language' ) ) ? true : false;
	}

	/**
	 * Determine if we're using WPML.
	 * Since PLL has a compatibility layer for WPML, we'll have to consider that too.
	 */
	private static function is_wpml() {
		return ( defined( 'ICL_SITEPRESS_VERSION' ) && false === self::$is_pll ) ? true : false;
	}
}
