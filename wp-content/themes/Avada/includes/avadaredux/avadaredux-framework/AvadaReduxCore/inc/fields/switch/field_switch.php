<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AvadaReduxFramework_switch' ) ) {
	class AvadaReduxFramework_switch {

		/**
		 * Field Constructor.
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
		 * @since AvadaReduxFramework 0.0.4
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
		 * @since AvadaReduxFramework 0.0.4
		 */
		function render() {

			$cb_enabled = $cb_disabled = ''; //no errors, please
			//
			//Get selected
			if ( (int) $this->value == 1 ) {
				$cb_enabled = ' selected';
			} else {
				$cb_disabled = ' selected';
			}

			//Label ON
			$this->field['on'] = isset( $this->field['on'] ) ? $this->field['on'] : __( 'On', 'avadaredux-framework' );

			//Label OFF
			$this->field['off'] = isset( $this->field['off'] ) ? $this->field['off'] : __( 'Off', 'avadaredux-framework' );

			echo '<div class="switch-options">';
			echo '<label class="cb-enable' . $cb_enabled . '" data-id="' . $this->field['id'] . '"><span>' . $this->field['on'] . '</span></label>';
			echo '<label class="cb-disable' . $cb_disabled . '" data-id="' . $this->field['id'] . '"><span>' . $this->field['off'] . '</span></label>';
			echo '<input type="hidden" class="checkbox checkbox-input ' . $this->field['class'] . '" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . $this->value . '" />';
			echo '</div>';
		} //function

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since AvadaReduxFramework 0.0.4
		 */
		function enqueue() {
			wp_enqueue_script(
				'avadaredux-field-switch-js',
				AvadaReduxFramework::$_url . 'inc/fields/switch/field_switch' . AvadaRedux_Functions::isMin() . '.js',
				array( 'jquery', 'avadaredux-js' ),
				time(),
				true
			);

			if ($this->parent->args['dev_mode']) {
				wp_enqueue_style(
					'avadaredux-field-switch-css',
					AvadaReduxFramework::$_url . 'inc/fields/switch/field_switch.css',
					array(),
					time(),
					'all'
				);
			}
		}
	}
}
