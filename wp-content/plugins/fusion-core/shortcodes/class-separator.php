<?php
class FusionSC_Separator {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_separator-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_separator-shortcode-icon-wrapper', array( $this, 'icon_wrapper_attr' ) );
		add_filter( 'fusion_attr_separator-shortcode-icon', array( $this, 'icon_attr' ) );

		add_shortcode( 'separator', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'				=> '',
				'id'				=> '',
				'alignment'			=> 'center',
				'bottom_margin'		=> '',
				'border_size'		=> intval( $smof_data['separator_border_size'] ) . 'px',
				'icon'				=> '',
				'icon_circle'		=> $smof_data['separator_circle'],
				'icon_circle_color'	=> '',
				'sep_color'			=> $smof_data['sep_color'],
				'style_type'		=> 'none',
				'top_margin'		=> '',
				'width'				=> '',
				'bottom'			=> '',	//deprecated
				'color'				=> '',	//deprecated
				'style' 			=> '',	//deprecated
				'top'				=> '',	//deprecated
			), $args
		);

		$defaults['border_size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_size'], 'px' );
		$defaults['width'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['width'], 'px' );
		$defaults['top_margin'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['top_margin'], 'px' );
		$defaults['bottom_margin'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['bottom_margin'], 'px' );

		if ( $defaults['icon_circle'] === '0' ) {
			$defaults['icon_circle'] = 'no';
		}

		if( $defaults['style'] ) {
			$defaults['style_type'] = $defaults['style'];
		}

		extract( $defaults );

		self::$args = $defaults;

		if( $bottom ) {
			self::$args['bottom_margin'] = $bottom;
		}

		if( $color ) {
			self::$args['sep_color'] = $color;
		}

		if( $top ) {
			self::$args['top_margin'] = $top;

			if( ! $bottom && $defaults['style'] != 'none' ) {
				self::$args['bottom_margin'] = $top;
			}
		}

		if ( $icon &&
			 $style_type != 'none'
		) {
			$icon_insert = sprintf( '<span %s><i %s></i></span>', FusionCore_Plugin::attributes( 'separator-shortcode-icon-wrapper' ), FusionCore_Plugin::attributes( 'separator-shortcode-icon' ) );
		} else {
			$icon_insert = '';
		}

		$html = sprintf( '<div %s></div><div %s>%s</div>', FusionCore_Plugin::attributes( 'fusion-sep-clear' ), FusionCore_Plugin::attributes( 'separator-shortcode' ), $icon_insert );

		if ( self::$args['alignment'] == 'right' ) {
			$html .= sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fusion-sep-clear' ) );
		}

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-separator' );
		$attr['style'] = '';


		if( ! self::$args['width'] || self::$args['width'] == '100%' ) {
			$attr['class'] .= ' fusion-full-width-sep';
		}

		$styles = explode( '|', self::$args['style_type'] );

		if( ! in_array( 'none', $styles ) &&
			! in_array( 'single', $styles ) &&
			! in_array( 'double', $styles ) &&
			! in_array( 'shadow', $styles )
		) {
			$styles[] .= 'single';
		}

		foreach ( $styles as $style ) {
			$attr['class'] .= ' sep-' . $style;
		}

		if( self::$args['sep_color'] ) {
			if ( self::$args['style_type'] == 'shadow' ) {

				$shadow = sprintf( 'background:radial-gradient(ellipse at 50%% -50%% , %s 0px, rgba(255, 255, 255, 0) 80%%) repeat scroll 0 0 rgba(0, 0, 0, 0);', self::$args['sep_color'] );

				$attr['style'] = $shadow;
				$attr['style'] .= str_replace( 'radial-gradient', '-webkit-radial-gradient', $shadow );
				$attr['style'] .= str_replace( 'radial-gradient', '-moz-radial-gradient', $shadow );
				$attr['style'] .= str_replace( 'radial-gradient', '-o-radial-gradient', $shadow );
			} else {

				$attr['style'] = sprintf( 'border-color:%s;', self::$args['sep_color'] );
			}
		}


		if( in_array( 'single', $styles ) ) {
			$attr['style'] .= sprintf( 'border-top-width:%s;', self::$args['border_size'] );
		}

		if( in_array( 'double', $styles ) ) {
			$attr['style'] .= sprintf( 'border-top-width:%s;border-bottom-width:%s;', self::$args['border_size'], self::$args['border_size'] );
		}

		if ( self::$args['alignment'] == 'center' ) {
			$attr['style'] .= 'margin-left: auto;margin-right: auto;';
		} elseif ( self::$args['alignment'] == 'right' ) {
			$attr['style'] .= 'float:right;';
			$attr['class'] .=  ' fusion-clearfix';
		}

		$attr['style'] .= sprintf( 'margin-top:%s;', self::$args['top_margin'] );

		if( self::$args['bottom_margin'] ) {
			$attr['style'] .= sprintf( 'margin-bottom:%s;', self::$args['bottom_margin'] );
		}

		if( self::$args['width'] ) {
			$attr['style'] .= sprintf( 'width:100%%;max-width:%s;', self::$args['width'] );
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function icon_wrapper_attr() {

		$attr = array();

		$attr['class'] = 'icon-wrapper';

		$circle_color = self::$args['sep_color'];
		if ( self::$args['icon_circle'] == 'no' ) {
			$circle_color = 'transparent';
		}

		$attr['style'] = sprintf( 'border-color:%s;', $circle_color );

		if ( self::$args['icon_circle_color'] ) {
			$attr['style'] .= sprintf( 'background-color:%s;', self::$args['icon_circle_color'] );
		}

		return $attr;

	}

	function icon_attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fa %s', FusionCore_Plugin::font_awesome_name_handler( self::$args['icon'] ) );

		$attr['style'] = sprintf( 'color:%s;', self::$args['sep_color'] );

		return $attr;

	}

}

new FusionSC_Separator();
