<?php
class FusionSC_RecentPosts {

	public static $args;


	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_recentposts-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_recentposts-shortcode-section', array( $this, 'section_attr' ) );
		add_filter( 'fusion_attr_recentposts-shortcode-column', array( $this, 'column_attr' ) );		
		add_filter( 'fusion_attr_recentposts-shortcode-slideshow', array( $this, 'slideshow_attr' ) );
		add_filter( 'fusion_attr_recentposts-shortcode-img', array( $this, 'img_attr' ) );
		add_filter( 'fusion_attr_recentposts-shortcode-img-link', array( $this, 'link_attr' ) );

		add_shortcode( 'recent_posts', array( $this, 'render' ) );

	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class' 				=> '',
				'id' 					=> '',
				'cat_id' 				=> '',
				'cat_slug' 				=> '',
				'columns' 				=> 3,
				'excerpt' 				=> 'no',
				'exclude_cats' 			=> '',
				'excerpt_length' 		=> '',
				'excerpt_words' 		=> '15', // deprecated
				'hover_type'			=> 'none',
				'layout' 				=> 'default',
				'meta'					=> 'yes',
				'number_posts' 			=> 4,
				'offset'				=> '',
				'strip_html' 			=> 'yes',
				'title'					=> 'yes',
				'thumbnail'				=> 'yes',
				'animation_direction' 	=> 'left',
				'animation_speed' 		=> '',
				'animation_type' 		=> '',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
			), $args
		);
		
		if( $defaults['columns'] > 6 ) {
			$defaults['columns'] = 6;
		}		

		( $defaults['strip_html'] == 'yes' || $defaults['strip_html'] == 'true' ) ? ( $defaults['strip_html'] = true ) : ( $defaults['strip_html'] = false );
	
		if( $defaults['number_posts'] ) {
			$defaults['posts_per_page'] = $defaults['number_posts'];
		}
		
		if( $defaults['excerpt_length'] || 
			$defaults['excerpt_length'] === '0' 
		) {
			$defaults['excerpt_words'] = $defaults['excerpt_length'];
		}		
	
		//check for cats to exclude; needs to be checked via exclude_cats param and '-' prefixed cats on cats param
		//exclution via exclude_cats param 
		$cats_to_exclude = explode( ',' , $defaults['exclude_cats'] );
		if( $cats_to_exclude ) {
			foreach( $cats_to_exclude as $cat_to_exclude ) {
				$idObj = get_category_by_slug( $cat_to_exclude );
				if( $idObj ) {
					$cats_id_to_exclude[] = $idObj->term_id;
				}
			}
			if( isset( $cats_id_to_exclude ) && $cats_id_to_exclude ) {
				$defaults['category__not_in'] = $cats_id_to_exclude;
			}
		}

		//setting up cats to be used and exclution using '-' prefix on cats param; transform slugs to ids
		$cat_ids = '';
		$categories = explode( ',' , $defaults['cat_slug'] );
		if ( isset( $categories ) && $categories ) {
			foreach ( $categories as $category ) {
				if( $category ) {
					$cat_obj = get_category_by_slug( $category );
					if( isset( $cat_obj->term_id ) ) {
						if(strpos( $category, '-' ) === 0 ) {
							$cat_ids .= '-' .$cat_obj->cat_ID .',';
						} else {
							$cat_ids .= $cat_obj->cat_ID .',';
						}
					}
				}
			}
		}
		$defaults['cat'] = substr( $cat_ids, 0, -1 );	
		
		$defaults['cat'] .= $defaults['cat_id'];

		$items = '';

		$args = array(
			'posts_per_page' => $defaults['number_posts'],
			'ignore_sticky_posts' => 1
		);
		
		if ( $defaults['offset'] ) {
			$args['offset'] =  $defaults['offset'];
		}

		if( $defaults['cat'] ) {
			$args['cat'] = $defaults['cat'];
		}
		
		if( isset( $defaults['category__not_in'] ) && is_array( $defaults['category__not_in'] ) ) {
			$args['category__not_in'] = $defaults['category__not_in'];
		}

		extract( $defaults );

		self::$args = $defaults;		

		$recent_posts = new WP_Query( $args );
		
		$count = 1;
		
		while( $recent_posts->have_posts() ) {
			$recent_posts->the_post();

			$attachment = $date_box = $slideshow = $slides = $content = '';
			
			if( $layout == 'date-on-side' ) {
				
				switch( get_post_format() ) {
					case 'gallery':
						$format_class = 'images';
						break;
					case 'link':
						$format_class = 'link';
						break;
					case 'image':
						$format_class = 'image';
						break;
					case 'quote':
						$format_class = 'quotes-left';
						break;
					case 'video':
						$format_class = 'film';
						break;
					case 'audio':
						$format_class = 'headphones';
						break;
					case 'chat':
						$format_class = 'bubbles';
						break;
					default:
						$format_class = 'pen';
						break;
				}				

				$date_box = sprintf( '<div %s><div %s><span %s>%s</span><span %s>%s</span></div><div %s><i %s></i></div></div>', FusionCore_Plugin::attributes( 'fusion-date-and-formats' ), 
									 FusionCore_Plugin::attributes( 'fusion-date-box updated' ), FusionCore_Plugin::attributes( 'fusion-date' ), get_the_time( $smof_data['alternate_date_format_day'] ), 
									 FusionCore_Plugin::attributes( 'fusion-month-year' ), get_the_time( $smof_data['alternate_date_format_month_year'] ), FusionCore_Plugin::attributes( 'fusion-format-box' ), 
							 		 FusionCore_Plugin::attributes( 'fusion-icon-' . $format_class ) );
			}
							  
			if( $thumbnail == 'yes' &&
				$layout != 'date-on-side' &&
				! post_password_required( get_the_ID() )
			) {
				
				if( $layout == 'default' ) {
					$image_size = 'recent-posts';
				} elseif( $layout == 'thumbnails-on-side' ) {
					$image_size = 'portfolio-five';
				}
			
				if( has_post_thumbnail() || 
					get_post_meta(get_the_ID(), 'pyre_video', true) 
				) {
					if( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
						$slides .= sprintf( '<li><div %s>%s</div></li>', FusionCore_Plugin::attributes( 'full-video' ), get_post_meta(get_the_ID(), 'pyre_video', true) );
					}

					if( has_post_thumbnail() ) {
						$attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), $image_size );
						$attachment_img_tag = wp_get_attachment_image( get_post_thumbnail_id(), $image_size );
						
						$attachment_img_tag_custom = sprintf( '<img src="%s" alt="%s" />', $attachment_image[0], get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) );
						$full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						$attachment_data = wp_get_attachment_metadata( get_post_thumbnail_id() );					
						$attachment = get_post(get_post_thumbnail_id());

						$slides .= sprintf( '<li><a href="%s" %s>%s</a></li>', get_permalink( get_the_ID() ),
											FusionCore_Plugin::attributes( 'recentposts-shortcode-img-link' ), 
											$attachment_img_tag_custom ) ;
					}

					$i = 2;
					while( $i <= $smof_data['posts_slideshow_number'] ) {
						$attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'post' );
						if( $attachment_new_id ) {
							$attachment_image = wp_get_attachment_image_src( $attachment_new_id, $image_size );
							$attachment_img_tag = wp_get_attachment_image( $attachment_new_id, $image_size );
							$attachment_img_tag_custom = sprintf( '<img src="%s" alt="%s" />', $attachment_image[0], get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true ) );
							$full_image = wp_get_attachment_image_src( $attachment_new_id, 'full' );
							$attachment_data = wp_get_attachment_metadata( $attachment_new_id );

							$slides .= sprintf( '<li><a href="%s" %s>%s</a></li>', get_permalink( get_the_ID() ), 
											FusionCore_Plugin::attributes( 'recentposts-shortcode-img-link' ),
											$attachment_img_tag_custom ) ;									
						}
						$i++; 
					}

					$slideshow = sprintf( '<div %s><ul %s>%s</ul></div>', FusionCore_Plugin::attributes( 'recentposts-shortcode-slideshow' ), FusionCore_Plugin::attributes( 'slides' ), $slides );
				}
			}

			if( $title == 'yes' ) {
				$content .= avada_render_rich_snippets_for_pages( false );
				$entry_title = '';
				if( $smof_data['disable_date_rich_snippet_pages'] ) {
					$entry_title = 'entry-title';
				}
				$content .= sprintf( '<h4 class="%s"><a href="%s">%s</a></h4>', $entry_title, get_permalink( get_the_ID() ), get_the_title() );
			} else {
				$content .= avada_render_rich_snippets_for_pages();
			}

			if( $meta == 'yes' ) {

				$comments = $comments_link = '';
				ob_start();
				comments_popup_link( __( '0 Comments', 'fusion-core' ), __( '1 Comment', 'fusion-core' ), '% ' . __( 'Comments', 'fusion-core' ) );
				$comments_link = ob_get_contents();
				ob_get_clean();				
				
				$comments = sprintf( '<span %s>|</span><span>%s</span>', FusionCore_Plugin::attributes( 'meta-separator' ), $comments_link );	

				$content .= sprintf( '<p %s><span><span %s>%s</span></span>%s</p>', FusionCore_Plugin::attributes( 'meta' ), FusionCore_Plugin::attributes( 'date' ), 
									 get_the_time( $smof_data['date_format'], get_the_ID() ), $comments);

			}

			if( $excerpt == 'yes' ) {		
				$content .= fusion_get_post_content( '', 'yes', $excerpt_words, $strip_html );
			}
						
			if( $count == self::$args['columns'] ) {
				$count = 0;
				$items .= sprintf( '<div %s>%s%s<div %s>%s</div></div><div class="fusion-clearfix"></div>', FusionCore_Plugin::attributes( 'recentposts-shortcode-column' ), $date_box, $slideshow, 
							   FusionCore_Plugin::attributes( 'recent-posts-content' ), $content );
			} else {
				$items .= sprintf( '<div %s>%s%s<div %s>%s</div></div>', FusionCore_Plugin::attributes( 'recentposts-shortcode-column' ), $date_box, $slideshow, 
							   FusionCore_Plugin::attributes( 'recent-posts-content' ), $content );
			}

			$count++;
			
		}
	
		$html = sprintf( '<div %s><section %s>%s</section></div>', FusionCore_Plugin::attributes( 'recentposts-shortcode' ), 
					  FusionCore_Plugin::attributes( 'recentposts-shortcode-section' ), $items );

		wp_reset_query();

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-recent-posts avada-container layout-%s layout-columns-%s', self::$args['layout'], self::$args['columns'] );

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}
	
	function section_attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-columns columns fusion-columns-%s columns-%s', self::$args['columns'], self::$args['columns'] );

		return $attr;

	}
	
	function column_attr() {

		$attr = array();
		
		if( self::$args['columns'] ) {
			$columns = 12 / self::$args['columns'];
		} else {
			$columns = 3;
		}
		
		$attr['class'] = sprintf( 'fusion-column column col col-lg-%s col-md-%s col-sm-%s', $columns, $columns, $columns );
		
		if( self::$args['columns'] == '5'  ) {
			$attr['class'] = 'fusion-column column col-lg-2 col-md-2 col-sm-2';
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

		return $attr;

	}	
	
	function slideshow_attr() {

		$attr = array();

		$attr['class'] = 'fusion-flexslider flexslider';

		 if( self::$args['layout'] == 'thumbnails-on-side' ) {
		 	$attr['class'] .= ' floated-slideshow';
		 }

		if( self::$args['hover_type'] ) {
			$attr['class'] .= ' flexslider-hover-type-' . self::$args['hover_type'];
		}
		
		return $attr;

	} 
	
	function img_attr( $args ) {

		$attr = array();

		$attr['src'] = $args['src'];

		if( $args['alt'] ) {
			$attr['alt'] = $args['alt'];
		}

		return $attr;

	}	

	function link_attr( $args ) {

		$attr = array();

		if( self::$args['hover_type'] ) {
			$attr['class'] = 'hover-type-' . self::$args['hover_type'];
		}

		return $attr;

	}	
}

new FusionSC_RecentPosts();