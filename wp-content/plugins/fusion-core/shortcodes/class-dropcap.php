<?php
class FusionSC_Dropcap {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_dropcap-shortcode', array( $this, 'attr' ) );
		add_shortcode( 'dropcap', array( $this, 'render' ) );

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
				'class'			=> '',
				'id'			=> '',
				'boxed'			=> '',
				'boxed_radius'	=> '',
				'color'			=> strtolower( $smof_data['dropcap_color'] ), 
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$html = sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'dropcap-shortcode' ), do_shortcode( $content ) );

		return $html;

	}

	function attr() {

		$attr['class'] = 'fusion-dropcap dropcap';
		$attr['style'] = '';
		
		if( self::$args['boxed'] == 'yes' ) {
			$attr['class'] .= ' dropcap-boxed';
			
			if( self::$args['boxed_radius'] || 
				self::$args['boxed_radius'] === '0'
			) {
				if( self::$args['boxed_radius'] == 'round' ) {
					self::$args['boxed_radius'] = '50%';
				}

				$attr['style'] = sprintf( 'border-radius:%s;', self::$args['boxed_radius'] );
			}			

			$attr['style'] .= sprintf( 'background-color:%s;', self::$args['color'] );	
		} else {
			$attr['style'] .= sprintf( 'color:%s;', self::$args['color'] );
		}
		
		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}		

		return $attr;

	}

}

new FusionSC_Dropcap();
