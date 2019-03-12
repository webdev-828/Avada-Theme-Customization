<?php
class FusionSC_Soundcloud {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_soundcloud-shortcode', array( $this, 'attr' ) );
		add_shortcode('soundcloud', array( $this, 'render' ) );

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
				'class' 		=> 'fusion-soundcloud',
				'id' 			=> '',
				'auto_play'		=> 'no',
				'color' 		=> 'ff7700',
				'comments' 		=> 'yes',
				'height' 		=> '',
				'layout'		=> 'classic',
				'show_related'	=> 'no',
				'show_reposts'	=> 'no',
				'show_user'		=> 'yes',
				'url' 			=> '',
				'width' 		=> '100%'
			), $args
		);

		$defaults['width'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['width'], 'px' );
		$defaults['height'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['height'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		if( $auto_play  == 'yes' ) {
			$autoplay = 'true';
		} else {
			$autoplay = 'false';
		}

		if( $comments == 'yes' ) {
			$comments = 'true';
		} else {
			$comments = 'false';
		}

		if( $layout == 'visual' ) {
			$visual = 'true';

			if ( ! $height ) {
				$height = '450';
			}
		} else {
			$visual = 'false';

			if ( ! $height ) {
				$height = '166';
			}
		}

		$height = (int) $height;

		if( $show_related  == 'yes' ) {
			$show_related = 'false';
		} else {
			$show_related = 'true';
		}

		if( $show_reposts  == 'yes' ) {
			$show_reposts = 'true';
		} else {
			$show_reposts = 'false';
		}

		if( $show_user  == 'yes' ) {
			$show_user = 'true';
		} else {
			$show_user = 'false';
		}

		if( $color ) {
			$color = str_replace( '#', '', $color );
		}

		$html = sprintf( '<div %s><iframe scrolling="no" frameborder="no" width="%s" height="%s" src="https://w.soundcloud.com/player/?url=%s&amp;auto_play=%s&amp;hide_related=%s&amp;show_comments=%s&amp;show_user=%s&amp;show_reposts=%s&amp;visual=%s&amp;color=%s"></iframe></div>',
						  FusionCore_Plugin::attributes( 'soundcloud-shortcode' ), $width, $height, $url, $autoplay, $show_related, $comments, $show_user, $show_reposts, $visual, $color );

		return $html;

	}

	function attr() {

		$attr = array();

		if( self::$args['class'] ) {
			$attr['class'] = self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

}

new FusionSC_Soundcloud();