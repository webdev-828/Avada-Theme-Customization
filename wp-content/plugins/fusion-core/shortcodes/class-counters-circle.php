<?php
class FusionSC_CountersCircle {

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_counters-circle-shortcode', array( $this, 'parent_attr' ) );
		add_shortcode( 'counters_circle', array( $this, 'render_parent' ) );

		add_filter( 'fusion_attr_counter-circle-shortcode', array( $this, 'child_attr' ) );
		add_filter( 'fusion_attr_counter-circle-wrapper-shortcode', array( $this, 'child_wrapper_attr' ) );
		add_shortcode( 'counter_circle', array( $this, 'render_child' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_parent( $args, $content = '' ) {

		$defaults =	shortcode_atts(
			array(
				'class'				=> '',
				'id'				=> '',
				'animation_offset'	=> Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		extract( $defaults );

		self::$parent_args = $defaults;

		$html = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'counters-circle-shortcode' ), do_shortcode( $content ) );

		return $html;

	}

	function parent_attr() {

		$attr['class'] = 'fusion-counters-circle counters-circle';

		if( self::$parent_args['class'] ) {
			$attr['class'] .= ' ' . self::$parent_args['class'];
		}

		if( self::$parent_args['id'] ) {
			$attr['id'] = self::$parent_args['id'];
		}

		return $attr;

	}

	/**
	 * Render the child shortcode
	 *
	 * @param  array  $args	 Shortcode paramters
	 * @param  string $content  Content between shortcode
	 * @return string		   HTML output
	 */
	function render_child( $args, $content = '' ) {
		global $smof_data;

		$defaults =	FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			=> '',
				'id'			=> '',
				'countdown'		=> 'no',
				'filledcolor' 	=> strtolower( $smof_data['counter_filled_color'] ),
				'unfilledcolor' => strtolower( $smof_data['counter_unfilled_color'] ),
				'scales'		=> 'no',
				'size' 			=> '220',
				'speed' 		=> '1500',
				'value' 		=> '1',
			), $args
		);

		$defaults['size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['size'], '' );

		extract( $defaults );

		self::$child_args = $defaults;

		if( $scales == 'yes' ) {
			self::$child_args['scales'] = true;
		} else {
			self::$child_args['scales'] = false;
		}

		if( $countdown == 'yes' ) {
			self::$child_args['countdown'] = true;
		} else {
			self::$child_args['countdown'] = false;
		}

		$output = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'counter-circle-shortcode' ), do_shortcode( $content ) );

		$html = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'counter-circle-wrapper-shortcode' ), $output );

		return $html;

	}

	function child_attr() {

		$attr['class'] = 'fusion-counter-circle counter-circle counter-circle-content';

		if( self::$child_args['class'] ) {
			$attr['class'] .= ' ' . self::$child_args['class'];
		}

		if( self::$child_args['id'] ) {
			$attr['id'] = self::$child_args['id'];
		}

		$multiplicator = self::$child_args['size'] / 220;
		$stroke_size = 11 * $multiplicator;
		$font_size = 50 * $multiplicator;

		$attr['data-percent'] = self::$child_args['value'];
		$attr['data-countdown'] = self::$child_args['countdown'];
		$attr['data-filledcolor'] = self::$child_args['filledcolor'];
		$attr['data-unfilledcolor'] = self::$child_args['unfilledcolor'];
		$attr['data-scale'] = self::$child_args['scales'];
		$attr['data-size'] = self::$child_args['size'];
		$attr['data-speed'] = self::$child_args['speed'];
		$attr['data-strokesize'] = $stroke_size;

		$attr['style'] = sprintf( 'font-size:%spx;height:%spx;width:%spx;line-height:%spx;', $font_size, self::$child_args['size'], self::$child_args['size'], self::$child_args['size'] );

		return $attr;

	}

	function child_wrapper_attr() {

		$attr['class'] = 'counter-circle-wrapper';

		$attr['style'] = sprintf( 'height:%spx;width:%spx;line-height:%spx;', self::$child_args['size'], self::$child_args['size'], self::$child_args['size'] );

		$attr['data-originalsize'] = self::$child_args['size'];

		if ( self::$parent_args['animation_offset'] ) {
			$animations = FusionCore_Plugin::animations( array( 'offset' => self::$parent_args['animation_offset'] ) );

			$attr = array_merge( $attr, $animations );
		}

		return $attr;

	}

}

new FusionSC_CountersCircle();
