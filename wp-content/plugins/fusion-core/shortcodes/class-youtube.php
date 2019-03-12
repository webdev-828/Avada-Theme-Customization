<?php
class FusionSC_Youtube {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_youtube-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_youtube-shortcode-video-sc', array( $this, 'video_sc_attr' ) );

		add_shortcode('youtube', array( $this, 'render' ) );

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
				'autoplay'		=> "false",
				'center'		=> 'no',
				'height' 		=> 360,
				'id'			=> '',
				'width'			=> 600

			), $args
		);

		$defaults['height'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['height'], '' );
		$defaults['width'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['width'], '' );

		extract( $defaults );

		self::$args = $defaults ;

		if( is_ssl() ) {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}

		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Trident/7.0;' ) !== false && strpos( $_SERVER['HTTP_USER_AGENT'], 'rv:11.0' ) !== false ) {
			$protocol = 'https';
		}

		// Make sure only the video ID is passed to the iFrame
		$pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
		preg_match( $pattern, $id, $matches );
		if ( isset( $matches[1] ) ) {
			$id = $matches[1];
		}

		$html = sprintf( '<div %s><div %s><iframe title="YouTube video player" src="%s://www.youtube.com/embed/%s?wmode=transparent&autoplay=0%s" width="%s" height="%s" allowfullscreen></iframe></div></div>',
						 FusionCore_Plugin::attributes( 'youtube-shortcode' ), FusionCore_Plugin::attributes( 'youtube-shortcode-video-sc' ), $protocol, $id, $api_params, $width, $height );

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-video fusion-youtube';

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

new FusionSC_Youtube();