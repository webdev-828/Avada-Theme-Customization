<?php

class Avada_Settings {

	public static $instance = null;

	public static $options_with_id = array();

	public static $saved_options = array();

	/**
	 * Access the single instance of this class
	 * @return Avada_Settings
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new Avada_Settings();
		}
		return self::$instance;
	}

	/**
	 * The class constructor
	 */
	public function __construct() {

		self::$saved_options   = get_option( Avada::get_option_name() );
		self::$options_with_id = Avada_Options::get_option_fields();

	}

	/**
	 * Get all settings
	 */
	public function get_all() {

		global $smof_data;
		return $smof_data;

	}

	/**
	 * Gets the value of a single setting
	 */
	public function get( $setting = null, $subset = false ) {

		if ( is_null( $setting ) || empty( $setting ) ) {
			return '';
		}

		$settings   = self::$saved_options;
		$all_fields = Avada_Options::get_option_fields();

		if ( is_array( $settings ) && isset( $settings[ $setting ] ) ) {
			// Setting is saved so retrieve it from the db
			$value = $settings[ $setting ];

			if ( $subset ) {
				// Hack for typography fields
				if ( 'typography' == $all_fields[ $setting ]['type'] ) {
					if ( 'font-family' == $subset ) {
						if ( isset( $value['font-family'] ) && 'select font' == strtolower( $value['font-family'] ) ) {
							return '';
						}
					} elseif ( 'color' == $subset ) {
						if ( isset( $value['color'] ) && ( '' == $value['color'] || empty( $value['color'] ) ) ) {
							// get the default value. Colors should not be empty
							return $this->get_default( $setting, $subset );
						}
					}
				}

				if ( is_array( $value ) && isset( $value[ $subset ] ) ) {
					// The subset is set so we can just return it
					return $value[ $subset ];
				} else {
					// If we've reached this point then the setting has not been set in the db.
					// We'll need to get the default value.
					return $this->get_default( $setting, $subset );
				}
			} else {
				// Hack for color & color-alpha fields
				if ( isset( $all_fields[ $setting ]['type'] ) && in_array( $all_fields[ $setting ]['type'], array( 'color', 'color-alpha' ) ) ) {
					if ( '' == $value || empty( $value ) ) {
						return $this->get_default( $setting, $subset );
					}
				}
				// We don't want a subset so just return the value
				return $value;
			}
		} else {
			// If we've reached this point then the setting has not been set in the db.
			// We'll need to get the default value.
			return $this->get_default( $setting, $subset );
		}
	}

	/**
	 * Sets the value of a single setting
	 */
	public function set( $setting, $value ) {

		$settings = self::$saved_options;
		$settings[ $setting ] = $value;
		update_option( Avada::get_option_name(), $settings );

	}

	/**
	 * Gets the default value of a single setting
	 */
	public function get_default( $setting = null, $subset = false ) {

		if ( is_null( $setting ) || empty( $setting ) ) {
			return '';
		}

		$all_fields = Avada_Options::get_option_fields();

		if ( ! is_array( $all_fields ) || ! isset( $all_fields[ $setting ] ) || ! isset( $all_fields[ $setting ]['default'] ) ) {
			return '';
		}

		$default = $all_fields[ $setting ]['default'];

		if ( ! $subset || ! is_array( $default ) ) {
			return $default;
		}

		if ( ! isset( $default[ $subset ] ) ) {
			return '';
		}

		return $default[ $subset ];

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
