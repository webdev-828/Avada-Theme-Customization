<?php

if ( ! function_exists( 'avada_avadaredux_validate_dimension' ) ) {
	function avada_avadaredux_validate_dimension( $field, $value, $existing_value ) {

		$return = array();

		$value = trim( strtolower( $value ) );
		if ( in_array( $value, array( 'auto', 'initial', 'inherit' ) ) ) {
			return array( 'value' => $value );
		}
		$warning = false;

		if ( 'round' == $value ) {
			$value = '50%';
		}

		if ( '' == $existing_value || null == $existing_value || false == $existing_value ) {
			$existing_value = Avada()->settings->get( $field['id'] );
		}

		if ( '' == $value || null == $value || false == $value ) {
			$value = $existing_value;
		}

		// remove spaces from the value
		$value = trim( str_replace( ' ', '', $value ) );
		// Get the numeric value
		$value_numeric = Avada_Sanitize::number( $value );
		if ( empty( $value_numeric ) ) {
			$value_numeric = '0';
		}
		// Get the units.
		$value_unit = str_replace( $value_numeric, '', $value );
		$value_unit = strtolower( $value_unit );
		if ( empty( $value_unit ) ) {
			$warning = true;
		}

		// An array of valid CSS units
		$valid_units = array( 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'vh', 'vw', 'vmin', 'vmax', );

		// If we can't find a valid CSS unit in the value
		// show a warning message and fallback to using pixels.
		if ( ! in_array( $value_unit, $valid_units ) ) {
			$warning = true;
		}

		if ( $warning ) {
			$replaced_units_message = esc_html__( 'We could not find a valid unit for this field, falling back to "%1$s". Saved value "%2$s" and not "%3$s".', 'Avada' );
			$units_message          = esc_html__( 'No units were entered, falling back to using pixels. Saved value "%2$s" and not "%3$s".' );
			if ( empty( $value_unit ) ) {
				$message    = $units_message;
				$value_unit = 'px';
				$unit_found = true;
			} else {
				$message    = $replaced_units_message;
				$unit_found = false;
				foreach ( $valid_units as $valid_unit ) {
					if ( $unit_found ) {
						continue;
					}
					if ( false !== strrpos( $value_unit, $valid_unit ) ) {
						$value_unit = $valid_unit;
						$unit_found = true;
					}
				}
			}
			if ( ! $unit_found ) {
				$value_unit = 'px';
			}
			$field['msg']      = sprintf( $message, $value_unit, $value_numeric . $value_unit, $value );
			$return['warning'] = $field;
		}

		$return['value'] = $value_numeric . $value_unit;

		return $return;

	}
}

if ( ! function_exists( 'avada_avadaredux_validate_font_size' ) ) {
	function avada_avadaredux_validate_font_size( $field, $value, $existing_value ) {
		$warning = false;
		$value = trim( strtolower( $value ) );

		$return = array();

		if ( '' == $existing_value || null == $existing_value || false == $existing_value ) {
			$existing_value = Avada()->settings->get( $field['id'] );
		}

		if ( '' == $value || null == $value || false == $value ) {
			$value = $existing_value;
		}

		// remove spaces from the value
		$value = trim( str_replace( ' ', '', $value ) );
		// Get the numeric value
		$value_numeric = Avada_Sanitize::number( $value );
		if ( empty( $value_numeric ) ) {
			$value_numeric = '0';
		}
		// Get the units.
		$value_unit = str_replace( $value_numeric, '', $value );
		$value_unit = strtolower( $value_unit );
		if ( empty( $value_unit ) ) {
			$warning = true;
		}

		// An array of valid CSS units
		$valid_units = array( 'rem', 'em', 'px' );

		// If we can't find a valid CSS unit in the value
		// show a warning message and fallback to using pixels.
		if ( ! in_array( $value_unit, $valid_units ) ) {
			$warning = true;
		}

		if ( $warning ) {
			$replaced_units_message = esc_html__( 'We could not find a valid unit for this field, falling back to "%1$s". Valid units are %4$s. Saved value "%2$s" and not "%3$s.".', 'Avada' );
			$units_message          = esc_html__( 'No units were entered, falling back to using pixels. Saved value "%2$s" and not "%3$s".' );
			if ( empty( $value_unit ) ) {
				$message    = $units_message;
				$value_unit = 'px';
				$unit_found = true;
			} else {
				$message    = $replaced_units_message;
				$unit_found = false;
				foreach ( $valid_units as $valid_unit ) {
					if ( $unit_found ) {
						continue;
					}
					if ( false !== strrpos( $value_unit, $valid_unit ) ) {
						$value_unit = $valid_unit;
						$unit_found = true;
					}
				}
			}
			if ( ! $unit_found ) {
				$value_unit = 'px';
			}
			$imploded_valid_units = implode( ', ', $valid_units );
			$field['msg']         = sprintf( $message, $value_unit, $value_numeric . $value_unit, $value, $imploded_valid_units );
			$return['warning']    = $field;
		}

		$return['value'] = $value_numeric . $value_unit;

		return $return;

	}
}

if ( ! function_exists( 'avada_avadaredux_validate_typography' ) ) {
	function avada_avadaredux_validate_typography( $field, $value, $existing_value ) {

		$return = array();

		$limit_units_fields = array(
			'font-size',
			'line-height',
			'letter-spacing',
		);
		if ( is_array( $value ) ) {
			// An array of valid CSS units
			$valid_units = array( 'px', 'rem', 'em' );
			$warning     = array();
			$message     = array();

			$imploded_valid_units = implode( ', ', $valid_units );

			foreach ( $value as $key => $subvalue ) {
				$replaced_units_message = '';
				$units_message          = '';
				$subvalue_numeric = '';
				$subvalue_unit = '';
				$warning[ $key ] = false;
				if ( in_array( $key, $limit_units_fields ) ) {
					if ( '' == $existing_value[ $key ] || null == $existing_value[ $key ] || false == $existing_value[ $key ] ) {
						$existing_value[ $key ] = Avada()->settings->get( $field['id'], $key );
					}
					if ( '' == $subvalue || null == $subvalue || false == $subvalue ) {
						$subvalue = $existing_value[ $key ];
					}
					// remove spaces from the value
					$subvalue = trim( str_replace( ' ', '', $subvalue ) );
					// Get the numeric value
					$subvalue_numeric = Avada_Sanitize::number( $subvalue );
					if ( empty( $subvalue_numeric ) ) {
						$subvalue_numeric = '0';
					}
					// Get the units.
					$subvalue_unit = str_replace( $subvalue_numeric, '', $subvalue );
					$subvalue_unit = strtolower( $subvalue_unit );
					if ( empty( $subvalue_unit ) ) {
						if ( '0' == $subvalue_numeric ) {
							if ( 'font-size' == $key ) {
								$warning[ $key ] = true;
							}
						} else if ( 'line-height' != $key ) {
							$warning[ $key ] = true;
						}
					}

					// If we can't find a valid CSS unit in the value
					// show a warning message and fallback to using pixels.
					if ( ! in_array( $subvalue_unit, $valid_units ) ) {
						if ( ! ( 'line-height' == $key && empty( $subvalue_unit ) ) && ! ( '0' == $subvalue && 'font-size' != $key ) ) {
							$warning[ $key ] = true;
						}
					}

					if ( true === $warning[ $key ] ) {
						if ( ! isset( $field['msg'] ) ) {
							$field['msg'] = '';
						}
						$replaced_units_message = esc_html__( 'We could not find a valid unit for %1$s, falling back to "%2$s". Valid units are %3$s. Saved value "%4$s" and not "%5$s.".', 'Avada' );
						$units_message          = esc_html__( 'No units were entered for %1s, falling back to using pixels. Saved value "%4$s" and not "%5$s".' );
						if ( empty( $subvalue_unit ) ) {
							$message[]     = sprintf( $units_message, $key, $subvalue_unit, $imploded_valid_units, $subvalue_numeric . $subvalue_unit, $subvalue );
							$subvalue_unit = 'px';
							$unit_found    = true;
						} else {
							$unit_found           = false;
							foreach ( $valid_units as $valid_unit ) {
								if ( $unit_found ) {
									continue;
								}
								if ( false !== strrpos( $subvalue_unit, $valid_unit ) ) {
									$subvalue_unit = $valid_unit;
									$unit_found = true;
								}
							}
							if ( ! $unit_found ) {
								$subvalue_unit = 'px';
							}
							$message[] = sprintf( $replaced_units_message, $key, $subvalue_unit, $imploded_valid_units, $subvalue_numeric . $subvalue_unit, $subvalue );
						}
						if ( ! $unit_found ) {
							$subvalue_unit = 'px';
						}
					}
					$value[ $key ] = $subvalue_numeric . $subvalue_unit;
				}
			}
		}
		if ( ! empty( $message ) ) {
			$field['msg']      = implode( ' ', $message );
			$return['warning'] = $field;
		}

		$return['value'] = $value;

		return $return;

	}
}

if ( ! function_exists( 'avada_avadaredux_validate_dimensions' ) ) {
	function avada_avadaredux_validate_dimensions( $field, $value, $existing_value ) {

		$warning       = array();
		$error_message = array();

		$return = array();

		// An array of valid CSS units
		$valid_units = array( 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'vh', 'vw', 'vmin', 'vmax', );

		if ( ! is_array( $value ) ) {
			return array( 'value' => $value );
		}
		foreach ( $value as $key => $subvalue ) {
			$warning[ $key ] = false;

			if ( 'round' == $subvalue ) {
				$value = '50%';
			}

			if ( ! isset( $existing_value[ $key ] ) || '' == $existing_value[ $key ] || null == $existing_value[ $key ] || false == $existing_value[ $key ] ) {
				$existing_value = Avada()->settings->get( $field['id'], $key );
			}

			if ( '' == $subvalue || null == $subvalue || false == $subvalue ) {
				if ( isset( $existing_value[ $key ] ) ) {
					$subvalue = $existing_value[ $key ];
				}
			}

			// remove spaces from the value
			$subvalue = trim( str_replace( ' ', '', $subvalue ) );
			// Get the numeric value
			$subvalue_numeric = Avada_Sanitize::number( $subvalue );
			if ( empty( $subvalue_numeric ) ) {
				$subvalue_numeric = '0';
			}
			// Get the units.
			$subvalue_unit = str_replace( $subvalue_numeric, '', $subvalue );
			$subvalue_unit = strtolower( $subvalue_unit );
			if ( empty( $subvalue_unit ) ) {
				$warning[ $key ] = true;
			}

			// If we can't find a valid CSS unit in the value
			// show a warning message and fallback to using pixels.
			if ( ! in_array( $subvalue_unit, $valid_units ) ) {
				$warning[ $key ] = true;
			}

			if ( $warning[ $key ] ) {
				$replaced_units_message = esc_html__( 'We could not find a valid unit for this field, falling back to "%1$s". Saved value "%2$s" and not "%3$s".', 'Avada' );
				$units_message          = esc_html__( 'No units were entered, falling back to using pixels. Saved value "%2$s" and not "%3$s".' );
				if ( empty( $subvalue_unit ) ) {
					$message       = $units_message;
					$subvalue_unit = 'px';
					$subunit_found = true;
				} else {
					$message       = $replaced_units_message;
					$subunit_found = false;
					foreach ( $valid_units as $valid_unit ) {
						if ( $subunit_found ) {
							continue;
						}
						if ( false !== strrpos( $subvalue_unit, $valid_unit ) ) {
							$subvalue_unit = $valid_unit;
							$subunit_found = true;
						}
					}
				}

				if ( ! $subunit_found ) {
					$subvalue_unit = 'px';
				}
				$error_message[]   = sprintf( $message, $subvalue_unit, $subvalue_numeric . $subvalue_unit, $subvalue );

			}

			$return['value'][ $key ] = $subvalue_numeric . $subvalue_unit;

		}
		if ( ! empty( $error_message ) ) {
			$field['msg']      = implode( ' ', $error_message );
			$return['warning'] = $field;
		}

		return $return;

	}
}

if ( ! function_exists( 'avada_avadaredux_validate_color_rgba' ) ) {
	function avada_avadaredux_validate_color_rgba( $field, $value, $existing_value ) {

		$return = array();

		$error = false;
		$sanitized_value = Avada_Sanitize::color( $value );
		$return['value'] = $sanitized_value;

		if ( $value != $sanitized_value ) {
			$error = true;
			$field['msg'] = sprintf(
				esc_html__( 'Sanitized value and saved as %1s instead of %2s.', 'Avada' ),
				'<code>' . $sanitized_value . '</code>',
				'<code>' . $value . '</code>'
			);
			$return['warning'] = $field;
		}
		return $return;
	}
}

if ( ! function_exists( 'avada_avadaredux_validate_color_hex' ) ) {
	function avada_avadaredux_validate_color_hex( $field, $value, $existing_value ) {

		$return = array();

		$error = false;
		$sanitized_value = Avada_Sanitize::color( $value );
		if ( false !== strpos( $sanitized_value, 'rgba' ) ) {
			$sanitized_value = Avada_Color::rgba2hex( $sanitized_value, false );
		}
		$return['value'] = $sanitized_value;

		if ( $value != $sanitized_value ) {
			$error = true;
			$field['msg'] = sprintf(
				esc_html__( 'Sanitized value and saved as %1s instead of %2s.', 'Avada' ),
				'<code>' . $sanitized_value . '</code>',
				'<code>' . $value . '</code>'
			);
			$return['warning'] = $field;
		}
		return $return;
	}
}

if ( ! function_exists( 'avada_avadaredux_validate_custom_fonts' ) ) {
	function avada_avadaredux_validate_custom_fonts( $field, $value, $existing_value ) {
		$return = array();

		if ( isset( $value['name'] ) ) {

			foreach ( $value['name'] as $name_key => $name_value ) {
				$value['name'][ $name_key ] = trim( $name_value );
				$value['name'][ $name_key ] = str_replace( ' ', '-', $value['name'][ $name_key ] );
			}

		}

		return array(
			'value' => $value,
		);
	}
}
