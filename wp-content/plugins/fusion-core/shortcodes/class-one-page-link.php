<?php
class FusionSC_OnePageTextLink {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_one-page-text-link-shortcode', array( $this, 'attr' ) );

		add_shortcode( 'one_page_text_link', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   	=> '',
				'id'				 	=> '',
				'link'					=> '',
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$html = sprintf( '<a %s>%s</a>', 
						 FusionCore_Plugin::attributes( 'one-page-text-link-shortcode' ), do_shortcode( $content ) );

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-one-page-text-link';

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		$attr['href'] = self::$args['link'];

		return $attr;

	}
	
}

new FusionSC_OnePageTextLink();
