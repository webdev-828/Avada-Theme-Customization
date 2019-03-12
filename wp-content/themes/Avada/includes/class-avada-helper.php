<?php

class Avada_Helper {

	/**
	 * Return the value of an echo.
	 * example: Avada_Helper::get_echo( 'function' );
	 */
	public static function get_echo( $function, $args = '' ) {

		// Early exit if function does not exist
		if ( ! function_exists( $function ) ) {
			return;
		}

		ob_start();
		$function( $args );
		$get_echo = ob_get_clean();
		return $get_echo;

	}

	public static function slider_name( $name ) {

		$type = '';

		switch( $name ) {
			case 'layer':
				$type = 'slider';
				break;
			case 'flex':
				$type = 'wooslider';
				break;
			case 'rev':
				$type = 'revslider';
				break;
			case 'elastic':
				$type = 'elasticslider';
				break;
		}

		return $type;

	}

	public static function get_slider_type( $post_id ) {
		return get_post_meta( $post_id, 'pyre_slider_type', true );
	}

	public static function percent_to_pixels( $percent, $max_width = 2000 ) {
		return intval( ( intval( $percent ) * $max_width ) / 100 );
	}

	public static function ems_to_pixels( $ems, $font_size = 14 ) {
		return intval( Avada_Sanitize::number( $ems ) * $font_size );
	}

	public static function merge_to_pixels( $values = array() ) {
		$final_value = 0;
		foreach ( $values as $value ) {
			if ( false !== strpos( $value, '%' ) ) {
				$value = self::percent_to_pixels( $value, 1600 );
			} elseif ( false !== strpos( $value, 'em' ) ) {
				$value = self::ems_to_pixels( $value );
			} else {
				$value = intval( $value );
			}
			$final_value = $final_value + $value;
		}
		return $final_value;
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
