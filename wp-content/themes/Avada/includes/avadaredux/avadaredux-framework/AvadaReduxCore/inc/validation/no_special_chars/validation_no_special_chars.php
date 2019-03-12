<?php

	if ( ! class_exists( 'AvadaRedux_Validation_no_special_chars' ) ) {
		class AvadaRedux_Validation_no_special_chars {

			/**
			 * Field Constructor.
			 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
			 *
			 * @since AvadaReduxFramework 1.0.0
			 */
			function __construct( $parent, $field, $value, $current ) {

				$this->parent       = $parent;
				$this->field        = $field;
				$this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : __( 'You must not enter any special characters in this field, all special characters have been removed.', 'avadaredux-framework' );
				$this->value        = $value;
				$this->current      = $current;

				$this->validate();
			} //function

			/**
			 * Field Render Function.
			 * Takes the vars and validates them
			 *
			 * @since AvadaReduxFramework 1.0.0
			 */
			function validate() {

				if ( ! preg_match( '/[^a-zA-Z0-9_ -]/s', $this->value ) == 0 ) {
					$this->warning = $this->field;
				}

				$this->value = preg_replace( '/[^a-zA-Z0-9_ -]/s', '', $this->value );
			} //function
		} //class
	}
