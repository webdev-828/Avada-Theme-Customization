<?php
class FusionSC_Vimeo {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_vimeo-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_vimeo-shortcode-video-sc', array( $this, 'video_sc_attr' ) );

		add_shortcode('vimeo', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '' ) {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class' 		=> '',
				'api_params'	=> '',
				'autoplay' 		=> 'no',
				'center'		=> 'no',
				'height' 		=> 360,
				'id' 			=> '',
				'width' 		=> 600
			), $args
		);

		$defaults['height'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['height'], '' );
		$defaults['width']  = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['width'], '' );

		extract( $defaults );

		self::$args = $defaults;

		if( is_ssl() ) {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}

		// Make sure only the video ID is passed to the iFrame
		$pattern = '/(?:https?:\/\/)?(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/';
		preg_match( $pattern, $id, $matches );
		if ( isset( $matches[3] ) ) {
			$id = $matches[3];
		}

		$html = sprintf( '<div %s><div %s><iframe src="%s://player.vimeo.com/video/%s?autoplay=0%s" width="%s" height="%s" allowfullscreen></iframe></div></div>',
						 FusionCore_Plugin::attributes( 'vimeo-shortcode' ), FusionCore_Plugin::attributes( 'vimeo-shortcode-video-sc' ), $protocol, $id, $api_params, $width, $height );


		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-video fusion-vimeo';

		if( self::$args['center'] == 'yes' ) {
			$attr['class'] .= ' center-video';
		} else {
			$attr['style'] = sprintf( 'max-width:%spx;max-height:%spx;', self::$args['width'], self::$args['height'] );
		}

		if( self::$args['autoplay'] == 'true' ||
			self::$args['autoplay'] == 'yes'
		) {
			$attr['data-autoplay'] = 1;
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		return $attr;

	}

	function video_sc_attr() {

		$attr = array();

		$attr['class'] = 'video-shortcode';

		if( self::$args['center'] == 'yes' ) {
			$attr['style'] = sprintf( 'max-width:%spx;max-height:%spx;', self::$args['width'], self::$args['height'] );
		}

		return $attr;

	}

}

new FusionSC_Vimeo();