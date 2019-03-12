<?php

class Avada_Options {

	private static $instance = null;

	public $section_names = array();
	public $sections      = array();

	private static $fields;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new Avada_Options();
		}
		return self::$instance;
	}

	/**
	 * The class constructor
	 */
	public function __construct() {
		Avada::$is_updating = ( $_GET && isset( $_GET['avada_update'] ) && '1' == $_GET['avada_update'] ) ? true : false;


		/**
		 * The array of sections by ID.
		 * These are used in the filenames AND the function-names.
		 */
		$this->section_names = array(
			'layout',
			'menu',
			'responsive',
			'colors',
			'header',
			'logo',
			'page_title_bar',
			'sliding_bar',
			'footer',
			'sidebars',
			'background',
			'typography',
			'shortcode_styling',
			'blog',
			'portfolio',
			'social_media',
			'slideshows',
			'elastic_slider',
			'lightbox',
			'contact',
			'search_page',
			'extra',
			'advanced',
			'bbpress',
			'woocommerce',
			'events_calendar',
			'custom_css',
		);

		/**
		 * Include the section files
		 */
		$this->include_files();

		/**
		 * Set the $sections
		 */
		$this->set_sections();

		/**
		 * Set the $fields
		 */
		$this->set_fields();

	}

	/**
	 * Include required files
	 */
	public function include_files() {

		foreach ( $this->section_names as $section ) {
			include_once get_template_directory() . '/includes/options/' . $section . '.php';
		}

	}

	/**
	 * Set the sections.
	 */
	public function set_sections() {

		$sections = array();
		foreach ( $this->section_names as $section ) {
			$sections = call_user_func( 'avada_options_section_' . $section, $sections );
		}

		$this->sections = $sections;

	}

	/**
	 * Get a flat array of our fields.
	 * This will contain simply the field IDs and nothing more than that.
	 * We'll be using this to check if a setting belongs to Avada or not.
	 *
	 * @return  array
	 */
	public function fields_array() {
		/**
		 * Get the options object
		 */
		$avada_new_options = Avada()->options;
		$fields = array();
		/**
		 * start parsing sections
		 */
		foreach ( $avada_new_options->sections as $section ) {
			/**
			 * Make sure we have defined fields for this section.
			 * No need to proceed otherwise
			 */
			if ( isset( $section['fields'] ) ) {
				/**
				 * start parsing the fields inside the section
				 */
				foreach ( $section['fields'] as $field ) {
					/**
					 * Make sure a field-type has been defined
					 */
					if ( isset( $field['type'] ) ) {
						/**
						 * For normal fields, we'll just add the field ID to our array.
						 */
						if ( ! in_array( $field['type'], array( 'sub-section', 'accordion' ) ) ) {
							if ( isset( $field['id'] ) ) {
								$fields[] = $field['id'];
							}
						}
						/**
						 * For sub-sections & accordions we'll have to parse the sub-fields and add them to our array
						 */
						else {
							if ( isset( $field['fields'] ) ) {
								foreach ( $field['fields'] as $sub_field ) {
									if ( isset( $sub_field['id'] ) ) {
										$fields[] = $sub_field['id'];
									}
								}
							}
						}
					}
				}
			}
		}
		return $fields;
	}

	public function set_fields() {
		/**
		 * Start parsing the sections
		 */
		foreach ( $this->sections as $section ) {
			if ( ! isset( $section['fields'] ) ) {
				continue;
			}
			// Start parsing the fields.
			foreach ( $section['fields'] as $field ) {
				if ( ! isset( $field['id'] ) ) {
					continue;
				}
				// This is a sub-section or an accordion
				if ( isset( $field['type'] ) && in_array( $field['type'], array( 'sub-section', 'accordion' ) ) ) {
					// Start parsing the fields inside the sub-section/accordion.
					foreach ( $field['fields'] as $sub_field ) {
						if ( ! isset( $sub_field['id'] ) ) {
							continue;
						}
						self::$fields[ $sub_field['id'] ] = $sub_field;
					}
				} else {
					/**
					 * This is not a section, continue processing.
					 */
					self::$fields[ $field['id'] ] = $field;
				}
			}
		}
	}

	public static function get_option_fields() {
		return self::$fields;
	}
}
