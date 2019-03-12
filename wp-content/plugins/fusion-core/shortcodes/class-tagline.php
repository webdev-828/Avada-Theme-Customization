<?php
class FusionSC_Tagline {

	private $tagline_box_counter = 1;

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_tagline-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_tagline-shortcode-reading-box', array( $this, 'reading_box_attr' ) );
		add_filter( 'fusion_attr_tagline-shortcode-button', array( $this, 'button_attr' ) );

		add_shortcode('tagline_box', array( $this, 'render' ) );

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
				'class'					=> '',
				'id'				  	=> '',
				'backgroundcolor'		=> strtolower( $smof_data['tagline_bg'] ),
				'border' 				=> '0px',
				'bordercolor' 			=> strtolower( $smof_data['tagline_border_color'] ),
				'button' 				=> '',
				'buttoncolor' 			=> 'default',
				'button_shape'			=> strtolower( $smof_data['button_shape'] ),
				'button_size'			=> strtolower( $smof_data['button_size'] ),
				'button_type'			=> strtolower( $smof_data['button_type'] ),
				'content_alignment'		=> 'left',
				'description'			=> '',
				'highlightposition' 	=> 'left',
				'link'					=> '',
				'linktarget' 			=> '_self',
				'margin_bottom'			=> ( isset( $smof_data['tagline_margin']['bottom'] ) ) ? Avada_Sanitize::size( $smof_data['tagline_margin']['bottom'] ) : '0px',
				'margin_top'			=> ( isset( $smof_data['tagline_margin']['top'] ) ) ? Avada_Sanitize::size( $smof_data['tagline_margin']['top'] ) : '0px',
				'modal'					=> '',
				'shadow' 				=> 'no',
				'shadowopacity' 		=> '0.7',
				'title'					=> '',
				'animation_type' 		=> '',
				'animation_direction' 	=> 'left',
				'animation_speed' 		=> '',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		$defaults['border'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border'], 'px' );

		if( $defaults['modal'] ) {
			$defaults['link'] = '#';
		}

		$defaults['button_type'] = strtolower( $defaults['button_type'] );

		extract( $defaults );

		self::$args = $defaults;
		$additional_content = '';

		$styles = "<style type='text/css'>.reading-box-container-{$this->tagline_box_counter} .element-bottomshadow:before,.reading-box-container-{$this->tagline_box_counter} .element-bottomshadow:after{opacity:{$shadowopacity};}</style>";

		if( ( isset( $link ) && $link ) &&
			( isset( $button ) && $button ) &&
			self::$args['content_alignment'] != 'center'
		) {
			self::$args['button_class'] = ' fusion-desktop-button continue';
			$additional_content = sprintf( '<a %s><span>%s</span></a>', FusionCore_Plugin::attributes( 'tagline-shortcode-button' ), $button );
		}

		if( isset( $title ) && $title ) {
			$additional_content .= sprintf( '<h2>%s</h2>',  $title );
		}

		if( isset( $description ) && $description ) {
			$additional_content .= sprintf( '<div class="reading-box-description">%s</div>',  $description );
		}



		$additional_content .= sprintf( '<div class="reading-box-additional">%s</div>',  do_shortcode( $content ) );

		if( ( isset( $link ) && $link ) && ( isset( $button ) && $button ) ) {
			self::$args['button_class'] = ' fusion-mobile-button';
			$additional_content .= sprintf( '<a %s><span>%s</span></a>', FusionCore_Plugin::attributes( 'tagline-shortcode-button' ), $button );
		}

		$html = sprintf('%s<div %s><div %s>%s</div></div>', $styles, FusionCore_Plugin::attributes( 'tagline-shortcode' ), FusionCore_Plugin::attributes( 'tagline-shortcode-reading-box' ), $additional_content );

		$this->tagline_box_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-reading-box-container reading-box-container-' . $this->tagline_box_counter;

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

		$attr['style'] = '';

		if ( self::$args['margin_top'] ||
			 self::$args['margin_top'] === '0'
		) {
			$attr['style'] .= sprintf( 'margin-top:%s;', Avada_Sanitize::get_value_with_unit( self::$args['margin_top'] ) );
		}

		if ( self::$args['margin_bottom'] ||
			 self::$args['margin_bottom'] === '0'
		) {
			$attr['style'] .= sprintf( 'margin-bottom:%s;', Avada_Sanitize::get_value_with_unit( self::$args['margin_bottom'] ) );
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function reading_box_attr() {
		global $smof_data;

		$attr = array();

		$attr['class'] = 'reading-box';

		if( self::$args['content_alignment'] == 'right' ) {
			$attr['class'] .= ' reading-box-right';
		} elseif( self::$args['content_alignment'] == 'center' ) {
			$attr['class'] .= ' reading-box-center';
		}

		if( self::$args['shadow'] == 'yes' ) {
			$attr['class'] .= ' element-bottomshadow';
		}

		$attr['style'] = sprintf( 'background-color:%s;', self::$args['backgroundcolor'] );
		$attr['style'] .= sprintf( 'border-width:%s;', self::$args['border'] );
		$attr['style'] .= sprintf( 'border-color:%s;', self::$args['bordercolor'] );
		if( self::$args['highlightposition'] != 'none' ) {
			if( str_replace( 'px', '', self::$args['border'] ) > 3  ) {
				$attr['style'] .= sprintf( 'border-%s-width:%s;', self::$args['highlightposition'], self::$args['border'] );
			} else {
				$attr['style'] .= sprintf( 'border-%s-width:3px;', self::$args['highlightposition'] );
			}
			$attr['style'] .= sprintf( 'border-%s-color:%s;', self::$args['highlightposition'], $smof_data['primary_color'] );
		}
		$attr['style'] .= 'border-style:solid;';

		return $attr;
	}

	function button_attr() {

		$attr = array();

		$button_color = ( 'default' == self::$args['buttoncolor'] ) ? 'fusion-button-default' : 'button-' . self::$args['buttoncolor'];

		$attr['class'] = sprintf( 'button fusion-button %s button-%s fusion-button-%s button-%s button-%s %s', $button_color,
								  self::$args['button_shape'], self::$args['button_size'], self::$args['button_size'], self::$args['button_type'], self::$args['button_class'] );

		if( self::$args['content_alignment'] == 'right' ) {
			$attr['class'] .= ' continue-left';
		} elseif( self::$args['content_alignment'] == 'center' ) {
			$attr['class'] .= ' continue-center';
		} else {
			$attr['class'] .= ' continue-right';
		}

		if( self::$args['button_type'] == 'flat' ) {
			$attr['style'] = '-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;';
		}

		$attr['href'] = self::$args['link'];
		$attr['target'] = self::$args['linktarget'];

		if( self::$args['modal'] ) {
			$attr['data-toggle'] = 'modal';
			$attr['data-target'] = '.' . self::$args['modal'];
		}

		return $attr;

	}

}

new FusionSC_Tagline();
