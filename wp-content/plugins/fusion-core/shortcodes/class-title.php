<?php
class FusionSC_Title {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_title-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_title-shortcode-heading', array( $this, 'heading_attr' ) );
		add_filter( 'fusion_attr_title-shortcode-sep', array( $this, 'sep_attr' ) );

		add_shortcode('title', array( $this, 'render' ) );

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
				'content_align'	=> 'left',
				'margin_top'	=> '',
				'margin_bottom'	=> '',
				'sep_color' 	=> '',
				'size'			=> 1,
				'style_tag'		=> '',
				'style_type'	=> $smof_data['title_style_type'],
			), $args
		);

		$defaults['margin_top'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['margin_top'], 'px' );
		$defaults['margin_bottom'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['margin_bottom'], 'px' );

		if( ! $defaults['margin_top'] && isset( $smof_data['title_margin']['top'] ) && $smof_data['title_margin']['top'] ) {
			$defaults['margin_top'] = $smof_data['title_margin']['top'];
		}

		if( ! $defaults['margin_bottom'] && isset( $smof_data['title_margin']['bottom'] ) && $smof_data['title_margin']['bottom'] ) {
			$defaults['margin_bottom'] = $smof_data['title_margin']['bottom'];
		}

		extract( $defaults );

		self::$args = $defaults;

		if ( ! $style_type ||
			 $style_type == 'default'
		) {
			self::$args['style_type'] = $style_type = $smof_data['title_style_type'];
		}

		if ( strpos( $style_type, 'underline' ) !== FALSE ||
			 strpos( $style_type, 'none' ) !== FALSE
		) {

			$html = sprintf( '<div %s><h%s %s>%s</h%s></div>', FusionCore_Plugin::attributes( 'title-shortcode' ), $size,
							 FusionCore_Plugin::attributes( 'title-shortcode-heading' ), do_shortcode( $content ), $size );

		} else {

			if ( self::$args['content_align'] == 'right' ) {

				$html = sprintf( '<div %s><div %s><div %s></div></div><h%s %s>%s</h%s></div>', FusionCore_Plugin::attributes( 'title-shortcode' ),
								FusionCore_Plugin::attributes( 'title-sep-container' ), FusionCore_Plugin::attributes( 'title-shortcode-sep' ), $size,
								FusionCore_Plugin::attributes( 'title-shortcode-heading' ), do_shortcode( $content ), $size );
			} elseif ( self::$args['content_align'] == 'center' ) {

				$html = sprintf( '<div %s><div %s><div %s></div></div><h%s %s>%s</h%s><div %s><div %s></div></div></div>', FusionCore_Plugin::attributes( 'title-shortcode' ),
								 FusionCore_Plugin::attributes( 'title-sep-container title-sep-container-left' ), FusionCore_Plugin::attributes( 'title-shortcode-sep' ), $size,
								 FusionCore_Plugin::attributes( 'title-shortcode-heading' ), do_shortcode( $content ), $size,
								 FusionCore_Plugin::attributes( 'title-sep-container title-sep-container-right' ), FusionCore_Plugin::attributes( 'title-shortcode-sep' ) );

			} else {

				$html = sprintf( '<div %s><h%s %s>%s</h%s><div %s><div %s></div></div></div>', FusionCore_Plugin::attributes( 'title-shortcode' ), $size,
								 FusionCore_Plugin::attributes( 'title-shortcode-heading' ), do_shortcode( $content ), $size,
								 FusionCore_Plugin::attributes( 'title-sep-container' ), FusionCore_Plugin::attributes( 'title-shortcode-sep' ) );
			}

		}

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-title title';
		$attr['style'] = '';

		if( strpos( self::$args['style_type'], 'underline' ) !== false ) {
			$styles = explode( ' ', self::$args['style_type'] );

			foreach ( $styles as $style ) {
				$attr['class'] .= ' sep-' . $style;
			}

			if( self::$args['sep_color'] ) {
				$attr['style'] = sprintf( 'border-bottom-color:%s;', self::$args['sep_color'] );
			}
		} elseif ( strpos( self::$args['style_type'], 'none' ) !== false ) {
			$attr['class'] .= ' fusion-sep-none';
		}

		if ( self::$args['content_align'] == 'center' ) {
			$attr['class'] .= ' fusion-title-center';
		}

		if( self::$args['size'] == '1' ) {
			$title_size = 'one';
		} else if( self::$args['size'] == '2' ) {
			$title_size = 'two';
		} else if( self::$args['size'] == '3' ) {
			$title_size = 'three';
		} else if( self::$args['size'] == '4' ) {
			$title_size = 'four';
		} else if( self::$args['size'] == '5' ) {
			$title_size = 'five';
		} else if( self::$args['size'] == '6' ) {
			$title_size = 'six';
		} else {
			$title_size = 'two';
		}

		$attr['class'] .= ' fusion-title-size-' . $title_size;

		if ( self::$args['margin_top'] ) {
			$attr['style'] .= sprintf( 'margin-top:%s;', self::$args['margin_top'] );
		}

		if ( self::$args['margin_bottom'] ) {
			$attr['style'] .= sprintf( 'margin-bottom:%s;', self::$args['margin_bottom'] );
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function heading_attr() {

		$attr = array();

		$attr['class'] = sprintf( 'title-heading-%s', self::$args['content_align'] );

		if ( self::$args['style_tag'] ) {
			$attr['style'] = self::$args['style_tag'];
		}

		return $attr;

	}

	function sep_attr() {

		$attr = array();

		$attr['class'] = 'title-sep';

		$styles = explode( ' ', self::$args['style_type'] );

		foreach ( $styles as $style ) {
			$attr['class'] .= ' sep-' . $style;
		}

		if( self::$args['sep_color'] ) {
			$attr['style'] = sprintf( 'border-color:%s;', self::$args['sep_color'] );
		}

		return $attr;

	}

}

new FusionSC_Title();
