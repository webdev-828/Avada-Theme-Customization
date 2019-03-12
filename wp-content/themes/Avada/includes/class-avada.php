<?php

/**
 * The main theme class
 */
class Avada {

	/**
	 * The one, true instance of the Avada object
	 *
	 * @static
	 * @access public
	 * @var null|object
	 */
	public static $instance = null;

	/**
	 * The theme version
	 *
	 * @static
	 * @access public
	 * @var string
	 */
	public static $version = '4.0.2';

	/**
	 * The original option name.
	 * This is the untainted option name, without using any languages.
	 * If you want the property including language, use $option_name instead.
	 *
	 * @static
	 * @access private
	 * @var string
	 */
	private static $original_option_name = 'avada_theme_options';

	/**
	 * The option name including the language suffix.
	 * If you want the option name without language, use $original_option_name
	 *
	 * @static
	 * @access private
	 * @var string
	 */
	private static $option_name = '';

	/**
	 * The language we're using.
	 * This is used to modify $option_name
	 * It is the language code prefixed with a '_'
	 *
	 * @static
	 * @access public
	 * @var string
	 */
	public static $lang = '';

	/**
	 * Determine if the language has been applied to the $option_name.
	 *
	 * @static
	 * @access public
	 * @var bool
	 */
	public static $lang_applied = false;

	/**
	 * Dertermine if the current language is set to "all".
	 *
	 * @static
	 * @access private
	 * @var bool
	 */
	private static $language_is_all = false;

	/**
	 * Determine if we're currently upgrading/migration options.
	 *
	 * @static
	 * @access public
	 * @var bool
	 */
	public static $is_updating  = false;

	/**
	 * @access public
	 * @var object Avada_Settings
	 */
	public $settings;

	/**
	 * @access public
	 * @var object Avada_Options
	 */
	public $options;

	/**
	 * @access public
	 * @var object Avada_Init
	 */
	public $init;

	/**
	 * @access public
	 * @var object Avada_Social_Sharing
	 */
	public $social_sharing;

	/**
	 * @access public
	 * @var object Avada_Template
	 */
	public $template;

	/**
	 * @access public
	 * @var object Avada_Blog
	 */
	public $blog;

	/**
	 * @access public
	 * @var object Avada_Images
	 */
	public $images;

	/**
	 * @access public
	 * @var object Avada_Head
	 */
	public $head;

	/**
	 * @access public
	 * @var object Avada_Layout
	 */
	public $layout;

	/**
	 * @access public
	 * @var object Avada_Dynamic_CSS
	 */
	public $dynamic_css;

	/**
	 * @access public
	 * @var object Avada_GoogleMap
	 */
	public $google_map;

	/**
	 * @access public
	 * @var object Avada_EventsCalendar
	 */
	public $events_calendar;

	/**
	 * The current page ID.
	 *
	 * @access public
	 * @var bool|int
	 */
	public $c_pageID = false;

	/**
	 * Access the single instance of this class
	 * @return Avada
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Avada();
		}
		return self::$instance;
	}

	/**
	 * Shortcut method to get the settings
	 */
	public static function settings() {
		return self::get_instance()->settings->get_all();
	}

	/**
	 * The class constructor
	 */
	private function __construct() {

		$this->set_is_updating();

		// Multilingual handling
		self::multilingual_options();
		// Make sure that $option_name is set.
		// This is run AFTER the multilingual option as a fallback.
		if ( empty( self::$option_name ) ) {
			self::$option_name = self::get_option_name();
		}

		// Instantiate secondary classes
		$this->settings       = Avada_Settings::get_instance();
		$this->options        = Avada_Options::get_instance();
		$this->init           = new Avada_Init();
		$this->social_sharing = new Avada_Social_Sharing();
		$this->template       = new Avada_Template();
		$this->blog           = new Avada_Blog();
		$this->images         = new Avada_Images();
		$this->head           = new Avada_Head();
		$this->dynamic_css    = new Avada_Dynamic_CSS();
		$this->layout         = new Avada_Layout();
		$this->google_map     = new Avada_GoogleMap();

		add_action( 'wp', array( $this, 'set_page_id' ) );

	}

	/**
	 * Checks if we're in the migration page
	 * It does that by checking _GET, and then sets the $is_updating property.
	 */
	public function set_is_updating() {
		if ( ! self::$is_updating && $_GET && isset( $_GET['avada_update'] ) && '1' == $_GET['avada_update'] ) {
			self::$is_updating = true;
		}
	}

	/**
	 * Gets the theme version.
	 *
	 * @return string
	 */
	public static function get_theme_version() {
		return self::$version;
	}

	/**
	 * sets the current page ID.
	 *
	 * @uses self::c_pageID
	 */
	public function set_page_id() {
		$this->c_pageID = self::c_pageID();
	}

	/**
	 * Gets the current page ID.
	 *
	 * @return bool|int
	 */
	public static function c_pageID() {
		$object_id = get_queried_object_id();

		$c_pageID = false;

		if ( get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) && is_home() ) {
			$c_pageID = get_option( 'page_for_posts' );
		} else {
			// Use the $object_id if available
			if ( isset( $object_id ) ) {
				$c_pageID = $object_id;
			}
			// If we're not on a singular post, set to false
			if ( ! is_singular() ) {
				$c_pageID = false;
			}
			// Front page is the posts page
			if ( isset( $object_id ) && 'posts' == get_option( 'show_on_front' ) && is_home() ) {
				$c_pageID = $object_id;
			}
			// The woocommerce shop page
			if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
				$c_pageID = get_option( 'woocommerce_shop_page_id' );
			}

		}

		return $c_pageID;

	}

	/**
	 * sets the $lang property for this object.
	 * Languages are prefixed with a '_'
	 *
	 * If we're not currently performing a migration
	 * it also checks if the options for the current language are set.
	 * If they are not, then we will copy the options from the main language.
	 */
	public static function multilingual_options() {
		// Set the self::$lang
		if ( ! in_array( Avada_Multilingual::get_active_language(), array( '', 'en', 'all' ) ) ) {
			self::$lang = '_' . Avada_Multilingual::get_active_language();
		}
		// Make sure the options are copied if needed
		if ( ! in_array( self::$lang, array( '', 'en', 'all' ) ) && ! self::$lang_applied ) {
			// Set the $option_name property
			self::$option_name = self::get_option_name();
			// Get the options without using a language (defaults)
			$original_options = get_option( self::$original_option_name, array() );
			// Get options with a language
			$options = get_option( self::$original_option_name . self::$lang, array() );
			// If we're not currently performing a migration and the options are not set
			// then we must copy the default options to the new language.
			if ( ! self::$is_updating && ! empty( $original_options ) && empty( $options ) ) {
				update_option( self::$original_option_name . self::$lang, get_option( self::$original_option_name ) );
			}
			// Modify the option_name to include the language
			self::$option_name  = self::$original_option_name . self::$lang;
			// Set $lang_applied to true. Makes sure we don't do the above more than once.
			self::$lang_applied = true;
		}
	}

	/**
	 * Get the private $option_name
	 *
	 * @since 4.0
	 *
	 * @return string current theme version
	 */
	public static function get_version() {
		return self::$version;
	}

	/**
	 * Get the private $option_name
	 * If empty returns the original_option_name
	 *
	 * @return string
	 */
	public static function get_option_name() {
		if ( empty( self::$option_name ) ) {
			return self::$original_option_name;
		}
		return self::$option_name;
	}

	/**
	 * Get the private $original_option_name
	 *
	 * @return string
	 */
	public static function get_original_option_name() {
		return self::$original_option_name;
	}

	/**
	 * Change the private $option_name
	 */
	public static function set_option_name( $option_name = false ) {
		if ( false !== $option_name && ! empty( $option_name ) ) {
			self::$option_name = $option_name;
		}
	}

	/**
	 * Change the private $language_is_all property.
	 *
	 * @static
	 * @access public
	 * @param bool $is_all Whether we're on the "all" language option or not.
	 * @return null|void
	 */
	public static function set_language_is_all( $is_all ) {
		if ( true === $is_all ) {
			self::$language_is_all = true;
			return;
		}
		self::$language_is_all = false;
	}

	/**
	 * Get the private $language_is_all property.
	 *
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function get_language_is_all() {
		return self::$language_is_all;
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
