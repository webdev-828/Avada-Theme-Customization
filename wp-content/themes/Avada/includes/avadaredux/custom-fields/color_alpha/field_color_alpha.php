<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'AvadaReduxFramework_color_alpha' ) ) {

	/**
	 * Main AvadaReduxFramework_color class
	 *
	 * @since       1.0.0
	 */
	class AvadaReduxFramework_color_alpha {

		protected $parent;
		protected $field;
		protected $value;

		/**
		 * Field Constructor.
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
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
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function render() {

			$input  = '<input ';
			$input .= 'data-id="' . $this->field['id'] . '" ';
			$input .= 'name="' . $this->field['name'] . $this->field['name_suffix'] . '" ';
			$input .= 'id="' . $this->field['id'] . '-color" ';
			$input .= 'class="color-picker avadaredux-color avadaredux-color-init ' . $this->field['class'] . '"  ';
			$input .= 'type="text" value="' . $this->value . '" ';
			$input .= 'data-oldcolor="" ';
			$input .= 'data-alpha="true" ';
			$input .= 'data-default-color="' . ( isset( $this->field['default'] ) ? $this->field['default'] : '' ) . '" ';
			$input .= '/>';
			echo $input;
			echo '<input type="hidden" class="avadaredux-saved-color" id="' . $this->field['id'] . '-saved-color' . '" value="">';

			if ( ! isset( $this->field['transparent'] ) || $this->field['transparent'] !== false ) {

				$tChecked = "";

				if ( $this->value == "transparent" ) {
					$tChecked = ' checked="checked"';
				}

				echo '<label for="' . $this->field['id'] . '-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . $this->field['class'] . '" id="' . $this->field['id'] . '-transparency" data-id="' . $this->field['id'] . '-color" value="1"' . $tChecked . '> ' . __( 'Transparent', 'avadaredux-framework' ) . '</label>';
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function enqueue() {
			if ($this->parent->args['dev_mode']) {
				wp_enqueue_style( 'avadaredux-color-picker-css' );
			}

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'wp-color-picker-alpha',
				trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/custom-fields/color_alpha/wp-color-picker-alpha.js',
				array( 'wp-color-picker' ),
				'1.2'
			);
			wp_enqueue_script(
				'avadaredux-field-color-js',
				trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/custom-fields/color_alpha/field_color_alpha.js',
				array( 'jquery', 'wp-color-picker-alpha', 'avadaredux-js' ),
				time(),
				true
			);
		}

		public function output() {
			$style = '';

			if ( ! empty( $this->value ) ) {
				$mode = ( isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'color' );

				$style .= $mode . ':' . $this->value . ';';

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					$css = AvadaRedux_Functions::parseCSS( $this->field['output'], $style, $this->value );
					$this->parent->outputCSS .= $css;
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$css = AvadaRedux_Functions::parseCSS( $this->field['compiler'], $style, $this->value );
					$this->parent->compilerCSS .= $css;

				}
			}
		}
	}
}
