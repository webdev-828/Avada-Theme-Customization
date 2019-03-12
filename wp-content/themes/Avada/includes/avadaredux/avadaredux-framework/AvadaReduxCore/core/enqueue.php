<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'avadareduxCoreEnqueue' ) ) {
		class avadareduxCoreEnqueue {
			public $parent = null;

			private $min = '';
			private $timestamp = '';

			public function __construct( $parent ) {
				$this->parent = $parent;

				AvadaRedux_Functions::$_parent = $parent;
			}

			public function init() {
				$this->min = AvadaRedux_Functions::isMin();

				$this->timestamp = AvadaReduxFramework::$_version;
				if ( $this->parent->args['dev_mode'] ) {
					$this->timestamp .= '.' . time();
				}

				$this->register_styles();
				$this->register_scripts();

				add_thickbox();

				$this->enqueue_fields();

				add_filter("avadaredux/{$this->parent->args['opt_name']}/localize", array('AvadaRedux_Helpers', 'localize'));

				$this->set_localized_data();

				/**
				 * action 'avadaredux-enqueue-{opt_name}'
				 *
				 * @deprecated
				 *
				 * @param  object $this AvadaReduxFramework
				 */
				do_action( "avadaredux-enqueue-{$this->parent->args['opt_name']}", $this->parent ); // REMOVE

				/**
				 * action 'avadaredux/page/{opt_name}/enqueue'
				 */
				do_action( "avadaredux/page/{$this->parent->args['opt_name']}/enqueue" );
			}

			private function register_styles() {

				//*****************************************************************
				// AvadaRedux Admin CSS
				//*****************************************************************
				wp_enqueue_style(
					'avadaredux-admin-css',
					AvadaReduxFramework::$_url . 'assets/css/avadaredux-admin.css',
					array(),
					$this->timestamp,
					'all'
				);

				//*****************************************************************
				// AvadaRedux Fields CSS
				//*****************************************************************
				if ( ! $this->parent->args['dev_mode'] ) {
					wp_enqueue_style(
						'avadaredux-fields-css',
						AvadaReduxFramework::$_url . 'assets/css/avadaredux-fields.css',
						array(),
						$this->timestamp,
						'all'
					);
				}

				//*****************************************************************
				// Select2 CSS
				//*****************************************************************
				AvadaRedux_CDN::register_style(
					'select2-css',
					AvadaReduxFramework::$_url . 'assets/css/vendor/select2.css',
					array(),
					'3.5.2',//$this->timestamp,
					'all'
				);

				//*****************************************************************
				// Spectrum CSS
				//*****************************************************************
				$css_file = 'avadaredux-spectrum.min.css';
				if ($this->parent->args['dev_mode']) {
					$css_file = 'avadaredux-spectrum.css';
				}

				wp_register_style(
					'avadaredux-spectrum-css',
					AvadaReduxFramework::$_url . 'assets/css/vendor/spectrum/' . $css_file,
					array(),
					'1.3.3',
					'all'
				);

				//*****************************************************************
				// Elusive Icon CSS
				//*****************************************************************
				wp_enqueue_style(
					'avadaredux-elusive-icon',
					AvadaReduxFramework::$_url . 'assets/css/vendor/elusive-icons/elusive-icons.css',
					array(),
					$this->timestamp,
					'all'
				);

				//*****************************************************************
				// QTip CSS
				//*****************************************************************
				$css_file = 'jquery.qtip.min.css';
				if ($this->parent->args['dev_mode']) {
					$css_file = 'jquery.qtip.css';
				}

				wp_enqueue_style(
					'qtip-css',
					AvadaReduxFramework::$_url . 'assets/css/vendor/qtip/' . $css_file,
					array(),
					'2.2.0',
					'all'
				);

				//*****************************************************************
				// JQuery UI CSS
				//*****************************************************************
				wp_enqueue_style(
					'jquery-ui-css',
					apply_filters( "avadaredux/page/{$this->parent->args['opt_name']}/enqueue/jquery-ui-css", AvadaReduxFramework::$_url . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
					array(),
					$this->timestamp,
					'all'
				);

				//*****************************************************************
				// Iris CSS
				//*****************************************************************
				wp_enqueue_style( 'wp-color-picker' );

				if ( $this->parent->args['dev_mode'] ) {

					//*****************************************************************
					// Color Picker CSS
					//*****************************************************************
					wp_register_style(
						'avadaredux-color-picker-css',
						AvadaReduxFramework::$_url . 'assets/css/color-picker/color-picker.css',
						array( 'wp-color-picker' ),
						$this->timestamp,
						'all'
					);

					//*****************************************************************
					// Media CSS
					//*****************************************************************
					wp_enqueue_style(
						'avadaredux-field-media-css',
						AvadaReduxFramework::$_url . 'assets/css/media/media.css',
						array(),
						time(),
						'all'
					);
				}

				//*****************************************************************
				// RTL CSS
				//*****************************************************************
				if ( is_rtl() ) {
					wp_enqueue_style(
						'avadaredux-rtl-css',
						AvadaReduxFramework::$_url . 'assets/css/rtl.css',
						array( 'avadaredux-admin-css' ),
						$this->timestamp,
						'all'
					);
				}

			}

			private function register_scripts() {
				//*****************************************************************
				// JQuery / JQuery UI JS
				//*****************************************************************
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-dialog' );

				//*****************************************************************
				// Select2 Sortable JS
				//*****************************************************************
				wp_register_script(
					'avadaredux-select2-sortable-js',
					AvadaReduxFramework::$_url . 'assets/js/vendor/avadaredux.select2.sortable' . $this->min . '.js',
					array( 'jquery' ),
					$this->timestamp,
					true
				);

				//*****************************************************************
				// Select2 JS
				//*****************************************************************

				// JWp6 plugin giving us problems.  They need to update.
				if (  wp_script_is ( 'jquerySelect2' )) {
					wp_deregister_script( 'jquerySelect2' );
					wp_dequeue_script('jquerySelect2');
					wp_dequeue_style('jquerySelect2Style');
				}


				AvadaRedux_CDN::register_script(
					'select2-js',
					AvadaReduxFramework::$_url . 'assets/js/vendor/select2.min.js',
					array( 'jquery', 'avadaredux-select2-sortable-js' ),
					'3.5.2',
					true
				);

				//*****************************************************************
				// QTip JS
				//*****************************************************************
				$js_file = 'jquery.qtip.min.js';
				if ($this->parent->args['dev_mode']) {
					$js_file = 'jquery.qtip.js';
				}

				wp_enqueue_script(
					'qtip-js',
					AvadaReduxFramework::$_url . 'assets/js/vendor/qtip/' . $js_file,
					array( 'jquery' ),
					'2.2.0',
					true
				);

				//*****************************************************************
				// Spectrum JS
				//*****************************************************************
				$js_file = 'avadaredux-spectrum.min.js';
				if ($this->parent->args['dev_mode']) {
					$js_file = 'avadaredux-spectrum.js';
				}

				wp_register_script(
					'avadaredux-spectrum-js',
					AvadaReduxFramework::$_url . 'assets/js/vendor/spectrum/' . $js_file,
					array( 'jquery' ),
					'1.3.3',
					true
				);

				$depArray = array( 'jquery' );

				//*****************************************************************
				// Vendor JS
				//*****************************************************************
				if ( $this->parent->args['dev_mode'] ) {
					wp_register_script(
						'avadaredux-vendor',
						AvadaReduxFramework::$_url . 'assets/js/vendor.min.js',
						array( 'jquery' ),
						$this->timestamp,
						true
					);

					array_push( $depArray, 'avadaredux-vendor' );
				}

				//*****************************************************************
				// AvadaRedux JS
				//*****************************************************************
				wp_register_script(
					'avadaredux-js',
					AvadaReduxFramework::$_url . 'assets/js/avadaredux' . $this->min . '.js',
					$depArray,
					$this->timestamp,
					true
				);

				wp_enqueue_script(
					'webfontloader',
					'https://ajax.googleapis.com/ajax/libs/webfont/1.5.0/webfont.js',
					array( 'jquery' ),
					'1.5.0',
					true
				);
			}

			public function _enqueue_field($field) {
				// TODO AFTER GROUP WORKS - Revert IF below
				// if( isset( $field['type'] ) && $field['type'] != 'callback' ) {
				if ( isset( $field['type'] ) && $field['type'] != 'callback' ) {

					$field_class = 'AvadaReduxFramework_' . $field['type'];

					/**
					 * Field class file
					 * filter 'avadaredux/{opt_name}/field/class/{field.type}
					 *
					 * @param       string        field class file path
					 * @param array $field        field config data
					 */
					$class_file = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/field/class/{$field['type']}", AvadaReduxFramework::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
					if ( $class_file ) {
						if ( ! class_exists( $field_class ) ) {
							if ( file_exists( $class_file ) ) {
								require_once $class_file;
							}
						}

						if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {

							if ( ! isset( $this->parent->options[ $field['id'] ] ) ) {
								$this->parent->options[ $field['id'] ] = "";
							}
							$theField = new $field_class( $field, $this->parent->options[ $field['id'] ], $this->parent );

							// Move dev_mode check to a new if/then block
							if ( ! wp_script_is( 'avadaredux-field-' . $field['type'] . '-js', 'enqueued' ) && class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
								$theField->enqueue();
							}

							if ( method_exists( $field_class, 'localize' ) ) {
								$params = $theField->localize( $field );
								if ( ! isset( $this->parent->localize_data[ $field['type'] ] ) ) {
									$this->parent->localize_data[ $field['type'] ] = array();
								}
								$this->parent->localize_data[ $field['type'] ][ $field['id'] ] = $theField->localize( $field );
							}

							unset( $theField );
						}
					}
				}
			}

			private function enqueue_fields() {
				$data = array();
				foreach ( $this->parent->sections as $section ) {
					if ( isset( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							$data[$section['id']][$field['id']] = true;
							$this->_enqueue_field( $field );
						}
					}
				}
				$this->parent->localize_data['optionSections'] = $data;
			}

			public function get_warnings_and_errors_array() {
				// Construct the errors array.
				if ( isset( $this->parent->transients['last_save_mode'] ) && ! empty( $this->parent->transients['notices']['errors'] ) ) {
					$theTotal  = 0;
					$theErrors = array();

					foreach ( $this->parent->transients['notices']['errors'] as $error ) {
						$theErrors[ $error['section_id'] ]['errors'][] = $error;

						if ( ! isset( $theErrors[ $error['section_id'] ]['total'] ) ) {
							$theErrors[ $error['section_id'] ]['total'] = 0;
						}

						$theErrors[ $error['section_id'] ]['total'] ++;
						$theTotal ++;
					}

					$this->parent->localize_data['errors'] = array( 'total' => $theTotal, 'errors' => $theErrors );
					unset( $this->parent->transients['notices']['errors'] );
				}

				// Construct the warnings array.
				if ( isset( $this->parent->transients['last_save_mode'] ) && ! empty( $this->parent->transients['notices']['warnings'] ) ) {
					$theTotal    = 0;
					$theWarnings = array();

					foreach ( $this->parent->transients['notices']['warnings'] as $warning ) {
						$theWarnings[ $warning['section_id'] ]['warnings'][] = $warning;

						if ( ! isset( $theWarnings[ $warning['section_id'] ]['total'] ) ) {
							$theWarnings[ $warning['section_id'] ]['total'] = 0;
						}

						$theWarnings[ $warning['section_id'] ]['total'] ++;
						$theTotal ++;
					}

					unset( $this->parent->transients['notices']['warnings'] );
					$this->parent->localize_data['warnings'] = array(
						'total'    => $theTotal,
						'warnings' => $theWarnings
					);
				}

				if ( empty( $this->parent->transients['notices'] ) ) {
					unset( $this->parent->transients['notices'] );
				}
			}

			private function set_localized_data() {
				if (!empty($this->parent->args['last_tab'])) {
					$this->parent->localize_data['last_tab']       = $this->parent->args['last_tab'];
				}

				$this->parent->localize_data['required']       = $this->parent->required;
				$this->parent->localize_data['fonts']          = $this->parent->fonts;
				$this->parent->localize_data['required_child'] = $this->parent->required_child;
				$this->parent->localize_data['fields']         = $this->parent->fields;

				if ( isset( $this->parent->font_groups['google'] ) ) {
					$this->parent->localize_data['googlefonts'] = $this->parent->font_groups['google'];
				}

				if ( isset( $this->parent->font_groups['std'] ) ) {
					$this->parent->localize_data['stdfonts'] = $this->parent->font_groups['std'];
				}

				if ( isset( $this->parent->font_groups['customfonts'] ) ) {
					$this->parent->localize_data['customfonts'] = $this->parent->font_groups['customfonts'];
				}

				$this->parent->localize_data['folds'] = $this->parent->folds;

				// Make sure the children are all hidden properly.
				foreach ( $this->parent->fields as $key => $value ) {
					if ( in_array( $key, $this->parent->fieldsHidden ) ) {
						foreach ( $value as $k => $v ) {
							if ( ! in_array( $k, $this->parent->fieldsHidden ) ) {
								$this->parent->fieldsHidden[] = $k;
								$this->parent->folds[ $k ]    = "hide";
							}
						}
					}
				}



				$this->parent->localize_data['fieldsHidden'] = $this->parent->fieldsHidden;
				$this->parent->localize_data['options']      = $this->parent->options;
				$this->parent->localize_data['defaults']     = $this->parent->options_defaults;

				/**
				 * Save pending string
				 * filter 'avadaredux/{opt_name}/localize/save_pending
				 *
				 * @param       string        save_pending string
				 */
				$save_pending = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/localize/save_pending", __( 'You have changes that are not saved. Would you like to save them now?', 'avadaredux-framework' ) );

				/**
				 * Reset all string
				 * filter 'avadaredux/{opt_name}/localize/reset
				 *
				 * @param       string        reset all string
				 */
				$reset_all = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/localize/reset", __( 'Are you sure? Resetting will lose all custom values.', 'avadaredux-framework' ) );

				/**
				 * Reset section string
				 * filter 'avadaredux/{opt_name}/localize/reset_section
				 *
				 * @param       string        reset section string
				 */
				$reset_section = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/localize/reset_section", __( 'Are you sure? Resetting will lose all custom values in this section.', 'avadaredux-framework' ) );

				/**
				 * Preset confirm string
				 * filter 'avadaredux/{opt_name}/localize/preset
				 *
				 * @param       string        preset confirm string
				 */
				$preset_confirm = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/localize/preset", __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'avadaredux-framework' ) );
				global $pagenow;
				$this->parent->localize_data['args'] = array(
					'save_pending'          => $save_pending,
					'reset_confirm'         => $reset_all,
					'reset_section_confirm' => $reset_section,
					'preset_confirm'        => $preset_confirm,
					'please_wait'           => __( 'Please Wait', 'avadaredux-framework' ),
					'opt_name'              => $this->parent->args['opt_name'],
					'slug'                  => $this->parent->args['page_slug'],
					'hints'                 => $this->parent->args['hints'],
					'disable_save_warn'     => $this->parent->args['disable_save_warn'],
					'class'                 => $this->parent->args['class'],
					'ajax_save'             => $this->parent->args['ajax_save'],
					'menu_search'           => $pagenow . '?page=' . $this->parent->args['page_slug'] . "&tab="
				);

				$this->parent->localize_data['ajax'] = array(
					'console' => __( 'There was an error saving. Here is the result of your action:', 'avadaredux-framework' ),
					'alert'   => __( 'There was a problem with your action. Please try again or reload the page.', 'avadaredux-framework' ),
				);

				$this->parent->localize_data = apply_filters( "avadaredux/{$this->parent->args['opt_name']}/localize", $this->parent->localize_data );

				$this->get_warnings_and_errors_array();

				wp_localize_script(
					'avadaredux-js',
					'avadaredux',
					$this->parent->localize_data
				);

				wp_enqueue_script( 'avadaredux-js' ); // Enque the JS now

			}
		}
	}
