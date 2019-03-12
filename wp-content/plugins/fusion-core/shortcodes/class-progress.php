<?php
class FusionSC_Progressbar {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_progressbar-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_progressbar-shortcode-bar', array( $this, 'bar_attr' ) );
		add_filter( 'fusion_attr_progressbar-shortcode-content', array( $this, 'content_attr' ) );
		add_filter( 'fusion_attr_progressbar-shortcode-span', array( $this, 'span_attr' ) );

		add_shortcode('progress', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults =	FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'					=> '',
				'id'					=> '',
				'animated_stripes'		=> 'no',
				'filledcolor' 			=> '',
				'height'				=> Avada()->settings->get( 'progressbar_height' ),
				'percentage'			=> '70',
				'show_percentage'		=> 'yes',
				'striped'				=> 'no',
				'textcolor'				=> '',
				'text_position'			=> Avada()->settings->get( 'progressbar_text_position' ),				
				'unfilledcolor' 		=> '',
				'unit' 					=> '',
				'filledbordercolor' 	=> $smof_data['progressbar_filled_border_color'],
				'filledbordersize' 		=> intval( $smof_data['progressbar_filled_border_size'] ) . 'px',
			), $args
		);

		$defaults['filledbordersize'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['filledbordersize'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		if( ! $filledcolor ) {
			self::$args['filledcolor'] = $smof_data['progressbar_filled_color'];
		}

		if( ! $textcolor ) {
			self::$args['textcolor'] = $smof_data['progressbar_text_color'];
		}

		if( ! $unfilledcolor ) {
			self::$args['unfilledcolor'] = $smof_data['progressbar_unfilled_color'];
		}
		
		$text = sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'fusion-progressbar-text' ), $content );
		
		$value = '';
		if ( $show_percentage == 'yes' ) {
			$value = sprintf( '<span %s>%s%s</span>', FusionCore_Plugin::attributes( 'fusion-progressbar-value' ), $percentage, $unit );
		}
		
		$text_wrapper = sprintf( '<span %s>%s %s</span>', FusionCore_Plugin::attributes( 'progressbar-shortcode-span' ), $text, $value );
		
		$bar = sprintf( '<div %s><div %s></div></div>', FusionCore_Plugin::attributes( 'progressbar-shortcode-bar' ), FusionCore_Plugin::attributes( 'progressbar-shortcode-content' ) );
		
		if ( $text_position == 'above_bar' ) {
			$html = sprintf( '<div %s>%s %s</div>', FusionCore_Plugin::attributes( 'progressbar-shortcode' ), $text_wrapper, $bar );
		} else {
			$html = sprintf( '<div %s>%s %s</div>', FusionCore_Plugin::attributes( 'progressbar-shortcode' ), $bar, $text_wrapper );
		}
		
		

		return $html;

	}
	
	function attr() {
	
		$attr = array();
	
		$attr['class'] = 'fusion-progressbar';
		
		if ( self::$args['text_position'] == 'above_bar' ) {
			$attr['class'] .= ' fusion-progressbar-text-above-bar';
		} else if ( self::$args['text_position'] == 'below_bar' ) {	
			$attr['class'] .= ' fusion-progressbar-text-below-bar';
		} else {
			$attr['class'] .= ' fusion-progressbar-text-on-bar';
		}		
		
		return $attr;
		
	}

	function bar_attr() {

		$attr = array();

		$attr['style'] = sprintf( 'background-color:%s;', self::$args['unfilledcolor'] );

		$attr['class'] = 'fusion-progressbar-bar progress-bar';

		if( self::$args['height'] ) {
			$attr['style'] .= sprintf( 'height:%s;', self::$args['height'] );
		}

		if( self::$args['striped'] == "yes" ) {
			$attr['class'] .= ' progress-striped';
		}

		if( self::$args['animated_stripes'] == "yes" ) {
			$attr['class'] .= ' active';
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function content_attr() {

		$attr = array();

		$attr['class'] = 'progress progress-bar-content';

		$attr['style'] = sprintf( 'width:%s%%;background-color:%s;', 0, self::$args['filledcolor'] );

		if( self::$args['filledbordersize'] && self::$args['filledbordercolor'] ) {
			$attr['style'] .= sprintf( 'border: %s solid %s;', self::$args['filledbordersize'], self::$args['filledbordercolor'] );
		}

		$attr['role'] = 'progressbar';
		$attr['aria-valuemin'] = '0';
		$attr['aria-valuemax'] = '100';


		$attr['aria-valuenow'] = self::$args['percentage'];

		return $attr;

	}

	function span_attr() {

		$attr = array();

		$attr['class'] = 'progress-title';

		$attr['style'] = sprintf( 'color:%s;', self::$args['textcolor'] );

		return $attr;

	}

}

new FusionSC_Progressbar();
