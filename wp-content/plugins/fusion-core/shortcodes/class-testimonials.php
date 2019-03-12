<?php
class FusionSC_Testimonials {

	private $testimonials_counter = 1;

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_testimonials-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-testimonials', array( $this, 'testimonials_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-quote', array( $this, 'quote_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-blockquote', array( $this, 'blockquote_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-review', array( $this, 'review_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-thumbnail', array( $this, 'thumbnail_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-image', array( $this, 'image_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-author', array( $this, 'author_attr' ) );
		add_filter( 'fusion_attr_testimonials-shortcode-pagination', array( $this, 'pagination_attr' ) );

		add_shortcode( 'testimonials', array( $this, 'render_parent' ) );
		add_shortcode( 'testimonial', array( $this, 'render_child' ) );

	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_parent( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class' 			=> '',
				'id' 				=> '',
				'backgroundcolor' 	=> strtolower( $smof_data['testimonial_bg_color'] ),
				'design'			=> 'classic',
				'random'			=> $smof_data['testimonials_random'],
				'textcolor' 		=> strtolower( $smof_data['testimonial_text_color'] ),
			), $args
		);

		if ( $defaults['random'] == 'yes' ) {
			$defaults['random'] = 1;
		} else {
			$defaults['random'] = 0;
		}

		extract( $defaults );

		self::$parent_args = $defaults;

		$styles = sprintf( "<style type='text/css' scoped='scoped'>
		#fusion-testimonials-%s a{border-color: {$textcolor};}#fusion-testimonials-%s a:hover, #fusion-testimonials-%s .activeSlide{background-color: {$textcolor};}
		.fusion-testimonials.%s.fusion-testimonials-%s .author:after{border-top-color:{$backgroundcolor} !important;}</style>",
		$this->testimonials_counter, $this->testimonials_counter, $this->testimonials_counter, $design, $this->testimonials_counter );

		$pagination = '';
		if( self::$parent_args['design'] == 'clean' ) {
			$pagination  = sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'testimonials-shortcode-pagination' ) );
		}

		$html = sprintf( '<div %s>%s<div %s>%s</div>%s</div>', FusionCore_Plugin::attributes( 'testimonials-shortcode' ), $styles,
						 FusionCore_Plugin::attributes( 'testimonials-shortcode-testimonials' ), do_shortcode($content), $pagination );

		$this->testimonials_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-testimonials %s fusion-testimonials-%s', self::$parent_args['design'], $this->testimonials_counter );

		$attr['data-random'] = self::$parent_args['random'];

		if( self::$parent_args['class'] ) {
			$attr['class'] .= ' ' . self::$parent_args['class'];
		}

		if( self::$parent_args['id'] ) {
			$attr['id'] = self::$parent_args['id'];
		}

		return $attr;

	}

	function testimonials_attr() {

		$attr = array();

		$attr['class'] = 'reviews';

		return $attr;

	}

	/**
	 * Render the child shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_child( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'avatar'				=> 'male',
				'company'				=> '',
				'image'					=> '',
				'image_border_radius'	=> '',
				'link'					=> '',
				'name'	 				=> '',
				'target'				=> '_self',

				'gender' 				=> '',	// Deprecated
			), $args
		);

		$defaults['image_border_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['image_border_radius'], 'px' );

		if( $defaults['image_border_radius'] == 'round' ) {
			$defaults['image_border_radius'] = '50%';
		}

		extract( $defaults );

		self::$child_args = $defaults;

		// check for deprecated
		if( $gender ) {
			self::$child_args['avatar'] = $gender;
		}

		if( self::$parent_args['design'] == 'clean' ) {
			$html = $this->render_child_clean( $content );
		} else {
			$html = $this->render_child_classic( $content );
		}

		return $html;

	}

	/* Render classic design */
	private function render_child_classic( $content ) {
		$inner_content = $thumbnail = $pic = '';

		if( self::$child_args['name'] ) {

			if( self::$child_args['avatar'] == 'image' &&
				self::$child_args['image']
			) {

				$image_id = FusionCore_Plugin::get_attachment_id_from_url( self::$child_args['image'] );
				self::$child_args['alt'] = '';
				if( $image_id ) {
					self::$child_args['alt'] = get_post_field( 'post_excerpt', $image_id );
				}

				$pic = sprintf( '<img %s />', FusionCore_Plugin::attributes( 'testimonials-shortcode-image' ) );
			}

			if( self::$child_args['avatar'] == 'image' &&
				! self::$child_args['image']
			) {
				self::$child_args['avatar'] = 'none';
			}

			if( self::$child_args['avatar'] != 'none' ) {
				$thumbnail = sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'testimonials-shortcode-thumbnail' ), $pic );
			}

			$inner_content .= sprintf( '<div %s>%s<span %s>', FusionCore_Plugin::attributes( 'testimonials-shortcode-author' ), $thumbnail, FusionCore_Plugin::attributes( 'company-name' ) );

			if( self::$child_args['name'] ) {
				$inner_content .= sprintf( '<strong>%s</strong>', self::$child_args['name'] );
			}

			if ( self::$child_args['name'] && self::$child_args['company'] ) {
				$inner_content .= ', ';
			}

			if( self::$child_args['company'] ) {

				if( ! empty( self::$child_args['link'] ) &&
					self::$child_args['link']
				) {

					$inner_content .= sprintf( '<a href="%s" target="%s">%s</a>', self::$child_args['link'], self::$child_args['target'], sprintf( '<span>%s</span>', self::$child_args['company'] ) );

				} else {

					$inner_content .= sprintf( '<span>%s</span>', self::$child_args['company'] );

				}

			}

			$inner_content .= '</span></div>';
		}

		$html = sprintf( '<div %s><blockquote><q %s>%s</q></blockquote>%s</div>', FusionCore_Plugin::attributes( 'testimonials-shortcode-review' ),
						 FusionCore_Plugin::attributes( 'testimonials-shortcode-quote' ), do_shortcode( $content ), $inner_content );

		return $html;

	}

	/* Render clean design */
	private function render_child_clean( $content ) {
		$thumbnail = $pic = $author = '';

		if( self::$child_args['avatar'] == 'image' &&
			self::$child_args['image']
		) {

			$image_id = FusionCore_Plugin::get_attachment_id_from_url( self::$child_args['image'] );
			self::$child_args['alt'] = '';
			if( $image_id ) {
				self::$child_args['alt'] = get_post_field( 'post_excerpt', $image_id );
			}

			$pic = sprintf( '<img %s />', FusionCore_Plugin::attributes( 'testimonials-shortcode-image' ) );
		}

		if( self::$child_args['avatar'] == 'image' &&
			! self::$child_args['image']
		) {
			self::$child_args['avatar'] = 'none';
		}

		if( self::$child_args['avatar'] != 'none' ) {
			$thumbnail = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'testimonials-shortcode-thumbnail' ), $pic );
		}

		$author .= sprintf( '<div %s><span %s>', FusionCore_Plugin::attributes( 'testimonials-shortcode-author' ), FusionCore_Plugin::attributes( 'company-name' ) );

		if ( self::$child_args['name'] ) {
			$author .= sprintf( '<strong>%s</strong>', self::$child_args['name'] );
		}

		if ( self::$child_args['name'] && self::$child_args['company'] ) {
			$author .= ', ';
		}

		if( self::$child_args['company'] ) {

			if( ! empty( self::$child_args['link'] ) &&
				self::$child_args['link']
			) {

				$author .= sprintf( '<a href="%s" target="%s">%s</a>', self::$child_args['link'], self::$child_args['target'], sprintf( '<span>%s</span>', self::$child_args['company'] ) );

			} else {

				$author .= sprintf( '<span>%s</span>', self::$child_args['company'] );

			}

		}

		$author .= '</span></div>';


		$html = sprintf( '<div %s>%s<blockquote %s><q %s>%s</q></blockquote>%s</div>', FusionCore_Plugin::attributes( 'testimonials-shortcode-review' ), $thumbnail,
						 FusionCore_Plugin::attributes( 'testimonials-shortcode-blockquote' ), FusionCore_Plugin::attributes( 'testimonials-shortcode-quote' ),
						 do_shortcode( $content ), $author );

		return $html;
	}

	function blockquote_attr() {

		$attr = array();

		$attr['style'] = '';

		if( self::$parent_args['design'] == 'clean' && ( 'transparent' == self::$parent_args['backgroundcolor'] || '0' == Avada_Color::get_alpha_from_rgba( self::$parent_args['backgroundcolor'] ) ) ) {
			$attr['style'] .= 'margin: -25px;';
		}

		$attr['style'] .= sprintf( 'background-color:%s;', self::$parent_args['backgroundcolor'] );

		return $attr;

	}

	function quote_attr() {

		$attr = array();

		$attr['style'] = sprintf( 'background-color:%s;', self::$parent_args['backgroundcolor'] );
		$attr['style'] .= sprintf( 'color:%s;', self::$parent_args['textcolor'] );

		return $attr;

	}

	function review_attr() {

		$attr = array();

		$attr['class'] = 'review ';

		if( self::$child_args['avatar'] == 'none' ) {
			$attr['class'] .= 'no-avatar';
		} else if( self::$child_args['avatar'] == 'image' ) {
			$attr['class'] .= 'avatar-image';
		} else {
	   		$attr['class'] .= self::$child_args['avatar'];
		}

		return $attr;

	}

	function thumbnail_attr() {

		$attr = array();

		$attr['class'] = 'testimonial-thumbnail';

		if( self::$child_args['avatar'] != 'image' ) {
			$attr['class'] .= ' doe';
			$attr['style'] = sprintf( 'color:%s;', self::$parent_args['textcolor'] );
		}

		return $attr;

	}

	function image_attr() {

		$attr = array();

		$attr['class'] = 'testimonial-image';
		$attr['src'] = self::$child_args['image'];
		$attr['alt'] = self::$child_args['alt'];

		if( self::$child_args['avatar'] == 'image' ) {
			$attr['style'] = sprintf( '-webkit-border-radius: %s;-moz-border-radius: %s;border-radius: %s;',
									  self::$child_args['image_border_radius'], self::$child_args['image_border_radius'],  self::$child_args['image_border_radius'] );
		}

		return $attr;

	}

	function author_attr() {

		$attr = array();

		$attr['class'] = 'author';
		$attr['style'] = sprintf( 'color:%s;', self::$parent_args['textcolor'] );

		return $attr;

	}

	function pagination_attr() {

		$attr = array();

		$attr['class'] = 'testimonial-pagination';
		$attr['id'] = sprintf( 'fusion-testimonials-%s', $this->testimonials_counter );

		return $attr;

	}

}

new FusionSC_Testimonials();
