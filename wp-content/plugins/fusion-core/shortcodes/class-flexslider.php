<?php
class FusionSC_Flexslider {

	private $flex_counter = 1;

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_flexslider-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_flexslider-shortcode-slides-container', array( $this, 'slides_container_attr' ) );
		add_filter( 'fusion_attr_flexslider-shortcode-caption', array( $this, 'caption_attr' ) );
		add_filter( 'fusion_attr_flexslider-shortcode-title-container', array( $this, 'title_container_attr' ) );
		add_filter( 'fusion_attr_flexslider-shortcode-thumbnails', array( $this, 'thumbnails_attr' ) );

		add_shortcode( 'flexslider', array( $this, 'render' ) );
		add_shortcode( 'postslider', array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '' ) {

		$defaults =	FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'		=> '',
				'id' 		=> '',
				'category'	=> '',
				'excerpt' 	=> '35',
				//'group' 	=> '', not yet used
				'layout' 	=> 'attachments',
				'lightbox' 	=> 'yes',
				'limit' 	=> '3',
				'post_id' 	=> '',
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$thumbnails = '';

		if( $layout == 'attachments' ) {
			$slider = $this->attachments();
			$thumbnails = $this->get_attachments_thumbnails();
		} else if( $layout == 'posts' ) {
			$slider = $this->posts();
		} else if( $layout == 'posts-with-excerpt' ) {
			$slider = $this->posts_excerpt();
		} else {
			//$slider = $this->default_layout();
			$slider = '';
		}

		$slides_html = sprintf( '<ul %s>%s</ul>', FusionCore_Plugin::attributes( 'flexslider-shortcode-slides-container' ), $slider );


		$html = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flexslider-shortcode' ), $slides_html );

		if( $layout == 'attachments' ) {
			$thumbnails_html = '';
			$html .= sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flexslider-shortcode-thumbnails' ), $thumbnails_html );
		}

		$this->flex_counter++;

		return $html;

	}

	/**
	 * Default layout of Flexslider
	 *
	 * @return string HTML for default layout slides
	 */
	function default_layout() {

		if( self::$args['group'] ) {

			$html = '';

			$group = explode( ',', self::$args['group'] );

			$query = new WP_Query( array(
				'post_type' 	 => 'slide',
				'posts_per_page' => self::$args['limit'],
				'tax_query' 	 => array(
					array(
						'taxonomy' => 'slide-page',
						'field'	=> 'slug',
						'terms'	=> $group,
					),
				),
			) );

			if( $query->have_posts() ):

				while( $query->have_posts() ): $query->the_post();

					$meta = get_post_meta( get_the_ID(), 'smof_data', true );
					$caption = '';

					if( isset( $meta['caption'] ) && $meta['caption'] ) {
						$caption = sprintf( '<p %s>%s</p>', FusionCore_Plugin::attributes( 'flexslider-shortcode-caption' ), $meta['caption'] );
					}

					$html .= sprintf( '<li>%s</li>', fusion_get_post_content( '', 'yes', self::$args['excerpt'], true ) . $caption );

				endwhile;

			else:
			endif;

			wp_reset_query();

			return $html;

		}

	}

	function attachments() {

		$html = '';

		if( ! self::$args['post_id'] ) {
			self::$args['post_id'] = get_the_ID();
		}

		$query = get_posts( array(
			'post_type' 	 => 'attachment',
			'posts_per_page' => self::$args['limit'],
			'post_status'	=> null,
			'post_parent' 	 => self::$args['post_id'],
			'orderby'		 => 'menu_order',
			'order' 		 => 'ASC',
			'post_mime_type' => 'image',
			'exclude' 		 => get_post_thumbnail_id()
		) );

		if( $query ):

			foreach( $query as $attachment ):

				$image = wp_get_attachment_url( $attachment->ID );
				$title = get_post_field( 'post_title', $attachment->ID );
				$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				$thumb = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
				$caption = get_post_field( 'post_excerpt', $attachment->ID );

				$image_output = sprintf( '<img src="%s" alt="%s" />', $image, $alt );
				$output = $image_output;

				if( self::$args['lightbox'] == 'yes' ) {
					$output = sprintf( '<a href="%s" data-title="%s" data-caption="%s" title="%s" data-rel="iLightbox[flex_%s]">%s</a>', $image, $title, $caption, $title, $this->flex_counter, $image_output );
				}

				$html .= sprintf( '<li data-thumb="' . $thumb[0] . '">%s</li>', $output );

			endforeach;

		endif;

		wp_reset_query();

		return $html;

	}

	function get_attachments_thumbnails() {

		$html = '';

		if( ! self::$args['post_id'] ) {
			self::$args['post_id'] = get_the_ID();
		}

		$query = get_posts( array(
			'post_type' 	 => 'attachment',
			'posts_per_page' => self::$args['limit'],
			'post_status'	=> null,
			'post_parent' 	 => self::$args['post_id'],
			'orderby'		 => 'menu_order',
			'order' 		 => 'ASC',
			'post_mime_type' => 'image',
			'exclude' 		 => get_post_thumbnail_id()
		) );

		if( $query ):

			foreach( $query as $attachment ):

				$image = wp_get_attachment_url( $attachment->ID );
				$title = get_post_field( 'post_excerpt', $attachment->ID );
				$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				$thumb = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );

				$image_output = sprintf( '<img src="%s" alt="%s" />', $thumb[0], $alt );
				$output = $image_output;

				$html .= sprintf( '<li>%s</li>', $output );

			endforeach;

		endif;

		wp_reset_query();

		return $html;

	}

	function posts() {

		$html = '';

		$args = array(
			'posts_per_page' => self::$args['limit'],
			'meta_query' 	 => array(
				array(
					'key' => '_thumbnail_id'
				)
			),
		);

		if( self::$args['post_id'] ) {
			$post_ids = explode( ',', self::$args['post_id'] );
			$args['post__in'] = $post_ids;
		}

		if( self::$args['category'] ) {
			$args['category_name'] = self::$args['category'];
		}

		$query = new WP_Query( $args );

		if( $query->have_posts() ):

			while( $query->have_posts() ): $query->the_post();

				$image = wp_get_attachment_url( get_post_thumbnail_id() );
				$title = get_post_field( 'post_excerpt', get_post_thumbnail_id() );
				$alt = the_title_attribute( 'echo=0' );

				$image_output = sprintf( '<img src="%s" alt="%s" />', $image, $alt );

				$link_output = sprintf( '<a href="%s">%s</a>', get_permalink(), $image_output );

				$title = sprintf( '<h2><a href="%s">%s</a></h2>', get_permalink(), get_the_title() );

				$container = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flexslider-shortcode-title-container' ), $title );

				$html .= sprintf( '<li>%s</li>', $link_output . $container );

			endwhile;

		else:
		endif;

		wp_reset_query();

		return $html;

	}

	function posts_excerpt() {


		$html = '';

		$args = array(
			'posts_per_page' => self::$args['limit'],
			'meta_query' 	 => array(
				array(
					'key' => '_thumbnail_id'
				)
			),
		);

		if( self::$args['post_id'] ) {
			$post_ids = explode( ',', self::$args['post_id'] );
			$args['post__in'] = $post_ids;
		}

		if( self::$args['category'] ) {
			$args['category_name'] = self::$args['category'];
		}

		$query = new WP_Query( $args );

		if( $query->have_posts() ):

			while( $query->have_posts() ): $query->the_post();

				$image = wp_get_attachment_url( get_post_thumbnail_id() );
				$title = get_post_field( 'post_excerpt', get_post_thumbnail_id() );
				$alt = get_the_title();

				$image_output = sprintf( '<img src="%s" alt="%s" />', $image, get_the_title() );

				$link_output = sprintf( '<a href="%s">%s</a>', get_permalink(), $image_output );

				$title = sprintf( '<h2><a href="%s">%s</a></h2>', get_permalink(), get_the_title() );
				$excerpt = sprintf( '%s', fusion_get_post_content( '', 'yes', self::$args['excerpt'], true ) );

				$container = sprintf( '<div %s><div %s>%s</div></div>', FusionCore_Plugin::attributes( 'flexslider-shortcode-title-container' ), FusionCore_Plugin::attributes( 'excerpt-container' ), $title . $excerpt );

				$html .= sprintf( '<li>%s</li>', $link_output . $container );

			endwhile;

		else:
		endif;

		wp_reset_query();

		return $html;

	}

	function attr() {

		$attr['class'] = 'fusion-flexslider fusion-flexslider-loading flexslider-' . self::$args['layout'];

		if( self::$args['lightbox'] == 'yes' && self::$args['layout'] == 'attachments' ) {
			$attr['class'] .= ' flexslider-lightbox';
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function slides_container_attr() {

		$attr['class'] = 'slides';

		return $attr;

	}

	function caption_attr() {

		$attr['class'] = 'flex-caption';

		return $attr;

	}

	function title_container_attr() {

		$attr['class'] = 'slide-excerpt';

		return $attr;

	}

	function thumbnails_attr() {

		$attr['class'] = 'flexslider';

		if( self::$args['layout'] == 'attachments' ) {
			$attr['class'] .= ' fat';
		}

		return $attr;

	}

}

new FusionSC_Flexslider();
