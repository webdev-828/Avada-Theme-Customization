<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AvadaReduxFramework_spacing' ) ) {
	class AvadaReduxFramework_spacing {

		protected $parent;
		protected $field;
		protected $value;

		/**
		 * Field Constructor.
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
		 * @since AvadaReduxFramework 1.0.0
		 */
		function __construct( $field = array(), $value = '', $parent ) {
			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since AvadaReduxFramework 1.0.0
		 */
		function render() {
			/*
			 * So, in_array() wasn't doing it's job for checking a passed array for a proper value.
			 * It's wonky.  It only wants to check the keys against our array of acceptable values, and not the key's
			 * value.  So we'll use this instead.  Fortunately, a single no array value can be passed and it won't
			 * take a dump.
			 */

			// No errors please
			// Set field values
			$defaults = array(
				'top'            => true,
				'bottom'         => true,
				'all'            => false,
				'left'           => true,
				'right'          => true,
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			// Set default values
			$defaults = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			$value = array(
				'top'    => isset( $this->value['top'] ) ? Avada_Sanitize::size( $this->value['top'] ) : '',
				'right'  => isset( $this->value['right'] ) ? Avada_Sanitize::size( $this->value['right'] ) : '',
				'bottom' => isset( $this->value['bottom'] ) ? Avada_Sanitize::size( $this->value['bottom'] ) : '',
				'left'   => isset( $this->value['left'] ) ? Avada_Sanitize::size( $this->value['left'] ) : '',
			);

			$this->value = $value;

			$defaults = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			if ( isset( $this->field['all'] ) && $this->field['all'] == true ) {
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el el-fullscreen icon-large"></i></span><input type="text" class="avadaredux-spacing-all avadaredux-spacing-input mini ' . $this->field['class'] . '" placeholder="' . __( 'All', 'avadaredux-framework' ) . '" rel="' . $this->field['id'] . '-all" value="' . $this->value['top'] . '"></div>';
			}

			if ( $this->field['top'] === true ) {
				echo '<input type="hidden" class="avadaredux-spacing-value" id="' . $this->field['id'] . '-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[top]" value="' . $this->value['top'] . '">';
			}

			if ( $this->field['right'] === true ) {
				echo '<input type="hidden" class="avadaredux-spacing-value" id="' . $this->field['id'] . '-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[right]" value="' . $this->value['right'] . '">';
			}

			if ( $this->field['bottom'] === true ) {
				echo '<input type="hidden" class="avadaredux-spacing-value" id="' . $this->field['id'] . '-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[bottom]" value="' . $this->value['bottom'] . '">';
			}

			if ( $this->field['left'] === true ) {
				echo '<input type="hidden" class="avadaredux-spacing-value" id="' . $this->field['id'] . '-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[left]" value="' . $this->value['left'] . '">';
			}

			if ( ! isset( $this->field['all'] ) || $this->field['all'] !== true ) {
				/**
				 * Top
				 * */
				if ( $this->field['top'] === true ) {
					echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el el-arrow-up icon-large"></i></span><input type="text" class="avadaredux-spacing-top avadaredux-spacing-input mini ' . $this->field['class'] . '" placeholder="' . __( 'Top', 'avadaredux-framework' ) . '" rel="' . $this->field['id'] . '-top" value="' . $this->value['top'] . '"></div>';
				}

				/**
				 * Right
				 * */
				if ( $this->field['right'] === true ) {
					echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el el-arrow-right icon-large"></i></span><input type="text" class="avadaredux-spacing-right avadaredux-spacing-input mini ' . $this->field['class'] . '" placeholder="' . __( 'Right', 'avadaredux-framework' ) . '" rel="' . $this->field['id'] . '-right" value="' . $this->value['right'] . '"></div>';
				}

				/**
				 * Bottom
				 * */
				if ( $this->field['bottom'] === true ) {
					echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el el-arrow-down icon-large"></i></span><input type="text" class="avadaredux-spacing-bottom avadaredux-spacing-input mini ' . $this->field['class'] . '" placeholder="' . __( 'Bottom', 'avadaredux-framework' ) . '" rel="' . $this->field['id'] . '-bottom" value="' . $this->value['bottom'] . '"></div>';
				}

				/**
				 * Left
				 * */
				if ( $this->field['left'] === true ) {
					echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el el-arrow-left icon-large"></i></span><input type="text" class="avadaredux-spacing-left avadaredux-spacing-input mini ' . $this->field['class'] . '" placeholder="' . __( 'Left', 'avadaredux-framework' ) . '" rel="' . $this->field['id'] . '-left" value="' . $this->value['left'] . '"></div>';
				}
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since AvadaReduxFramework 1.0.0
		 */
		function enqueue() {

			wp_enqueue_script(
				'avada-avadaredux-field-spacing-js',
				trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/custom-fields/spacing/field_spacing.js',
				array( 'jquery', 'avadaredux-js' ),
				time(),
				true
			);
			wp_enqueue_style(
				'avada-avadaredux-field-spacing-css',
				trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/custom-fields/spacing/field_spacing.css',
				array(),
				time(),
				'all'
			);
		} //function

		public function output() {

			if ( ! isset( $this->field['mode'] ) ) {
				$this->field['mode'] = "padding";
			}

			if ( isset( $this->field['mode'] ) && ! in_array( $this->field['mode'], array(
						'padding',
						'absolute',
						'margin'
					) )
			) {
				$this->field['mode'] = "";
			}

			$mode  = ( $this->field['mode'] != "absolute" ) ? $this->field['mode'] : "";
			$units = isset( $this->value['units'] ) ? $this->value['units'] : "";
			$style = '';

			if ( ! empty( $mode ) && is_array( $this->value ) ) {
				foreach ( $this->value as $key => $value ) {
					if ( $key == "units" ) {
						continue;
					}

					// Strip off any alpha for is_numeric test - kp
					$num_no_alpha = preg_replace('/[^\d.-]/', '', $value);

					// Output if it's a numeric entry
					if ( isset( $value ) && is_numeric( $num_no_alpha ) ) {
						$style .= $key . ':' . $value . ';';
					}

				}
			} else {
				$this->value['top']    = isset( $this->value['top'] ) ? $this->value['top'] : 0;
				$this->value['bottom'] = isset( $this->value['bottom'] ) ? $this->value['bottom'] : 0;
				$this->value['left']   = isset( $this->value['left'] ) ? $this->value['left'] : 0;
				$this->value['right']  = isset( $this->value['right'] ) ? $this->value['right'] : 0;

				$cleanValue = array(
					'top'    => isset( $this->value[ $mode . '-top' ] ) ? filter_var( $this->value[ $mode . '-top' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['top'], FILTER_SANITIZE_NUMBER_INT ),
					'right'  => isset( $this->value[ $mode . '-right' ] ) ? filter_var( $this->value[ $mode . '-right' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['right'], FILTER_SANITIZE_NUMBER_INT ),
					'bottom' => isset( $this->value[ $mode . '-bottom' ] ) ? filter_var( $this->value[ $mode . '-bottom' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['bottom'], FILTER_SANITIZE_NUMBER_INT ),
					'left'   => isset( $this->value[ $mode . '-left' ] ) ? filter_var( $this->value[ $mode . '-left' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['left'], FILTER_SANITIZE_NUMBER_INT )
				);

				if ( isset( $this->field['all'] ) && true == $this->field['all'] ) {
					$style .= $mode . 'top:' . $cleanValue['top'] . $units . ';';
					$style .= $mode . 'bottom:' . $cleanValue['top'] . $units . ';';
					$style .= $mode . 'right:' . $cleanValue['top'] . $units . ';';
					$style .= $mode . 'left:' . $cleanValue['top'] . $units . ';';
				} else {
					if ( true == $this->field['top'] ) {
						$style .= $mode . 'top:' . $cleanValue['top'] . $units . ';';
					}

					if ( true == $this->field['bottom'] ) {
						$style .= $mode . 'bottom:' . $cleanValue['bottom'] . $units . ';';
					}

					if ( true == $this->field['left'] ) {
						$style .= $mode . 'left:' . $cleanValue['left'] . $units . ';';
					}

					if ( true == $this->field['right'] ) {
						$style .= $mode . 'right:' . $cleanValue['right'] . $units . ';';
					}
				}
			}

			if ( ! empty( $style ) ) {

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					$keys = implode( ",", $this->field['output'] );
					$this->parent->outputCSS .= $keys . "{" . $style . '}';
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$keys = implode( ",", $this->field['compiler'] );
					$this->parent->compilerCSS .= $keys . "{" . $style . '}';
				}
			}
		}
	}
}
