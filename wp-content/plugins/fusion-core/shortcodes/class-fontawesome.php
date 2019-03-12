<?php
class FusionSC_FontAwesome {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_fontawesome-shortcode', array( $this, 'attr' ) );
		add_shortcode( 'fontawesome', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {

		$defaults =	FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'					=> '',
				'id'					=> '',
				'alignment'	  			=> '',
				'circle'				=> 'yes',
				'circlecolor' 			=> '',
				'circlebordercolor' 	=> '',
				'flip'					=> '',
				'icon'  				=> '',
				'iconcolor' 			=> '',
				'rotate'				=> '',
				'size' 					=> 'medium',
				'spin'					=> 'no',
				'animation_type' 		=> '',
				'animation_direction' 	=> 'down',
				'animation_speed' 		=> '0.1',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		extract( $defaults );

		// Dertmine line-height and margin from font size
		$defaults['font_size'] = FusionCore_Plugin::validate_shortcode_attr_value( self::convert_deprecated_sizes( $defaults['size'] ), '' );
		$defaults['circle_yes_font_size'] = $defaults['font_size'] * 0.88;
		$defaults['line_height'] = $defaults['font_size']* 1.76;
		$defaults['icon_margin'] = $defaults['font_size'] * 0.5;
		$defaults['icon_margin_position'] = ( is_rtl() ) ? 'left' : 'right';

		self::$args = $defaults;

		$html = sprintf( '<i %s>%s</i>', FusionCore_Plugin::attributes( 'fontawesome-shortcode' ), do_shortcode( $content ) );

		if( $alignment ) {
			$html = sprintf( '<div class="align%s">%s</div>', $alignment, $html );
		}

		return $html;

	}

	function attr() {

		$attr['class'] = sprintf( 'fa fontawesome-icon %s circle-%s', FusionCore_Plugin::font_awesome_name_handler( self::$args['icon'] ), self::$args['circle'] );
		$attr['style'] = '';

		if( self::$args['circle'] == 'yes' ) {

			if( self::$args['circlebordercolor'] ) {
				$attr['style'] .= sprintf( 'border-color:%s;', self::$args['circlebordercolor'] );
			}

			if( self::$args['circlecolor'] ) {
				$attr['style'] .= sprintf( 'background-color:%s;', self::$args['circlecolor'] );
			}

			$attr['style'] .= sprintf( 'font-size:%spx;', self::$args['circle_yes_font_size'] );

			$attr['style'] .= sprintf( 'line-height:%spx;height:%spx;width:%spx;', self::$args['line_height'], self::$args['line_height'], self::$args['line_height'] );

		} else {
			$attr['style'] .= sprintf( 'font-size:%spx;', self::$args['font_size'] );
		}

		if(  'center' == self::$args['alignment'] ) {
			$attr['style'] .= 'margin-left:0;margin-right:0;';
		} else {
			$attr['style'] .= sprintf( 'margin-%s:%spx;', self::$args['icon_margin_position'], self::$args['icon_margin'] );
		}

		if( self::$args['iconcolor'] ) {
			$attr['style'] .= sprintf( 'color:%s;', self::$args['iconcolor'] );
		}

		if( self::$args['rotate'] ) {
			$attr['class'] .= ' fa-rotate-' . self::$args['rotate'];
		}

		if( self::$args['spin'] == 'yes' ) {
			$attr['class'] .= ' fa-spin';
		}

		if ( self::$args['animation_type'] ) {
			$animations = FusionCore_Plugin::animations( array(
				'type'	  	=> self::$args['animation_type'],
				'direction' => self::$args['animation_direction'],
				'speed'	 	=> self::$args['animation_speed'],
				'offset' 	=> self::$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function convert_deprecated_sizes( $size ) {
		switch ( $size ) {
			case 'small':
				$size = '10px';
				break;
			case 'medium':
				$size = '18px';
				break;
			case 'large':
				$size = '40px';
				break;
			default:
				break;
		}

		return $size;
	}

}

new FusionSC_FontAwesome();