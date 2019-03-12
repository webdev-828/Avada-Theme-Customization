<?php
class FusionSC_Imageframe {

	private $imageframe_counter = 1;
	private $image_data = false;
	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_imageframe-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_imageframe-shortcode-link', array( $this, 'link_attr' ) );

		add_shortcode( 'imageframe', array( $this, 'render' ) );

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
				'id'					=> '',
				'align' 				=> '',
				'bordercolor' 			=> '',
				'borderradius' 			=> intval( $smof_data['imageframe_border_radius'] ) . 'px',
				'bordersize' 			=> intval( $smof_data['imageframe_border_size'] ) . 'px',
				'gallery_id'			=> '',
				'hide_on_mobile'		=> 'no',
				'lightbox' 				=> 'no',
				'lightbox_image'		=> '',
				'link'					=> '',
				'linktarget'			=> '_self',
				'style' 				=> '',
				'style_type'			=> 'none',	// deprecated
				'stylecolor' 			=> '',
				'animation_type' 		=> '',
				'animation_direction' 	=> 'left',
				'animation_speed' 		=> '',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
				'hover_type'			=> 'none'
			), $args
		);

		$defaults['borderradius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['borderradius'], 'px' );
		$defaults['bordersize'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['bordersize'], 'px');

		if( ! $defaults['style'] ) {
			$defaults['style'] = $defaults['style_type'];
		}

		if( $defaults['borderradius'] && $defaults['style'] == 'bottomshadow' ) {
			$defaults['borderradius'] = '0';
		}

		if( $defaults['borderradius'] == 'round' ) {
			$defaults['borderradius'] = '50%';
		}

		extract( $defaults );

		self::$args = $defaults;


		// Add the needed styles to the img tag
		if ( ! $bordercolor ) {
			$bordercolor = $smof_data['imgframe_border_color'];
		}

		if ( ! $stylecolor ) {
			$stylecolor = $smof_data['imgframe_style_color'];
		}

		$rgb = FusionCore_Plugin::hex2rgb( $stylecolor );
		$border_radius = $img_styles = '';

		if ( $borderradius != '0' &&
			 $borderradius != '0px'
		) {
			$border_radius .= "-webkit-border-radius:{$borderradius};-moz-border-radius:{$borderradius};border-radius:{$borderradius};";
		}

		if ( $border_radius ) {
			$img_styles = sprintf( ' style="%s"', $border_radius );
		}

		$img_classes = 'img-responsive';

		// Get custom classes from the img tag
		preg_match( '/(class=["\'](.*?)["\'])/', $content, $classes );

		if ( ! empty( $classes ) ) {
			$img_classes .= ' ' . $classes[2];
		}

		$img_classes = sprintf( 'class="%s"', $img_classes );

		// Add custom and responsive class and the needed styles to the img tag
		if( ! empty( $classes ) ) {
			$content = str_replace( $classes[0], $img_classes . $img_styles , $content );
		} else {
			$content = str_replace( '/>', $img_classes . $img_styles . '/>', $content );
		}

		// Alt tag
		$alt_tag = $image_url = '';

		preg_match( '/(src=["\'](.*?)["\'])/', $content, $src );
		if ( array_key_exists( '2', $src ) ) {
			
			$image_url = self::$args['pic_link'] = $src[2];
			
			if ( self::$args['lightbox_image'] ) {
				$lightbox_image = self::$args['lightbox_image'];
			} else {
				$lightbox_image = self::$args['pic_link'];
			}
			
			$this->image_data = FusionCore_Plugin::get_attachment_data_from_url( self::$args['pic_link'] );

			if ( $this->image_data ) {
				$content = str_replace( '/>', 'width="' . $this->image_data['width'] . '" height="' . $this->image_data['height'] . '"/>' , $content );

				$alt_tag = sprintf( 'alt="%s"', $this->image_data['alt'] );
			}

			if ( strpos( $content, 'alt=""' ) !== false && $alt_tag ) {
				$content = str_replace( 'alt=""', $alt_tag, $content );
			} elseif ( strpos( $content, 'alt' ) === false && $alt_tag ) {
				$content = str_replace( '/> ', $alt_tag . ' />', $content );
			}
		}

		// Set the lightbox image to the dedicated link if it is set
		if ( $lightbox_image ) {
			self::$args['pic_link'] = $lightbox_image;
		}

		$output = do_shortcode( $content );

		if ( $lightbox == 'yes' || $link ) {
			$output = sprintf( '<a %s>%s</a>', FusionCore_Plugin::attributes( 'imageframe-shortcode-link' ), do_shortcode( $content ) );
		}

		$html = sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'imageframe-shortcode' ), $output );

		if ( $hover_type == 'liftup' ) {
			$liftup_classes = 'imageframe-liftup';
			$liftup_styles = '';
			if ( $border_radius ) {
				$liftup_styles = sprintf( '<style scoped="scoped">.imageframe-liftup.imageframe-%s:before{%s}</style>', $this->imageframe_counter, $border_radius );
				$liftup_classes .= sprintf( ' imageframe-%s', $this->imageframe_counter );
			}

			$html = sprintf( '<div %s>%s%s</div>', FusionCore_Plugin::attributes( $liftup_classes ), $liftup_styles, $html );
		}

		if ( $align == 'center' ) {
			$html = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'imageframe-align-center' ), $html );
		}

		$this->imageframe_counter++;

		return $html;

	}

	function attr() {

		global $smof_data;

		$attr = array();

		$attr['style'] = '';

		$bordercolor = self::$args['bordercolor'];
		$stylecolor = self::$args['stylecolor'];
		$bordersize = self::$args['bordersize'];
		$borderradius = self::$args['borderradius'];
		$style = self::$args['style'];

		// Add the needed styles to the img tag
		if ( ! $bordercolor ) {
			$bordercolor = $smof_data['imgframe_border_color'];
		}

		if ( ! $stylecolor ) {
			$stylecolor = $smof_data['imgframe_style_color'];
		}

		$rgb = FusionCore_Plugin::hex2rgb( $stylecolor );
		$img_styles = '';

		if ( $bordersize != '0' &&
			 $bordersize != '0px'
		) {
			$img_styles .= "border:{$bordersize} solid {$bordercolor};";
		}

		if ( $borderradius != '0' &&
			 $borderradius != '0px'
		) {
			$img_styles .= "-webkit-border-radius:{$borderradius};-moz-border-radius:{$borderradius};border-radius:{$borderradius};";
		}

		if ( $style == 'glow' ) {
			$img_styles .= "-moz-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		}

		if ( $style == 'dropshadow' ) {
			$img_styles .= "-moz-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		}

		if ( $img_styles ) {
			$attr['style'] .= $img_styles;
		}

		$attr['class'] = sprintf( 'fusion-imageframe imageframe-%s imageframe-%s', self::$args['style'], $this->imageframe_counter );

		if( self::$args['style'] == 'bottomshadow' ) {
			$attr['class'] .= ' element-bottomshadow';
		}

		if( self::$args['align'] == 'left' ) {
			$attr['style'] .= 'margin-right:25px;float:left;';
		} elseif( self::$args['align'] == 'right' ) {
			$attr['style'] .= 'margin-left:25px;float:right;';
		}

		if( self::$args['hover_type'] != 'liftup' ) {
			$attr['class'] .= ' hover-type-' . self::$args['hover_type'];
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

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

		if( self::$args['hide_on_mobile'] == 'yes' ) {
			$attr['class'] .= ' fusion-hide-on-mobile';
		}

		return $attr;

	}

	function link_attr() {

		$attr = array();

		if ( self::$args['lightbox'] == 'yes' ) {
			$attr['href'] = self::$args['pic_link'];
			$attr['class'] = 'fusion-lightbox';
			
			if ( self::$args['gallery_id'] || self::$args['gallery_id'] == '0' ) {
				$attr['data-rel'] = 'iLightbox[' . self::$args['gallery_id'] . ']';
			} else {
				$attr['data-rel'] = 'iLightbox[' . substr( md5( self::$args['pic_link'] ), 13 ) . ']';
			}
			
			if ( $this->image_data ) {
				$attr['data-caption'] = $this->image_data['caption'];
				$attr['data-title']	= $this->image_data['title'];
			}			
		} elseif ( self::$args['link'] ) {
			$attr['class'] = 'fusion-no-lightbox';
			$attr['href'] = self::$args['link'];
			$attr['target'] = self::$args['linktarget'];
		}



		return $attr;

	}

}

new FusionSC_Imageframe();