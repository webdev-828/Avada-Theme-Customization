<?php
class FusionSC_FusionText {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_shortcode('fusion_text', array( $this, 'render' ) );

		add_filter( 'fusion_text_content', 'shortcode_unautop' );
		add_filter( 'fusion_text_content', 'do_shortcode' );
	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		return apply_filters( 'fusion_text_content', wpautop( $content, false ) );
	}

}

new FusionSC_FusionText();