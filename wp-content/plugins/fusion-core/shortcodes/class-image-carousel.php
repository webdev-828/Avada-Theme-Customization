<?php
class FusionSC_ImageCarousel {

	private $image_carousel_counter = 1;

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_image-carousel-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_image-carousel-shortcode-carousel', array( $this, 'carousel_attr' ) );
		add_filter( 'fusion_attr_image-carousel-shortcode-slide-link', array( $this, 'slide_link_attr' ) );
		add_filter( 'fusion_attr_fusion-image-wrapper', array( $this, 'image_wrapper' ) );
		//add_filter( 'fusion_attr_fusion-nav-prev', array( $this, 'fusion_nav_prev' ) );
		//add_filter( 'fusion_attr_fusion-nav-next', array( $this, 'fusion_nav_next' ) );

		add_shortcode( 'images', array( $this, 'render_parent' ) );
		add_shortcode( 'image', array( $this, 'render_child' ) );

		add_shortcode( 'clients', array( $this, 'render_parent' ) );
		add_shortcode( 'client', array( $this, 'render_child' ) );


	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	Shortcode paramters
	 * @param  string $content Content between shortcode
	 *
	 * @return string		  HTML output
	 */
	function render_parent( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'				=> '',
				'id'				=> '',
				'autoplay'			=> 'no',
				'border'			=> 'yes',
				'columns'			=> '5',
				'column_spacing'	=> '13',
				'lightbox'			=> 'no',
				'mouse_scroll'		=> 'no',
				'picture_size' 		=> 'fixed',
				'scroll_items'		=> '',
				'show_nav'			=> 'yes',
				'hover_type'		=> 'none'
		), $args );

		$defaults['column_spacing'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['column_spacing'], '' );

		extract( $defaults );

		self::$parent_args = $defaults;

		$html = sprintf( '<div %s>', FusionCore_Plugin::attributes( 'image-carousel-shortcode' ) );
			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'image-carousel-shortcode-carousel' ) );
				$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-carousel-positioner' ) );
					// The main carousel
					$html .= sprintf( '<ul %s>', FusionCore_Plugin::attributes( 'fusion-carousel-holder' ) );
						$html .= do_shortcode( $content );
					$html .= '</ul>';

					// Check if navigation should be shown
					if ( $show_nav == 'yes' ) {
						$html .= sprintf( '<div %s><span %s></span><span %s></span></div>', FusionCore_Plugin::attributes( 'fusion-carousel-nav' ),
										  FusionCore_Plugin::attributes( 'fusion-nav-prev' ), FusionCore_Plugin::attributes( 'fusion-nav-next' ) );
					}
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';

		$this->image_carousel_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-image-carousel fusion-image-carousel-' . self::$parent_args['picture_size'];

		if( self::$parent_args['lightbox'] == 'yes' ) {
		  $attr['class'] .= ' lightbox-enabled';
		}

		if( self::$parent_args['border'] == 'yes' ) {
		  $attr['class'] .= ' fusion-carousel-border';
		}

		if( self::$parent_args['class'] ) {
			$attr['class'] .= ' ' . self::$parent_args['class'];
		}

		if( self::$parent_args['id'] ) {
			$attr['id'] = self::$parent_args['id'];
		}

		return $attr;

	}

	function carousel_attr() {

		$attr['class'] = 'fusion-carousel';

		$attr['data-autoplay'] = self::$parent_args['autoplay'];
		$attr['data-columns'] = self::$parent_args['columns'];
		$attr['data-itemmargin'] = self::$parent_args['column_spacing'];
		$attr['data-itemwidth'] = 180;
		$attr['data-touchscroll'] = self::$parent_args['mouse_scroll'];
		$attr['data-imagesize'] = self::$parent_args['picture_size'];
		$attr['data-scrollitems'] = self::$parent_args['scroll_items'];

		return $attr;

	}


	/**
	 * Render the child shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 *
	 * @return string		  HTML output
	 */
	function render_child( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'alt'			=> '',
				'image'	  		=> '',
				'link'	   		=> '',
				'linktarget' 	=> '_self',
			), $args
		);

		extract( $defaults );

		self::$child_args = $defaults;

		$image_data = FusionCore_Plugin::get_attachment_data_from_url( $image );

		$width = $height = '';
		if( is_array( $image_data ) && $image_data ) {
			$width = $image_data['width'];
			$height = $image_data['height'];
		}		

		if( ! $alt && empty( $alt ) && $image_data ) {
			self::$child_args['alt'] = $alt = $image_data['alt'];
		}

		$output = sprintf( '<img src="%s" width="%s" height="%s" alt="%s" />', $image, $width, $height, $alt );

		if( self::$parent_args['mouse_scroll'] == 'no' &&
			( $link || self::$parent_args['lightbox'] == 'yes' )
		) {
			$output = sprintf( '<a %s>%s</a>', FusionCore_Plugin::attributes( 'image-carousel-shortcode-slide-link' ), $output );
		}

		$html = sprintf( '<li %s><div %s><div %s>%s</div></div></li>', FusionCore_Plugin::attributes( 'fusion-carousel-item'), FusionCore_Plugin::attributes( 'fusion-carousel-item-wrapper'),
						 FusionCore_Plugin::attributes( 'fusion-image-wrapper' ), $output );
		return $html;

	}

	function slide_link_attr() {

		$attr = array();

		if( self::$parent_args['lightbox'] == 'yes' ) {

		  	if( ! self::$child_args['link'] ) {
		  		self::$child_args['link'] = self::$child_args['image'];
		  	}

		  	$attr['data-rel'] = sprintf( 'iLightbox[gallery_image_%s]', $this->image_carousel_counter );

			$image_data = FusionCore_Plugin::get_attachment_data_from_url( self::$child_args['image'] );

			if ( $image_data ) {
				$attr['data-caption'] = $image_data['caption'];
				$attr['data-title'] = $image_data['title'];
			}
		}

		$attr['href'] = self::$child_args['link'];

		$attr['target'] = self::$child_args['linktarget'];

		return $attr;

	}

	function image_wrapper() {

		$attr = array();

		$attr['class'] = 'fusion-image-wrapper';

		if( self::$parent_args['hover_type'] ) {
			$attr['class'] .= ' hover-type-' . self::$parent_args['hover_type'];
		}

		return $attr;

	}

	function fusion_nav_prev() {

		$attr = array();

		$attr['class'] = 'fusion-nav-prev fusion-icon-left';

		return $attr;

	}

	function fusion_nav_next() {

		$attr = array();

		$attr['class'] = 'fusion-nav-next  fusion-icon-right';

		return $attr;

	}

}

new FusionSC_ImageCarousel();